<?php 

/*
Plugin Name: Clipbucket Elastic Search Module 
Description: This Plugins provides a new experience for efficient search 
Author: Fahad Abbas
Author Website: http://clip-bucket.com/
Version: 2.0
ClipBucket Version: 4.2
*/



define('CB_ES', this_plugin( __FILE__ ) );
assign('cb_es',CB_ES_MANAGER);

define('CB_ES','installed');
assign('cb_es',CB_ES);
/* PATHS */
define( 'CB_ES_DIR', PLUG_DIR.'/'.CB_ES);
define( 'CB_ES_URL', PLUG_URL.'/'.CB_ES );

define( 'CB_ES_ADMIN_DIR', CB_ES_DIR.'/admin');
define( 'CB_ES_ADMIN_URL', CB_ES_URL.'/admin');

assign('cb_es_dir',CB_ES_DIR);
assign('cb_es_url',CB_ES_URL);

assign('cb_es_admin_dir',CB_ES_ADMIN_DIR);
assign('cb_es_admin_url',CB_ES_ADMIN_URL);

assign('cb_es_ajax_url',CB_ES_URL.'/es_ajax.php');

define('CB_ES_INSTALLED', 'yes' );
assign('cb_es_installed',CB_ES_INSTALLED);



$Cbucket->links['search_result'] = array('module.php','module.php');

//fields required for search form
function elastic_mode_search() {
	echo '<input type="hidden" name="s" value="elastic">';
	echo '<input type="hidden" name="p" value="search">';
}


/*ini_set('display_errors', '-1');
error_reporting(E_ALL);*/
function is_es_server_running(){
	return checkRemoteFile(config('elastic_server_ip'));
}

/*if (is_es_server_running()){
	exit("YES");
}else{
	exit("NO");
}*/

//Elastic Search Class
include "elasticSearch.php";

register_anchor_function("elastic_mode_search","elastic_mode_search");

register_module('elastic_search',CB_ES_DIR.'/search.php');

add_admin_menu("Elastic Search","ES Indexer",'index_data.php',CB_ES.'/admin');
add_admin_menu("Elastic Search","Configure",'configure.php',CB_ES.'/admin');