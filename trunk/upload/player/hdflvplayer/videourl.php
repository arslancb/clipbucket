<?php

 function http_test_existance($url, $timeout = 10) {

                        $timeout = (int)round($timeout/2+0.00000000001);

                        $return = array();



                        ### 1 ###

                        $inf = parse_url($url);



                        if (!isset($inf['scheme']) or $inf['scheme'] !== 'http') return array('status' => -1);

                        if (!isset($inf['host'])) return array('status' => -2);

                        $host = $inf['host'];



                        if (!isset($inf['path'])) return array('status' => -3);

                        $path = $inf['path'];

                        if (isset($inf['query'])) $path .= '?'.$inf['query'];



                        if (isset($inf['port'])) $port = $inf['port'];

                        else $port = 80;



                        ### 2 ###

                        $pointer = fsockopen($host, $port, $errno, $errstr, $timeout);

                        if (!$pointer) return array('status' => -4, 'errstr' => $errstr, 'errno' => $errno);

                        socket_set_timeout($pointer, $timeout);



                        ### 3 ###

                        $head =

                        'HEAD '.$path.' HTTP/1.1'."\r\n".

                        'Host: '.$host."\r\n";



                        if (isset($inf['user']))

                             $head .= 'Authorization: Basic '.

                             base64_encode($inf['user'].':'.(isset($inf['pass']) ? $inf['pass'] : ''))."\r\n";

                             if (func_num_args() > 2) {

                                  for ($i = 2; $i < func_num_args(); $i++) {

                                       $arg = func_get_arg($i);

                                       if (

                                       strpos($arg, ':') !== false and

                                       strpos($arg, "\r") === false and

                                       strpos($arg, "\n") === false

                                       ) {

                                       $head .= $arg."\r\n";

                                  }

                             }

                        }

                        else $head .= 'User-Agent: Selflinkchecker 1.0 (http://aktuell.selfhtml.org/artikel/php/existenz/)'."\r\n";



                        $head .= 'Connection: close'."\r\n"."\r\n";



                        ### 4 ###

                        fputs($pointer, $head);



                        $response = '';



                        $status = socket_get_status($pointer);

                        while (!$status['timed_out'] && !$status['eof']) {

                             $response .= fgets($pointer);

                             $status = socket_get_status($pointer);

                        }

                        fclose($pointer);

                        if ($status['timed_out']) {

                             return array('status' => -5, '_request' => $head);

                        }



                        ### 5 ###

                        $res = str_replace("\r\n", "\n", $response);

                        $res = str_replace("\r", "\n", $res);

                        $res = str_replace("\t", ' ', $res);



                        $ares = explode("\n", $res);

                        $first_line = explode(' ', array_shift($ares), 3);



                        $return['status'] = trim($first_line[1]);

                        $return['reason'] = trim($first_line[2]);



                        foreach ($ares as $line) {

                             $temp = explode(':', $line, 2);

                             if (isset($temp[0]) and isset($temp[1])) {

                                  $return[strtolower(trim($temp[0]))] = trim($temp[1]);

                             }

                        }



                        //$return['_response'] = $response;

                        //$return['_request'] = $head;



                        return $return;

}


function youtubeurl($url)
{
	$urlArray = split("=", $url);

                   $videoid = trim($urlArray[1]);



                   $pageurl = $_SERVER["HTTP_REFERER"];

                   $newAPIurl = "http://www.youtube.com/get_video_info?&video_id=$videoid";

                   $newAPIurl .= "&el=embedded&ps=chromeless&eurl=$pageurl";



                   $newInfo = trim(@file_get_contents($newAPIurl));

                   $infoArray = split("&", $newInfo);

                   for ($i=0; $i < count($infoArray); $i++) {

                       $tmp = split("=", $infoArray[$i]);

                       $key = urldecode($tmp[0]);

                       $val = urldecode($tmp[1]);

                       $paramArray["$key"] = "$val";

                   }



                   if (array_key_exists("token", $paramArray)) {

                       $t = $paramArray["token"];

                   } else {

                       $legacyAPIurl="http://www.youtube.com/api2_rest?method=youtube.videos.get_video_token&video_id=$videoid";

                       $t = trim(strip_tags(@file_get_contents($legacyAPIurl)));

                   }



                   $vid_location = "http://www.youtube.com/get_video.php?video_id=$videoid&t=$t&fmt=18";



                   //$headers = get_headers($uri);

                   //print "<pre>\n";

                   //print " uri: $uri\n" ;

                   //print "videoid: $videoid\n";

                   //print " token: $token\n";

                   //print " fmt: $fmt\nheaders: ";

                   //print_r($headers);

                   //print "\n</pre>\n";

                   //exit;



                   //...debug



                   $response=http_test_existance($vid_location);

                   $uri=$response["location"];



                   $vid_location = $uri;



                   return $vid_location;
}

$url = $_GET['url'];
$location = urlencode(youtubeurl($url));
//$location = str_replace("&",'$',$location);
print("&location1=".$location);


?>