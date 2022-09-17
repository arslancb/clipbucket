<?php


if(!defined('IN_CLIPBUCKET'))
	exit('Invalid access');


$section = get('s');
$file = get('p');


if(defined('IN_MODULE') && $section=='elastic')
{
	
	
	//Elastic Search Page
	if($file=='search')
	{
			

		$search_mode = mysql_clean($_GET['type']) ?  mysql_clean($_GET['type']) : 'all';
		$page = mysql_clean($_GET['page']);
		$sort = mysql_clean($_GET['search_by']);
		$search = mysql_clean($_GET['query']);
		$page = $_GET['page'] ? $_GET['page'] : 1;

		#pex($search_mode,1);

		//pre($sort,1);


		switch ($search_mode) {
		    case 'videos':{

		        $query = array();
		        $es = new ElasticSearch($search_mode);
		        if (isset($_GET['category']) && !empty($_GET['category'])){
		            $es->filters["category"] = mysql_clean($_GET['category']); 
		        }
		        if (isset($_GET['author']) && !empty($_GET['author'])){
		            $es->filters["author"] = mysql_clean($_GET['author']); 
		        }
		        $es->publicQuery = $search;
		        if ($sort){
		            $es->sort = $sort;
		        }
		        if ($page > 1){
		            $from = $page - 1;
		            $es->from = $from * $es->size;
		        }
		        $es->buildQuery();
		        $es->ElasticSearch();
		        $results = json_decode($es->results['result'],1);
		        #pre($results,1);
		        if ($results["hits"]["hits"]){
		            $es->resultsHits = $results["hits"]["hits"];
		            foreach ($es->resultsHits as $key => $video) {
		                $newVideos[] = $video["_source"];
		            }
		        }
		            
		    }
		    break;


		    case 'photos':{

		        $query = array();
		        $es = new ElasticSearch($search_mode);
		        if (isset($_GET['category']) && !empty($_GET['category'])){
		            $es->filters["category"] = mysql_clean($_GET['category']); 
		        }
		        if (isset($_GET['author']) && !empty($_GET['author'])){
		            $es->filters["author"] = mysql_clean($_GET['author']); 
		        }
		        $es->publicQuery = $search;
		        if ($sort){
		            $es->sort = $sort;
		        }
		        if ($page > 1){
		            $from = $page - 1;
		            $es->from = $from * $es->size;
		        }
		        $es->buildQuery();
		        $es->ElasticSearch();
		        $results = json_decode($es->results['result'],1);
		        #pex($results,1);
		        if ($results["hits"]["hits"]){
		            $es->resultsHits = $results["hits"]["hits"];
		            foreach ($es->resultsHits as $key => $photo) {
		                $newPhotos[] = $photo["_source"];
		            }
		        }
		            
		    }
		    break;


		    case 'channels':{

		        $query = array();
		        $es = new ElasticSearch('users');
		        if (isset($_GET['category']) && !empty($_GET['category'])){
		            $es->filters["category"] = $_GET['category']; 
		        }
		        if (isset($_GET['author']) && !empty($_GET['author'])){
		            $es->filters["author"] = $_GET['author']; 
		        }
		        $es->publicQuery = $search;
		        if ($sort){
		            $es->sort = $sort;
		        }
		        if ($page > 1){
		            $from = $page - 1;
		            $es->from = $from * $es->size;
		        }
		        $es->buildQuery();
		        $es->ElasticSearch();
		        $results = json_decode($es->results['result'],1);
		        #pex($results,1);
		        if ($results["hits"]["hits"]){
		            $es->resultsHits = $results["hits"]["hits"];
		            foreach ($es->resultsHits as $key => $user) {
		                $newUsers[] = $user["_source"];
		            }
		        }
		    
		    }
		    break;



		    case 'all':
		    default:{

		        $query = array();
		        $es = new ElasticSearch(false);
		        if (isset($_GET['category']) && !empty($_GET['category'])){
		            $es->filters["category"] = $_GET['category']; 
		        }
		        if (isset($_GET['author']) && !empty($_GET['author'])){
		            $es->filters["author"] = $_GET['author']; 
		        }
		        $es->publicQuery = $search;
		        if ($sort){
		            $es->sort = $sort;
		        }
		        if ($page > 1){
		            $from = $page - 1;
		            $es->from = $from * $es->size;
		        }
		        $es->buildQuery();
		        $es->ElasticSearch();
		        $results = json_decode($es->results['result'],1);
		        #pex($results,1);
		        if ($results["hits"]["hits"]){
		            $es->resultsHits = $results["hits"]["hits"];
		            foreach ($es->resultsHits as $key => $item) {
		                if ($item["_index"] == 'videos'){
		                    $newVideos[] = $item["_source"];
		                }
		                if ($item["_index"] == 'users'){
		                    $newUsers[] = $item["_source"];
		                }
		                if ($item["_index"] == 'photos'){
		                    $newPhotos[] = $item["_source"];
		                }
		            }
		        }
		        
		    
		    }   
		    break;
		}



		$filters = $es->makeFilters();
		assign("filter_results",$filters);

		#pre($results,1);


		$get_limit = create_query_limit($page,10);
		$total_rows  = $results["hits"]['total'];
		$total_pages = count_pages($total_rows,10);
		$pages->paginate($total_pages,$page);




		if (DEVELOPMENT_MODE)
		assign("time_took",$results["took"] / 1000);
		#pre($es->results['result'],1);
		assign("mode",$search_mode);
		assign("videos",$newVideos);
		assign("users",$newUsers);
		assign("photos",$newPhotos);
					

		template_files('search.html',CB_ES_DIR.'/');
		display_it();
		exit();
		
	}
}

?>