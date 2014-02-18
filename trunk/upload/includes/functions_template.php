<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/28/13
 * Time: 12:20 PM
 * To change this template use File | Settings | File Templates.
 */

//Redirect Using JAVASCRIPT

function redirect_to($url){
    echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
    exit("Javascript is turned off, <a href='$url'>click here to go to requested page</a>");
}

//Test function to return template file
function Fetch($name,$inside=FALSE)
{
    global $cbtpl;
    if($inside){
        $file = $cbtpl->fetch($name);
    }
    else{
        //var_dump($name);die();
        $file = $cbtpl->fetch(LAYOUT.'/'.$name);
    }

    return $file;
}

//Simple Template Displaying Function

function Template($template,$layout=true){
    global $admin_area,$cbtpl;
    if($layout)
        $cbtpl->display(LAYOUT.'/'.$template);
    else
        $cbtpl->display($template);

    if($template == 'footer.html' && $admin_area !=TRUE){
        $cbtpl->display(BASEDIR.'/includes/templatelib/'.$template);
    }
    if($template == 'header.html'){
        $cbtpl->display(BASEDIR.'/includes/templatelib/'.$template);
    }
}

function Assign($name,$value)
{
    global $cbtpl;
    $cbtpl->assign($name,$value);
}


/**
 * Return Head menu of CLipBucket front-end
 *
 * @param Array $params
 * @return Array
 */
function cb_menu($params=NULL){ global $Cbucket; return $Cbucket->cbMenu($params); }


/**
 * Function used to call display
 */
function display_it()
{
    try
    {
        global $ClipBucket, $db;
        $dir = LAYOUT;
        foreach($ClipBucket->template_files as $file)
        {
            if(file_exists(LAYOUT.'/'.$file['file']) || is_array($file))
            {

                if(!$ClipBucket->show_page && $file['follow_show_page'])
                {

                }else
                {
                    if(!is_array($file))
                        $new_list[] = $file;
                    else
                    {
                        if(isset($file['folder']) && file_exists($file['folder'].'/'.$file['file']))
                            $new_list[] = $file['folder'].'/'.$file['file'];
                        else
                            $new_list[] = $file['file'];
                    }
                }
            }
        }

        assign('template_files',$new_list);

        Template('body.html');

        footer();
    }catch(SmartyException $e)
    {
        show_cb_error($e);
    }
}