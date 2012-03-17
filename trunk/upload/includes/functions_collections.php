<?php


        /**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_collection_category($array=NULL)
	{
		global $cbcollection;
		return $cbcollection->validate_collection_category($array);
	}
        
        
        /**
	 * function used to get photos
	 */
	function get_collections($param)
	{
		global $cbcollection;
		return $cbcollection->get_collections($param);
	}
        
        
        /**
	 * Function used to get collection name from id
	 * Smarty Function
	 */
	function get_collection_field($cid,$field='collection_name')
	{
		global $cbcollection;
		return $cbcollection->get_collection_field($cid,$field);
	}
	
	/**
	 * Function used to delete photos if
	 * whole collection is being deleted
	 */
	function delete_collection_photos($details)
	{
		global $cbcollection,$cbphoto;
		$type = $details['type'];

		if($type == 'photos')
		{
			$ps = $cbphoto->get_photos(array("collection"=>$details['collection_id']));
			if(!empty($ps))
			{	
				foreach($ps as $p)
				{
					$cbphoto->make_photo_orphan($details,$p['photo_id']);	
				}
				unset($ps); // Empty $ps. Avoiding the duplication prob
			}
		}
	}
        

?>