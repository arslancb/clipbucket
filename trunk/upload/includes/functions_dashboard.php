<?php

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
function add_dashboard_widget( $place, $id, $name, $display_callback, $importance = 'normal', $description = null, $callback = null ) {
    if ( !dashboard_widget_exists( $id, $place, $importance ) ) {
        
        if ( !function_exists( $display_callback ) ) {
            return false;
        }
        
        $importance = _check_widget_importance( $importance );
        $position = get_last_dashboard_widget_position( $place, $importance );
        $dashboard_widget[ $id ] = array(
            'id' => $id,
            'name' => $name,
            'display_callback' => $display_callback,
            'importance' => $importance,
            'description' => $description,
            'position' => $position ? ( $position+1 ) : (int)1,
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
        $output = ''; // Dashboard widgets output
        $importance = get_dashboard_widget_importance();

        foreach ( $importance as $important ) {
            if ( isset( $dashboard[ $important ] ) ) {
                $dashboard_widgets = $dashboard[ $important ];
                $dashboard_widgets = apply_filters( $dashboard_widgets, 'dashboard_widgets' );
                $total_dashboard_widgets = count( $dashboard_widgets );
                if ( $dashboard_widgets ) {
                    $output .= '<div id="'.$place.'-'.$important.'-importance" class="dashboard-widgets dashboard-widgets-'.$important.'-importance '.$place.'-widgets has-'.$total_dashboard_widgets.'-widgets" data-importance="'.$important.'" data-palce="'.$place.'">';
                    foreach ( $dashboard_widgets as $dashboard_widget ) {
                        $output .= '<div id="'.$dashboard_widget['id'].'-'.$dashboard_widget['importance'].'" class="dashboard-widget '.SEO( strtolower( $dashboard_widget['name'] ) ).' '.$dashboard_widget['id'].' is-'.$important.'">';
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
        return $output;
    }
    return false;
}

/**
 * This orders the widgets of specifc place and important according
 * to their positions.
 * 
 * @todo Make positioning of widgets work
 * @param array $widgets
 * @return array
 */
function _order_dashboard_widgets_to_position( $widgets ) {
    
    foreach ( $widgets as $widget ) {
        $tmp_arr[ $widget['position'] ] = $widget['id'];
    }
    
    ksort( $tmp_arr );
        
    foreach ( $tmp_arr as $tmp ) {
        $ordered_widgets[ $tmp ] = $widgets[ $tmp ];
    }
    
    return $ordered_widgets;
}

function setup_myaccount_dashboard() {
    add_dashboard_widget('myaccount','account_dashboard_videos','Videos','account_dashboard_videos');
    add_dashboard_widget('myaccount','account_dashboard_photos','Photos','account_dashboard_photos');
    add_dashboard_widget('myaccount','account_dashboard_messages','Messages','account_dashboard_messages', 'highest');
}

function account_dashboard_videos( $widget ) {
    return $widget['name'];
}

function account_dashboard_photos ( $widget ) {
    return $widget['name'];
}

function account_dashboard_messages( $widget ) {
    return $widget['name'];
}
?>
