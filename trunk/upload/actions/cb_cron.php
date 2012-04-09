#!/usr/bin/php -q
<?php

/**
 * @name : ClipBucket Cron
 * @package : ClipBucket
 */

set_time_limit(0);

include("../includes/config.inc.php");

$interval = 10; //in seconds...

while(1)
{
    //Clearing Sesssions..
    sleep($interval);    
}
?>