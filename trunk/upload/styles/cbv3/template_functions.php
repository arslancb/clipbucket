<?php

/**
 * widget callback function to display user box..
 */

function displayUserBox($widget)
{
    return Fetch('widgets/user-box.html');     
}

/**
 * Fetch user0box for admin area
 * @param type $widget
 * @return type 
 */
function displayUserBoxAdmin($widget)
{
    return Fetch('/layout/widgets/user-box-admin.html',FRONT_TEMPLATEDIR);
}

/**
 * outputs related videos widget....
 * 
 */
function displayRelatedVideos($widget)
{
    return Fetch('widgets/related-videos.html');  
}

/**
 * Get CBv3 Rating
 * @param type $video
 * @param type $type
 * @return string 
 */


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
    
    if($type=='video-bar')
    {
        assign('video',$video);
        
        if($video['rated_by']>0)
        {
            $rated_by = $video['rated_by'];
            $rating = $video['rating'];
            $rating_full = $rating*10;
            $likes = $rating_full*$rated_by/100;
            $likes = round($likes+0.49,0);
            
            $dislikes = $rated_by-$likes;
            
            assign('rating',array('rating'=>$rating,
                'dislikes'=>$dislikes,
                'likes'=>$likes,
                'rated_by'=>$rated_by,
                'rating_perc'=>$rating_full
                ));
        }
        
        
        return Fetch('blocks/rating.html');
    }
}


/**
 * Show-rating function for cbv3 template
 *  
 */
function cbv3_show_rating($rating)
{
    
    $array = array();
    if(error())
    {
        $array['err'] = error();
    }
    
    $array['rating'] = $rating;
    
    $rated_by = $rating['ratings'];
    $rating = $rating['rating'];
    $rating_full = $rating*10;
    $likes = $rating_full*$rated_by/100;
    $likes = round($likes+0.49,0);

    $dislikes = $rated_by-$likes;

    assign('rating',array('rating'=>$rating,
        'dislikes'=>$dislikes,
        'likes'=>$likes,
        'rated_by'=>$rated_by,
        'rating_perc'=>$rating_full
        ));
    
    $template = Fetch('blocks/rating.html');
    
    $array['template'] = $template;
    
    echo json_encode($array);
    
}

function cbv3_photo_tagger_options( $options ) {
    $options['labelWrapper'] = 'photo-tags';
    $options['buttonWrapper'] = 'photo-tagger-button';
    $options['addIcon'] = false;
    $options['autoComplete'] = true;
    
    return $options;
}

register_filter( 'tagger_configurations', 'cbv3_photo_tagger_options' );
?>