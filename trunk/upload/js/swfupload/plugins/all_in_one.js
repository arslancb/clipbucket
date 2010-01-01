/*
	Queue Plug-in
	
	Features:
		*Adds a cancelQueue() method for cancelling the entire queue.
		*All queued files are uploaded when startUpload() is called.
		*If false is returned from uploadComplete then the queue upload is stopped.
		 If false is not returned (strict comparison) then the queue upload is continued.
		*Adds a QueueComplete event that is fired when all the queued files have finished uploading.
		 Set the event handler with the queue_complete_handler setting.
		
	*/

var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.queue = {};
	
	SWFUpload.prototype.initSettings = (function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}
			
			this.queueSettings = {};
			
			this.queueSettings.queue_cancelled_flag = false;
			this.queueSettings.queue_upload_count = 0;
			
			this.queueSettings.user_upload_complete_handler = this.settings.upload_complete_handler;
			this.queueSettings.user_upload_start_handler = this.settings.upload_start_handler;
			this.settings.upload_complete_handler = SWFUpload.queue.uploadCompleteHandler;
			this.settings.upload_start_handler = SWFUpload.queue.uploadStartHandler;
			
			this.settings.queue_complete_handler = this.settings.queue_complete_handler || null;
		};
	})(SWFUpload.prototype.initSettings);

	SWFUpload.prototype.startUpload = function (fileID) {
		this.queueSettings.queue_cancelled_flag = false;
		this.callFlash("StartUpload", [fileID]);
	};

	SWFUpload.prototype.cancelQueue = function () {
		this.queueSettings.queue_cancelled_flag = true;
		this.stopUpload();
		
		var stats = this.getStats();
		while (stats.files_queued > 0) {
			this.cancelUpload();
			stats = this.getStats();
		}
	};
	
	SWFUpload.queue.uploadStartHandler = function (file) {
		var returnValue;
		if (typeof(this.queueSettings.user_upload_start_handler) === "function") {
			returnValue = this.queueSettings.user_upload_start_handler.call(this, file);
		}
		
		// To prevent upload a real "FALSE" value must be returned, otherwise default to a real "TRUE" value.
		returnValue = (returnValue === false) ? false : true;
		
		this.queueSettings.queue_cancelled_flag = !returnValue;

		return returnValue;
	};
	
	SWFUpload.queue.uploadCompleteHandler = function (file) {
		var user_upload_complete_handler = this.queueSettings.user_upload_complete_handler;
		var continueUpload;
		
		if (file.filestatus === SWFUpload.FILE_STATUS.COMPLETE) {
			this.queueSettings.queue_upload_count++;
		}

		if (typeof(user_upload_complete_handler) === "function") {
			continueUpload = (user_upload_complete_handler.call(this, file) === false) ? false : true;
		} else if (file.filestatus === SWFUpload.FILE_STATUS.QUEUED) {
			// If the file was stopped and re-queued don't restart the upload
			continueUpload = false;
		} else {
			continueUpload = true;
		}
		
		if (continueUpload) {
			var stats = this.getStats();
			if (stats.files_queued > 0 && this.queueSettings.queue_cancelled_flag === false) {
				this.startUpload();
			} else if (this.queueSettings.queue_cancelled_flag === false) {
				this.queueEvent("queue_complete_handler", [this.queueSettings.queue_upload_count]);
				this.queueSettings.queue_upload_count = 0;
			} else {
				this.queueSettings.queue_cancelled_flag = false;
				this.queueSettings.queue_upload_count = 0;
			}
		}
	};
}

/*
	Speed Plug-in
	
	Features:
		*Adds several properties to the 'file' object indicated upload speed, time left, upload time, etc.
			- currentSpeed -- String indicating the upload speed, bytes per second
			- averageSpeed -- Overall average upload speed, bytes per second
			- movingAverageSpeed -- Speed over averaged over the last several measurements, bytes per second
			- timeRemaining -- Estimated remaining upload time in seconds
			- timeElapsed -- Number of seconds passed for this upload
			- percentUploaded -- Percentage of the file uploaded (0 to 100)
			- sizeUploaded -- Formatted size uploaded so far, bytes
		
		*Adds setting 'moving_average_history_size' for defining the window size used to calculate the moving average speed.
		
		*Adds several Formatting functions for formatting that values provided on the file object.
			- SWFUpload.speed.formatBPS(bps) -- outputs string formatted in the best units (Gbps, Mbps, Kbps, bps)
			- SWFUpload.speed.formatTime(seconds) -- outputs string formatted in the best units (x Hr y M z S)
			- SWFUpload.speed.formatSize(bytes) -- outputs string formatted in the best units (w GB x MB y KB z B )
			- SWFUpload.speed.formatPercent(percent) -- outputs string formatted with a percent sign (x.xx %)
			- SWFUpload.speed.formatUnits(baseNumber, divisionArray, unitLabelArray, fractionalBoolean)
				- Formats a number using the division array to determine how to apply the labels in the Label Array
				- factionalBoolean indicates whether the number should be returned as a single fractional number with a unit (speed)
				    or as several numbers labeled with units (time)
	*/

var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.speed = {};
	
	SWFUpload.prototype.initSettings = (function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}
			
			this.ensureDefault = function (settingName, defaultValue) {
				this.settings[settingName] = (this.settings[settingName] == undefined) ? defaultValue : this.settings[settingName];
			};

			// List used to keep the speed stats for the files we are tracking
			this.fileSpeedStats = {};
			this.speedSettings = {};

			this.ensureDefault("moving_average_history_size", "10");
			
			this.speedSettings.user_file_queued_handler = this.settings.file_queued_handler;
			this.speedSettings.user_file_queue_error_handler = this.settings.file_queue_error_handler;
			this.speedSettings.user_upload_start_handler = this.settings.upload_start_handler;
			this.speedSettings.user_upload_error_handler = this.settings.upload_error_handler;
			this.speedSettings.user_upload_progress_handler = this.settings.upload_progress_handler;
			this.speedSettings.user_upload_success_handler = this.settings.upload_success_handler;
			this.speedSettings.user_upload_complete_handler = this.settings.upload_complete_handler;
			
			this.settings.file_queued_handler = SWFUpload.speed.fileQueuedHandler;
			this.settings.file_queue_error_handler = SWFUpload.speed.fileQueueErrorHandler;
			this.settings.upload_start_handler = SWFUpload.speed.uploadStartHandler;
			this.settings.upload_error_handler = SWFUpload.speed.uploadErrorHandler;
			this.settings.upload_progress_handler = SWFUpload.speed.uploadProgressHandler;
			this.settings.upload_success_handler = SWFUpload.speed.uploadSuccessHandler;
			this.settings.upload_complete_handler = SWFUpload.speed.uploadCompleteHandler;
			
			delete this.ensureDefault;
		};
	})(SWFUpload.prototype.initSettings);

	
	SWFUpload.speed.fileQueuedHandler = function (file) {
		if (typeof this.speedSettings.user_file_queued_handler === "function") {
			file = SWFUpload.speed.extendFile(file);
			
			return this.speedSettings.user_file_queued_handler.call(this, file);
		}
	};
	
	SWFUpload.speed.fileQueueErrorHandler = function (file, errorCode, message) {
		if (typeof this.speedSettings.user_file_queue_error_handler === "function") {
			file = SWFUpload.speed.extendFile(file);
			
			return this.speedSettings.user_file_queue_error_handler.call(this, file, errorCode, message);
		}
	};

	SWFUpload.speed.uploadStartHandler = function (file) {
		if (typeof this.speedSettings.user_upload_start_handler === "function") {
			file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
			return this.speedSettings.user_upload_start_handler.call(this, file);
		}
	};
	
	SWFUpload.speed.uploadErrorHandler = function (file, errorCode, message) {
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
		SWFUpload.speed.removeTracking(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_error_handler === "function") {
			return this.speedSettings.user_upload_error_handler.call(this, file, errorCode, message);
		}
	};
	SWFUpload.speed.uploadProgressHandler = function (file, bytesComplete, bytesTotal) {
		this.updateTracking(file, bytesComplete);
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_progress_handler === "function") {
			return this.speedSettings.user_upload_progress_handler.call(this, file, bytesComplete, bytesTotal);
		}
	};
	
	SWFUpload.speed.uploadSuccessHandler = function (file, serverData) {
		if (typeof this.speedSettings.user_upload_success_handler === "function") {
			file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
			return this.speedSettings.user_upload_success_handler.call(this, file, serverData);
		}
	};
	SWFUpload.speed.uploadCompleteHandler = function (file) {
		file = SWFUpload.speed.extendFile(file, this.fileSpeedStats);
		SWFUpload.speed.removeTracking(file, this.fileSpeedStats);

		if (typeof this.speedSettings.user_upload_complete_handler === "function") {
			return this.speedSettings.user_upload_complete_handler.call(this, file);
		}
	};
	
	// Private: extends the file object with the speed plugin values
	SWFUpload.speed.extendFile = function (file, trackingList) {
		var tracking;
		
		if (trackingList) {
			tracking = trackingList[file.id];
		}
		
		if (tracking) {
			file.currentSpeed = tracking.currentSpeed;
			file.averageSpeed = tracking.averageSpeed;
			file.movingAverageSpeed = tracking.movingAverageSpeed;
			file.timeRemaining = tracking.timeRemaining;
			file.timeElapsed = tracking.timeElapsed;
			file.percentUploaded = tracking.percentUploaded;
			file.sizeUploaded = tracking.bytesUploaded;

		} else {
			file.currentSpeed = 0;
			file.averageSpeed = 0;
			file.movingAverageSpeed = 0;
			file.timeRemaining = 0;
			file.timeElapsed = 0;
			file.percentUploaded = 0;
			file.sizeUploaded = 0;
		}
		
		return file;
	};
	
	// Private: Updates the speed tracking object, or creates it if necessary
	SWFUpload.prototype.updateTracking = function (file, bytesUploaded) {
		var tracking = this.fileSpeedStats[file.id];
		if (!tracking) {
			this.fileSpeedStats[file.id] = tracking = {};
		}
		
		// Sanity check inputs
		bytesUploaded = bytesUploaded || tracking.bytesUploaded || 0;
		if (bytesUploaded < 0) {
			bytesUploaded = 0;
		}
		if (bytesUploaded > file.size) {
			bytesUploaded = file.size;
		}
		
		var tickTime = (new Date()).getTime();
		if (!tracking.startTime) {
			tracking.startTime = (new Date()).getTime();
			tracking.lastTime = tracking.startTime;
			tracking.currentSpeed = 0;
			tracking.averageSpeed = 0;
			tracking.movingAverageSpeed = 0;
			tracking.movingAverageHistory = [];
			tracking.timeRemaining = 0;
			tracking.timeElapsed = 0;
			tracking.percentUploaded = bytesUploaded / file.size;
			tracking.bytesUploaded = bytesUploaded;
		} else if (tracking.startTime > tickTime) {
			this.debug("When backwards in time");
		} else {
			// Get time and deltas
			var now = (new Date()).getTime();
			var lastTime = tracking.lastTime;
			var deltaTime = now - lastTime;
			var deltaBytes = bytesUploaded - tracking.bytesUploaded;
			
			if (deltaBytes === 0 || deltaTime === 0) {
				return tracking;
			}
			
			// Update tracking object
			tracking.lastTime = now;
			tracking.bytesUploaded = bytesUploaded;
			
			// Calculate speeds
			tracking.currentSpeed = (deltaBytes * 8 ) / (deltaTime / 1000);
			tracking.averageSpeed = (tracking.bytesUploaded * 8) / ((now - tracking.startTime) / 1000);

			// Calculate moving average
			tracking.movingAverageHistory.push(tracking.currentSpeed);
			if (tracking.movingAverageHistory.length > this.settings.moving_average_history_size) {
				tracking.movingAverageHistory.shift();
			}
			
			tracking.movingAverageSpeed = SWFUpload.speed.calculateMovingAverage(tracking.movingAverageHistory);
			
			// Update times
			tracking.timeRemaining = (file.size - tracking.bytesUploaded) * 8 / tracking.movingAverageSpeed;
			tracking.timeElapsed = (now - tracking.startTime) / 1000;
			
			// Update percent
			tracking.percentUploaded = (tracking.bytesUploaded / file.size * 100);
		}
		
		return tracking;
	};
	SWFUpload.speed.removeTracking = function (file, trackingList) {
		try {
			trackingList[file.id] = null;
			delete trackingList[file.id];
		} catch (ex) {
		}
	};
	
	SWFUpload.speed.formatUnits = function (baseNumber, unitDivisors, unitLabels, singleFractional) {
		var i, unit, unitDivisor, unitLabel;

		if (baseNumber === 0) {
			return "0 " + unitLabels[unitLabels.length - 1];
		}
		
		if (singleFractional) {
			unit = baseNumber;
			unitLabel = unitLabels.length >= unitDivisors.length ? unitLabels[unitDivisors.length - 1] : "";
			for (i = 0; i < unitDivisors.length; i++) {
				if (baseNumber >= unitDivisors[i]) {
					unit = (baseNumber / unitDivisors[i]).toFixed(2);
					unitLabel = unitLabels.length >= i ? " " + unitLabels[i] : "";
					break;
				}
			}
			
			return unit + unitLabel;
		} else {
			var formattedStrings = [];
			var remainder = baseNumber;
			
			for (i = 0; i < unitDivisors.length; i++) {
				unitDivisor = unitDivisors[i];
				unitLabel = unitLabels.length > i ? " " + unitLabels[i] : "";
				
				unit = remainder / unitDivisor;
				if (i < unitDivisors.length -1) {
					unit = Math.floor(unit);
				} else {
					unit = unit.toFixed(2);
				}
				if (unit > 0) {
					remainder = remainder % unitDivisor;
					
					formattedStrings.push(unit + unitLabel);
				}
			}
			
			return formattedStrings.join(" ");
		}
	};
	
	SWFUpload.speed.formatBPS = function (baseNumber) {
		var bpsUnits = [1073741824, 1048576, 1024, 1], bpsUnitLabels = ["Gbps", "Mbps", "Kbps", "bps"];
		return SWFUpload.speed.formatUnits(baseNumber, bpsUnits, bpsUnitLabels, true);
	
	};
	SWFUpload.speed.formatTime = function (baseNumber) {
		var timeUnits = [86400, 3600, 60, 1], timeUnitLabels = ["d", "h", "m", "s"];
		return SWFUpload.speed.formatUnits(baseNumber, timeUnits, timeUnitLabels, false);
	
	};
	SWFUpload.speed.formatBytes = function (baseNumber) {
		var sizeUnits = [1073741824, 1048576, 1024, 1], sizeUnitLabels = ["GB", "MB", "KB", "bytes"];
		return SWFUpload.speed.formatUnits(baseNumber, sizeUnits, sizeUnitLabels, true);
	
	};
	SWFUpload.speed.formatPercent = function (baseNumber) {
		return baseNumber.toFixed(2) + " %";
	};
	
	SWFUpload.speed.calculateMovingAverage = function (history) {
		var vals = [], size, sum = 0.0, mean = 0.0, varianceTemp = 0.0, variance = 0.0, standardDev = 0.0;
		var i;
		var mSum = 0, mCount = 0;
		
		size = history.length;
		
		// Check for sufficient data
		if (size >= 8) {
			// Clone the array and Calculate sum of the values 
			for (i = 0; i < size; i++) {
				vals[i] = history[i];
				sum += vals[i];
			}

			mean = sum / size;

			// Calculate variance for the set
			for (i = 0; i < size; i++) {
				varianceTemp += Math.pow((vals[i] - mean), 2);
			}

			variance = varianceTemp / size;
			standardDev = Math.sqrt(variance);
			
			//Standardize the Data
			for (i = 0; i < size; i++) {
				vals[i] = (vals[i] - mean) / standardDev;
			}

			// Calculate the average excluding outliers
			var deviationRange = 2.0;
			for (i = 0; i < size; i++) {
				
				if (vals[i] <= deviationRange && vals[i] >= -deviationRange) {
					mCount++;
					mSum += history[i];
				}
			}
			
		} else {
			// Calculate the average (not enough data points to remove outliers)
			mCount = size;
			for (i = 0; i < size; i++) {
				mSum += history[i];
			}
		}

		return mSum / mCount;
	};
	
}
/*
	Cookie Plug-in
	
	This plug in automatically gets all the cookies for this site and adds them to the post_params.
	Cookies are loaded only on initialization.  The refreshCookies function can be called to update the post_params.
	The cookies will override any other post params with the same name.
*/

var SWFUpload;
if (typeof(SWFUpload) === "function") {
	SWFUpload.prototype.initSettings = function (oldInitSettings) {
		return function () {
			if (typeof(oldInitSettings) === "function") {
				oldInitSettings.call(this);
			}
			
			this.refreshCookies(false);	// The false parameter must be sent since SWFUpload has not initialzed at this point
		};
	}(SWFUpload.prototype.initSettings);
	
	// refreshes the post_params and updates SWFUpload.  The sendToFlash parameters is optional and defaults to True
	SWFUpload.prototype.refreshCookies = function (sendToFlash) {
		if (sendToFlash === undefined) {
			sendToFlash = true;
		}
		sendToFlash = !!sendToFlash;
		
		// Get the post_params object
		var postParams = this.settings.post_params;
		
		// Get the cookies
		var i, cookieArray = document.cookie.split(';'), caLength = cookieArray.length, c, eqIndex, name, value;
		for (i = 0; i < caLength; i++) {
			c = cookieArray[i];
			
			// Left Trim spaces
			while (c.charAt(0) === " ") {
				c = c.substring(1, c.length);
			}
			eqIndex = c.indexOf("=");
			if (eqIndex > 0) {
				name = c.substring(0, eqIndex);
				value = c.substring(eqIndex + 1);
				postParams[name] = value;
			}
		}
		
		if (sendToFlash) {
			this.setPostParams(postParams);
		}
	};

}
/*
	A simple class for displaying file information and progress
	Note: This is a demonstration only and not part of SWFUpload.
	Note: Some have had problems adapting this class in IE7. It may not be suitable for your application.
*/

// Constructor
// file is a SWFUpload file object
// targetID is the HTML element id attribute that the FileProgress HTML structure will be added to.
// Instantiating a new FileProgress object with an existing file will reuse/update the existing DOM elements
function FileProgress(file, targetID) {
	this.fileProgressID = file.id;

	this.opacity = 100;
	this.height = 0;
	

	this.fileProgressWrapper = document.getElementById(this.fileProgressID);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.fileProgressID;

		this.fileProgressElement = document.createElement("div");
		this.fileProgressElement.className = "progressContainer";

		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(file.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);

		this.fileProgressWrapper.appendChild(this.fileProgressElement);

		document.getElementById(targetID).appendChild(this.fileProgressWrapper);
	} else {
		this.fileProgressElement = this.fileProgressWrapper.firstChild;
		this.reset();
	}

	this.height = this.fileProgressWrapper.offsetHeight;
	this.setTimer(null);


}

FileProgress.prototype.setTimer = function (timer) {
	this.fileProgressElement["FP_TIMER"] = timer;
};
FileProgress.prototype.getTimer = function (timer) {
	return this.fileProgressElement["FP_TIMER"] || null;
};

FileProgress.prototype.reset = function () {
	this.fileProgressElement.className = "progressContainer";

	this.fileProgressElement.childNodes[2].innerHTML = "&nbsp;";
	this.fileProgressElement.childNodes[2].className = "progressBarStatus";
	
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = "0%";
	
	this.appear();	
};

FileProgress.prototype.setProgress = function (percentage) {
	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
	this.fileProgressElement.childNodes[3].style.width = percentage + "%";

	this.appear();	
};
FileProgress.prototype.setComplete = function () {
	this.fileProgressElement.className = "progressContainer blue";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 10000));
};
FileProgress.prototype.setError = function () {
	this.fileProgressElement.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 5000));
};
FileProgress.prototype.setCancelled = function () {
	this.fileProgressElement.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	this.setTimer(setTimeout(function () {
		oSelf.disappear();
	}, 2000));
};
FileProgress.prototype.setStatus = function (status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

// Show/Hide the cancel button
FileProgress.prototype.toggleCancel = function (show, swfUploadInstance) {
	this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (swfUploadInstance) {
		var fileID = this.fileProgressID;
		this.fileProgressElement.childNodes[0].onclick = function () {
			swfUploadInstance.cancelUpload(fileID);
			return false;
		};
	}
};

FileProgress.prototype.appear = function () {
	if (this.getTimer() !== null) {
		clearTimeout(this.getTimer());
		this.setTimer(null);
	}
	
	if (this.fileProgressWrapper.filters) {
		try {
			this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = 100;
		} catch (e) {
			// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
			this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=100)";
		}
	} else {
		this.fileProgressWrapper.style.opacity = 1;
	}
		
	this.fileProgressWrapper.style.height = "";
	
	this.height = this.fileProgressWrapper.offsetHeight;
	this.opacity = 100;
	this.fileProgressWrapper.style.display = "";
	
};

// Fades out and clips away the FileProgress box.
FileProgress.prototype.disappear = function () {

	var reduceOpacityBy = 15;
	var reduceHeightBy = 4;
	var rate = 30;	// 15 fps

	if (this.opacity > 0) {
		this.opacity -= reduceOpacityBy;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduceHeightBy;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}

	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		this.setTimer(setTimeout(function () {
			oSelf.disappear();
		}, rate));
	} else {
		this.fileProgressWrapper.style.display = "none";
		this.setTimer(null);
	}
};
/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileQueued(file) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Pending...");
		progress.toggleCancel(true, this);

	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
			progress.setStatus("File is too big.");
			this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			progress.setStatus("Cannot upload Zero Byte files.");
			this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			progress.setStatus("Invalid File Type.");
			this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		default:
			if (file !== null) {
				progress.setStatus("Unhandled Error");
			}
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = false;
		}
		
		/* I want auto start the upload and I can do that here */
		this.startUpload();
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and
		return true to indicate that the upload should start.
		It's important to update the UI here because in Linux no uploadProgress events are called. The best
		we can do is say we are uploading.
		 */
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setStatus("Uploading...");
		progress.toggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);

		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setProgress(percent);
		progress.setStatus("Uploading...");
		document.getElementById('progress_status').innerHTML = percent+'% completed';
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadSuccess(file, serverData) {
	submit_upload_form();
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setComplete();
		progress.setStatus("Complete.");
		progress.toggleCancel(false);

	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	try {
		var progress = new FileProgress(file, this.customSettings.progressTarget);
		progress.setError();
		progress.toggleCancel(false);

		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
			progress.setStatus("Upload Error: " + message);
			this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
			progress.setStatus("Upload Failed.");
			this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.IO_ERROR:
			progress.setStatus("Server (IO) Error");
			this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
			progress.setStatus("Security Error");
			this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			progress.setStatus("Upload limit exceeded.");
			this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
			progress.setStatus("Failed Validation.  Upload skipped.");
			this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			// If there aren't any files left (they were all cancelled) disable the cancel button
			if (this.getStats().files_queued === 0) {
				document.getElementById(this.customSettings.cancelButtonId).disabled = true;
			}
			progress.setStatus("Cancelled");
			progress.setCancelled();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			progress.setStatus("Stopped");
			break;
		default:
			progress.setStatus("Unhandled Error: " + errorCode);
			this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
			break;
		}
	} catch (ex) {
        this.debug(ex);
    }
}

function uploadComplete(file) {
	if (this.getStats().files_queued === 0) {
		document.getElementById(this.customSettings.cancelButtonId).disabled = true;
	}
}

// This event comes from the Queue Plugin
function queueComplete(numFilesUploaded) {
	var status = document.getElementById("divStatus");
	status.innerHTML = numFilesUploaded + " file" + (numFilesUploaded === 1 ? "" : "s") + " uploaded.";
}
