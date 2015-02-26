<?php
/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Custom Field');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Custom Field');
}
/*video fields inserted from here*/

if(isset($_POST['submit'])){
    $video="video";
    assign('video',$video);
    if(count($_POST['fieldname'])==1){
        $fieldname=array($_POST['fieldname']);
        $fieldtitle=array($_POST['fieldtitle']);
        $defaultvalue=array($_POST['default_value']);

         
        //$db_field=array($_POST['fieldname']);
         $type_page=$_POST['type_page'];
        $date=date('Y-m-d h:i:s');
        $db_field;
        if(isset($_POST['usethis'])){
            $db_field=array($_POST['db_field']);
        }else {
            $db_field=$fieldname;
        }

    }else {
    $fieldname=$_POST['fieldname'];
    $fieldtitle=$_POST['fieldtitle'];
    $defaultvalue=$_POST['default_value'];
    $db_field=$_POST['fieldname'];
    $type=$_POST['type'];
    $type_page=$_POST['type_page'];
    $date=date('Y-m-d h:i:s');
    if(isset($_POST['usethis'])){
    $db_field=$_POST['db_field'];
    }else {
    $db_field=$fieldname;
    }
    }
    foreach($fieldname as $cnt=>$fields) {
        add_custom_file($fields,$fieldtitle[$cnt],$type[$cnt],$db_field[$cnt],$defaultvalue[$cnt],$type_page,$date);
    $db->execute("ALTER TABLE ".tbl('video')." ADD `".$db_field[$cnt]."` TEXT NOT NULL");
    }
    e("Custom Fields for video has been ADDED Successfully","m");


}



if(isset($_GET['deletefield'])){
$deletefield = mysql_clean($_GET['deletefield']);
$deletefield_name=mysql_clean($_GET['deletefield_name']);
$custom_db_field=mysql_clean($_GET['dbfield_name']);
$page_type=mysql_clean($_GET['page_type']);
    if($page_type=="video"){
    $db->execute("ALTER TABLE ".tbl('video')." DROP $custom_db_field");
    }else {
    $db->execute("ALTER TABLE ".tbl('users')." DROP $custom_db_field");
    }

	delete_custom_field($deletefield);
    header("Refresh: 1;url=".BASEURL."/admin_area/plugin.php?folder=customfield/admin&file=add_custom_field.php");
	e("Custom fields have been deleted","m");
}

$listing=list_custom_field();



$page = mysql_clean($_GET['page']);

$get_limit = create_query_limit($page,RESULTS);
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);
$result_array['limit'] = $get_limit;

/*Signup fields Insertions*/
if(isset($_POST['submit_signup'])){
    $signup="signup";
    assign('signup',$signup);
    if(count($_POST['fieldname_signup'])==1){
    $fieldname_signup=array($_POST['fieldname_signup']);
    $fieldtitle_signup=array($_POST['fieldtitle_signup']);
    $defaultvalue_signup=array($_POST['default_value_signup']);
    //$db_field=array($_POST['fieldname']);
    $type_signup=array($_POST['type2']);

    $type_page=$_POST['type_page'];
    $date=date('Y-m-d h:i:s');
    $db_field;
    if(isset($_POST['usethis'])){
    $db_field_signup=array($_POST['db_field_signup']);
    }else {
    $db_field_signup=$fieldname_signup;
    }

    }else {
    $fieldname_signup=$_POST['fieldname_signup'];
    $fieldtitle_signup=$_POST['fieldtitle_signup'];
    $defaultvalue_signup=$_POST['default_value_signup'];
    $db_field_signup=$_POST['fieldname_signup'];
    $type_signup=$_POST['type2'];
    $type_page=$_POST['type_page'];
    $date=date('Y-m-d h:i:s');
    if(isset($_POST['usethis'])){
    $db_field_signup=$_POST['db_field_signup'];
    }else {
    $db_field_signup=$fieldname_signup;
    }
    }
    foreach($fieldname_signup as $cnt=>$fields) {
    add_custom_file($fields,$fieldtitle_signup[$cnt],$type_signup[$cnt],$db_field_signup[$cnt],$defaultvalue_signup[$cnt],$type_page,$date);
    $db->execute("ALTER TABLE ".tbl('users')." ADD `".$db_field_signup[$cnt]."` TEXT NOT NULL");
    }
    e("Custom Fields for signup has been ADDED Successfully","m");
 header("Refresh: 1;url=".BASEURL."/admin_area/plugin.php?folder=customfield/admin&file=add_custom_field.php");
}


if(!$array['order'])
$result_array['order'] = " date_added DESC ";
assign('listscustom', $listing);


template_files('add_custom_field.html',CB_CUSTOM_FIELDS_PLUG_DIR.'/admin');

?>