// JavaScript Document

	function GetParam( name )
	{
	  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	  var regexS = "[\\?&]"+name+"=([^&#]*)";
	  var regex = new RegExp( regexS );
	  var results = regex.exec( window.location.href );
	  if( results == null )
		return "";
	  else
		return results[1];
	}

	function Confirm_Delete(delUrl) {
	  if (confirm("Are you sure you want to delete")) {
		document.location = delUrl;
	  }
	}
	
	function Confirm_Uninstall(delUrl) {
	  if (confirm("Are you sure you want to uninstall this plugin ?")) {
		document.location = delUrl;
	  }
	}

	function Confirm_DelVid(delUrl) {
	  if (confirm("Are you sure you want to delete this video?")) {
		document.location = delUrl;
	  }
	}
	
	function removeVideo(formname)
	{
		if (confirm('Are you sure you want to remove this video?'))
		{
			document.formname.submit();
		}
	}
	
	function reloadImage(captcha_src)
	{
	img = document.getElementById('captcha');
	img.src = captcha_src+'?'+Math.random();
	}
	
	//Is Used to Validate Form Fields
	function validate_required(field,alerttxt)
	{
	with (field)
	{
	  if (value==null||value=="")
 	 {
 	 alert(alerttxt);return false;
 	 }
 	 else
 	 {
	  return true;
	  }
	}
	}
	
	//Validate the Upload Form
	function validate_upload_form(thisform)
	{
	with (thisform)
	{
			if (validate_required(title,"Title must be filled out!")==false)
 	 		{
		 title.focus();return false;
			}
			if (validate_required(description,"Description must be filled out!")==false)
 			{
		 description.focus();return false;
			}
			if (validate_required(tags,"Plase Enter some tags for video")==false)
 			{
		 tags.focus();return false;
			}
			if (validate_required(category[0],"Select Category")==false)
 			{
		 	}
	
	}
	}
	
	//Validate the Add Category Form
	function validate_category_form(thisform)
	{
	with (thisform)
	{
			if (validate_required(title,"Title must be filled out!")==false)
 	 		{
		 title.focus();return false;
			}
			if (validate_required(description,"Description must be filled out!")==false)
 			{
		 description.focus();return false;
			}
	
	}
	}
	
	//Validate the Add Advertisment Form
	function validate_ad_form(thisform)
	{
	with (thisform)
	{
			if (validate_required(name,"Name must be filled out!")==false)
 	 		{
		 name.focus();return false;
			}
			if (validate_required(type,"Type must be filled out!")==false)
 			{
		 type.focus();return false;
			}
			if (validate_required(syntax,"Syntax Must Be Filled Out")==false)
 			{
		 syntax.focus();return false;
			}
			if (validate_required(code,"Code Must Be Filled Out")==false)
 			{
		 code.focus();return false;
			}
	}
	}
	
	
	//CHECKK ALL FUNCTIOn

		<!--
		function checkAll(wotForm,wotState) {
			for (a=0; a<wotForm.elements.length; a++) {
				if (wotForm.elements[a].id.indexOf("delete_") == 0) {
					wotForm.elements[a].checked = wotState ;
				}
			}
		}
		// -->

	function hide_active_sharing() {
	  hideDiv("flash_recent_videos");
	}
	
	function hideDiv(divname) {
	if (document.getElementById) { // DOM3 = IE5, NS6
	document.getElementById(divname).style.visibility = 'hidden';
	}
	else {
	if (document.layers) { // Netscape 4
	document.divname.visibility = 'hidden';
	}
	else { // IE 4
	document.all.divname.style.visibility = 'hidden';
	}
	}
	}
	
	function showDiv(divname) {
	if (document.getElementById) { // DOM3 = IE5, NS6
	document.getElementById(divname).style.visibility = 'visible';
	}
	else {
	if (document.layers) { // Netscape 4
	document.divname.visibility = 'visible';
	}
	else { // IE 4
	document.all.divname.style.visibility = 'visible';
	}
	}
	}
	var OnId =null;
	function SetId(ID){
 		if(OnId != null){
		OldElement = document.getElementById(OnId);
		OldElement.setAttribute("class", ''); //For Most Browsers
		OldElement.setAttribute("className", ''); //For Most Browsers
		}
		element = document.getElementById(ID);
		if(element !=null){
		element.setAttribute("class", 'currentTab'); //For Most Browsers
		element.setAttribute("className", 'currentTab'); //For Most Browsers
		OnId = ID;
		}
	}
	
	function innerHtmlDiv(Div,HTML){
		document.getElementById(Div).innerHTML=HTML;
	}
	
	
	function check_remote_url()
	{

		var page = baseurl+'/actions/file_downloader.php';
		var Val = $("#remote_file_url").val();
		
		$.post(page, 
		{ 	
			check_url	:	'yes' ,
			file_url : Val,
			file_name : file_name
		},				
		
		function (data) {
			if(data.err)
			{
				alert(data.err);
			}else{
				
				$("#remote_upload_div").html('<div class="progressWrapper"><div class="progressBarInProgress"></div><div>');
				var current_size = 0;
				var total_size = data.size;
				refresh_interval(file_name+'.'+data.ext,total_size);
				upload_file(Val,file_name);
				alert(data.size);
			}
		}, "json");
	}
	
	
	function upload_file(Val,file_name)
	{
		var page = baseurl+'/actions/file_downloader.php';
		$.post(page, 
		{ 	
			file_url : Val,
			file_name : file_name
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
				alert("Ho gaya");
		},'text');
	}
	
	function check_progess(file,total_size)
	{
		var page = baseurl+'/actions/get_file_size.php';
		$.post(page, 
		{ 	
			file:file,
		},
		
		function (data) {
			var current_size = data;
			return (total_size/current_size)*100;
		}, "text");
	
	}
	
	function refresh_interval(file,total_size)
	{
		var progress = check_progess(file,total_size)
		$("#remote_upload_div").html('<div class="progressWrapper"><div class="progressBarInProgress" style="width:'+progress+'%"></div><div>');
		
		if(progress<100)
			refresh_interval();
	}
	
	
	function go_hq(vid)
	{
		 var flashvars = {
		 htmlPage: document.location,
		settingsFile: "http://localhost/clipbucket/2.x/2/upload/player/cbplayer/settings.php?hqid="+vid
		};
		var params = {
		  allowFullScreen: "true"
		};
		swfobject.embedSWF("http://localhost/clipbucket/2.x/2/upload/player/cbplayer/videoPlayer.swf", 
										   "videoPlayer", "600", "350", "9.0.115",
										   "swfobject/expressInstall.swf", flashvars
										   , params);
	}
	
	
	/**
	 * Function used to delete video
	 */
	function delete_video(obj,vid,msg,url)
	{
		$("#"+obj+'-'+vid).click(function () {
			if (confirm(msg)) {
				document.location = url;
			}				
		});
	}
	
	
	/**
	 * Function used to load editor's pic video
	 */
	function get_ep_video(vid)
	{
		var page = baseurl+'/plugins/editors_pick/get_ep_video.php';
		$.post(page, 
		{ 	
			vid : vid,
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
				$("#ep_video_container").html(data);
		},'text');
	}
	
	
	/**
	 * Function used to load editor's pic video
	 */
	function get_video(type,div)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : type,
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
				$(div).html(data);
		},'text');
	}


	function rating_over(msg,disable)
	{
		if(disable!='disabled')
		$("#rating_result_container").html(msg);
	}
	function rating_out(msg,disable)
	{
		if(disable!='disabled')
		$("#rating_result_container").html(msg);
	}
	
	
	function submit_share_form(form_id,type)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : 'share_object',
			type : type,
			users : $("#"+form_id+" input:#users").val(),
			message : $("#"+form_id+" input:#message").val(),
			id : $("#"+form_id+" input:#objectid").val(),
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#share_form_results").css("display","block");
				$("#share_form_results").html(data);
			}
		},'text');
	}
	
	
	
	function flag_object(form_id,id,type)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : 'flag_object',
			type : type,
			flag_type : $("#"+form_id+" input:#flag_type").val(),
			id : id,
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
	}
	
	function add_to_fav(type,id)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : 'add_to_fav',
			type : type,
			id : id,
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
	}
	
	
	function subscriber(user,type)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : type,
			subscribe_to : user,
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#video_detail_result_cont").css("display","block");
				$("#video_detail_result_cont").html(data);
			}
		},'text');
	}
	
	
	function rate_comment(cid,thumb)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : 'rate_comment',
			thumb : thumb,
			cid : cid
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

	function add_comment_js(form_id,type)
	{
		var page = baseurl+'/ajax.php';
		$.post(page, 
		{ 	
			mode : 'add_comment',
			name : $("#"+form_id+" input:#name").val(),
			email : $("#"+form_id+" input:#email").val(),
			comment : $("#"+form_id+" textarea:#comment_box").val(),
			obj_id : $("#"+form_id+" input:#obj_id").val(),
			reply_to : $("#"+form_id+" input:#reply_to").val(),
			type : type,
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				$("#add_comment_result").css("display","block");
				if(data.err!='')
					$("#add_comment_result").html(data.err);
				if(data.msg!='')
					$("#add_comment_result").html(data.msg);
					
				if(data.cid!='')
				{
					get_the_comment(data.cid,"#latest_comment_container");
					$("#"+form_id).slideUp();
				}
			}
		},'json');
	}
	
	function get_the_comment(id,div)
	{
		var page = baseurl+'/ajax.php';
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
	}
	
	function add_playlist(mode,vid,form_id)
	{
		var page = baseurl+'/ajax.php';
		switch(mode)
		{
			case 'add':
			{
				$.post(page, 
				{ 	
					mode : 'add_playlist',
					vid : vid,
					pid : $("#playlist_id option:selected").val(),
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
					vid : vid,
					plname : $("#"+form_id+" input:#playlist_name").val(),
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
		}
	}