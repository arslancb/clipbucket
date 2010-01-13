<?php
/** 
 * CLipBucket v2 Player Manage
 * Author : Arslan
 *
 * Licensed Under CBLA
 * ClipBucket 2007-2009
 */
 
require_once '../includes/admin_config.php';
$pages->page_redir();
$userquery->login_check('admin_access');

if($_GET['set'])
{
	$cbplayer->set_player($_GET);
}

subtitle("Manage Players");
template_files('manage_players.html');
display_it();
?>