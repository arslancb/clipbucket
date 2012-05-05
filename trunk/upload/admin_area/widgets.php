<?php

/**
 * @package ClipBucket
 * @author Arslan Hassan
 * @todo Write Documentation
 * @since v3.0
 */
require_once '../includes/admin_config.php';
$userquery->admin_login_check();


subtitle('Widgets Manager');
template_files('widgets.html');
display_it();

?>