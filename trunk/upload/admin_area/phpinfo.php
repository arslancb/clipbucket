<?php
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

@$page = $pages->show_admin_page(clean($_GET['settings']));
if(!empty($page)){
$pages->redirect($page);
}
phpinfo();
Template('footer.html');
?>