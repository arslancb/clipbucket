<?php

/**
 * Fetch Smarty Template
 * 
 * @param type $name
 * @param type $inside
 * @return type 
 */
function Fetch($name, $layout = true)
{


    if (file_exists($name) && !is_dir($name))
    {
        $fileName = $name;
    }
    elseif ($layout === true)
    {
        $fileName = LAYOUT . '/' . $name;
    }
    elseif ($layout)
    {
        $fileName = $layout . $name;
    }
    else
    {
        $fileName = $name;
    }

    $file = CBTemplate::fetch($fileName);

    return $file;
}

/**
 * Function used to render Smarty Template
 * 
 * @global type $admin_area
 * @param type $template
 * @param type $layout 
 */
function Template($template, $layout = true)
{

    global $admin_area, $Cbucket;


    //Getting list of variables and classes to make them global..
    if ($Cbucket->templateClasses)
    {
        foreach ($Cbucket->templateClasses as $tClasskey)
        {
            global ${$tClasskey};
        }
    }

    if ($Cbucket->template_details['php'] != 'on')
    {

        if ($layout)
            CBTemplate::display(LAYOUT . '/' . $template);
        else
            CBTemplate::display($template);
    }else
    {
        if ($layout)
            $template_file = LAYOUT . '/' . $template;
        else
            $template_file = $template;

        if (file_exists($template_file))
            include($template_file);
    }
}

/**
 * assign smarty variable
 * 
 * @param type $name
 * @param type $value 
 */
function Assign($name, $value)
{
    global $Cbucket;
    $CBucket->templateVars[$name] = $value;
    CBTemplate::assign($name, $value);
}

/**
 * Function used to add tempalte in display template list
 * @param File : file of the template
 * @param Folder : weather to add template folder or not
 * if set to true, file will be loaded from inside the template
 * such that file path will becom $templatefolder/$file
 * @param follow_show_page : this param tells weather to follow ClipBucket->show_page
 * variable or not, if show_page is set to false and follow is true, this template will not load
 * otherwise there it WILL
 */
function template_files($file, $folder = false, $follow_show_page = true)
{
    global $ClipBucket;
    if (!$folder)
        $ClipBucket->template_files[] = array('file' => $file, 'follow_show_page' => $follow_show_page);
    else
        $ClipBucket->template_files[] = array('file' => $file,
            'folder' => $folder, 'follow_show_page' => $follow_show_page);
}

/**
 * Function used to include file
 */
function include_template_file($params)
{
    $file = $params['file'];

    //Assign Vars
    if ($params)
    {
        foreach ($params as $name => $value)
        {
            if ($name != 'file')
            {
                assign($name, $value);
            }
        }
    }


    if (file_exists(LAYOUT . '/' . $file))
    {
        echo '<!-- Including ' . $file . ' -->';
        Template($file);
    }
    elseif (file_exists($file))
    {
        echo '<!-- Including ' . $file . ' -->';
        Template($file, false);
    }
    elseif (file_exists(STYLES_DIR . '/global/' . $file))
    {
        echo '<!-- Including ' . $file . ' -->';
        Template(STYLES_DIR . '/global/' . $file, false);
    }
}

/**
 * Function used to fetch file
 */
function fetch_template_file( $params ) {
    $file = $params[ 'file' ];

    if ( $params ) {
        foreach ($params as $name => $value) {
            if ($name != 'file') {
                assign( $name, $value );
            }
        }
    }
    
    if ( file_exists( LAYOUT . '/' . $file ) ) {
        $output = "<!-- fetching layout/$file -->";
        $output .= Fetch( $file );
    } else if ( file_exists( $file ) ) {
        $output = "<!-- fetching $file -->";
        $output .= Fetch ( $file, false );
    } else if ( file_exists( STYLES_DIR . '/global/' . $file ) ) {
        $output = "<!-- fetching global/$file -->";
        $output .= Fetch ( STYLES_DIR . '/global/' . $file, false );
    } else {
        $output = "<!-- fetching $file, No file found -->";
    }
    
    return $output;
}

/**
 * Function used to call display
 */
function display_it()
{

    global $ClipBucket;
    $dir = LAYOUT;

    foreach ($ClipBucket->template_files as $file)
    {
        if (file_exists(LAYOUT . '/' . $file) || is_array($file))
        {

            if (!$ClipBucket->show_page && $file['follow_show_page'])
            {
                
            }
            else
            {
                if (!is_array($file))
                    $new_list[] = $file;
                else
                {
                    if ($file['folder'] && file_exists($file['folder'] . '/' . $file['file']))
                        $new_list[] = $file['folder'] . '/' . $file['file'];
                    else
                        $new_list[] = $file['file'];
                }
            }
        }
    }

    assign('template_files', $new_list);

    Template('body.html');

    footer();
}

function showpagination($total, $page, $link, $extra_params = NULL, $tag = '<a #params#>#page#</a>')
{
    global $pages;
    return $pages->pagination($total, $page, $link, $extra_params, $tag);
}


function smarty_lang($param)
{
    if ($param['assign'] == '')
        return lang($param['code'], $param['sprintf']);
    else
        assign($param['assign'], lang($param['code'], $param['sprintf']));
}

/**
 * Function used to get player logo
 */
function website_logo()
{
    $logo_file = config('player_logo_file');
    if (file_exists(BASEDIR . '/images/' . $logo_file) && $logo_file)
        return BASEURL . '/images/' . $logo_file;

    return BASEURL . '/images/logo.png';
}

/**
 * Function used to assign link
 */
function cblink($params)
{
    global $ClipBucket;
    $name = $params['name'];
    $ref = $param['ref'];

    if ($name == 'category')
    {
        return category_link($params['data'], $params['type']);
    }
    if ($name == 'sort')
    {
        return sort_link($params['sort'], 'sort', $params['type']);
    }
    if ($name == 'time')
    {
        return sort_link($params['sort'], 'time', $params['type']);
    }
    if ($name == 'tag')
    {
        return BASEURL . '/search_result.php?query=' . urlencode($params['tag']) . '&type=' . $params['type'];
    }
    if ($name == 'category_search')
    {
        return BASEURL . '/search_result.php?category[]=' . $params['category'] . '&type=' . $params['type'];
    }


    if (SEO != 'yes')
    {
        preg_match('/http:\/\//', $ClipBucket->links[$name][0], $matches);
        if ($matches)
            $link = $ClipBucket->links[$name][0];
        else
            $link = BASEURL . '/' . $ClipBucket->links[$name][0];
    }else
    {
        preg_match('/http:\/\//', $ClipBucket->links[$name][1], $matches);
        if ($matches)
            $link = $ClipBucket->links[$name][1];
        else
            $link = BASEURL . '/' . $ClipBucket->links[$name][1];
    }

    $param_link = "";
    if (!empty($params['extra_params']))
    {
        preg_match('/\?/', $link, $matches);
        if (!empty($matches[0]))
        {
            $param_link = '&' . $params['extra_params'];
        }
        else
        {
            $param_link = '?' . $params['extra_params'];
        }
    }

    if ($params['assign'])
        assign($params['assign'], $link . $param_link);
    else
        return $link . $param_link;
}

/**
 * Function used to show rating
 * @inputs
 * class : class used to show rating usually rating_stars
 * rating : rating of video or something
 * ratings : number of rating
 * total : total rating or out of
 */
function show_rating($params)
{
    $class = $params['class'] ? $params['class'] : 'rating_stars';
    $rating = $params['rating'];
    $ratings = $params['ratings'];
    $total = $params['total'];
    $style = $params['style'];
    if (empty($style))
        $style = config('rating_style');
    //Checking Percent {
    if ($total <= 10)
        $total = 10;
    $perc = $rating * 100 / $total;
    $disperc = 100 - $perc;
    if ($ratings <= 0 && $disperc == 100)
        $disperc = 0;


    $perc = $perc . '%';
    $disperc = $disperc . "%";
    switch ($style)
    {
        case "percentage": case "percent":
        case "perc": default:
            {
                $likeClass = "UserLiked";
                if (str_replace('%', '', $perc) < '50')
                    $likeClass = 'UserDisliked';

                $ratingTemplate = '<div class="' . $class . '">
                                                                <div class="ratingContainer">
                                                                        <span class="ratingText">' . $perc . '</span>';
                if ($ratings > 0)
                    $ratingTemplate .= ' <span class="' . $likeClass . '">&nbsp;</span>';
                $ratingTemplate .='</div>
                                                        </div>';
            }
            break;

        case "bars": case "Bars": case "bar":
            {
                $ratingTemplate = '<div class="' . $class . '">
                                <div class="ratingContainer">
                                        <div class="LikeBar" style="width:' . $perc . '"></div>
                                        <div class="DislikeBar" style="width:' . $disperc . '"></div>
                                </div>
                        </div>';
            }
            break;

        case "numerical": case "numbers":
        case "number": case "num":
            {
                $likes = round($ratings * $perc / 100);
                $dislikes = $ratings - $likes;

                $ratingTemplate = '<div class="' . $class . '">
                                <div class="ratingContainer">
                                        <div class="ratingText">
                                                <span class="LikeText">' . $likes . ' Likes</span>
                                                <span class="DislikeText">' . $dislikes . ' Dislikes</span>
                                        </div>
                                </div>
                        </div>';
            }
            break;

        case "custom": case "own_style":
            {
                $file = LAYOUT . "/" . $params['file'];
                if (!empty($params['file']) && file_exists($file))
                {
                    // File exists, lets start assign things
                    assign("perc", $perc);
                    assign("disperc", $disperc);

                    // Likes and Dislikes
                    $likes = floor($ratings * $perc / 100);
                    $dislikes = $ratings - $likes;
                    assign("likes", $likes);
                    assign("dislikes", $dislikes);
                    Template($file, FALSE);
                }
                else
                {
                    $params['style'] = "percent";
                    return show_rating($params);
                }
            }
            break;
    }
    /* $rating = '<div class="'.$class.'">
      <div class="stars_blank">
      <div class="stars_filled" style="width:'.$perc.'">&nbsp;</div>
      <div class="clear"></div>
      </div>
      </div>'; */
    return $ratingTemplate;
}

/**
 * Function used to display
 * Blank Screen
 * if there is nothing to play or to show
 * then show a blank screen
 */
function blank_screen($data)
{
    global $swfobj;
    $code = '<div class="blank_screen" align="center">No Player or Video File Found - Unable to Play Any Video</div>';
    $swfobj->EmbedCode(unhtmlentities($code), $data['player_div']);
    return $swfobj->code;
}

/**
 * Adds js files in ClipBucket template.
 * 
 * @link http://docs.clip-bucket.com/user-manual/developers-guide/functions/functions_templates/add_js
 * @global type $Cbucket
 * @param type $files
 * @return type 
 * @since 2.6
 */
function add_js($files, $scope = 'global')
{
    global $Cbucket;
    if ($files)
    {
        if (is_array($scope))
        {
            foreach ($scope as $sc)
            {
                if (is_array($files))
                    foreach ($files as $file)
                        $Cbucket->JSArray[$sc][] = $file;
                else
                    $Cbucket->JSArray[$sc][] = $files;
            }
        }else
        {
            if (is_array($files))
                foreach ($files as $file)
                    $Cbucket->JSArray[$scope][] = $file;
            else
                $Cbucket->JSArray[$scope][] = $files;
        }
    }
    return;
}

/**
 * Adds CSS Files in ClipBucket template
 * 
 * @link http://docs.clip-bucket.com/user-manual/developers-guide/functions/functions_templates/add_css
 * @since 2.6
 * @param STRING $file CSS FILE
 * @param STRING $scope File Scope, read more about scope on http://docs.clip-bucket.com/ 
 */
function add_css($files, $scope)
{
    global $Cbucket;
    if ($files)
    {
        if (is_array($scope))
        {
            foreach ($scope as $sc)
            {
                if (is_array($files))
                {
                    foreach ($files as $file)
                    {
                        $Cbucket->CSSArray[$sc][] = $file;
                    }
                }
                else
                {
                    $Cbucket->CSSArray[$sc][] = $files;
                }
            }
        }
        else
        {
            if (is_array($files))
            {
                foreach ($files as $file)
                {
                    $Cbucket->CSSArray[$scope][] = $file;
                }
            }
            else
            {
                $Cbucket->CSSArray[$scope][] = $files;
            }
        }
    }
    return;
}

/**
 * Function add_header()
 * this will be used to add new files in header array
 * this is basically for plugins
 * for specific page array('page'=>'file') 
 * ie array('uploadactive'=>'datepicker.js')
 */
function add_header($files)
{
    global $Cbucket;
    return $Cbucket->add_header($files);
}

function add_admin_header($files)
{
    global $Cbucket;
    return $Cbucket->add_admin_header($files);
}

/**
 * Function used to show sharing form
 */
function show_share_form($array)
{

    assign('params', $array);
    Template('blocks/share_form.html');
}

/**
 * Function used to show flag form
 */
function show_flag_form($array)
{
    assign('params', $array);
    Template('blocks/flag_form.html');
}

/**
 * Function used to show flag form
 */
function show_playlist_form($array)
{
    global $cbvid;
    assign('params', $array);

    $playlists = $cbvid->action->get_playlists();
    assign('playlists', $playlists);

    Template('blocks/playlist_form.html');
}

/**
 * Function used to show collection form
 */
function show_collection_form($params)
{
    global $db, $cbcollection;
    if (!userid())
        $loggedIn = "not";
    else
    {
        $collectArray = array("order" => " collection_name ASC", "type" => "videos", "user" => userid());
        $collections = $cbcollection->get_collections($collectArray);

        assign("collections", $collections);
    }
    Template("/blocks/collection_form.html");
}

/**
 * Function used to check weather tempalte file exists or not
 * input path to file
 */
function template_file_exists($file, $dir)
{
    if (!file_exists($dir . '/' . $file) && !empty($file) && !file_exists($file))
    {
        echo sprintf(lang("temp_file_load_err"), $file, $dir);
        return false;
    }else
        return true;
}

/**
 * Category Link is used to return
 * Category based link
 */
function category_link($data, $type)
{
    switch ($type)
    {
        case 'video':case 'videos':case 'v':
            {


                if (SEO == 'yes')
                    return BASEURL . '/videos/' . $data['category_id'] . '/' . SEO($data['category_name']) . '/' . $_GET['sort'] . '/' . $_GET['time'] . '/';
                else
                    return BASEURL . '/videos.php?cat=' . $data['category_id'] . '&sort=' . $_GET['sort'] . '&time=' . $_GET['time'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;

        case 'channels':case 'channel':case'c':case'user':
            {

                if (SEO == 'yes')
                    return BASEURL . '/channels/' . $data['category_id'] . '/' . SEO($data['category_name']) . '/' . $_GET['sort'] . '/' . $_GET['time'] . '/';
                else
                    return BASEURL . '/channels.php?cat=' . $data['category_id'] . '&sort=' . $_GET['sort'] . '&time=' . $_GET['time'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;

        default:
            {

                if (THIS_PAGE == 'photos')
                    $type = 'photos';

                if (defined("IN_MODULE"))
                {
                    $url = 'cat=' . $data['category_id'] . '&sort=' . $_GET['sort'] . '&time=' . $_GET['time'] . '&page=' . $_GET['page'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
                    global $prefix_catlink;
                    $url = $prefix_catlink . $url;

                    $rm_array = array("cat", "sort", "time", "page", "seo_cat_name");
                    $p = "";
                    if ($prefix_catlink)
                        $rm_array[] = 'p';
                    $plugURL = queryString($url, $rm_array);
                    return $plugURL;
                }

                if (SEO == 'yes')
                    return BASEURL . '/' . $type . '/' . $data['category_id'] . '/' . SEO($data['category_name']) . '/' . $_GET['sort'] . '/' . $_GET['time'] . '/';
                else
                    return BASEURL . '/' . $type . '.php?cat=' . $data['category_id'] . '&sort=' . $_GET['sort'] . '&time=' . $_GET['time'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;
    }
}

/**
 * Sorting Links is used to return
 * Sorting based link
 */
function sort_link($sort, $mode = 'sort', $type)
{
    switch ($type)
    {
        case 'video':
        case 'videos':
        case 'v':
            {
                if (!isset($_GET['cat']))
                    $_GET['cat'] = 'all';
                if (!isset($_GET['time']))
                    $_GET['time'] = 'all_time';
                if (!isset($_GET['sort']))
                    $_GET['sort'] = 'most_recent';
                if (!isset($_GET['page']))
                    $_GET['page'] = 1;
                if (!isset($_GET['seo_cat_name']))
                    $_GET['seo_cat_name'] = 'All';

                if ($mode == 'sort')
                    $sorting = $sort;
                else
                    $sorting = $_GET['sort'];
                if ($mode == 'time')
                    $time = $sort;
                else
                    $time = $_GET['time'];

                if (SEO == 'yes')
                    return BASEURL . '/videos/' . $_GET['cat'] . '/' . $_GET['seo_cat_name'] . '/' . $sorting . '/' . $time . '/' . $_GET['page'];
                else
                    return BASEURL . '/videos.php?cat=' . $_GET['cat'] . '&sort=' . $sorting . '&time=' . $time . '&page=' . $_GET['page'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;

        case 'channels':
        case 'channel':
            {
                if (!isset($_GET['cat']))
                    $_GET['cat'] = 'all';
                if (!isset($_GET['time']))
                    $_GET['time'] = 'all_time';
                if (!isset($_GET['sort']))
                    $_GET['sort'] = 'most_recent';
                if (!isset($_GET['page']))
                    $_GET['page'] = 1;
                if (!isset($_GET['seo_cat_name']))
                    $_GET['seo_cat_name'] = 'All';

                if ($mode == 'sort')
                    $sorting = $sort;
                else
                    $sorting = $_GET['sort'];
                if ($mode == 'time')
                    $time = $sort;
                else
                    $time = $_GET['time'];

                if (SEO == 'yes')
                    return BASEURL . '/channels/' . $_GET['cat'] . '/' . $_GET['seo_cat_name'] . '/' . $sorting . '/' . $time . '/' . $_GET['page'];
                else
                    return BASEURL . '/channels.php?cat=' . $_GET['cat'] . '&sort=' . $sorting . '&time=' . $time . '&page=' . $_GET['page'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;


        default:
            {
                if (!isset($_GET['cat']))
                    $_GET['cat'] = 'all';
                if (!isset($_GET['time']))
                    $_GET['time'] = 'all_time';
                if (!isset($_GET['sort']))
                    $_GET['sort'] = 'most_recent';
                if (!isset($_GET['page']))
                    $_GET['page'] = 1;
                if (!isset($_GET['seo_cat_name']))
                    $_GET['seo_cat_name'] = 'All';

                if ($mode == 'sort')
                    $sorting = $sort;
                else
                    $sorting = $_GET['sort'];
                if ($mode == 'time')
                    $time = $sort;
                else
                    $time = $_GET['time'];

                if (THIS_PAGE == 'photos')
                    $type = 'photos';

                if (defined("IN_MODULE"))
                {
                    $url = 'cat=' . $_GET['cat'] . '&sort=' . $sorting . '&time=' . $time . '&page=' . $_GET['page'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
                    $plugURL = queryString($url, array("cat", "sort", "time", "page", "seo_cat_name"));
                    return $plugURL;
                }

                if (SEO == 'yes')
                    return BASEURL . '/' . $type . '/' . $_GET['cat'] . '/' . $_GET['seo_cat_name'] . '/' . $sorting . '/' . $time . '/' . $_GET['page'];
                else
                    return BASEURL . '/' . $type . '.php?cat=' . $_GET['cat'] . '&sort=' . $sorting . '&time=' . $time . '&page=' . $_GET['page'] . '&seo_cat_name=' . $_GET['seo_cat_name'];
            }
            break;
    }
}

/**
 * function used to call clipbucket footers
 */
function footer()
{
    $funcs = get_functions('clipbucket_footer');

    if (is_array($funcs) && count($funcs) > 0)
    {
        foreach ($funcs as $func)
        {
            if (function_exists($func))
            {
                $func();
            }
        }
    }
}

/**
 * FUnction used to get head menu
 */
function head_menu($params = NULL)
{
    global $Cbucket;
    return $Cbucket->head_menu($params);
}

/**
 * This function returns the provided menu. If no name is passed
 * navigation menu will load automatically.
 * 
 * @global object $Cbucket
 * @param array $params
 * @return string 
 */
function cbMenu($params = NULL)
{
    global $Cbucket;
    $name = $params['name'];
    if (!$name)
    {
        $name = 'navigation';
    }

    $menu = get_menu($name);
    $params['show_icons'] = $params['show_icons'] ? $params['show_icons'] : 'yes';
    if ($menu)
    {

        foreach ($menu as $item)
        {
            $continue = true;
            if ($item['section'] && !isSectionEnabled($item['section']))
            {
                $continue = false;
            }

            if ($continue == true)
            {
                $selected = current_page(array('page' => $item['section']));
                $icon = '';
                $output .= '<li';
                $output .= " id='" . SEO(strtolower($name)) . "-" . $item['id'] . "' ";
                $classes = $params['class'] ? $params['class'] : '';
                if ($selected)
                {
                    $classes .= ' active';
                }

                $output .= " class='$classes'  ";
                if ($item['icon'] && $params['show_icons'] == 'yes')
                {
                    $icon = "<i class='" . $item['icon'] . "'></i> ";
                }
                $output .= "" . $params['extra_params'] ? $params['extra_params'] : '' . ">";
                $output .= "<a href='" . $item['link'] . "' target='" . $item['target'] . "'>" . $icon . $item['title'] . "</a>";
                $output .= "</li>";
            }
        }

        if ($params['assign'])
        {
            assign($params['assign'], $output);
        }
        else
        {
            return $output;
        }
    }
    //pr( $menu , true );
    //return $Cbucket->cbMenu($params);
}

/**
 * FUnction used to get foot menu
 */
function foot_menu($params = NULL)
{
    global $Cbucket;
    return $Cbucket->foot_menu($params);
}

/**
 * This function used to include headers in <head> tag
 * it will check weather to include the file or not
 * it will take file and its type as an array
 * then compare its type with THIS_PAGE constant
 * if header has TYPE of THIS_PAGE then it will be inlucded
 */
function include_header($params)
{
    $file = $params['file'];
    $type = $params['type'];

    if ($file == 'global_header')
    {
        Template(BASEDIR . '/styles/global/head.html', false);
        return false;
    }

    if ($file == 'admin_bar')
    {
        if (has_access('admin_access', TRUE))
            Template(BASEDIR . '/styles/global/admin_bar.html', false);
        return false;
    }

    if (!$type)
        $type = "global";

    if (is_includeable($type))
        Template($file, false);

    return false;
}

/**
 * Function used to check weather to include
 * given file or not
 * it will take array of pages
 * if array has ACTIVE PAGE or has GLOBAL value
 * it will return true
 * otherwise FALSE
 */
function is_includeable($array)
{
    if (!is_array($array))
        $array = array($array);
    if (in_array(THIS_PAGE, $array) || in_array('global', $array))
    {
        return true;
    }else
        return false;
}

/**
 * This function works the same way as include_header
 * but the only difference is , it is used to include
 * JS files only
 * @deprecated v3.0
 */
$the_js_files = array();

function include_js($params)
{
    global $the_js_files;

    $file = $params['file'];
    $type = $params['type'];

    if (!in_array($file, $the_js_files))
    {
        $the_js_files[] = $file;
        if ($type == 'global')
            return '<script src="' . JS_URL . '/' . $file . '" type="text/javascript"></script>';
        elseif (is_array($type))
        {
            foreach ($type as $t)
            {
                if ($t == THIS_PAGE)
                    return '<script src="' . JS_URL . '/' . $file . '" type="text/javascript"></script>';
            }
        }elseif ($type == THIS_PAGE)
            return '<script src="' . JS_URL . '/' . $file . '" type="text/javascript"></script>';
    }

    return false;
}

/**
 * function used to get theme options
 * @todo Write documentation
 */
function theme_config($name)
{
    global $Cbucket;

    if ($Cbucket->theme_configs)
        $theme_configs = $Cbucket->theme_configs;
    else
        $theme_configs = theme_configs();

    $value = $value[$name];
}

/**
 * Get them configurations
 * @global type $Cbucket
 * @return type
 */
function theme_configs()
{
    global $Cbucket;

    $value = config($Cbucket->template . '-options');
    $value = json_decode($value, true);
    $value = $value['options'];

    return $value;
}

/**
 * add link in admin area left menu
 *
 * Function used to add items in admin menu
 * This function will insert new item in admin menu
 * under given header, if the header is not available 
 * it will create one, ( Header means titles ie 'Plugins' 'Videos' etc)
 * http://docs.clip-bucket.com/add_admin_menu-function for reference
 *
 * @todo Write documentation
 */
function add_admin_menu($params, $name = false, $link = false, $plug_folder = false, $is_player_file = false)
{
    global $Cbucket;

    if (!is_array($params))
    {
        $params = _add_admin_menu($params, $name, $link, $plug_folder, $is_player_file);
        add_admin_sub_menu($params);
        return true;
    }

    $defaults = array(
        'title' => lang('Settings'),
        'id' => 'settings',
        'icon' => 'icon-gauge',
        'access' => 'admin-access'
    );

    $params = array_merge($defaults, $params);

    return $Cbucket->AdminMenu[$params['id']] = $params;
}

/**
 * add multiple admin menus
 */
function add_admin_menus($menus)
{
    if (is_array($menus))
    {
        foreach ($menus as $menu)
            add_admin_menu($menu);
    }
}

/**
 * @todo write documentation
 */
function add_admin_sub_menu($params)
{
    global $Cbucket;
    $defaults = array(
        'parent_id' => 'tool-box',
        'access' => 'admin_access',
    );

    $params = array_merge($defaults, $params);

    if ($params['title'])
    {
        $id = $params['id'];
        if (!$id)
            $id = SEO($params['title']);

        $menu = array(
            'id' => $id,
            'parent_id' => $params['parent_id'],
            'access' => $params['access'],
            'title' => $params['title'],
            'link' => $params['link'],
            'icon' => $params['icon'],
        );

        if ($Cbucket->AdminMenu[$params['parent_id']])
        {
            $Cbucket->AdminMenu[$params['parent_id']]['sub_menu'][] = $menu;
        }
        else
        {
            //Add menu to misc menu
            $Cbucket->AdminMenu['miscellaneous']['sub_menu'][] = $menu;
        }
    }
}

function add_admin_sub_menus($params)
{
    if (is_array($params))
    {
        foreach ($params as $parent => $child)
        {

            foreach ($child as $ch)
            {
                $ch['parent_id'] = $parent;
                add_admin_sub_menu($ch);
            }
        }
    }
}

function _add_admin_menu($header = 'Tool Box', $name = false, $link = false, $plug_folder = false, $is_player_file = false)
{
    global $Cbucket;

    //Get Menu
    $menu = $Cbucket->AdminMenu;

    if ($plug_folder)
        $link = 'plugin.php?folder=' . $plug_folder . '&file=' . $link;
    if ($is_player_file)
        $link .= '&player=true';

    //Add New Menu
    $menu[$header][$name] = $link;

    //Add sub menu function here...

    $params = array(
        'title' => $name,
        'parent_id' => SEO($header),
        'id' => SEO('title'),
        'link' => $link
    );

    return $params;
}

/**
 * get admin menu
 * 
 * @todo Write documentation
 */
function get_admin_menu()
{
    global $Cbucket;

    $array = $Cbucket->AdminMenu;

    //Apply Filters
    $array = apply_filters($array, 'admin_menu');

    return $array;
}

/**
 * get list of icons in category-icons folder
 */
function get_category_icons()
{
    //Check if there is a folder
    //template for category icons
    if (file_exists(FRONT_TEMPLATEDIR . '/category-icons'))
    {
        $dir = FRONT_TEMPLATEDIR . '/category-icons';
        $dir_url = FRONT_TEMPLATEURL . '/category-icons';
    }
    else
    {
        $dir = BASEDIR . '/images/category-icons';
        $dir_url = BASEURL . '/images/category-icons';
    }


    //Blank list of images
    $images = array();

    if (file_exists($dir))
    {
        //Only get PNGs
        $imgList = glob($dir . '/*.png');

        if ($imgList)
        {
            foreach ($imgList as $img)
            {
                list($width, $height, $type, $attr) = getimagesize($img);
                if ($width && $height)
                    $images[] = $img;
            }
        }
    }

    $final_images = array();
    if ($images)
    {
        foreach ($images as $image)
        {
            $imagearr = explode('/', $image);
            $imageName = $imagearr[count($imagearr) - 1];

            $final_images[$imageName] = array('url' => $dir_url . '/' . $imageName,
                'path' => $dir . '/' . $imageName);
        }
    }

    return $final_images;
}

/**
 * Loading Pointer
 * 
 * Displays a loading image with the given ID
 * we need this pointer on many places to let user know if the
 * process is finised or not to improve UI
 * 
 * @param ID String
 * @return Image wraped in img tag with ID and hidden by default 
 */
function loading_pointer($params)
{
    $id = $params['place'] ? $params['place'] : $params['id'];

    $img = TEMPLATEURL . '/images/loaders/1.gif';

    return '<img src="' . $img . '" id="' . $id . '-loader" class="loading_pointer ' . $params['class'] . '">';
}

/**
 * Shortify Numbers
 * 
 * display large numbers in short forms by adding K
 * and triming the rest
 * 100,000 => 100K 105,2345 => 105.2K 
 * 
 * @param INT $numbers
 * @return STRING $shortened
 */
function shortify($numbers)
{
    if (is_numeric($numbers))
    {
        if ($numbers > 1000)
        {
            $new = round($numbers / 1000, 1);
            return $new . 'K';
        }
    }
}

/**
 * Displays the rating in the template in an ajax request 
 * @todo Write Documentation
 * 
 * filters isliye lagay hain take array main radobadal ki ja skay
 * cb_call_functions baad main issy array ko istemal kr k rating
 * show krwa dega, is k liye pehle cb_register_function krwana
 * parre ga.
 * 
 * return isliye kuch nhin krwaya kion k cb_call_funcion b kuch return
 * nhin kr ra hai wo ilsye k ye content ko format nhin krta
 * balke jitne registered functions hote hain unko call krta aur bich
 * me hi echo hota
 */
function showRating($rating)
{
    $rating = apply_filters($rating, 'show-rating');
    cb_call_functions('show_rating', $rating);
}

function get_menu($name)
{
    global $Cbucket;
    $menu = $Cbucket->menus[$name];

    if ($menu)
    {
        $menu = apply_filters($menu, 'filter_menu');
        return $menu;
    }
    else
    {
        return false;
    }
}

function add_menu($name, $items = null)
{
    global $Cbucket;
    if (!get_menu($name) && !is_null($items) && is_array($items))
    {
        $Cbucket->menus[$name] = array();
        add_menu_items($name, $items);
    }
}

function add_menu_items($name, $items)
{
    if (is_array($items))
    {
        foreach ($items as $item)
        {
            add_menu_item($name, $item);
        }
    }
}

/**
 * This adds a new item in provided menu.
 * @param $name STRING, Name of the menu in which item will be added
 * @param $item STRING|ARRAY, If a string is provided it will be considered as item title
 * it should be an array
 * @param $link STRING, HTTP URL for provided title
 * @param $section STRING, Name of the section
 * @param $icon STRING, Add class name for icon. http://twitter.github.com/bootstrap/base-css.html#icon
 * contains list of all icons that you can use
 * @param $id STRING, Unique id for this item
 * @param $target STRING, Set a target for current item
 */
function add_menu_item($name, $item, $link = false, $section = false, $icon = false, $id = false, $target = '_self')
{
    global $Cbucket;
    if (!is_array($item))
    {
        $item =
                array(
                    'title' => $item,
                    'link' => $link,
                    'icon' => $icon,
                    'target' => $target,
                    'id' => $id,
                    'section' => $section);
    }
    $item['id'] = $item['id'] ? $item['id'] : SEO(strtolower($item['title']));
    $Cbucket->menus[$name][$item['id']] = $item;
}

/* * *
 *  fetch template files as defined int he template config
 *  @param file
 *  @param type , display | fetch
 */

function get_template($file, $type = 'fetch', $layout = true)
{
    $defaults = array(
        'single_comment' => 'blocks/comments/comment.html',
        'comments' => 'blocks/comments/comments.html',
        'pagination' => 'blocks/pagination.html',
        'notification_block' => 'blocks/notifications/notification_block.html',
        'notifications' => 'blocks/notifications/notifications.html',
        'msgs_notifications' => 'blocks/pm/notifications.html',
        'msgs_notifications_block' => 'blocks/pm/notification_block.html',
        'single_message'=>'blocks/pm/message.html',
        'friends_notifications' => 'blocks/contacts/notifications.html',
        'friends_notifications_block' => 'blocks/contacts/notification_block.html',
        'topics' => 'blocks/groups/topics.html',
        'share_feed_block' => 'blocks/feed_share_block.html',
        'single_feed' => 'blocks/single_feed.html',
        'single_topic' => 'blocks/groups/topic.html',
        'group_topic' => 'blocks/groups/group_topic.html',
        'group_video' => 'blocks/groups/video.html'
    );


    $files = config('template_files');

    if ($files[$file])
    {
        $the_file = $files[$file];
    }
    else
    {
        $the_file = $defaults[$file];
    }

    if ($the_file)
    {

        if ($type == 'fetch')
            return fetch($the_file);
        if ($type == 'path')
        {
            $path = $the_file;
            if ($layout)
                $path = LAYOUT . '/' . $the_file;

            return $path;
        }else
            template($the_file);
    }
}

/**
 * Loads all javascript files, previous function include_js is now
 * deprecated, all js files that are added using add_js function will be
 * loadded this simple function
 * 
 * @return js files wrappded in script tag
 */
function cb_load_js()
{
    global $Cbucket;
    $js_array = $Cbucket->JSArray;

    $js_array = apply_filters($js_array, 'js_array');

    if (is_array($js_array))
    {
        foreach ($js_array as $scope => $js_files)
        {

            if ((defined('THIS_PAGE') && $scope == THIS_PAGE) OR
                    $scope == 'global' || !defined('THIS_PAGE'))
            {
                foreach ($js_files as $file)
                {
                    if (!strstr($file, 'http'))
                    {
                        $file = JS_URL . '/' . $file;
                    }

                    echo '<script src="' . $file . '" type="text/javascript"></script>';
                    echo "\n";
                }
            }
        }
    }
}

/**
 * Function loads ClipBucket CSS Files that are added using add_css function
 * either in a plugin or a template file.
 * 
 * @return CSS files...
 */
function cb_load_css()
{

    global $Cbucket;
    $css_array = $Cbucket->CSSArray;

    $css_array = apply_filters($css_array, '$css_array');

    if (is_array($css_array))
    {
        foreach ($css_array as $scope => $css_files)
        {

            if ((defined('THIS_PAGE') && $scope == THIS_PAGE) OR
                    $scope == 'global' || !defined('THIS_PAGE'))
            {
                foreach ($css_files as $file)
                {
                    echo '<link rel="stylesheet" type="text/css" href="' . $file . '" /> ';
                    echo "\n";
                }
            }
        }
    }
}

/**
 * Source: http://www.barattalo.it/2010/02/02/recursive-remove-directory-rmdir-in-php/
 * @param string $path
 */
function rmdir_recurse($path)
{
    $path = rtrim($path, '/') . '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle)))
    {
        if ($file != '.' and $file != '..')
        {
            $fullpath = $path . $file;
            if ( is_dir($fullpath) ) {
                rmdir_recurse($fullpath); 
            } else {
                unlink($fullpath);
            }
        }
    }
    closedir($handle);
    rmdir($path);
}

/**
 * Get manager order for provided $type
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @todo Add alias function for different objects
 * @global OBJECT $Cbucket
 * @param STRING $type
 * @return MIX
 */
function object_manager_orders($type = 'video')
{
    global $Cbucket;
    $orders = $Cbucket->manager_orders[$type];
    if ($orders)
    {
        $orders = apply_filters($orders, 'manager_orders');
        return $orders;
    }

    return false;
}

/**
 * Adds a new order for object
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @todo Add alias function for different objects
 * @global OBJECT $Cbucket
 * @param STRING $title
 * @param STRING $order
 * @param STRING $type
 * @param STRING $id
 * @return MIX
 */
function add_object_manager_order($title, $order, $type = 'video')
{
    global $Cbucket;

    if (!$title || !$order || !$type)
    {
        return false;
    }

    $order_array = array(
        'title' => $title,
        'order' => $order,
        'id' => $type . '-' . SEO(strtolower($title)) . '-' . time()
    );

    $Cbucket->manager_orders[trim($type)][] = $order_array;
    return $Cbucket->manager_orders;
}

/**
 * Displays the current order title 
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @todo Add alias function for different objects
 * @param STRING $type
 * @return MIX
 */
function current_object_order($type = 'video')
{
    $current = $_GET['omo'] ? mysql_clean($_GET['omo']) : (int) 0;
    $orders = object_manager_orders($type);

    if (!$orders[$current])
    {
        $current = 0;
    }

    if ($orders[$current])
    {
        return $orders[$current]['title'];
    }

    return false;
}

/**
 * Displays the list of orders for current object. You have option to only
 * display unselected orders excluding the current order. Set $display to
 * 'all' to add all orders, adding CSS .active class to current one
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @todo Add alias function for different objects
 * @param STRING $type
 * @param STRING $display
 * @return MIX
 */
function display_manager_orders($type = 'video', $display = 'unselected')
{
    $orders = object_manager_orders($type);
    $current_order = $_GET['omo'] ? mysql_clean($_GET['omo']) : (int) 0;

    if (!$orders[$current_order])
    {
        $current_order = 0;
    }

    $total_order = count($orders);
    if ($_SERVER['QUERY_STRING'])
    {
        $query_string = queryString(null, 'omo');
    }

    if ($orders)
    {
        foreach ($orders as $key => $order)
        {
            if ($key == $current_order && $display == 'unselected' && $total_order >= 2)
            {
                continue; // skip the selected one
            }

            $active = '';

            if ($key == $current_order)
            {
                $active = ' class="active"';
            }
            $output .= '<li' . $active . '>';
            $output .= '<a href="' . ($query_string ? $query_string : '?') . 'omo=' . $key . '" id="' . $order['id'] . '" data-order="' . $key . '" data-type="' . $type . '">' . $order['title'] . '</a>';
            $output .= '</li>';
        }

        return $output;
    }

    return false;
}

/**
 * This function returns mySQL for given type
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $type
 * @return string
 */
function return_object_order( $type = null ) {
    if ( is_null( $type) ) {
        return false;
    }

    $orders = object_manager_orders($type);
    if ($orders)
    {
        $current_order = $_GET['omo'] ? mysql_clean($_GET['omo']) : (int) 0;
        if (!$orders[$current_order])
        {
            $current_order = 0;
        }

        if ($orders[$current_order]['order'])
        {
            return $orders[$current_order]['order'];
        }
    }
    return false;
}

/**
 * Get fileds that you should be enough for end user
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $extra
 * @return array
 */
function get_template_fields( $extra = null ) {
    $fields = array( 'name','author','version','released','website','dir' );
    if ( is_null( $extra ) and is_array( $extra ) ) {
        $fields = array_merge( $fields, $extra );
    }
    
    return $fields;
}

/**
 * Display template changer for users if it is
 * allowed by administrator
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global $cbtpl;
 */
function display_template_changer() {
    if ( ALLOW_STYLE_SELECT ) {
        global $cbtpl;

        $templates = $cbtpl->get_templates( true );

        // Arrange templates according to name
        // A - Z
        if ( $templates ) {
            ksort( $templates );
            $list = '';
            
            $fields = get_template_fields();
            $active_template = get_active_template();
            
            foreach( $templates as $template ) {
                // Only get commonly used fields
                foreach( $fields as $field ) {
                    if ( $template[ $field ] ) {
                        $tem[ $field ] = $template[ $field ];
                    }
                }

                $tem = apply_filters( $tem, 'template_selection' );    
                if ( !$tem['name'] or !$template['dir'] ) {
                    continue;
                }
                
                $params_item['file'] = 'blocks/template_changer/item.html';
                $params_item['template'] = $tem;
                
                $active = ( $active_template == $template['dir'] ) ? ' active' : '';
                $list .= '<li class="template-item'.$active.'" id="template-'.$template['dir'].'" data-template="'.$template['dir'].'">';
                $list .= '<a href="'.queryString( 'set_the_template='.$template['dir'].'', array('set_the_template') ).'">';
                $list .= fetch_template_file( $params_item );
                $list .= '</a>';
                $list .= '</li>';
            }
        }
                
        $params['file'] = 'blocks/template_changer/template_changer.html';
        $params['templates_list'] = $list;
        
        return fetch_template_file( $params );
    }
    
    return false;
}

/**
 * This filter template details that should be enough
 * for user
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $details
 * @return array
 */
function get_template_info_for_user( $details ) {
    if ( $details ) {
        $fields = get_template_fields();
        
        foreach( $fields as $field ) {
            if ( $details[ $field ] ) {
                $to_user[ $field ] = $details[ $field ];
            }
        }
        
        if ( $to_user ) {
            return $to_user;
        } else {
            return false;
        }
        
    }
}

/**
 * Get current template
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global type $Cbucket
 * @return string
 */
function get_active_template() {
    global $Cbucket;
    return $Cbucket->template;
}

/**
 * Get current template details
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global type $Cbucket
 * @return type
 */
function get_active_template_details() {
    global $Cbucket;
    $details = get_template_info_for_user( $Cbucket->template_details );
    return $details;
}

/**
 * Get template name
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $tem
 * @return string
 */
function get_template_name( $tem ) {
    return get_template_detail( $tem, 'name' );
}

/**
 * Get given detail of given template
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $cbtpl
 * @param array $tem
 * @param string $detail
 * @return string
 */
function get_template_detail( $tem, $detail = 'name' ) {
    global $cbtpl;
    $details = $cbtpl->get_template_details( $tem );
    
    if ( $details ) {
        $to_user_details = get_template_info_for_user( $details );
        if ( $to_user_details and $to_user_details[ $detail ] ) {
            return $to_user_details[ $detail ];
        }
    }
}

/**
 * Get name of active template
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @return string
 */
function get_active_template_name() {
    $active = get_active_template();
    return get_template_name( $active );
}

/**
 * Function confirms that user can change template or not
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @return boolean
 */
function can_change_template() {
    $is_allowed = ALLOW_STYLE_SELECT;
    
    if ( !$is_allowed ) {
        if ( has_access('admin_access') ) {
            $can_change =  true;
        } else {
            $can_change = false;
        }
    }
    
    $hidden = get_hidden_templates();

    if ( $hidden ) {
        $the_template = mysql_clean( $_GET['set_the_template'] );
        if ( in_array( $the_template, $hidden ) ) {
            if ( has_access('admin_access') ) {
                $can_change =  true;
            } else {
                $can_change = false;
            }
        } else {
            $can_change = true;
        }
    }

    return $can_change;
}

/**
 * Function gets the list of hidden tempaltes.
 * $details can be set to true, if we want there details
 * as-well
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param boolean $details
 * @return boolean
 */
function get_hidden_templates( $details = false ) {
    $hidden = config('hidden_templates');
    if ( $hidden ) {
        $hidden = json_decode( $hidden, true );
        if ( $details == true ) {
            $hidden_details = array();
            foreach( $hidden as $tpl_dir ) {
                $tpl_details = CBTemplate::get_template_details( $tpl_dir );
                if( $tpl_details && $tpl_details['name'] != '' ) {
				$hidden_details[$tpl_details['name']] = $tpl_details;
                }
            }
            
            $hidden = ( count( $hidden_details ) > 0 ) ? $hidden_details : false;
        }
        
        return $hidden;
    }
    
    return false;
}

/**
 * Checks whether template is hidden or not
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param string $template
 * @return boolean
 */
function is_template_hidden( $template ) {
    $hidden = get_hidden_templates();
    if ( $hidden ) {
        if ( in_array( $template, $hidden ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Function hides the template from end user
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $cbtpl
 * @param string $dir
 */
function hide_the_template( $dir ) {
    global $cbtpl;
    
    $details = $cbtpl->get_template_details( $dir );
    if ( $details ) {
        $hidden = get_hidden_templates();
        if ( $hidden and in_array( $details['dir'], $hidden ) ) {
            return;
        }

        $hidden[] = $details['dir'];
        config( 'hidden_templates', json_encode( $hidden ) );
        e( lang( $details['name']." is now hidden from user." ), "m" );

    } else {
        e( lang('This template is not Clipbucket compatible or template does not exist') );
    }
}

/**
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @global object $cbtpl
 * @param type $dir
 */
function show_the_template ( $dir ) {
    global $cbtpl;
    
    $details = $cbtpl->get_template_details( $dir );
    if ( $details ) {
        $hidden = get_hidden_templates();
        if ( $hidden ) {
            $tpl_index = array_search( $details['dir'], $hidden );
            if ( $hidden[ $tpl_index ] ) {
                unset( $hidden[ $tpl_index ] );
                config( 'hidden_templates', json_encode( $hidden ) );
                e( lang( $details['name']." is now visible to user." ), "m" );
            }
        }
        
        return;
    } else {
        e( lang('This template is not Clipbucket compatible or template does not exist') );
    }
}

/**
 * Function uploads a new theme
 * 
 * @author Fawaz Tahir <fawaz.cb@gmail.com>
 * @param array $theme_file
 * @return array $messages
 */
function upload_new_theme ( $theme_file ) {
    global $cbtpl;
    
    $messages = array();
    $back_link = "<a href='templates.php'>Please go back</a>";
    
    if ( $theme_file ) {
        $name = $theme_file['name'];
        $extension = strtolower( end( explode(".", $name) ) );
        $messages[] = "<i class='icon-info-sign'></i> Confirming file extension ...";
        
        if ( "zip" != ( $extension ) ) {
            $messages[] = "<strong>Error</strong>: Unknown format provided. Only <code>ZIP</code> file is allowed. ".$back_link;
        } else {
            $messages[] = "<i class='icon-ok-sign'></i> Extension confirmed ...";
            
            {                
                // extracting can take a lot of memeory
                ini_set('memory_limit', '256M');
                
                include( 'classes/pclzip.class.php' );
                $arc = new PclZip( $theme_file['tmp_name'] );
                
                $zip_files = $arc->listContent();
                $template_name = $zip_files[0]['filename'];
                $template_name = rtrim( $template_name, "/" );
                
                if ( file_exists( STYLES_DIR."/".$template_name ) ) {
                    $messages[] = "<i class='icon-remove-sign'></i> <strong>Error:</strong> Can not upload template. It already exists. ".$back_link;
                } else {
                    $messages[] = "<i class='icon-info-sign'></i> Unpacking file ...";
                    
                    if ( $arc->extract( PCLZIP_OPT_PATH, STYLES_DIR  ) ) {
                        $messages[] = "<i class='icon-ok-sign'></i> File successfully unpacked ...";
                        $messages[] = "<i class='icon-info-sign'></i> Checking required files and folders ... ";
                        $remove_template = false;
                        $theme_dir = STYLES_DIR."/".$template_name;
                                                
                        if ( !file_exists( $theme_dir."/template.xml" ) ) {
                            $messages[] = "<i class='icon-remove-sign'></i> <strong>Error</strong>: Can not upload template. <code>template.xml</code> is missing. ".$back_link;
                            $remove_template = true;
                        } else if ( !file_exists( $theme_dir."/images" ) or !file_exists( $theme_dir."/layout" ) or !file_exists( $theme_dir."/theme" ) ) {
                            $messages[] = "<i class='icon-remove-sign'></i> <strong>Error</strong>: Can not upload template. <code>layout</code>, <code>theme</code> and <code>images</code> folders are required. Some are missing. ".$back_link;
                            $remove_template = true;
                        } else {
                            $messages[] = "<i class='icon-ok-sign'></i> Required files and folders are present ...";
                            $messages[] = "<i class='icon-info-sign'></i> Getting template details ... ";
                            $details = $cbtpl->get_template_details( $template_name );
                            if ( $details ) {
                                $messages[] = "<i class='icon-ok-sign'></i> <strong>".$details['name']."</strong> has been successfully added to your available templates list. <a href='templates.php?change=".$details['dir']."'>Activate template</a> or <a href='templates.php'>go back</a>";
                            } else {
                                $messages[] = "<i class='icon-remove-sign'></i> <strong>Error:</strong> Can not upload template. Unable to find template details. ".$back_link;
                                $remove_template = true;
                            }
                        }
                    } else {
                        $messages[] = "<i class='icon-remove-sign'></i> <strong>Error:</strong> ".$arc->errorInfo( true ).". ".$back_link;
                    }  
                }  
            }
        }
    } else {
        $messages[] = "<strong>Error</strong>: No theme file was selected. ".$back_link;
    }
    
    if ( file_exists( $theme_file['tmp_name'] ) ) {
        unlink( $theme_file['tmp_name'] );
    }
    
    if ( $remove_template === true ) {
        if ( is_dir( $theme_dir ) ) {
            rmdir_recurse( $theme_dir );
        } elseif ( is_file( $theme_dir ) ) {
            unlink( $theme_dir );
        }
    }
    
    return $messages;
}
?>