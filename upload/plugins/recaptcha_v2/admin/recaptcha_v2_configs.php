<?php

if(!defined('IN_CLIPBUCKET'))
exit('Invalid access');


$userquery->admin_login_check();
$pages->page_redir();

if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'reCaptcha v2');
}
if(!defined('SUB_PAGE')){
		define('SUB_PAGE', "Configurations");
}

try {
	
	if(isset($_POST['recaptcha_v2_update'])){

		
		$param = $_POST;

		$response = $recv2->update_recaptcha_confs($param);

		e($response,"m");
	}

	
	$rec_config=$recv2->get_recaptcha_confs();
	assign('rec_config',$rec_config);



} catch (Exception $e) {
	

	e($e->getMessage(),"e");


}




subtitle("reCaptcha V2 configurations");
template_files(RECAPTCHA_V2_DIR.'/admin/recaptcha_v2_configs.html');