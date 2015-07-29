// player height
function player_height (argument) {
	var playerWidth = $('.video-js').width();
	var playerHeight = playerWidth * (9/16);
	$('.video-js').css('height', playerHeight+"px");
	return playerHeight;
}
	
$(window).on("resize", function() {
	player_height();
});

$(document).ready(function(){
	player_height();
	$(".vjs-cb-logo-cont").css({
		'float': 'right',
		'width' :'3.5em',
		'height': '2.5em',
		'margin': '7px 0 0 0',
		'cursor': 'pointer',
		'display' : 'block !important'
	});
});