<?php
/**
 * ClipBucket Player v2 Settings File
 */
 
include('../../includes/config.inc.php');

header ("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<settings>
	<options>
		<autoResize value="true"/>
		<playerWidth value="500"/>
		<playerHeight value="350"/>
		
		<autoLoad value="true"/>
		<autoPlay value="false"/>
		<playContinuously value="true"/>
		<jumpToNextCategory value="false"/>
		<loop value="false"/>
		
		<keepAspectRatio value="true"/>
		
		<volume value="50"/>
		<controlsPadding value="5"/>
		
		<showPreviewImage value="true"/>
		<showShareBtn value="false"/>
		<showHidePlaylistBtn value="true"/>
		<showFullScreenBtn value="true"/>
		<showMiddlePlayBtn value="true"/>
		
		<showWatermark value="true"/>
		<watermarkPos value="BR"/>
	
		<spaceKeyListener value="true"/>
		<clickListener value="true"/>
		
		<playlistSize value="200"/>
		<playlistThumbWidth value="70"/>
		<playlistThumbHeight value="50"/>
		<playlistThumbPadding value="10"/>
		
		<descriptionSize value="100"/>
		<descriptionTextPadding value="10"/>
	</options>
	
	<colors>
		<playerBackground value="0x000000"/>
		<preloader background="0x000000" bckAlpha="70" text="0xCCCCCC" circle="0x559BB5"/>
		<middlePlayBtn arrowNormal="0xFFFFFF" bckNormal="0x000000" alphaNormal="60" arrowOver="0xFFFFFF" bckOver="0x000000" alphaOver="80" arrowDown="0xFFFFFF" bckDown="0x000000" alphaDown="100"/>
		<controllerBackground value="0x000000"/>
		<controllerButtons normal="0xCCCCCC" over="0xFFFFFF" down="0xFFFFFF"/>
		<volume border="0x666666" background="0x000000" speaker="0xCCCCCC" bar="0x559BB5"/>
		<volumeScrubBtn brdNormal="0xCCCCCC" bckNormal="0x000000" brdOver="0xFFFFFF" bckOver="0x000000" brdDown="0xFFFFFF" bckDown="0x000000" />
		<progressBar border="0x666666" background="0x000000" elapse="0x559BB5" download="0x999999"/>
		<progressScrubBtn brdNormal="0xCCCCCC" bckNormal="0x000000" brdOver="0xFFFFFF" bckOver="0x000000" brdDown="0xFFFFFF" bckDown="0x000000" />
		<times value="0xCCCCCC"/>
		<share background="0x000000" bckAlpha="40" boxBck="0x000000" boxBckAlpha="70" labels="0xFFFFFF" errorText="0x00FF00" text="0x000000" textBck="0xFFFFFF" textBrd="0x000000" btnsNormal="0xCCCCCC" btnsOver="0xFFFFFF" btnsDown="0xFFFFFF"/>
		<playlist background="0x151515" line="0x202020" thumbBck="0x000000" thumbNormalAlpha="30" thumbOverAlpha="70" thumbSelectedAlpha="100" title="0x559BB5" description="0xCCCCCC"/>
		<category bckNormal="0x101010" titleNormal="0xCCCCCC" bckOver="0x000000" titleOver="0xFFFFFF" bckSelected="0x559BB5" titleSelected="0x000000"/>
		<description background="0x151515" line="0x202020" title="0xFFFFFF" description="0xCCCCCC"/>
		<scrollBar trackBar="0xCCCCCC" scrubBrdNormal="0xCCCCCC" scrubBckNormal="0x000000" scrubBrdOver="0xFFFFFF" scrubBckOver="0x000000" scrubBrdDown="0xFFFFFF" scrubBckDown="0x000000"/>
	</colors>
	
	<videos>
		<category title="Animations">
        <?php
			$ep_videos = get_ep_videos();
			foreach($ep_videos as $video)
			{
		?>
			<video>
				<videoPath value="<?php echo get_video_file($video,true,true) ?>"/>
				<previewImage value="<?=getthumb($video,'big')?>"/>
				<thumbImage value="<?=getthumb($video)?>"/>
                <aspectRatio value="4:3"/>
                <totalTime value="<?=$video['duration']?>"/>
                <!-- <watermarkPath value="<?=website_logo()?>"/>
                <watermarkLink value="<?=video_link($video)?>"/> -->
				<title>
					<![CDATA[<?=$video['title']?>]]>
				</title>
				<description>
					<![CDATA[<?=$video['description']?>]]>
				</description>
			</video>
        <?php
			}
		?>
		</category>
	</videos>
</settings>