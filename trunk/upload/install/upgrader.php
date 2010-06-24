<?php

/**
 * Installed Written by Arslan Hassan
 * @ Software : ClipBucket v2
 * @ license : CBLA
 * @ since : 2512-2009
 * @ author: Arslan Hassan
 */
define("FRONT_END",TRUE);
define("BACK_END",FALSE);
ini_set("include_path", "../includes");
$while_installing = true;
include("../includes/common.php");
//Including Embed Video Mod Plugin (IMPORTANT)
include('../plugins/embed_video_mod/embed_video_mod.php');

$errors = array();
$msgs = array();

$step = $_POST['step'];

if(file_exists(BASEDIR.'/files/install.lock'))
	exit("Installation Loc Found");


$countries = $Cbucket->get_countries();

if($_POST['upgrade'])
{
	
	$userquery 	= new userquery();
	$userquery->init();
	switch($step)
	{
		//Importing Users..
		case "import_users":
		{
			
			//First Importing Admin
			$user = $db->select("users","*"," userid='1'");
			$user = $user[0];
			
			$db->update(tbl("users"),array("username","password","email","avatar"),
									 array($user['username'],$user['password'],$user['email'],$user['avatar'])," userid='1' ");
			
			
			$userquery->login_as_user(1);	
			$userquery->init();
			
			
			//Getting List Of User
			$users = $db->select("users","*"," userid<>'1'");
			if(is_array($users))
			foreach($users as $user)
			{
				$array['username'] = $user['username'];
				$array['email']	= $user['email'];
				$array['password'] = '13246579';
				$array['cpassword'] = '13246579';
				$array['country'] = 'PK';
				$array['gender'] = $user['sex']?$user['sex']:"Male";
				$array['dob'] = $user['dob'];
				$array['category'] = 1;
				$array['level'] = 2;
				$array['active'] = $user['usr_status'];
				
				$eh->flush();
				
				$uid = $userquery->signup_user($array);
				
				$country = array_search($user['country'],$countries);
				
				
				$db->update(tbl("users"),
								array("password",
									  "signup_ip",
									  "country","doj","num_visits","avcode","profile_hits","total_watched",
									  "total_videos",
									  "total_comments",
									  "total_groups",
									  "avatar",
									  "background",
									  "avcode",
									  ),
								
									 array($user['password'],
										$user['signup_ip'],
										$country,$user['doj'],
										$user["num_visits"],$user['avcode'],$user['profile_hits'],$user['total_watched'],
										$user['total_videos'],
										$user['total_comments'],
										$user['total_groups'],
										$user['avatar'],
										$user['background'],
										$user['avcode']
										),
									 
									 " userid='$uid' ");
			}
			
			$msgs = msg();
			$msgs[] = "All Users have been imported";
			if(error())
			$errors = error();
			
			
        	include("steps/msgs.php");
        	echo "<a href=\"javascript:void(0)\" onClick=\"import_vids()\">Click Here To Continue Upgrading...</a>";
		}
		break;
		
		case "import_video":
		{
			//Dropping Existing Categories
			$db->execute("DELETE FROM ".tbl("video_categories"));
			
			//Getting List of categories
			$results = $db->select("category","*");
			$count = 0;
			foreach($results as $r)
			{
				
				$db->insert(tbl("video_categories"),
					array
					(
					 'category_id','category_name','category_order','category_desc','date_added','category_thumb','isdefault'
					 ),
					array
					(
					 $r['categoryid'],
					 $r['category_name'],
					 1,
					 $r['category_desc'],
					 $r['date_added'],
					 $r['category_thumb'],
					 $r['isdefault'],
					 ));
				
				if($count==0)
					$cbvid->make_default_category($r['categoryid']);
				
				$count++;
			}
			$msgs[] = "Categories have been imported";
			
			//Getting List Of Videos
			$vids = $db->select("video","*");
			foreach($vids as $vid)
			{
				$category = "";
				//Settin File
				$file = getName($vid['flv']);
				//Getting userId
				$userid = $userquery->get_user_field_only($vid['username'],"userid");
				//Setting Category
				if($vid['category01'])
					$category .= "#".$vid['category01']."#";
				if($vid['category02'])
					$category .= "#".$vid['category02']."#";
				if($vid['category03'])
					$category .= "#".$vid['category03']."#";
					
				if(!vkey_exists($vid['videokey']))
				$db->insert(tbl("video"),
				array
				(
				 'videokey',
				 'title',
				 'description',
				 'tags',
				 'broadcast',
				 'country',
				 'allow_embedding',
				 'rating',
				 'rated_by',
				 'voter_ids',
				 'allow_comments',
				 'comments_count',
				 'active',
				 'views',
				 'date_added',
				 'duration',
				 'status',
				 'embed_code',
				 'uploader_ip',
				 'file_name',
				 'userid',
				 'category',
				 ),
				array
				(
				$vid['videokey'],
				$vid['title'],
				$vid['description'],
				$vid['tags'],
				$vid['broadcast'],
				$vid['country'],
				$vid['allow_embedding'],
				$vid['rating'],
				$vid['rated_by'],
				$vid['voter_ids'],
				$vid['allow_comments'],
				$vid['comments_count'],
				$vid['active'],
				$vid['views'],
				$vid['date_added'],
				$vid['duration'],
				$vid['status'],
				"|no_mc|".htmlspecialchars(validate_embed_code($vid['embed_code'])),
				$vid['uploader_ip'],
				$file,
				$userid,
				$category
				 )
				);
			}
			
			$msgs = msg();
			$msgs[] = "All Videos have been imported";
			if(error())
			$errors = error();			
			
        	include("steps/msgs.php");
        	echo "<a href=\"javascript:void(0)\" onClick=\"import_comments()\">Click Here To Continue Upgrading...</a>";
		}
		break;
		case "import_comments":
		{
			//Getting list Of Comments
			$coms = $db->select("video_comments","*");
			if(is_array($coms))
			foreach($coms as $com)
			{
				$vid = $com['videoid'];
				$comment = $com['comment'];
				$username 	 = $com['username'];
				$date_added 	 = $com['date_added'];
				$score 	 = $com['score'];
				$scorer_ids 	 = $com['scorer_ids'];
				$reply_to 	 = $com['reply_to'];
				$userid = $userquery->get_user_field_only($username,"userid");
				$owner_id = $cbvid->get_video_owner($vid,true);
				
				//Get Video Key From Old Video Table
				$vkey = $db->select("video","videokey"," videoid='$vid' ");
				$vkey = $vkey[0];
				$vkey = $vkey['videokey'];
				
				//Get Id From Video Key
				$vid = $db->select(tbl("video"),"videoid"," videokey='$vkey' ");
				$vid = $vid[0];
				$vid = $vid['videoid'];

				$db->insert(tbl('comments'),
				array
				(
				 'type','comment','userid','parent_id','type_id','type_owner_id','vote','voters','date_added'
				 ),
				array
				(
				 'v',$comment,$userid,$reply_to,$vid,$owner_id,$score,$score_ids,$com['date_added']
				 )
				);
			}			
        	include("steps/msgs.php");
        	echo "<a href=\"javascript:void(0)\" onClick=\"import_configs()\">Click Here To Continue Upgrading...</a>";
		}
		break;
		case "update_configs":
		{
			//Updating Configs...
			$configs = $db->select("config","*");

			$array = array("site_title","site_slogan","description","keywords","ffmpegpath","flvpath","keep_original","activation","email_verification","allow_reg","php_path","video_download","video_embed","seo","max_upload_size","allow_upload","allowed_types","baseurl");
			
			foreach($configs as $cnfg)
			{
				$nconfig[$cnfg['name']] = $cnfg['value'];
			}
			
			foreach($array as $arr)
			{
				if($arr == 'flvpath')
				{
					$db->update(tbl("config"),array("value"),array($nconfig[$arr])," name='flvtool2path'");
				}
				elseif($arr == 'allow_reg')
				{
					$db->update(tbl("config"),array("value"),array($nconfig[$arr])," name='allow_registration'");
				}elseif($arr== 'allowed_types')
				{
					$db->update(tbl("config"),array("value"),array(preg_replace("/ /",",",$nconfig[$arr]))," name='allowed_types'");
				}else
				{
					$db->update(tbl("config"),array("value"),array($nconfig[$arr])," name='".$arr."'");
				}
			}
			
			$msgs[] = "All Configs have been imported";
			include("steps/msgs.php");
        	echo "<a href=\"javascript:void(0)\" onClick=\"final()\">Click Here To Continue Upgrading...</a>";

		}
		break;
		case "finalize_upgrade":
		{
			//Finalize Upgrade
			//Setting Up The lock
            //file_put_contents(BASEDIR.'/files/install.lock',time());
            //file_put_contents(BASEDIR.'/includes/clipbucket.php',file_get_contents('clipbucket.php'));
			copy("install.loc",BASEDIR.'/files/install.lock');
			unlink(BASEDIR."/includes/clipbucket.php");
			copy("clipbucket.php",BASEDIR."/includes/clipbucket.php");
			//Dropin Db Tbls
			$tbls = array("ads_data","ads_placements","category","channel_comments","config","contacts","editors_picks","email_settings","flagged_videos","groups","group_invitations","group_members","group_posts","group_topics","group_videos","logs","logs_ping","messages","modules","players","player_configs","player_skins","plugins","plugin_config","stats","subscriptions","template","users","video","video_comments","video_detail","video_favourites");
			foreach($tbls as $tbl){
				$db->Execute("DROP TABLE IF EXISTS `$tbl`");
			}
			
			$msgs[] = "Successfully Upgraded To v2";
			include("steps/msgs.php");
        	echo "<a href=\"".BASEURL."/admin_area\" >Click Here To Go To Admin Panel...</a>";

		}
	}
}	
?>