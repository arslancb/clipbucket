<?php
/**
 * This File contains a class that extends cbsearch in order to modifiy it's behaviour and accept extended search
 */
class ExtendSearch extends cbsearch {
	
	/**
	 * Function used to convert array to query condition
	 * 
	 * Overrride of cbsearch query_cond function to be able to accept searches in other table than $this->db_tbl
	 * By default if $array doesn't contains "table" field then run the same code than the original function
	 * Otherwise take the $array['table'] as source for the field to be searched
	 * 
	 * @param array $array 
	 * 		an array that contains an elements of the query's condition. It look's like :<br/>
	 * 		array('table'=>'table_name', 'field'=>'field_name','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR')<br/>
	 * 		where 'table" default value is $this->db_tbl<br/>
	 * 		where "type" may be one of ['<','>','<=', '>=','like', 'match', '=', '!=', '<>'] Default Value = "="<br/>
	 * 		where "op" may be one of ["OR", "AND"] default value is "AND"
	 */
	function query_cond($array) {
		$table=$this->db_tbl;
		if ($array['table']) {
			$table=$array['table'];
		}
		//Checking Condition Type
		$type = strtolower($array['type']);
		if($type !='=' && $type!='<' && $type!='>' && $type!='<=' && $type!='>=' && $type!='like' && $type!='match'
				&& $type!='!='  && $type!='<>') 	{
			$type = '=';
		}

		$var = $array['var'];
		if(empty($var)) {
			$var = "{KEY}";
		}
	
		$array['op'] = $array['op']?$array['op']:'AND';
	
	
		if(count($this->query_conds)>0)
			$op = $array['op'];
		else
			$op = '';
			
		if($array['value'] == 'static') {
			$this->query_conds[] = $op." ".tbl($table).".".$array['field']." ".$type." '".$array['var']."'";
			return true;
		}
	
	
		if(!empty($this->key) && $type != 'match')
			$this->query_conds[] = $op." ".tbl($table).".".$array['field']." ".$type." '".preg_replace("/{KEY}/",$this->key,$var)."'";
		if(!empty($this->key) && $type == 'match')
			$this->query_conds[] = $op." MATCH(".tbl($table).".".$array['field'].") AGAINST('".preg_replace("/{KEY}/",$this->key,$var)."'
										IN BOOLEAN MODE)";
	}
	
	/**
	 * Purge the given array from data that don(t have to be in.
	 * 
	 * To take in account querries with single quotes. Le SQL request has been modified by replacing single quotes in $this->key by "%" char
	 * This has a direct impact on the SQL result. SQL will match unnecessary results (strings where there's a quote but where the text after
	 * contains but doesn't start with the text after the single quote into the requested string. 
	 * This function uses a regexp to eliminate thoses bad answers.
	 * 
	 * @param array $results
	 * 		The array to purge (comming from a $db->select function)
	 * @return array
	 * 		The same array but without the unnecessary rows. 
	 */
	 function filterBadResults($results){
		$filteredResults=array();
		$regexpchars=array('%','.','*','^','$','(',')','[',']','{','}','<','>','+','|','\\','/','?');
		$key="/".strtolower(str_replace($regexpchars,".",$this->key))."/";
		$escapechars=array('’',"'","\'","\\&#8217;","\&#8217;","&#8217;","#39;");
			
		foreach ($results as $r){
			$found=false;
			foreach ($this->columns as $c){
				$str=strtolower($r[$c["field"]]);
				$str=str_replace($escapechars,".",$str);
				//$str=str_replace($regexpchars,".",$str);
				if (preg_match($key, $str)){
					$found=true;
				}
			}
			if ($found){
				$filteredResults[]=$r;
			}
		}
		return  $filteredResults;
	}
	
	/**
	 * Run the database search request. 
	 * Need $this->columns to be filed for adding conditions in WHERE part of the query<br/>
	 * Need $this->reqTbls to be filed for adding tables in FROM part of the query<br/>
	 * Need $this->reqTblsJoin to be filed for adding conditions in WHERE part of the query (table junction)<br/>
	 */
	 function search(){
		global $db;
		/* 	Problem when searching text with a single quote (single quotes may be encoded differntly in the database)
		 	The solution found is to replace single quotes from the requested text by a % char. 
			This Have a negative effect because it may return more data than necessary (% char can replace may chars in sql query).
			So after the request we do a second search path using a regexp on all fields of all sql results to eliminate bad results.
			All this job is run only in cas of a request containing a single quote
		*/
		$flagPass2=false;
		$escapechars=array('’',"'","\'","\\&#8217;","&amp;#8217;","#39;");
		$key=str_replace($escapechars,"%",$this->key);
		if ($key!= $this->key){
			$this->key=$key;
			$flagPass2=true;
		}

		$ma_query = "";
		#Checking for columns
		if(!$this->use_match_method)
			foreach($this->columns as $column) {
				$this->query_cond($column);
			}
		else {
			if($this->key) {
				$this->set_the_key();
				$ma_query = $this->match_against_query();
				$this->add_cond($ma_query);
				//add order
				$add_select_field = ",".$ma_query." AS Resource";
				//$sorting = "Resource ASC";
			}else {
				//do nothing
			}
				
			foreach($this->columns as $column) {
				if($column['value'] == 'static')
					$this->query_cond($column);
			}
		}
		
		
		
		#Checking for category
		if(isset($this->category)) {
			$this->cat_to_query($this->category,$this->multi_cat);
		}
		#Setting Date Margin
		if($this->date_margin!='') {
			$this->add_cond('('.$this->date_margin($this->date_added_colum).')');
		}
		
		#Sorting
		if(isset($this->sort_by) && !$sorting) {
			$sorting = $this->sorting[$this->sort_by];
		}
		
		$condition = "";
		$firstCond ='';
		$orConds= [];
		$andConds= [];
		$foundAnd=false;
		#Creating Condition (put all "OR" conditions together inside a parenthesis  and then and conditions.)
		foreach($this->query_conds as $cond) {
			//$condition .= $cond.' ';
			if (preg_match("/^OR\b/", $cond))
				$orConds [] = $cond;
			else if (preg_match("/^AND\b/", $cond))
				$andConds [] = $cond;
			else 
				$firstCond = $cond;
		}
		if (count ($orConds >0)) 
			$condition .= '(';
		$condition .= $firstCond." ";
		foreach($orConds as $cond) 
			$condition .= $cond.' ';
		if (count ($orConds >0)) 
			$condition .= ') ';
		foreach($andConds as $cond)
			$condition .= $cond.' ';
		
		$tables="";
		# Creating liste of from tables
		foreach ($this->reqTbls as $table) {
			$tables.=$table.',';
		}
		$tables=substr($tables,0,-1); //remove last ","
		
		$joinCondition="";
		# Creating condition for sql join queries
		foreach ($this->reqTblsJoin as $join){
			$joinCondition.=" ".tbl($join['table1']).".".$join['field1']."=".tbl($join['table2']).".".$join['field2']." AND";
		}
		$joinCondition=substr($joinCondition, 0,-3); //remove alrd "AND"

		//default values if $this->searchFields has not been redefined in the init-search function.
		if (!$this->searchFields)
			$this->searchFields=$this->db_tbl.'.*,users.userid,users.username';
		
		$query_cond = "(".$condition.")";
		if($condition)
			$query_cond .= " AND ";
		else
			$query_cond = $condition;
		$restrictionCond="";
		//only add restrictions for some tables
		if (in_array ($this->db_tbl,array("video","photos","collections"))){
			if(!has_access('admin_access',TRUE)){
				$restrictionCond = " AND ".tbl($this->db_tbl).".active='yes'"."AND".tbl($this->db_tbl).".broadcast='public'";
			}
			else{
				$restrictionCond = " AND ".tbl($this->db_tbl).".active='yes'";
			}
		}
		$results = $db->select(tbl($tables), "DISTINCT ".tbl($this->searchFields).$add_select_field,
				$query_cond." ".$joinCondition.$restrictionCond,$this->limit,$sorting);
		//The same request as above but without the limit restriction. Used to count results. Don't use count function because of the problem og single quote
		$resultsNoLimit = $db->select(tbl($tables), "DISTINCT ".tbl($this->searchFields).$add_select_field,
				$query_cond." ".$joinCondition.$restrictionCond,false,false);
		
		// Second search pass for single quote treatment (See comments above for more explainations)
		if ($flagPass2){
			$results=$this->filterBadResults($results);
			$resultsNoLimit=$this->filterBadResults($resultsNoLimit);
			
			
			
		}
		
		
		//$this->total_results = $db->count(tbl($this->db_tbl),'*',$condition);
		$this->total_results = count($resultsNoLimit);
		
		// Use array_reverse function because search_result.php also use this function and $result is in good order.
		return array_reverse($results);
		}
	
}

?>