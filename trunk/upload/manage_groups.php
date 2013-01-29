<?php

/*
 * ***************************************************************
  | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
  | @ Author	   : ArslanHassan
  | @ Software  : ClipBucket , � PHPBucket.com
 * ***************************************************************
 */

define("THIS_PAGE", 'manage_groups');
define("PARENT_PAGE", "groups");

require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user', $udetails);
assign('p', $userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page, VLISTPP);


switch ($mode) {
    case 'manage':
    default: {
            if ($_GET['gid_delete']) {
                $gid = $_GET['gid_delete'];
                $cbgroup->delete_group($gid);
            }

            assign('mode', 'manage');


            $order_by = 'date_added DESC';

            if (isset($_GET['order'])) {
                $order = get('order');
                if ($order == 'oldest')
                    $order_by = 'date_added ASC';
            }


            assign('total_groups', 0);
            $usr_groups = $cbgroup->get_groups(array('user' => userid(), 'order' => $order_by));
            
            assign('usr_groups', $usr_groups);
            if($usr_groups)
            assign('total_groups', count($usr_groups));

            assign('groups', $usr_groups);
        }
        break;

    case 'manage_members': {
            assign('mode', 'manage_members');
            $gid = mysql_clean($_GET['gid']);
            $gdetails = $cbgroup->get_group_details($gid);

            $gArray =
                    array
                        (
                        'group' => $gdetails,
                        'groupid' => $gid,
                        'uid' => userid(),
                        'user' => $userquery->udetails,
                        'checkowner' => 'yes'
            );

            if (!$cbgroup->is_admin($gArray) && !has_access('admin_access', true))
                e(lang("you_cant_moderate_group"));
            else {
                //assign querystring
                $queryString = queryString(NULL, array(
                    'make_admin',
                    'remove_admin',
                    'deactivate',
                    'activate',
                    'delete',
                    'unban',
                    'ban'));


                assign('queryString', $queryString);
                //Activating Member Members
                if (isset($_POST['activate_pending'])) {
                    $total = count($_POST['users']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['users'][$i] != '')
                            $cbgroup->member_actions($gid, $_POST['users'][$i], 'activate');
                    }
                    $cbgroup->update_group_members($gid);
                }

                if ($_GET['activate']) {
                    $uid = mysql_clean($_GET['activate']);
                    $cbgroup->member_actions($gid, $uid, 'activate');
                    $cbgroup->update_group_members($gid);
                }



                //Deactivation Members
                if (isset($_POST['disapprove_members'])) {
                    $total = count($_POST['users']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['users'][$i] != '')
                            $cbgroup->member_actions($gid, $_POST['users'][$i], 'deactivate');
                    }
                    $cbgroup->update_group_members($gid);
                }
                if ($_GET['deactivate']) {
                    $uid = mysql_clean($_GET['deactivate']);
                    $cbgroup->member_actions($gid, $uid, 'deactivate');
                    $cbgroup->update_group_members($gid);
                }

                //Deleting Members
                if (isset($_POST['delete_members'])) {
                    $total = count($_POST['users']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['users'][$i] != '')
                            $cbgroup->member_actions($gid, $_POST['users'][$i], 'delete');
                    }
                    $cbgroup->update_group_members($gid);
                }

                if ($_GET['delete']) {
                    $uid = mysql_clean($_GET['delete']);
                    $cbgroup->member_actions($gid, $uid, 'delete');
                    $cbgroup->update_group_members($gid);
                }



                //Ban Members
                if (isset($_POST['ban_members'])) {
                    $total = count($_POST['users']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['users'][$i] != '')
                            $cbgroup->member_actions($gid, $_POST['users'][$i], 'ban');
                    }
                    $cbgroup->update_group_members($gid);
                }

                if ($_GET['ban']) {
                    $uid = mysql_clean($_GET['ban']);
                    $cbgroup->member_actions($gid, $uid, 'ban');
                    $cbgroup->update_group_members($gid);
                }

                //unban Members
                if (isset($_POST['unban_members'])) {
                    $total = count($_POST['users']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['users'][$i] != '')
                            $cbgroup->member_actions($gid, $_POST['users'][$i], 'unban');
                    }
                    $cbgroup->update_group_members($gid);
                }

                if ($_GET['unban']) {
                    $uid = mysql_clean($_GET['unban']);
                    $cbgroup->member_actions($gid, $uid, 'unban');
                    $cbgroup->update_group_members($gid);
                }


                //Making Admin
                if ($_GET['make_admin']) {
                    $uid = mysql_clean($_GET['make_admin']);
                    $cbgroup->make_admin(array('groupid' => $gid, 'group' => $gdetails, 'uid' => $uid));
                    if (!error()) {
                        $makeAdmins[$uid] = 'yes';
                        assign('makeAdmins', $makeAdmins);
                    }
                }

                //Remove Admin
                if ($_GET['remove_admin']) {
                    $uid = mysql_clean($_GET['remove_admin']);
                    $cbgroup->remove_admin(array('groupid' => $gid, 'group' => $gdetails, 'uid' => $uid));
                    if (!error()) {
                        $rmAdmins[$uid] = 'yes';
                        assign('rmAdmins', $rmAdmins);
                    }
                }


                if ($gdetails) {
                    $limit = 30;

                    $page = mysql_clean($_GET['page']);
                    $get_limit = create_query_limit($page, $limit);

                    assign("group", $gdetails);
                    //Getting Group Members (Active Only)
                    $gp_mems = $cbgroup->get_members($gdetails['group_id'], $get_limit);



                    $pending_mems = $cbgroup->get_members($gdetails['group_id'], $get_limit, 'no');

                    assign('pending_members', $pending_mems);
                    assign('group_members', $gp_mems);
                    assign('group_members_mixed', array('all_members' => $gp_mems, 'pending_members' => $pending_mems));
                }else
                    e(lang("grp_exist_error"));
            }
        }
        break;
    case 'manage_videos': {

            //assign querystring
            $queryString = queryString(NULL, array(
                'disapprove',
                'approve',
                'delete'
                    ));

            assign('queryString', $queryString);

            assign('mode', 'manage_videos');
            $gid = mysql_clean($_GET['gid']);
            $gdetails = $cbgroup->get_group_details($gid);

            $gArray =
                    array
                        (
                        'group' => $gdetails,
                        'groupid' => $gid,
                        'uid' => userid(),
                        'user' => $userquery->udetails,
                        'checkowner' => 'yes'
            );

            if (!$cbgroup->is_admin($gArray) && !has_access('admin_access', true))
                e(lang("you_cant_moderate_group"));
            else {
                //Activating videos
                if (isset($_POST['activate_videos'])) {
                    $total = count($_POST['check_vid']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['check_vid'][$i] != '')
                            $cbgroup->video_actions($gid, $_POST['check_vid'][$i], 'activate');
                    }

                    if (!error() && msg()) {
                        $eh->flush_msg();
                        e(lang('Selected videos have approved'), 'm');
                    }
                }

                if ($_GET['approve']) {
                    $vid = mysql_clean($_GET['approve']);
                    $cbgroup->video_actions($gid, $vid, 'activate');
                }


                if ($_GET['disapprove']) {
                    $vid = mysql_clean($_GET['disapprove']);
                    $cbgroup->video_actions($gid, $vid, 'deactivate');
                }

                if ($_GET['delete']) {
                    $vid = mysql_clean($_GET['delete']);
                    $cbgroup->video_actions($gid, $vid, 'delete');
                }


                //Deactivation videos
                if (isset($_POST['disapprove_videos'])) {
                    $total = count($_POST['check_vid']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['check_vid'][$i] != '')
                            $cbgroup->video_actions($gid, $_POST['check_vid'][$i], 'deactivate');
                    }

                    if (!error() && msg()) {
                        $eh->flush_msg();
                        e(lang('Selected videos have disapproved'), 'm');
                    }
                }
                //Deleting videos
                if (isset($_POST['delete_videos'])) {
                    $total = count($_POST['check_vid']);
                    for ($i = 0; $i < $total; $i++) {
                        if ($_POST['check_vid'][$i] != '')
                            $cbgroup->video_actions($gid, $_POST['check_vid'][$i], 'delete');
                    }

                    if (!error() && msg()) {
                        $eh->flush_msg();
                        e(lang('Selected videos have removed from this group'), 'm');
                    }
                }



                if ($gdetails) {

                    $video_limit = 20;
                    $video_limit = apply_filters($video_limit, 'manage_group_videos_limit');
                    $page = mysql_clean($_GET['page']);
                    $get_limit = create_query_limit($page, $video_limit);

                    assign("group", $gdetails);
                    //Getting Group Videos (Active Only)
                    $grp_vids = $cbgroup->get_group_videos($gid, NULL, $get_limit);

                    assign('grp_vids', $grp_vids);
                    assign('videos', $grp_vids);

                    //Getting pending videos
                    $pending_videos = $cbgroup->get_group_videos($gid, 'no', $get_limit);
                    assign('pending_videos', $pending_videos);

                    $group_videos = array('all_videos' => $grp_vids, 'pending_videos' => $pending_videos);
                    assign('group_videos', $group_videos);


                    $total_pages = count_pages($gdetails['total_videos'], $video_limit);
                    //Pagination
                    $pages->paginate($total_pages, $page);
                }else
                    e(lang("grp_exist_err"));
            }
        }
        break;

    case 'joined': {

            //Leaving Groups
            if (isset($_POST['leave_groups'])) {
                $total = count($_POST['check_gid']);
                for ($i = 0; $i < $total; $i++)
                    $cbgroup->leave_group($_POST['check_gid'][$i], userid());
            }

            assign('mode', 'joined');
            $mem_grps = $cbgroup->user_joined_groups(userid());
            assign('usr_groups', $mem_grps);
        }
        break;
}

subtitle(lang("grp_groups_title"));
template_files('manage_groups.html');
display_it();
?>