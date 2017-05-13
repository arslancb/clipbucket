function new_player_height (videoid) {
	var player_ratio = 1.77777;

	var native_player = $(".cb_video_js_"+videoid+"-dimensions"); 
	var embed_player = $("#cb_player_"+videoid);

	var native_player_width = native_player.width();
	var player_container_width = embed_player.width();
	


	var native_player_height  = native_player_width/player_ratio;
	var embed_player_height = player_container_width/player_ratio;

	native_player.css("height",native_player_height+"px");
	embed_player.css("height",embed_player_height+"px");
}
