<?php

include("../../includes/admin_config.php");
//Ajax categories...

$mode = post('mode');

switch($mode)
{
    case "add_category":
    {
        $cid = $cbvid->add_category($_POST);
        //$cid = 18;
        if(error())
        {
            echo json_encode(array('err'=>error()));
        }else{
           $category =  $cbvid->get_category($cid);
           assign('category',$category);
           
           $category_template = Fetch('blocks/category.html');
           
           echo json_encode(array('success'=>'yes','data'=>$category_template,'cid'=>$cid));
        }
    }
    break;
    
    case "delete_category":
    {
        $cid = mysql_clean(post('cid'));
        
        $cbvid->delete_category($cid);
        
        if(error())
        {
            echo json_encode(array('err'=>error()));
        }else
            echo json_encodE(array('success'=>'yes'));
    }
    break;
    
    case "make_default":
    {
        $cid = mysql_clean(post('cid'));
        $cbvid->make_default_category($cid);
        $name = post('name');
        if(error())
        {
            echo json_encode(array('err'=>error()));
        }else
            echo json_encodE(array('success'=>'yes',"msg"=>array("<strong>$name</strong> has been set as default")));
    }
    break;
    
    case "edit_category":
    {
        $cid = mysql_clean(post('cid'));
        $category = $cbvid->get_category($cid);
        if($category)
        {
            assign('c',$category);
            $template = Fetch('blocks/edit-category.html');
            echo json_encode(array('success'=>'yes','template'=>$template,
                'title'=>  loading_pointer(array('place'=>'save-category')).' '.$category['category_name']
                ));
            
        }else{
            echo json_encode(array('err'=>array('Unable to fetch category details')));
        }
    }
    break;

    case "save_category":
    {
        $cbvid->update_category($_POST);
        if(error())
        {
            echo json_encode(array('err'=>error('single')));
        }else
        {
            echo json_encode(array('success'=>'yes','msg'=>array('Category has been updated successfully')));
        }
    }
    
    break;
    
    case "update_order":
    {
        $cid = mysql_clean(post('cid'));
        $order = mysql_clean(post('order'));
        
        $cbvid->update_cat_order($cid,$order);
        
        if(error())
            echo json_encode (array('err'=>erro()));
        else
            echo json_encode(array('success'=>'yes'));
    }
}
?>