<?php

// Funtions ued in for admin area only

function list_admin_categories($categories)
{
    foreach($categories as $category)
    {
        if(!$category['parent_id'])
            list_category($category);
    }
}

function list_category($category,$prefix=NULL)
{
    if($category)
    {
        assign('prefix',$prefix);
        assign('category',$category);
        Template('blocks/category.html');

        if($category['children'])
        {
            $prefix = $prefix.' &ndash;	 ';
            foreach($category['children'] as $child)
            {
                list_category($child,$prefix);
            }
        }
    }
}
?>
