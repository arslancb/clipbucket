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
	
	/**
	 * Variable to hold search query condition
	 */
	var $query_conds = array();
	
	function search()
	{
		global $db;
		#Checking for columns
		foreach($this->columns as $column)
		{
			$this->query_cond($column);
		}
		#Checking for category
		if(isset($this->category))
		{
			$this->cat_to_query($this->category);
		}
		#Setting Date Margin
		if($this->date_margin!='')
		{
			$this->add_cond('('.$this->date_margin().')');
		}
		
		#Sorting
		if(isset($this->sort_by))
		{
			$sorting = $this->sorting[$this->sort_by];
		}
		
		$condition = "";
		#Creating Condition
		foreach($this->query_conds as $cond)
		{
			$condition .= $cond." ";
		}
		
		$results = $db->select($this->db_tbl,'*',$condition,$this->limit,$sorting);
		$this->total_results = $db->count($this->db_tbl,'*',$condition);
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
		if($type !='=' && $type!='<' && $type!='>' && $type!='<=' && $type!='>=' && $type!='like')
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
		if(!empty($this->key))	
			$this->query_conds[] = $op." ".$array['field']." ".$type." '".preg_replace("/{KEY}/",$this->key,$var)."'";

	}
	
	/**
	 * Category to query
	 * fucntion used to covert category to query
	 */
	function cat_to_query($input)
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
				$query .=" category LIKE '%#$cat#%' ";
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
					$cond = " CONCAT(YEAR(curdate()),MONTH(curdate()))-1 = CONCAT(YEAR($date_column),MONTH($date_column)) ";
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

}

?>