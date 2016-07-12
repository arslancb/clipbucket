<?php
/**
 * This File contains a class that extends cbsearch in order to modifiy it's behaviour and accept extended search
 */
class extend_search extends cbsearch {
	
	/**
	 * Function used to convert array to query condition
	 * 
	 * Overwride of cbsearch query_cond function to accept search in other table tha, $this->db_tbl
	 * By default if $array doesn't contains "table" field then run the same code than the original function
	 * Otherwise take the $array['table'] as source for the field to be searched
	 * 
	 * input $array : an array tha contains an elements of the query's condition. It look's like :
	 * 	array('table'=>'table_name', 'field'=>'field_name','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR')
	 * 	where 'table" default value is $this->db_tbl
	 * 	where "type" may be one of ['<','>','<=', '>=','like', 'match', '=', '!=', '<>'] Default Value = "="
	 * 	where "op" may be one of ["OR", "AND"] default value is "AND"
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
	 * Run the database search request. 
	 * 	need $this->columns for adding conditions in WHERE part of the query
	 * 	need $this->reqTbls for adding tables in FROM part of the query
	 * 	need $this->reqTblsJoin for adding conditions in WHERE part of the query (table junction)
	 */
	 function search(){
		global $db;
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
		#Creating Condition
		foreach($this->query_conds as $cond) {
			$condition .= $cond." ";
		}
		
		$tables="";
		#Creating liste of from tables
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
		
		if($this->has_user_id) {
			$query_cond = "(".$condition.")";
			if($condition)
				$query_cond .= " AND ";
			else
				$query_cond = $condition;
			/*
			$results = $db->select(tbl($this->db_tbl.",users"),
					tbl($this->db_tbl.'.*,users.userid,users.username').$add_select_field,
					$query_cond." ".tbl($this->db_tbl).".userid=".tbl("users.userid")." AND ".tbl($this->db_tbl).".active='yes'",$this->limit,$sorting);
			*/
			$results = $db->select(tbl($tables),
					"DISTINCT ".tbl($this->db_tbl.'.*,users.userid,users.username').$add_select_field,
					$query_cond." ".$joinCondition." AND ".tbl($this->db_tbl).".active='yes'",$this->limit,$sorting);
				
		
		
			$this->total_results = $db->count(tbl($this->db_tbl),'*',$condition);
				
		}else {
			//TODO: Request non modified. If used it should be like the request above but wutthout users table and fields.  
			$results = $db->select(tbl($this->db_tbl),'*',$condition,$this->limit,$sorting);
			//echo $db->db_query;
			$this->total_results = $db->count(tbl($this->db_tbl),'*',$condition);
		}
		return $results;
	}
	
}

?>