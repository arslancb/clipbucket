<?php

/** 
 * All ajax requests that are related videos or its object will be listed here
 *  
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */

include('../includes/config.inc.php');

//Getting mode..
$mode = post('mode');
if(!$mode)
    $mode - get('mode');

$mode = mysql_clean($mode);


switch($mode){
    
    
    default:
        exit(json_encode(array('err'=>lang('Invalid request'))));
}
?>