<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/26/13
 * Time: 4:47 PM
 * To change this template use File | Settings | File Templates.
 */

function get_user_fields($array=NULL) {
    
    global $cb_columns;
    return $cb_columns->object( 'users' )->get_columns();
    
}


/**
 * Get name of a user from array
 *
 * @param Array $user_array
 * @return String $name
 */
function name($user_array)
{
    $user = $user_array;
    $name = "";

    if(isset($user['first_name']) && $user['first_name'])
        $name = $user['first_name'];

    if(isset($user['last_name']) && $user['last_name'])
        $name .= " ". $user['last_name'];

    if(isset($user['anonym_name']) && $user['anonym_name'])
        $name = $user['anonym_name'];

    if(!$name) $name = $user['username'];

    return $name;
}

