
					<?php 
					$body ="<html>
<head>
<style type='text/css'>
<!--
.title {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #FFFFFF;
	font-size: 16px;
}
.title2 {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 14px;
}
.message {
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	font-weight:bold;
	color: #000000;
	font-size: 12px;
}
#videoThumb{
	width: 120px;
	padding: 2px;
	margin: 3px;
	border: 1px solid #F0F0F0;
	text-align: center;
	vertical-align: middle;
}
body,td,th {
	font-family: tahoma;
	font-size: 11px;
	color: #FFFFFF;
}
.text {
	font-family: tahoma;
	font-size: 11px;
	color: #000000;
	padding: 5px;
}
-->
</style>
</head>
<body>
<table width='100%' border='0' cellspacing='0' cellpadding='5'>
  <tr>
    <td bgcolor='#53baff' ><span class='title'>$title</span>share video</td>
  </tr>
  <tr>
    <td height='20' class='message'>$username wants to share Video With You<div id='videoThumb'><a href='$baseurl/watch_video.php?v=$videokey'>$videothumb<br>
    watch video</a></div></td>
  </tr>
  <tr>
    <td class='text' ><span class='title2'>Video Description</span><br>
      <span class='text'>$videodes</span></td>
  </tr>
  <tr>
    <td><span class='title2'>Personal Message</span><br>
      <span class='text'>$message
      </span><br>
      <br>
<span class='text'>Thanks,</span><br> 
<span class='text'>$username</span></td>
  </tr>
  <tr>
    <td bgcolor='#53baff'>copyrights 2007 $title</td>
  </tr>
</table>
</body>
</html>" 
					?>
					