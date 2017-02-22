<?php
/*
Plugin Name: Logged Unlisted
Description: Add a new type of broadcast for video that is similar to "unlisted" but restricted to registered users
Author: Sylvain Tognola
Author Website: http://stognola.ovh
ClipBucket Version: 2
Version: 1.0
Plugin Type: global

Works with most HTML5 players 
you must call the anchor "after_watch_video"
*/

if(!function_exists('loggedunlisted_watch_video'))
{
  function loggedunlisted_watch_video($v){
    return has_access('admin_access',TRUE) || ($v['broadcast'] != "logunlist" || userid() != false);
  }

  function loggedunlisted_get_videos($vidarray){
    if( !has_access('admin_access',TRUE) )
      return ($vidarray["cond"]?$vidarray["cond"]." AND ":"")." video.broadcast != 'logunlist' ";
  
  }

  function loggedunlisted_load_option_fields($fields){
    $fields["broadcast"]["value"]["logunlist"] = "LoggedUnlisted - seul les utilisateurs enregistrés et qui ont le lien peuvent trouver la vidéo";
    return $fields;
  }

  $globname = $Cbucket->search_types['videos'];
  $clsname = get_class(${$globname});
  class_alias($clsname, 'loggedunlisted_CBvideo_Base'); 
  class loggedunlisted_CBvideo extends loggedunlisted_CBvideo_Base
  {
    function init_search(){
      parent::init_search();
      if( !has_access('admin_access',TRUE) ){/*
        array_walk($this->search->columns, function($key, $value) {
           if($value["field"] == "broadcast"){ 
            $this->search->columns[$key] = array('field'=>'broadcast','type'=>'==','var'=>'logunlist','op'=>'OR','type'=>'!=','var'=>'unlisted','value'=>'static') ;
           }
        });
        $this->search->columns =array(
          array('field'=>'title','type'=>'LIKE','var'=>'%{KEY}%'),
          array('field'=>'tags','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
          array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','type'=>'!=','var'=>'logunlist','op'=>'AND','value'=>'static'),
          array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static')
        );*/
      }
    }
  }

  $g_loggedunlisted_CBvideo = new loggedunlisted_CBvideo();
  //echo "place";
  cb_register_function('loggedunlisted_watch_video','watch_video');
  cb_register_function('loggedunlisted_get_videos','get_videos');
  cb_register_function('loggedunlisted_load_option_fields','load_option_fields');
  
  //$Cbucket->search_types['videos'] = "g_loggedunlisted_CBvideo";
}

