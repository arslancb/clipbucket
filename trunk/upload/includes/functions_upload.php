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
 * ---------------------------------------------------------------------------------------
 * Updated by Fawaz Tahir on 9th Sep, 2012 
 * -----------------------------------------------
 * Introducing object parameter. This way we dont need to make separate
 * variables for different objects. To make sure right object is provided, we'll
 * check it against $Cbucket->search_types key. If it exists in variable, we'll
 * add it.
 * ---------------------------------------------------------------------------------------
 * 
 * @global OBJECT $Cbucket
 * @param ARRAY $array
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
    
    /* $type checking - Added by Fawaz Tahir */
    if ( !$Cbucket->search_types[$object] ) {
        return false;
    }
    
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
    
    $Cbucket->upload_options[$object][] = $upload_option;
    return $upload_option;
        
}

/**
 * Get upload options for provided object
 * ---------------------------------------------------------------------------------------
 * Updated by Fawaz Tahir on 9th Sep, 2012
 * -----------------------------------------------
 * Because we have already used this function @upload.html, so we're providing
 * an defined $object if nothing is passed.
 * ---------------------------------------------------------------------------------------
 * @global OBJECT $Cbucket
 * @param STRING $object Object
 * @return ARRAY
 */

function get_upload_options($object='videos')
{
    global $Cbucket;
    $opts =  $Cbucket->upload_options[$object];
    
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


function get_file_uploader_path()
{
	return BASEURL . '/actions/file_uploader.php';
}
?>