<?php
/**
 * 
 * @link      http://
 * @copyright Copyright (c) 2007 - 2018 by Fahad Abbas
 * @link   https://bitbucket.org/clip-bucket/aditube/src/master/includes/classes/elasticSearch
*/
class ElasticSearch 
{
		
	/**
     * This variable is to define 
     *
     * @var Variable
    */
    protected $apiUrl = '';

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $sort = "relevance";

     /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $from = 0;

     /**
     * This variable is to define to fetch results count
     *
     * @var Variable
    */
    public $size = 10;

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    protected $EsQuery = array();

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $publicQuery = "";

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $index = "";

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $results = "";

    /**
     * This variable is to define 
     *
     * @var Variable
    */
    public $resultsHits = array();

    /**
     * This variable is to define to fetch results count
     *
     * @var Variable
    */
    public $filters = array(
                    "category"=>"",
                    "author"=>"",
                    "object_id"=>"",
                );

    /**
     * This variable is to define mapping of videos Object
     *
     * @object Variable
    */
    public $videoMappingData = array(
			"mappings"=> array(
			    "_doc"=> array( 
				    "properties"=> array( 
				    	"videoid"          =>  array( "type"=> "long" ), 
				        "title"            =>  array( "type"=> "text" ), 
                        "userid"           =>  array( 
                                                    "type"=> "long", 
                                                ), 
                        "username"         =>  array( "type"=> "text" ),
                        "first_name"       =>  array( "type"=> "text" ),
                        "last_name"        =>  array( "type"=> "text" ),
				        "description"      =>  array( "type"=> "text" ), 
				        "category"         =>  array( 
                                                "type" => "object",
                                                "properties" => array(
                                                    "id" => array(
                                                        "type"=>"integer",
                                                    ),
                                                    "name"=> array(
                                                        "type"=>"text",
                                                        "fielddata"=>true,
                                                    )
                                                )  
                                            ),
				        "tags"             =>  array( "type"=> "text" ),
				        "videokey"         =>  array( "type"=> "text" ),
				        "file_name"        =>  array( "type"=> "text" ),
				        "file_server_path" =>  array( "type"=> "text" ),
				        "files_thumbs_path"=>  array( "type"=> "text" ),
				        "file_directory"   =>  array( "type"=> "text" ),
				        "rating"           =>  array( "type"=> "text" ),
				        "comments_count"   =>  array( "type"=> "integer" ),
				        "default_thumb"    =>  array( "type"=> "integer" ),
                        "status"           =>  array( "type"=> "text" ),
                        "broadcast"        =>  array( "type"=> "text" ),
				        "views"            =>  array( "type"=> "integer" ),
				        "date_added" =>  array(
					        "type"   =>  "date"
					    )
				    )
			    )
		  	)
		);


    /**
     * This variable is to define mapping of videos Object
     *
     * @object Variable
    */
    public $photoMappingData = array(
            "mappings"=> array(
                "_doc"=> array( 
                    "properties"=> array( 
                        "photo_id"         =>  array( "type"=> "long" ), 
                        "title"      =>  array( "type"=> "text" ), 
                        "username"         =>  array( "type"=> "text" ),
                        "first_name"       =>  array( "type"=> "text" ),
                        "last_name"        =>  array( "type"=> "text" ),
                        "description"      =>  array( "type"=> "text" ), 
                        "tags"             =>  array( "type"=> "text" ),
                        "userid"           =>  array( 
                                                    "type"=> "long", 
                                                ), 
                        "collection_id"    =>  array( 
                                                    "type"=> "long", 
                                                ), 
                        "photo_key"        =>  array( "type"=> "text" ),
                        "file_name"        =>  array( "type"=> "text" ),
                        "file_directory"   =>  array( "type"=> "text" ),
                        "rating"           =>  array( "type"=> "text" ),
                        "photo_details"    =>  array( "type"=> "text" ),
                        "active"           =>  array( "type"=> "text" ),
                        "views"            =>  array( "type"=> "integer" ),
                        "date_added" =>  array(
                            "type"   =>  "date"
                        )
                    )
                )
            )
        );

    /**
     * This variable is to define mapping of videos Object
     *
     * @object Variable
    */
    public $userMappingData = array(
			"mappings"=> array(
			    "_doc"=> array( 
				    "properties"=> array( 
				    	"userid"      =>  array( 
                                            "type"=> "long", 
                                        ), 
				        "username"    =>  array( "type"=> "text" ), 
				        "first_name"  =>  array( "type"=> "text" ), 
				        "last_name"   =>  array( "type"=> "text" ),
				        "email"       =>  array( "type"=> "text" ),
                        "category"    =>  array( 
                                                "type" => "object",
                                                "properties" => array(
                                                    "id" => array(
                                                        "type"=>"integer",
                                                    ),
                                                    "name"=> array(
                                                        "type"=>"text",
                                                        "fielddata"=>true,
                                                    )
                                                )  
                                            ),
                        "usr_status"  =>  array( "type"=> "text" ),
				        "views"       =>  array( "type"=> "integer" ),
                        "total_videos" =>  array( "type"=> "integer" ),
				        "date_added"  =>  array(
					        "type"    =>  "date"
					    )
				    )
			    )
		  	)
		);

    

    /**
     * This variable is to define mapping of videos Object
     *
     * @object Variable
    */
    public $collectionMappingData = array(
            "mappings"=> array(
                "_doc"=> array( 
                    "properties"=> array( 
                        "group_id"      =>  array( "type"=> "integer" ), 
                        "group_name"    =>  array( "type"=> "text" ), 
                        "userid"        =>  array( 
                                            "type"=> "long", 
                                        ), 
                        "username"         =>  array( 
                                            "type"=> "text",
                                            "fielddata"=> true 
                                        ), 
                        "first_name"       =>  array( "type"=> "text" ),
                        "last_name"        =>  array( "type"=> "text" ),
                        "group_description"   =>  array( "type"=> "text" ),
                        "group_tags"    =>  array( "type"=> "text" ),
                        "group_url"     =>  array( "type"=> "text" ),
                        "category"      =>  array( 
                                                "type" => "object",
                                                "properties" => array(
                                                    "id" => array(
                                                        "type"=>"integer",
                                                    ),
                                                    "name"=> array(
                                                        "type"=>"text",
                                                        "fielddata"=>true,
                                                    )
                                                )  
                                            ),
                        "active"        =>  array( "type"=> "text" ),
                        "views"         =>  array( "type"=> "integer" ),
                        "total_videos"  =>  array( "type"=> "integer" ),
                        "total_members" =>  array( "type"=> "integer" ),
                        "total_topics"  =>  array( "type"=> "integer" ),
                        "date_added"    =>  array(
                            "type"    =>  "date"
                        )
                    )
                )
            )
        );


	/**
     * The Constrcutor of Controller Class
     *
     * @constructor Function
    */
	function __construct($index=false)
	{
		
		$this->apiUrl = config('elastic_server_ip');
		$this->index = $index;
	}


	/**
     * @todo    : This method is used to map Mysql Database tables with ES
     * @param   : { $index,$postData } { tablename and mapping Data }  
     * @since   : { 5th June 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { MapIndex($request,$response, $args) } 
     * 
     * This method is used to map Mysql Database tables with E
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function EsMap($mapData,$extras=false){


		$request = array();
		
		$request["post_arr"] = json_encode($mapData);
		$request["url"] = $this->apiUrl.'/'.$this->index;
		$request["method"] = $extras["method"];
		$request["headers"] = array('Content-Type: application/json');
		if ($request["method"] == 'delete'){
			unset($request["post_arr"]);
			unset($request["headers"]);
		}
		
		$response = generic_curl($request);
		return $response;

	}


	public function EsIndex($IndexData,$extras=false){

		$request = array();
		
		$request["post_arr"] = $IndexData;
		$request["url"] = $this->apiUrl.'/'.$this->index.'/_doc/'.$extras["id"];
		$request["method"] = $extras["method"];
		$request["headers"] = array('Content-Type: application/json');
		if ($request["method"] == 'delete' || $request["method"] == 'get'){
			unset($request["post_arr"]);
			unset($request["headers"]);
		}
		$response = generic_curl($request);
		return $response;

	}

	/**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function FormatVideo($video){

		global $db,$cbvid,$cbgroup,$userquery;
		
		$newVideo = (object)array();

		$newVideo->videoid = $video["videoid"];
        $newVideo->userid = $video["userid"];
		$newVideo->title = $video["title"];
		$newVideo->description = htmlspecialchars($video["description"]);
		$newVideo->tags = $video['tags'];

        $categories = $cbvid->get_category_names($video["category"],false);
        $category = array();
        if (is_array($categories)){
            foreach ($categories as $key => $cat) {
                $cattemp["id"]  = $cat["category_id"];
                $cattemp["name"]  = $cat["category_name"];
                $category[] = $cattemp;
            }
        }

        
        $user_details = $userquery->get_user_details($video["userid"]);
        if ($user_details){
            $newVideo->username = $user_details['username'];
            $newVideo->first_name = $user_details['first_name'];
            $newVideo->last_name = $user_details['last_name'];
        }              
        
		$newVideo->category = $category;
        $newVideo->videokey = $video['videokey'];
        $newVideo->file_name = $video['file_name'];
        $newVideo->file_server_path = $video['file_server_path'];
        $newVideo->files_thumbs_path = $video['file_server_path'];
        $newVideo->file_directory = $video['file_directory'];
        $newVideo->rating = $video['rating'];
        $newVideo->comments_count = $video['comments_count'];
        $newVideo->default_thumb = $video['default_thumb'];
        $newVideo->broadcast = $video['broadcast'];
        $newVideo->status = $video['status'];
        $newVideo->views = $video['views'];
        $date = new DateTime($video["date_added"]);
		$newVideo->date_added = $date->format('Y-m-d');
        #pre($newVideo,1);
		return json_encode($newVideo);

	}


    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
    public function FormatPhoto($photo){

        global $db,$cbphoto,$userquery;
        
        $newPhoto = (object)array();

        $newPhoto->photo_id = $photo["photo_id"];
        $newPhoto->userid = $photo["userid"];
        $newPhoto->collection_id = $photo["collection_id"];
        $newPhoto->photo_title = $photo["photo_title"];
        $newPhoto->photo_description = htmlspecialchars($photo["photo_description"]);
        $newPhoto->photo_tags = $photo['photo_tags'];

        
        $user_details = $userquery->get_user_details($photo["userid"]);
        if ($user_details){
            $newPhoto->username = $user_details['username'];
            $newPhoto->first_name = $user_details['first_name'];
            $newPhoto->last_name = $user_details['last_name'];
        }              
        
       
        $newPhoto->photo_key = $photo['photo_key'];
        $newPhoto->filename = $photo['filename'];
        $newPhoto->file_directory = $photo['file_directory'];
        $newPhoto->rating = $photo['rating'];
        $newPhoto->active = $photo['active'];
        $newPhoto->views = $photo['views'];
        $newPhoto->ext = $photo['ext'];
        $date = new DateTime($photo["date_added"]);
        $newPhoto->date_added = $date->format('Y-m-d');
        #pre($newPhoto,1);
        return json_encode($newPhoto);

    }


	/**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function FormatUser($user){

		global $db,$userquery;
		
		$newUser = (object)array();

		$newUser->userid = $user["userid"];
		$newUser->first_name = $user["first_name"];
		$newUser->last_name = $user["last_name"];
		$newUser->username = $user["username"];
		$newUser->email = $user["email"];
		$newUser->views = $user["profile_hits"];
        $newUser->usr_status = $user["usr_status"];
        $categories = $userquery->get_category_names($user["category"],false);
        $category = array();
        if (is_array($categories)){
            foreach ($categories as $key => $cat) {
                $cattemp["id"]  = $cat["category_id"];
                $cattemp["name"]  = $cat["category_name"];
                $category[] = $cattemp;
            }
        }
        $user_details = $userquery->get_user_details($user["userid"]);
        if ($user_details){
            $newUser->username = $user_details['username'];
            $newUser->first_name = $user_details['first_name'];
            $newUser->last_name = $user_details['last_name'];
        }           
        $newUser->category = $category;
        $newUser->total_videos = $user["total_videos"];
        $newUser->total_photos = $user["total_photos"];
        $newUser->subscribers = $user["subscribers"];
        $newUser->total_subscriptions = $user["total_subscriptions"];
        $date = new DateTime($user["doj"]);
		$newUser->date_added = $date->format('Y-m-d');

		return json_encode($newUser);

	}


    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
    public function FormatGroupCollection($group){

        global $db,$cbgroup,$userquery;
        
        $NewGroup = (object)array();

        $NewGroup->group_id = $group["group_id"];
        $NewGroup->group_name = $group["group_name"];
        $NewGroup->userid = $group["userid"];
        $NewGroup->group_description = htmlspecialchars($group["group_description"]);
        $NewGroup->group_tags = $group["group_tags"];
        $NewGroup->group_url = $group["group_url"];
        $categories = $cbgroup->get_category_names($group["category"],false);
        $category = array();
        if (is_array($categories)){
            foreach ($categories as $key => $cat) {
                $cattemp["id"]  = $cat["category_id"];
                $cattemp["name"]  = $cat["category_name"];
                $category[] = $cattemp;
            }
        }
        $user_details = $userquery->get_user_details($group["userid"]);
        if ($user_details){
            $NewGroup->username = $user_details['username'];
            $NewGroup->first_name = $user_details['first_name'];
            $NewGroup->last_name = $user_details['last_name'];
        }    
        $NewGroup->category = $category;
        $NewGroup->views = $group["total_views"];
        $NewGroup->total_videos = $group["total_videos"];
        $NewGroup->total_members = $group["total_members"];
        $NewGroup->total_topics = $group["total_topics"];

        $NewGroup->active = $group["active"];
        $date = new DateTime($group["date_added"]);
        $NewGroup->date_added = $date->format('Y-m-d');
        #pre($NewGroup,1);
        return json_encode($NewGroup);

    }

    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function ElasticSearch(){
		$request = array();
		
		$request["post_arr"] = $this->EsQuery;
		if ($this->index){
			$request["url"] = $this->apiUrl.'/'.$this->index.'/_search/';
		}else{
			$request["url"] = $this->apiUrl.'/_search/';
		}
		$request["method"] = "GET";
		$request["headers"] = array('Content-Type: application/json');
			

		#pre($request,1);
        $response = generic_curl($request);
		$this->results = $response;
        #pre(json_decode($this->results["result"],true),1);
		return $response;
	}

    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function getVideoQueryBool(){

		$bool = array();

        $bool_must["should"][] = array(
                "wildcard"=>array(
                    "title"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "description"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "category.name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "tags"=>$this->publicQuery
                ));
        $bool_must["should"][] = array(
                "prefix"=>array(
                    "username"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "first_name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "email"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "last_name"=>$this->publicQuery
                ));

             
            
        $bool["must"][] = array(
                "bool"=>$bool_must
            );

	
        if (!has_access("admin_access")){
            $bool["filter"][] = array(
                "match"=>array(
                    "status"=>'Successful'
                )
            );
        }
       	
        return $bool;
	}

    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
	public function getUserQueryBool(){

		$bool = array();
		
		$bool_must["should"][] = array(
	            "prefix"=>array(
	                "username"=>$this->publicQuery
	            ));
        $bool_must["should"][] = array("prefix"=>array(
                    "first_name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "email"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "last_name"=>$this->publicQuery
                ));
             
        	
        $bool["must"][] = array(
                "bool"=>$bool_must
        	);


        if (!has_access("admin_access",true)){
            $bool["filter"][] = array(
                "match"=>array(
                    "usr_status"=>'Ok'
                )
            );
        }
        
        return $bool;
	}

    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
    public function getGCQueryBool(){

        $bool = array();
        
        $bool_must["should"][] = array(
                "match"=>array(
                    "group_name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "group_description"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "group_tags"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "category.name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array(
                "prefix"=>array(
                    "username"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "first_name"=>$this->publicQuery
                ));
         $bool_must["should"][] = array("prefix"=>array(
                    "email"=>$this->publicQuery
                ));
          $bool_must["should"][] = array("prefix"=>array(
                    "last_name"=>$this->publicQuery
                ));
             
            
        $bool["must"][] = array(
                "bool"=>$bool_must
            );


        if (!has_access("admin_access")){
            $bool["filter"][] = array(
                "match"=>array(
                    "active"=>'yes'
                )
            );
        }

        return $bool;
        
       
    }


     /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
    public function getAllQueryBool(){

        $bool = array();

        //videos
        $bool_must["should"][] = array(
                "match"=>array(
                    "title"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "description"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "tags"=>$this->publicQuery
                ));
        //users
        $bool_must["should"][] = array(
                "prefix"=>array(
                    "username"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("prefix"=>array(
                    "first_name"=>$this->publicQuery
                ));
         $bool_must["should"][] = array("prefix"=>array(
                    "email"=>$this->publicQuery
                ));
          $bool_must["should"][] = array("prefix"=>array(
                    "last_name"=>$this->publicQuery
                ));
        //groups && collections
        $bool_must["should"][] = array(
                "match"=>array(
                    "group_name"=>$this->publicQuery
                ));
        $bool_must["should"][] = array("match"=>array(
                    "group_description"=>$this->publicQuery
                ));
        $bool_must["should"][] = array(
            "match"=>array(
                    "group_tags"=>$this->publicQuery
            )
        );


        //all in all
        $bool_must["should"][] = array(
            "match"=>array(
                    "category.name"=>$this->publicQuery
            )
        );
             
            
        $bool["must"][] = array(
                "bool"=>$bool_must
            );


       


        if (!has_access("admin_access",true)){
            $bool["filter"][] = array(
                "match"=>array(
                    "active"=>'yes'
                )
            );
            $bool["filter"][] = array(
                "match"=>array(
                    "usr_status"=>'Ok'
                )
            );
            $bool["filter"][] = array(
                "match"=>array(
                    "status"=>'Successful'
                )
            );
        }

       

        return $bool;
        
       
    }


    /**
     * @todo    : This method is used to format video for ES sever request
     * @param   : { $video } { video to be processed }  
     * @since   : { 30 October 2018 } 
     * @return  : { Array } {Video Array}
     * @author  : <fahad.dev@iu.com.pk> <Fahad Abbas>
     * @example : { FormatVideo($video) } 
     * 
     * This method is used to format video for ES sever request
     *  
     * @throws Exception if any of the Error come
     *
    */
    public function buildQuery(){

        $bool = array();
        
        if ($this->index == 'videos'){
            $queryBool = $this->getVideoQueryBool();
        }elseif ($this->index == 'users'){
            $queryBool = $this->getUserQueryBool();
        }elseif ($this->index == 'groups' || $this->index == 'collections') {
            $queryBool = $this->getGCQueryBool();
        }else{
            $queryBool = $this->getAllQueryBool();
        }

        //this must be called on videos index
        if (!empty($this->filters['category'])){
            $queryBool = array();
            $queryBool["must"][] = array(
                "match"=>array(
                    "category.id"=>$this->filters['category']
                )
            );
        }
        if (!empty($this->filters['author'])){
            $queryBool = array();
            $queryBool["must"][] = array(
                "match"=>array(
                    "userid"=>$this->filters['author']
                )
            );
        }

        ////this must be called on collections / groups index
        if (!empty($this->filters['object_id'])){
            $queryBool = array();
            $queryBool["must"][] = array(
                "match"=>array(
                    "group_id"=>$this->filters['object_id']
                )
            );
        }

        //pr($this->index,1);
        $this->EsQuery["query"] = array("bool"=>$queryBool);
        #pre($this->EsQuery["query"],1);

        if ($this->sort == 'date'){
            $sort["date_added"] = array("order"=>"desc");
        }else if ($this->sort == 'views'){
            $sort["views"] = array("order"=>"desc");
        }else{
            $sort["_score"]  = array("order"=>"desc");
        }


       $this->EsQuery["aggs"]["object"] = array(
                            "terms" => array(
                                "field" => "_index"
                            ),
                        );

        $this->EsQuery["aggs"]["byindex"] = array(
                    "terms" => array(
                        "field" => "_index"
                    ),
                    
                    "aggs" => array(
                        "author" =>  array(
                            "terms" => array(
                                "field" => "userid"
                            ),
                            "aggs"=> array(
                                "platform"=> array(
                                    "top_hits"=> array(
                                        "size"=> 1, 
                                        "_source"=> 
                                        array("include"=> array(
                                            'userid',
                                            'username',
                                            'first_name',
                                            'last_name',
                                            )
                                        )
                                    )
                                )
                            )
                        ), 
                        "category" =>  array(
                            "terms" => array(
                                "field" => "category.id",
                            ),
                            "aggs"=> array(
                                "platform"=> array(
                                    "top_hits"=> array(
                                        "size"=> 1, 
                                        "_source"=> 
                                        array("include"=> array('category'))
                                    )
                                )
                            )
                        ),
                        "collections" =>  array(
                            "terms" => array(
                                "field" => "collections.id",
                            ),
                            "aggs"=> array(
                                "platform"=> array(
                                    "top_hits"=> array(
                                        "size"=> 1, 
                                        "_source"=> 
                                        array("include"=> array('collections'))
                                    )
                                )
                            )
                        ), 
                        "groups" =>  array(
                            "terms" => array(
                                "field" => "groups.id",
                            ),
                            "aggs"=> array(
                                "platform"=> array(
                                    "top_hits"=> array(
                                        "size"=> 1, 
                                        "_source"=> 
                                        array("include"=> array('groups'))
                                    )
                                )
                            )
                        ),  
                    )
                    
                );
        


        
        $this->EsQuery["sort"] = $sort;


        $this->EsQuery["from"] = $this->from;
        $this->EsQuery["size"] = $this->size;
        #pre($this->EsQuery,1);
        $this->EsQuery = json_encode($this->EsQuery);
    }


    public function makeFilters(){
        global $userquery;
        $results = json_decode($this->results["result"],1);
        $aggregations = $results["aggregations"];

        $filters["SOURCE"] = array();
        $filters["AUTHOR"] = array();
        $filters["CATEGORY"] = array();
        $filters["COLLECTION"] = array();
        $filters["GROUP"] = array();

        $filters["SOURCE"] = $aggregations["object"]["buckets"];

        $videos_aggregations = array();
        $aggregations = $aggregations["byindex"]["buckets"];
        foreach ($aggregations as $key => $aggs) {
            if ($aggs['key'] == 'videos'){
                $videos_aggregations = $aggs;
            }
        }
        #pre($videos_aggregations,1);

        $authors_bucket = $videos_aggregations["author"]["buckets"];
        if (is_array($authors_bucket)){
            foreach ($authors_bucket as $key => $author) {
                $authors_temp["id"] = $author["key"];
                $authors_temp["counts"] = $author["doc_count"];
                $platform_hits = $author["platform"]["hits"]["hits"];
                if (is_array($platform_hits) && !empty($platform_hits)){
                    foreach ($platform_hits as $key => $hits) {
                       if ($hits["_source"]["userid"] == $author["key"]){
                            $authors_temp["source"] = $hits["_source"];
                       }
                    }
                }
                $filters["AUTHOR"][] = $authors_temp;
            }
        }
           
        $categories_bucket = $videos_aggregations["category"]["buckets"];
        if (is_array($categories_bucket) && !empty($categories_bucket)){
            foreach ($categories_bucket as $key => $category) {
                $categories_temp["id"] = $category["key"];
                $categories_temp["counts"] = $category["doc_count"];
                $platform_hits = $category["platform"]["hits"]["hits"];
                if (is_array($platform_hits) && !empty($platform_hits)){
                    foreach ($platform_hits as $key => $hits) {
                        $category_source = $hits["_source"]["category"];
                        //pre($category_source,1);
                        if (is_array($category_source) && !empty($category_source)){
                            foreach ($category_source as $key => $cat) {
                                if ($cat["id"] == $category["key"]){
                                    $categories_temp["source"] = $cat["name"];
                                }
                            }
                        }
                    }
                }
                $filters["CATEGORY"][] = $categories_temp;
            }
        }

        $collections_bucket = $videos_aggregations["collections"]["buckets"];
        if (is_array($collections_bucket) && !empty($collections_bucket)){
            foreach ($collections_bucket as $key => $collection) {
                $collection_temp["id"] = $collection["key"];
                $collection_temp["counts"] = $collection["doc_count"];
                $platform_hits = $collection["platform"]["hits"]["hits"];
                if (is_array($platform_hits) && !empty($platform_hits)){
                    foreach ($platform_hits as $key => $hits) {
                        $collection_source = $hits["_source"]["collections"];
                        //pre($collection_source,1);
                        if (is_array($collection_source) && !empty($collection_source)){
                            foreach ($collection_source as $key => $cat) {
                                if ($cat["id"] == $collection["key"]){
                                    $collection_temp["source"] = $cat["name"];
                                }
                            }
                        }
                    }
                }
                $filters["COLLECTION"][] = $collection_temp;
            }
        }


        $groups_bucket = $videos_aggregations["groups"]["buckets"];
        if (is_array($groups_bucket) && !empty($groups_bucket)){
            foreach ($groups_bucket as $key => $group) {
                $groups_temp["id"] = $group["key"];
                $groups_temp["counts"] = $group["doc_count"];
                $platform_hits = $group["platform"]["hits"]["hits"];
                if (is_array($platform_hits) && !empty($platform_hits)){
                    foreach ($platform_hits as $key => $hits) {
                        $group_source = $hits["_source"]["groups"];
                        //pre($group_source,1);
                        if (is_array($group_source) && !empty($group_source)){
                            foreach ($group_source as $key => $cat) {
                                if ($cat["id"] == $group["key"]){
                                    $groups_temp["source"] = $cat["name"];
                                }
                            }
                        }
                    }
                }
                $filters["GROUP"][] = $groups_temp;
            }
        }
           
        #pre($filters,1);
        return $filters;

    }

}


?>