<?php
/**
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://www.opensource.org/licenses/attribution.php
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
 
 // We Can Set SEO urls or Simple Urls by selecting following options, by selecting YES , make sure that all ReWrites are defined in .httaccess
 
 $seo_urls = @SEO; 			//  yes/no
 function SEO( $text, $slash=false ) {
	
	$text = preg_replace('/ \&\#?[(0-9a-zA-Z){4}]+\;/','',$text);
	$entities_match		= array('&quot;','!','@','#','%','^','&','*','_','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';','"',',','.','/','*','+','~','`','=',"'");
	$entities_replace   = array('','','','','','','','','','','','','','','','','','','','','','','','');
	$clean_text	 	    = str_replace($entities_match, $entities_replace, $text);
	$clean_text = trim($clean_text);
	$clean_text = preg_replace('/ /','-',$clean_text);
    if ( $clean_text != '' )
        $slash              = ( $slash ) ? '/' : NULL;
	
	
	$clean_text = preg_replace('/\-{2,10}/','-',$clean_text);
	
	return $slash . $clean_text;
}	
	
 if($seo_urls == 'yes'){

		
		
	@define(compose_msg_link,'/message/compose');	
	@define(login_success,'/login/success');
	@define(logout_success,'/logout/success');
	@define(signup_success,'/signup/success');
	@define(signup_link,'/signup.php');	
	@define(myaccount_link,'/myaccount');
	@define(videos_link,'/videos');
	@define(view_group_link,'/group/view/');
	@define(user_account_link,'/manage/account');
	@define(search_result,'/search/result');
	//@define(edit_group_link,'/manage/group/edit/');
    @define(edit_group_link,'/edit_group.php?url=');
    @define(admin_link,'/admin_area/');

	

	}else{
	
	@define(compose_msg_link,'/compose.php');		
	@define(login_success,'/login_success.php');
	@define(logout_success,'/logout_success.php');
	@define(signup_success,'/signup_success.php');
	@define(signup_link,'/signup.php');
	@define(myaccount_link,'/myaccount.php');
	@define(videos_link,'/videos.php');
	@define(view_group_link,'/view_group.php?url=');
	@define(user_account_link,'/user_account.php');
	@define(search_result,'/search_result.php');
	@define(edit_group_link,'/edit_group.php?url=');
    @define(admin_link,'/admin_area/');

	}
?>