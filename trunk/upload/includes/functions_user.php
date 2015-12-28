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

/**
* Function used to check fields in myaccount section (edit_account.php?mode=profile)
* It checks certain important fields to make sure user enters correct data
* @param $array : array of fields data
* @since ClipBucket 2.7.7
*/

function profile_fileds_check($array)
{
        $post_clean = true;
        if (preg_match('/[0-9]+/', $array['first_name']) || preg_match('/[0-9]+/', $array['last_name']))
        {
            e('Name contains numbers! Seriously? Are you alien?');
            $post_clean = false;
        }

        if (!empty($array['profile_tags']))
        {
            if (preg_match('/[0-9]+/', $array['profile_tags']) || !strpos($array['profile_tags'], ','))
            {
                 e('Invalid tags. Kindly review!');
                $post_clean = false;
            }
        }

        if (!empty($array['web_url']) && !filter_var($array['web_url'], FILTER_VALIDATE_URL))
        {   
            e('Invalid URL provided.');
            $post_clean = false;
        }

        if (!is_numeric($array['postal_code']) && !empty($array['postal_code']))
        {
            e("Don't fake it! Postal Code can't be words!");
            $post_clean = false;
        }
}

