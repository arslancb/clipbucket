<?php

/**
 * This class performs
 * all the search
 * Modify it at your own risk
 * read docs.clip-bucket.com for further details
 *
 * @Author : Arslan Hassan
 * @Software: CLipBucket v2
 * @Since : 07 October 2009
 * 
 * @license:CBLA
 */



class cbsearch
{
	/**
	 * Variable for search key
	 */
	var $key = "";
	
	/**
	 * Search Table
	 */
	var $db_tbl = 'video';
	
	var $columns = array();
	
	var $category = '';
	
	var $cat_tbl = '';
	
	var $date_margin = '';
	
	var $sorting = array();
	
	var $sort_by = 'date_added';
	
	var $sory_order = 'DESC';
	
	var $limit = 25;
	
	var $total_results = 0;
	
	
	var $multi_cat = true;
	
	var $date_added_colum = 'date_added';
	
	/**
	 * this tells the cbsearch weather to get results from 
	 * users table or not. if it is set to true, it will get 
	 * user details where user_id = table.useri_id
	 */
	var $has_user_id = false; 
	
	
	/**
	 * ClipBucket Search System works pretty easily
	 * 1. It loads the appropriate class which defines what kind of search to perform and how to operate it
	 * 2. Gets the result and save it in variable
	 * 3. Loop results and assign VARIABLE.DATA to TEMPLATE_VAR
	 * 4. Call display_template to show the result
	 */
	 
	 var $display_template = '';
	 var $template_var = '';
	 
	 
	/**
	 * want to use MATCH - AGAINST method instead of LIKE
	 * simply set this variable to true
	 */
	var $use_match_method = false;
	
	/**
	 * Fields to use for MATCH - AGAINST method
	 */
	var $match_fields = array();
	 
	/**
	 * INITIATION SEARCH
	 */
	function init_search($type='video')
	{
		global $Cbucket;
		if($Cbucket->search_types[$type])
		{
			$obj = $Cbucket->search_types[$type];
			global ${$obj};
			${$obj}->init_search();
			return ${$obj}->search;
		}else
		{
			global $cbvid;
			$cbvid->init_search();
			return $cbvid->search;
		}
	}
	
	
	/**
	 * Variable to hold search query condition
	 */
	var $query_conds = array();
	
	function search()
	{
		global $db;
		
		$ma_query = "";
		#Checking for columns
		if(!$this->use_match_method)
			foreach($this->columns as $column)
			{
				$this->query_cond($column);
			}
		else
		{
			if($this->key)
			{
				$this->set_the_key();
				$ma_query = $this->match_against_query();
				$this->add_cond($ma_query);
				//add order
				$add_select_field = ",".$ma_query." AS Resource";
				//$sorting = "Resource ASC";
			}else
			{
				//do nothing
			}
			
			foreach($this->columns as $column)
			{
				if($column['value'] == 'static') 
				$this->query_cond($column);
			}
		}
		
		
		
		#Checking for category
		if(isset($this->category))
		{
			$this->cat_to_query($this->category,$this->multi_cat);
		}
		#Setting Date Margin
		if($this->date_margin!='')
		{
			$this->add_cond('('.$this->date_margin($this->date_added_colum).')');
		}
		
		#Sorting
		if(isset($this->sort_by) && !$sorting)
		{
			$sorting = $this->sorting[$this->sort_by];
		}
		
		$condition = "";
		#Creating Condition
		foreach($this->query_conds as $cond)
		{
			$condition .= $cond." ";
		}	
		
		if($this->has_user_id)
		{
			$query_cond = "(".$condition.")";
			if($condition)
				$query_cond .= " AND ";
			else
				$query_cond = $condition;
			$results = $db->select(tbl($this->db_tbl.",users"),
								tbl($this->db_tbl.'.*,users.userid,users.username').$add_select_field,
							$query_cond." ".tbl($this->db_tbl).".userid=".tbl("users.userid")." AND ".tbl($this->db_tbl).".active='yes'",$this->limit,$sorting);
							
		

			$this->total_results = $db->count(tbl($this->db_tbl),'*',$condition);
			
		}else
		{
			$results = $db->select(tbl($this->db_tbl),'*',$condition,$this->limit,$sorting);
			//echo $db->db_query;
			$this->total_results = $db->count(tbl($this->db_tbl),'*',$condition);
		}
		
		
		
		return $results;
	}
	
	
	/**
	 * function used to add query cond
	 */
	function add_cond($cond,$op='AND')
	{
		if(count($this->query_conds)>0)
			$op = $op;
		else
			$op = '';
			
		$this->query_conds[] = $op." ".$cond;
	}
	
	
	/**
	 * Function used to convert array to query condition
	 */
	function query_cond($array)
	{
		//Checking Condition Type
		$type = strtolower($array['type']);
		
		
		if($type !='=' && $type!='<' && $type!='>' && $type!='<=' && $type!='>=' && $type!='like' && $type!='match'
			 && $type!='!='  && $type!='<>')
		{
			$type = '=';
		}
		
		
		$var = $array['var'];
		if(empty($var))
		{
			$var = "{KEY}";
		}
		
		$array['op'] = $array['op']?$array['op']:'AND';
		
		
		if(count($this->query_conds)>0)
			$op = $array['op'];
		else
			$op = '';
			
		if($array['value'] == 'static')
		{
			$this->query_conds[] = $op." ".tbl($this->db_tbl).".".$array['field']." ".$type." '".$array['var']."'";
			return true;
		}
		
		
		if(!empty($this->key) && $type != 'match')	
			$this->query_conds[] = $op." ".tbl($this->db_tbl).".".$array['field']." ".$type." '".preg_replace("/{KEY}/",$this->key,$var)."'";
		if(!empty($this->key) && $type == 'match')
			$this->query_conds[] = $op." MATCH(".tbl($this->db_tbl).".".$array['field'].") AGAINST('".preg_replace("/{KEY}/",$this->key,$var)."'
										IN BOOLEAN MODE)";
										
	}
	
	/**
	 * Category to query
	 * fucntion used to covert category to query
	 */
	function cat_to_query($input,$multi=TRUE)
	{
		if(!empty($input))
		{
			if(!is_array($input))
				$cats = explode(",",$input);
			else
				$cats = $input;
				
			$query = "";
			foreach($cats as $cat)
			{
				if(!empty($query))
					$query .=" OR ";
				
				if($multi)
					$query .=" ".tbl($this->db_tbl).".category LIKE '%#$cat#%' ";
				else
					$query .=" ".tbl($this->db_tbl).".category = '$cat' ";
			}
	
			if(count($this->query_conds)>0)
				$op = "AND";
			else
				$op = '';
			$this->query_conds[] = $op." (".$query.") "; 
		}
	}
	
	
	/**
	 * Function used to set date margin query
	 * it is used to get results within defined time span
	 * ie today, this week , this month or this year
	 */
	function date_margin($date_column='date_added',$date_margin=NULL)
	{
		if(!$date_margin)
			$date_margin = $this->date_margin;
			
		if(!empty($date_margin))
		{
			switch($date_margin)
			{
				case "today":
				{
					$cond = " curdate() = date($date_column) ";
				}
				break;
				
				case "yesterday":
				{
					$cond = " CONCAT(YEAR(curdate()),DAYOFYEAR(curdate())-1) = CONCAT(YEAR($date_column),DAYOFYEAR($date_column)) ";
				}
				break;
				
				case "this_week":
				case "week":
				case "thisweek":
				{
					$cond = " YEARWEEK($date_column)=YEARWEEK(curdate())  ";
				}
				break;
				
				case "this_month":
				case "month":
				case "thismonth":
				{
					$cond = " CONCAT(YEAR(curdate()),MONTH(curdate())) = CONCAT(YEAR($date_column),MONTH($date_column)) ";
				}
				break;
				
				case "this_year":
				case "year":
				case "thisyear":
				{
					$cond = "YEAR(curdate()) = YEAR($date_column)";
				}
				break;
				
				case "all_time":
				case "alltime":
				case "all":
				default:
				{
					$cond = " $date_column != '' ";
				}
				break;
				
				case "last_week":
				case "lastweek":
				{
					$cond = " YEARWEEK($date_column)=YEARWEEK(curdate())-1  ";
				}
				break;
				
				case "last_month":
				case "lastmonth":
				{
					$lastmonth = date("Ym", strtotime("last month"));
					$cond = " CONCAT(YEAR($date_column),MONTH($date_column)) ='$lastmonth' ";
				}
				break;
				
				
				case "last_year":
				case "lastyear":
				{
					$cond = "YEAR(curdate())-1 = YEAR($date_column)";
				}
				break;
			}
			
			return $cond;
		}
	}
	
	/**
	 * Function used to define date margins
	 */
	function date_margins()
	{
		$this->date_margins = array
		(
		 'alltime'	=> lang('alltime'),
		 'today'	=> lang('today'),
		 'yesterday'=> lang('yesterday'),
		 'thisweek'	=> lang('thisweek'),
		 'lastweek'	=> lang('lastweek'),
		 'thismonth'=> lang('thismonth'),
		 'lastmonth'=> lang('lastmonth'),
		 'thisyear'	=> lang('thisyear'),
		 'lastyear'	=> lang('lastyear'),
		 );
		
		return $this->date_margins;
	}
	
	
	/**
	 * Function used to create match_against query
	 * it will simple loop the input fields
	 * add table prefix and create MATCH(fields) AGAINST (keyword) query
	 * @return - MATCH (fields) AGAINST (kewyord)
	 */
	function match_against_query()
	{
		
		
		$cond = " MATCH ( ";
		$count = 0;
		foreach($this->match_fields as $field)
		{	
		
			if($count>0)
				$cond .= ",";
			$cond .= tbl($this->db_tbl).".".$field;
			
			$count++;
		}
		$cond .= ")"; //Here match(fields1,field2) thing is finished
		
		//now add against
		$cond .= " AGAINST ('".$this->key."' IN BOOLEAN MODE) ";
		
		return $cond;
	}
	
	/**
	 * Function used to set the key
	 */
	function set_the_key($string=null)
	{
		if(!$string)
		$string = $this->key;
		$pattern = array('/(\w+)/i','/(\++)/i',"/(\-\+)/i",'/(\-+)/i');
		$replacement = array('+$1',"+","-","-");
		return $this->key = preg_replace($pattern, $replacement, $string);
	}
}

?>