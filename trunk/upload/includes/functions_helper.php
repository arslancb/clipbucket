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