<?php

/**
 * This Class is used to
 * Send and recieve
 * private or personal messages
 * within the CLIPBUCKET system
 *
 * @Author : Arslan Hassan (=D)
 * @Software : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 *
 * Pleae check CBLA for more details
 * For code reference, please check docs.clip-bucket.com
 * This Code is property of PHPBucket - ClipBucket - Arslan Hassan
 *
 * NOTE : MAINTAIN THIS SECTION
 *
 *
 * Attachment Pattern : {v:videoidid}{p:pictureid}{g:groupid}{c:channelid}
 * For Multi Users : uid can be uid1|uid2|uid3|....
 */
define('CB_PM', 'ON');
define('CB_PM_MAX_INBOX', 500); // 0 - OFF , U - Unlimited

/**
 * Function used to to attach video to pm
 * @param array => 'attachment_video'
 */
function attach_video($array)
{
    global $cbvid;
    if ($cbvid->video_exists($array['attach_video']))
        return '{v:' . $array['attach_video'] . '}';
}

/**
 * Function used to pars video from attachemtn
 */
function parse_and_attach_video($att)
{
    global $cbvid;
    preg_match('/{v:(.*)}/', $att, $matches);
    $vkey = $matches[1];
    if (!empty($vkey))
    {
        assign('video', $cbvid->get_video_details($vkey));
        assign('only_once', true);
        echo '<h3>Attached Video</h3>';
        template('blocks/video.html');
    }
}

/**
 * Function used to add custom video attachment form field
 */
function video_attachment_form()
{
    global $cbvid;
    $vid_array = array('user' => userid(), 'order' => 'date_added DESC', 'limit' => 15);
    $videos = $cbvid->get_videos($vid_array);
    $vids_array = array('' => lang("No Video"));
    if ($videos)
        foreach ($videos as $video)
        {
            $vids_array[$video['videokey']] = $video['title'];
        }
    $field = array(
        'video_form' => array
            ('title' => 'Attach video',
            'type' => 'dropdown',
            'name' => 'attach_video',
            'id' => 'attach_video',
            'value' => $vids_array,
            'checked' => post('attach_video'),
            'anchor_before' => 'before_video_attach_box',
        )
    );
    return $field;
}

class cb_pm
{

    /**
     * Private messages table
     */
    var $tbl = 'messages';

    /**
     * Allow multi users
     */
    var $multi = true;

    /**
     * Default Template
     */
    var $email_template = 'pm_email_message';

    /**
     * Send Email on pm
     */
    var $send_email = true;

    /**
     * Allow inline attachments
     * these attachements are linked in the messages instead of attached like emails
     */
    var $allow_attachments = true;
    //Attachment functionss
    var $pm_attachments = array('attach_video');
    var $pm_attachments_parse = array('parse_and_attach_video');
    var $pm_custom_field = array();

    /**
     * Calling Constructor
     */
    function init()
    {
        //$array = video_attachment_form();
        $this->add_custom_field($array);
    }

    /**
     * Sending PM
     */
    function send_pm($array)
    {
        global $userquery, $db;
        $to = $this->check_users($array['to'], $array['from']);

        //checking from user
        if (!$userquery->user_exists($array['from']))
        {
            e(lang('unknown_sender'));
            //checking to user
        }
        elseif (!$to)
            return false;
        //Checking if subject is empty
        elseif (empty($array['subj']))
            e(lang('class_subj_err'));
        elseif (empty($array['content']))
            e(lang('please_enter_message'));
        else
        {
            $from = $this->get_the_user($array['from']);
            $attachments = $this->get_attachments($array);
            $type = $array['type'] ? $array['type'] : 'pm';
            $reply_to = $this->is_reply($array['reply_to'], $from);

            $fields = array('message_from', 'message_to', 'message_content',
                'message_subject', 'date_added', 'message_attachments', 'message_box', 'reply_to');
            $values = array($from, $to, $array['content'],
                $array['subj'], now(), $attachments);

            //PM INBOX FIELDS
            $fields_in = $fields;
            //PM INBOX
            $values_in = $values;
            $values_in[] = 'in';
            $values_in[] = $reply_to;

            $db->insert(tbl($this->tbl), $fields_in, $values_in);
            $array['msg_id'] = $db->insert_id();
            if ($array['is_pm'])
            {
                //PM SENTBOX FIELDS
                $fields_out = $fields;
                $fields_out[] = 'message_status';

                //PM SENTBOX
                $values_out = $values;
                $values_out[] = 'out';
                $values_out[] = $reply_to;
                $values_out[] = 'read';

                $db->insert(tbl($this->tbl), $fields_out, $values_out);
            }

            //Sending Email
            //$this->send_pm_email($array);
            e(lang("pm_sent_success"), "m");
        }
    }

    /**
     * Function used to check input users
     * are valid or not
     */
    function check_users($input, $sender)
    {
        global $userquery;

        if (empty($input))
        {
            e(lang("unknown_reciever"));
        }
        else
        {
            //check if usernames are sperated by colon ';'
            $input = preg_replace('/;/', ',', $input);
            //Now Exploding Input and converting it to and array
            $usernames = explode(',', $input);

            //Now Checkinf for valid usernames
            $valid_users = array();
            foreach ($usernames as $username)
            {
                $user_id = $this->get_the_user($username);
                if ($userquery->is_user_banned($username, userid()))
                {
                    e(sprintf(lang("cant_pm_banned_user"), $username));
                }
                elseif ($userquery->is_user_banned(username(), $username))
                {
                    e(sprintf(lang("cant_pm_user_banned_you"), $username));
                }
                elseif (!$userquery->user_exists($username))
                {
                    e(lang("unknown_reciever"));
                }
                elseif ($user_id == $sender)
                {
                    e(lang("you_cant_send_pm_yourself"));
                }
                else
                {
                    $valid_users[] = $user_id;
                }
            }

            $valid_users = array_unique($valid_users);

            if (count($valid_users) > 0)
            {
                $vusers = '';
                foreach ($valid_users as $vu)
                {
                    $vusers .="#" . $vu . "#";
                }
                return $vusers;
            }
            else
                return false;
        }
    }

    /**
     * Function used to get user
     */
    function get_the_user($user)
    {
        global $userquery;
        if (!is_numeric($user))
            return $userquery->get_user_field_only($user, 'userid');
        else
            return $user;
    }

    /**
     * Function used to make attachment valid
     * and embed it in the message
     */
    function get_attachments($array)
    {
        $funcs = $this->pm_attachments;
        $attachments = '';

        if (is_array($funcs))
            foreach ($funcs as $func)
            {
                if (function_exists($func))
                {
                    $attachments .= $func($array);
                }
            }

        return $attachments;
    }

    /**
     * function used to check weather message is reply or not
     */
    function is_reply($id, $uid)
    {
        global $db;
        $results = $db->select(tbl($this->tbl), 'message_to', " message_id = '$id' AND message_to LIKE '%#$uid#%'");
        if ($db->num_rows > 0)
            return true;
        else
            return false;
    }

    /**
     * Get Message from cb_messages table
     * 
     * @param INT message_id
     * @return ARRAY message
     */
    function get_message($id)
    {
        $id = mysql_clean($id);
        $query = "SELECT message_id,thread_id,userid,message,subject";
        $query .= " FROM " . tbl('messages');
        $query .= " WHERE message_id='$id' ";
        $query .= " LIMIT 1";
        echo $query;
        $results = db_select($query);

        if ($results)
            return $results[0];
        else
            return false;
    }

    /**
     * Function used to get user INBOX Message
     * @param MESSAGE ID
     * @param USER ID
     */
    function get_inbox_message($mid, $uid = NULL)
    {
        global $db;
        if (!$uid)
            $uid = userid();
        $result = $db->select(tbl($this->tbl . ',users'), tbl($this->tbl . '.*,users.userid,users.username'), " message_id='$mid' AND message_to LIKE '%#$uid#%' AND userid=" . tbl($this->tbl) . ".message_from", NULL, " date_added DESC ");

        if ($db->num_rows > 0)
        {
            return $result[0];
        }
        else
        {
            e(lang('no_pm_exist'));
            return false;
        }
    }

    /**
     * Function used to get user OUTBOX Message
     * @param MESSAGE ID
     * @param USER ID
     */
    function get_outbox_message($mid, $uid = NULL)
    {
        global $db;
        if (!$uid)
            $uid = userid();
        $result = $db->select(tbl($this->tbl . ',users'), tbl($this->tbl . '.*,users.userid,users.username'), " message_id='$mid' AND message_from='$uid' AND userid=" . tbl($this->tbl . ".message_from"));

        if ($db->num_rows > 0)
        {
            return $result[0];
        }
        else
        {
            e(lang('no_pm_exist'));
            return false;
        }
    }

    /**
     * Get Total PM
     */
    function pm_count()
    {
        global $db;
        return $db->count(tbl($this->tbl), 'message_id');
    }

    /**
     * Function used to get user inbox messages
     */
    function get_user_messages($uid, $box = 'in', $count_only = false)
    {
        global $db;

        if (!$uid)
            $uid = userid();
        switch ($box)
        {

            case 'in':
                {
                    if ($count_only)
                    {
                        $result = $db->count(tbl($this->tbl), 'message_id', " message_to LIKE '%#$uid#%' AND message_box ='in' AND message_type='pm' ");
                    }
                    else
                    {
                        $result = $db->select(tbl($this->tbl . ',users'), tbl($this->tbl . '.*,users.username AS message_from_user '), tbl($this->tbl) . ".message_to LIKE '%#$uid#%' AND " . tbl("users") . ".userid = " . tbl($this->tbl) . ".message_from 
										  AND " . tbl($this->tbl) . ".message_box ='in' AND message_type='pm'", NULL, " date_added DESC");
                    }
                }
                break;


            case 'out':
                {
                    if ($count_only)
                    {
                        $result = $db->count(tbl($this->tbl), 'message_id', " message_from = '$uid' AND message_box ='out' ");
                    }
                    else
                    {
                        $result = $db->select(tbl($this->tbl . ',users'), tbl($this->tbl . '.*,users.username AS message_from_user '), tbl($this->tbl) . ".message_from = '$uid' AND " . tbl("users") . ".userid = " . tbl($this->tbl) . ".message_from 
										  AND " . tbl($this->tbl) . ".message_box ='out'", NULL, " date_added DESC");
                        //echo $db->db_query;
                        //One More Query Need To be executed to get username of recievers
                        $count = 0;

                        $cond = "";
                        if (is_array($result))
                            foreach ($result as $re)
                            {

                                $cond = '';
                                preg_match_all("/#(.*)#/Ui", $re['message_to'], $receivers);
                                //pr($receivers);
                                foreach ($receivers[1] as $to_user)
                                {

                                    if (!empty($to_user))
                                    {
                                        if (!empty($cond))
                                            $cond .= " OR ";
                                        $cond .= " userid = '$to_user' ";
                                    }
                                }

                                $to_names = $db->select(tbl('users'), 'username', $cond);
                                $t_names = '';

                                if (is_array($to_names))
                                    foreach ($to_names as $tn)
                                    {
                                        $t_names[] = $tn[0];
                                    }
                                if (is_array($t_names))
                                    $to_user_names = implode(', ', $t_names);
                                else
                                    $to_user_names = $t_names;
                                $result[$count]['to_usernames'] = $to_user_names;
                                $count++;
                            }
                    }
                }
                break;

            case 'notification':
                {
                    if ($count_only)
                    {
                        $result = $db->count(tbl($this->tbl), 'message_id', " message_to LIKE '%#$uid#%' AND message_box ='in' AND message_type='pm' ");
                    }
                    else
                    {
                        $result = $db->select(tbl($this->tbl . ',users'), tbl($this->tbl . '.*,users.username AS message_from_user '), tbl($this->tbl) . ".message_to LIKE '%#$uid#' AND " . tbl("users.userid") . " = " . tbl($this->tbl) . ".message_from 
										  AND " . tbl($this->tbl) . ".message_box ='in' AND message_type='notification'", NULL, " date_added DESC");
                    }
                }
        }

        if ($result)
            return $result;
        else
            return false;
    }

    function get_user_inbox_messages($uid, $count_only = false)
    {
        return $this->get_user_messages($uid, 'in', $count_only);
    }

    function get_user_outbox_messages($uid, $count_only = false)
    {
        return $this->get_user_messages($uid, 'out', $count_only);
    }

    function get_user_notification_messages($uid, $count_only = false)
    {
        return $this->get_user_messages($uid, 'notification', $count_only);
    }

    /**
     * Function used parse attachments
     */
    function parse_attachments($attachment)
    {
        $funcs = $this->pm_attachments_parse;
        if (is_array($funcs))
            foreach ($funcs as $func)
            {
                if (function_exists($func))
                {
                    $attachments .= $func($attachment);
                }
            }
    }

    /**
     * Function used to create PM FORM
     */
    function load_compose_form()
    {
        $to = post('to');
        $to = $to ? $to : get('to');

        $array = array
            (
            'to' => array(
                'title' => 'to',
                'type' => 'textfield',
                'name' => 'to',
                'id' => 'to',
                'value' => $to,
                //'hint_2'=> "seperate usernames by comma ','",
                'required' => 'yes'
            ),
            'subj' => array(
                'title' => 'Subject',
                'type' => 'textfield',
                'name' => 'subj',
                'id' => 'subj',
                'value' => post('subj'),
                'required' => 'yes'
            ),
            'content' => array(
                'title' => 'content',
                'type' => 'textarea',
                'name' => 'content',
                'id' => 'pm_content',
                'value' => post('content'),
                'required' => 'yes',
                'anchor_before' => 'before_pm_compose_box',
            ),
        );

        return array_merge($array, $this->pm_custom_field);
    }

    /**
     * Function used to add custom pm field
     */
    function add_custom_field($array)
    {
        $this->pm_custom_field = array_merge($array, $this->pm_custom_field);
    }

    /**
     * Function used to send PM EMAIL
     */
    function send_pm_email($array)
    {
        global $cbemail, $userquery;
        $sender = $userquery->get_user_field_only($array['from'], 'username');
        $content = clean($array['content']);
        $subject = clean($array['subj']);
        $msgid = $array['msg_id'];
        //Get To(Emails)
        $emails = $this->get_users_emails($array['to']);
        $vars = array
            (
            '{sender}' => $sender,
            '{content}' => $content,
            '{subject}' => $subject,
            '{msg_id}' => $msgid
        );

        $tpl = $cbemail->get_template($this->email_template);
        $subj = $cbemail->replace($tpl['email_template_subject'], $vars);
        $msg = $cbemail->replace($tpl['email_template'], $vars);

        cbmail(array('to' => $emails, 'from' => WEBSITE_EMAIL, 'subject' => $subj, 'content' => $msg, 'nl2br' => true));
    }

    /**
     * Function used to get emails of users from input
     */
    function get_users_emails($input)
    {
        global $userquery, $db;
        //check if usernames are sperated by colon ';'
        $input = preg_replace('/;/', ',', $input);
        //Now Exploding Input and converting it to and array
        $usernames = explode(',', $input);
        $cond = '';
        foreach ($usernames as $user)
        {
            if (!empty($user))
            {
                if (!empty($cond))
                    $cond .= " OR ";
                $cond .= " username ='" . $user . "' ";
            }
        }

        $emails = array();
        $results = $db->select(tbl($userquery->dbtbl['users']), 'email', $cond);
        foreach ($results as $result)
        {
            $emails[] = $result[0];
        }

        return implode(',', $emails);
    }

    /**
     * Function used to set private message status as read
     */
    function set_message_status($mid, $status = 'read')
    {
        global $db;
        if ($mid)
            $db->update(tbl($this->tbl), array('message_status'), array($status), " message_id='$mid'");
    }

    /**
     * Function used to delete message from user messages box
     */
    function delete_msg($mid, $uid, $box = 'in')
    {
        global $db;
        if ($box == 'in')
        {
            $inbox = $this->get_inbox_message($mid, $uid);
            if ($inbox)
            {
                $inbox_user = $inbox['message_to'];
                $inbox_user = preg_replace("/#" . $uid . "#/Ui", "", $inbox_user);
                if (empty($inbox_user))
                    $db->delete(tbl($this->tbl), array("message_id"), array($mid));
                else
                    $db->update(tbl($this->tbl), array("message_to"), array($inbox_user), " message_id='" . $inbox['message_id'] . "'  ");
                e(lang('msg_delete_inbox'), 'm');
            }
        }else
        {
            $outbox = $this->get_outbox_message($mid, $uid);
            if ($outbox)
            {
                $db->delete(tbl($this->tbl), array("message_id"), array($mid));
                e(lang('msg_delete_outbox'), 'm');
            }
        }
    }

    /**
     * Function updated for V3
     * 
     * Get new messages from a thread w.r.t to time
     * @param ARRAY ( INT ThreadId , INT time)
     */
    function get_new_messages($param)
    {
        $thread_id = mysql_clean($param['thread_id']);
        $time = mysql_clean($param['time']);

        if (isset($param['userid']))
        {
            $uid = $param['userid'];
        }
        else
        {
            $uid = userid();
        }

        $fields_array = array(
            'm' => array(
                'message_id', 'thread_id', 'message', 'subject', 'seen_by',
                'time_added'
            ),
            'u' => array(
                'username', 'email', 'first_name', 'last_name', 'userid',
                'avatar', 'avatar_url'
            )
        );

        $the_fields = tbl_fields($fields_array);

        $query = " SELECT " . $the_fields . " FROM " . tbl('messages'). " AS m";
        $query .= " LEFT JOIN " . tbl('users') . ' AS u ON  ';
        $query .= 'm.userid = u.userid ';

        $query .= " WHERE thread_id='$thread_id' ";
        $query .= " AND m.time_added > '".$time."'";
        $query .= " AND m.userid!='$uid' ";
        $query .= " ORDER BY m.time_added ASC ";

        $results = db_select($query);
        

        if($results)
            return $results;
        else
            return false;
        
    }

    /**
     * Create the thread
     */
    function create_thread($params)
    {
        $default_values = array(
            'userid' => userid(),
            'thread_type' => 'private',
            'recipients' => array()
        );

        $data = array_merge($default_values, $params);
        $the_data = array(); //Keep only specific indexes
        foreach ($default_values as $key => $value)
        {
            $the_data[$key] = $data[$key];
        }

        //Turn Keys to variables...
        extract($the_data);

        if (!$recipients)
        {
            e(lang('No recipients with the thread'));
            return false;
        }

        $recipients[] = $userid;
        $recipients = array_unique($recipients);

        if (!$userid)
        {
            e(lang('Please login / Invalid user'));
            return false;
        }

        arsort($recipients);
        $total_recipients = count($recipients);
        
        if($subject)
            $subject = strip_tags(replacer($subject));

        $recipients_imploded = implode('|', $recipients);
        $recipient_md5 = md5($recipients_imploded);

        //Make first three MAIN recipients
        $main_limit = 3;
        $main_recipients = array();
        for ($i = 0; $i < $main_limit; $i++)
        {
            if ($recipients[$i])
                $main_recipients[] = get_basic_user_details($recipients[$i]);
        }

        $thread_id = $this->get_thread_from_md5($recipient_md5);

        if (!$thread_id)
        {
            $insert_array = array(
                'thread_type' => $thread_type,
                'userid' => $userid,
                'recipient_md5' => $recipient_md5,
                'total_recipients' => $total_recipients,
                'date_added' => now(),
                'subject'       => $subject,
                'main_recipients' => json_encode($main_recipients),
                'time_added' => time()
            );

            $thread_id = db_insert(tbl('threads'), $insert_array);

            //Add recipients
            $this->add_recipients($recipients, $thread_id);
        }

        if ($thread_id)
            return $thread_id;
        else
            return false;
    }

    /**
     * get thread from md5 hash
     * @param STRING $md5
     * @return INT $thread_id
     */
    function get_thread_from_md5($md5)
    {
        $md5 = mysql_clean($md5);
        $query = "SELECT thread_id";
        $query .= " FROM " . tbl('threads');
        $query .= " WHERE recipient_md5='$md5' ";
        $query .= " LIMIT 1";

        $results = db_select($query);

        global $db;

        if ($db->num_rows > 0)
        {
            return $results[0]['thread_id'];
        }
        else
        {
            return false;
        }
    }

    /**
     * Send a private message...
     * 
     * @param ARRAY
     * @param INT $message_id
     */
    function send_message($params)
    {
        $default_value =
                array(
                    'userid' => userid(),
                    'recipients' => array(),
                    'subject' => "",
                    'message' => "",
                    'thread_id' => NULL
        );

        $data = array_merge($default_value, $params);

        extract($data);

        if (!$this->thread_exists($thread_id))
        {
            $thread_id = $this->create_thread($data);
        }

        if (!$thread_id)
        {
            e(lang("Invalid thread id"));
            return false;
        }

        $message = strip_tags(replacer($message));
        $subject = strip_tags(replacer($subject));

        if (!$message)
        {
            e(lang('Message was empty'));
            return false;
        }

        //Add message and send emnail...

        $fields = array(
            'subject' => $subject,
            'message' => $message,
            'userid' => $userid,
            'thread_id' => $thread_id,
            'date_added' => now(),
            'time_added' => time(),
        );

        $message_id = db_insert(tbl('messages'), $fields);

        //Update thread

        $fields = array(
            'last_userid' => $userid,
            'last_message_id' => $message_id,
            'last_message_date' => now(),
            'last_message' => $message,
            'total_messages' => '{{total_messages+1}}'
        );


        //Exlude sender
        $exclude_notifiers = array($userid);
        $this->add_message_notifications($thread_id, $exclude_notifiers);


        db_update(tbl('threads'), $fields, " thread_id='$thread_id' ");

        //Increment unread message
        db_update(tbl('recipients'), array(
            'unread_msgs' => '{{unread_msgs+1}}',
            'unseen_msgs' => '{{unseen_msgs+1}}'
                ), "thread_id='$thread_id' AND userid !='$userid' ");


        global $userquery;

        //if($message_id)


        return $message_id;
    }

    /**
     * check if thread exists using threadid
     * 
     * @param INT $thread_id
     * @return BOOLEAN
     */
    function thread_exists($tid)
    {
        $tid = mysql_clean($tid);
        $query = " SELECT thread_id FROM " . tbl('threads');
        $query .= " WHERE thread_id='$tid' ";
        $query .= " LIMIT 1";

        $result = db_select($query);

        global $db;

        if ($db->num_rows > 0)
            return true;
        else
            return false;
    }

    /**
     * add recipient
     * 
     * @param INT userid
     * @param INT thread_id
     * 
     */
    function add_recipient($uid, $thread_id, $check_thread = true)
    {
        if ($check_thread)
        {
            if (!$this->thread_exists($thread_id))
            {
                e(lang('Invalid thread while adding recipient'));
                return false;
            }
        }

        $fields = array(
            'userid' => $uid,
            'thread_id' => $thread_id,
            'date_added' => now(),
            'time_added' => time(),
        );

        db_insert(tbl('recipients'), $fields);
    }

    /**
     * Adding multiple recipients;
     * 
     * @param ARRAY recipients
     * @param INT thread
     * @param BOOLEAN check_thread
     */
    function add_recipients($users, $thread_id, $check_thread = true)
    {
        if ($check_thread)
        {
            if (!$this->thread_exists($thread_id))
            {
                e(lang('Invalid thread while adding recipient'));
                return false;
            }
        }

        $users = array_unique($users);

        foreach ($users as $uid)
            $fields[] = array(
                'userid' => $uid,
                'thread_id' => $thread_id,
                'date_added' => now(),
                'time_added' => time(),
            );


        db_multi_insert(tbl('recipients'), $fields);
    }

    /**
     * Get threads
     * 
     * THreads are always returned for a USER..against UID
     * 
     * @param ARRAY $options
     * @return ARRAY $messages
     * 
     */
    function get_threads($options = NULL)
    {
        $o = $options;

        $fields_array = array(
            't' => array(
                'thread_id', 'total_recipients', 'total_messages',
                'date_added', 'time_added', 'last_message_date',
                'main_recipients', 'last_message','subject'
            ),
            'r' => 'recipient_id'
        );

        $the_fields = tbl_fields($fields_array);


        /**
         * How this query works..
         * 
         * 1st it will get all thread_ids from recipients table against userid()
         * 2nd once all thread_ids are here, we will INNER JOIN them
         * ON recipients.thread_id = threads.thread_id
         * now we have our own threads..in order to get more recipients
         * (here we have 3 as a limit) we will INNER JOIN our query again
         * with the recipients as you can see its name is tr
         * ON threads.thread_id = tr.thread_id
         * 
         * This query is not used anymore because of its complexity


          $query = "  SELECT $the_fields FROM " . tbl('recipients') . " as r";
          $query .= " INNER JOIN " . tbl('threads') . ' as t ON ';
          $query .= ' t.thread_id=r.thread_id ';
          $query .= " INNER JOIN " . tbl('messages') . ' as m ON ';
          $query .= ' t.last_message_id=m.message_id ';
          $query .= " INNER JOIN " . tbl('recipients') . " as tr ON ";
          $query .= " t.thread_id = tr.thread_id";
          $query .= " INNER JOIN (SELECT * FROM ".tbl('users')." LIMIT 3 ) AS u on ";
          $query .= " u.userid = tr.userid";

         * 
         */
        /** Simplified query * */
        $query = "  SELECT $the_fields FROM " . tbl('recipients') . " as r";
        $query .= " INNER JOIN " . tbl('threads') . ' as t ON ';
        $query .= ' t.thread_id=r.thread_id ';


        start_where();

        if ($o['userid'])
        {
            add_where("r.userid='" . $o['userid'] . "'");
        }
        elseif (userid())
        {
            add_where("r.userid='" . userid() . "'");
        }
        else
        {
            return false;
        }

        if (isset($o['unread']))
        {
            add_where(" r.unread_msgs>0 ");
        }

        if (isset($o['unseen']))
        {
            add_where(" r.unseen_msgs>0 ");
        }

        if (get_where())
            $query .= " WHERE " . get_where();

        end_where();

        if (!$o['order'])
            $query .= " ORDER BY last_message_date DESC ";
        else
            $query .= " ORDER BY " . $o['order'];

        if ($o['limit'])
            $query .= " LIMIT " . $o['limit'];

        $results = db_select($query);

        $the_results = array();
        if ($results)
            foreach ($results as $result)
            {
                $the_results[] = $result;
                //$the_results[$result['thread_id']]['thread'] = $result;
                //$the_results[$result['thread_id']]['recipients'][] = $result;
            }



        return $the_results;
    }

    /**
     * Mark User messages seen
     * 
     * This will set unseen_msgs to 0 when user clicks 
     * on icon to get new messages or visits PM/Inbox page
     * 
     * @param INT 
     */
    function mark_messages_seen($uid)
    {
        if ($uid)
            return false;

        $uid = mysql_clean($uid);

        db_update(tbl('recipients'), array(
            'unseen_msgs' => 0
                ), " userid='$uid' ");

        return true;
    }

    /**
     * get messages
     * 
     * @param ARRAY $options
     */
    function get_messages($array = NULL)
    {
        $fields_array = array(
            'm' => array(
                'message_id', 'thread_id', 'message', 'subject', 'seen_by',
                'time_added'
            ),
            'u' => array(
                'username', 'email', 'first_name', 'last_name', 'userid',
                'avatar', 'avatar_url'
            )
        );

        $the_fields = tbl_fields($fields_array);
        
        $thread_id = $array['thread_id'];


        $query = " SELECT " . $the_fields . " FROM " . tbl('messages'). " AS m";
        $query .= " LEFT JOIN " . tbl('users') . ' AS u ON  ';
        $query .= 'm.userid = u.userid ';

        $query .= " WHERE thread_id='$thread_id' ";
        $query .= " ORDER BY time_added ASC ";
        
        $results = db_select($query);

        return $results;
    }

    /**
     * Get thread along with all the details..
     * 
     * @param INT $thread_id
     * @param ARRAY $thread
     */
    function get_thread($tid)
    {
        $tid = mysql_clean($tid);

        $fields_array = array(
            't' => array(
                'thread_id', 'total_recipients', 'total_messages',
                'date_added', 'time_added', 'last_message_date',
                'main_recipients', 'last_message','subject'
            ),
            'r' => array(
                'recipient_id',
            ),
        );

        $the_fields = tbl_fields($fields_array);

        $thread_id = $tid;

        $query = "  SELECT $the_fields FROM " . tbl('recipients') . " as r";
        $query .= " INNER JOIN " . tbl('threads') . ' as t ON ';
        $query .= ' t.thread_id=r.thread_id ';

        start_where();

        if ($o['userid'])
        {
            add_where("r.userid='" . $o['userid'] . "'");
        }
        elseif (userid())
        {
            add_where("r.userid='" . userid() . "'");
        }
        else
        {
            return false;
        }

        add_where("r.thread_id='" . $tid . "'");


        if (get_where())
            $query .= " WHERE " . get_where();

        end_where();

        $query .= " LIMIT 1 ";

        $results = db_select($query);

        if ($results)
            return $results[0];
        else
            return false;
    }

    /**
     * Add message notification
     */
    function add_message_notifications($thread_id, $exclude = NULL)
    {
        global $userquery;

        $query = "SELECT userid FROM " . tbl('recipients');
        $query .= " WHERE thread_id='$thread_id' ";

        $results = db_select($query);

        if (!is_array($exclude))
            $exclude = array();

        if ($results)
        {
            foreach ($results as $user)
            {
                if (!in_array($user['userid'], $exclude))
                    $userquery->new_notify($user['userid'], 'new_msgs');
            }
        }
    }

}

?>