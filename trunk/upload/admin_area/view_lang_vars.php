<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
//$userquery->admin_login_check();
$pages->page_redir();

//Adding new phrase
if(isset($_POST['button']))
{
	$name = $_POST['name'];
	$text = $_POST['text'];
	$lang_obj->add_phrase($name,$text);
}

//Getting lang variables

$query = mysql_query("SELECT * FROM ".tbl("phrases")." ORDER BY text ASC");

?>
<form id="form1" name="form1" method="post" action="">

<table width="99%" border="0" cellspacing="0" cellpadding="0">
  <tr style="color:#fff; font-weight:bold">
<td align="left" bgcolor="#000000">Add New</td>
    <td bgcolor="#000000">&nbsp;</td>
  </tr>
  <tr>
    <td><label for="textfield"></label>
    <input name="name" type="text" id="textfield" size="55" /></td>
    <td><textarea name="text" cols="55" id="textfield2"></textarea></td>
  </tr>
  <tr>
    <td><label for="button"></label>
    <input type="submit" name="button" id="button" value="Submit" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>


<table width="99%" border="0" cellspacing="2" cellpadding="2">
<tr style="color:#fff; font-weight:bold">
<td align="left" bgcolor="#000000">Phrase Code
</td>
<td align="left" bgcolor="#000000">Sentence
</td>
</tr>
<?
while($data=mysql_fetch_array($query))
{
?>
<td align="left"><?=$data['varname']?>
</td>
<td align="left"><?=$data['text']?>
</td>
</tr>

<?
}
?>
</table>
