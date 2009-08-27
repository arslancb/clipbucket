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
