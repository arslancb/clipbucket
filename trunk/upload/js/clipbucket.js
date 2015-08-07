(function(window){
	var _cb = function(){
		var self = this;
		// bootstrapping
		this.baseurl = baseurl;
		this.imageurl = "";
		this.page = this.baseurl+'/ajax.php';
		this.loading_img = "<img style='vertical-align:middle' src='" + this.imageurl + "/ajax-loader.gif'>";
		this.loading = this.loading_img+" Loading...";
		this.download = 0;
		this.total_size = 0;
		this.cur_speed = 0;
		
		this.status_refesh = 1 //in seconds
		this.result_page = this.baseurl+'/actions/file_results.php';
		this.download_page = this.baseurl+'/actions/file_downloader.php';
		this.count = 0;

		this.hasLoaded = false;
		this.perc_download = 0;
		
		
		this.force_stop = false;
		// this.remoteObjID = this.randomString();
		this.remoteObjID = "";

		this.current_menu = "";

		this.normal_player_html = '';
		this.hq_player_html = '';

		this.has_hq_function = false;

		this.collectionID = false;

		this.keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";


		this.ua = navigator.userAgent.toLowerCase();

		if (this.ua.indexOf(" chrome/") >= 0 || this.ua.indexOf(" firefox/") >= 0 || this.ua.indexOf(' gecko/') >= 0) {
			var StringMaker = function () {
				this.str = "";
				this.length = 0;
				this.append = function (s) {
					this.str += s;
					this.length += s.length;
				}
				this.prepend = function (s) {
					this.str = s + this.str;
					this.length += s.length;
				}
				this.toString = function () {
					return this.str;
				}
			}
		} else {
			var StringMaker = function () {
				this.parts = [];
				this.length = 0;
				this.append = function (s) {
					this.parts.push(s);
					this.length += s.length;
				}
				this.prepend = function (s) {
					this.parts.unshift(s);
					this.length += s.length;
				}
				this.toString = function () {
					return this.parts.join('');
				}
			}
		}

		this.setRemoteId = function(){
			this.remoteObjID = this.randomString();
		};

		this.Confirm_Delete = function(delUrl){
			var self = this;
			if (confirm("Are you sure you want to delete")) {
				document.location = delUrl;
			}
		};

		this.Confirm_Uninstall = function(delUrl) {
			var self = this;
			  if (confirm("Are you sure you want to uninstall this plugin ?")) {
				document.location = delUrl;
		  }
		};

		this.confirm_it = function(msg){
			var self = this;
			var action = confirm(msg);
			if(action)
			{
				return true;
			}else
				return false;
		};

		this.reloadImage = function(captcha_src,imgid){
			var self = this;
			img = document.getElementById(imgid);
			img.src = captcha_src+'?'+Math.random();
		};

		this.validate_category_form = function(thisform){
			var self = this;
			with(thisform){
				if (validate_required(title,"Title must be filled out!")==false){
			 		title.focus();
			 		return false;
				}
				if (validate_required(description,"Description must be filled out!")==false){
			 		description.focus();
			 		return false;
				}
			}
		};

		this.validate_ad_form = function(thisform){
			var self = this;
			with (thisform){
				if (validate_required(name,"Name must be filled out!")==false){
			 		name.focus();
			 		return false;
				}
				if (validate_required(type,"Type must be filled out!")==false){
			 		type.focus();
			 		return false;
				}
				if (validate_required(syntax,"Syntax Must Be Filled Out")==false){
			 		syntax.focus();
			 		return false;
				}
				if (validate_required(code,"Code Must Be Filled Out")==false){
			 		code.focus();return false;
				}
			}
		};

		this.randomString = function(){
			var self = this;
			var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
			var string_length = 8;
			var randomstring = '';
			for (var i=0; i<string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum,rnum+1);
			}
			return randomstring;
		};

		this.check_remote_url = function(){
			var self = this;
			var file = $("#remote_file_url").val();
			var $youtubeButton = $('#ytUploadBttn'); // youtube upload button
			var $uploadButton = $('#remoteUploadBttn'); // upload button
			var $cancelButton = $('#remoteUploadBttnStop'); // cancel upload button
			this.force_stop = false;
			if(file.match(/^e.g/) || typeof file === "undefined" || file.length === 0){
				// given url is not valid
				//alert("Please enter file url");
				$('#error_msgs').html('<div class="alert alert-danger" role="alert">Given URL is invalid!</div>');
				return false;
			}

			// these functions will only be used in remote upload
			// they manage the UI changes
			var remoteUploadStart = function(youtube){
				if(typeof youtube !== "undefined"){
					$uploadButton.attr("disabled","disabled");
					$youtubeButton.attr("disabled","disabled");
					//$cancelButton.show();
				}else{
					$(".downloadStatusContainer").removeClass("hidden");
					$uploadButton.attr("disabled","disabled").hide();
					$youtubeButton.attr("disabled","disabled");
					$cancelButton.show();
				}
			};

			var remoteUploadStop = function(youtube){
				if(typeof youtube !== "undefined"){
					$cancelButton.removeAttr("disabled").hide();
					$youtubeButton.removeAttr("disabled");
					$uploadButton.removeAttr("disabled").show();
				}else{
					$(".downloadStatusContainer").addClass("hidden");
					$cancelButton.removeAttr("disabled").hide();
					$youtubeButton.removeAttr("disabled");
					$uploadButton.removeAttr("disabled").show();
				}
			};

			remoteUploadStart();

			var ajaxCall = $.ajax({
				url: self.download_page,
				type: "POST",
				data: ({file:file,file_name:file_name}),
				dataType : 'JSON',
				beforeSend : function(){
					self.remoteUploadStatusUpdate();
					var remoteFileName = self.getName(file);
					$("#loading").html("Downloading");
					$('#remoteFileName').replaceWith('"'+remoteFileName+'"');
				},
				success: function(data){
					self.force_stop = true;
					if(data.error){
						self.force_stop = true;
						remoteUploadStop();
						$('#error_msgs').html('<div class="alert alert-danger" role="alert"> File Type Not Allowed!</div>');
						return false;
					}
					remoteUploadStop();  
					$("#loading").html('');
					var vid = data.vid;
					$.post(self.baseurl+'/actions/getVideoDetails.php', {
						"file_name":file_name,
						"vid" : vid,
						},function(data){
							var oneFileForm = $("#updateVideoInfoForm").clone();
							$(oneFileForm).find("input[name=title]").val(data.title);
							$(oneFileForm).find("textarea#desc").val(data.description);
							$(oneFileForm).find("input[name='category[]']:first").attr('checked', 'checked');


							// creating the hidden form fields
							var hiddenVideoIdField = document.createElement('input');
							hiddenVideoIdField.name = 'videoid';
							hiddenVideoIdField.type = 'hidden';
							hiddenVideoIdField.value =  vid;

							var hiddenVideoNameField = document.createElement('input');
							hiddenVideoNameField.name = 'file_name';
							hiddenVideoNameField.type = 'hidden';
							hiddenVideoNameField.value =  file_name;


							$(oneFileForm).append(hiddenVideoIdField);
							$(oneFileForm).append(hiddenVideoNameField);


							$("#remoteForm").html("");
							$(oneFileForm).removeClass("hidden")
							.attr("id", "uploadFormContainer_remote")
							.appendTo("#remoteForm");
							$(oneFileForm).find("form").on({
								submit: function(e){
									e.preventDefault();
									
									var form = $(this);

									var formData = $(form).serialize();
									formData += "&updateVideo=yes";

									$.ajax({
										url : baseurl + "/actions/file_uploader.php",
										type : "post",
										data : formData,
									}).success(function(data){
										msg = $.parseJSON(data);
										$("#uploadMessage").removeClass("hidden");
										if(msg.error){
											$("#uploadMessage").html(msg.error).attr("class", "alert alert-danger");
										}else{
											$("#uploadMessage").html(msg.msg).attr("class", "alert alert-success");
										}
										setTimeout(function(){
											$("#uploadMessage").addClass("hidden");
										}, 5000);
									});
								}
							});
							 $(".formSection h4").on({
                        click: function(e){
                            e.preventDefault();
                            if($(this).find("i").hasClass("glyphicon-chevron-down")){
                                $(this).find("i").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                                $(this).next().toggleClass("hidden");
                            }else{
                                $(this).find("i").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                                $(this).next().toggleClass("hidden");
                            }
                        }
                    });
							$(oneFileForm).on({
                        submit: function(e){
                            e.preventDefault();
                            var self = this;
                            var data = $(this).serialize();
                            data += "&updateVideo=yes";
                            $.ajax({
                                url : baseurl + "/actions/file_uploader.php",
                                type : "post",
                                data : data,
                                dataType: "JSON",
                            }).success(function(msg){
                                
                                	$("#uploadMessage").removeClass("hidden");
	                                if(msg.error){
	                                    $("#uploadMessage").html(msg.error).attr("class", "alert alert-danger");
	                                }else{
	                                    $("#uploadMessage").html(msg.msg).attr("class", "alert alert-success");
	                                }
	                                setTimeout(function(){
	                                    $("#uploadMessage").addClass("hidden");
	                                }, 5000);
                                
                            }).fail(function(err){
                                console.log(err);
                            });
                        }
                    });
						},'json');
					}
				});
			
				$('#remoteUploadBttnStop').click(function() {
					ajaxCall.abort(); 
					this.force_stop=true; 
					$("#loading").html('');
					$('#remoteDownloadStatus').hide(); 
					$(this).hide();
					$('#remoteUploadBttn').attr('disabled','').show(); 
				});
		};

		this.remoteUploadStatusUpdate = function(){
			var self = this;
			var ajaxCall = $.ajax({
				url: self.result_page,
				type: "POST",
				data:({file_name:file_name}),
				dataType: "JSON",
			});
			ajaxCall.success(function(serverResponse){
				//console.log(serverResponse);
				if(false === self.force_stop){
					self.updateProgress(serverResponse);
					setTimeout(function(){
						self.remoteUploadStatusUpdate();
					}, self.status_refesh*1000);
				}
			});
		};

		this.updateProgress = function(serverResponse){
			if(typeof serverResponse !== "undefined" && serverResponse !== null){
				var downloaded = (serverResponse.downloaded/1048576).toFixed(2);
				var total = (serverResponse.total_size/1048576).toFixed(2);
				var progress = (serverResponse.downloaded/serverResponse.total_size) * 100;
				$("#downloadStatus").find("#downloaded").text(downloaded+" Mb");
				$("#downloadStatus").find("#totalSize").text(total+" Mb");
				$("#prog_bar").css("width", progress+"%");
			}
		};
		this.youtube_check_url = function(){
			var self = this;
			var file = $("#remote_file_url").val();
			var $youtubeButton = $('#ytUploadBttn'); // youtube upload button
			var $uploadButton = $('#remoteUploadBttn'); // upload button
			var $cancelButton = $('#remoteUploadBttnStop'); // cancel upload button
			this.force_stop = false;
			if(file.match(/^e.g/) || typeof file === "undefined" || file.length === 0){
				// given url is not valid
				$('#error_msgs').html('<div class="alert alert-danger" role="alert"> Please enter valid URL !</div>');
				return false;
			}
			return true;

		};
		this.youtube_upload = function(){

			if(!this.youtube_check_url()){
				return false;
			}

			$('#remoteUploadBttn').attr("disabled","disabled");
			$('#ytUploadBttn').attr("disabled","disabled");

			var file = $("#remote_file_url").val();
			force_stop = false;		
			if(!file || file=='undefined')
			{
				alert("Please enter file url");
				$('#remoteUploadBttn').attr('disabled','');
				$('#ytUploadBttn').attr("disabled",'');
				return false;
			}

			var ajaxCall = $.ajax({
			  url: download_page,
			  type: "POST",
			  data: ({file:file,file_name:file_name,"youtube":"yes"}),
			  dataType : 'json',
			  beforeSend : function()
			  {
				$("#loading").html('<div style="float: left; display: inline-block;"><img src="'+imageurl+'/ajax-loader.gif"></div><div style="float: left; line-height: 16px; padding-left:5px">Uploading video from youtube, please wait...</div><div class="clear"></div>');
			  },
			  success: function(data)
			  {
				  if(data.error)
				  {		  
					force_stop = true;
					$('#remoteUploadBttn').attr('disabled','');
					$('#ytUploadBttn').attr("disabled","");
					alert(data.error);
				  }else if(data.vid)
				  {
				  	alert('this is checked success');
					  vid = data.vid;
					  $('#remoteUploadBttn').attr("disabled","disabled").hide();
					  $('#ytUploadBttn').attr("disabled","disabled").hide();
						
					  $.post(baseurl+'/actions/file_uploader.php',
					  {"getForm":"get_form",
					  "title":data.title,
					  "desc":data.desc,
					  "tags":data.tags,"objId":remoteObjID},
					  function(data)
					  {
							$('#remoteForm').append(data);
							$('#cbSubmitUpload'+remoteObjID)
							.before('<span id="updateVideoDataLoading" style="margin-right:5px"></span>')
							.attr("disabled","")
							.attr("value",lang.saveData)
							.attr("onClick","doUpdateVideo('#uploadForm"+remoteObjID+"','"+remoteObjID+"')")
							.after('<input type="hidden" name="videoid" value="'+vid+'" id="videoid" />')
							.after('<input type="hidden" name="updateVideo" value="yes" id="updateVideo" />');
					
					  },'text');  
				  }
				  $("#loading").html('');
			  }
		    });
		};

		this.status_update = function(){
			var self = this;
			var ajaxCall = $.ajax({
				url: self.result_page,
				type: "POST",
				data:({file_name:file_name}),
				dataType: "json",
				success: function(data){
					if(data){
						var total = parseFloat(data.total_size);
						var download = parseFloat(data.downloaded);
						var total_fm = parseFloat(data.total_size_fm);
						var download_fm = parseFloat(data.downloaded_fm);
						var speed = parseFloat(data.speed_download);
						var eta = parseFloat(data.time_eta);
						var eta_fm = parseFloat(data.time_eta_fm);
						var time_took = parseFloat(data.time_took);
						var time_took_fm = parseFloat(data.time_took_fm);
						if(speed/1024/1024 > 1){
							var theSpeed = Math.round(speed / 1024/1024) + " Mbps";
						}else{
							var theSpeed = Math.round(speed/ 1024 ) + " Kbps";
						}
						self.perc_download = Math.round(download/total*100);
						if(isNaN(download_fm)){
							$('#remoteDownloadStatus').show();
							$('#prog_bar').html('Loading');
							$('#dspeed').html('Loading');
							$('#eta').html('Loading');
							$('#status').html('Loading');
						}else{
							$('#remoteDownloadStatus').show();
							//$('#prog_bar').width(this.perc_download+'%');
							$('#prog_bar').animate({width:self.perc_download+'%'},1000);
							$('#prog_bar').html(self.perc_download+'%');
							$('#dspeed').html(theSpeed);
							$('#eta').html(eta_fm);
							$('#status').html(download_fm+' of '+total_fm);
						}
					}
					var intval = self.status_refesh*1000;
					if(self.perc_download > 99){
						self.force_stop = true;
					}
					if(!self.force_stop){
						setTimeout(function(){
							self.status_update()
						},intval);
					}
					else if(self.perc_download==100 && total>1){
						$('#time_took').html('Time Took : '+ time_took_fm);
					}
				}
			});
		};

		this.upload_file = function(Val,file_name){
			var self = this;
			var page =this.baseurl+'/actions/file_downloader.php';
			$.post(page, { 	
				file_url : Val,
				file_name : file_name
			},
			function(data){
				if(!data)
					alert("No data");
				else
					submit_upload_form();
			},'text');
		};

		/**
		 * Function used to delete any item with confirm message
		 */
		this.delete_item = function(obj,id,msg,url){
			$("#"+obj+'-'+id).click(function () {
				if (confirm(msg)) {
					document.location = url;
				}				
			});
			var self = this;
		};

		this.delete_video = function(obj,id,msg,url){ 
			var self = this;
			return delete_item(obj,id,msg,url); 
		};

		/**
		 * Function used to load editor's pic video
		 */
		this.get_video = function(type,div){
			var self = this;
			$(div).css("display","block");
			$(div).html(this.loading);
			$(div).html(this.loading);
			$.post(page, { 	
				mode : type
			},
			function(data){
				$(div).html(data);
			},'text');
		};

		/**
		 * functio used to get photos through ajax
		 */
		this.getAjaxPhoto = function(type,div){
			var self = this;
			$(div).css("display","block");
			var preservedHTML = $(div).html();
			$.ajax({
				url : self.page,
				type : 'POST',
				dataType : 'json',
				data : ({ mode : 'loadAjaxPhotos', 'photosType' : type }),
				beforeSend : function ()
				{
					$(div).html(this.loading);	
				},
				success : function (data)
				{
					if(data['failed'])
					{
						//alert("No Photos Returned");
						$(div).html(preservedHTML);
					}
						
					if(data['completed'])
					{	
						$(div).html(data['photoBlocks']);
					}
				}
			});
		};


		this.rating_over = function(msg,disable){
			if(disable!='disabled')
				$("#rating_result_container").html(msg);
			var self = this;
		};

		this.rating_out = function(msg,disable){
			if(disable!='disabled')
			$("#rating_result_container").html(msg);
		var self = this;
		};
		
		
		this.submit_share_form = function(form_id,type){
			var self = this;
			$("#share_form_results").css("display","block");
			$("#share_form_results").html(this.loading);
			$.post(page, 
			{ 	
				mode : 'share_object',
				type : type,
				users : $("#ShareUsers").val(),
				message : $("#message").val(),
				id : $("#objectid").val()
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#share_form_results").html(data);
				}
			},'text');
		};
		
		
		
		this.flag_object = function(form_id,id,type){
			var self = this;
			$("#flag_form_result").css("display","block");
			$("#flag_form_result").html(this.loading);
			$.post(page, 
			{ 	
				mode : 'flag_object',
				type : type,
				flag_type : $("#"+form_id+" select option:selected").val(),
				id : id
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#flag_form_result").css("display","block");
					$("#flag_form_result").html(data);
				}
			},'text');
		};
		
		this.slide_up_watch_video = function(nodiv){
			if($('.video_action_result_boxes '+nodiv).css("display")!="block")
				$('.video_action_result_boxes > *').slideUp();
			var self = this;
		}
		
		this.add_to_fav = function(type,id){
			var self = this;
			$("#video_action_result_cont").css("display","block");
			$("#video_action_result_cont").html(this.loading);
			
			$.post(page, 
			{ 	
				mode : 'add_to_fav',
				type : type,
				id : id
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#video_action_result_cont").css("display","block");
					$("#video_action_result_cont").html(data);
				}
			},'text');
		};
		
		
		this.subscriber = function(user,type,result_cont){
			var self = this;
			$("#"+result_cont).css("display","block");
			$("#"+result_cont).html(this.loading);
			
			$.post(page, 
			{ 	
				mode : type,
				subscribe_to : user
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#"+result_cont).css("display","block");
					$("#"+result_cont).html(data);
				}
			},'text');
		};



		this.add_friend = function (uid,result_cont){
			$("#"+result_cont).css("display","block");
			$("#"+result_cont).html(this.loading);
			
			$.post(page, 
			{ 	
				mode : 'add_friend',
				uid : uid
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#"+result_cont).css("display","block");
					$("#"+result_cont).html(data);
				}
			},'text');
		};
		
		
		this.block_user = function (user,result_cont){
			$("#"+result_cont).css("display","block");
			$("#"+result_cont).html(this.loading);
			
			$.post(page, 
			{ 	
				mode : 'ban_user',
				user : user
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$("#"+result_cont).css("display","block");
					$("#"+result_cont).html(data);
				}
			},'text');
		};
		
		
		this.rate_comment = function (cid,thumb,type,typeid){

			$.post(page, 
			{ 	
				mode : 'rate_comment',
				thumb : thumb,
				cid : cid,
				type : type,
				typeid : typeid
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{

					if(data.msg!='')
						alert(data.msg)
					if(data.rate!='')
						$("#comment_rating_"+cid).html(data.rate);
				}
			},'json');
		}
		
		
		this.delete_comment = function (cid,type){

			$.post(page, 
			{ 	
				mode : 'delete_comment',
				cid : cid,
				type : type
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{

					if(data.msg)
					{
						alert(data.msg);
						$("#comment_"+cid).fadeOut("slow");
						$("#spam_comment_"+cid).fadeOut("slow");;
						
					}
					if(data.err)
						alert(data.err);
				}
			},'json');
		};

		this.add_comment_js = function (form_id,type){
			$("#add_comment_result").css("display","block");
			$("#add_comment_result").html(this.loading);
			$("#add_comment_button").attr("disabled","disabled");
					
			//var captcha_enabled =  $("#"+form_id+" input:#cb_captcha_enabled").val();
			
			//First we will get all values of form_id and then serialize them
			//so we can forward details to ajax.php
			
			var formObjectData = $('#'+form_id).serialize()+'&mode=add_comment';
			
			$.post(page,formObjectData,
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					
					$("#add_comment_button").attr("disabled","");
					
					$("#add_comment_result").css("display","block");
					if(data.err!='')
					{
						$("#add_comment_result").html(data.err);
					}
					if(data.msg!='')
						$("#add_comment_result").html(data.msg);
					
					if(data.cid)
					{
						get_the_comment(data.cid,"#latest_comment_container");
						//$("#"+form_id).slideUp();
					}
				}
			},'json');
		};
		
		this.get_the_comment = function (id,div){

			$(div).html(this.loading);
			$.post(page, 
			{ 	
				mode : 'get_comment',
				cid : id
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{		
					$(div).css("display","none");
					$(div).html(data).fadeIn("slow");
				}
			},'text');
		};
		
		this.add_playlist = function (mode,vid,form_id,objtype){
			$("#playlist_form_result").css("display","block");
			$("#playlist_form_result").html(this.loading);
			switch(mode)
			{
				case 'add':
				{
					$.post(page, 
					{ 	
						mode : 'add_playlist',
						id : vid,
						objtype : objtype,
						pid : $("#playlist_id option:selected").val()
			},
					function(data)
					{
						if(!data)
							alert("No data");
						else
						{	
							if(data.err != '')
							{
								$("#playlist_form_result").css("display","block");
								$("#playlist_form_result").html(data.err);
							}
							
							if(data.msg!='')
							{
								$("#playlist_form_result").css("display","block");
								$("#playlist_form_result").html(data.msg);
								$("#"+form_id).css("display","none");
							}	
							
						}
					},'json');
				}
				break;
				
				case 'new':
				{

					$.post(page, 
					{ 	
						mode : 'add_new_playlist',
						id : vid,
						objtype : objtype,
						plname : $("#playlist_name").val()
			},
					function(data)
					{
						if(!data)
							alert("No data");
						else
						{	
							if(data.err )
							{
								$("#playlist_form_result").css("display","block");
								$("#playlist_form_result").html(data.err);
							}
							
							if(data.msg)
							{
								$("#playlist_form_result").css("display","block");
								$("#playlist_form_result").html(data.msg);
								$("#"+form_id).css("display","none");
							}	
							
						}
					},'json');
				}
				break;
			}
		};
		
		
		/**
		 * Function used to add and remove video from qucklist
		 * THIS FEATURE IS SPECIALLY ADDED ON REQUEST BY JAHANZEB HASSAN
		 */
		this.add_quicklist = function (obj,vid){
			
			$(obj).attr('src',this.imageurl+"/ajax-loader.gif");
			$(obj).css('background-position',"-200px 200px");
			
			$.post(page, 
			{ 	
				mode : 'quicklist',
				todo : 'add',
				vid : vid
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$(obj).attr('src',this.imageurl+"/dot.gif");
					$(obj).css('background-position',"-0px -0px");
					$(obj).removeClass('add_icon');
					$(obj).addClass('check_icon');
					$(obj).removeAttr('onClick');
					load_quicklist_box();
				}
			},'text');
		};


		this.remove_qucklist = function(obj,vid){
			var self = this;
			
			$.post(page, 
			{ 	
				mode : 'quicklist',
				todo : 'remove',
				vid : vid
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					$(obj).slideUp();
					$(obj).hide();
				}
			},'text');
		};
		
		/**
		 * Function used to load quicklist
		 */
		this.load_quicklist_box = function(){
			var self = this;
			
			$.post(page, 
			{ 	
				mode : 'getquicklistbox'
			},
			function(data)
			{
				data = $.trim(data);
				if(!data)
					$("#quicklist_box").css("display","none");
				else
				{			
						$("#quicklist_box").css("display","block");
						$("#quicklist_box").html(data);
						
					if($.cookie("quick_list_box")!="hide")
					{
						$("#quicklist_cont").css("display","block");
					}
				}
			},'text');
		};
		
		this.clear_quicklist = function(){
			var self = this;
			$.post(page, 
			{ 	
				mode : 'clear_quicklist'
			},
			function(data)
			{
				load_quicklist_box();
			},'text');
		};
		
		this.quick_show_hide_toggle = function(obj){
			var self = this;
			$(obj).slideToggle()
			
			if($.cookie("quick_list_box")=="show")
				$.cookie("quick_list_box","hide")	
			else
				$.cookie("quick_list_box","show")
		};
		
		/**
		 * Function used to set cookies
		 */
		this.ini_cookies = function(){
			var self = this;
			if(!$.cookie("quick_list_box"))
				$.cookie("quick_list_box","show")
		};
		
		
		this.get_group_info = function(Div,li){
			var self = this;
			if( $(Div).css("display")=="none") {
				$("#group_info_cont > div").slideUp();
				$("#group_info_cont "+Div).slideDown();
				$(".group_detail_tabs .selected").removeClass("selected");
				$(li).addClass("selected");
			}
		};

	
		this.show_menu = function(menu,load_from_hash){
			var self = this;
			if(window.location.hash && load_from_hash)
			{
				var thehash = window.location.hash;
				show_menu(thehash.substr(9),false);
				return false;
			}
			window.location.hash = 'current_'+menu;
			if(this.current_menu!=menu)
				hide_menu()
			$("#"+menu).show()
			this.current_menu = menu;	
			$("."+menu).addClass("selected");
		};

		this.hide_menu = function(){
			var self = this;
			if(this.current_menu!='')
			{
				$("#"+this.current_menu).hide(); 
				$("."+this.current_menu).removeClass("selected");
				return true;
			}
		};
		
		
		this.to_reply = function(cid){
			var self = this;
			$("#reply_to").val(cid);
			window.location = "#add_comment";
		};

		this.spam_comment = function(cid,type,typeid){

			$.post(page, 
			{ 	
				mode : 'spam_comment',
				cid : cid,
				type : type,
				typeid : typeid
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
				{
					if(data.msg)
					{
						$("#comment_"+cid).hide();
						$("#spam_comment_"+cid).fadeIn("slow");
					}
					if(data.err)
					{
						alert(data.err)
					}
				}
			},'json');
			var self = this;
		};


		this.hq_toggle = function(nplayer_div,hq_div){
			var self = this;
			if(this.has_hq_function)
			{
				var nplayer_div = nplayer_div;
				var hq_div = hq_div;
				hq_function();
				return false;
			}
			if($(nplayer_div).css("display")=='block')
			{
				if(this.normal_player_html=='')
				this.normal_player_html = $(nplayer_div).html();
				$(nplayer_div).html("");
			}else
			{
				if(this.normal_player_html!='')
				$(nplayer_div).html(this.normal_player_html);
			}
			if($(hq_div).css("display")=='block')
			{
				if(this.hq_player_html=='')
				this.hq_player_html = $(hq_div).html();
				$(hq_div).html("");
			}else
			{
				if(this.hq_player_html!='')
				$(hq_div).html(this.hq_player_html);
			}
			$(nplayer_div+","+hq_div).toggle()
		};

		/** 
		 * Funcion autplay playlist
		 */
		this.swap_auto_play = function(){
			if($.cookie("auto_play_playlist")=="true")
			{
				$.cookie("auto_play_playlist","false",{path:"/"});
				window.location = document.location;
				$('#ap_status').html("off");
				
			}else
			{
				$.cookie("auto_play_playlist","true",{path:"/"});
				window.location = document.location;
				$('#ap_status').html("on");
			}
			var self = this;
		};

		this.collection_actions = function(form,mode,objID,result_con,type,cid){
			var self = this;
			$(result_con).css("display","block");
			$(result_con).html(this.loading);
			
			switch(mode)
			{
				case 'add_new_item':
				{
					
					$.post(page,
						   {
							   mode: mode,
							   cid: $("#"+form+' #collection').val(),
						   	   obj_id: objID,
							   type: type
						   },
						   function(data)
						   {
								if(!data)
									alert("No Data returned");
								else
								{
									
									if(data.msg)
										$(result_con).html(data.msg);
										
									if(data.err)
										$(result_con).html(data.err);		
								}
						   },'json')
				}
				break;
				
				case "remove_collection_item":
				{
					$("#"+form).hide();
					$.post(page,
						   {
								mode: mode,
								obj_id: objID,
								type: type,
								cid: cid   
						   },
						   function(data)
						   {
								if(!data)
								{
									alert("No Data Returned");
									$(result_con+"_"+objID).hide();
									$("#"+form).show();
								}
								else
								{
									if(data.err)
									{
										alert(data.err);
										$(result_con+"_"+objID).hide();
										$("#"+form+objID).show();
									}
									
									if(data.msg)
									{
										$(result_con).html(data.msg);
										$("#"+form+"_"+objID).slideUp(350);	
									}
											
								}
						   },'json')	
					
				}
			}
			
			return false;
		};

		// Simple function to open url with javascript
		this.openURL = function(url) {
			var self = this;
			document.locati= url;
		};



		this.get_item = function(obj,ci_id,cid,type,direction){
			var self = this;
			var btn_text = $(obj).html();
			$(obj).html(this.loading);
				
			$.post(page,
				   {
					   mode : 'get_item',
					   ci_id: ci_id,
					   cid : cid,
					   type: type,
					   direction: direction
				   },
				   function(data)
				   {
						if(!data)
						{
							alert('No '+type+' returned');
							$(obj).text(btn_text);
						} else {
							var jsArray = new Array(type,data['cid'],data['key']);
							construct_url(jsArray);
							$("#collectionItemView").html(data['content']);
						}
				   },'json')
		};

		this.construct_url = function(jsArr){
			var url;
			if(Seo == 'yes')
			{
				url = '#!/item/'+jsArr[0]+'/'+jsArr[1]+'/'+jsArr[2];
				window.location.hash = url
			} else {
				url	= '#!?item='+jsArr[2]+'&type='+jsArr[0]+'&collection='+jsArr[1];
				window.location.hash = url
			}
			var self = this;
		};

		this.onReload_item = function(){
			var comURL,
				regEX;		
			if(window.location.hash)
			{
				comURL = window.location.href;
				if(Seo == 'yes')
				{	
					regEX = RegExp('\/item.+#!');
					if(regEX.test(comURL))
					{
						comURL = comURL.replace(regEX,'');
						window.location.href = comURL;
					}
				} else {
					regEX = RegExp('\\\?item.+#!');
					if(regEX.test(comURL))
					{	comURL = comURL.replace(regEX,'')		
						window.location.href = comURL;
					}
				}
			}
			var self = this;
		};

		this.pagination = function(object,cid,type,pageNumber){
			var self = this;
			var obj = $(object), objID = obj.id, 
				paginationParent = obj.parent(), paginationParentID, paginationInnerHTML = obj.html();
			
			if(paginationParent.attr('id'))
				paginationParentID = parent.attr('id')
			else
			{	paginationParent.attr('id','loadMoreParent'); paginationParentID = paginationParent.attr('id'); }
					
			newCall = 
			$.ajax({
				url: self.page,
				type: "post",
				dataType: "json",
				data: { 
					mode: "moreItems", 
					page : pageNumber, 
					cid: cid, 
					type: type 
				},
				beforeSend: function() { obj.removeAttr('onClick'); obj.html(self.loading) },
				success : function(data) { 
								if(data['error'])
								{
									if(object.tagName == "BUTTON")
										obj.attr('disabled','disabled');
									obj.removeAttr('onClick'); obj.html('No more '+type);	
								} else {
									$('#collectionItemsList').append(data['content']); 
									$('#NewPagination').html(data['pagination']);
									obj.html(paginationInnerHTML);
								}
							}		
			});
		};

		this.ajax_add_collection = function(obj){
			var self = this;
			var formID = obj.form.id, Form = $('#'+formID),
				This = $(obj), AjaxCall, ButtonHTML = This.html(),
				Result = $('#CollectionResult');	
			AjaxCall = 
			$.ajax
			({
				url: self.page,
				type: "post",
				dataType: "json",
				data: "mode=add_collection&"+Form.serialize(),
				beforeSend: function() { if(Result.css('display') == 'block') Result.slideUp('fast'); This.attr('disabled','disabled'); This.html(this.loading) },
				success: function(data) {
							if(data.msg)
							{
								$('#CollectionDIV').slideUp('fast');
								Result.html(data['msg']).slideDown('fast');
								this.collectionID = data['id'];
							}
							else
							{
								Result.html(data['err']).slideDown('fast');
								This.removeAttr('disabled'); This.html(ButtonHTML);
							}
						 }
			});	
		};

		this.getDetails = function(obj){
			var forms = getInputs(obj), ParamArray = new Array(forms.length);
				
			$.each(forms,function(index,form) {
					query = $("#"+form.id+" *").serialize();
					query += "&mode=ajaxPhotos";
					ParamArray[index] = query;
			})
				
			return ParamArray;
			var self = this;
		};

		this.getName = function(File){
			var self = this;
			var url = File;
			var filename = url.substring(url.lastIndexOf('/')+1);
			return filename;
		};

		this.viewRatings = function(object,pid){
			var self = this;
			var obj = $(object), innerHTML = obj.html();
			if(document.getElementById('RatingStatContainer'))
					$("#RatingStatContainer").toggle();
			else
			{       
					loadAjax = 
					$.ajax
					({
							url:page,
							type: "post",
							dataType: "text",
							data: { mode:"viewPhotoRating", photoid:pid },
							beforeSend: function() { obj.html(this.loading); },
							success:function(data) {
									obj.html(innerHTML); 
									if(data)
									{
											$("<div />").attr('id','RatingStatContainer')
											.addClass('clearfix')
											.css({
													"padding" : "8px",
													"font" : "normal 11px Tahoma",
													"border" : "1px solid #ccc",
													"backgroundColor" : "#FFF"     
											}).html(data).fadeIn(350).insertAfter(obj);
									} else {
											obj.removeAttr('onclick');
											alert("Photo has not recieved any rating yet.");        
									}
							}       
					});
			}
		};

		this.showAdvanceSearch = function(simple,advance,expandClass,collapseClass){
			var simpleObj = $("#"+simple); var advanceObj = $("#"+advance);
			var	value = $('#SearchType').val();
			//simpleObj.toggle();
			advanceObj.toggle();
			if(advanceObj.css('display') == 'block')	
				advanceObj.children().hide().filter('#'+value).show();
			$('.'+expandClass).toggleClass(collapseClass);
			var self = this;
		};

		this.toggleCategory = function(object,perPage){
			var obj = $(object), childTarget = obj.attr('alt'), child = $("#"+childTarget),
				childparts = childTarget.split("_"), childID = childparts[0];
			var browser = $.browser.msie; var browserVersion = $.browser.version;
			
			if(child.css('display') == "none")
			{
				child.slideDown(350);
				if(browser && browserVersion == "7.0")
					child.addClass('internetExplorer7CategoryToggleFix');
				$.cookie(childID,'expanded',{ expires: 1, path: '/' });
				obj.removeClass('none').addClass('block');	
			} else {
				child.slideUp(350);
				if(browser && browserVersion == "7.0")
					child.removeClass('internetExplorer7CategoryToggleFix');		
				$.cookie(childID,'collapsed',{ expires: 1, path: '/' });
				obj.removeClass('block').addClass('none');		
			}
			var self = this;
		};

		this.loadObject = function(currentDOM,type,objID,container){
			var self = this;
			var object = new Array(4);
				object['this'] = currentDOM, object['type'] = type,
				object['objID'] = objID, object['container'] = container;
				
			var obj = $(object['this']);
			
			{
				obj.parent().css('position','relative');
			
				$.ajax({
					url : self.page,
					type : 'POST',
					dataType : 'json',
					data  : ({ mode : 'channelFeatured',
							   contentType : object['type'],
							   objID : object['objID']
							}),
					beforeSend : function()
					{
						obj.find('img').animate({ opacity : .5 });
						$("#"+object['container']).animate({ opacity : .5 });
					},
					success : function(data)
					{
						if(data['error'])
						{
							obj.find('img').animate({ opacity : 1 });
							$("#"+object['container']).animate({ opacity : 1 });
							alert(data['error']);
						}
						else
						{
							obj.parent().children('.selected').removeClass('selected');
							obj.addClass('selected');						
							obj.find('img').animate({ opacity : 1 });				
							$("#"+object['container']).html(data['data']);					
							$("#"+object['container']).animate({ opacity : 1 });
						}
					}
				})	
			}
		};


		this.channelObjects = function(object,div,type,user,assign){
			var self = this;
			var obj = $(object), curRel = obj.attr('rel'),
				DIV = $(div), oldRel = obj.parents('ul').find('a.selected').attr('rel');
			
			if(curRel)
			{
				if($("#"+curRel).css('display') == 'block')
					return false;
				else
				{
					obj.parents('ul').find('a.selected').removeClass('selected');
					obj.addClass('selected');
					
					$("#"+oldRel).hide();
					$("#"+curRel).show();		
				}
			} else {
				var newRel = type+"DIV";
				obj.attr('rel',newRel);
				$.ajax({
				 url : self.page,
				 type : "POST",
				 dataType : "json",
				 data : ({ mode : "channelObjects", content : type, user : user, assign : assign}),
				 beforeSend : function() { obj.append(this.loading_img) },
				 success : function(data)
				 {
					obj.find('img').remove();
					obj.parents('ul').find('a.selected').removeClass('selected');
					obj.addClass('selected');		
				
					$("#"+oldRel).hide();
					$("<div></div>").attr('id',newRel).addClass($("#"+oldRel).attr('class')).html(data.html).appendTo(DIV); 
				 }
				})	
			}
		};

		var comments_voting = 'no';
		this.getComments = function(type,type_id,last_update,pageNum,total,object_type,admin){
			var self = this;
			$('#comments').html("<div style='padding:5px 0px;'>"+this.loading+"</div>");
			$.ajax({
			  type: 'POST',
			  url: self.page,
			  data:  {mode:'getComments',
			  page:pageNum,type:type,
			  type_id:type_id,
			  object_type : object_type,
			  last_update : last_update,
			  total_comments : total,
			  comments_voting : comments_voting,admin : admin},
			  success: function(data)
			  {
				$('#comments').hide();
				$('#comments').html(data);
				$('#comments').fadeIn('slow');
			  },
			  dataType: 'text'
			});
		};

		this.checkUncheckAll = function(theElement) {
		     var theForm = theElemeform, z = 0;
			 
				for(z=0; z<theForm.length;z++){
					if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
					  theForm[z].checked = theElement.checked;
					}
				}
				var self = this;
		};
			
		/**
		 * Function used to rate object
		 */
		this.rate = function(id,rating,type){
			var self = this;
			var page = this.baseurl+'/ajax.php';
			$.post(page, 
			{ 	
				mode : 'rating',
				id:id,
				rating:rating,
				type:type
			},
			function(data)
			{
				if(!data)
					alert("No data");
				else
					$("#rating_container").html(data);
			},'text');
		};

		this.setPageHash = function(Page){
			var self = this;
			// Removing this.baseurl
			var hashPart = Page.replace(this.baseurl,"");
			var prevHash = window.location.hash.replace("#!",'');
			//alert(hashPart+"       "+prevHash);
		    {
				window.location.hash = "#!"+hashPart;
			}
		};

		this.callURLParser = function(){
			var self = this;
			var expression = /(\#![/a-zA-Z0-9=\.\&\-\_\?]*)/g,
				   location = window.location.href,
				   returned = location.match(expression),
				   lastVisited;
			if(returned)
			{
				lastVisited = returned[returned.length - 1];	   
				if(lastVisited)
					window.location.href = this.baseurl+lastVisited.replace("#!",'');	
			}
		};

		this.groupsAjax = function(event,selector,divSelector){
			var self = this;
			event.preventDefault(); // prevent from redirecting to URL
			var ajaxPage, onLink = false, PreserveHTML, ParentTag, DIV;
			if(divSelector == undefined)
				divSelector = "ajaxGroupResultContainer";
			if(selector.href) // Means function is on link
			{
				ajaxPage = selector.href;
				onLink = true;
				jqueryObj = $(selector);
				javaObj = selector;	
			} else {
				ajaxPage = selector.childNodes[0].href;
				jqueryObj = $(selector.childNodes[0]);
				javaObj = selector.childNodes[0];	
			}
			if(ajaxPage == "undefined") {
				alert("URL not found"); 
				return false;
			} else {
				PreserveHTML = jqueryObj.html();
				setPageHash(ajaxPage);
				//return false;
				if(onLink == true) {
					ParentTag = jqueryObj.parent().parent();
					ParentTag.children().filter('.selected').removeClass('selected');
					jqueryObj.parent().addClass('selected');
				} else {
					ParentTag = jqueryObj.parent();
					ParentTag.children().filter('.selected').removeClass('selected');
					jqueryObj.addClass('selected');
				}
				jqueryObj.html(this.loading_img);
				$("#"+divSelector).load(ajaxPage+" #"+divSelector+"",function(response, status, xhr){
						jqueryObj.html(PreserveHTML);
						if(document.getElementById('flag_item'))
							$('#flag_item').show();	
				});
			}
		};

		this.encode64 = function(input) {
			var self = this;
			var output = nStringMaker();
			var chr1, chr2, chr3;
			var enc1, enc2, enc3, enc4;
			var i = 0;

			while (i < input.length) {
				chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);

				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;

				if (isNaN(chr2)) {
					enc3 = enc4 = 64;
				} else if (isNaN(chr3)) {
					enc4 = 64;
				}

				output.append(this.keyStr.charAt(enc1) + this.keyStr.charAt(enc2) + this.keyStr.charAt(enc3) + this.keyStr.charAt(enc4));
		   }
		   
		   return output.toString();
		};

		this.decode64 = function(input) {
			var self = this;
			var output = nStringMaker();
			var chr1, chr2, chr3;
			var enc1, enc2, enc3, enc4;
			var i = 0;

			// remove all characters that are not A-Z, a-z, 0-9, +, /, or =
			input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

			while (i < input.length) {
				enc1 = this.keyStr.indexOf(input.charAt(i++));
				enc2 = this.keyStr.indexOf(input.charAt(i++));
				enc3 = this.keyStr.indexOf(input.charAt(i++));
				enc4 = this.keyStr.indexOf(input.charAt(i++));

				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;

				output.append(String.fromCharCode(chr1));

				if (enc3 != 64) {
					output.append(String.fromCharCode(chr2));
				}
				if (enc4 != 64) {
					output.append(String.fromCharCode(chr3));
				}
			}

			return output.toString();
		};

		this.getAllComments = function(type,type_id,last_update,pageNum,total,object_type,admin){
			 var self = this;
          $('#userCommentsList').html("<div style='padding:5px 0px;'>"+loading+"</div>");
          $.ajax({
            type: 'POST',
            url: page,
            data: {
					mode:'getComments',
					page:pageNum,
					type:type,
					type_id:type_id,
					object_type : object_type,
					last_update : last_update,
					total_comments : total,
					comments_voting : comments_voting,
					admin : admin
            },
            success: function(comments){
              $("#userCommentsList").html(comments);
            },
            dataType: 'text'
          });
      };

		this.addToFav = function(type,id){
			 var self = this;
			$('#messageFav').show();
			$.post(page, {   
				mode : 'add_to_fav',
				type : type,
				id : id
			},
			function(data){
			   if(!data){
			        alert("No data");
			   }else{
					$("#messageFav").html(data);
					setTimeout(function(){
						$('#messageFav').hide();
					}, 5000);
			   }
			},'text');
		};

		this.subscribeToChannel = function(user,type,result_cont){
			 var self = this;
	       $('#messageFav').show();
	       $.post(page, {  
	           mode : type,
	           subscribe_to : user
	       },
	       function(data){
	           if(!data){
	               alert("No data");
	           }
	           else{
	               $("#messageFav").html(data);
	               setTimeout(function(){
	                   $('#messageFav').hide();
	               }, 5000);
	           }
	       },'text');
	   };

	   this.debounce = function(func, wait, immediate){
	   	var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
	   }

	};

	window._cb = new _cb();
	window._cb.setRemoteId();

})(window);