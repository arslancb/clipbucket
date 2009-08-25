// JavaScript Document
/**
 *
 * ClipBucket Tabs
 * Author: Arslan Hassan clip-bucket.com
 * 
 * ClipBucket License (CBLA)
 * 
 */$(document).ready(function(){
	$("#tabbed_div > div").hide();						  
    display_tab('#first_tab','div_1');
	
  });

		
		
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
	
	current_tab = Li;
	current_div = divid;
}