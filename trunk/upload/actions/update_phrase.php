<?php

/**
 * This file is used to update
 * language phrases
 * Author : Arslan Hassan
 * Since : 10 Aug, 2009
 */
 
 
require'../includes/admin_config.php';
$userquery->admin_login_check();

$phrase_id = $_POST['id'];
$value = $_POST['value'];

$lang_obj->update_phrase($phrase_id,'|no_mc|'.$value);

echo ($value);
?>
