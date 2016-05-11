<?php

    /**
    * File: Functions Helper
    * Description: Functions written for making things simpler for developers
    * @author: Fawaz Tahir
    * @since: August 28th, 2013
    */

    /**
    * Pulls website configurations from the database
    * @param : { none } { handled inside function }
    * @return { array } { $data } { array with all configurations }
    */

    function get_website_configurations() {
        $query = "SELECT name, value FROM ".tbl('config');
        $results = select($query);
        $data = array();
        if ($results) {
            foreach($results as $config) {
                $data[$config[ 'name' ]] = $config['value'];
            }
        }
        return $data;
    }

    /**
    * Function used to get config value of ClipBucket
    * @uses: { class : Cbucket } { var : configs }
    */

    function config($input) {
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

    /**
    * Returns theme currently uploaded for your ClipBucket powered website
    * @param : { none }
    * @return : { array } { $conts } { an array with names of uploaded themes }
    * @since : March 16th, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    */

    function installed_themes() {
        $dir = BASEDIR.'/styles';
        $conts = scandir($dir);
        for ($i=0; $i < 3; $i++) { 
            unset($conts[$i]);
        }
        
        return $conts;
    }

    /**
    * Pulls categories without needing any paramters
    * making it easy to use in smarty. Decides type using page
    *
    * @return : { array } { $all_cats } { array with all details of all categories }
    * @since : March 22nd, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    */

    function pullCategories() {
        global $cbvid, $userquery, $cbphoto;
        $params = array();
        switch (THIS_PAGE) {
            case 'videos':
                $all_cats = $cbvid->cbCategories($params);
                break;
            case 'photos':
                $all_cats = $cbphoto->cbCategories($params);
                break;
            case 'channels':
                $all_cats = $userquery->cbCategories($params);
                break;
            
            default:
                $all_cats = $cbvid->cbCategories($params);
                break;
        }

        if (is_array($all_cats)) {
            return $all_cats;
        } else {
            return false;
        }
    }

    /**
    * Takes a number and returns more human friendly format of it e.g 1000 == 1K
    * @param : { integer } { $num } { number to convert to pretty number}
    * @return : { integer } { $kviews } { pretty number after processing }
    * @since : 24th March, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    */

    function prettyNum($num) {
        $prettyNum = preg_replace("/[^0-9\.]/", '', $num);
        if ($prettyNum >= 1000 && $prettyNum < 1000000) {
            $kviews = $prettyNum / 1000;
            if ($prettyNum > 1000) {
                $kviews = round($kviews,0);
            }
            $kviews = $kviews.' K'; // number is in thousands
        } elseif ($prettyNum >= 1000000 && $prettyNum < 1000000000) {
            $kviews = $prettyNum / 1000000;
            $kviews = round($kviews,2);
            $kviews = $kviews.' M'; // number is in millions
        } elseif ($prettyNum >= 1000000000) {
            $kviews = $prettyNum / 1000000000;
            $kviews = round($kviews,2);
            $kviews = $kviews.' B'; // number is in billions
        } elseif ($prettyNum < 1000) {
            return $prettyNum;
        }

        if (!empty($kviews)) {
            return $kviews;
        } else {
            return false;
        }
    }