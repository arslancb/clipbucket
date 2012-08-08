<?php

// Funtions ued in for admin area only

function list_admin_categories($categories) {
    foreach ($categories as $category) {
        if (!$category['parent_id'])
            list_category($category);
    }
}

function list_category($category, $prefix = NULL) {
    if ($category) {
        assign('prefix', $prefix);
        assign('category', $category);
        Template('blocks/category.html');

        if ($category['children']) {
            $prefix = $prefix . ' &ndash;	 ';
            foreach ($category['children'] as $child) {
                list_category($child, $prefix);
            }
        }
    }
}

/**
 * Register a block for admin home page...
 * 
 */
function register_admin_block($prop) {
    global $Cbucket;

    $blocks = $Cbucket->admin_blocks;


    if (!$prop['title'] || !$prop['function'] || !function_exists($prop['function']))
        return false;
    $slug = slug($prop['title']);
    if (!$slug)
        return false;


    $blocks[$slug] = $prop;


    return $Cbucket->admin_blocks = $blocks;
}

/**
 * get admin blocks..
 */
function get_admin_blocks() {
    global $Cbucket;
    $blocks = $Cbucket->admin_blocks;

    //Check order and container...
    $block_orders = config('admin-block-orders');
    $block_orders = json_decode($block_orders, true);

    $new_blocks = array();

    $default_container = 1;
    $default_order = 1;

    foreach ($blocks as $id => $block) {

        $order = $default_order;
        $container = $default_container;

        if ($block_orders[$id]['order'])
            $order = $block_orders[$id]['order'];
        if ($block_orders[$id]['container'])
            $container = $block_orders[$id]['container'];

        $block['order'] = $order;
        $block['container'] = $container;
        $block['id'] = $id;

        $new_blocks[] = $block;

        if ($default_container === 1)
            $default_container = 2; else
            $default_container = 1;

        $default_order++;
    }



    usort($new_blocks, "cmp_block_order");

    return $new_blocks;
}

function cmp_block_order($a, $b) {
    return strcmp($a["order"], $b["order"]);
}

/**
 * List amdin blocks
 */
function list_admin_blocks($container = 1) {
    if ($container < 1 || $container > 2)
        $container = 1;

    $blocks = get_admin_blocks();

    foreach ($blocks as $block) {
        if ($block['container'] == $container) {

            $block['content'] = $block['function']();
            assign('block', $block);

            Template('blocks/home-block.html');
        }
    }
}

/**
 * Display admin home page overview
 */
function admin_home_overview() {
    return Fetch('blocks/home/overview.html');
}

function admin_home_stats() {
    return Fetch('blocks/home/stats.html');
}

function admin_home_notes() {
    return Fetch('blocks/home/notes.html');
}

function admin_home_activity() {
    return Fetch('blocks/home/activity.html');
}

?>