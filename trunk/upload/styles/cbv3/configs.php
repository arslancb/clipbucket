<?php

/**
 * CBV3 Them configs...
 */

//Template files
$array = array(
    'single_comment' => 'blocks/comment.html',
    'comments'       => 'blocks/comments.html',
    'topic'          => 'blocks/groups/topic.html',
);

set_config('template_files',$array);

//Setting the pagination tag
$pages->pagination_tag = '<a #params# class="btn">#page#</a>';
$pages->skipper = '<a #params# class="btn disabled">...</a>'
?>
