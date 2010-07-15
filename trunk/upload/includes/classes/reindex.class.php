<?php
/**
 * Author : Arslan Hassan
 * Script : ClipBucket v2
 * License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 *
 * Class : Reindex
**/

class CBreindex 
{
	var $indexing = false; // Tells whether indexing is completed or not
	var $vtbl = 'video';
	var $utbl = 'users';
	var $gtbl = 'groups';

	/**
	 * Function is used to calculate
	 * the percentage of total figure
	 */	
	function percent($percent,$total)
	{
		$result = number_format($percent * $total / 100);
		return $result;
	}
	
	/**
	 * Function used to count
	 * indexes
	 */	
	function count_index($type,$params)
	{
		global $db;

		switch($type)
		{
			case "user":
			case "u":
			{
				if($params['video_count'])
				{
					$video_count = $db->count(tbl($this->vtbl),
											  tbl($this->vtbl).".videoid",
											  tbl($this->vtbl).".userid = ".$params['user']." AND 
											  ".tbl($this->vtbl).".active = 'yes' AND ".tbl($this->vtbl).".status = 'Successful'");
					//echo $db->db_query;										  
					return $video_count;						  
				}
										  
				if($params['comment_count'])
				{
					$ctbl = tbl("comments");
					$comment_count = $db->count($ctbl,
												$ctbl.".comment_id",
												$ctbl.".userid = ".$params['user']."");	
					return $comment_count;							
				}
				
			}
			break;
			
		}
	}
	
	/**
	 * Function used to update
	 * indexes
	 */
	 function update_index($type,$params=NULL) {
		 global $db;
		 
		 switch($type)
		{
			case "user":
			case "u":
			{
				$test_query = "UPDATE ".tbl($this->utbl)." SET ".$params['values']." WHERE ".tbl($this->utbl).".userid = ".$params['user']."";
				echo $test_query."<br/ ><br />";				
			}
			break;
			
		}
	 }
}

?>