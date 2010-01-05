// JavaScript Document
/**
 *
 * ClipBucket Tabs
 * Author: Arslan Hassan clip-bucket.com
 * 
 * ClipBucket License (CBLA)
 * 
 */
	
var current_tab = '';
var current_div = '';
function display_tab(Li,divid)
{ 
	if(current_tab!='')
		$(current_tab).attr("class","");
	if(current_div!='')	
		$('#'+current_div).hide();
		
	$(Li).attr("class","selected");
	$('#'+divid).show();
	
	window.location.hash = 'current_'+divid.substr(4,3);
	$("#main_form").attr("action",window.location.hash); 
	
	current_tab = Li;
	current_div = divid;
}