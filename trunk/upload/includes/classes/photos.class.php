<?php

/**
 * @ Author Arslan Hassan, Fawaz Tahir
 * @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @ Class : Photos Class
 * @ date : 06 November 2010
 * @ Version : v2.0.91
 * @ Description: Well guys time to work on one of the most wanted Module. Photo Module.
 * @ New Things Needed: 
 * 	 - Photo Sharing Email Template
 */
//define('MAX_PHOTO_UPLOAD',5);

class CBPhotos {

    var $action = '';
    var $collection = '';
    var $p_tbl = "photos";
    var $i_tbl = "collection_items";
    var $exts = '';
    var $max_file_size; // image file size. Setting from Admin area;
    var $mid_width;
    var $mid_height;
    var $lar_width;
    var $thumb_width;
    var $thumb_height;
    var $position;
    var $cropping;
    var $padding = 10;
    var $max_watermark_width = 120;
    var $embed_types;
    var $share_email_vars;
    //var $max_uploads = MAX_PHOTO_UPLOAD;  Max number of uploads at once
    var $search;
    var $tagger_configs;
    var $selector_id = 'photo';
	var $thumb_dimensions = array();

    /**
     * __Constructor of CBPhotos
     */
    function CBPhotos() {
        $this->exts = array('jpg', 'png', 'gif', 'jpeg'); // This should be added from Admin Area. may be some people also want to allow BMPs;
        //$this->embed_types = array("html", "forum", "email", "direct");
        $this->set_default_tagger_configs();
    }

    /**
     * Setting up Photos Section
     */
    function init_photos() {
        global $Cbucket;
        $this->init_actions();
        $this->init_collections();
        $this->photos_admin_menu();
        $this->setting_other_things();
        $this->set_photo_max_size();
        $this->thumb_dimensions = array(
          't'	=> array(
                    'name' => lang('Thumb'),
            'width' => config('photo_thumb_width'),
            'height' => config('photo_thumb_height'),
            'crop' => (config('photo_crop') == 0 ? -1 : 5 ),
            'watermark' => false,
            'sharpit' => true
          ),
          'm'	=> array(
                    'name' => lang('Medium'),
            'width' => config('photo_med_width'),
            'height' => config('photo_med_height'),
            'crop' => (config('photo_crop') == 0 ? -1 : 5 ),
            'watermark' => false,
            'sharpit' => false
          ),
          'l'		=> array(
                    'name' => lang('Large'),
            'width' => config('photo_lar_width'),
            'height' => 0,
            'crop' => -1,
            'watermark' => config('watermark_photo'),
            'sharpit' => false
          ),
          'o'	=> array(
                    'name' => lang('Original'),
            'width' => 0,
            'height' => 0,
            'crop' => -1,
            'watermark' => config('watermark_photo'),
            'sharpit' => false
          )
        );
        
        $this->default_thumb_dimensions = array( 't', 'm', 'l', 'o' );
    }

    /**
     * Initiating Actions for Photos
     */
    function init_actions() {
        $this->action = new cbactions();
        $this->action->init();  // Setting up reporting excuses
        $this->action->type = 'p';
        $this->action->name = 'photo';
        $this->action->obj_class = 'cbphoto';
        $this->action->check_func = 'photo_exists';
        $this->action->type_tbl = "photos";
        $this->action->type_id_field = 'photo_id';
    }

    /**
     * Setting Email Settings
     */
    function set_share_email( $data ) {
        $this->share_email_vars = array(
            '{photo_title}' => $data['photo_title'],
            '{photo_description}' => $data['photo_description'],
            '{photo_link}' => $this->collection->collection_links( $data, 'view_item' ),
            '{photo_thumb}' => $this->get_image_file( $data['photo_id'], 'm' )
        );
        $this->action->share_template_name = 'photo_share_template';
        $this->action->val_array = $this->share_email_vars;
    }

    /**
     * Initiating Collections for Photos
     */
    function init_collections() {
        $this->collection = new Collections;
        $this->collection->objType = "p";
        $this->collection->objClass = "cbphoto";
        $this->collection->objTable = "photos";
        $this->collection->objName = "Photo";
        $this->collection->objFunction = "photo_exists";
        $this->collection->objFieldID = "photo_id";
        $this->photo_register_function( 'delete_collection_photos' );
    }

    /**
     * Create Admin Area menu for photos
     */
    function photos_admin_menu() {
		global $Cbucket,$cbcollection,$userquery;
		$am = $Cbucket->AdminMenu;
		$per = $userquery->get_user_level(userid());
		
		
		$menu = array(
                    'title' => lang('Photos Manager'),
                    'icon'  => 'icon-picture',
                    'id'    => 'photos-manager',
                    'access' => 'photos_moderation',
                );
                    
                $sub_menu =  array(
                    'photos-manager' => array(
                        array('title' => lang('Photo Manager'), 'link' => 'photo_manager.php'),
                        array('title' => lang('Photo Tags Manager'), 'link' => 'tags_manager.php' ),
                        array('title' => lang('Flagged Photos'), 'link' => 'flagged_photos.php'),
                        array('title' => lang('Orphan Photos'), 'link' => 'orphan_photos.php'),					
                        array('title' => lang('Photo Settings'), 'link' => 'photo_settings.php'),
                        array('title' => lang('Watermark Settings'), 'link' => 'photo_settings.php?mode=watermark_settings'),
                        array('title' => lang('Recreate Thumbs'), 'link' => 'recreate_thumbs.php?mode=mass')
                    )
                );
                
                add_admin_menu($menu);
                add_admin_sub_menus($sub_menu);	
    }

    /**
     * Setting other things
     */
    function setting_other_things() {
        global $userquery, $Cbucket;
        // Search type
        if ( isSectionEnabled( 'photos' ) )
            $Cbucket->search_types['photos'] = "cbphoto";

        // My account links
        $accountLinks = array();
        $accountLinks = array(
            lang( 'manage_photos' ) => "manage_photos.php",
            lang( 'manage_favorite_photos' ) => "manage_photos.php?mode=favorite",
            lang( 'Manage Avatars' ) => "manage_photos.php?mode=avatars",
            lang( 'manage_my_album' ) => "manage_photos.php?mode=my_album",
        );
        if ( isSectionEnabled( 'photos' ) )
            $userquery->user_account[lang( 'Photos' )] = $accountLinks;

        //Setting Cbucket links

        $Cbucket->links['photos'] = array('photos.php', 'photos/');
        $Cbucket->links['manage_photos'] = array('manage_photos.php', 'manage_photos.php');
        $Cbucket->links['edit_photo'] = array('edit_photo.php?photo=', 'edit_photo.php?photo=');
        $Cbucket->links['photo_upload'] = array('photo_upload.php', 'photo_upload');
        $Cbucket->links['manage_favorite_photos'] = array('manage_photos.php?mode=favorite', 'manage_photos.php?mode=favorite');
        $Cbucket->links['manage_orphan_photos'] = array('manage_photos.php?mode=orphan', 'manage_photos.php?mode=orphan');
        $Cbucket->links['user_photos'] = array('user_content.php?object_group=content&object=photos&user=', 'user_content.php?object_group=content&object=photos&user=');
        $Cbucket->links['user_fav_photos'] = array('user_content.php?object_group=content&object=photos&content_type=favorite&user=', 'user_content.php?object_group=content&object=photos&content_type=favorite&user=');
       
        // Setting Home Tab
        add_menu_item('navigation', lang('Photos'), cblink(array("name"=>"photos")), "photos","icon-picture icon-white");
        
        // Adding photo upload options
        register_upload_option( array(
            'object' => 'photos',
            'title' => lang('Upload from computer'),
            'description' => lang('Upload photos from your computer. Supported formats are <i>'.implode( ', ', $this->exts ).'</i>'),
            'function' => 'load_photo_plupload_block'
        ) );
        
        $plupload_js_files = array(
            MODULES_URL.'/uploader/plupload/plupload.js',
            MODULES_URL.'/uploader/plupload/plupload.html5.js',
            MODULES_URL.'/uploader/plupload/plupload.flash.js',
            //MODULES_URL.'/uploader/plupload/jquery.plupload.queue/jquery.plupload.queue.js',
            // MODULES_URL.'/uploader/plupload/jquery.ui.plupload/jquery.ui.plupload.js'
        );
        
        add_js($plupload_js_files,'photo_upload');
    }

    /**
     * Initiatting Search
     */
    function init_search() {
        $this->search = new cbsearch;
        $this->search->db_tbl = "photos";
        $this->search->use_match_method = TRUE;

        $this->search->columns = array(
            array("field" => "photo_title", "type" => "LIKE", "var" => "%{KEY}%"),
            array("field" => "photo_tags", "type" => "LIKE", "var" => "%{KEY}%", "op" => "OR")
        );
        $this->search->match_fields = array("photo_title", "photo_tags");
        $this->search->cat_tbl = $this->cat_tbl;

        $this->search->display_template = LAYOUT . '/blocks/photo.html';
        $this->search->template_var = 'photo';
        $this->search->has_user_id = true;
        $this->search->results_per_page = config( 'photo_search_result' );
        $this->search->search_type['photos'] = array('title' => lang( 'photos' ));
        $this->search->add_cond( tbl( 'photos.collection_id' ) . " <> 0" );

        $sorting = array(
            'date_added' => lang( "date_added" ),
            'views' => lang( "views" ),
            'total_comments' => lang( "comments" ),
            'rating' => lang( "rating" ),
            'total_favorites' => lang( "favorites" )
        );

        $this->search->sorting = array(
            'date_added' => " date_added DESC",
            'views' => " views DESC",
            'rating' => " rating DESC, rated_by DESC",
            'total_comments' => " total_comments DESC ",
            'total_favorites' => " total_favorites DESC"
        );

        $array = $_GET;
        $uploaded = $array['datemargin'];
        $sort = $array['sort'];

        $forms = array(
            'query' => array(
                'title' => lang( 'keywords' ),
                'type' => 'textfield',
                'name' => 'query',
                'id' => 'query',
                'value' => cleanForm( $array['query'] )
            ),
            'date_margin' => array(
                'title' => lang( 'uploaded' ),
                'type' => 'dropdown',
                'name' => 'datemargin',
                'id' => 'datemargin',
                'value' => $this->search->date_margins(),
                'checked' => $uploaded,
            ),
            'sort' => array(
                'title' => lang( 'sort_by' ),
                'type' => 'dropdown',
                'name' => 'sort',
                'value' => $sorting,
                'checked' => $sort
            )
        );

        $this->search->search_type['photos']['fields'] = $forms;
    }

    /**
     * Set File Max Size
     */
    function set_photo_max_size() {
        global $Cbucket;
        $adminSize = $Cbucket->configs['max_photo_size'];
        if ( !$adminSize )
            $this->max_file_size = 2 * 1024 * 1024;
        else
            $this->max_file_size = $adminSize * 1024 * 1024;
    }

    /**
     * Check if photo exists or not
     */
    function photo_exists( $id ) {
        global $db;
        if ( is_numeric( $id ) )
            $result = $db->select( tbl( $this->p_tbl ), "photo_id", " photo_id = '$id'" );
        else
            $result = $db->select( tbl( $this->p_tbl ), "photo_id", " photo_key = '$id'" );

        if ( $result )
            return true;
        else
            return false;
    }

    /**
     * Register function
     */
    function photo_register_function( $func ) {
        global $cbcollection;
        $cbcollection->collection_delete_functions[] = 'delete_collection_photos';
    }

    /**
     * Get Photo
     */
    function get_photo( $pid, $join = false ) {
        global $db;
        
        if ( $join == true ) {
            list( $join, $alias ) = join_collection_table();
        }
        
        $userFields = array('userid','username','avatar','avatar_url','email','total_videos');
        $uQueryFields = ',users.'.implode(',users.',$userFields);
       
        if ( is_numeric( $pid ) )
            $cond = tbl( $this->p_tbl.".photo_id='$pid'");
        else
            $cond = tbl( $this->p_tbl.".photo_key='$pid'" );
            
        $result = $db->select( tbl( $this->p_tbl )
                .' LEFT JOIN '.tbl('users').' ON '
                .tbl($this->p_tbl.'.userid').' = '.tbl('users.userid').$join, tbl($this->p_tbl.".*".$uQueryFields).$alias, $cond );
       //echo $db->db_query;
        if ( $db->num_rows > 0 )
            return $result[0];
        else
            return false;
    }

    /**
     * Get Photos 
     */
    function get_photos( $p ) {
        global $db;
        $tables = "users,photos";
        $order = $p['order'];
        if ( !$order ) {
            $order = 'date_added DESC';
        }
        
        if ( strpos( $order, ',' ) > 0 ) {
            $order = explode(',', $order);
            $order = array_map('trim',$order);
            foreach( $order as $o ) {
                $newo[] = tbl('photos.'.$o);
            }
            $order = $newo;
            $order = implode(',', $order);
        }
        
        $limit = $p['limit'];
        $cond = "";

        if ( !has_access( 'admin_access', TRUE ) ) {
            $cond = " " . tbl( 'photos.broadcast' ) . " = 'public' AND " . tbl( 'photos.active' ) . " = 'yes'";
        } else {
            if ( $p['active'] )
                $cond .= " " . tbl( 'photos.active' ) . " = '" . $p['active'] . "'";

            if ( $p['broadcast'] ) {
                if ( $cond != "" )
                    $cond .= " AND ";
                $cond .= " " . tbl( 'photos.broadcast' ) . " = '" . $p['broadcast'] . "'";
            }
        }

        if ( $p['pid'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $this->constructMultipleQuery( array("ids" => $p['pid'], "sign" => "=", "operator" => "OR") );
        }

        if ( $p['key'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . tbl( 'photos.photo_key' ) . " = '" . $p['key'] . "'";
        }

        if ( $p['filename'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . tbl( 'photos.filename' ) . " = '" . $p['filename'] . "'";
        }

        if ( $p['extension'] ) {
            foreach ( $this->exts as $ext ) {
                if ( in_array( $ext, $this->exts ) ) {
                    if ( $cond != "" )
                        $cond .= " AND ";
                    $cond .= " " . tbl( 'photos.ext' ) . " = '" . $p['extension'] . "'";
                }
            }
        }

        if ( $p['date_span'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . cbsearch::date_margin( tbl("photos.date_added"), $p['date_span'] );
        }

        if ( $p['featured'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . tbl( 'photos.featured' ) . " = '" . $p['featured'] . "'";
        }

        if ( $p['user'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $this->constructMultipleQuery( array("ids" => $p['user'], "sign" => "=", "operator" => "AND", "column" => "userid") );
        }

        if ( $p['exclude'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $this->constructMultipleQuery( array("ids" => $p['exclude'], "sign" => "<>") );
        }

        $title_tag = '';

        if ( $p['title'] ) {
            $title_tag = " " . tbl( 'photos.photo_title' ) . " LIKE '%" . $p['title'] . "%'";
        }

        if ( $p['tags'] ) {
            $tags = explode( ",", $p['tags'] );
            if ( count( $tags ) > 0 ) {
                if ( $title_tag != '' )
                    $title_tag .= " OR ";
                $total = count( $tags );
                $loop = 1;
                foreach ( $tags as $tag ) {
                    $title_tag .= " " . tbl( 'photos.photo_tags' ) . " LIKE '%$tag%'";
                    if ( $loop < $total )
                        $title_tag .= " OR ";
                    $loop++;
                }
            } else {
                if ( $title_tag != '' )
                    $title_tag .= " OR ";
                $title_tag .= " " . tbl( 'photos.photo_tags' ) . " LIKE '%" . $p['tags'] . "%'";
            }
        }

        if ( $title_tag != "" ) {
            if ( $cond != '' )
                $cond .= " AND ";
            $cond .= " ($title_tag) ";
        }

        if ( $p['ex_user'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $this->constructMultipleQuery( array("ids" => $p['ex_user'], "sign" => "<>", "operator" => "AND", "column" => "userid") );
        }

        if ( $p['extra_cond'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $p['extra_cond'];
        }

        if ( $p['get_orphans'] )
            $p['collection'] = "\0";

        if ( $p['collection'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= $this->constructMultipleQuery( array("ids" => $p['collection'], "sign" => "=", "operator" => "OR", "column" => "collection_id") );
        } else {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . tbl( 'photos.collection_id' ) . " <> '0'";
        }
        
        if ( $p['mature'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $cond .= " " . tbl( 'photos.is_mature' ) . " = '" . $p['mature'] . "'";
        }
        
        if ( has_access('admin_access') ) {
            if ( !$p['is_avatar'] ) { $p['is_avatar'] = 'no'; }
            if ( $cond != '' ) { $cond .= ' AND '; }
            $cond .= " ".tbl('photos.is_avatar')." = '".$p['is_avatar']."'";	
        } else {
            if ( $cond != '' ) {
                $cond .= ' AND ';	
            }
            $cond .= ' '.tbl('photos.is_avatar').' = "no" ';
        }
        list ( $join, $alias ) = join_collection_table();
        
        if ( !$p['count_only'] && !$p['show_related'] ) {
            if ( $cond != "" )
                $cond .= " AND ";
            $result = $db->select( tbl( $tables ).$join, tbl( "photos.*,users.userid,users.username" ).$alias, $cond . tbl( "photos.userid" ) . " = " . tbl( "users.userid" ), $limit, trim($order) );
            //echo $db->db_query;					  						  	
        }

        if ( $p['show_related'] ) {
            $cond = "MATCH(" . tbl( 'photos.photo_title,photos.photo_tags' ) . ")";
            $cond .= " AGAINST ('" . cbsearch::set_the_key( $p['title'] ) . "' IN BOOLEAN MODE)";
            if ( $p['exclude'] ) {
                if ( $cond != "" )
                    $cond .= " AND ";
                $cond .= $this->constructMultipleQuery( array("ids" => $p['exclude'], "sign" => "<>") );
            }

            if ( $p['collection'] ) {
                if ( $cond != "" )
                    $cond .= " AND ";
                $cond .= $this->constructMultipleQuery( array("ids" => $p['collection'], "sign" => "<>", "column" => "collection_id") );
            }

            if ( $p['extra_cond'] ) {
                if ( $cond != "" )
                    $cond .= " AND ";
                $cond .= $p['extra_cond'];
            }
            
            // Remove avatar photos
            if ( has_access('admin_access') ) {
				if ( !$p['is_avatar'] ) { $p['is_avatar'] = 'no'; }
				if ( $cond != '' ) { $cond .= ' AND '; }
				$cond .= " ".tbl('photos.is_avatar')." = '".$p['is_avatar']."'";	
		} else {
			if ( $cond != '' ) {
				$cond .= ' AND ';	
			}
			$cond .= ' '.tbl('photos.is_avatar').' = "no" ';
		}
            
            $result = $db->select( tbl( $tables ).$join, tbl( "photos.*,users.userid,users.username" ).$alias, $cond . " AND " . tbl( 'photos.collection_id' ) . " <> '0' AND " . tbl( "photos.userid" ) . " = " . tbl( "users.userid" ), $limit, tbl('photos.'.trim($order) ) );
            //echo $db->db_query;
            // We found nothing from TITLE of Photos, let's try TAGS
            if ( $db->num_rows == 0 ) {
                $tags = cbsearch::set_the_key( $p['tags'] );
                $tags = str_replace( '+', '', $tags );

                $cond = "MATCH(" . tbl( 'photos.photo_title,photos.photo_tags' ) . ")";
                $cond .= " AGAINST ('" . $tags . "' IN BOOLEAN MODE)";

                if ( $p['exclude'] ) {
                    if ( $cond != "" )
                        $cond .= " AND ";
                    $cond .= $this->constructMultipleQuery( array("ids" => $p['exclude'], "sign" => "<>") );
                }

                if ( $p['collection'] ) {
                    if ( $cond != "" )
                        $cond .= " AND ";
                    $cond .= $this->constructMultipleQuery( array("ids" => $p['collection'], "sign" => "<>", "column" => "collection_id") );
                }

                if ( $p['extra_cond'] ) {
                    if ( $cond != "" )
                        $cond .= " AND ";
                    $cond .= $p['extra_cond'];
                }
                
                if ( has_access('admin_access') ) {
                    if ( !$p['is_avatar'] ) { $p['is_avatar'] = 'no'; }
                    if ( $cond != '' ) { $cond .= ' AND '; }
                    $cond .= " ".tbl('photos.is_avatar')." = '".$p['is_avatar']."'";	
                } else {
                    if ( $cond != '' ) {
                        $cond .= ' AND ';	
                    }
                    $cond .= ' '.tbl('photos.is_avatar').' = "no" ';
                }
                $result = $db->select( tbl( $tables ).$join, tbl( "photos.*,users.userid,users.username" ).$alias, $cond . " AND " . tbl( 'photos.collection_id' ) . " <> '0' AND " . tbl( "photos.userid" ) . " = " . tbl( "users.userid" ), $limit, tbl('photos.'.trim($order) ) );
                //echo $db->db_query;
            }
        }

        if ( $p['count_only'] ) {
            if ( $p['extra_cond'] ) {
                if ( $cond != "" )
                    $cond .= " AND ";
                $cond .= $p['extra_cond'];
            }
            $result = $db->count( tbl( "photos" ), "photo_id", $cond );
        }

        if ( $p['assign'] )
            assign( $p['assign'], $result );
        else
            return $result;
    }

    /**
     * Used to construct Multi Query
     * Only IDs will be excepted
     */
    function constructMultipleQuery( $params ) {
        $cond = "";
        $IDs = $params['ids'];
        if ( is_array( $IDs ) )
            $IDs = $IDs;
        else
            $IDs = explode( ",", $IDs );

        $count = 0;
        $cond .= "( ";
        foreach ( $IDs as $id ) {
            $id = str_replace( " ", "", $id );
            if ( is_numeric( $id ) || $params['column'] == 'collection_id' ) {
                if ( $count > 0 )
                    $cond .= " " . ($params['operator'] ? $params['operator'] : 'AND') . " ";
                $cond .= "" . tbl( 'photos.' . ($params['column'] ? $params['column'] : 'photo_id') ) . " " . ($params['sign'] ? $params['sign'] : '=') . " '" . $id . "'";
                $count++;
            }
        }
        $cond .= " )";

        return $cond;
    }

    /**
     * Used to construct Exclude Query 
      function exclude_query($array)
      {
      $cond = '';
      if(!is_array($array))
      $ids = explode(',',$array);
      else
      $ids = $array;

      $count = 0;

      $cond .= "( ";
      foreach($ids as $id)
      {
      $count++;
      if($count > 1)
      $cond .= " AND ";
      $cond .= " ".tbl('photos.photo_id')." <> '".$id."'";
      }
      $cond .= " )";

      return $cond;
      } */

    /**
     * Used to generate photo key
     * Replica of video_keygen function
     */
    function photo_key() {
        global $db;

        $char_list = "ABDGHKMNORSUXWY";
        $char_list .= "123456789";
        while ( 1 ) {
            $photo_key = '';
            srand( (double) microtime() * 1000000 );
            for ( $i = 0; $i < 12; $i++ ) {
                $photo_key .= substr( $char_list, (rand() % (strlen( $char_list )) ), 1 );
            }

            if ( !$this->pkey_exists( $photo_key ) )
                break;
        }

        return $photo_key;
    }

    /**
     * Used to check if key exists
     */
    function pkey_exists( $key ) {
        global $db;
        $db->select( tbl( "photos" ), "photo_key", " photo_key = '$key'" );
        if ( $db->num_rows > 0 )
            return true;
        else
            return false;
    }

    /**
     * Used to delete photo
     */
    function delete_photo( $id, $oprhan = FALSE ) {
        global $db;
        if ( $this->photo_exists( $id ) ) {
            $photo = $this->get_photo( $id );

            $del_photo_funcs = cb_get_functions( 'delete_photo' );
            if ( is_array( $del_photo_funcs ) ) {

                foreach ( $del_photo_funcs as $func ) {
                    if ( function_exists( $func['func'] ) ) {
                        $func['func']( $photo );
                    }
                }
            }

            if ( $orphan == FALSE )//removing from collection
                $this->collection->remove_item( $photo['photo_id'], $photo['collection_id'] );

            //now removing photo files
            $this->delete_photo_files( $photo );

            //finally removing from Database
            $this->delete_from_db( $photo );

            //Decrementing User Photos
            $db->update( tbl( "users" ), array("total_photos"), array("|f|total_photos-1"), " userid='" . $photo['userid'] . "'" );

            //Removing Photo Comments
            $db->delete( tbl( "comments" ), array("type", "type_id"), array("p", $photo['photo_id']) );

            //Removing Photo From Favortes
            $db->delete( tbl( "favorites" ), array("type", "id"), array("p", $photo['photo_id']) );
        } else
            e( lang( "photo_not_exists" ) );
    }

    /**
     * Used to delete photo files
     */
    function delete_photo_files( $id ) {
        if ( !is_array( $id ) )
            $photo = $this->get_photo( $id );
        else
            $photo = $id;

        $pid = $photo['photo_id'];
		$date_dir  = get_photo_date_folder( $photo );
        $files = $this->get_image_file( $pid, 't', TRUE, NULL, FALSE, TRUE );
        if ( !empty( $files ) ) {
            foreach ( $files as $file ) {
                $file_dir = PHOTOS_DIR . "/" . $date_dir.'/' . $file;
                if ( file_exists( $file_dir ) )
                    unlink( $file_dir );
            }

            e( sprintf( lang( "success_delete_file" ), $photo['photo_title'] ), "m" );
        }
    }

    /**
     * Used to delete photo from database
     */
    function delete_from_db( $id ) {
        global $db;
        if ( is_array( $id ) )
            $delete_id = $id['photo_id'];
        else
            $delete_id = $id;

        $db->execute( "DELETE FROM " . tbl( 'photos' ) . " WHERE photo_id = $delete_id" );
        e( lang( "photo_success_deleted" ), "m" );
    }

    /**
     * Used to get photo owner
     */
    function get_photo_owner( $id ) {
        return $this->get_photo_field( $id, 'userid' );
    }

    /**
     * Used to get photo any field
     */
    function get_photo_field( $id, $field ) {
        global $db;
        if ( !$field )
            return false;
        else {
            if ( !is_numeric( $id ) )
                $result = $db->select( tbl( $this->p_tbl ), $field, ' photo_key = ' . $id . '' );
            else
                $result = $db->select( tbl( $this->p_tbl ), $field, ' photo_id = ' . $id . '' );

            if ( $result )
                return $result[0][$field];
            else
                return false;
        }
    }

    /**
     * Used filter array
     */
    function remove_empty_indexes( $array ) {
        $newArr = array();
        if ( is_array( $array ) ) {
            foreach ( $array as $key => $arr ) {
                if ( is_array( $arr ) ) {
                    foreach ( $arr as $a ) {
                        if ( !empty( $a ) ) {
                            $newArr[$key][] = $a;
                        }
                    }
                }
            }

            return $newArr;
        } else {
            echo "No Array Provided";
        }
    }

    /**
     * Used to crop the image
     * Image will be crop to dead-center
     */
    function crop_image( $input, $output, $ext, $width, $height ) {
        $info = getimagesize( $input );
        $Swidth = $info[0];
        $Sheight = $info[1];

        $canvas = imagecreatetruecolor( $width, $height );
        $left_padding = $Swidth / 2 - $width / 2;
        $top_padding = $Sheight / 2 - $height / 2;

        switch ( $ext ) {
            case "jpeg":
            case "jpg":
            case "JPG":
            case "JPEG": {
                    $image = imagecreatefromjpeg( $input );
                    imagecopy( $canvas, $image, 0, 0, $left_padding, $top_padding, $width, $height );
                    imagejpeg( $canvas, $output, 90 );
                }
                break;

            case "png":
            case "PNG": {
                    $image = imagecreatefrompng( $input );
                    imagecopy( $canvas, $image, 0, 0, $left_padding, $top_padding, $width, $height );
                    imagepng( $canvas, $output, 9 );
                }
                break;

            case "gif":
            case "GIF": {
                    $image = imagecreatefromgif( $input );
                    imagecopy( $canvas, $image, 0, 0, $left_padding, $top_padding, $width, $height );
                    imagejpeg( $canvas, $output, 90 );
                }
                break;

            default: {
                    return false;
                }
                break;
        }
        imagedestroy( $canvas );
    }

    /**
     * Used to resize and watermark image.
     * |= Updated on May 19th, 2012 =|
     * using photo_dimensions filter you can add new
     * dimensions in thumb_dimensions array. Use 
     * add_custom_photo_size() function in your callback
     * to easily add new dimensions
     * */
    function generate_photos( $array ) {
        global $db;
        
        if ( !is_array( $array ) )
            $p = $this->get_photo( $array );
        else
            $p = $array;

        $filename = $p['filename'];
        $extension = $p['ext'];
		$date_dir = get_photo_date_folder( $p );
		$path = PHOTOS_DIR . "/".$date_dir.'/';
        /* Updating resizes code. From static, we'll load code, dimensions, watermark and sharpit from thumb_dimensions array */
//        apply_filters( null, 'photo_dimensions' );
//        $dimensions = $this->thumb_dimensions;
        $dimensions = get_photo_dimensions( true );
        
        $img = new CB_Resizer( $path.$filename.".".$extension );
        foreach ( $dimensions as $code => $dim ) {
            $img->target = $path.$filename."_".$code.".".$extension;
            // Set cropping
            $img->cropping = $dim['crop'];
            $img->_resize( $dim['width'], $dim['height'] );
            
            // Check if we want to sharp this thumb
            if ( $dim['sharpit'] == true ) {
                $img->_sharpit();
            }
            
            // Check if we want to apply watermark
            if ( $dim['watermark'] == true ) {
                // Set placement
                $img->watermark_placement = $this->position;
                $watermark_file = $this->watermark_file();
                // Replacing URL to DIR.
                $watermark = str_replace( BASEURL, BASEDIR, $watermark_file );
                $img->watermark( $watermark );
            }
            
            // Lets save it
            $img->save();
        }
//        $this->createThumb( $path . $filename . "." . $extension, $path . $filename . "_o." . $extension, $extension );
//        $this->createThumb( $path . $filename . "." . $extension, $path . $filename . "_t." . $extension, $extension, $this->thumb_width, $this->thumb_height );
//        $this->createThumb( $path . $filename . "." . $extension, $path . $filename . "_m." . $extension, $extension, $this->mid_width, $this->mid_height );
//        $this->createThumb( $path . $filename . "." . $extension, $path . $filename . "_l." . $extension, $extension, $this->lar_width );
//
//        $should_watermark = config( 'watermark_photo' );
//
//        if ( !empty( $should_watermark ) && $should_watermark == 1 ) {
//            $this->watermark_image( $path . $filename . "_l." . $extension, $path . $filename . "_l." . $extension );
//            $this->watermark_image( $path . $filename . "_o." . $extension, $path . $filename . "_o." . $extension );
//        }

        /* GETTING DETAILS OF IMAGES AND STORING THEM IN DB */
        $this->update_image_details( $p );
    }

    /**
     * This function is used to get photo files and extract
     * dimensions and file size of each file, put them in array
     * then encode in json and finally update photo details column
     */
    function update_image_details( $photo ) {
        global $db, $json;
        if ( is_array( $photo ) && !empty( $photo['photo_id'] ) )
            $p = $photo;
        else
            $p = $this->get_photo( $photo );

        if ( !empty( $photo ) ) {
            $images = $this->get_image_file( $p, 'all', true, null, false );
            
            if ( $images ) {
                foreach ( $images as $image ) {
                    $imageFile = PHOTOS_DIR . "/" . get_photo_date_folder( $p ).'/'. $image;
                    if ( file_exists( $imageFile ) ) {
                        $imageDetails = getimagesize( $imageFile );
                        $imageSize = filesize( $imageFile );
                        $data[$this->get_image_type( $image )] = array(
                            "width" => $imageDetails[0],
                            "height" => $imageDetails[1],
                            "attribute" => mysql_clean( $imageDetails[3] ),
                            "size" => array(
                                "bytes" => round( $imageSize ),
                                "kilobytes" => round( $imageSize / 1024 ),
                                "megabytes" => round( $imageSize / 1024 / 1024, 2 )
                            )
                        );
                    }
                }

                if ( is_array( $data ) && !empty( $data ) ) {
                    if ( phpversion() < "5.2.0" )
                        $encodedData = stripslashes( $json->json_encode( $data ) );
                    else
                        $encodedData = stripslashes( json_encode( $data ) );

                    $db->update( tbl( 'photos' ), array("photo_details"), array("|no_mc|$encodedData"), " photo_id = '" . $p['photo_id'] . "' " );
                }
            }
        }
    }

    /**
     * Creating resized photo
     */
    function createThumb( $from, $to, $ext, $d_width = NULL, $d_height = NULL, $force_copy = false ) {
        $file = $from;
        $info = getimagesize( $file );
        $org_width = $info[0];
        $org_height = $info[1];

        if ( $org_width > $d_width && !empty( $d_width ) ) {
            $ratio = $org_width / $d_width; // We will resize it according to Width

            $width = $org_width / $ratio;
            $height = $org_height / $ratio;

            $image_r = imagecreatetruecolor( $width, $height );
            if ( !empty( $d_height ) && $height > $d_height && $this->cropping == 1 ) {
                $crop_image = TRUE;
            }

            switch ( $ext ) {
                case "jpeg":
                case "jpg":
                case "JPG":
                case "JPEG": {
                        $image = imagecreatefromjpeg( $file );
                        imagecopyresampled( $image_r, $image, 0, 0, 0, 0, $width, $height, $org_width, $org_height );
                        imagejpeg( $image_r, $to, 90 );

                        if ( !empty( $crop_image ) )
                            $this->crop_image( $to, $to, $ext, $width, $d_height );
                    }
                    break;

                case "png":
                case "PNG": {
                        $image = imagecreatefrompng( $file );
                        imagecopyresampled( $image_r, $image, 0, 0, 0, 0, $width, $height, $org_width, $org_height );
                        imagepng( $image_r, $to, 9 );
                        if ( !empty( $crop_image ) )
                            $this->crop_image( $to, $to, $ext, $width, $d_height );
                    }
                    break;

                case "gif":
                case "GIF": {
                        $image = imagecreatefromgif( $file );
                        imagecopyresampled( $image_r, $image, 0, 0, 0, 0, $width, $height, $org_width, $org_height );
                        imagegif( $image_r, $to, 90 );
                        if ( !empty( $crop_image ) )
                            $this->crop_image( $to, $to, $ext, $width, $d_height );
                    }
                    break;
            }
            imagedestroy( $image_r );
        } else {
            if ( !file_exists( $to ) || $force_copy === true )
                copy( $from, $to );
        }
    }

    /**
     * Used to get watermark file
     */
    function watermark_file() {
        if ( file_exists( BASEDIR . "/images/photo_watermark.png" ) )
            return BASEURL . "/images/photo_watermark.png";
        else
            return false;
    }

    /**
     * Used to set watermark position
     */
    function position_watermark( $file, $watermark ) {
        if ( empty( $this->position ) )
            $info = array('left', 'top');
        else
            $info = explode( ":", $this->position );

        $x = $info[0];
        $y = $info[1];
        list($w, $h) = getimagesize( $file );
        list($ww, $wh) = getimagesize( $watermark );
        $padding = $this->padding;

        switch ( $x ) {
            case "center": {
                    $finalxPadding = $w / 2 - $ww / 2;
                }
                break;

            case "left":
            default: {
                    $finalxPadding = $padding;
                }
                break;

            case "right": {
                    $finalxPadding = $w - $ww - $padding;
                }
                break;
        }

        switch ( $y ) {
            case "top":
            default: {
                    $finalyPadding = $padding;
                }
                break;

            case "center": {
                    $finalyPadding = $h / 2 - $wh / 2;
                }
                break;

            case "bottom": {
                    $finalyPadding = $h - $wh - $padding;
                }
                break;
        }

        $values = array($finalxPadding, $finalyPadding);
        return $values;
    }

    /**
     * Used to watermark image
     */
    function watermark_image( $input, $output ) {
        $watermark_file = $this->watermark_file();
        if ( !$watermark_file )
            return false;
        else {
            list($Swidth, $Sheight, $Stype) = getimagesize( $input );
            $wImage = imagecreatefrompng( $watermark_file );
            $ww = imagesx( $wImage );
            $wh = imagesy( $wImage );
            $paddings = $this->position_watermark( $input, $watermark_file );

            switch ( $Stype ) {
                case 1: //GIF 
                {
                        $sImage = imagecreatefromgif( $input );
                        imagecopy( $sImage, $wImage, $paddings[0], $paddings[1], 0, 0, $ww, $wh );
                        imagejpeg( $sImage, $output, 90 );
                    }
                    break;

                case 2: //JPEG 
                {
                        $sImage = imagecreatefromjpeg( $input );
                        imagecopy( $sImage, $wImage, $paddings[0], $paddings[1], 0, 0, $ww, $wh );
                        imagejpeg( $sImage, $output, 90 );
                    }
                    break;

                case 3: //PNG 
                {
                        $sImage = imagecreatefrompng( $input );
                        imagecopy( $sImage, $wImage, $paddings[0], $paddings[1], 0, 0, $ww, $wh );
                        imagepng( $sImage, $output, 9 );
                    }
                    break;
            }
        }
    }

    /**
     * Load Upload Form
     */
    function loadUploadForm( $params ) {
        $p = $params;
        $should_include = $p['includeHeader'] ? $p['includeHeader'] : TRUE;
        $output = '<form action="" method="post"';
        if ( $p['formName'] )
            $output .= " name = '" . $p['formName'] . "'";
        else
            $output .= " name = 'photo_form'";
        if ( $p['formID'] )
            $output .= " id = '" . $p['formID'] . "'";
        else
            $output .= " id = 'photo_form'";
        if ( $p['formClass'] )
            $formClass = $p['formClass'];
        $output .= " class = 'clearfix " . $formClass . "'";
        $output .= ">";
        if ( $p['class'] )
            $class = $p['class'];
        if ( $should_include == TRUE )
            $output .= Fetch( "/blocks/upload_head.html" );
        $output .= "<div ";
        $output .= "class = 'PhotoUploaderWrapper " . $class . "'";
        if ( $p['WrapperID'] )
            $output .= "id = '" . $p['WrapperID'] . "'";
        if ( $p['WrapperExtraAttr'] )
            $output .= $p['WrapperExtraAttr'];
        $output .= ">";
        $output .= '<input type="file" name="photo_uploads" id="photo_uploads" />';
        $output .= "</div>";
        $output .= '<div style="clear:both;"></div>';
        $output .= '<div id="photoUploadQueue"></div>';
        $output .= "</form>";

        return $output;
    }

    /**
     * Load Required Form
     */
    function load_required_forms( $array = NULL ) {

        if ( $array == NULL )
            $array = $_POST;

        $title = $array['photo_title'];
        $description = $array['photo_description'];
        $tags = $array['photo_tags'];
        $show_collection = $array['is_avatar'] ? 'no' : 'yes';
        
        if ( $array['user'] )
            $p['user'] = $array['user'];
        else
            $p['user'] = userid();

        $p['type'] = "photos";
        {
            $collections = $this->collection->get_collections( $p );
            if ( !$collections ) {
                global $cbcollection;
                $collection_details['collection_name'] = $collection_details['collection_description'] = lang('Untitled Collection');
                $collection_details['collection_tags'] = lang('untitled, collection'); $collection_details['category'] =  array ( $cbcollection->get_default_cid() );
                // Update following to have default values
                $collection_details['broadcast'] = 'public'; $collection_details['allow_comments'] = 'yes'; $collection_details['public_upload'] = 'no';
                
                $collection_insert_id = $cbcollection->create_collection( $collection_details );
                $collection_details['collection_id'] = $collection_insert_id;
                $collections[0] = $collection_details;
                //pr( $collections, true );
            }
            $cl_array = $this->parse_array( $collections );
            $collection = $array['collection_id'];
        }

        $fields =
                array
                    (
                    'name' => array(
                        'title' => lang( 'photo_title' ),
                        'id' => 'photo_title',
                        'name' => 'photo_title',
                        'type' => 'textfield',
                        'value' => cleanForm( $title ),
                        'db_field' => 'photo_title',
                        'required' => 'yes',
                        'invalid_err' => lang( 'photo_title_err' )
                    ),
                    'desc' => array(
                        'title' => lang( 'photo_caption' ),
                        'id' => 'photo_description',
                        'name' => 'photo_description',
                        'type' => 'textarea',
                        'value' => cleanForm( $description ),
                        'db_field' => 'photo_description',
                        'anchor_before' => 'before_desc_compose_box',
                        'required' => 'yes',
                        'invalid_err' => lang( 'photo_caption_err' )
                    ),
                    'tags' => array(
                        'title' => lang( 'photo_tags' ),
                        'id' => 'photo_tags',
                        'name' => 'photo_tags',
                        'type' => 'textfield',
                        'value' => genTags( $tags ),
                        'db_field' => 'photo_tags',
                        'required' => 'yes',
                        'invalid_err' => lang( 'photo_tags_err' )
                    )
        );

        {
            $fields['collection'] = array(
                        'title' => lang( 'collection' ),
                        'id' => 'collection_id',
                        'name' => 'collection_id',
                        'type' => 'dropdown',
                        'value' => $cl_array,
                        'db_field' => 'collection_id',
                        'required' => '',
                        'checked' => $collection,
                        'invalid_err' => lang( 'photo_collection_err' )
                    );
        }
        return $fields;
    }

    function insert_photo( $array = NULL ) {
        global $db, $eh;
        if ( $array == NULL )
            $array = $_POST;

        if ( is_array( $_FILES ) )
            $array = array_merge( $array, $_FILES );

        $this->validate_form_fields( $array );
        if ( !error() ) {
            $forms = $this->load_required_forms( $array );
            $oForms = $this->load_other_forms( $array );
            $FullForms = array_merge( $forms, $oForms );

            foreach ( $FullForms as $field ) {
                $name = formObj::rmBrackets( $field['name'] );
                $val = $array[$name];

                if ( $field['use_func_val'] )
                    $val = $field['validate_function']( $val );

                if ( !empty( $field['db_field'] ) )
                    $query_field[] = $field['db_field'];

                if ( is_array( $val ) ) {
                    $new_val = '';
                    foreach ( $val as $v ) {
                        $new_val .= "#" . $v . "# ";
                    }
                    $val = $new_val;
                }
                if ( !$field['clean_func'] || (!function_exists( $field['clean_func'] ) && !is_array( $field['clean_func'] )) )
                    $val = ($val);
                else
                    $val = apply_func( $field['clean_func'], sql_free( '|no_mc|' . $val ) );

				if(empty($val) && !empty($field['default_value']))
					$val = $field['default_value'];

                if ( !empty( $field['db_field'] ) )
                    $query_val[] = $val;
            }

            $query_field[] = "userid";
            if ( !$array['userid'] ) {
                $userid = userid();
                $query_val[] = $userid;
            } else {
                $query_val[] = $array['userid'];
                $userid = $array['userid'];
            }

            $query_field[] = "date_added";
            $query_val[] = NOW();

            $query_field[] = "owner_ip";
            $query_val[] = $_SERVER['REMOTE_ADDR'];

            $query_field[] = "ext";
            $query_val[] = $array['ext'];

            $query_field[] = "photo_key";
            //$query_val[] = $array['photo_key'];
            $query_val[] = $this->photo_key();

            $query_field[] = "filename";
            $query_val[] = $array['filename'];

            if ( $array['server_url'] && $array['server_url'] != "undefined" ) {
                $query_field[] = "server_url";
                $query_val[] = $array['server_url'];
            }

            if ( $array['folder'] && $array['folder'] != "undefined" ) {
                $query_field[] = "file_directory";
                $query_val[] = $array['folder'];
            }
			
            if ( $array['is_avatar'] == true ) {
              $query_field[] = 'is_avatar';
              $query_val[] = true;
            }
		
            $insert_id = $db->insert( tbl( $this->p_tbl ), $query_field, $query_val );
            $photo = $this->get_photo( $insert_id );
            $this->collection->add_collection_item( $insert_id, $photo['collection_id'] );
			
			/*
			 * EXIF should be added here
			*/
			insert_exif_data( $photo );
			
			/*
			 * Extract colors
			 */
			insert_photo_colors( $photo );
			
            if ( !$array['server_url'] || $array['server_url'] == 'undefined' )
                $this->generate_photos( $photo );


            $eh->flush();
            e( sprintf( lang( "photo_is_saved_now" ), $photo['photo_title'] ), "m" );
            $db->update( tbl( "users" ), array("total_photos"), array("|f|total_photos+1"), " userid='" . $userid . "'" );

            //Adding Photo Feed
            addFeed( array('action' => 'upload_photo', 'object_id' => $insert_id, 'object' => 'photo') );

            return $insert_id;
        }
    }

    /**
     * Update watermark file
     */
    function update_watermark( $file ) {
        if ( empty( $file ) )
            e( lang( "no_watermark_found" ) );
        else {
            $oldW = BASEDIR . "/images/photo_watermark.png";
            if ( file_exists( $oldW ) ) {
                unset( $oldW );
            }
            
            $info = getimagesize( $file['tmp_name'] );
            $width = $info[0];
            $type = $info[2];

            //pr($info,TRUE);

            if ( $type == 3 ) {
                if ( move_uploaded_file( $file['tmp_name'], BASEDIR . "/images/photo_watermark.png" ) ) {
                    $wFile = BASEDIR . "/images/photo_watermark.png";
                    if ( $width > $this->max_watermark_width ) {
                        /* Updating resizing code */
                        $img = new CB_Resizer( $wFile );
                        $img->target = $wFile;
                        $img->_resize( $this->max_watermark_width );
                        $img->save();
                        //$this->createThumb( $wFile, $wFile, 'png', $this->max_watermark_width );
                    }
                }
                e( lang( "watermark_updated" ), "m" );
            } else {
                e( lang( "upload_png_watermark" ) );
            }
        }
    }

    /**
     * Load Other Form
     */
    function load_other_forms( $array = NULL ) {
        if ( $array == NULL )
            $array = $_POST;

        $comments = $array['allow_comments'];
        $broadcast = $array['broadcast'];
        $tagging = $array['allow_tagging'];
        $embedding = $array['allow_embedding'];
        $rating = $array['allow_rating'];
        $rand = $array['photo_key'];

        $Otherfields = array
            (
            'comments' => array(
                'title' => lang( 'comments' ),
                'name' => 'allow_comments',
                'id' => 'allow_comments',
                'db_field' => 'allow_comments',
                'type' => 'radiobutton',
                'value' => array('yes' => lang( 'vdo_allow_comm' ), 'no' => lang( 'vdo_dallow_comm' )),
                'required' => 'no',
                'checked' => $comments,
                'validate_function' => 'yes_or_no',
                'display_function' => 'display_sharing_opt',
                'default_value' => 'yes'
            ),
            /* 'broadcast' => array(
              'title' => lang("vdo_br_opt"),
              'type' => 'radiobutton',
              'name' => 'broadcast',
              'id' => 'broadcast',
              'value' => array("public"=>lang("collect_borad_pub"),"private"=>lang("collect_broad_pri")),
              'checked' => $broadcast,
              'db_field' => 'broadcast',
              'required' => 'no',
              'display_function' => 'display_sharing_opt',
              'default_value'=>'public'
              ), */
            'tagging' => array(
              'title' => lang('Tagging'),
              'type' => 'radiobutton',
              'id' => 'allow_tagging',
              'name' => 'allow_tagging',
              'db_field' => 'allow_tagging',
              'type' => 'radiobutton',
              'value' => array('yes' => lang('Enable photo tagging'),'no' => lang('Disable photo tagging')),
              'checked' => $tagging,
              'validate_function'=>'yes_or_no',
              'display_function' => 'display_sharing_opt',
              'default_value'=>'yes'
              ),
            'embedding' => array(
                'title' => lang( 'vdo_embedding' ),
                'type' => 'radiobutton',
                'name' => 'allow_embedding',
                'id' => 'allow_embedding',
                'db_field' => 'allow_embedding',
                'value' => array('yes' => lang( 'pic_allow_embed' ), 'no' => lang( 'pic_dallow_embed' )),
                'checked' => $embedding,
                'validate_function' => 'yes_or_no',
                'display_function' => 'display_sharing_opt',
                'default_value' => 'yes'
            ),
            'rating' => array(
                'title' => lang( 'rating' ),
                'id' => 'allow_rating',
                'name' => 'allow_rating',
                'type' => 'radiobutton',
                'db_field' => 'allow_rating',
                'value' => array('yes' => lang( 'pic_allow_rating' ), 'no' => lang( 'pic_dallow_rating' )),
                'checked' => $rating,
                'validate_function' => 'yes_or_no',
                'display_function' => 'display_sharing_opt',
                'default_value' => 'yes'
            )
        );

        //pr($Otherfields,TRUE);
        return $Otherfields;
    }

    /**
     * This will return a formatted array
     * return @Array
     * Array Format: Multidemsional
     * Array ( [photo_id] => array( ['field_name'] => 'value' ) )
     */
    function return_formatted_post( $arr ) {
        $photoID = '';
        foreach ( $_POST as $key => $value ) {
            $parts = explode( '_', $key );
            $total = count( $parts );
            $id = $parts[$total - 1];
            $name = array_splice( $parts, 0, $total - 1 );
            $name = implode( "_", $name );

            if ( $photoID != $id ) {
                $values = array();
                $photoID = $id;
            }

            if ( is_numeric( $id ) ) {
                if ( strpos( $key, $id ) !== FALSE ) {
                    $values[$name] = $value;
                    $PhotosArray[$id] = $values;
                }
            }
        }

        return $PhotosArray;
    }

    /**
     * This will be used to mutliple photos
     * at once.
     * Single update will be different.
     */
    function update_multiple_photos( $arr ) {
        global $db, $cbcollection, $eh;

        foreach ( $arr as $id => $details ) {
            if ( is_array( $details ) ) {
                $i = 0;
                $query = "UPDATE " . tbl( 'photos' ) . " SET ";
                foreach ( $details as $key => $value ) {
                    $i++;
                    $query .= "$key = '$value'";
                    if ( $i < count( $details ) )
                        $query .= " , ";
                }

                $query .= " WHERE " . tbl( 'photos.photo_id' ) . " = '$id'";

                $db->Execute( $query );
                $cbcollection->add_collection_item( $id, $details['collection_id'] );
            }
        }
        $eh->flush();
    }

    /**
     * Used to parse collections dropdown
     */
    function parse_array( $array ) {
        if ( is_array( $array ) ) {
            foreach ( $array as $key => $v ) {
                $cl_arr[$v['collection_id']] = $v['collection_name'];
            }
            return $cl_arr;
        } else {
            return false;
        }
    }

    /**
     * Used to create filename of photo
     */
    function create_filename() {
        $filename = time() . RandomString( 6 );
        return $filename;
    }

    /**
     * Construct extensions for SWF
     */
    function extensions() {
        $exts = $this->exts;
        $list = '';
        foreach ( $exts as $ext ) {
            $list .= "*." . $ext . ";";
        }
        return $list;
    }

    /**
     * Function used to validate form fields
     */
    function validate_form_fields( $array = NULL ) {
        $reqFileds = $this->load_required_forms( $array );

        if ( $array == NULL )
            $array = $_POST;

        if ( is_array( $_FILES ) )
            $array = array_merge( $array, $_FILES );


        $otherFields = $this->load_other_forms( $array );

        $photo_fields = array_merge( $reqFileds, $otherFields );

        validate_cb_form( $photo_fields, $array );
    }

    /**
     * Update Photo
     */
    function update_photo( $array = NULL ) {
        global $db;

        if ( $array == NULL )
            $array = $_POST;
        $this->validate_form_fields( $array );
        $pid = $array['photo_id'];
        $cid = $this->get_photo_field( $pid, 'collection_id' );

        if ( !error() ) {
            $reqFields = $this->load_required_forms( $array );
            $otherFields = $this->load_other_forms( $array );

            $fields = array_merge( $reqFields, $otherFields );

            foreach ( $fields as $field ) {
                $name = formObj::rmBrackets( $field['name'] );
                $val = $array[$name];

                if ( $field['use_func_val'] )
                    $val = $field['validate_function']( $val );


                if ( !empty( $field['db_field'] ) )
                    $query_field[] = $field['db_field'];

                if ( is_array( $val ) ) {
                    $new_val = '';
                    foreach ( $val as $v ) {
                        $new_val .= "#" . $v . "# ";
                    }
                    $val = $new_val;
                }
                if ( !$field['clean_func'] || (!function_exists( $field['clean_func'] ) && !is_array( $field['clean_func'] )) )
                    $val = ($val);
                else
                    $val = apply_func( $field['clean_func'], sql_free( '|no_mc|' . $val ) );

                if ( !empty( $field['db_field'] ) )
                    $query_val[] = $val;
            }

            if ( has_access( 'admin_access', TRUE ) ) {
                if ( isset( $array['views'] ) ) {
                    $query_field[] = 'views';
                    $query_val[] = $array['views'];
                }

                if ( isset( $array['total_comments'] ) ) {
                    $query_field[] = "total_comments";
                    $query_val[] = $array['total_comments'];
                }

                if ( isset( $array['total_favorites'] ) ) {
                    $query_field[] = "total_favorites";
                    $query_val[] = $array['total_favorites'];
                }

                if ( isset( $array['downloaded'] ) ) {
                    $query_field[] = "downloaded";
                    $query_val[] = $array['downloaded'];
                }

                if ( isset( $array['voters'] ) ) {
                    $query_field[] = "voters";
                    $query_val[] = $array['voters'];
                }
            }

            if ( !error() ) {
                if ( !userid() )
                    e( lang( "you_not_logged_in" ) );
                elseif ( !$this->photo_exists( $pid ) )
                    e( lang( "photo_not_exists" ) );
                elseif ( $this->get_photo_owner( $pid ) != userid() && !has_access( 'admin_access', TRUE ) )
                    e( lang( "cant_edit_photo" ) );
                else {
                    if ( $cid != $array['collection_id'] ) {
                        $this->collection->change_collection( $array['collection_id'], $pid, $cid );
                    }

                    $db->update( tbl( 'photos' ), $query_field, $query_val, " photo_id='$pid'" );
                    e( lang( "photo_updated_successfully" ), "m" );
                }
            }
        }
    }

    /**
     * Used to get image type
     * t = Thumb 
     * m = Medium 
     * l = Large
     * |= Updated on May 19th, 2012 =|
     * Now we also have custom dimensions for photos. 
     */
    function get_image_type( $name ) {
        if ( empty( $name ) )
            return false;
        else {
            // Removing extension from $name if exists, making sure it's ungreedy and starts from end
            $name = preg_replace('/(\.['.implode('|',$this->exts).']+?)$/', '', $name );
            // Clipbucket photos filename and thumb code is joined using '_'
            $first_occurence = strpos( $name, '_' );
            // Checking if we have found any occurence
            if ( $first_occurence ) {
                // Adding 1 to remove the underscore of Clipbucket
                $name = substr( $name, $first_occurence+1);
                return $name;
            }
        }
    }

    /**
     * Used to get image file
     */
    function get_image_file( $pid, $size = 't', $multi = false, $assign = NULL, $with_path = true, $with_orig = false ) {
        
        return get_image_url( $pid, $size, $multi, $assign, $with_path, $with_orig );
        
        $params = array("details" => $pid, "size" => $size, "multi" => $multi, "assign" => $assign, "with_path" => $with_path, "with_orig" => $with_orig);
        return $this->getFileSmarty( $params );
    }

    /**
     * This will become a Smarty function.
     * I am writting this to eliminate the possiblitles
     * of distort pictures
     */
    function getFileSmarty( $p ) {
        global $Cbucket;
        $details = $p['details'];
        $output = $p['output'];
        if ( empty( $details ) ) {
            return $this->default_thumb( $size, $output );
        } else {
            //Calling Custom Functions
            if ( count( $Cbucket->custom_get_photo_funcs ) > 0 ) {

                foreach ( $Cbucket->custom_get_photo_funcs as $funcs ) {
                    if ( function_exists( $funcs ) ) {
                        $func_returned = $funcs( $p );
                        if ( $func_returned )
                            return $func_returned;
                    }
                }
            }
            
            if (  empty( $p['size'] ) ) {
                $p['size'] = 't';
            }
            
            if ( !empty( $p['code']) ) {
                $p['size'] = $p['code'];
            }
            
			if ( $details['is_mature'] == 'yes' && !userid() ) {
				return get_mature_thumb( $details, $size, $output );
			}
			
            if ( $p['with_path'] === FALSE )
                $p['with_path'] = FALSE; else
                $p['with_path'] = TRUE;
            $with_path = $p['with_path'];
            $with_orig = $p['with_orig'] ? $p['with_orig'] : FALSE;

            if ( !is_array( $details ) )
                $photo = $this->get_photo( $details );
            else
                $photo = $details;

            if ( empty( $photo['photo_id'] ) || empty( $photo['photo_key'] ) )
                return $this->default_thumb( $size, $output );
            else {
                if ( !empty( $photo['filename'] ) && !empty( $photo['ext'] ) ) {
                    /* Enhacing the code to work with date folder structure */
                    $date_dir = get_photo_date_folder( $photo );
                    if ( $date_dir ) {
                      $date_dir .= '/';	
                    }
					
                    $files = glob( PHOTOS_DIR . "/". $date_dir . $photo['filename'] . "*." . $photo['ext'] );
                    if ( !empty( $files ) && is_array( $files ) ) {

                        foreach ( $files as $file ) {
                            $file_parts = explode( "/", $file );
                            $thumb_name = $file_parts[count( $file_parts ) - 1];

                            $type = $this->get_image_type( $thumb_name );

                            if ( $with_orig ) {
                                if ( $with_path )
                                    $thumbs[] = PHOTOS_URL . "/". $date_dir . $thumb_name;
                                else
                                    $thumbs[] = $thumb_name;
                            }
                            elseif ( !empty( $type ) ) {
                                if ( $with_path )
                                    $thumbs[] = PHOTOS_URL . "/" .$date_dir. $thumb_name;
                                else
                                    $thumbs[] = $thumb_name;
                            }
                        }
						
						if ( !$thumbs ) {
							return $this->default_thumb( $size, $output );	
						}
						
                        if ( empty( $p['output'] ) || $p['output'] == 'non_html' ) {
                            if ( $p['assign'] && $p['multi'] ) {
                                assign( $p['assign'], $thumbs );
                            } elseif ( !$p['assign'] && $p['multi'] ) {
                                return $thumbs;
                            } else {

                                $size = "_" . $p['size'];

                                $return_thumb = array_find( $photo['filename'] . $size, $thumbs );

                                if ( empty( $return_thumb ) ) {
                                    $this->default_thumb( $size, $output );
                                } else {
                                    if ( $p['assign'] != NULL )
                                        assign( $p['assign'], $return_thumb );
                                    else
                                        return $return_thumb;
                                }
                            }
                        }

                        if ( $p['output'] == 'html' ) {
                            
                            /* Creating output here is dropped. Now we'll create an array of
                             * attributes and pass to cb_output_img_tag, that function will
                             *  create the actual HTML IMG tag
                             */
                            $size = "_" . $p['size'];

                            $src = array_find( $photo['filename'] . $size, $thumbs );
                            $attrs = array();
                            if ( empty( $src ) ) {
                                return $this->default_thumb( $size, $output );
                            } else {
                                $src = $src;
                            }
                            
                            if ( phpversion < '5.2.0' ) {
                                global $json; $js = $json;
                            }

                            if ( !empty( $js ) ) {
                                $imgDetails = $js->json_decode( $photo['photo_details'], true );
                            } else {
                                $imgDetails = json_decode( $photo['photo_details'], true );
                            }
                            
                            if ( empty( $imgDetails ) || empty( $imgDetails[$p['size']] ) ) {
                                $dem = getimagesize( str_replace( PHOTOS_URL, PHOTOS_DIR, $src ) );
                                $width = $dem[0];
                                $height = $dem[1];
                                /* UPDATEING IMAGE DETAILS */
                                $this->update_image_details( $details );
                            } else {
                                $width = $imgDetails[$p['size']]['width'];
                                $height = $imgDetails[$p['size']]['height'];
                            }

                            if ( USE_PHOTO_TAGGING && THIS_PAGE == 'view_item' ) {
                                $id = $this->get_selector_id()."_".$photo['photo_id'];
                            } else {
                                if ( $p['id'] ) {
                                    $id = mysql_clean( $p['id'] )."_".$photo['photo_id'];
                                } else {
                                    $id = $this->get_selector_id()."_".$photo['photo_id'];
                                }                               
                            }
                            $attrs['id'] = $id;
                            
                            if ( $p['class'] ) {
                                $attrs['class'] = mysql_clean( $p['class'] );
                            }
                            
                            if ( $p['align'] ) {
                                $attrs['align'] = $p['align'];
                            }
                            
                            if ( ($p['width'] && is_numeric( $p['width'] )) && ($p['height'] && is_numeric( $p['height'] )) ) {
                                $height = $p['height'];
                                $width = $p['width'];
                            } elseif ( $p['width'] && is_numeric( $p['width'] ) ) {
                                $height = round( $p['width'] / $width * $height );
                                $width = $p['width'];
                            } elseif ( $p['height'] && is_numeric( $p['height'] ) ) {
                                $width = round( $p['height'] * $width / $height );
                                $height = $p['height'];
                            }
                            $attrs['width'] = $width; $attrs['height'] = $height;
                            
                            if ( $p['title'] ) {
                                $title = mysql_clean( $p['title'] );
                            } else {
                                $title = $photo['photo_title'];
                            }
                            $attrs['title'] = $title;
                            
                            if ( $p['alt'] ) {
                                $alt = mysql_clean( $p['alt'] );
                            } else {
                                $alt = mysql_clean( TITLE.' - '.$photo['photo_title'] );
                            }
                            $attrs['alt'] = $alt;
                            
                            $anchor_p = array("place" => 'photo_thumb', "data" => $photo);
                            ANCHOR( $anchor_p );
                            
                            if ( $p['style'] ) {
                                $attrs['style'] = $p['style'];
                            }
                            
                            if ( $p['extra'] ) {
                                $attrs['extra'] = $p['extra'];
                            }
                            
                            $img = cb_output_img_tag( $src, $attrs );
                            
                            if ( $p['assign'] ) {
                                assign( $p['assign'], $img );
                            } else {
                                return $img;
                            }
                        }
                    } else {
                        return $this->default_thumb( $size, $output );
                    }
                }
            }
        }
    }

    /**
     * Will be called when collection is being deleted
     * This will make photos in the collection orphan
     * User will be able to access them in orphan photos
     */
    function make_photo_orphan( $details, $pid = NULL ) {
        global $db;
        if ( !is_array( $details ) && is_numeric( $details ) ) {
            $c = $this->collection->get_collection( $details );
            $cid = $c['collection_id'];
        }
        else
            $cid = $details['collection_id'];
        if ( !empty( $pid ) )
            $cond = " AND photo_id = $pid";

        $db->update( tbl( 'photos' ), array('collection_id'), array('0'), " collection_id = $cid $cond" );
    }

    /**
     * This will create download button for
     * photo
     */
    function download_button( $params ) {
        $output = '';
        if ( !is_array( $params['details'] ) )
            $p = $this->get_photo( $params['details'] );
        else
            $p = $params['details'];

        $text = lang( 'download_photo' );
        if ( config( 'photo_download' ) == 1 && !empty( $p ) ) {
            if ( $params['return_url'] ) {
                $output = $this->photo_links( $p, 'download_photo' );
                if ( $params['assign'] ) {
                    assign( $params['assign'], $output );
                    return;
                }
                else
                    return $output;
            }

            if ( $params['output'] == '' || $params['output'] == 'link' ) {
                $output .= "<a href='" . $this->photo_links( $p, 'download_photo' ) . "'";
                if ( $params['id'] )
                    $output .= " id = '" . $params['id'] . "'";
                if ( $params['class'] )
                    $output .= " class = '" . $params['class'] . "'";
                if ( $params['target'] )
                    $output .= " target = '" . $params['target'] . "'";
                if ( $params['style'] )
                    $output .= " style = '" . $params['style'] . "'";
                if ( $params['title'] )
                    $output .= " title = '" . $params['title'] . "'";
                if ( $params['relation'] )
                    $output .= " rel = '" . $params['relation'] . "'";
                $output .= ">" . $text . "</a>";
            }

            if ( $params['output'] == "div" ) {
                $link = "'" . $this->photo_links( $p, 'download' ) . "'";
                $new_window = $params['new_window'] ? "'new'" : "'same'";
                $output .= '<div onClick = "openURL(' . $link . ',' . $new_window . ')"';
                if ( $params['id'] )
                    $output .= " id = '" . $params['id'] . "'";
                if ( $params['class'] )
                    $output .= " class = '" . $params['class'] . "'";
                if ( $params['style'] )
                    $output .= " style = '" . $params['style'] . "'";
                if ( $params['align'] )
                    $output .= " align = '" . $params['align'] . "'";
                $output .= '>' . $text . '</div>';
            }

            if ( $params['assign'] )
                assign( $params['assign'], $output );
            else
                return $output;
        }
    }

    /**
     * Used to load upload more photos
     * This button will only appear if collection type is photos
     * and user logged-in is Collection Owner
     */
    function upload_photo_button( $arr ) {
        $cid = $arr['details'];
        //pr($arr,TRUE);
        $text = lang( "add_more" );
        $result = '';
        if ( !is_array( $cid ) )
            $details = $this->collection->get_collection( $cid );
        else
            $details = $cid;

        if ( $details['type'] == 'photos' && $details['userid'] == user_id() ) {
            $output = $arr['output'];
            if ( $arr['return_url'] ) {
                $result = $this->photo_links( $details, 'upload_more' );
                if ( $arr['assign'] ) {
                    assign( $arr['assign'], $result );
                    return;
                }
                else
                    return $result;
            }
            
            if ( empty( $output ) || $output == "button" ) {
                $result .= '<button type="button"';
                $link = "'" . $this->photo_links( $details, 'upload_more' ) . "'";
                if ( $arr['new_window'] || $arr['target'] == "_blank" )
                    $new_window = "'new'";
                else
                    $new_window = "'same'";

                $result .= 'onClick = "openURL(' . $link . ',' . $new_window . ')"';
                if ( $arr['id'] )
                    $result .= ' id = "' . $arr['id'] . '"';
                if ( $arr['class'] )
                    $result .= ' class = "' . $arr['class'] . '"';
                if ( $arr['title'] )
                    $result .= ' title = "' . $arr['title'] . '"';
                if ( $arr['style'] )
                    $result .= ' style = "' . $arr['style'] . '"';
                if ( $arr['extra'] )
                    $result .= mysql_clean( $arr['extra'] );

                $result .= ">" . $text . "</button>";
            }

            if ( $output == "div" ) {
                $result .= '<div ';
                $link = "'" . $this->photo_links( $details, 'upload_more' ) . "'";
                if ( $arr['new_window'] || $arr['target'] == "_blank" )
                    $new_window = "'new'";
                else
                    $new_window = "'same'";
                $result .= 'onClick = "openURL(' . $link . ',' . $new_window . ')"';
                if ( $arr['id'] )
                    $result .= ' id = "' . $arr['id'] . '"';
                if ( $arr['align'] )
                    $result .= ' align = "' . $arr['align'] . '"';
                if ( $arr['class'] )
                    $result .= ' class = "' . $arr['class'] . '"';
                if ( $arr['title'] )
                    $result .= ' title = "' . $arr['title'] . '"';
                if ( $arr['style'] )
                    $result .= ' style = "' . $arr['style'] . '"';
                if ( $arr['extra'] )
                    $result .= mysql_clean( $arr['extra'] );

                $result .= ">" . $text . "</div>";
            }

            if ( $output == "link" ) {
                $result .= '<a href="' . $this->photo_links( $details, 'upload_more' ) . '"';

                if ( $arr['new_window'] )
                    $result .= ' target = "_blank"';
                elseif ( $arr['target'] )
                    $result .= ' target = "' . $arr['target'] . '"';

                if ( $arr['id'] )
                    $result .= ' id = "' . $arr['id'] . '"';
                if ( $arr['align'] )
                    $result .= ' align = "' . $arr['align'] . '"';
                if ( $arr['class'] )
                    $result .= ' class = "' . $arr['class'] . '"';
                if ( $arr['title'] )
                    $result .= ' title = "' . $arr['title'] . '"';
                if ( $arr['style'] )
                    $result .= ' style = "' . $arr['style'] . '"';
                if ( $arr['extra'] )
                    $result .= mysql_clean( $arr['extra'] );

                $result .= ">" . $text . "</a>";
            }

            if ( $arr['assign'] )
                assign( $arr['assign'], $result );
            else
                return $result;
        } else {
            return FALSE;
        }
    }

    /**
     * used to create links
     */
    function photo_links( $details, $type ) {
        if ( empty( $type ) )
            return BASEURL;
        else {
            switch ( $type ) {
                case "upload": {
                        if ( SEO == "yes" )
                            $link = BASEURL . "/photo_upload";
                        else
                            $link = BASEURL . "/photo_upload.php";
                    }
                    break;

                case "upload_more": {
                        if ( SEO == "yes" )
                            $link = BASEURL . "/photo_upload/" . $this->encode_key( $details['collection_id'] );
                        else
                            $link = BASEURL . "/photo_upload.php?collection=" . $this->encode_key( $details['collection_id'] );
                    }
                    break;

                case "download_photo":
                case "download": {
                        return BASEURL . "/download_photo.php?download=" . $this->encode_key( $details['photo_key'] );
                } break;

                case "view_item":
                case "view_photo": {
                        return $this->collection->collection_links( $details, 'view_item' );
                    }
                    break;
					
				case "make_avatar": case "ma": {
					global $userquery;
					if ( $details['collection_id'] == $userquery->udetails['avatar_collection']) {
						$set_avatar = '&set_avatar=1';
					}
					
					$link = BASEURL.'/edit_account.php?pid='.$this->encode_key(RandomString(12).$details['photo_key']).'&mode=make_avatar&u='.$details['userid'].$set_avatar;	
				}break;
				
				case 'exif_data': case 'exif': {
					if ( SEO == 'yes' ) {
						$link = BASEURL.'/exif.php?id='.$details['photo_key'];
					} else {
						$link = BASEURL.'/exif.php?id='.$details['photo_key'];
					}
					
					return $link;
				} break;
            }
            return $link;
        }
    }

    /**
     * Used to return default thumb
     */
    function default_thumb( $size = NULL, $output = NULL ) {
        if ( $size != "_t" && $size != "_m" )
            $size = "_m";

        if ( file_exists( TEMPLATEDIR . "/images/thumbs/no-photo" . $size . ".png" ) )
            $path = TEMPLATEURL . "/images/thumbs/no-photo" . $size . ".png";
        else
            $path = PHOTOS_URL . "/no-photo" . $size . ".png";

        if ( !empty( $output ) && $output == "html" ) {
            return cb_output_img_tag( $path );
        } else {
            return $path;
        }
    }

    /**
     * Used to add comment
     */
    function add_comment( $comment, $obj_id, $reply_to = NULL, $force_name_email = false ) {
        global $myquery;
        $photo = $this->get_photo( $obj_id );
        if ( empty( $photo ) )
            e( "photo_not_exist" );
        else {
            $ownerID = $photo['userid'];
            $photoLink = $this->photo_links( $photo, 'view_item' );
            $comment = $myquery->add_comment( $comment, $obj_id, $reply_to, 'p', $ownerID, $photoLink, $force_name_email );
            if ( $comment ) {
                $this->update_total_comments( $obj_id );
            }
            return $comment;
        }
    }

    /**
     * Function used to update total comments of collection 
     */
    function update_total_comments( $pid ) {
        global $db;
        $count = $db->count( tbl( "comments" ), "comment_id", " type = 'p' AND type_id = '$pid'" );
        $db->update( tbl( 'photos' ), array("total_comments", "last_commented"), array($count, now()), " photo_id = '$pid'" );
    }

    /**
     * Used to check if collection can add
     * photos or not
     */
    function is_addable( $cid ) {
        if ( !is_array( $cid ) )
            $details = $this->collection->get_collection( $cid );
        else
            $details = $cid;

        if ( empty( $details ) ) {
            return false;
        } else {
            if ( ($details['active'] == 'yes' || $details['broadcast'] == 'public') && $details['userid'] == userid() )
                return true;
            elseif ( $details['userid'] == userid() )
                return true;
            else
                return false;
        }
    }

    /**
     * Used to display photo voterts details.
     * User who rated, how many stars and when user rated
     */
    function photo_voters( $id, $return_array = FALSE, $show_all = FALSE ) {
        global $json;
        $p = $this->get_photo( $id );
        if ( (!empty( $p ) && $p['userid'] == userid()) || $show_all === TRUE ) {
            global $userquery;
            $voters = $p['voters'];
            if ( phpversion() < "5.2.0" )
                $voters = $json->json_decode( $voters, TRUE );
            else
                $voters = json_decode( $voters, TRUE );

            if ( !empty( $voters ) ) {
                if ( $return_array )
                    return $voters;
                else {
                    foreach ( $voters as $id => $details ) {
                        $username = get_username( $id );
                        $output = "<li id='user" . $id . $p['photo_id'] . "' class='PhotoRatingStats'>";
                        $output .= "<a href='" . $userquery->profile_link( $id ) . "'>$username</a>";
                        $output .= " rated <strong>" . $details['rate'] / 2 . "</strong> stars <small>(";
                        $output .= niceTime( $details['time'] ) . ")</small>";
                        $output .= "</li>";
                        echo $output;
                    }
                }
            }
        } else
            return false;
    }

    /**
     * Used to get current rating
     */
    function current_rating( $id ) {
        global $db;

        if ( !is_numeric( $id ) )
            $result = $db->select( tbl( 'photos' ), 'userid,allow_rating,rating,rated_by,voters', " photo_key = " . $id . "" );
        else
            $result = $db->select( tbl( 'photos' ), 'userid,allow_rating,rating,rated_by,voters', " photo_id = " . $id . "" );

        if ( $result )
            return $result[0];
        else
            return false;
    }

    /**
     * Used to rate photo
     */
    function rate_photo( $id, $rating ) {
        global $db, $json;

        if ( !is_numeric( $rating ) || $rating <= 9 )
            $rating = 0;
        if ( $rating >= 10 )
            $rating = 10;

        $c_rating = $this->current_rating( $id );
        $voters = $c_rating['voters'];

        $new_rate = $c_rating['rating'];
        $rated_by = $c_rating['rated_by'];

        if ( phpversion < '5.2.0' )
            $voters = $json->json_decode( $voters, TRUE );
        else
            $voters = json_decode( $voters, TRUE );

        if ( !empty( $voters ) )
            $already_voted = array_key_exists( userid(), $voters );

        if ( !userid() )
            e( lang( "please_login_to_rate" ) );
        elseif ( userid() == $c_rating['userid'] && !config( 'own_photo_rating' ) )
            e( lang( "you_cannot_rate_own_photo" ) );
        elseif ( !empty( $already_voted ) )
            e( lang( "you_hv_already_rated_photo" ) );
        elseif ( $c_rating['allow_rating'] == 'no' || !config( 'photo_rating' ) )
            e( lang( "photo_rate_disabled" ) );
        else {
            $voters[userid()] = array('rate' => $rating, 'time' => NOW());
            if ( phpversion < '5.2.0' )
                $voters = $json->json_encode( $voters );
            else
                $voters = json_encode( $voters );

            $t = $c_rating['rated_by'] * $c_rating['rating'];
            $rated_by = $c_rating['rated_by'] + 1;
            $new_rate = ($t + $rating) / $rated_by;
            $db->update( tbl( 'photos' ), array('rating', 'rated_by', 'voters'), array("$new_rate", "$rated_by", "|no_mc|$voters"), " photo_id = " . $id . "" );
            $userDetails = array(
                "object_id" => $id,
                "type" => "photo",
                "time" => now(),
                "rating" => $rating,
                "userid" => userid(),
                "username" => username()
            );
            /* Updating user details */
            update_user_voted( $userDetails );
            e( lang( "thnx_for_voting" ), "m" );
        }

        $return = array("rating" => $new_rate, "rated_by" => $rated_by, 'total' => 10, "id" => $id, "type" => "photo", "disable" => "disabled");
        return $return;
    }

    /**
     * Used to generate different
     * embed codes
     */
    function generate_embed_codes( $p ) {
        $details = $p['details'];
        $type = $p['type'];
        $size = $p['size'] ? $p['size'] : 'm';

        if ( is_array( $details ) )
            $photo = $details;
        else
            $photo = $this->get_photo( $detials );

        switch ( $type ) {
            case "html": {
                    if ( $p['with_url'] )
                        $code .= "&lt;a href='" . $this->collection->collection_links( $photo, 'view_item' ) . "' target='_blank'&gt;";
                    $code .= "&lt;img src='" . $this->get_image_file( $photo, $size ) . "' title='" . $photo['photo_title'] . "' alt='" . $photo['photo_title'] . "&nbsp;" . TITLE . "' /&gt;";
                    if ( $p['with_url'] )
                        $code .= "&lt;/a&gt;";
                }
                break;

            case "forum": {
                    if ( $p['with_url'] )
                        $code .= "&#91;URL=" . $this->collection->collection_links( $photo, 'view_item' ) . "&#93;";
                    $code .= "&#91;IMG&#93;" . $this->get_image_file( $photo, $size ) . "&#91;/IMG&#93;";
                    if ( $p['with_url'] )
                        $code .= "&#91;/URL&#93;";
                }
                break;

            case "email": {
                    $code .= $this->collection->collection_links( $photo, 'view_item' );
                }
                break;

            case "direct": {
                    $code .= $this->get_image_file( $photo, "o" );
                }
                break;

            default:
                return false;
        }

        return $code;
    }

    /**
     * Embed Codes
     */
    function photo_embed_codes( $newArr ) {
        if ( empty( $newArr['details'] ) ) {
            echo "<div class='error'>" . e( lang( "need_photo_details" ) ) . "</div>";
        } elseif ( $newArr['details']['allow_embedding'] == 'no' ) {
            echo "<div class='error'>" . e( lang( "embedding_is_disabled" ) ) . "</div>";
        } else {
            $t = $newArr['type'];
            if ( is_array( $t ) )
                $types = $t;
            elseif ( $t == 'all' )
                $types = $this->embed_types;
            else
                $types = explode( ',', $t );

            foreach ( $types as $type ) {
                $type = strtolower( $type );
                if ( in_array( $type, $this->embed_types ) ) {
                    $type = str_replace( ' ', '', $type );
                    $newArr['type'] = $type;
                    $codes[] = array("name" => ucwords( $type ), "type" => $type, "code" => $this->generate_embed_codes( $newArr ));
                }
            }

            if ( $newArr['assign'] )
                assign( mysql_clean( $newArr['assign'] ), $codes );
            else
                return $codes;
        }
    }

    /**
     * Used encode photo key
     */
    function encode_key( $key ) {
        return base64_encode( serialize( $key ) );
    }

    /**
     * Used encode photo key
     */
    function decode_key( $key ) {
        return unserialize( base64_decode( $key ) );
    }

    function incrementDownload( $Array ) {
        global $db;
        if ( !isset( $_COOKIE[$Array['photo_id'] . "_downloaded"] ) ) {
            $db->update( tbl( 'photos' ), array('downloaded'), array('|f|downloaded+1'), ' photo_id = "' . $Array['photo_id'] . '"' );
            setcookie( $Array['photo_id'] . "_downloaded", NOW(), time() + 1800 );
        }
    }

    function download_photo( $key ) {

        $file = $this->ready_photo_file( $key );

        if ( $file ) {

            if ( $file['details']['server_url'] ) {
                $url = dirname( dirname( $file['details']['server_url'] ) );
                header( 'location:' . $url . '/download_photo.php?file=' . $file['details']['filename']
                        . '.' . $file['details']['ext'] . '&folder=' . $file['details']['file_directory']
                        . '&title=' . urlencode( $file['details']['photo_title'] ) );

                $this->incrementDownload( $p );
                return true;
            }
            $p = $file['details'];
            $mime_types = array();
            $mime_types['gif'] = 'image/gif';
            $mime_types['jpe'] = 'image/jpeg';
            $mime_types['jpeg'] = 'image/jpeg';
            $mime_types['jpg'] = 'image/jpeg';
            $mime_types['png'] = 'image/png';

            if ( array_key_exists( $p['ext'], $mime_types ) ) {
                $mime = $mime_types[$p['ext']];
                if ( file_exists( $file['file_dir'] ) ) {
                    if ( is_readable( $file['file_dir'] ) ) {
                        $size = filesize( $file['file_dir'] );
                        if ( $fp = @fopen( $file['file_url'], 'r' ) ) {
                            $this->incrementDownload( $p );
                            // sending the headers
                            /* Alternate Download Method
                              header('Content-Type: application/octet-stream');
                              header("Content-Length: $size");
                              header("Content-Disposition: attachment; filename=\"".$p['photo_title'].".".$p['ext']."\"");
                              header('Content-Transfer-Encoding: binary');
                              header('Pragma: public');
                              ob_clean();
                              flush();
                              readfile($photo_file); */


                            header( "Content-type: $mime" );
                            header( "Content-Length: $size" );
                            header( "Content-Disposition: attachment; filename=\"" . $p['photo_title'] . "." . $p['ext'] . "\"" );
                            // send the file content
                            fpassthru( $fp );
                            // close the file
                            fclose( $fp );
                            // and quit
                            exit;
                        }
                    } else {
                        e( lang( "photo_not_readable" ) );
                    }
                } else {
                    e( lang( "photo_not_exist" ) );
                }
            } else {
                e( lang( "wrong_mime_type" ) );
            }
        } else
            return false;
    }

    /**
     * Ready photo for downloading
     */
    function ready_photo_file( $pid ) {
        $photo = $this->get_photo( $pid );
        if ( empty( $photo ) )
            e( lang( "photo_not_exist" ) );
        else {
            if ( !$this->collection->is_viewable( $photo['collection_id'] ) )
                return false;
            else {
                $filename = $this->get_image_file( $photo['photo_id'], 'o', FALSE, FALSE, FALSE );
                $returnArray = array(
                    "file_dir" => PHOTOS_DIR . "/". get_photo_date_folder( $photo ) ."/" . $filename,
                    "file_url" => PHOTOS_URL . "/". get_photo_date_folder( $photo ) ."/" . $filename,
                    "filename" => $filename,
                    "details" => $photo
                );
                return $returnArray;
            }
        }
    }

    /**
     * Used to perform photo actions
     */
    function photo_actions( $action, $id ) {
        global $db;

        switch ( $action ) {
            case "activate":
            case "activation":
            case "ap": {
                    $db->update( tbl( $this->p_tbl ), array("active"), array("yes"), " photo_id = $id" );
                    e( lang( "photo_activated" ), "m" );
                }
                break;

            case "deactivate":
            case "deactivation":
            case "dap": {
                    $db->update( tbl( $this->p_tbl ), array("active"), array("no"), " photo_id = $id" );
                    e( lang( "photo_deactivated" ), "m" );
                }
                break;

            case "make_featured":
            case "feature_photo":
            case "fp": {
                    $db->update( tbl( $this->p_tbl ), array("featured"), array("yes"), " photo_id = $id" );
                    e( lang( "photo_featured" ), "m" );
                }
                break;

            case "make_unfeatured":
            case "unfeature_photo":
            case "ufp": {
                $db->update( tbl( $this->p_tbl ), array("featured"), array("no"), " photo_id = $id" );
                e( lang( "photo_unfeatured" ), "m" );
            }
            break;
            case 'remove_mature':
            case 'remove_mature_flag':
            case 'rm': {
                $db->update( tbl( $this->p_tbl ), array("is_mature"), array("no"), " photo_id = $id" );
                e( lang( "Mature flag has been removed" ), "m" );
            }
            break;
        
            case 'add_mature':
            case 'add_mature_flag':
            case 'am': {
                $db->update( tbl( $this->p_tbl ), array("is_mature"), array("yes"), " photo_id = $id" );
                e( lang( "Mature flag has been added" ), "m" );
            }
        }
    }
    
    function get_selector_id () {
        return $this->selector_id;
    }
    
    function set_selector_id ( $id ) {
        return $this->selector_id = $id;
    }
    
    function get_tagger_config ( $name ) {
        return $this->tagger_configs[$name] ? $this->tagger_configs[$name] : false;
    }
    
    function load_default_tagger_configs($json=false) {
        //Following are default tagger configs, which user should
        // have control over.
        $defaults = array(

            'autoComplete' => false,
            'autoCompleteOptions' => array(),

            'showLabels' => true,
            'labelWrapper' => null,
            'labelLinksNew' => false,
            'makeString' => true,
            'makeStringCSS' => false,

            'defaultTags' => null,
            'wrapDeleteLinks' => true,
            'use_percentage' => true,
            'use_arrows' => true,
			
			'buttonWrapper' => null,
			'addIcon' => true,
			
            'phrases' => array(
                'tagging_disabled' => lang('Tagging is disabled'),
                'start_tagging' => lang('Tag Photo'),
                'stop_tagging' => lang('Done Tagging'),
                'cancel_tag' => lang('Cancel'),
                'save_tag' => lang('Save'),
                'saving_tag' => lang('Saving ..'),
                'empty_tag' => lang('Input is empty'),
                'remove_tag' => lang('Remove Tag'),
                'confirm_remove_tag' => lang('Confirm Tag Removal ?'),
                'pending_tag' => lang('Pending')
            )
        );
			
        if ( $json === true ):
            return json_encode($defaults);
        else:
            return $defaults;
        endif;
    }
    
    function set_default_tagger_configs() {
        $configs = $this->load_default_tagger_configs();
        return $this->tagger_configs = $configs;
    }
    
    function get_default_tagger_configs() {
        return $this->tagger_configs;
    }

    /**
     * This function loads photo tagger and it's default tags
     * @global OBJECT $userquery
     * @return ARRAY 
     */
    function load_tagging() {
        $args = func_get_args(); $photo = $args[0];

        // If photo tagging is disabled by admin return false
        if ( USE_PHOTO_TAGGING != true ) {
            return false;
        } else if ( !$photo || empty($photo) ) {
            return false;
        } else {
            global $userquery;
            if ( !empty($args[1]) || $args[1] != '' || is_null( $args[1] ) ) {
                $options = cb_parse_args_string($args[1]);
            }

            if ( is_array($options) && !empty($options)) {
                $options = array_merge($this->load_default_tagger_configs(), $options );
            } else {
                $options = $this->get_default_tagger_configs();
            }

            $options['allowTagging'] = $photo['allow_tagging'];
            $tags = $this->get_photo_tags( $photo['photo_id'] );
            $uid = userid();
                /* Get user contacts list. This will help us in making suggestions
                     while tagging. Showing profile link on tag
                  */
             if ( $uid ) {
                $friends = $userquery->get_contacts( $uid, 0, 'yes');
             }
            
            if ( $friends ) {
                foreach ( $friends as $contact ) {
                    $fa[$contact['contact_userid']] = $contact['username'];
                    $typeahead[] = $contact['username'];
                }
            }

            if ( $tags ) {
                /* Tags exists */
                foreach ( $tags as $tag ) {
                    $needs_update = false;
                    /* Check if tag is active or not and if current user is not tagger or owner of photo or is guest, do not show tag */
                    if ( ( !$uid && $tag['ptag_active'] == 'no' ) || ( $tag['ptag_active'] == 'no' && $uid && $tag['ptag_by_userid'] != $uid && $tag['photo_owner_userid'] != $uid ) ) {
                        continue; 
                    }
                    $ta = array();
                    $ta['id'] = $tag['ptag_id'];
                    $ta['key'] = $tag['ptag_key'];
                    $ta['width'] = $tag['ptag_width'];
                    $ta['height'] = $tag['ptag_height'];
                    $ta['left'] = $tag['ptag_left'];
                    $ta['top'] = $tag['ptag_top'];
                    $ta['label'] = $tag['username'] = $tag['ptag_username'];
                    $ta['added_by'] = $tag['ptag_by_username'];
                    $ta['date_added'] = nicetime( $tag['date_added'], true);

                    if ( $tag['ptag_active'] == 'no' ) {
                        $ta['pending'] = true;
                    }

                    /* Photo owner and User which has tagged */
                    if ( $uid == $tag['photo_owner_userid'] || $uid == $tag['ptag_by_userid'] ) {
                        $ta['canDelete'] = true;
                    }

                    /* 
                        If make sure tag is a user
                        See which person is online, tagger or tagged
                        If Tagger is online, give him option to delete

                        if Tagged is online, check if it's tagger's friend
                        if true, give option to delete
                    */
                    if ( $tag['ptag_isuser'] == 1 ) {
                        if ( $uid == $tag['ptag_by_userid'] ){ // Tagger is online
                            $ta['canDelete'] = true; // Grant him access to delete
                           
                            if ( is_array($friends) && $fa[ $tag['ptag_userid'] ] ) {
                                $ta['link'] = $userquery->profile_link( $tag['ptag_userid'] );
                                // Person tagged is in his contacts lists and already been tagged, remove it from typahead array
                                unset( $typeahead[ end(array_keys($typeahead,$tag['ptag_username'])) ] );
                            }
                        } else if ( $uid == $tag['ptag_userid'] ) {
                            if ( is_array($friends) && $fa[ $tag['ptag_by_userid'] ] ) {
                                $ta['canDelete'] = true;
                            }
                            $ta['link'] = $userquery->profile_link( $tag['ptag_userid'] );
                        }
                    }

                    $defaultTags[] = $ta;
                }

                $options['defaultTags'] = $defaultTags;
            } 

            if ( $friends && $typeahead && $options['autoComplete'] == 1 ) {
                $options['autoCompleteOptions']['source'] = $typeahead;
            }

            assign('tagger_configs', json_encode($options));
            assign('selector_id',$this->get_selector_id());
            assign('photo',$photo);
            Template(STYLES_DIR.'/global/photo_tagger.html',false);
        }
    }

    function tag_exists( $pid, $hash ) {
        global $db;
        $result = $db->count( tbl('photo_tags'), 'photo_id', " photo_id = '".$pid."' AND ptag_key = '".$hash."' ");
        if ( $result ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add new tag in photo
     * @global OBJECT $userquery
     * @global OBJECT $db
     * @param ARRAY $array
     * @return boolean 
     */
    function add_new_tag ( $array = null ) {
        global $userquery, $db;
        if ( is_null($array) ) {
            $array = $_POST;
        }
        $tag = $array['label'];
        $photo = $this->get_photo( $array['pid'] ); $owner = $userquery->get_user_details( $photo['userid'], false, true); $logged = $tagged_by = $userquery->udetails; $tagged = $array['label'];
        $hash = $tag; $hash_id = null; $is_user = false; $is_friend = false; $needs_approval = $photo['tag_moderation'] = 'no';
        $tagged_userid = 0; $tagged_username = $tagged_name = $tag; 
        if ( empty($photo) ) {
            e(lang('photo_not_exists'));
            return false;
        }

        /* Check if the label is user or not */
        if ( $user = $userquery->get_user_details($tagged, false, true ) ) {
            $is_user = true; $tagged_userid = $user['userid'];
            $tagged_username = $tagged_name = $user['username'];
            if ( $user['first_name'] && $user['last_name'] ) {
                $tagged_name = $user['first_name'].' '.$user['last_name'];
            }
            /* Check if this user is a confirmed friend or not */
            if ( $userquery->is_confirmed_friend($logged['userid'], $tagged_userid ) ) {
                $is_friend = true; $hash_id = $user['userid'];
            }
        }

        /* Checking if tag should be active or not */
        if ( $needs_approval == 'yes' && $tagged_by['userid'] != $photo['userid'] ) {
            $tag_active = 'no';
        } else {
            $tag_active = 'yes';
        }

        /* 
            Create tag hash. If user is confirmed friend we'll add it's id
            to make hash to unique to him 
        */
        $tag_hash = $this->create_tag_hash( $hash, $hash_id );
        if ( $this->tag_exists( $photo['photo_id'], $tag_hash ) ) {
            e(lang('tag_already_exists'));
            return false;
        } else {
            $ta = array(
                'ptag_key' => $tag_hash,
                'ptag_width' => $array['width'],
                'ptag_height' => $array['height'],
                'ptag_top' => $array['top'],
                'ptag_left' => $array['left'],
                /* Tag details either simple/user/friend */
                'ptag_isuser' => $is_user,
                'ptag_isfriend' => $is_friend,
                'ptag_userid' => $tagged_userid,
                'ptag_username' => $tagged_username,
                'ptag_name' => $tagged_name,
                /* User which tagged */
                'ptag_by_userid' => $logged['userid'],
                'ptag_by_username' => $logged['username'],
                'ptag_by_name' => ( $logged['first_name'] && $logged['last_name'] ? $logged['first_name'].' '.$logged['last_name'] : $logged['username']),
                /* Photo owner details */
                'photo_id' => $photo['photo_id'],
                'photo_owner_userid' => $photo['userid'],
                'photo_owner_username' => $owner['username'],
                'photo_owner_name' => ( $owner['first_name'] && $owner['last_name'] ? $owner['first_name'].' '.$owner['last_name'] : $owner['username']),
                'ptag_active' => $tag_active,
                'date_added' => time()
            );

            /* Construct arrays for fields and their corresponding values */
            foreach ( $ta as $field => $value ) {
                $fields[] = $field;
                $values[] = $value;
            }
            /* Now insert tag in database */
            $tag_id = $db->insert( tbl('photo_tags'), $fields, $values );
            if ( $tag_id ) {
                /* Update count of photo ptags_count */
                $db->update( tbl('photos'), array('ptags_count'), array('|f|ptags_count+1'), " photo_id = '".$photo['photo_id']."' " );

                /* Update $tagged_by details */
                /*$tagged_by_ptags = json_decode($tagged_by['ptags_by'],true);
                $tagged_by_ptags = is_array($tagged_by_ptags) ? $tagged_by_ptags : array();
                $tagged_by_ptags[$photo['photo_id']] += 1;
                $db->update( tbl('users'), array('ptags_by','ptags_by_count'), array('|no_mc|'.json_encode( $tagged_by_ptags ),'|f|ptags_by_count+1'), " userid = '".$tagged_by['userid']."' " );*/

                /* Update tag details if it is a user and a confirmed friend of tagger */
                /*if ( $is_user == true && $is_friend == true ) {
                    $tagged_ptags = json_decode( $user['ptags'], true );
                    $tagged_ptags = is_array($tagged_ptags) ? $tagged_ptags : array();
                    $tagged_ptags[$photo['photo_id']] += 1;
                    $db->update( tbl('users'), array('ptags','ptags_count'), array('|no_mc|'.json_encode( $tagged_ptags ),'|f|ptags_count+1'), " userid = '".$user['userid']."' " );
                }*/

                return $tag_id;
            } else {
                e(lang('unable_to_tag'));
                return false;
            }
        }
    }
    
    /**
     *
     * @param ARRAY $params
     */
    
    function get_tags( $params = array() ) {
        global $db;
        
        $limit = $params['limit'];
        $order = $params['order'];
        
        $cond = '';
        
        if ( !has_access('admin_access', true) ) {
            $cond .= tbl('photo_tags.ptag_active')." = 'yes' ";
        } else {
            if ( $params['active'] ) {
                if( $cond ) {
                    $cond .= " AND ";
                }
                $cond .= tbl('photo_tags.ptag_active')." = '".$params['active']."' ";
            }
        }
        
        if ( $params['id'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= tbl('photo_tags.ptag_id')." = '".$params['id']."' ";
        }
        
        if ( $params['pid'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= tbl('photo_tags.photo_id')." = '".$params['pid']."' ";
        }
        
        if ( $params['tag'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= '( '.tbl('photo_tags.ptag_name').' LIKE \'%'.$params['tag'].'%\' || '.tbl('photo_tags.ptag_username').' LIKE \'%'.$params['tag'].'%\' )';
        }
        
        if ( $params['tagger'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= tbl('photo_tags.ptag_by_userid')." = '".$params['tagger']."' ";
        }
        
        if ( $params['tagged'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= tbl('photo_tags.ptag_userid')." = '".$params['tagged']."' ";
        }
        
        if ( $params['user_tagged_only'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= tbl("photo_tags.ptag_isuser = '1'");
        }
        
        if ( $params['join_photos'] ) {
            $join = " LEFT JOIN ".tbl('photos')." ON ".tbl('photo_tags.photo_id')." = ".tbl('photos.photo_id')." ";
            $alias = ", ".tbl('photos.*,photo_tags.date_added as date_added,photos.date_added as pdate_added');
        }
        
        if ( $params['extra'] ) {
            if ( $cond ) {
                $cond .= " AND ";
            }
            $cond .= $params['extra'];
        }
        
        if ( $params['count_only'] ) {
            $results = $db->count( tbl('photo_tags'),'ptag_id', $cond );
        }
        
        if ( !$params['count_only'] ) {
            $results = $db->select( tbl('photo_tags').$join,tbl('photo_tags.*').$alias,$cond, $limit, $order );
        }
        
        
        
        if ( $results ) {
            if ( $params['assign'] ) {
                assign( $params['assign'], $results );
            } else {
                return $results;  
            }
        } else {
            return false;
        }
    }
    
    /**
     * This will get tags of provided photo id
     * 
     * @global OBJECT $db
     * @param INT $photo Photo ID
     * @param INT $limit Limit of number of results from DB
     * @param STRING $cond Any other extra condition
     * @param STRING $order Order of results
     * @return MIX 
     */
    function get_photo_tags( $photo, $limit=null, $cond=null, $order='date_added ASC' ) {
        global $db;
        
        if ( is_array ($photo) ) {
            $params = $photo;
        } else {
            $params['pid'] = $photo;
            $params['limit'] = $limit;
            $params['order'] = $order;

            if ( !is_null($cond) ) {
                $params['extra'] = $cond;
            }
        }
        
        $results = $this->get_tags($params);
        
        //$results = $db->select( tbl('photo_tags'),'*', " photo_id = '".$photo."' ".$cond." ", $limit, $order );
        if ( $results ) {
            return $results;
        } else {
            return false;
        }
    }
    
    /**
     * This function will be create 32 characters long hash for tag string.
     * It will remove everything expect alphanumeric characters. If tag is
     * user and is friend of tagger, it will add it's userid to make it unique for
     * that user.
     * @param STRING $tag
     * @param INT $uid
     * @return STRING 
     */
    function create_tag_hash( $tag, $uid=null ) {
        $tag = mb_strtolower($tag);
        $tag = preg_replace('/[^a-z0-9]/','', $tag);
        if( !is_null($tag) ) {
            if( !is_null($uid) ) {
                $tag .= $uid;
            }
            return md5($tag);
        }
    }

    /**
     * Get tag from it's id
     * @global OBJECT $db
     * @param INT $tag_id
     * @param INT $pid
     * @param STRING $cond
     * @return MIX 
     */
    function get_tag_with_id ( $tag_id, $pid =null, $cond = null ) {
        global $db; $condition = '';
        if ( !is_null($pid) ) {
            $condition = " AND photo_id = '".$pid."' ";
        } 

        if ( !is_null($cond) ) {
            $condition = ( $condition ? $condition.' AND '.$cond : $cond );
        }

        $result = $db->select( tbl('photo_tags'),'*', " ptag_id = '".$tag_id."' || ptag_key = '".$tag_id."' ".$condition." ");
        if ( $result ) {
            return $result[0];
        } else {
            return false;
        }
    }

    /**
     * Remove photo tag
     * @global OBJECT $db
     * @global OBJECT $userquery
     * @param INT $tag
     * @return boolean 
     */
    function remove_photo_tag ( $tag_id ) {
        global $db, $userquery;
        $uid = $userquery->userid;
        if ( empty($uid)) {
            e(lang('login_to_remove_tag'));
            return false;
        }
        
        if ( !is_array($tag_id) ) {
            $tag = $this->get_tag_with_id( $tag_id );
        } else {
            $tag =$tag_id;
        }

        /* if tag is empty return */
        if ( empty($tag) ) {
            e(lang('tag_not_exist'));
            return false;
        }

        /* if user is not photo owner or tagger or tagged or does not have admin access */
        if ( ( $uid != $tag['photo_owner_userid'] && $uid != $tag['ptag_by_userid'] && $uid != $tag['ptag_userid'] ) && !has_access( 'admin_access', true ) ) {
            e(lang('cant_remove_tag_1'));
            return false;
        }
        
        /* if tagged is user, make sure tag hash matches */
        if ( $tag['ptag_isuser'] == true ) {
            if ( $uid != $tag['ptag_userid'] && !has_access('admin_access',true) ) {
                {
                    e(lang('cant_remove_tag_2'));
                    return false;
                }
            }
        }
        
        /* Deletion Good TO GO */
        $db->delete( tbl('photo_tags'), array('ptag_id'), array($tag['ptag_id']) );

        /* Decrease photo tag count */
        $db->update( tbl('photos'), array('ptags_count'), array('|f|ptags_count-1'), " photo_id = '".$tag['photo_id']."' " );

        return true;
    }
	
}

?>