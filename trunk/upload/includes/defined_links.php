<?php
/**
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://clip-bucket.com/cbla
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/

 // We Can Set SEO urls or Simple Urls by selecting following options, by selecting YES , make sure that all ReWrites are defined in .httaccess
 
 $seo_urls = SEO; 			//  yes/no
 

 /* if selected yes then our UPLOAD link will become http://ourdomain.com/upload and if no the http://ourdomain.com/upload.php */
 
 if($seo_urls == 'yes'){
 	@Assign('seo','yes');
	 
 	Assign('logout_link',BASEURL.'/logout');
	Assign('leave_group_link',BASEURL.'/group/leave/');
	Assign('sent_link',BASEURL.'/message/sent/');
	Assign('signup_link',BASEURL.'/signup');
	Assign('subscriptions_link',BASEURL.'/subscriptions/');
	Assign('search_result',BASEURL.'/search/result');
 	Assign('upload_link',BASEURL.'/upload');
	Assign('myaccount_link',BASEURL.'/myaccount');
	Assign('manage_videos',BASEURL.'/manage/videos/');
	Assign('manage_subscriptions',BASEURL.'/manage/subscriptions/');
	Assign('manage_favourites_link',BASEURL.'/manage/favourites/');
	Assign('manage_contacts_link',BASEURL.'/manage/contacts/');
	Assign('manage_group_videos_link',BASEURL.'/manage_group_videos.php?url=');
	Assign('manage_group_members_link',BASEURL.'/manage_group_members.php?url=');
	Assign('manage_groups_link',BASEURL.'/manage/group/');
	Assign('videos_link',BASEURL.'/videos');
	Assign('user_account_link',BASEURL.'/manage/account');
	Assign('user_contacts_link',BASEURL.'/user/contacts/');
	Assign('user_videos_link',BASEURL.'/user/videos/');
	Assign('user_fav_videos_link',BASEURL.'/user/favourites/');
	Assign('channels_link',BASEURL.'/channels/');
	Assign('compose_msg_link',BASEURL.'/message/compose/');
	Assign('view_channel_link',BASEURL.'/channel/');
	Assign('view_topic_link',BASEURL.'/group/topic/');
	Assign('view_group_link',BASEURL.'/group/view/');
	Assign('view_group_videos_link',BASEURL.'/view_group_videos.php?url=');
	Assign('view_group_members_link',BASEURL.'/view_group_members.php?url=');
	Assign('inbox_link',BASEURL.'/message/inbox/');
	Assign('invite_group_members_link',BASEURL.'/invite_group.php?url=');
	Assign('edit_video_link',BASEURL.'/manage/video/edit');
    Assign('edit_video_watch',BASEURL.'/edit_video.php');
	Assign('edit_group_link',BASEURL.'/edit_group.php?url=');
	Assign('about_us_link',BASEURL.'/pages/aboutus/');
	Assign('add_group_video_link',BASEURL.'/add_group_videos.php?url=');
	Assign('contact_us_link',BASEURL.'/pages/contactus/');
	Assign('create_group_link',BASEURL.'/group/create/');
	Assign('privacy_link',BASEURL.'/pages/privacy/');
	Assign('termsofuse_link',BASEURL.'/pages/termsofuse/');
	Assign('help_link',BASEURL.'/pages/help/');
	Assign('join_group_link',BASEURL.'/join_group.php?url=');
	Assign('groups_link',BASEURL.'/community');
	Assign('delete_group_link',BASEURL.'/delete_group.php?url=');
	Assign('External_Upload_link',BASEURL.'/external_upload.php');
	Assign('rss_link',BASEURL.'/rss.php?show=');
    Assign('admin_link',BASEURL.'/admin_area/');
	
	}else{
	@Assign('seo','no');
	
 	Assign('logout_link',BASEURL.'/logout.php');
	Assign('leave_group_link',BASEURL.'/leave_group.php?url=');
	Assign('sent_link',BASEURL.'/sent.php');
 	Assign('signup_link',BASEURL.'/signup.php');
	Assign('subscriptions_link',BASEURL.'/manage_subscriptions.php');
	Assign('search_result',BASEURL.'/search_result.php');
 	Assign('upload_link',BASEURL.'/upload.php');
	Assign('myaccount_link',BASEURL.'/myaccount.php');
	Assign('manage_videos',BASEURL.'/manage_videos.php');
	Assign('manage_subscriptions',BASEURL.'/manage_subscriptions.php');
	Assign('manage_favourites_link',BASEURL.'/manage_favourites.php');
	Assign('manage_contacts_link',BASEURL.'/manage_contacts.php');
	Assign('manage_group_videos_link',BASEURL.'/manage_group_videos.php?url=');
	Assign('manage_group_members_link',BASEURL.'/manage_group_members.php?url=');
	Assign('manage_groups_link',BASEURL.'/manage_groups.php');
	Assign('videos_link',BASEURL.'/videos.php');
	Assign('user_account_link',BASEURL.'/user_account.php');
	Assign('user_contacts_link',BASEURL.'/user_contacts.php?user=');
	Assign('user_videos_link',BASEURL.'/user_videos.php?user=');
	Assign('user_fav_videos_link',BASEURL.'/user_fav_videos.php?user=');
	Assign('channels_link',BASEURL.'/channels.php');
	Assign('compose_msg_link',BASEURL.'/compose.php');
	Assign('view_channel_link',BASEURL.'/view_channel.php?user=');
	Assign('view_topic_link',BASEURL.'/view_topic.php?tid=');
	Assign('view_group_link',BASEURL.'/view_group.php?url=');
	Assign('view_group_videos_link',BASEURL.'/view_group_videos.php?url=');
	Assign('view_group_members_link',BASEURL.'/view_group_members.php?url=');
	Assign('inbox_link',BASEURL.'/inbox.php');
	Assign('invite_group_members_link',BASEURL.'/invite_group.php?url=');
	Assign('edit_video_link',BASEURL.'/edit_video.php');
    Assign('edit_video_watch',BASEURL.'/edit_video.php');
	Assign('edit_group_link',BASEURL.'/edit_group.php?url=');
	Assign('about_us_link',BASEURL.'/aboutus.php');
	Assign('add_group_video_link',BASEURL.'/add_group_videos.php?url=');
	Assign('contact_us_link',BASEURL.'/contactus.php');
	Assign('create_group_link',BASEURL.'/create_group.php');
	Assign('privacy_link',BASEURL.'/privacy.php');
	Assign('termsofuse_link',BASEURL.'/termsofuse.php');
	Assign('help_link',BASEURL.'/help.php');
	Assign('join_group_link',BASEURL.'/join_group.php?url=');
	Assign('groups_link',BASEURL.'/groups.php');
	Assign('delete_group_link',BASEURL.'/delete_group.php?url=');
	Assign('External_Upload_link',BASEURL.'/External_Upload');
	Assign('rss_link',BASEURL.'/rss.php?show=');
    Assign('admin_link',BASEURL.'/admin_area/');

	}
?>