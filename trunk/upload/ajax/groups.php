<?php

include("../includes/config.inc.php");

$mode = $_POST['mode'];

switch ($mode) {

    case "add_topic": {

            $array = $_POST;
            $tid = $cbgroup->add_topic($array);

            if (error()) {
                echo json_encode(array('err' => error()));
            } else {

                $topic = $cbgroup->get_topic_details($tid);

                Assign('topic', $topic);
                $template = get_template('topic');
                echo json_encode(array('success' => 'yes', 'tid' => $tid
                    , 'template' => $template));
            }
        }
        break;


    case "join_group":
    case "leave_group": {
            $gid = mysql_clean(post('group_id'));

            if ($mode == 'join_group')
                $cbgroup->join_group($gid, userid());
            else
                $cbgroup->leave_group($gid, userid());

            if (error()) {
                echo json_encode(array('err' => error()));
            } else {
                echo json_encode(array('success' => 'yes'));
            }
        }
        break;

    case "get_topics": {

            $gid = mysql_clean(post('group_id'));
            $page = mysql_clean(post('page'));

            $topics_limit = 10;
            $get_limit = create_query_limit($page, $topics_limit);

            //Getting list of topics
            $topics = $cbgroup->get_topics(array(
                'group' => $gid,
                'limit' => $get_limit,
                    ));

            $template = "";

            if ($topics) {
                foreach ($topics as $topic) {
                    assign('topic', $topic);
                    $template .= get_template('group_topic');
                }
            }

            echo json_encode(array('success' => 'ok', 'topics' => $template));
        }
        break;



    case "getVideos":
    case "get_videos": {

            $gid = mysql_clean(post('group_id'));
            $page = mysql_clean(post('page'));

            $group_videos_limit = 18;
            $group_videos_limit = apply_filters($group_videos_limit, 'group_videos_limit');

            $get_limit = create_query_limit($page, $group_videos_limit);

            $videos = $cbgroup->get_group_videos($gid, "yes", $get_limit);

            if ($videos) {
                foreach ($videos as $video) {
                    assign('video', $video);
                    $template .= get_template('group_video');
                }
            }

            echo json_encode(array('success' => 'ok', 'videos' => $template));
        }

        break;

    case "delete_groups" : {
            try {

                $gids = post('gids');
                
                if(!$gids || !is_array($gids)) cb_error(lang("No group was selected"));

                foreach ($gids as $gid) {
                    if ($gid) {
                        $cbgroup->delete_group($gid);
                    }
                }
                
                if (error())
                    cb_error(error('single'));

                exit(json_encode(array('success' => 'yes')));
                
            } catch (Exception $e) {

                exit(json_encode(array('err' => array($e->getMessage()))));
            }
        }
    default:
        exit(json_encode(array('err' => array(lang('Invalid request')))));
}
?>
