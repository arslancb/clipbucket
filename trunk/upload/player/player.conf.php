<!-- These are sample configuration properties... however, you may wish to add more! Please add as many configuration properties as you reasonably can -->
<config>
	<AUTO_START>true</AUTO_START>
	<AUTORESIZE>true</AUTORESIZE>
	
	<!-- time in seconds -->
	<BUFFER_TIME>3</BUFFER_TIME>
	
	<!-- options: Off, On -->
	<CONTROLS>On</CONTROLS>
	<LABEL_ON_START_CLICK>Press start to play</LABEL_ON_START_CLICK>
	<LOOPING>true</LOOPING>
	
	<!-- if we must play commercials before video file: true, false -->
	<PLAY_COMMERCIALS>false</PLAY_COMMERCIALS>
	
	<!-- relative path to videos XML file -->
	<VIDEO_FILE_PATH><?php echo $_GET['baseurl'] ?>/player/panel.xml.php</VIDEO_FILE_PATH>
	<VIDEOS_PATH><?php echo $_GET['baseurl'] ?>/files/videos/</VIDEOS_PATH>
	<PHP_PATH>./</PHP_PATH>
	
	<!-- number 0-100 -->
	<VOLUME>50</VOLUME>
	<skin_color>0x550000</skin_color>
	<speaker_icon>0x6C6C6C</speaker_icon>
	<time_color>0x000000</time_color>
	<video_bar_back_color>0x474747</video_bar_back_color>
	<video_bar_progress_color>0xED0000</video_bar_progress_color>
	
	<!-- options: Up Left, Down Left, Up Right, Down Right -->
	<LOGO_APPEARANCE>Down Right</LOGO_APPEARANCE>
	<LOGO_CLICK_URL><?php echo $_GET['baseurl'] ?></LOGO_CLICK_URL>
	
	<!-- relative path to a non-progressive JPG image -->
	<LOGO_PATH><?php echo $_GET['baseurl'] ?>/player/logo.png</LOGO_PATH>
	<SHOW_LOGO>true</SHOW_LOGO>
	<visible>true</visible>
	<minHeight>375</minHeight>
	<minWidth>450</minWidth>
	
	<!-- number 0-100 -->
	<LOGO_ALPHA>80</LOGO_ALPHA>
	<VIDEO_ALPHA>100</VIDEO_ALPHA>
	
	<!-- options: _self, _blank, _parent, _top -->
	<LOGO_CLICK_URL_TARGET>_blank</LOGO_CLICK_URL_TARGET>
	
	<!-- number 0-100 -->
	<videoBrightness>50</videoBrightness>
	
	<!-- options: Up, Down -->
	<CONTROLS_LAYOUT>Down</CONTROLS_LAYOUT>
	
	<!-- work only if LOOPING == false. options: true, false -->
	<auto_reset_playhead>false</auto_reset_playhead>
	<video_back_color>0x000000</video_back_color>
	<panel_back_color>0x9F9F9F</panel_back_color>
	
	<!-- number 0-100 -->
	<panel_back_alpha>30</panel_back_alpha>
</config>