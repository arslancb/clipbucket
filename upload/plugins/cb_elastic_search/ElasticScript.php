<?php 

require 'includes/config.inc.php';

//require_once("includes/classes/elasticSearch.php");


$request = $_REQUEST;
$mode = $request["mode"];
$type = $request["type"];
if (!isset($request["method"])){
	$method = "get";
}else{
	$method = $request["method"];
}
#$method = $request['method'] ? "GET" : "GET";
$offset = 0;
$count = 0;

if (isset($request["offset"])){
	$offset = $request["offset"];
}
if (isset($request["count"])){
	$count = $request["count"];
}


$response = array();
$response["data"] = null;
$results = array();
try{

	if (!$mode){
		throw new Exception("Please provide mode to map or index");
	}

	
	switch ($mode) {
		default:
		case 'videos':{

			$index = $mode;
			$es = new ElasticSearch($index);

			if ($type == 'map'){

				
				//mapping the database with ES server for videos
				$mappingData = $es->videoMappingData;
				//Finally Calling the Function
				$extras["method"] = $method;
				$response["data"] = $es->EsMap($mappingData,$extras);
				if ($response["data"]["curl_error_no"]){
					exit(json_encode(array("err"=>$response["data"]["curl_error"],"data"=>$response)));
				}else{
					exit(json_encode(array("msg"=>"success","data"=>$response)));
				}


				
			}elseif ($type == 'index'){

				$videoRequest = array();

				//applying dynamic limit
				if ($count){
					$videoRequest["limit"] = $offset.','.$count;
				}
				 
				// get a specific video
				if ( isset($request["id"]) ){
					$videoRequest["videoid"] = $request["id"];
				} 
				
				//Fetching Videos to process
				$videos = $cbvid->get_videos($videoRequest);
				$extras["method"] = $method;
				
				if ($videos){
		
					foreach ($videos as $key => $video) {
						
						$formattedVideo = $es->FormatVideo($video);
						$extras["id"] = $video["videoid"];
						$response["data"] = $es->EsIndex($formattedVideo,$extras);
						
						//checking for Curl Error
						if ($response["data"]["curl_error_no"]){
							throw new Exception($response["data"]["curl_error"]);
							
 						}
 						#pre($response,1);
 						//checking for bad request or error
 						/*if ($response["data"]["code"] == '400' ){
							throw new Exception($response["data"]["result"]);
 						}*/

 						$results[] = $response["data"];
 					}
				}else{
					throw new Exception("No Video Found for this request");
				}

				//Creating Response
				exit(json_encode(array("msg"=>"success","data"=>$results)));
				
			}else{
				throw new Exception("Invalid Request, please select proper type");	
			}


			
		}
		break;
		
		case 'users':{


			$index = $mode;
			$es = new ElasticSearch($index);

			if ($type == 'map'){

				
				//mapping the database with ES server for videos
				$mappingData = $es->userMappingData;
				//Finally Calling the Function
				$extras["method"] = $method;
				$response["data"] = $es->EsMap($mappingData,$extras);
				if ($response["data"]["curl_error_no"]){
					exit(json_encode(array("err"=>$response["data"]["curl_error"],"data"=>$response)));
				}else{
					exit(json_encode(array("msg"=>"success","data"=>$response)));
				}


				
			}elseif ($type == 'index'){

				$userRequest = array();

				//applying dynamic limit
				if ($count){
					$userRequest["limit"] = $offset.','.$count;
				}
				
				// get a specific user
				if ( isset($request["id"]) ){
					$userRequest["userid"] = $request["id"];
				} 
				
				//Fetching users to process
				$users = $userquery->get_users($userRequest);
				$extras["method"] = $method;

				if ($users){
					foreach ($users as $key => $user) {
						$formatteduser = $es->FormatUser($user);
						$extras["id"] = $user["userid"];
						$response["data"] = $es->EsIndex($formatteduser,$extras);
						
						//checking for Curl Error
						if ($response["data"]["curl_error_no"]){
							throw new Exception($response["data"]["curl_error"]);
							
 						}
 						#pre($response,1);
 						//checking for bad request or error
 						/*if ($response["data"]["code"] == '400' ){
							throw new Exception($response["data"]["result"]);
 						}*/

 						$results[] = $response["data"];
 					}
				}else{
					throw new Exception("No Video Found for this request");
				}

				//Creating Response
				exit(json_encode(array("msg"=>"success","data"=>$results)));
				
			}else{
				throw new Exception("Invalid Request, please select proper type");	
			}


		}
		break;

		case 'collections':
		case 'groups':{


			$index = $mode;
			$es = new ElasticSearch($index);

			if ($type == 'map'){
				
				//mapping the database with ES server for videos
				if ($index == 'groups'){
					$mappingData = $es->groupMappingData;
				}else{
					$mappingData = $es->collectionMappingData;
				}
				//Finally Calling the Function
				$extras["method"] = $method;
				$response["data"] = $es->EsMap($mappingData,$extras);
				if ($response["data"]["curl_error_no"]){
					exit(json_encode(array("err"=>$response["data"]["curl_error"],"data"=>$response)));
				}else{
					exit(json_encode(array("msg"=>"success","data"=>$response)));
				}


				
			}elseif ($type == 'index'){

				$groupRequest = array();

				//applying dynamic limit
				if ($count){
					$groupRequest["limit"] = $offset.','.$count;
				}
				// get a specific group
				if ( isset($request["id"]) ){
					$groupRequest["group_id"] = $request["id"];
				} 
				
				if ($index == 'collections'){
					$groupRequest["is_collection"] = "yes";
				}else{
					$groupRequest["is_collection"] = "no";
				}

				//Fetching groups to process
				$groups = $cbgroup->get_groups($groupRequest);
				$extras["method"] = $method;

				if ($groups){
					foreach ($groups as $key => $group) {
						$formattedgroup = $es->FormatGroupCollection($group);
						$extras["id"] = $group["group_id"];
						$response["data"] = $es->EsIndex($formattedgroup,$extras);
						#pr($response,1);
						//checking for Curl Error
						if ($response["data"]["curl_error_no"]){
							throw new Exception($response["data"]["curl_error"]);
							
 						}
 						#pre($response,1);
 						//checking for bad request or error
 						/*if ($response["data"]["code"] == '400' ){
							throw new Exception($response["data"]["result"]);
 						}*/

 						$results[] = $response["data"];
 					}
				}else{
					throw new Exception("No Video Found for this request");
				}

				//Creating Response
				exit(json_encode(array("msg"=>"success","data"=>$results)));
				
			}else{
				throw new Exception("Invalid Request, please select proper type");	
			}

		}
		break;

	
	}

}catch(Exception $e){
	exit(json_encode(array("err"=>$e->getMessage(),"data"=>$response)));
}

?>