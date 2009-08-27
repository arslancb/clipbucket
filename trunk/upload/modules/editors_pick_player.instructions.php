<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Module : Editors Pick Player																	|
 | @ Module File : editors_pick_player.instructions.php {Instruction FILE}							|
 | @ Date : Jan, 21 2008																			|
 | @ License: Addon With ClipBucket																	|
 ****************************************************************************************************
*/
		$template_url = BASEURL.'/'.TEMPLATEFOLDER.'/'.TEMPLATE;
        $code = 
		'<img src="'.$template_url.'/images/'.LANG.'/editors_pick.png" /><div id="editors_pick" style="padding-bottom:10px">
		This content requires JavaScript and Macromedia Flash Player 7 or higher. <a href=http://www.macromedia.com/go/getflash/>Get Flash</a><br/><br/>
		</div>
		<script type="text/javascript">
		var ep = new FlashObject("'.BASEURL.'/modules/editors_pick_player.swf?xmlfile='.BASEURL.'/modules/editors_pick_player.php", "sotester", "340", "243", "9", "#FFFFFF");
        ep.addParam("wmode", "opaque");
        ep.addParam("allowFullScreen", "true");
		ep.write("editors_pick");
		</script>
		';
		Assign('editors_pick',$code);
	
?>