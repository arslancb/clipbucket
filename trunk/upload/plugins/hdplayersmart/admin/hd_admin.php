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
	  'hd_skin',
	  'license',
	  'embed_visible',

	  );
	 foreach($fields as $field)
		$cb_hd_smart->update_config($field,$_POST[$field]);
	
	//Setting Custom Vars
	$custom_vars = $_POST['custom_vars'];
	$cust_name = $_POST['cust_name'];
	$cust_val = $_POST['cust_val'];
	$new_code = $cb_hd_smart->custom_to_json($cust_name,$cust_val,$custom_vars,$_POST);
	$cb_hd_smart->update_config('custom_variables','|no_mc|'.$new_code);
	$cb_hd_smart->get_configs();
	
	if(isset($_FILES['skin_file']) && $_FILES['skin_file']['name'])
	{
		$cb_hd_smart->upload_skin($_FILES['skin_file']);
		$cb_hd_smart->get_skins();
		
	}
	
	e("Configurations have been updated","m");
 }

template_files('hd_admin.html',PLUG_DIR.'/hdplayersmart/admin');
?>