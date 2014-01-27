<?php

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('member_moderation');
$pages->page_redir();

if(isset($_POST['add_group']))
{
    if($userquery->signup_user($_POST))
    {
        e(lang("new_mem_added"),"m");
        $_POST = '';
    }
}

subtitle("Add New group");
template_files('add_group.html');
display_it();
?>