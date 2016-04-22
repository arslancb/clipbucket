<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/28/13
 * Time: 12:17 PM
 * To change this template use File | Settings | File Templates.
 */


function get_website_configurations() {
    $query = "SELECT name, value FROM ".tbl( 'config' );
    $results = select( $query );
    $data = array();

    if ( $results ) {
        foreach( $results as $config ) {
            $data[ $config[ 'name' ] ] = $config[ 'value' ];
        }
    }

    return $data;
}

/**
 * Function used to get config value
 * of ClipBucket
 */
function config($input)
{
    global $Cbucket;
    return $Cbucket->configs[$input];
}
function get_config($input){ return config($input); }

/**
 * Function used to get player logo
 */
function website_logo()
{
    $logo_file = config('player_logo_file');
    if(file_exists(BASEDIR.'/images/'.$logo_file) && $logo_file)
        return BASEURL.'/images/'.$logo_file;

    return BASEURL.'/images/logo.png';
}

/**
 * createDataFolders()
 *
 * create date folders with respect to date. so that no folder gets overloaded
 * with number of files.
 *
 * @param string FOLDER, if set to null, sub-date-folders will be created in
 * all data folders
 * @return string
 */
function createDataFolders($headFolder = NULL, $custom_date = NULL)
{

    $time = time();

    if ($custom_date)
    {
        if(!is_numeric($custom_date))
            $time = strtotime($custom_date);
        else
            $time = $custom_date;
    }
        

    $year = date("Y", $time);
    $month = date("m", $time);
    $day = date("d", $time);
    $folder = $year . '/' . $month . '/' . $day;

    $data = cb_call_functions('dated_folder');
    if ($data)
        return $data;

    if (!$headFolder)
    {
        @mkdir(VIDEOS_DIR . '/' . $folder, 0777, true);
        @mkdir(THUMBS_DIR . '/' . $folder, 0777, true);
        @mkdir(ORIGINAL_DIR . '/' . $folder, 0777, true);
        @mkdir(PHOTOS_DIR . '/' . $folder, 0777, true);
        @mkdir(LOGS_DIR . '/' . $folder, 0777, true);
    }
    else
    {
        if (!file_exists($headFolder . '/' . $folder))
        {
            @mkdir($headFolder . '/' . $folder, 0777, true);
        }
    }

    $folder = apply_filters($folder, 'dated_folder');
    return $folder;
}

function create_dated_folder($headFolder = NULL, $custom_date = NULL)
{
    return createDataFolders($headFolder, $custom_date);
}

function cb_create_html_tag( $tag = 'p', $self_closing = false, $attrs = array(), $content = null ) {

    $open = '<'.$tag;
    $close = ( $self_closing === true ) ? '/>' : '>'.( !is_null( $content ) ? $content : '' ).'</'.$tag.'>';

    $attributes = '';

    if( is_array( $attrs ) and count( $attrs ) > 0 ) {

        foreach( $attrs as $attr => $value ) {

            if( strtolower( $attr ) == 'extra' ) {
                $attributes .= ( $value );
            } else {
                $attributes .= ' '.$attr.' = "'.$value.'" ';
            }

        }

    }

    return $open.$attributes.$close;
}