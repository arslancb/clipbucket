<?php
//ini_set('display_errors', true);
//error_reporting(E_ALL + E_NOTICE);
/*
Plugin Name: Custom field for video and signup
Description: This Plugin will display custom field
Author: test
Author Website: http://clip-bucket.com/arslan-hassan
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/

  define("CB_CUSTOM_FIELDS_DIR_NAME",basename(dirname(__FILE__)));
  define('CB_CUSTOM_FIELDS_PLUG_DIR',PLUG_DIR."/".CB_CUSTOM_FIELDS_DIR_NAME);
  define("SITE_MODE",'/admin_area');

  define("CB_CUSTOM_FIELDS_EditPAGE_URL",BASEURL.SITE_MODE."/plugin.php?folder=".CB_CUSTOM_FIELDS_DIR_NAME."/admin&file=edit_field.php");

  assign("cb_custom_fields_edit_page",CB_CUSTOM_FIELDS_EditPAGE_URL);
if(!function_exists("customfield"))
{
}   
    /**
	 * This function is used to add Custom field
	 */
   function add_custom_file($field_name,$field_title,$field_type,$db_field,$default_value,$type_page,$date){
    global $db;
   $result=$db->insert(tbl('custom_field'),array("field_name","field_title","field_type","db_field","default_value","customfields_flag","date_added"),array($field_name,$field_title,$field_type,$db_field,$default_value,$type_page,$date));
  }
      /**
      *This function is used to list custom fields on custom field plugin page for editing and deleting
      */
  function list_custom_field()
  {
  		global $db;
        //$exec = $db->Execute('SELECT * FROM '.tbl("custom_field"));
		$results = $db->select(tbl('custom_field'),'*',$limit,$order);
		foreach($results as $value)
		{
			$list[]=$value;
		}
	   return $list;
  }
  /**
  *This function is used to delete custom field
  */
  function delete_custom_field($fid){
        global $db;
  			$db->delete(tbl('custom_field'),array('custom_field_list_id'),array($fid));
        /*remove column also from video*/
  }

  /**
  *This function is used to list specific custom field for editing purpose
  */

  function view_customfield_detail($field_id){
  	global $db;
		$result = $db->select(tbl('custom_field'),"*","custom_field_list_id='$field_id'");
		foreach($result as $value)
		{
			$listdetail[]=$value;

		}
	   return $listdetail;
 
    }
    /**
    *This function is used edit custom fields
    */
	function edit_field($field_name,$field_title,$field_type,$db_field,$default_value,$edit_id)
    {   
        global $db;
        $sql = "UPDATE ".tbl("custom_field")." SET field_name= '".$field_name."',field_type='$field_type',field_title='$field_title',default_value='$default_value',db_field='$db_field' WHERE custom_field_list_id='".$edit_id."'";
        $db->Execute($sql);
    }
    
    /**
    *Function for loading custom fields on video page
    */
	function load_custom_fields($data,$ck_display_admin=FALSE,$ck_display_user=FALSE){
		global $db;
		$results = $db->select(tbl("custom_field"),"*","customfields_flag='video'");
		foreach($results as $result)
			{
            $name = $result['field_name'];
            $type = $result['field_type'];
            $title = $result['field_title'];
            $value = $result['default_value'];
            $db_field = $result['db_field'];
        if($type=='dropdown'){
        $defaultselectvalue=explode(",",$value);
        
    $selectbuttonvalues=array();
    foreach ($defaultselectvalue as $key => $value) {
    $selectbuttonvalues[$value]=$value;
    } 
    $array= array($name=>array('title'=>$title,'type'=>$type,'value'=>$selectbuttonvalues,'name'=> $name,'id'=> $name,'db_field'=>$db_field,));

    }else if($type=='radiobutton' || $type=='checkbox'){
    $defaultradio=explode(",",$value);
    $radiobuttonvalues=array();
    foreach ($defaultradio as $key => $value) {
    $radiobuttonvalues[$value]=$value;
    }
        $array=array($name => array(
                          'title'=>  $title,
                          'type'=> $type,
                          'name'=> $name,
                          'id'=> $name,
                          'value' => $radiobuttonvalues,
                          'checked' => $defaultradio[0],
                          'db_field'=>$db_field,
                          'auto_view'=>'no',
                          'sep'=>'&nbsp;'
                          ));

        }else{
        $array= array($name=>array('title'=>$title,'type'=>$type,'name'=> $name,'id'=> $name,
        'db_field'=>$db_field,));
        }
foreach($array as $key => $fields) {
		$new_array[$key] = $fields;
		  }
	   }
     return $new_array;
}
 /**
    *Function for loading custom fields for signup page
    */
 //$data,$ck_display_admin=FALSE,$ck_display_user=FALSE
function load_custom_fields_signup(){
    global $db;
    $results = $db->select(tbl("custom_field"),"*","customfields_flag='signup'");
    foreach($results as $result)
      {
            $name = $result['field_name'];
            $type = $result['field_type'];
            $title = $result['field_title'];
            $value = $result['default_value'];
            $db_field = $result['db_field'];
        if($type=='dropdown'){
        $defaultselectvalue=explode(",",$value);
        
    $selectbuttonvalues=array();
    foreach ($defaultselectvalue as $key => $value) {
    $selectbuttonvalues[$value]=$value;
    } 
    $array= array($name=>array('title'=>$title,'type'=>$type,'value'=>$selectbuttonvalues,'name'=> $name,'id'=> $name,'db_field'=>$db_field,));

    }else if($type=='radiobutton' || $type=='checkbox'){
    $defaultradio=explode(",",$value);
    $radiobuttonvalues=array();
    foreach ($defaultradio as $key => $value) {
    $radiobuttonvalues[$value]=$value;
    }
        $array=array($name => array(
                          'title'=>  $title,
                          'type'=> $type,
                          'name'=> $name,
                          'id'=> $name,
                          'value' => $radiobuttonvalues,
                          'checked' => $defaultradio[0],
                          'db_field'=>$db_field,
                          'auto_view'=>'no',
                          'sep'=>'&nbsp;'
                          ));

        }else{
        $array= array($name=>array('title'=>$title,'type'=>$type,'name'=> $name,'id'=> $name,
        'db_field'=>$db_field,));
        }
foreach($array as $key => $fields) {
    $new_array[$key] = $fields;
      }
     }
     return $new_array;
}
register_signup_field(load_custom_fields_signup($data,true));
register_custom_upload_field(load_custom_fields($data,true));

add_admin_menu('Custom Field','Custom Field','add_custom_field.php',CB_CUSTOM_FIELDS_DIR_NAME.'/admin');

?>