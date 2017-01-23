<?php
    /**
     * Created by JetBrains PhpStorm.
     * @author: Fawaz Tahir
     * @since: August 26th, 2013 ClipBucket 2.6
     * Functions for users section
     */

    function get_user_fields($array=NULL) {    
        global $cb_columns;
        return $cb_columns->object( 'users' )->get_columns();
    }


    /**
    * Get name of a user from array
    *
    * @param { array } { $user_array } { array with user details }
    * @return { string } { $name } { name of user fetched from array }
    */

    function name($user_array) {
        $user = $user_array;
        $name = "";
        if(isset($user['first_name']) && $user['first_name']) {
            $name = $user['first_name'];
        }
        if(isset($user['last_name']) && $user['last_name']) {
            $name .= " ". $user['last_name'];
        }
        if(isset($user['anonym_name']) && $user['anonym_name']) {
            $name = $user['anonym_name'];
        }
        if(!$name) $name = $user['username'];
        return $name;
    }

    /**
    * Function used to check fields in myaccount section (edit_account.php?mode=profile)
    * It checks certain important fields to make sure user enters correct data
    * @param: { array } : { $array } { array of fields data }
    * @since: ClipBucket 2.8
    * @return: { boolean }  { true or false depending on situation }
    */

    function profile_fileds_check($array) {
        $post_clean = true;
        if (preg_match('/[0-9]+/', $array['first_name']) || preg_match('/[0-9]+/', $array['last_name'])) {
            e('Name contains numbers! Seriously? Are you alien?');
            $post_clean = false;
        }
        if (!empty($array['web_url'])){
            if (is_numeric($array['web_url'])) {   
                e('Invalid URL provided.');
                $post_clean = false;
            }
        }
        if (!is_numeric($array['postal_code']) && !empty($array['postal_code'])) {
            e("Don't fake it! Postal Code can't be words!");
            $post_clean = false;
        }
    }

    /**
    * Resend verification email to a given user
    * @param: { integer } { $userid } { id of user to resend verification to }
    * @return: { boolean } { true if success, else false }
    * @since: March 10th, 2016 ClipBucket 2.8.1
    * @author: Saqib Razzaq
    */

    function resend_verification($userid) {
        global $db;
        $raw_data = $db->select(tbl("users"),"usr_status,username,email","userid = '$userid'");
        $usr_status = $raw_data[0]['usr_status'];
        $uname = $raw_data[0]['username'];
        $email = $raw_data[0]['email'];
        if (trim($usr_status) == "ToActivate") {
            global $cbemail;
            $avcode = RandomString(10);
            $db->update(tbl("users"),array("avcode"),array($avcode),"userid = '$userid'");
            $tpl = $cbemail->get_template('email_verify_template');
            $more_var = array
            ('{username}'   => $uname,
             '{email}'      => $email,
             '{avcode}'     => $avcode,
            );
            if(!is_array($var)) {
                $var = array();
            }
            $var = array_merge($more_var,$var);
            $subj = $cbemail->replace($tpl['email_template_subject'],$var);
            $msg = nl2br($cbemail->replace($tpl['email_template'],$var));
            //Now Finally Sending Email
           // pex($email,true);
            cbmail(array('to'=>$email,'from'=>WEBSITE_EMAIL,'subject'=>$subj,'content'=>$msg));
            return $uname;
        } else {
            return false;
        }
    }

    /**
    * Returns playable video for user's channel page
    * @param : { array } { $userVideos } { an array user videos }
    * @return : { string / boolean } { video key if found a video matches pattern else false }
    * @since : November 17th, 2016 ClipBucket 2.8.2
    * @author : Saqib Razzaq
    */

    function userMainVideo($userVideos) {
        global $userquery;
        if (is_array($userVideos)) {
            $userid = $userquery->userid;
            foreach ($userVideos as $key => $video) {
                $vBroadcast = trim($video['broadcast']);
                $vKey = $video['videokey'];
                switch ($vBroadcast) {
                    case 'private':
                        if (is_numeric($userid)) {
                            $allowedUsers = explode(',', $video['video_users']);
                            if (in_array($userid, $allowedUsers)) {
                                return $vKey;
                            }
                        }
                        break;
                    case 'logged':
                        if (is_numeric($userid)) {
                            return $vKey;
                        }
                        break;
                    case 'public':
                        return $vKey;
                        break;
                }

            }
        }
    }

