<?php

function list_thread_recipients($thread)
{
    $total_recipients = $thread['thread']['total_recipients'];
    $recipients = $thread['recipients'];

    $list = "";
    
    
    
    switch ($total_recipients)
    {
        case 2:
            {
                foreach ($recipients as $user)
                {
                    
                    if ($user['userid'] == userid())
                    {
                        //if($list)
                        //    $list .=" and ";
                        //$list .= lang("You");
                        //Just show other recipient
                    }else
                    {
                        if($list)
                            $list .=" and ";
                        $list .=name($user);
                    }
                }
            }
            break;
        case 3:
            {
                $count=0;
                foreach ($recipients as $user)
                {
                    if($count==1)
                        $list .=", ";
                    
                    if($count==2)
                        $list .=" and ";
                    
                    if ($user['userid'] == userid())
                        $list .= lang("You");
                    else
                        $list .=name($user);
                    
                    $count++;
                }
            }
            break;

        case ($total_recipients > 3) :
            {
                foreach ($recipients as $user)
                {
                    if ($user['userid'] == userid())
                        $list .= lang("You");
                    else
                        $list .=name($user);
                }
            }
            break;
    }
    
    return $list;
}


/**
 * Get thread link
 */
function get_thread_link($thread)
{
    if($thread['thread'])
        $thread = $thread['thread'];
    
    return BASEURL.'/private_message.php?mode=inbox&thread_id='.$thread['thread_id'];
}


?>
