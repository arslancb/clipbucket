<?php
$videoid=$_GET["v"];
$format = 18;
$content= file_get_contents("http://youtube.com/get_video_info?video_id=$videoid");
parse_str($content);
$url = "http://www.youtube.com/get_video.php?video_id=" . $videoid . "&t=" . $token. "&fmt=".$format;
$headers = get_headers($url,1);

if(!is_array($headers['Location'])) {
$url = $headers['Location'];
}else {
foreach($headers['Location'] as $h){
if(strpos($h,"googlevideo.com")!=false){
$url = $h;
break;
}
}
}
if(isset($_GET["debug"])){
print "URI: $url<br/>" ;
echo "<pre>";print_r($headers);
die("it's all folks!");
}
header("Location: $url");

?>