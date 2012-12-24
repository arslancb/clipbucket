<?php

/**
 * CBV3 Them configs...
 */

//Template files
$array = array(
    'single_comment'        => 'blocks/comment.html',
    'single_feed_comment'   => 'blocks/single_feed_comment.html',
    'comments'              => 'blocks/comments.html',
    'topic'                 => 'blocks/groups/group_topic.html',
    'single_group_feed'     => 'blcoks/groups/group_feed.html',
    'single_feed_user'      => 'blocks/view_channel/feed.html',
);

set_config('template_files',$array);

//Setting the pagination tag
$pages->pagination_tag = '<a #params# class="btn">#page#</a>';
$pages->skipper = '<a #params# class="btn disabled">...</a>';


?>
