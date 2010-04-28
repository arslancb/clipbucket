<?php
require('../../includes/common.php');
?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
	<title>Example XSPF playlist</title>
	<tracklist>


	<?php
      $playlist = $_GET['playlist'];
      $playlist = explode('_',$playlist);
      $pid = $playlist[0];
      $vid = $playlist[1];
      $playlist = $cbvid->get_playlist_items(mysql_clean($pid));
     
      if($playlist)
      {
		  foreach($playlist as $video)
          {
             if($vid==$video['videoid'])
		  	{
	?>
    
    	<track>
			<title><?=$video['title']?></title>
			<info><?=videoLink($video)?></info>
			<annotation><?=$video['description']?></annotation>
			<creator><?=$video['username']?></creator>
			<location><?=get_video_file($video,true,true)?></location>
			<image><?=getThumb($video)?></image>
		</track>
        
	<?php
			}
		  }
          foreach($playlist as $video)
          {
             if($vid!=$video['videoid'])
		  	{
      ?>	
		<track>
			<title><?=$video['title']?></title>
			<info><?=videoLink($video)?></info>
			<annotation><?=$video['description']?></annotation>
			<creator><?=$video['username']?></creator>
			<location><?=videoLink($video)?></location>
			<image><?=getThumb($video)?></image>
            <provider>html</provider>
		</track>
          <?php
			}
          }
      }
      ?>
	</tracklist>
</playlist>