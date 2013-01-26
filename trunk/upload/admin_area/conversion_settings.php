<?php

/*
 * @Author Arslan Hassan
 * @Since 3.0
 * 
 * Newly added Conversion Settings for new conversion kit
 */

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');

if(isset($_POST['profile_id']))
{
    //checking if there is watermark file..
    $file = $_FILES['watermark_file'];
    if($file['tmp_name'])
    {
        if(GetExt($file['name'])!='png')
        {
            e(lang('Please upload png file only for watermark.'));
        }else
        {
            $waterMarkFile = WATERMARK_DIR.'/'.$_POST['profile_id'].'.png';
            
            if(file_exists($waterMarkFile))
                unlink($waterMarkFile);
            if(move_uploaded_file($file['tmp_name'], $waterMarkFile))
            {
                //show success watermark msg
                
            }else
            {
                e(lang("Unable to upload watermark file"));
            }
        }
        
    }
    
    if(!$_POST['updated_profile'])
        e(lang("Profile has been created"),"m");
    else
        e(lang("Profile has been updated"),"m");
}

/** Update order **/
if($_POST['mode']=='update')
{
    if($cbvid->update_video_profile_order($_POST['profile_order']))
    {
        e(lang("Profile order has been updated"),"m");
    }
}

/** Delete profile **/
if($_POST['delete_profile'])
{
    $pid = $_POST['delete_profile'];
    if($cbvid->delete_video_profile($pid))
        e(lang("Video profile has been removed"),"m");
    else {
        e(lang("Unable to delete video profile"));
    }
}

/** Delete profiles **/
if($_POST['mode']=='delete')
{
    $profile_ids = $_POST['profile_ids'];
    if($profile_ids)
    foreach($profile_ids as $profile_id)
        $cbvid->delete_video_profile($profile_id);
    
    if(count($profile_ids)>0)
        e(lang("Profiles have been demoved"),"m");
    else
        e(lang("Please select profiles you want to delete"));
}

/** Activate/Deactivate Profiles */
if($_POST['activate'])
{
    $pid = mysql_clean($_POST['profile_id']);
    if($_POST['activate']=='yes')
        $cbvid->action($pid,'activate');
    else
        $cbvid->action($pid,'deactivate');
}

/** Activate/deactivate profiles */
if($_POST['mode']=='activate' || $_POST['mode']=='deactivate')
{
    
    $profile_ids = $_POST['profile_ids'];
    if($profile_ids)
    foreach($profile_ids as $profile_id)
    {
        if($_POST['mode']=='activate')
        {
            $cbvid->profile_action($profile_id,'activate');
            
        }else
            $cbvid->profile_action($profile_id,'deactivate');
    }
    
        
    if(count($profile_ids)>0)
    {
        if($_POST['mode']=='activate')
            e(lang('Profiles have been activated'),'m');
        else
            e(lang('Profiles have been deactivated'),'m');
    }else
        e(lang("Please select profiles you want to activate or deactivate"));
}

$formats = array('flv','mp4','webm','f4v','m4v');
$vcodecs = array(''=>'none','libx264'=>'X264 - libx264','libvpx' => 'Webm - libvpx','flv'=>'FLV - flv','mpeg4'=>'Mpeg4 - mpeg4');
$acodecs = array(''=>'none','libfaac'=>'FAAC - libfaac','libmp3lame' => 'MP3 - libmp3lame ','libvorbis'=>'Vorbis - libvorbis');;
$resizes = array(''=>'none','max'=>'Max','fit'=>'Fit','wxh'=>'WxH');
$presets = array(''=>'none','low'=>'low  - 240','normal'=>'normal - 480','hq'=>'hq - 720','max'=>'max - 1080');

assign('formats',$formats);
assign('vcodecs', $vcodecs);
assign('acodecs',$acodecs);
assign('resizes',$resizes);
assign('presets',$presets);

/**
 * Get list of profiles... 
 */
$profiles = $cbvid->get_video_profiles(array('order'=>' profile_order ASC '));
assign('profiles',$profiles);


subtitle('Conversion Settings');
template_files('conversion_settings.html');
display_it();
?>