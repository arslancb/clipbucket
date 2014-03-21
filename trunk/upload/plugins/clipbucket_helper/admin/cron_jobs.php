<?php

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Clip-Bucket Helper');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Cron Jobs');
}

template_files(PLUG_DIR.'/clipbucket_helper/admin/cron_jobs.html',true);
?>