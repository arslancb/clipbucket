function headerFooter(){
	var headerheight = "";
	var footerheight = "";
	headerheight = $("#header").outerHeight();
	footerheight = $("#footer").outerHeight();
	
	$("#container").css('padding-top',headerheight+'px');
	$("#container").css('padding-bottom',footerheight+20+'px');
}

$(document).ready(function(){
	//header and footer position
	headerFooter();
});

//on resize functions
$(window).resize(function(){
	//header and footer position
 	headerFooter();
});