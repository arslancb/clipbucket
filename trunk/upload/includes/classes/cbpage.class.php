<?php

/**
 * Class use to create and manage simple pages
 * ie About us, Privacy Policy etc
 */
 
class cbpage
{
	
	var $page_tbl = '';
	
	
	/**
	 * _CONTRUCTOR
	 */
	function cbpage()
	{
		$this->page_tbl = 'pages';
	}
	
	
	/**
	 * Function used to create new page
	 * @param ARRAY
	 */
	function create_page($param)
	{
		global $db;
		$name = mysql_clean($param['page_name']);
		$title = mysql_clean($param['page_title']);
		$content = mysql_real_escape_string($param['page_content']);
		
		if(empty($name))
			e("Page name was empty");
		if(empty($title))
			e("Page title was empty");
		if(empty($content))
			e("Page content was empty");
		
		if(!error())
		{
			$db->insert(tbl($this->page_tbl),array("page_name","page_title","page_content","userid","date_added","active"),
											  array($name,$title,"|no_mc|".$content,userid(),now(),"yes"));
			e("New page has been added successfully","m");
			return false;
		}
		return false;
	}
	
	/**
	 * Function used to get details using id
	 */
	function get_page($id)
	{
		global $db;
		$result = $db->select(tbl($this->page_tbl),"*"," page_id ='$id' ");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to get all pages from database
	 */
	function get_pages()
	{
		global $db;
		$result = $db->select(tbl($this->page_tbl),"*");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	
	/**
	 * Function used to edit page
	 */
	function edit_page($param)
	{
		global $db;
		$id = $param['page_id'];
		$name = mysql_clean($param['page_name']);
		$title = mysql_clean($param['page_title']);
		$content = mysql_real_escape_string($param['page_content']);
		
		$page = $this->get_page($id);
		
		if(!$page)
			e("Page does not exist");
		if(empty($name))
			e("Page name was empty");
		if(empty($title))
			e("Page title was empty");
		if(empty($content))
			e("Page content was empty");
		
		if(!error())
		{
			$db->update(tbl($this->page_tbl),array("page_name","page_title","page_content"),
											  array($name,$title,$content)," page_id='$id'");
			e("Page has been updated","m");
		}
		
	}

	/**
	 * Function used to delete page
	 */
	function delete_page($id)
	{
		$page = $this->get_page($id);
		if(!$page)
			e("Page does not exist");
		if(!error())
		{
			$db->delete(tbl($this->page_tbl),array("page_id"),array($id));
			e("Page has been deleted successfully","m");
		}

	}
	
	/**
	 * Function used to create page link
	 */
	function page_link($pdetails)
	{
		//baseurl/page/$id/page_name
		if(SEO=='yes')
			return BASEURL.'/page/'.$pdetails['page_id'].'/'.SEO(strtolower($pdetails['page_name']));
		else
			return BASEURL.'/view_page.php?pid='.$pdetails['page_id'];
	}
	
	/**
	 * Function used to get page link fro id
	 */
	function get_page_link($id)
	{
		$page = $this->get_page($id);
		return $this->page_link($page);
	}
	
	
	/**
	 * Function used to activate, deactivate or to delete pages
	 */
	function page_actions($type,$id)
	{
		global $db;
		$page = $this->get_page($id);
		if(!$page)
			e("Page does not exist");
		else
		{
			switch($type)
			{
				case "activate";
				$db->update(tbl($this->page_tbl),array("active"),array("yes")," page_id='$id'");
				e("Page has been activated","m");
				break;
				case "deactivate";
				$db->update(tbl($this->page_tbl),array("active"),array("no")," page_id='$id'");
				e("Page has been dectivated","m");
				break;
				case "delete";
				{
					if($page['delete_able']=='yes')
					{
						$db->delete(tbl($this->page_tbl),array("page_id"),array($id));
						e("Page has been delete","m");
					}else
						e("You cannot delete this page");
				}
				
			}
		}
	}
	
	/**
	 * function used to check weather page is active or not
	 */
	function is_active($id)
	{
		global $db;
		$result = $db->count(tbl($this->page_tbl),"page_id"," page_id='$id' ");
		if($result>0)
			return true;
		else
			return falses;
	}
		
}

?>