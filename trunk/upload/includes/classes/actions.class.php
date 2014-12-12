<?php

/**
 * This class is used to perform
 * Add to favorits
 * Flag content
 * share content
 * rate content
 * playlist 
 * quicklist
 * 
 * @Author : ARSLAN HASSAN (te haur kaun o sukda)
 * @Script : ClipBucket v2
 * @Since : Bakra Eid 2009
 */
 


class cbactions
{
	/**
	 * Defines what is the type of content
	 * v = video
	 * p = pictures
	 * g = groups etc
	 */
	 
	var $type = 'v';
	
	/**
	 * Defines whats the name of the object
	 * weather its 'video' , its 'picture' or its a 'group'
	 */
	var $name = 'video';
	
	/**
	 * Defines the database table name
	 * that stores all information about these actions
	 */
	var $fav_tbl = 'favorites';
	var $flag_tbl = 'flags';
	var $playlist_tbl = 'playlists';
	var $playlist_items_tbl = 'playlist_items';
	
	var $type_tbl = 'videos';
	var $type_id_field = 'videoid';
	var $notifications = 'notifications';
	/**
	 * Class variable ie $somevar = SomeClass;
	 * $obj_class = 'somevar';
	 */
	var $obj_class = 'cbvideo';
	
	
	/**
	 * Defines function name that is used to check
	 * weather object exists or not
	 * ie video_exists
	 * it will be called as ${$this->obj_class}->{$this->check_func}($id);
	 */
	var $check_func = 'video_exists';
	
	/**
	 * This holds all options that are listed when user wants to report
	 * a content ie - copyrighted content - voilance - sex or something alike
	 * ARRAY = array('Copyrighted','Nudity','bla','another bla');
	 */
	var $report_opts = array();
	
	
	/**
	 * share email template name
	 */
	var $share_template_name = 'video_share_template';



	/**
	 * Var Array for replacing text of email templates
	 * see docs.clip-bucket.com for more details
	 */
	var $val_array = array();

	
	/**
	 * initializing
	 */
	function init()
	{
        global $cb_columns;

		$this->report_opts = array
		(
		lang('inapp_content'),
		lang('copyright_infring'),
		lang('sexual_content'),
		lang('violence_replusive_content'),
		lang('spam'),
		lang('disturbing'),
		lang('other')		
		);

        $fields = array( 'playlist_id', 'playlist_name', 'description', 'tags', 'category',
                         'played', 'privacy', 'total_comments', 'total_items', 'runtime',
                         'last_update', 'date_added', 'first_item', 'playlist_type', 'cover' );

        $cb_columns->object( 'playlists' )->register_columns( $fields );

        $fields = array(
            'playlist_item_id', 'object_id', 'playlist_id', 'playlist_item_type', 'userid',
            'date_added'
        );

        $cb_columns->object( 'playlist_items' )->register_columns( $fields );
	}

	/**
	 * Function used to add content to favorits
	 */
	 
	function add_to_fav($id)
	{
		 global $db;
		 $id = mysql_clean($id);
		 //First checking weather object exists or not
		 if($this->exists($id))
		 {
			if(userid())
			{
				if(!$this->fav_check($id))
				{
					
					$db->insert(tbl($this->fav_tbl),array('type','id','userid','date_added'),array($this->type,$id,userid(),NOW()));
					addFeed(array('action'=>'add_favorite','object_id' => $id,'object'=>'video'));
					
					//Loggin Favorite			
					$log_array = array
					(
					 'success'=>'yes',
					 'details'=> "added ".$this->name." to favorites",
					 'action_obj_id' => $id,
					 'action_done_id' => $db->insert_id(),
					);
					insert_log($this->name.'_favorite',$log_array);
					
					//e(sprintf(lang('add_fav_message'),$this->name),'m');
					 e('<div class="alert alert-success">This video has been added to your favorites</div>', "m" );
				}else{
					 
					e(sprintf(lang('already_fav_message'),$this->name));
				}
			}else{
				e(lang("you_not_logged_in"));
			}
		 }else{
			 e(sprintf(lang("obj_not_exists"),$this->name));
		 }
	 }
	 
	 	 
	function add_to_favorites($id){ return $this->add_to_fav($id); }
	function add_favorites($id){ return $this->add_to_fav($id); }
	function add_fav($id){ return $this->add_to_fav($id); }
	 
	/**
	 * Function used to check weather object already added to favorites or not
	 */
	function fav_check($id,$uid=NULL)
	{
		global $db;
		
		$id = mysql_clean($id);
		
		if(!$uid)
			$uid =userid();
		$results = $db->select(tbl($this->fav_tbl),"favorite_id"," id='".$id."' AND userid='".$uid."' AND type='".$this->type."'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	
	}
	 
	/**
	 * Function used to check weather object exists or not
	 */
	function exists($id)
	{
		$id = mysql_clean($id);
		$obj = $this->obj_class;
		global ${$obj};
		$obj = ${$obj};
		$func = $this->check_func;
		return $obj->{$func}($id);
	}
	
	
	/**
	 * Function used to report a content
	 */
	 
	function report_it($id)
	{
		global $db;
		$id = mysql_clean($id);
		//First checking weather object exists or not
		if($this->exists($id))
		{
			if(userid())
			{
				if(!$this->report_check($id))
				{
					$db->insert(tbl($this->flag_tbl),array('type','id','userid','flag_type','date_added'),
												array($this->type,$id,userid(),mysql_clean(post('flag_type')),NOW()));
					e(sprintf(lang('obj_report_msg'),$this->name),'m');
				}else{
					e(sprintf(lang('obj_report_err'),$this->name));
				}
			}else{
				e(lang("you_not_logged_in"));
			}
		}else{
		 e(sprintf(lang("obj_not_exists"),$this->name));
		}
	}
	function flag_it($id){ return $this->report_id($id); }
	
	
	/**
	 * Function used to delete flags
	 */
	function delete_flags($id)
	{
		global $db;
		$id = mysql_clean($id);
		$db->delete(tbl($this->flag_tbl),array("id","type"),array($id,$this->type));
		e(sprintf(lang("type_flags_removed"),$this->name),"m");
	}
	
	
	/**
	 * Function used to check weather user has already reported the object or not
	 */
	function report_check($id)
	{
		global $db;
		$id = mysql_clean($id);
		$results = $db->select(tbl($this->flag_tbl),"flag_id"," id='".$id."' AND type='".$this->type."' AND userid='".userid()."'");
		if($db->num_rows>0)
			return true;
		else
			return false;
	}
	
	
	
	/**
	 * Function used to content
	 */
	function share_content($id)
	{
		global $db,$userquery;
		$ok = true;
		$tpl = $this->share_template_name;
		$var = $this->val_array;
		$id = mysql_clean($id);
		//First checking weather object exists or not
		if($this->exists($id))
		{
			global $eh;
			if(userid())
			{
				
				$post_users = mysql_clean(post('users'));
				$users = explode(',',$post_users);
				if(is_array($users) && !empty($post_users))
				{
					foreach($users as $user)
					{
						if(!$userquery->user_exists($user) && !isValidEmail($user))
						{
							e(sprintf(lang('user_no_exist_wid_username'),$user));
							$ok = false;
							break;
						}
						
						$email = $user;
						if(!isValidEmail($user))
							$email = $userquery->get_user_field_only($user,'email');
						$emails_array[] = $email;
					}
					
					if($ok)
					{
						global $cbemail;
						$tpl = $cbemail->get_template($tpl);
						$more_var = array
						('{user_message}'	=> post('message'),);
						$var = array_merge($more_var,$var);
						$subj = $cbemail->replace($tpl['email_template_subject'],$var);
						$msg = $cbemail->replace($tpl['email_template'],$var);
						
						//Setting Emails
						$emails = implode(',',$emails_array);
						
						//Now Finally Sending Email
						$from = $userquery->get_user_field_only(username(),"email");
						
						cbmail(array('to'=>$emails_array,'from'=>$from,'from_name'=>username(),'subject'=>$subj,'content'=>$msg,'use_boundary'=>true));
						e(sprintf(lang("thnx_sharing_msg"),$this->name),'m');
						
					}
				}else{
					e(sprintf(lang("share_video_no_user_err"),$this->name));
				}
					
			}else{
				e(lang("you_not_logged_in"));
			}
		}else{
		 e(sprintf(lang("obj_not_exists"),$this->name));
		}
	}
	
	
	/**
	 * Get Used Favorites
	 */
	function get_favorites($params)
	{
		global $db;
		
		$uid	= $params['userid'];
		$limit	= $params['limit'];
		$cond	= $params['cond'];
		$order	= $params['order'];
		
		if(!$uid)
			$uid=userid();
		if($cond)
			$cond = " AND ".$cond;
		
		if(!$params['count_only'])
		{
			$results = $db->select(tbl($this->fav_tbl.",".$this->type_tbl),"*"," ".tbl($this->fav_tbl).".type='".$this->type."' 
							   AND ".tbl($this->fav_tbl).".userid='".$uid."' 
							   AND ".tbl($this->type_tbl).".".$this->type_id_field." = ".tbl($this->fav_tbl).".id".$cond,$limit,$order);
		}
		if($params['count_only'])
		{
			return $results = $db->count(tbl($this->fav_tbl.",".$this->type_tbl),"*"," ".tbl($this->fav_tbl).".type='".$this->type."' 
							   AND ".tbl($this->fav_tbl).".userid='".$uid."' 
							   AND ".tbl($this->type_tbl).".".$this->type_id_field." = ".tbl($this->fav_tbl).".id".$cond);
		}
		
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	/**
	 * Function used to count total favorites only
	 */
	function total_favorites()
	{
		global $db;
		return $db->count(tbl($this->fav_tbl),"favorite_id"," type='".$this->type."'");
	}
	
	
	/**
	 * Function used remove video from favorites
	 */
	function remove_favorite($fav_id,$uid=NULL)
	{
		global $db;
		if(!$uid)
			$uid=userid();
		if($this->fav_check($fav_id,$uid))
		{
			$fav_id = mysql_clean($fav_id);
			$uid = mysql_clean($uid);
			$db->delete(tbl($this->fav_tbl),array('userid','type','id'),array($uid,$this->type,$fav_id));
			e(sprintf(lang('fav_remove_msg'),$this->name),'m');
		}else
			e(sprintf(lang('unknown_favorite'),$this->name));
	}
	
	
	/**
	 * Function used to get object flags
	 */
	function get_flagged_objects($limit=NULL)
	{
		global $db;
		$type = $this->type;
   
    $results = $db->select(tbl($this->flag_tbl.",".$this->type_tbl),"*,
							   count(*) AS total_flags",tbl($this->flag_tbl).".id = ".tbl($this->type_tbl).".".$this->type_id_field." 
							   AND ".tbl($this->flag_tbl).".type='".$this->type."' GROUP BY ".tbl($this->flag_tbl).".id ,".tbl($this->flag_tbl).".type ",$limit);				   
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	/**
	 * Function used to get all flags of an object
	 */
	function get_flags($id)
	{
		global $db;
		$type = $this->type;
		$results = $db->select(tbl($this->flag_tbl),"*","id = '$id' AND type='".$this->type."'");
		if($db->num_rows>0)
			return $results;
		else
			return false;
	}
	
	
	/**
	 * Function used to count object flags
	 */
	function count_flagged_objects()
	{
		global $db;
		$type = $this->type;
		$results = $db->select(tbl($this->flag_tbl.",".$this->type_tbl),"id",tbl($this->flag_tbl).".id = ".tbl($this->type_tbl).".".$this->type_id_field." 
							   AND type='".$this->type."' GROUP BY ".tbl($this->flag_tbl).".id ,".tbl($this->flag_tbl).".type ");
		if($db->num_rows>0)
			return count($results);
		else
			return 0;
	}

    function load_basic_fields( $array = null ) {

        if ( is_null( $array ) ) {
            $array = $_POST;
        }

        $title = $array[ 'playlist_name' ];
        $description = $array[ 'description' ];
        $tags = $array[ 'tags' ];
        $privacy = $array[ 'privacy' ];

        $fields = array(
            'title' => array(
                'title' => lang( 'Playlist Name' ),
                'type' => 'textfield',
                'name' => 'playlist_name',
                'id' => 'playlist_name',
                'db_field' => 'playlist_name',
                'value' => $title,
                'required' => 'yes',
                'invalid_err' => lang( 'Please decide a name for your playlist' )
            ),
            'description' => array(
                'title' => lang ( 'Description' ),
                'type' => 'textarea',
                'name' => 'description',
                'id' => 'description',
                'db_field' => 'description',
                'value' => $description
            ),
            'tags' => array(
                'title' => lang( 'Tags' ),
                'type' => 'textfield',
                'name' => 'tags',
                'id' => 'tags',
                'db_field' => 'tags',
                'value' => $tags
            ),
            'privacy' => array(
                'title' => 'Privacy',
                'type' => 'dropdown',
                'name' => 'privacy',
                'id' => 'privacy',
                'db_field' => 'privacy',
                'value' => array(
                    'public' => lang( 'Public' ),
                    'private' => lang( 'Private' ),
                    #'unlisted' => lang( 'Unlisted' )
                ),
                'default_value' => 'public',
                'checked' => $privacy
            )
        );

        return $fields;
    }

    function load_other_options ( $array = null ) {

        if ( is_null( $array ) ) {
            $array = $_POST;
        }


        $allow_comments = $array[ 'allow_comments' ];
        $allow_rating = $array[ 'allow_rating' ];

        $fields = array(
            'allow_comments' => array(
                'title' => lang( 'Allow Comments' ),
                'id' => 'allow_comments',
                'type' => 'radiobutton',
                'name' => 'allow_comments',
                'db_field' => 'allow_comments',
                'value' => array(
                    'no' => lang( 'No' ),
                    'yes' => lang( 'Yes' )
                ),
                'default_value' => 'yes',
                'checked' => $allow_comments
            ),
            'allow_rating' => array(
                'title' => lang( 'Allow Rating' ),
                'id' => 'allow_rating',
                'type' => 'radiobutton',
                'name' => 'allow_rating',
                'db_field' => 'allow_rating',
                'value' => array(
                    'no' => lang( 'No' ),
                    'yes' => lang( 'Yes' )
                ),
                'default_value' => 'yes',
                'checked' => $allow_rating
            )
        );

        return $fields;
    }


    function load_playlist_fields( $array = null ) {

        if ( is_null( $array ) ) {
            $array = $_POST;
        }

        $basic = $this->load_basic_fields( $array );
        $other = $this->load_other_options( $array );

        $fields = array_merge( $basic, $other );

        $group = array(
            'basic' => array(
                'group_id' => 'basic_fields',
                'group_name' => 'Basic Details',
                'fields' => $basic
            ),
            'other' => array(
                'group_id' => 'other_fields',
                'group_name' => 'Other Options',
                'fields' => $other
            )
        );

        return $group;
    }
	
	/**
	 * Function used to create new playlist
	 * @param ARRAY
	 */
	/*function create_playlist( $array = null )
	{
		global $db;

        if ( is_null( $array ) ) {
            $array = $_POST;
        }

		$name = mysql_clean( $array['name'] );
		if(!userid())
			e(lang("please_login_create_playlist"));
		/*elseif(empty($name))
			e(lang("please_enter_playlist_name"));
		elseif($this->playlist_exists($name,userid(),$this->type))
			e(sprintf(lang("play_list_with_this_name_arlready_exists"),$name));
		else
		{

            $upload_fields = $this->load_playlist_fields( $array );
            $fields = array();

            foreach( $upload_fields as $group ) {

                $fields = array_merge( $fields, $group[ 'fields' ] );

            }

            validate_cb_form( $fields, $array );
            if ( !error() ) {

                foreach($fields as $field)
                {
                    $name = formObj::rmBrackets($field['name']);
                    $val = $array[ $name ];

                    if($field['use_func_val'])
                        $val = $field['validate_function']($val);

                    if(is_array($val))
                    {
                        $new_val = '';
                        foreach($val as $v)
                        {
                            $new_val .= "#".$v."# ";
                        }
                        $val = $new_val;
                    }
                    if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
                        $val = ($val);
                    else
                        $val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));

                    if(!empty($field['db_field']))
                        $query_values[ $name ] = $val;
                }


                $query_values[ 'date_added' ] = NOW();
                $query_values[ 'userid' ] = $array[ 'userid' ] ? $array[ 'userid' ] : userid();
                $query_values[ 'playlist_type' ] = $this->type;

                $db->insert( tbl( $this->playlist_tbl ), array_keys( $query_values ), array_values( $query_values ) );
                e(lang("new_playlist_created"),"m");

                return true;
            }


			/*$pid = $db->insert_id();
			
			//Logging Playlist			
			$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "created playlist",
			 'action_obj_id' => $pid,
			);
			
			insert_log('add_playlist',$log_array);
					
			return $pid;
			
		}
		
		return false;
	}
	*/



	function create_playlist($params)
	{
		global $db;
		$name = mysql_clean($params['name']);
		if(!userid())
			e(lang("please_login_create_playlist"));
		elseif(empty($name))
			e(lang("please_enter_playlist_name"));
		elseif($this->playlist_exists($name,userid(),$this->type))
			e(sprintf(lang("play_list_with_this_name_arlready_exists"),$name));
		else
		{
			$db->insert(tbl($this->playlist_tbl),array("playlist_name","userid","date_added","playlist_type"),
									  array($name,userid(),now(),$this->type));
		
			//return true;
			$pid = $db->insert_id();
			e(lang("new_playlist_created".$pid),"m");
			//Logging Playlist			
			/*$log_array = array
			(
			 'success'=>'yes',
			 'details'=> "created playlist",
			 'action_obj_id' => $pid,
			);
			
			insert_log('add_playlist',$log_array);*/
					
			
			return $pid;
		}
		
		return false;
	}
	
	/**
	 * Function used to check weather playlist already exists or not
	 */
	function playlist_exists($name,$user,$type=NULL)
	{
		global $db;
		if($type)
			$type = $this->type;
		$count = $db->count(tbl($this->playlist_tbl),"playlist_id"," userid='$user' AND playlist_name='$name' AND playlist_type='".$type."' ");

		if($count)
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to get playlist
	 */
	function get_playlist( $id, $user = null )
	{
		global $db, $cb_columns;


        $fields = array(
            'playlists' => $cb_columns->object( 'playlists' )->temp_add( 'rated_by,voters,rating,allow_rating,allow_comments' )->get_columns()
        );


        $fields[ 'users' ] = $cb_columns->object( 'users' )->temp_remove('usr_status,user_session_key')->get_columns();



        $query = "SELECT ".table_fields( $fields )." FROM ".table( 'playlists' );
        $query .= " LEFT JOIN ".table( 'users' )." ON playlists.userid = users.userid";

        $query .= " WHERE playlists.playlist_id = '$id'";

        if ( !is_null( $user ) and is_numeric( $user ) ) {
            $query .= " AND playlists.userid = '$user' ";
        }

        $query .= " LIMIT 1";

        $query_id = cb_query_id( $query );

        $data = cb_do_action( 'select_playlist', array( 'query_id' => $query_id, 'object_id' => $id ) );

        if ( $data ) {
            return $data;
        }

        $data = select( $query );

        if ( isset( $data[ 0 ] ) and !empty( $data[ 0 ] ) ) {
            $data = $data[ 0 ];

            if ( !empty( $data[ 'first_item' ] ) ) {
                $first_item = json_decode( $data[ 'first_item' ], true );
                if ( $first_item ) {
                    $data[ 'first_item' ] = $first_item;
                }
            }

            if ( !empty( $data[ 'cover' ] ) ) {
                $cover = json_decode( $data[ 'cover' ], true );
                if ( $cover ) {
                    $data[ 'cover' ] = $cover;
                }
            }

            cb_do_action( 'return_playlist', array(
                'query_id' => $query_id,
                'results' => $data,
                'object_id' => $id
            ) );

            return $data;
        }


        return false;

 
        
	}
	
	


	/**
	 * Function used to get playlist
	 */
/*function get_playlist($id,$user=NULL)
	{
		global $db;
		
		$user_cond;
		if($user)
			$user_cond = " AND userid='$user'";
			
		$result = $db->select(tbl($this->playlist_tbl),"*"," playlist_id='$id' $user_cond");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}*/
	


	/**
	 * Function used to add new item in playlist
	 */
	function add_playlist_item($pid,$id)
	{
		global $db, $cb_columns;

        $playlist = $this->get_playlist($pid);
        
		if(!$this->exists($id))
			e(sprintf(lang("obj_not_exists"),$this->name));
		elseif( !$playlist )
			e(lang("playlist_not_exist"));
		elseif(!userid())
			e(lang('you_not_logged_in'));
		elseif($this->playlist_item_with_obj($id,$pid))
			e(sprintf(lang('this_already_exist_in_pl'),$this->name));
		else
		{

            $video = get_video_basic_details( $id, true );

            cb_do_action( 'add_playlist_item', array( 'playlist' => $playlist, 'object' => $video, 'object_type' => $this->type ) );

            if ( !error() ) {

                $fields = array(
                    'object_id' => $id,
                    'playlist_id' => $pid,
                    'date_added' => now(),
                    'playlist_item_type' => $this->type,
                    'userid' => userid()
                );

                /* insert item */
                $db->insert( tbl( $this->playlist_items_tbl ), array_keys( $fields ), array_values( $fields ) );

                /* update playlist */
                $fields = array(
                    'last_update' => now(),
                    'runtime' => '|f|runtime+'.$video[ 'duration' ],
                    'first_item' => '|no_mc|'.json_encode( $video ),
                    'total_items' => '|f|total_items+1'
                );

                $db->update( tbl( 'playlists' ), array_keys( $fields ), array_values( $fields ), " playlist_id = '".$pid."' " );

                //e( sprintf( lang( 'this_thing_added_playlist' ), $this->name ), "m" );
                e('<div class="alert alert-success">This video has been added to playlist</div>', "m" );
                return $video;
            }

            /*
			$db->insert(tbl($this->playlist_items_tbl),array("object_id","playlist_id","date_added","playlist_item_type","userid"),
											array($id,$pid,now(),$this->type,userid()));
			e(sprintf(lang('this_thing_added_playlist'),$this->name),"m");
			return $db->insert_id();
            */
		}
	}
	
	
	/**
	 * Function use to delete playlist item
	 */
	function delete_playlist_item($id)
	{
		global $db;

		$item = $this->playlist_item( $id, true );

		if(!$item)
			e(lang("playlist_item_not_exist"));
		elseif($item['userid']!=userid() && !has_access('admin_access'))
			e(lang("you_dont_hv_permission_del_playlist"));
		else
		{
            $video = get_video_basic_details( $item[ 'object_id' ] );

            if ( !$video ) {
                e( lang( "playlist_item_not_exist" ) );
                return false;
            }

            cb_do_action( 'delete_playlist_item', array( 'playlist' => $item, 'object' => $video ) );

            /* Remove item */
			$db->delete( tbl( $this->playlist_items_tbl ),array( "playlist_item_id" ),array( $id ) );


            /* Update playlist */
            $fields = array(
                'last_update' => NOW(),
                'runtime' => $item[ 'runtime' ] - $video[ 'duration' ],
                'total_items' => $item[ 'total_items' ] - 1
            );

            if ( $fields[ 'runtime' ] <= 0 ) {
                $fields[ 'runtime' ] = 0;
            }

            if ( $fields[ 'total_items' ] <= 0 ) {
                $fields[ 'total_items' ] = 0;
            }

            if ( $this->is_item_first( $item, $item[ 'object_id' ] ) ) {
                $fields[ 'first_item' ] = '|no_mc|'.json_encode( array() );
            }


            $db->update( tbl( 'playlists' ), array_keys( $fields ), array_values( $fields ), " playlist_id = '".$item[ 'playlist_id' ]."' " );

			e( lang( "playlist_item_delete" ), "m" );

            return true;
		}
	}

    function is_item_first ( $details, $check_id ) {

        if ( !isset( $details[ 'first_item' ] ) ) {
            return false;
        }

        $decode = json_decode( $details[ 'first_item' ], true );

        if ( !isset( $decode[ 'videoid' ] ) ) {
            return false;
        }

        if ( $decode[ 'videoid' ] == $check_id ) {
            return true;
        }

        return false;
    }

	/**
	 * Function used to check weather playlist item exists or not
	 */
	function playlist_item( $id, $join_playlist = false )
	{
		global $db, $cb_columns;

        $fields = array(
            'playlist_items' => $cb_columns->object( 'playlist_items' )->get_columns()
        );

        if ( $join_playlist == true ) {
            $fields[ 'playlists' ] = $cb_columns->object( 'playlists' )->temp_change( 'date_added', 'playlist_added' )->get_columns();
        }

        $query = "SELECT ".table_fields( $fields )." FROM ".table( 'playlist_items' );

        if ( $join_playlist == true ) {
            $query .= " LEFT JOIN ".table( 'playlists' )." ON playlist_items.playlist_id = playlists.playlist_id";
        }

        $query .= " WHERE playlist_items.playlist_item_id = '$id' LIMIT 1";

        $query_id = cb_query_id( $query );

        $data = cb_do_action( 'select_playlist_item', array( 'playlist_item_id' => $id, 'query_id' => $query_id ) );

        if ( $data ) {
            return $data;
        }

        $data = select( $query );

        if ( $data ) {

            cb_do_action( 'return_playlist_item', array(
                'query_id' => $query_id,
                'results' => $data[ 0 ]
            ));

            return $data[ 0 ];
        } else {
            return false;
        }

        /*
		$result = $db->select(tbl($this->playlist_items_tbl),"*"," playlist_item_id='$id' ");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
        */
	}
	
	/**
	 * Function used to check weather playlist item exists or not
	 */
	function playlist_item_with_obj($id,$pid=NULL)
	{
		global $db;
		$pid_cond = "";
		if($pid)
			$pid_cond = " AND playlist_id='$pid'";
		$result = $db->select(tbl($this->playlist_items_tbl),"*"," object_id='$id' $pid_cond");
		if($db->num_rows>0)
			return $result[0];
		else
			return false;
	}
	
	/**
	 * Function used to update playlist details
	 */
	function edit_playlist( $array = null )
	{
		global $db;

        if( is_null( $array ) ) {
            $array = $_POST;

        }

		$name = mysql_clean($array['name']);
		$pdetails = $this->get_playlist( $array['pid'] ? $array['pid'] : $array['list_id'] );
		
		if(!$pdetails)
			e(lang("playlist_not_exist"));
		elseif(!userid())
			e(lang("you_not_logged_in"));
        elseif($this->playlist_exists($name,userid(),$this->type))
            e(sprintf(lang("play_list_with_this_name_arlready_exists"),$name));
		else
		{

            $upload_fields = $this->load_playlist_fields( $array );
            $fields = array();

            foreach( $upload_fields as $group ) {

                $fields = array_merge( $fields, $group[ 'fields' ] );

            }

            validate_cb_form( $fields, $array );

            if( !error() ) {

                foreach($fields as $field)
                {
                    $name = formObj::rmBrackets($field['name']);
                    $val = $array[ $name ];

                    if($field['use_func_val'])
                        $val = $field['validate_function']($val);

                    if(is_array($val))
                    {
                        $new_val = '';
                        foreach($val as $v)
                        {
                            $new_val .= "#".$v."# ";
                        }
                        $val = $new_val;
                    }
                    if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
                        $val = ($val);
                    else
                        $val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));

                    if(!empty($field['db_field']))
                        $query_values[ $name ] = $val;
                }

                if ( has_access( 'admin_access' ) ) {

                    if ( isset( $array[ 'played' ] ) and !empty( $array[ 'played' ] ) ) {
                        $query_values[ 'played' ] = $array[ 'played' ];
                    }

                }

                $query_values[ 'last_update' ] = NOW();

                $db->update( tbl( 'playlists' ), array_keys( $query_values ), array_values( $query_values ), " playlist_id = '".$pdetails[ 'playlist_id' ]."' " );

                $array[ 'playlist_id' ] = $array['pid'] ? $array['pid'] : $array['list_id'];

                cb_do_action( 'update_playlist', array(
                    'object_id' => $array['pid'] ? $array['pid'] : $array['list_id'],
                    'results' => $array
                ));
            }

			/*$db->update(tbl($this->playlist_tbl),array("playlist_name"),
									  array($name)," playlist_id='".$params['pid']."'");*/
			e(lang("play_list_updated"),"m");

		}
	}
	
	/**
	 * Function used to delete playlist
	 */
	function delete_playlist($id)
	{
		global $db;
		$playlist = $this->get_playlist($id);
		if(!$playlist)
			e(lang("playlist_not_exist"));
		elseif($playlist['userid']!=userid() && !has_access('admin_access',TRUE))
			e(lang("you_dont_hv_permission_del_playlist"));
		else
		{
			$db->delete(tbl($this->playlist_tbl),
						array("playlist_id"),array($id));
			$db->delete(tbl($this->playlist_items_tbl),
						array("playlist_id"),array($id));
			e(lang("playlist_delete_msg"),"m");
		}
	}
	
	/**
	 * Function used to get playlists
	 */

	
	function get_playlists( $params = array() )
	{
        global $cb_columns, $db;

        $fields = array(
            'playlists' => $cb_columns->object( 'playlists' )->get_columns()
        );

        $order = $params[ 'order' ];
        $limit = $params[ 'limit' ];

        $main_query = $query = "SELECT ".table_fields( $fields )." FROM ".table( 'playlists' );
        $condition = "playlists.playlist_type = 'v'";

        if ( !has_access( 'admin_access' ) ) {
            $condition .= ( $condition ) ? " AND " : "";
            $condition .= "playlists.privacy = 'public'";
        } else {
            if ( $params[ 'privacy' ] ) {
                $condition .= ( $condition ) ? " AND " : "";
                $condition .= " playlists.privacy = '".mysql_clean( $params[ 'privacy' ] )."' ";
            }
        }

        if ( $params[ 'category' ] ) {
            $condition .= ( $condition ) ? " AND " : "";
            $condition .= " playlists.category = '".$params[ 'category' ]."' ";
        }

        if ( $params[ 'include' ] ) {
            $ids = is_array( $params[ 'include' ] ) ? $params[ 'include' ] : explode( ',', $params[ 'include' ] );

            if ( is_array( $ids ) and !empty( $ids ) ) {
                $condition .= ( $condition ) ? " AND " : "";
                $ids = implode( ",", array_map( 'trim', $ids ) );
                $condition .= " playlists.playlist_id IN ($ids) ";
            }
        }

        if ( $params[ 'exclude' ] ) {
            $ids = is_array( $params[ 'exclude' ] ) ? $params[ 'exclude' ] : explode( ',', $params[ 'exclude' ] );

            if ( is_array( $ids) and !empty( $ids ) ) {
                $condition .= ( $condition ) ? " AND " : "";
                $ids = implode( ",", array_map( 'trim', $ids ) );
                $condition .= " playlists.playlist_id NOT IN ($ids) ";
            }
        }

        if ( $params[ 'date_span' ] ) {
            $condition .= ( $condition ) ? " AND " : "";
            $column = ( $params[ 'date_span_column' ] ) ? trim( $params[ 'date_span_column' ] ) : 'playlists.date_added';

            $condition .= cbsearch::date_margin( $column, $params['date_span'] );
        }

        if ( $params[ 'last_update' ] ) {
            $condition .= ( $condition ) ? " AND " : "";
            $condition .= cbsearch::date_margin( 'playlists.last_update', $params['last_update'] );
        }

        if( $params[ 'user' ] ) {
            $condition .= ( $condition ) ? " AND " : "";
            $condition .= " playlists.userid = '".$params[ 'user' ]."' ";
        }

        if ( $params[ 'has_items' ] ) {
            $condition .= ( $condition ) ? " AND " : "";
            $condition .= " playlists.total_items > '0' ";
        }

        if($params['count_only']){
              $result = $db->count( cb_sql_table('playlists') , 'playlist_id'  );
            	return $result;
		}

        if ( $condition ) {
            $query .= " WHERE ".$condition;
        }

        $order = " ORDER BY ".( $order ? trim( $order ) : "playlists.date_added DESC");
        $limit = ( $limit ) ? " LIMIT $limit ": "";


        $query .= $order.$limit;

        $query_id = cb_query_id( $query );

        $action_array = array( 'query_id' => $query_id );

        $data = cb_do_action( 'select_playlists', array_merge( $action_array, $params ) );

        if ( $data ) {
            return $data;
        }

        $results = select( $query );

        if ( !empty( $results ) ) {

            cb_do_action( 'return_playlists', array(
                'query_id' => $query_id,
                'results' => $results
            ));

            return $results;
        }

        return false;
	}

	

     /**
     * this method has been deprecated 
      */
	
     function get_playlists_no_more_cb26()
	{

		global $db;
		$result = $db->select(tbl($this->playlist_tbl),"*"," playlist_type='".$this->type."' AND userid='".userid()."'");
		
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}

	/**
     * Get playlist thumb
     * 
     * return a group of playlist thumbs
     * 
     * @param PID playlistid
     * @return THUMBS Array 
     */
    function getPlaylistThumb($pid)
    {
        $pid = (int) $pid;
        $array = array();

        $items = $this->get_playlist_items($pid, NULL, 3);

        global $cbvid, $cbaudio;
        $array = array();

        if ($items)
            foreach ($items as $item)
            {
                $item['type'] == 'v';
                $array[] = GetThumb($item['object_id']);
            }
        else
            return array(TEMPLATEURL . '/images/playlist-default.png');

        $array = array_unique($array);
        rsort($array);

        return $array;
    }
	
	/**
	 * Function used to get playlist items
	 */
	function get_playlist_items( $playlist_id, $order = null, $limit = -1 )
	{
		global $db, $cb_columns;

        $fields = array(
            'playlist_items' => $cb_columns->object( 'playlist_items' )->get_columns(),
            'playlists' => $cb_columns->object( 'playlists' )->temp_remove( 'first_item' )->get_columns(),
            'video' => $cb_columns->object( 'videos' )->get_columns()
        );

		$result = $db->select(tbl($this->playlist_items_tbl),"*","playlist_id='$pid'");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	}
	
	/**
	 * Function used to count playlist item
	 */	
	function count_playlist_items($id)
	{
		global $db;
		return $db->count(tbl($this->playlist_items_tbl),"playlist_item_id","playlist_id='$id'");
	}
	
	
	/**
	 * Function used to count total playlist or items
	 */
	function count_total_playlist($item=false)
	{
		global $db;
		if(!$item)
		{
			$result = $db->count(tbl($this->playlist_tbl),"*"," playlist_type='".$this->type."' ");
			return $result;
		}else{
			return $db->count(tbl($this->playlist_items_tbl),"playlist_item_id"," playlist_item_type='".$this->type."'");
		}
	}

	
}

?>