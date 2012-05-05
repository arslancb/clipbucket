<?php

/**
 * widget callback function to display user box..
 */

function displayUserBox($widget)
{
    return Fetch('widgets/user-box.html');     
}

function displayUserBoxAdmin($widget)
{
    return Fetch('/layout/widgets/user-box-admin.html',FRONT_TEMPLATEDIR);
}
?>