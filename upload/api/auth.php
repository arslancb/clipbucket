<?php

/**
 * @Author Arslan Hassan
 * @Since v3.0 - 2012
 * 
 * New Api for ClipBucket to let other application to authorize and login
 */

include('../includes/config.inc.php');
include('global.php');

$request = $_REQUEST;
$mode = strtolower($request['mode']);


$api_keys = $Cbucket->api_keys;

if($api_keys)
{
    if(!in_array($request['api_key'],$api_keys))
    {
        exit(json_encode(array('err'=>'App authentication error')));
    }
}

switch($mode)
{
    case "login":
    {
        $uDetails = array('username','userid','email','total_videos','total_photos','total_collections','total_groups');

        $userDetails = array();
        foreach($uDetails as $ud)
        {
                $userDetails[$ud] = $userquery->udetails[$ud];
        }

        $userDetails['sess_id'] = $_COOKIE['PHPSESSID'];
                
                
        $username = $request['username'];
        $password = $request['password'];
        
        if($userquery->userid)
        {
            $userDetails['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($userDetails);
            exit(json_encode(array('status'=>'ok','userid'=>$userquery->userid,'details'=>$userDetails)));
        }

        function onLoginMobile()
        {
            global $userquery;
            
            $uDetails = array('username','userid','email','total_videos','total_photos','total_collections','total_groups');

            $userDetails = array();
            foreach($uDetails as $ud)
            {
                    $userDetails[$ud] = $userquery->udetails[$ud];
            }
            
            $userDetails['sess_id'] = $_COOKIE['PHPSESSID'];
            
            $userDetails['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($userDetails);
            
            exit(json_encode(array('status'=>'ok','userid'=>$userquery->userid,'type'=>'custom','details'=>$userDetails)));
        }

        $onLogin = 'onLoginMobile';

        if(cb_get_functions('signup_page')) cb_call_functions('signup_page'); 


        $userquery->login_user($username,$password);

        if(error())
        {
                exit(json_encode(array('status'=>'failed','msg'=>error('single'))));
        }else
        {
                $uDetails = array('username','userid','email','total_videos','total_photos','total_collections','total_groups');

                $userDetails = array();
                foreach($uDetails as $ud)
                {
                        $userDetails[$ud] = $userquery->udetails[$ud];
                }

                $userDetails['sess_id'] = $_COOKIE['PHPSESSID'];
                
                $userDetails['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($userDetails);
				
                exit(json_encode(array('status'=>'ok','userid'=>$userquery->userid,'sess_id'=> $_COOKIE['PHPSESSID'],'details'=>$userDetails)));
        }
    }
    break;
    
    case "getuser":
    case "check_auth":
    case "is_logged_in":
    case "checkauth":
    case "isloggedin":
    {

        $userid = userid();
        if(!userid())
            exit(json_encode(array('status'=>'failed','msg'=>'User is not logged in','session'=>$_COOKIE['PHPSESSID'])));
        else
        {
            $uDetails = array('username','userid','email','total_videos','total_photos','total_collections','total_groups');

            $userDetails = array();
            foreach($uDetails as $ud)
            {
                    $userDetails[$ud] = $userquery->udetails[$ud];
            }
            
            $userDetails['sess_id'] = $_COOKIE['PHPSESSID'];
            $userDetails['avatar'] = $video['user_photo'] = $video['displayPic'] = $userquery->avatar($userDetails);
            exit(json_encode($userDetails));
        }
    }
    break;
    
    case "logout":
    {
        
        $userquery->logout();
        if(cb_get_functions('logout')) cb_call_functions('logout'); 
        setcookie('is_logout','yes',time()+3600,'/');
    }
}
?>