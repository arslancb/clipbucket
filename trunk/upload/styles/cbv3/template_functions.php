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

function cbv3_rating($video,$type='perc')
{
    if($type=='perc')
    {
        $rating = $video['rating'];
        
        if($rating>5)
        {
            $rating_output = '<span class="rating-text rating-green">';
        }elseif($rating<5 && $rating)
        {
            $rating_output = '<span class="rating-text rating-red">';
        }else
        {
            $rating_output = '<span class="rating-text">';
        }
        
         $rating_output .= round($rating*10+0.49,0);
         $rating_output .= '%</span>';
         
         return $rating_output;
    }
}
?>