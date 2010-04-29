<?php

/**
 * Plugin code goes here...
 */
 
 //Updaing COnfigs
 if($_POST['update'])
 {
	 $fields = array
	 (
	  'auto_play',
	  'logo_placement',
	  'jw_skin',
	  'plugin_var',
	  'longtail_enabled',
	  'longtail_id'
	  );
	 foreach($fields as $field)
		$cb_jw_smart->update_config($field,$_POST[$field]);
	
	//Setting Custom Vars
	$custom_vars = $_POST['custom_vars'];
	$cust_name = $_POST['cust_name'];
	$cust_val = $_POST['cust_val'];
	$new_code = $cb_jw_smart->custom_to_json($cust_name,$cust_val,$custom_vars,$_POST);
	$cb_jw_smart->update_config('custom_variables','|no_mc|'.$new_code);
	$cb_jw_smart->get_configs();
	
	
	if(isset($_FILES['skin_file']) && $_FILES['skin_file']['name'])
	{
		$cb_jw_smart->upload_skin($_FILES['skin_file']);
		$cb_jw_smart->get_skins();
	}
	
	e("Configurations have been updated","m");
 }

template_files('jw_admin.html',PLUG_DIR.'/jw_smart/admin');
?>