<?php

/**
 *  All function related to uploading will be listed here...
 * @author Arslan Hassan
 * @since 8/10/2012
 * 
 */

/**
 * @todo : in sab ko document krna hai =D
 */
/**
 * Register an upload option
 * 
 * @global type $Cbucket
 * @param type $array
 * @return boolean
 */
function register_upload_option($array)
{
    global $Cbucket;
    /**
     * -- for reference
     * an array will have
     * title -> required
     * description -> required
     * icon -> optional
     * function -> required , a php callback function to display in upload
     * window.
     */
    
    extract($array);

    
    if(!$title || !$description || !function_exists($function) || !$function)
        return false;
    
    
    
    if(!$id)
        $id = slug ($title);
    
    $upload_option = array(
        'title' => $title,
        'id'    => $id,
        'description' => $description,
        'icon'  => $icon,
        'function' => $function        
    );
    
    
    apply_filters($upload_option, 'register_upload_option');
    
    $Cbucket->upload_options[] = $upload_option;
    
    return $upload_option;
        
}


function get_upload_options()
{
    global $Cbucket;
    $opts =  $Cbucket->upload_options;
    
    $opts = apply_filters($opts, 'upload_options');
    
  
    return $opts;
}


function upload_window($option)
{
    $func = $option['function'];
    if($func && function_exists($func))
    {
        return $func();
    }else
        return false;
}
?>