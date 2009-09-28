<?php

/**
 * All Functions that
 * are used by admin,
 * registered here
 */
 
 
//Registering Admin Options for Watch Video

if(has_access('admin_access',TRUE))
{
	register_anchor('my_func','watch_admin_options');
}

?>