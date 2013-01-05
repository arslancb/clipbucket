<?php

class user_content {
    private $GROUPS; // Holds complete list of groups
    private $_current_user; // details of user of which content is being loaded
    private $filtered_content; // List of content after being filtered
    private $object_details = array(); // details of object being loaded
    
    var $object_group; // Content group
    var $object; // content object or content
    var $get_callback; // Callback functio
    var $display_callback; // Unused variable
    var $section = false; // Is section or not 
    var $is_private = false; // is private or not
    var $content_type = null; // content type
    var $permissions = null; // permissions for object
    var $access = null; // access for object
   
    /**
     * Checks if object is section or not. If $section is boolean, we'll
     * use $object to if section is enabled. If string provided, we'll
     * that string to check section
     * @return string
     */
    function _check_is_section() {
        return ( $this->section ? ( is_bool( $this->section ) ? ':is_section' : ':is_section|'.$this->section ) : '' );
    }
    
    /**
     * Check if object itself is content or not. For better explaination
     * consider following example:
     * $object_group is connections
     * --> $object is subscribers, this object itself is content, 
     * whereas
     * $object_group is content
     * --> $object is videos
     *     --> $content_type is uploaded, now this is content
     * @return string
     */
    function _check_is_content() {
        return ( $this->content_type ? '' : ':is_content' );
    }
    
    /**
     * Checks if content is public or private.
     * Note: Right now private means only user, user's friends
     * are not included.
     * 
     * @return string
     */
    function _check_is_private() {
        return ( $this->is_private ? ':is_private' : ':is_public' );
    }
    
    /**
     * Get the list of all groups
     * @return array
     */
    function ___groups() {
        return $this->GROUPS;
    }
    
    /**
     * Set the list of all groups
     * @param array $groups
     * @return array
     */
    function ___set_groups( $groups ) {
        return $this->GROUPS = $groups;
    }
    
    function __get_current_object_name( $name = true ) {
        $lists = $this->__filter_user_object_content();
        $index = $this->_build_index_object_content();
        if ( $lists ) {
            if ( !$index || !$lists[ $index ] ) {
                $index = key( $lists );
            }
            
            if( $name === true ) {
                $k = $this->__extract_key_details( $index );
                $name = apply_filters( $k[0], 'object_name' );
                return $name;
            } else {
                $list[ $index ] = $lists[ $index ];
                return $list;
            }
        }
        
        return false;
    }
    
    function __get_current_object_content_type_name( $name = true ) {
        $list = $this->__get_current_object_name( false );
        
        if ( $list ) {
            $index = key ( $list );
            if ( strpos( $index, 'is_content') === false ) {
                $content_type = mysql_clean( get('content_type') );
                if ( !$content_type ) {
                    $content_type = key( $list[ $index ] );
                }
                
                $content_type = $this->_build_index_object_content( $content_type );
                
                if ( !$content_type ) {
                    $content_type = key( $list[ $index ] );
                }
                
                if ( $name === true ) {
                    $name = $list[ $index ][ $content_type ]['content_type'];
                    $name = apply_filters( $name, 'object_name' );
                    return $name;
                } else {
                    $content_type[ $content_type ] = $list[ $index ][ $content_type ];
                    return $content_type;
                }
                
            }
        }
        
        return false;
    }
    
    /**
     * Get details of user whose content is being
     * viewed
     * @return array
     */
    function __get_current_user() {
        return $this->_current_user;
    }
    
    /**
     * Alias of aabove method
     * @return array
     */
    function get_current_user() {
        return $this->__get_current_user();
    }
    
    /**
     * Set details of user whose content is being
     * @param array $user
     * @return array
     */
    function __set_current_user( $user ) {
        return $this->_current_user = $user;
    }
    
    /**
     * Gets filtered content array
     * @return array
     */
    function __get_filtered_content() {
        return $this->filtered_content;
    }
    
    /**
     * Sets filtered content
     * @param array $content
     * @return array
     */
    function __set_filtered_content( $content ) {
        return $this->filtered_content = $content;
    }
    
    /**
     * Gets object details
     * @return array
     */
    function __get_object_details() {
        return $this->object_details;
    }
    
    /**
     * Sets object details which conists of following:
     * --> $object
     * --> result of _check_is_section()
     * --> result of _check_is_private()
     * --> result of _check_is_content()
     * @param array $details
     * @return array
     */
    function __set_object_details($details) {
        return $this->object_details = $details;
    }
    
    /**
     * Resets variables back to default values
     */
    function __reset_variables() {
        $this->object_group = '';
        $this->object = '';
        $this->section = false;
        $this->is_private = false;
        $this->content_type = null;
        $this->permissions = null;
    }
    
    /**
     * Adds a new content in array
     * @return array
     */
    function _add_new_content() {
        $groups = $this->___groups();
        if ( 
                !$this->object_group || !$this->object ||
                !$this->get_callback ||!function_exists( $this->get_callback ) 
            ) { return false; }
            
        if ( $this->content_type && !is_string( $this->content_type ) ) {
            return false;
        }
        
        $complete_id = $this->object.$this->_check_is_section().$this->_check_is_content();
        
        if ( !$this->content_type ) {
            $complete_id .= $this->_check_is_private();
        }
        
        if ( !$groups[ $this->object_group ] ) {
            $groups[ $this->object_group ] = array();
        }
        
        if ( !$groups[ $this->object_group ][ $complete_id ] ) {
            $groups[ $this->object_group ][ $complete_id ] = array();
        }
        
        if ( $this->content_type && !$groups[ $this->object_group ][ $complete_id ][ $this->content_type.$this->_check_is_private() ] ) {
            $groups[ $this->object_group ][ $complete_id ][ $this->content_type.$this->_check_is_private() ] = array();
        }
        
        $array = array(
            'group' => $this->object_group,
            'object' => $this->object,
            'get' => $this->get_callback,
            'display' => $this->display_callback,
            'permissions' => $this->permissions
        );
        
        if ( !$this->content_type ) {
            $groups[ $this->object_group ][ $complete_id ] = $array;
            $details = array( $this->object, $this->_check_is_section(), $this->_check_is_content(), $this->_check_is_private() );
        } else {
            $array['content_type'] = $this->content_type;
            $groups[ $this->object_group ][ $complete_id ][ $this->content_type.$this->_check_is_private() ] = $array;
            $details = array( $this->object, $this->_check_is_section(), $this->_check_is_content() );
        }
        
        $odetails = $this->__get_object_details();
        $odetails[$this->object_group ][ $this->object ]['object'] = $details;
        if ( $this->content_type ) {
            $odetails[$this->object_group ][ $this->object ][ $this->content_type ] = array( $this->content_type, $this->_check_is_private() );
        }
        $this->__set_object_details( $odetails );
        
        $this->__reset_variables();
        return $this->___set_groups( $groups );
    }
    
    /**
     * Alias function for above
     */
    function add_new_content() {
        return $this->_add_new_content();
    }
    
    /**
     * Extract important details of object from key
     * @param string $key
     * @return array
     */
    function __extract_key_details( $key ) {
        return array_map("trim", explode(":", $key) );
    }
    
    /**
     * Confirms if section is enabled or not
     * @param array $content
     * @param array $key_details
     * @return boolean
     */
    function _confirm_section_enabled( $content, $key_details ) {
        if ( $section = array_find( 'is_section', $key_details ) ) {
            if ( strpos( $section, "|" ) !== false ) {
                $section_key = end( explode("|", $section) );
            } else {
                $section_key = $key_details[0];
            }
            
            if ( !isSectionEnabled( $section_key ) ) {
                return true;
            }
            
            return false;
        }
    }
    
    /**
     * Confirms if content is private
     * @param array $content
     * @param string $key
     * @return string|boolean
     */
    function _confirm_private( $content, $key ) {
        $user = $this->get_current_user();
        if ( strpos( $key, ':is_private' ) !== false && (  !userid() || userid() != $user['userid'] ) ) {
            return ( $content['content_type'] ? $key : true );
        }
    }
    
    /**
     * Confirms if permissions is allowed or not
     * @param array $content
     * @param string $key
     * @return string|boolean
     */
    function _confirm_permissions ( $content, $key ) {
        
        $user = $this->get_current_user();
        
        $permissions = explode(",",$content['permissions']);
        $permissions = array_map( "trim", $permissions );
        
        foreach ( $permissions as $per ) {
            if ( $user[ $per ] == 'no' && userid() != $user['userid'] ) {
                return ( $content['content_type'] ? $key : true );
            }
        }
    }
    
    /**
     * This function filters the content array
     * using it's details
     * 
     * @global object $userquery
     * @param string $group
     * @param string $return
     * @return array
     */
    function __filter_user_object_content( $_group = null, $return = false, $cache = false ) {
        
        if( $this->__get_filtered_content() && $cache === false ) {
            return $this->__get_filtered_content();
        }
        
        $groups = $this->___groups();
        $group = $_group ? $_group : get('object_group');
        if (!$this->__get_current_user()) {
            global $userquery;
            $user = $userquery->get_user_details( get('user'), false, true );
            $user = $this->__set_current_user( $user );
        } else {
            $user = $this->__get_current_user();
        }
        
        if ( !$user ) {
            return false;
        }
        
        if ( !$group || !$groups[ $group ] ) {
            $group = key( $groups );
        }
        
        if ( $groups[ $group ] ) {            
            foreach( $groups[$group] as $key => $value ) {
                $info = $this->__extract_key_details( $key );
                $section_disbaled = false; 
                $is_private = false;
                $no_permission = false;
                
                $section_disbaled = $this->_confirm_section_enabled( $value, $info );
                                
                if ( array_find( 'is_content', $info ) ) {
                    $is_private = $this->_confirm_private( $value, $key );
                    $no_permission = $this->_confirm_permissions( $value, $key );
                } else {
                    foreach ( $value as $type => $content ) {
                        $content_pri = $this->_confirm_private( $content, $type );
                        $content_per = $this->_confirm_permissions( $content, $type );
                        
                        if ( $content_pri && $content_per ) {
                            $remove_content[] = $content_pri;
                            $remove_content[] = $content_per;
                        } else if ( $content_pri ) {
                            $remove_content[] = $content_pri;
                        } else if ( $content_per ) {
                            $remove_content[] = $content_per;
                        }
                    }
                }
                                
                if ( $section_disbaled || $is_private || $no_permission ) {
                    continue;
                }
                
                if ( isset( $remove_content ) ) {
                    foreach( $remove_content as $rc ) {
                        if ( $groups[ $group ][ $key ][ $rc ] ) {
                           unset ( $groups[ $group ][ $key ][ $rc ] ); 
                        }
                    }
                    $value = $groups[ $group ][ $key ];
                }
                
                unset( $remove_content );
                
                if ( $value ) { 
                    $filtered_content[ $key ] = $value;
                }
            }
            
            return ( $return ) ? $filtered_content : $this->__set_filtered_content( $filtered_content );
        }
    }
    
    /**
     * Function build index key for current object being
     * viewed
     * @return string
     */
    function _build_index_object_content( $build = 'object' ) {
        $object_group = mysql_clean( get('object_group') ); 
        $object = mysql_clean( get('object') );
        $odetails = $this->__get_object_details();

        if ( !$object_group || !$odetails[ $object_group ] ) {
            $object_group = key( $odetails );
        }
        
        if ( !$object || !$odetails[ $object_group ][ $object ] ) {
            $object = key( $odetails[ $object_group ] );
        }
        
        if ( $object_group && $object ) {
            if ( $odetails[$object_group][$object] ) {
                return ( $odetails[$object_group][$object][ $build ] ? implode("", $odetails[$object_group][$object][ $build ] ) : false );
            }
        }
        
        return false;
    }
    
    /**
     * Function outputs a list of objects
     * @return string
     */
    function _display_objects_list() {
        $content = $this->__filter_user_object_content();
        $index = $this->_build_index_object_content();
        $user = $this->__get_current_user();
        $output = '';
        
        if ( $content ) {
            $keys = array_keys( $content );
            if ( !$content[ $keys[0] ]['group'] ) {
                $first_key = key( $content[ $keys[0] ] );
                $group = $content[ $keys[0] ][ $first_key ]['group'];
            } else {
                $group = $content[ $keys[0] ]['group'];
            }

            foreach ( $keys as $key ) {
                $active = '';
                if ( $index == $key ) {
                    $active = ' active';
                }
                $k = $this->__extract_key_details( $key );
                $name = apply_filters( $k[0], 'object_name' );
                $name = apply_filters( $name, $k[0].'_name_filter' );
                $output .= '<li class="user-objects-list user-object-'.$k[0].$active.'"><a href="'.make_user_content_link( $user['username'], $group, $k[0] ).'">'.$name.'</a></li>';
            }
            
            return $output;
        }
    }
    
    /**
     * Function outputs a list of objects other the active
     */
    function _display_other_objects_list() {
        $groups = $this->___groups();
        $active_group = $group = get('object_group');;
        $user = $this->__get_current_user();
        
        if ( !$active_group || !$groups[ $active_group ] ) {
            $active_group = key( $groups );
        }
        
        if ( $groups ) {
            // Remove the active group from $groups array
            unset( $groups[ $active_group ] );
            $total_groups =  count( $groups ); $group_count = 0;
            foreach ( $groups as $group => $group_content ) {
                $group_content = $this->__filter_user_object_content( $group, true, true );
                if ( $group_content ) {
                    $keys = array_keys( $group_content );
                    
                    if ( $group_count > 0 && $group_count < $total_groups ) {
                        $output .= '<li class="user-other-objects-divider divider"></li>';
                    }
                    
                    foreach ( $keys as $key ) {
                        $k = $this->__extract_key_details( $key );
                        $name = apply_filters( $k[0], 'object_name' );
                        $name = apply_filters( $name, $k[0].'_other_name_filter' );
                        $output .= '<li class="other-objects-list other-object-'.$k[0].'"><a href="'.make_user_content_link( $user['username'], $group, $k[0] ).'">'.$name.'</a></li>';
                    }
                    
                    $group_count++;
                }
            }
            
            return $output;
        }
        return false;
    }
    
    /**
     * Function outputs a list of content type if
     * object itself is not content and it has content type
     * @return boolean|string
     */
    function _display_content_type_list() {
        $content = $this->__filter_user_object_content();
        $index = $this->_build_index_object_content();
        $user = $this->__get_current_user();
        
        if ( $content[ $index ] ) {
            
            if ( strpos( $index, ':is_content') !== false ) {
                return false;
            }
            
            $content_type = mysql_clean( get('content_type') );
            
            if ( !$content_type ) {
                $content_type = key( ( $content[$index] ) );
            }
            
            $content_type = $this->_build_index_object_content( $content_type );
                
            if ( !$content_type ) {
                $content_type = key( $content[ $index ] );
            }
            
            $output = '';
            foreach ( $content[ $index ] as $type => $object_content ) {
                $active = '';
                if ( $type == $content_type ) {
                   $active = ' active';
                }
                $name = apply_filters( $object_content['content_type'], 'content_type_name' );
                $name = apply_filters( $name, $object_content['object'].'_'.$type.'_name_filter' );
                $output .= '<li class="user-object-content-type user-object-content-'.$object_content['content_type'].' user-object-'.$object_content['object'].'-'.$object_content['content_type'].$active.'"><a href="'.  make_user_content_link( $user['username'], $object_content['group'], $object_content['object'], $object_content['content_type'] ).'">'.$name.'</a></li>';               
            }
            
            return $output;
        }
        
        return false;
    }
    
    /**
     * Function outputs the object content
     * @return string
     */
    function _display_object_content() {
        $content = $this->__filter_user_object_content();
        $index = $this->_build_index_object_content();
        
        if ( $content[ $index ] ) {
            $output = '';
            if ( isset( $content[ $index ]['get'] ) ) {
                $output .= $this->__do_content_callback( $content[ $index ], $index );
            } else {
                $content_type = get('content_type');
                if ( !$content_type ) {
                    $content_type = key( $content[ $index ] );
                }
                
                $content_type = $this->_build_index_object_content( $content_type );
                
                if ( !$content_type ) {
                    $content_type = key( $content[ $index ] );
                }
                
                if ( $content[ $index ][ $content_type ]['get'] ) {
                    $output .= $this->__do_content_callback( $content[ $index ][ $content_type ], $index );
                }
            }
            
            return $output;
        }
        
        return false;
    }
    
    /**
     * Function calls the callback of current object and
     * outputs the result.
     * @param array $content
     * @param string $key
     * @return string
     */
    function __do_content_callback( $content, $key ) {
        $class = 'user-'.$content['object'].' user-content-container';
        if ( $content['content_type'] ) {
            $class .= ' user-'.$content['object'].'-'.$content['content_type'];
        }
        
        $id = 'user-'.$content['object'];
        if ( $content['content_type'] ) {
            $id .= '-'.$content['content_type'];
        }
        
        $attrs = array( 'class' => $class, 'id' => $id, 'data-object-group' => $content['group'], 'data-object' => $content['object'] );
        if ( $content['content_type'] ) {
            $attrs['data-content-type'] = $content['content_type'];
        }
        
        foreach ( $attrs as $attribute => $value ) {
            $attributes .= $attribute.' = "'.$value.'" ';
        }
        
        $output = '<div '.$attributes.'> ';
            $output .= $content['get']( $content, $key );
        $output .= '</div>';
                
        return $output;
    }
    
    function _get_subtitle() {
        $user = $this->get_current_user();
        $object = $this->__get_current_object_name();
        $content_type = $this->__get_current_object_content_type_name();
        $name = sprintf( '%s\'s ', name( $user ) );
        $subtitle = $name.' '.( $content_type ? $content_type.' ' : '' ).$object;
        $subtitle = apply_filters( $subtitle, 'content_heading' );
        
        return $subtitle;
    }
}

/**
 * Adding alias functions
 */
function display_objects_list() {
    global $usercontent;
    return $usercontent->_display_objects_list();
}

function display_content_type_list() {
    global $usercontent;
    return $usercontent->_display_content_type_list();
}

function display_object_content() {
    global $usercontent;
    return $usercontent->_display_object_content();
}

function display_other_objects_list() {
    global $usercontent;
    return $usercontent->_display_other_objects_list();
}


/* STARTING USERCONTENT FUNCTIONS */

/**
 * This makes string readable
 * @param string $name
 * @return string
 */
function usercontent_make_label( $name ) {
    $name = explode( "_", $name );
    $name[0] = ucfirst( $name[0] );
    $name = implode( " ", $name );
    return $name;
}

/**
 * Creates link for user content
 * 
 * @param string $user Could be username or userid
 * @param string $group
 * @param string $object
 * @param string $content_type
 * @return string
 */
function make_user_content_link( $user = null, $group = null, $object = null, $content_type = null ) {
    $link = BASEURL.'/user_content.php?user='.$user;
    
    if ( !is_null( $group) ) {
        $link .= "&object_group=$group";
    }
    
    if ( !is_null( $object ) ) {
        $link .= "&object=$object";
    }
    
    if ( !is_null( $content_type ) ) {
        $link .= "&content_type=$content_type";
    }
    
    return $link;
}

function get_current_object_heading() {
    global $usercontent;
    return $usercontent->_get_subtitle();
}

/* ENDING USERCONTENT FUNCTIONS */

/**
 * Following are default callbacks for user content
 */

function cb_get_user_favorite_videos( $content, $key ) {
    global $usercontent, $pages, $cbvid;
    $udetails = $usercontent->get_current_user();
    $page = mysql_clean( get('page') );
    $get_limit = create_query_limit($page,config('videos_items_ufav_page'));
    
    $params = array('userid'=>$udetails['userid'],'limit'=>$get_limit);
    $videos = $cbvid->action->get_favorites($params);
    
    $params['count_only'] = "yes";
    $total_rows = $cbvid->action->get_favorites( $params );
    $total_pages = count_pages($total_rows,config('videos_items_ufav_page'));
    
    $params = array();
    $params['file'] = 'user_videos.html';
    $params['the_title'] = name( $udetails ).' '.lang('favorites');
    $params['videos'] = $videos;
    $params['total_videos'] = $total_rows;
    $params['mode'] = 'favorite';
    
     subtitle(sprintf(lang("title_usr_fav_vids"),name( $udetails )));
     
     return fetch_template_file( $params );
}

function cb_get_user_uploaded_videos ( $content, $key ) {
    global $usercontent, $pages;
    
    $udetails = $usercontent->get_current_user();
    $page = mysql_clean( get('page') );
    $get_limit = create_query_limit($page,config('videos_items_uvid_page'));
    
    $videos = get_videos(array('user'=>$udetails['userid'],'limit'=>$get_limit));
    
    $total_rows = get_videos(array('user'=>$udetails['userid'],'count_only'=>true));
    $total_pages = count_pages($total_rows,config('videos_items_uvid_page'));
    $pages->paginate($total_pages,$page);
    
    $params['file'] = 'user_videos.html';
    $params['the_title'] = name( $udetails )." ".lang('videos');
    $params['videos'] = $videos;
    $params['total_videos'] = $total_rows;
    $params['mode'] = 'uploaded';
    
    subtitle( sprintf(lang("users_videos"),name( $udetails ) ));
    
    return fetch_template_file($params);
}

function cb_get_user_uploaded_photos( $content, $key ) {
    global $usercontent, $pages;
    
    $user = $usercontent->get_current_user();
    $page = mysql_clean( get('page') );
    $limit = create_query_limit($page,config('photo_user_photos'));
    
    $photos = get_photos(array("limit"=>$limit,"user"=>$user['userid']));
    
    $total_rows = get_photos(array("count_only"=>true,"user"=>$user['userid']));
    $total_pages = count_pages($total_rows,config('photo_user_photos'));
    $pages->paginate( $total_pages, $page );
    
    $params['file'] = 'user_photos.html';
    $params['the_title'] = name( $user )." ".lang('photos');
    $params['photos'] = $photos;
    $params['total_photos'] = $total_rows;
    $params['mode'] = 'uploaded';
    
    return fetch_template_file($params);
}

function cb_get_user_favorite_photos() {
    global $usercontent, $pages, $cbphoto, $db;
    
    $user = $usercontent->get_current_user();
    $page = mysql_clean( get('page') );
    $limit = create_query_limit($page,config('photo_user_favorites'));

    $photos = $cbphoto->action->get_favorites( array("userid"=>$user['userid'],"limit"=>$limit) );

    $total_rows = $cbphoto->action->get_favorites( array("userid"=>$user['userid'],"limit"=>$limit, "count_only" => true ) );
    $total_pages = count_pages( $total_rows,config('photo_user_favorites') );
    $pages->paginate( $total_pages, $page );
    
    $params['file'] = 'user_photos.html';
    $params['the_title'] = name( $user )." ".lang('favorites');
    $params['photos'] = $photos;
    $params['total_photos'] = $total_rows;
    $params['mode'] = 'favorite';
    
    return fetch_template_file( $params );
}


function cb_get_user_friends() {
    global $usercontent, $userquery;
    
    $udetails = $usercontent->get_current_user();
    $friends = $userquery->get_contacts( $udetails['userid'], 0, "yes" );
    
    $params['file'] = 'user_contacts.html';
    $params['contacts'] = $friends;
    $params['no_result'] = lang( name( $udetails ).' has no friends, yet.' );
    $params['mode'] = 'friends';
    $params['the_title'] = sprintf( lang('users_contacts'), name ( $udetails ) );
    $params['heading'] = sprintf( lang('users_contacts'), name ( $udetails ) );
    
    subtitle(sprintf( lang("users_contacts"), name( $udetails )) );
    
    return fetch_template_file( $params );
}

function cb_get_user_subscriptions() {
    global $usercontent, $userquery;
    
    $udetails = $usercontent->get_current_user();
    $subscriptions = $userquery->get_user_subscriptions( $udetails['userid'], null );
    
    $params['file'] = 'user_contacts.html';
    $params['contacts'] = $subscriptions;
    $params['no_result'] = lang( name( $udetails ).' has no subscriptions, yet.' );
    $params['mode'] = 'subscriptions';
    $params['the_title'] = sprintf( lang('user_subscriptions'), name ( $udetails ) );
    $params['heading'] = sprintf( lang('user_subscriptions'), name( $udetails ) );

    subtitle(sprintf( lang('user_subscriptions'), name( $udetails )));
    
    return fetch_template_file( $params );
}

function cb_get_user_subscribers() {
    global $usercontent, $userquery;
    
    $udetails = $usercontent->get_current_user();
    $subscribers = $userquery->get_user_subscribers_detail( $udetails['userid'], null );
    
    $params['file'] = 'user_contacts.html';
    $params['contacts'] = $subscribers;
    $params['no_result'] = lang( name( $udetails ).' has no subscribers, yet.' );
    $params['mode'] = 'users_subscribers';
    $params['the_title'] = sprintf( lang('users_subscribers'), name ( $udetails ) );
    $params['heading'] = sprintf( lang('users_subscribers'), name( $udetails ) );

    subtitle(sprintf( lang('users_subscribers'), name( $udetails )));
    
    return fetch_template_file( $params );
}
?>