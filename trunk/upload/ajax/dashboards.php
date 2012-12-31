<?php

include("../includes/config.inc.php");

$mode = $_POST['mode'];


switch ( $mode ) {
    
    case "update_dasboard_widget_states": {
        $userid = userid();
        if ( !$userid ) {
            exit( json_encode( array('err' => array(lang( 'Invalid request' ))) ) );
        }
        
        echo __update_dashboard_widget_states();
    }
    break;
    
    case "update_dashboard_widget_positions": {
        $userid = userid();
        if ( !$userid ) {
            exit( json_encode( array('err' => array(lang( 'Invalid request' ))) ) );
        }
        
         echo __update_dashboard_widget_positions();
    }
    break;

    default: {
            exit( json_encode( array('err' => array(lang( 'Invalid request' ))) ) );
        }
}
?>
