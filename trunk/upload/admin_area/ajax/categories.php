<?php

include("../../includes/admin_config.php");
//Ajax categories...

$mode = post('mode');

$type = $_POST['type'];
assign('type',$type);


switch($type)
{
    case "video":case "videos":case "vid":default:
        $obj = $cbvid;
        break;
    case "user":case "users":case "channel":case "u":
        $obj = $userquery;
        break;
    case "group": case "groups":case "g":
        $obj = $cbgroup;
        break;
    case "collection": case "c":
        $obj = $cbcollection;
        break;
}
        
        
switch($mode)
{
    case "add_category":
    {

        $cid = $obj->add_category($_POST);
        //$cid = 18;
        if(error())
        {
            echo json_encode(array('err'=>error()));
        }else{
           $category =  $obj->get_category($cid);
           assign('category',$category);
           
           $category_template = Fetch('blocks/category.html');
           
           echo json_encode(array('success'=>'yes','data'=>$category_template,'cid'=>$cid));
        }
    }
    break;
    
    case "delete_category":
    {
        $cid = mysql_clean(post('cid'));
        
        $obj->delete_category($cid);
        
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
        $obj->make_default_category($cid);
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
        $category = $obj->get_category($cid);

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
        $type = $_POST['type'];
        assign('type',$type);

        $category = $obj->update_category($_POST);
     
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
        
        $obj->update_cat_order($cid,$order);
        
        if(error())
            echo json_encode (array('err'=>erro()));
        else
            echo json_encode(array('success'=>'yes'));
    }
}
?>