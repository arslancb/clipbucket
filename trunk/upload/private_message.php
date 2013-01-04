<?php

/*
 * **************************************************************
  | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
  | @ Author	   : ArslanHassan
  | @ Software  : ClipBucket , © PHPBucket.com
 * ***************************************************************
 */

define("THIS_PAGE", 'private_message');

require 'includes/config.inc.php';

//Adding JS Scroll
add_js('jquery_plugs/compressed/jquery.scrollTo-min.js');

$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user', $udetails);
assign('p', $userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

//Deleting Multple Message
if (isset($_POST['delete_pm']))
{
    if ($mode == 'inbox' || $mode == 'notification')
        $box = 'in';
    else
        $box = 'out';

    $total = count($_POST['msg_id']);
    for ($pms = 0; $pms < $total; $pms++)
    {
        if (!empty($_POST['msg_id'][$pms]))
        {
            $cbpm->delete_msg($_POST['msg_id'][$pms], userid(), $box);
        }

        $eh->flush();
        e(lang('private_messags_deleted'), 'm');
    }
}



switch ($mode)
{
    case 'inbox':
    default:
        {
            $query_string = queryString(NULL, array('thread_id'));

            assign('query_string', $query_string);

            $threads = $cbpm->get_threads(array(
                'userid'    => userid()
            ));

            assign('threads', $threads);

            //ADd new message
            if (isset($_POST['add_message']))
            {
                $tid = mysql_clean($_POST['thread_id']);

                $cbpm->send_message(array(
                    'thread_id' => $tid,
                    'subject' => post('subject'),
                    'message' => post('message')
                ));
            }

            //Get Messages if thread is selected
            if (isset($_GET['thread_id']))
            {
                $tid = mysql_clean($_GET['thread_id']);
                

                $messages = $cbpm->get_messages(array(
                    'thread_id' => $tid
                ));

                //Get Thread details..

                $thread = $cbpm->get_thread($tid);

                assign('thread', $thread);
                assign('messages', $messages);
                assign('thread_id',$tid);
            }
            
            if(isset($_GET['mid']))
            {
                assign('mid',clean($_GET['mid']));
            }

            assign('mode', 'inbox');

            subtitle(lang("com_my_inbox"));
        }
        break;

    case 'sent':
        {
            assign('mode', 'sent');


            //Deleting Message
            if ($_GET['delete_mid'])
            {
                $mid = mysql_clean($_GET['delete_mid']);
                $cbpm->delete_msg($mid, userid(), 'out');
            }

            //Getting Message
            if ($_GET['mid'])
            {
                $mid = mysql_clean($_GET['mid']);
                assign('pr_msg', $cbpm->get_outbox_message($mid, userid()));
            }

            //Get User Messages
            assign('user_msgs', $cbpm->get_user_outbox_messages(userid()));

            subtitle(lang("user_sent_box"));
        }

        break;

    case 'notification':
        {
            assign('mode', 'notification');

            //Deleting Message
            if ($_GET['delete_mid'])
            {
                $mid = mysql_clean($_GET['delete_mid']);
                $cbpm->delete_msg($mid, userid());
            }

            //Getting Message
            if ($_GET['mid'])
            {
                $mid = mysql_clean($_GET['mid']);
                assign('pr_msg', $cbpm->get_inbox_message($mid, userid()));
            }

            //Get User Messages
            assign('user_msgs', $cbpm->get_user_notification_messages(userid()));

            subtitle(lang("my_notifications"));
        }
        break;

    case 'new_msg':
    case 'compose':
        {
            assign('mode', 'new_msg');


            //sending message
            if (isset($_POST['send_message']))
            {
                $recipients = post('as_values_pm_recipients');
                if(!$recipients)
                    $recipients = post('recipients');
                
                $recipients = explode(',',$recipients);
                $_recipients = array();
                foreach($recipients as $recipient){
                    if($recipient){
                        $uid = user_exists($recipient);
                        if($uid)
                        {
                            $_recipients[] = $uid;
                        }
                    }
                }
                $recipients = $_recipients;
                
                $subject = post('subject');
                $message = post('message');
                
                
                $mid = $cbpm->send_message(array(
                    'subject'   => $subject,
                    'message'   => $message,
                    'recipients'    => $recipients
                    ));
                
                if($mid)
                {
                    $message_details = $cbpm->get_message($mid);
                    pr($message_details,true);
                    //$tid = thread id
                    $tid = $message_details['thread_id'];
                    //Redirect to thread..@todo work on this section
                    header('location:'.BASEURL.'/private_message.php?mode=inbox&thread_id='.$tid.'&mid='.$mid);
                }
                
            }

            subtitle(lang("title_crt_new_msg"));
        }
}

template_files('private_message.html');
display_it();
?>