var TimeComments = function(player,settings){
	var timecomments = this;
	timecomments.player = player;
	timecomments.settings = settings || {};
	timecomments.currentIndex = 0;
	timecomments.activeId = "";
	timecomments.activeComment = "";
	timecomments.show = false;
	timecomments.init();
}

TimeComments.prototype.init = function(){
	var timecomments = this;

	if (typeof timecomments.settings.comments == 'undefined' || timecomments.settings.comments == ''){
		timecomments.comments = [];
	}else{
		timecomments.comments = timecomments.GetTimeComments(timecomments.settings.dummy);
	}
	
	timecomments.AddComment();
	timecomments.AddControlBArMenu();
	timecomments.Structure();
	timecomments.playPause();
	timecomments.BindComments();
}

TimeComments.prototype.AddComment = function(){
	var timecomments = this;
	var progressControl  = timecomments.player.controlBar.progressControl.el_;
	var progressControl_ = timecomments.player.controlBar.progressControl;
	var mouseDisplay     = progressControl_.children_[0].mouseTimeDisplay.el_;

	var mouseDisplay_time    = "";
	var dataSetTime    = "0:00";
	var addCommentHolder = "";
	var addCommentChildHolder = "";
	var cTimeDisplay = "";
	var commentBoxForm = "";

	var commentTimeDisplay = function(){
		cTimeDisplay = document.createElement('div');
		cTimeDisplay.className = "cb-vjs-comments-display";
		cTimeDisplay.style.position =  'absolute';
		cTimeDisplay.style.width =  '2px';
		cTimeDisplay.style.height =  '100%';

		addCommentHolder = document.createElement('div');
		addCommentHolder.className = "add-comment-holder";
		addCommentChildHolder = document.createElement('div');
		addCommentChildHolder.className = "add-comment-child-holder";

		addCommentChildHolder.innerHTML = "<span class='cb-vjs-addcomment-clicker'>"+dataSetTime+" | Add Comment "+"</span>";
		
		addCommentHolder.appendChild(addCommentChildHolder);
		progressControl.insertBefore(addCommentHolder,progressControl_.el_.firstChild);
		progressControl.childNodes[1].insertBefore(cTimeDisplay, mouseDisplay);
	}


	var setCommentTime  = function(event){
		var mouseTimeDisplay = timecomments.player.controlBar.progressControl.seekBar.mouseTimeDisplay;
		var duration = timecomments.player.duration();
    	var val = mouseTimeDisplay.calculateDistance(event) * duration;
    	var newTime = val.toFixed(2);
    	mouseDisplay_time = newTime;

    	if (mouseDisplay_time > timecomments.player.duration() || mouseDisplay_time == 0){
    		hideAddComment();
    	}

    	dataSetTime = mouseDisplay.dataset.currentTime;
    	mouseDisplay.style.display = "none";
    	//cTimeDisplay.innerHTML = "<span class='cb-vjs-addcomment-clicker'>"+dataSetTime+" | Add Comment "+"</span>";
		cTimeDisplay.style.left = mouseDisplay.style.left;
	}

	var showAddComment = function(){
		cTimeDisplay.style.display = "block";
	}

	var hideAddComment = function(){
		cTimeDisplay.style.display = "none";
	}

	var setCommentBox = function(){
		var controlBar_  = timecomments.player.controlBar.el_;
		var Player_  = timecomments.player.el_;
		commentBoxForm = document.createElement('form');
		commentBoxForm.className = 'cb-vjs-timecomment-form';

		var commentData = document.createElement('div');
		commentData.className = 'cb-vjs-comment-data';
		commentData.innerHTML = "<img src="+timecomments.settings.userprofile+"><textarea id='timecommnts-send-box' class='timecommnts-send-box'></textarea>";
		
		var btnHolder = document.createElement('div');
		btnHolder.className = 'cb-vjs-comments-btn-holder';
		btnHolder.innerHTML = "<span id='timecomment-box-dismiss' class='timecomment-box-dismiss'>Cancel</span><span id='add-timecomment' class='add-timecomment'>Add Comment</span>";
			
		var commentWrapper  = document.createElement('div');
		commentWrapper.className = "comment-wrapper";

		var innerWrapper  = document.createElement('div');
		innerWrapper.className = "inner-wrapper";

		Player_.insertBefore(commentBoxForm,controlBar_);
		commentBoxForm.appendChild(commentWrapper);
		commentWrapper.appendChild(innerWrapper);
		innerWrapper.appendChild(commentData);
		innerWrapper.appendChild(btnHolder);
	}

	var showCommentBox = function (){
		var userid = timecomments.settings.userid;
		if (typeof userid == 'undefined' || userid == '' || !userid){
			alert("Please Login to Comment !");
			return;
		}
		commentBoxForm.className = "cb-vjs-timecomment-form open-comment";
		timecomments.player.pause();
	}

	var dismissCommentBox = function(){
		commentBoxForm.className = "cb-vjs-timecomment-form";
		document.getElementById('timecommnts-send-box').value = "";
		timecomments.player.play();
	}

	var sendComment = function(){
		var videoid = timecomments.settings.videoid;
		var userid = timecomments.settings.userid;
		var comment = document.getElementById('timecommnts-send-box').value;
		var time = mouseDisplay_time;
		if (comment != ''){
			timecomments.setNewCommentTemp_(comment,time);
			dismissCommentBox();
			sendTimeComment_(videoid,userid,comment,time);
		}else{
			alert("Please Write something text field !");
		}
		
	}

	setCommentBox();
	commentTimeDisplay();
	cTimeDisplay.addEventListener('click',showCommentBox);
	progressControl.addEventListener("mouseover", showAddComment);
	progressControl.addEventListener("mouseout", hideAddComment);
	/*cTimeDisplay.addEventListener('mouseleave',hideAddComment);*/
	progressControl.addEventListener("mousemove", setCommentTime);
	document.getElementById('timecomment-box-dismiss').addEventListener('click',dismissCommentBox);
	document.getElementById('add-timecomment').addEventListener('click',sendComment);
}

TimeComments.prototype.AddControlBArMenu = function(){
	var timecomments = this;
	var controlBar = timecomments.player.controlBar.el_;
	var controlBarChilds = controlBar.childNodes;
	for (var i = 0; i < controlBarChilds.length; i++) {
		if (controlBarChilds[i].id == 'vjs-cb-logo'){
			cbVjsLogo = controlBarChilds[i];
		}
	}
	var commentStructure = document.getElementById('ul-comments');

	var toggleCommentsView = document.createElement('div');
	toggleCommentsView.id = "cb-vjs-togglecomments-view";
	toggleCommentsView.className = "cb-vjs-togglecomments-view";
	toggleCommentsView.innerHTML = "<span></span>";
	controlBar.insertBefore(toggleCommentsView,cbVjsLogo);

	var showCommentsAction = function(show){
		console.log(timecomments.show);
		if (timecomments.show == false){
			timecomments.show == true;
			timecomments.ForceShowComments();
		}else{
			timecomments.show == false;
			timecomments.HideComments();
		}
	}

	toggleCommentsView.addEventListener('click',showCommentsAction)
}

TimeComments.prototype.setNewCommentTemp_ = function(comment,time){
	var timecomments = this;
	var comments = timecomments.comments;
	var id = new Date().getUTCMilliseconds();
	var Tempcomment = {"avatar":timecomments.settings.userprofile, "comment" : comment, "id": id, "time" : time, "username" : timecomments.settings.username};
	
	//sorting time comments again
	comments.push(Tempcomment);
	comments.sort(function(a, b) {
	    return parseFloat(a.time) - parseFloat(b.time);
	});

	timecomments.comments = comments;

	var commentStructure = { "listComm" : "",
							 "iteration" : "",
							 "commentBox" : "",
							 "avatar" : "",
							 "username" : "",
							 "comment" : ""
						};

	var TempCommentIndex = timecomments.getCommentIndex(Tempcomment); 
	timecomments.ListHtml_(commentStructure,Tempcomment,true,TempCommentIndex);
}

TimeComments.prototype.getCommentIndex = function (comment){
	var timecomments = this;
	var comments = timecomments.comments;
	for (var i = 0; i < comments.length; i++){
		if (comments[i].id == comment.id){
			return i;
		}
	}
}

TimeComments.prototype.convertToSeconds = function(string){
	
	var a = string.split(':');
	if (a.length == 2){
		return (+a[0]) * 60 + (+a[1]);
	}else if (a.length == 3){
		return (+a[0]) * 60 * 60 + (+a[1]) * 60 + (+a[2]);
	}
}


TimeComments.prototype.GetTimeComments = function(dummy){
	var timecomments = this;
	var comments =  "";
	if (dummy){
		comments =  [
						{"id":"1", "comment" : "This is first comment","time" : "10.25635","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					 	{"id":"2", "comment" : "This is Second comment","time" : "15.12353","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					 	{"id":"3", "comment" : "This is Third comment","time" : "30.25635","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					 	{"id":"4", "comment" : "This is Fourth comment","time" : "50.25635","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					 	{"id":"5", "comment" : "This is Fifth comment","time" : "70.25635","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					 	{"id":"6", "comment" : "This is Sixth comment","time" : "90.25635","avatar":"http://127.0.0.1/clipbucket-git/images/avatars/1.jpg"},
					]
	}else{
		comments = JSON.parse(timecomments.settings.comments);
	}

	return comments;
}

TimeComments.prototype.ListHtml_ = function(elements,comment,nodeValue,index){

	var UnOrderedList = document.getElementById('cb-vjs-comments-list-main');
	
	elements.listComm = document.createElement("li");
	elements.listComm.id = "comment-"+comment.id; 
	elements.listComm.className = "cb-vjs-comments-list";

	elements.avatar = document.createElement("img");
	elements.avatar.className = "cb-vjs-comments-avatar";
	elements.avatar.src = comment.avatar;

	elements.commentBox = document.createElement("div");
	elements.commentBox.className = "cb-vjs-comment-box";

	elements.username = document.createElement("span");
	elements.username.className = "cb-vjs-comment-username";
	elements.username.innerHTML = comment.username; 

	elements.comment = document.createElement("div");
	elements.comment.className = "comment";
	elements.comment.innerHTML = comment.comment; 

	elements.commentBox.appendChild(elements.username);
	elements.commentBox.appendChild(elements.comment);

	elements.listComm.appendChild(elements.avatar);
	elements.listComm.appendChild(elements.commentBox);

	if (!nodeValue){
		UnOrderedList.appendChild(elements.listComm);
	}else{
		UnOrderedList.insertBefore(elements.listComm, UnOrderedList.childNodes[index]);
	}
}

TimeComments.prototype.Structure = function(){
	var timecomments = this;
	var CommentsParent = document.createElement('div');
	CommentsParent.className = "cb-vjs-comments-main";
	CommentsParent.id = "cb-vjs-comments";
	var errorDisplay = timecomments.player.getChild('errorDisplay').el_;
	timecomments.player.el_.insertBefore(CommentsParent, errorDisplay);

	var UnOrderedList = document.createElement('ul');
	UnOrderedList.id = "cb-vjs-comments-list-main";
	UnOrderedList.className = "cb-vjs-comments";
	
	CommentsParent.appendChild(UnOrderedList);
	
	var commentStructure = { "listComm" : "",
							 "iteration" : "",
							 "commentBox" : "",
							 "avatar" : "",
							 "username" : "",
							 "comment" : ""
						};

	for (var i = 0; i < timecomments.comments.length ; i ++ ){
		timecomments.ListHtml_(commentStructure,timecomments.comments[i],false)
	}

	UnOrderedList.style.display = "none";
}

TimeComments.prototype.playPause = function(){
	var timecomments = this;
	var EventElement = document.getElementById('cb-vjs-comments');
	var Event = 'click';
	var Method = function(){
		if (timecomments.player.paused()){
			timecomments.player.play();
		}else{
			timecomments.player.pause();
		}
	}
	EventElement.addEventListener(Event, Method);
}

TimeComments.prototype.BindComments = function(){
	var timecomments = this;
	timecomments.player.timecomments = timecomments;

	timecomments.player.on("timeupdate",timecomments.ShowComments);
	timecomments.player.on("seeked",timecomments.UpdateCurrentIndex);
	timecomments.player.on("timeupdate",timecomments.TriggerComment);
}

TimeComments.prototype.ShowComments = function(){
	var player = this;
	var CurrentTime = player.currentTime();
	var UnOrderedList = document.getElementById('cb-vjs-comments-list-main');
	var FirstComment = player.timecomments.comments[0];
	if ( typeof FirstComment != 'undefined' && player.timecomments.show == false && CurrentTime >= FirstComment.time ){
		UnOrderedList.style.display = "block";
		player.timecomments.show = true;
	}
} 

TimeComments.prototype.ForceShowComments = function(){
	var timecomments = this;
	var UnOrderedList = document.getElementById('cb-vjs-comments-list-main');
	UnOrderedList.style.display = "block";
	timecomments.player.timecomments.show = true;
}


TimeComments.prototype.HideComments = function(){
	var timecomments = this;
	var UnOrderedList = document.getElementById('cb-vjs-comments-list-main');
	
	if ( timecomments.player.timecomments.show == true){
		UnOrderedList.style.display = "none";
		timecomments.player.timecomments.show = false;
	}
} 

TimeComments.prototype.TriggerComment = function(){
	var player = this;
	var curr_comment = player.timecomments.comments[player.timecomments.currentIndex];

	if (typeof curr_comment != 'undefined'){
		var CurrentTime = player.currentTime();
		if (CurrentTime >= curr_comment.time && player.timecomments.currentIndex < player.timecomments.comments.length){
	
			player.timecomments.SetActiveComment(curr_comment);
			player.timecomments.currentIndex++;

		}
	}else{
		//console.error("WTH :O no comments ? ");
	}
} 

TimeComments.prototype.SetActiveComment = function(current){
	var timecomments = this;
	
	timecomments.activeId = current.id;
	timecomments.activeComment = document.getElementById('comment-'+timecomments.activeId);
	timecomments.activeComment.className = "cb-vjs-comments-list active";
	
	for (i in timecomments.comments){
		if ( timecomments.comments[i].id != current.id){
			var inActiveId = parseInt(timecomments.comments[i].id);
			var inActiveComments = document.getElementById('comment-'+inActiveId);
			inActiveComments.className = "cb-vjs-comments-list";	
		}
	}

}


TimeComments.prototype.UpdateCurrentIndex = function(){
	var player = this;
	var comments = player.timecomments.comments;
	var CurrentTime = "";

	if ( typeof comments != 'undefined' ){
		CurrentTime = player.currentTime();
		var minDiff = 100000000;
		var Diff = 0;
		var closest = 0;
		for (var i = 0; i < comments.length ; i++){
			if (CurrentTime > comments[i].time){
				Diff = Math.abs(CurrentTime-comments[i].time);
				if ( Diff < minDiff ){
					minDiff = Diff;
					closest = comments[i].time;
					player.timecomments.currentIndex = i;	
				}
			}
		}
		if ( Diff == 0 ){
			player.timecomments.currentIndex = 0;
			player.timecomments.HideComments();
		}
	}
}


var timecomments = function(settings){
	
	myTimeComments = new TimeComments(this,settings);
	
}
videojs.plugin('timecomments',timecomments);