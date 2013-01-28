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
function add_dashboard_widget( $place, $id, $name, $display_callback, $callback = null, $importance = 'normal' ) {
    if ( !dashboard_widget_exists( $id, $place, $importance ) ) {
        
        if ( !function_exists( $display_callback ) ) {
            return false;
        }
        
        $importance = _check_widget_importance( $importance );
        
        if ( !is_null( $callback )  /* and function_exists( $callback ) */ ) {
            $widget_id = mysql_clean( $_GET['configure'] );
            if ( $widget_id == $id ) {
                $switch_link = '<div class="configure-dashboard-widget cancel-configuration configure-'.$id.' cancel-configure-'.$id.'"><a href=" '.queryString( '', array('configure') ).' ">'.lang('Cancel').'</a></div>';
                $display_callback = '__dashboard_widget_options_callback';  
            } else {
                $switch_link = '<div class="configure-dashboard-widget configure-'.$id.'"><a href=" '.queryString( 'configure='.$id, array('configure') ).'#'.$id.'-'.$importance.' ">'.lang('Configure').'</a></div>';
            }
        }
        
        $dashboard_widget[ $id ] = array(
            'place' => $place, 
            'id' => $id,
            'name' => $name,
            'display_callback' => $display_callback,
            'importance' => $importance,
            'callback' => $callback,
            'switch_link' => $switch_link
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
                    $output .= '<div id="'.$place.'-'.$important.'-importance" class="dashboard-widgets dashboard-widgets-'.$important.'-importance '.$place.'-widgets has-widgets widgets-'.$total_dashboard_widgets.'" data-importance="'.$important.'">';
                    foreach ( $dashboard_widgets as $dashboard_widget ) {
                        $hidden = ( $closed ? ( in_array( $dashboard_widget['id'], $closed ) ) ? ' closed' : '' : '' );
                        $output .= '<div id="'.$dashboard_widget['id'].'-'.$dashboard_widget['importance'].'" class="dashboard-widget '.SEO( strtolower( $dashboard_widget['name'] ) ).' '.$dashboard_widget['id'].' is-'.$important.''.$hidden.'" data-id="'.$dashboard_widget['id'].'">';
                        $output .= '<div class="dashboard-widget-toggler"> <b></b> </div>';
                        $output .= '<div class="dashboard-widget-handler"><b></b></div>';
                        $output .= '<h3 class="dashboard-widget-name">'.$dashboard_widget['name'].'</h3>';                        
                        $output .= '<div class="dashboard-content" id="'.$place.'-'.$dashboard_widget['importance'].'-'.$dashboard_widget['id'].'">';
                                                
                        if ( $dashboard_widget['switch_link'] ) {
                            $output .= $dashboard_widget['switch_link'];
                        }
                        
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
 * Calls the callback of widget provided
 * @param array $widget
 * @return string
 */
function __call_widget_options_callback( $widget ) {
    if ( $widget['callback'] and function_exists( $widget['callback'] ) ) {
        $fields = $widget['callback']( $widget );
        return __dashboard_widget_options_fields( $widget, $fields );  
    }
}

/**
 * Display form fields of widget configurations
 * @global object $formObj
 * @param array $widget
 * @param array $fields
 * @return string
 */
function __dashboard_widget_options_fields( $widget, $fields ) {
    global $formObj;
    
    if ( $fields ) {
        $output = '';
        foreach( $fields as $field ) {
            $name = $widget['id'].'['.($field['db_field'] ? $field['db_field'] : $field['name']).']';
            $field['name'] = $field['id'] = $name;           
            $output .= '<div class="control-group">';
            $output .= '<label class="control-label" for="'.$name.'">'.$field['title'].'</label>';
            $output .= '<div class="controls">';
            $output .= $formObj->createField( $field );
            if ( $field['hint_1'] ) {
                $output .= ' <span class="help-block">'.$field['hint_1'].'</span>';
            }
            $output .= '</div>';
            $output .= '</div>';
        }
        
        return $output;
    } else {
        return '<center>No configurations found</center>';
    }
    
}

/**
 * Display form for widget configuration
 * @param array $widget
 * @return string
 */
function __dashboard_widget_options_callback( $widget ) {    
    $output = '<form action="" method="post" name="configure_widget" id="'.$widget['id'].'-configurations" class="dashboard-widget-configure-form form-horizontal">';
    $output .= __call_widget_options_callback( $widget ); 
    $output .= '<div class="form-actions dashboard-widget-configure-form-actions"><button class="btn dashboard-widget-configure-button" name="save_dashboard_widget_configs" id="save_dashboard_widget_configs">'.lang('Save Changes').'</button></div>';
    $output .= '<input type="hidden" name="id" value="'.$widget['id'].'" />';
    $output .= '<input type="hidden" name="importance" value="'.$widget['importance'].'" />';
    $output .= '<input type="hidden" name="place" value="'.$widget['place'].'" />';
    $output .= '</form>';
    
    return $output;    
}

/**
 * This function gets configuration for provided widget id
 * @param string $id
 * @return boolean
 */
function get_dashboard_widget_configs( $id ) {
    $user_configs = config('dashboard_configs');
    if ( $user_configs ) {
        $userid = userid();
        if ( $userid ) {
            $user_configs = json_decode( $user_configs, true );
            if ( $user_configs[ $userid ][ $id ] ) {
                return $user_configs[ $userid ][ $id ];
            }
        }
    }
    
    return false;
}

/**
 * This function updates dashboard widget configuration
 * @param array $configs
 * @return boolean
 */
function update_dashboard_widget_configs( $configs ) {
    
    if ( confirm_config_form_submission() ) {
        $widget_name = mysql_clean( post('id') );
        $db_configs = config('dashboard_configs');
        if ( $db_configs ) {
            $db_configs = json_decode( $db_configs, true );
        }
        
        $userid = userid();
        
        if ( !$db_configs ) {
            $db_configs = array();
        }

        if ( !$db_configs[ $userid ] ) {
            $db_configs[ $userid ] = array();
        }

        if ( !$db_configs[ $userid ][ $widget_name ] ) {
            $db_configs[ $userid ][ $widget_name ] = array();
        }
        
        foreach ( $configs as $name => $value ) {
            $db_configs[ $userid ][ $widget_name ][ $name ] = $value;
        }
        
        config( 'dashboard_configs', json_encode( $db_configs ) );
        
        return true;
    } else {
        return false;
    }
    
}

/**
 * This function confirms that configuration form was submitted or not
 * @return boolean
 */
function confirm_config_form_submission() { 
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' and isset( $_POST['save_dashboard_widget_configs'] ) ) {  
        if ( !userid() ) {
            return false;
        }
        return true;
    } else {
        return false;
    }
}

/**
 * Setup for dashboard for my account
 */
function setup_myaccount_dashboard() {
    
    add_dashboard_widget( 'myaccount','account_dashboard_messages','Messages','account_dashboard_messages' );
    add_dashboard_widget( 'myaccount','account_dashboard_user_content','Your Content','account_dashboard_user_content' );
    add_dashboard_widget( 'myaccount','account_dashboard_recent_video_comments','Recent Video Comments','account_dashboard_recent_video_comments', 'account_dashboard_recent_video_comments_callback' );
    
    
    /* Handle form submission here */
    if ( confirm_config_form_submission() ) {
        $place = mysql_clean( post('place') );
        $id = mysql_clean( post('id') );
        $importance = mysql_clean( post('importance') );
        if ( $place and $id and $importance ) {
            $dashboard = get_dashboard( $place );
            if ( $dashboard[ $importance ] and $dashboard[ $importance ][ $id ] ) {
                $widget = $dashboard[ $importance ][ $id ];
                __call_widget_options_callback( $widget );
                redirect_to( queryString( '', array('configure') ) );
                exit('Redirecting ...');
            }
        }
    }
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
    
    $configs = get_dashboard_widget_configs( $widget['id'] );
    
    $no_of_comments = $configs['number_of_comments'] ? $configs['number_of_comments'] : 15;
    $no_of_days = $configs['number_of_days'] ? $configs['number_of_days'] : 8;
    
    if ( $userquery->udetails['total_videos'] > 0 ) {
        
        $fields = array(
            'video' => array('videoid','videokey','title','description','views'),
            'users' => get_user_fields(),
            'comments' => array( 'comment_id','type','comment','userid','type_id','type_owner_id','date_added' )
        );
        
        $fields = tbl_fields( $fields );

        $query = "SELECT $fields  FROM ".tbl('comments')." AS comments ";
        $query .= "LEFT JOIN ".tbl('video')." AS video ON ".('comments.type_id')." = ".('video.videoid')." ";
        $query .= "LEFT JOIN ".tbl('users')." AS users ON ".('comments.userid')." = ".('users.userid')." ";
        
        start_where();
        add_where(" ".('comments.type_owner_id')." = ".userid());
        add_where(" ".('comments.type')." = 'v' ");
        add_where( " ".('comments.userid')." <> ".userid() );
        add_where(" ". ('comments.date_added')." BETWEEN SYSDATE() - INTERVAL $no_of_days DAY AND SYSDATE() ");
        if ( get_where() ) {
            $query .= " WHERE ".get_where();
        }    
        end_where();

        $query .= " ORDER BY ".('comments.date_added')." DESC LIMIT $no_of_comments";
        $comments = db_select( $query );    
    }
        
    // Comment Template
    $params['file'] = 'blocks/account/dashboard_comments.html';
    $params['widget'] = $widget;
    $params['comments'] = $comments;
    $params['configs'] = $configs;
    
    return fetch_template_file( $params );
   
}

function account_dashboard_recent_video_comments_callback( $widget ) {
    $config = get_dashboard_widget_configs( $widget['id'] );
    $no_of_comments = $config['number_of_comments'] ? $config['number_of_comments'] : 15;
    $no_of_days = $config['number_of_days'] ? $config['number_of_days'] : 8;
    
    if ( confirm_config_form_submission() ) {
        $no_of_comments = mysql_clean( $_POST[$widget['id']]['number_of_comments']  );
        $no_of_days = mysql_clean( $_POST[$widget['id']]['number_of_days']  );
        
        $config['number_of_comments'] = abs( intval( $no_of_comments ) );
        $config['number_of_days'] = abs( intval( $no_of_days ) );
        
        update_dashboard_widget_configs( $config );
    }
    
    $fields[] = array(
        'title' => lang('Number of comments'),
        'type' => 'textfield',
        'value' => $no_of_comments,
        'db_field' => 'number_of_comments',
        'name' => 'number_of_comments'
    );

    $fields[] = array(
        'title' => lang('Number of days'),
        'type' => 'dropdown',
        'value' => array( '6' => lang('Last 6 Days'), '8' => lang('Last 8 Days'), '10' => lang('Last 10 Days'), '12' => lang('Last 12 Days'), '15' => lang('Last 15 Days') ),
        'checked' => $no_of_days,
        'db_field' => 'number_of_days',
        'name' => 'number_of_days'
    );
        
    return $fields;
}
?>
