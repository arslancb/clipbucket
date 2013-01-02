<?php

function init_dashboard_js() {
    if ( userid() ) {
        echo '<script type="text/javascript" src="'.JS_URL.'/functions_dashboard.js"></script>';
    }
}

/**
 * Function loads default levels of important for dashboard
 * widgets. Uses <em>dashboard_widget_importance</em> filter
 * @return array
 */
function get_dashboard_widget_importance() {
    $importance = array( 'highest', 'high', 'normal', 'low', 'lowest' );
    $importance = apply_filters( $importance, 'dashboard_widget_importance' );
    return $importance;
}

/**
 * Checks the provided importance exists or not.
 * @param string $importance
 * @return string
 */
function _check_widget_importance( $importance ) {
    $default = get_dashboard_widget_importance();
    return ( !in_array( $importance, $default) ? 'normal' : $importance );
}

/**
 * Returns all dashboards
 * @global object $Cbucket
 * @return array
 */
function get_dashboards() {
    global $Cbucket;
    return $Cbucket->dashboards;
}

/**
 * Return dashboard for specific place
 * @param sring $place
 * @return array
 */
function get_dashboard( $place ) {
    $dashboards = get_dashboards();    
    return $dashboards[ $place ];
}

/**
 * Assigns value to dashboards variable
 * @global object $Cbucket
 * @param array $dashboards
 * @return array
 */
function set_dashboards( $dashboards ) {
    global $Cbucket;
    return $Cbucket->dashboards = $dashboards;
}

/**
 * Assigns dashborad widget to dashboard for specifc place and importance
 * @global object $Cbucket
 * @param type $place
 * @param array $dashboard_widget
 * @param string $importace
 * @return array
 */
function set_dashboard( $place, $dashboard_widget, $importance = 'normal'  ) {
    global $Cbucket;
    $id = key( $dashboard_widget );
    return $Cbucket->dashboards[ $place ][ $importance ][ $id ]  = $dashboard_widget[ $id ];
}

/**
 * Gets position of last widget in dashboard for specific place and importance
 * @param string $place
 * @param string $importance
 * @param array $dashboard
 * @return boolean
 */
function get_last_dashboard_widget_position( $place, $importance = 'normal', $dashboard = null ) {
    if ( is_null( $dashboard ) ) {
        $dashboard = get_dashboard( $place );
    }
    
    if ( $dashboard ) {
        $widgets = $dashboard[ $importance ];
        if ( $widgets ) {
            $last_widget = end ( $widgets );
            return $last_widget[ 'position' ];
        }
    }
    
    return false;
}

/**
 * Checks if widgets or not
 * @param string $id
 * @param string $place
 * @param string $importance
 * @return boolean
 */
function dashboard_widget_exists( $id, $place, $importance = 'normal' ) {
    $dashboards = get_dashboards();
    if ( isset( $dashboards[ $place ][ $importance ][ $id ] ) ) {
        return true;
    }
    
    return false;
}

/**
 * Adds widget in dashboard for specific place and importance
 * @param string $place
 * @param string $id
 * @param string $name
 * @param string $display_callback
 * @param string $importance
 * @param string $description
 * @param string $callback
 * @return boolean
 */
function add_dashboard_widget( $place, $id, $name, $display_callback, $importance = 'normal', $callback = null ) {
    if ( !dashboard_widget_exists( $id, $place, $importance ) ) {
        
        if ( !function_exists( $display_callback ) ) {
            return false;
        }
        
        $importance = _check_widget_importance( $importance );
        $dashboard_widget[ $id ] = array(
            'id' => $id,
            'name' => $name,
            'display_callback' => $display_callback,
            'importance' => $importance,
            'callback' => $callback
        );
                
        return set_dashboard( $place, $dashboard_widget, $importance );
    }
    
    return false;
}

/**
 * Display dashboard for specific place. If not place is provided,
 * we'll try to look if some dashboard is registered for current
 * page using THIS_PAGE constant.
 * 
 * @todo Add open close toggler, configurations for widgets
 * @param string $place
 * @return string|boolean
 */
function display_dashboard( $place = null ) {
    
    if ( is_null( $place ) ) {
        $place = THIS_PAGE;
    }
    
    $dashboard = get_dashboard( $place );
    if ( $dashboard ) {
        $importance = get_dashboard_widget_importance();
        $dashboard['place'] = $place;
        $dashboard = apply_filters( $dashboard, 'dashboard' );
        $closed = get_closed_boxes( $place );
        
        $output = '<div id="dashboard-container" class="dashboard-container" data-place="'.$place.'">'; // Dashboard widgets output
        foreach ( $importance as $important ) {
            if ( isset( $dashboard[ $important ] ) ) {
                $dashboard_widgets = $dashboard[ $important ];
                $total_dashboard_widgets = count( $dashboard_widgets );
                if ( $dashboard_widgets ) {
                    $output .= '<div id="'.$place.'-'.$important.'-importance" class="dashboard-widgets dashboard-widgets-'.$important.'-importance '.$place.'-widgets has-'.$total_dashboard_widgets.'-widgets" data-importance="'.$important.'">';
                    foreach ( $dashboard_widgets as $dashboard_widget ) {
                        $hidden = ( $closed ? ( in_array( $dashboard_widget['id'], $closed ) ) ? ' closed' : '' : '' );
                        $output .= '<div id="'.$dashboard_widget['id'].'-'.$dashboard_widget['importance'].'" class="dashboard-widget '.SEO( strtolower( $dashboard_widget['name'] ) ).' '.$dashboard_widget['id'].' is-'.$important.''.$hidden.'" data-id="'.$dashboard_widget['id'].'">';
                        $output .= '<div class="dashboard-widget-toggler"> <b></b> </div>';
                        $output .= '<div class="dashboard-widget-handler"><b></b></div>';
                        $output .= '<h3 class="dashboard-widget-name">'.$dashboard_widget['name'].'</h3>';
                        if ( $dashboard_widget['description'] ) {
                            $output .= '<div class="dashboard-widget-description">'.$dashboard_widget['description'].'</div>';
                        }
                        $output .= '<div class="dashboard-content">';
                        $output .= $dashboard_widget['display_callback'] ( $dashboard_widget );
                        $output .= '</div>';
                        $output .= '</div>';         
                    }
                    $output .= '</div>';
                }
            }
        }
        $output .= "</div>";
        return $output;
    }
    return false;
}

/**
 * This orders the widgets of specifc place and important according
 * to their positions.
 * 
 * @todo Make positioning of widgets work
 * @param array $dashboard
 * @return array
 */
function _order_dashboard_widgets_positions( $dashboard ) {
    $user_positions = get_user_dashboard_widget_positions();
    if ( $user_positions ) {
        $upos = $user_positions[ $dashboard['place'] ];
        $place = $dashboard['place']; unset( $dashboard['place'] );
        if ( $upos ) {
            $importance = get_dashboard_widget_importance();
            
            foreach ( $importance as $imp ) {
                $usort = $upos[ $imp ];
                if ( $usort ) {
                    foreach ( $usort as $usort_id ) {
                        if ( $dashboard[ $imp ][ $usort_id ] ) {
                            $new_dashboard[ $imp ][ $usort_id ] = $dashboard[ $imp ][ $usort_id ];
                        }
                    }
                    
                    $dash_keys = array_keys ( $dashboard[ $imp ] );
                    if ( $dash_keys ) {
                        foreach ( $dash_keys as $id ) {
                            if ( isset( $new_dashboard[ $imp ][ $id ] ) ) {
                                continue;
                            }
                            
                            $new_dashboard[ $imp ][ $id ] = $dashboard[ $imp ][ $id ];
                        }
                    }
                    
                } else if ( $dashboard[ $imp ] ) {
                    $new_dashboard[ $imp ] = $dashboard[ $imp ];
                }

            }
            
        }
    }
    
    return $new_dashboard ? $new_dashboard : $dashboard;
}

/**
 * Return the states of all dashboard widgets for 
 * current user
 * 
 * @return array
 */
function get_user_dashboard_widget_states() {
    if ( userid() ) {
        $user_states = config('dashboard_states');
        if ( $user_states ) {
            $user_states = json_decode( $user_states, true );
            return $user_states[ userid() ];
        }
    }
    return false;  
}

/**
 * Return the positions of all dashboard widgets for
 * current user
 * 
 * @return array
 */
function get_user_dashboard_widget_positions() {
    if ( userid() ) {
        $user_positions = config('dashboard_positions');
        if ( $user_positions ) {
            $user_positions = json_decode( $user_positions, true );
            return $user_positions[ userid() ];
        }
    }
    return false;
}

/**
 * Function to update the widget positions
 * 
 * @return json encoded string
 */
function __update_dashboard_widget_positions() {
    $dashboard_positions = config('dashboard_positions');
    $userid = userid();
    
    if ( $dashboard_positions ) {
        $dashboard_positions = json_decode( $dashboard_positions, true );
    }
    
    $importance = post('importance');
    
    if ( $importance ) {
        foreach ( $importance as $importance => $widget_ids ) {
            $dashboard_positions[ $userid ][ post('place') ][ $importance ] = ( $widget_ids ? array_map("trim", explode(",", $widget_ids )) : '' );
        }
    }
    
    if ( config('dashboard_positions', json_encode( $dashboard_positions ) ) ) {
        return json_encode( array('success' => true ) );
    } else {
        return json_encode( array('err' => error('single') ) );
    }
}

/**
 * Function update widget states
 * @return json encoded string
 */
function __update_dashboard_widget_states() {
    $dashboard_states = config('dashboard_states');
    if ( $dashboard_states ) {
        $dashboard_states = json_decode( $dashboard_states, true );
    }
    
    $closed = '';
    
    if ( post('closed') ) {
         $closed = array_map("trim", explode(",", post('closed') ));
    }
    
    $dashboard_states[ userid() ][ post('place') ] = $closed;
    
    if ( config('dashboard_states', json_encode( $dashboard_states ) ) ) {
        return json_encode( array('success' => true ) );
    } else {
        return json_encode( array('err' => error('single') ) );
    }
}

/**
 * Gets the boxes that user have closed for current
 * placement
 * 
 * @param string $place
 * @return array
 */
function get_closed_boxes( $place ) {
    $user_data = config('dashboard_states');
    if ( $user_data ) {
        $userid = userid();
        $user_data = json_decode( $user_data, true );
        if ( $user_data[ $userid ][ $place ] ) {
            return $user_data[ $userid ][ $place ];
        }
    }
}

/**
 * Setup for dashboard for my account
 */
function setup_myaccount_dashboard() {
    add_dashboard_widget( 'myaccount','account_dashboard_messages','Messages','account_dashboard_messages' );
    add_dashboard_widget( 'myaccount','account_dashboard_user_content','Your Content','account_dashboard_user_content' );
    add_dashboard_widget( 'myaccount','account_dashboard_recent_video_comments','Recent Video Comments','account_dashboard_recent_video_comments' );
}

/**
 * myaccount dashboard, messages widget
 * 
 * @global object $cbpm
 * @param array $widget
 * @return string
 */
function account_dashboard_messages( $widget ) {
    global $cbpm;
    
    $file = 'blocks/account/dashboard_messages.html';
    $uid = userid();
    
    $params['inbox'] = $cbpm->get_user_inbox_messages( $uid, true );
    $params['outbox'] = $cbpm->get_user_outbox_messages( $uid, true );
    $params['notifications'] = $cbpm->get_user_notification_messages( $uid, true );
    $params['new_messages'] = $cbpm->get_new_messages ( $uid );
    $params['new_notifications'] = $cbpm->get_new_messages( $uid, 'notification' );
    
    $params['file'] = $file;
    $params[ 'widget' ] = $widget;
    
     return fetch_template_file( $params );
}

/**
 * myaccount dashboard, user content widget
 * 
 * @global object $userquery
 * @param array $widget
 * @return string
 */
function account_dashboard_user_content( $widget ) {
    global $userquery;
    $file = 'blocks/account/dashboard_your_content.html';
    
    $params['file'] = $file;
    $params['widget'] = $widget;
    $params['user'] = $userquery->udetails;
    
    return fetch_template_file( $params );
}

/**
 * myaccount dashboard, recent video comments widget
 * 
 * @global object $userquery
 * @param array $widget
 * @return string
 */
function account_dashboard_recent_video_comments ( $widget ) {
    global $userquery;
    
    if ( !userid() ){
        return false;
    }
    
    if ( $userquery->udetails['total_videos'] > 0 ) {
        $video_fields = array('videoid','videokey','title','description','views','date_added');
        $video_fields_query = tbl('video.%s');

        //List of user fields we need to show with the comment
        $userfields = array('username', 'email', 'userid','avatar', 'avatar_url');
        $user_fields_query = tbl('users.%s');
        
        //Applying filters...
        $userfields = apply_filters($userfields, 'comment_user_fields');
        foreach ($userfields as $userfield)
        {
            $ufields .= ',';
            $ufields .= sprintf( $user_fields_query, $userfield );
        }
        
        foreach( $video_fields as $video_field ) {
            $vfields .= ",";
            $vfields .= sprintf( $video_fields_query, $video_field );
            if ( $video_field == 'date_added' ) {
                $vfields .= ' as vdate_added';
            }
        }

        $query = "SELECT ".tbl('comments.*').$vfields.$ufields." FROM ".tbl('comments')." ";
        $query .= "LEFT JOIN ".tbl('video')." ON ".tbl('comments.type_id')." = ".tbl('video.videoid')." ";
        $query .= "LEFT JOIN ".tbl('users')." ON ".tbl('comments.userid')." = ".tbl('users.userid')." ";
        
        start_where();
        add_where(" ".tbl('comments.type_owner_id')." = ".userid());
        add_where(" ".tbl('comments.type')." = 'v' ");
        add_where( " ".tbl('comments.userid')." <> ".userid() );
        add_where(" ".tbl('comments.date_added')." BETWEEN SYSDATE() - INTERVAL 30 DAY AND SYSDATE() ");
        if ( get_where() ) {
            $query .= " WHERE ".get_where();
        }    
        end_where();

        $query .= " ORDER BY ".tbl('comments.date_added')." DESC LIMIT 20";
        $comments = db_select( $query );    
    }
        
    // Comment Template
    $params['file'] = 'blocks/account/dashboard_comments.html';
    $params['widget'] = $widget;
    $params['comments'] = $comments;

    return fetch_template_file( $params );
   
}
?>
