<?php 
	$playerPath = urldecode($_POST["playerPath"]);
	$to = urldecode($_POST["toEmail"]);
	
	$subject = "Message from your friend";
	
	$from_header = "MIME-Version: 1.0"."\r\n";
	$from_header .= "Content-type: text/html; charset=iso-8859-1"."\r\n";
	$from_header .= "From: ".urldecode($_POST["fromEmail"]);
	
	$imgPath = urldecode($_POST["imgPath"]);
	$playerPage = urldecode($_POST["playerPage"]);
	
	$file = $playerPath."stafTemplate.html";
	$f = fopen($file, "r");
	$content = fread($f, 8192);
	fclose($f);
	$content = str_replace(array('[videoPage]','[imgPath]'), array($playerPage, $imgPath), $content);
	
	mail($to, $subject, $content, $from_header);
	
	echo "status=sent";
?>