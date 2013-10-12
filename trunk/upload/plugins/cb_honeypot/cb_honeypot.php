<?php
/*
Plugin Name: Clipbucket Honeypot
Description: An alternative method, of captcha, for stopping spam. A simple trick will confuse stupid bots but still might get some spam.<br/> If you are using any other captcha plugin, please disable them before installing this
Author: Fawaz Tahir
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
*/

define( 'CB_HONEYPOT', this_plugin( __FILE__ ) );

/* DIRECTORIES */
define( 'CB_HONEYPOT_BACKEND', 'backend' );
define( 'CB_HONEYPOT_FRONTEND', 'frontend' );

/* PATHS */
define( 'CB_HONEYPOT_DIR', PLUG_DIR.'/'.CB_HONEYPOT );
define( 'CB_HONEYPOT_URL', PLUG_URL.'/'.CB_HONEYPOT );
define( 'CB_HONEYPOT_BACKEND_DIR', CB_HONEYPOT_DIR.'/'.CB_HONEYPOT_BACKEND );
define( 'CB_HONEYPOT_BACKEND_URL', CB_HONEYPOT_URL.'/'.CB_HONEYPOT_BACKEND );
define( 'CB_HONEYPOT_FRONTEND_DIR', CB_HONEYPOT_DIR.'/'.CB_HONEYPOT_FRONTEND );
define( 'CB_HONEYPOT_FRONTEND_URL', CB_HONEYPOT_URL.'/'.CB_HONEYPOT_FRONTEND );

/* CONSTANTS */
define( 'CB_HONEYPOT_SPAM_SALT', '@!xD:P)}{}!!ONEPIECE[narutodeath]NOTE' );
define( 'CB_HONEYPOT_TIMESTAMP', time() );
define( 'CB_HONEYPOT_NAME_SEPARATOR', '-' );
define( 'CB_HONEYPOT_FORM_SUBMISSION_WINDOW', 5 );
define( 'CB_HONEYPOT_DEFAULT_VALUE', 'Pirate king rocked, Hokage shocked' );

if ( !file_exists( CB_HONEYPOT_BACKEND_DIR ) ) {
    @mkdir( CB_HONEYPOT_BACKEND_DIR, 0777, true );
}

if ( !file_exists( CB_HONEYPOT_FRONTEND_DIR ) ) {
    @mkdir( CB_HONEYPOT_FRONTEND_DIR, 0777, true );
}

/* GLOBALS */
$CB_HONEYPOT_NAME = array();

/* FUNCTIONS */
include( CB_HONEYPOT_DIR.'/includes/functions.php' );

cb_add_honeypot_name_part( CB_HONEYPOT );
cb_add_honeypot_name_part( $_SERVER[ 'REMOTE_ADDR' ] );
cb_add_honeypot_name_part( CB_HONEYPOT_TIMESTAMP );
cb_add_honeypot_name_part( CB_HONEYPOT_SPAM_SALT );

#cb_register_function( 'cb_honeypot_assignment', 'clipbucket_init_completed' );
register_cb_captcha( 'cb_honeypot_assignment', 'cb_verify_honeypot', false );
$Cbucket->add_header( CB_HONEYPOT_FRONTEND_DIR."/header.html" );