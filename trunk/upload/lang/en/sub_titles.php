<?php
/**
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://clip-bucket.com/cbla
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
 
function subtitle($subtitle,$extra = NULL){

		$subtitle_array = array(
			'upload' 					=> SLOGAN.' - Upload Video',
			'signup'					=> SLOGAN.' - Sign Up',
			'logout_succes'				=> SLOGAN.' - Logged Out Successfully',
			'login_succes'				=> SLOGAN.' - Logged In Successfully',
			'signup_succes'				=> SLOGAN.' - Thank You for Joining',
			'videouploadsuccess'		=> SLOGAN.' - Video Uploaded Successfully',
			'videos'					=> SLOGAN.' - Videos',
			'channels'					=> SLOGAN.' - User Channels',
			'contacts'					=> SLOGAN.' - Manage Contacts',
			'manage_video'				=> SLOGAN.' - Manage Videos',
			'manage_favourites'			=> SLOGAN.' - Manage Favorites',
			'aboutus'					=> SLOGAN.' - About Us',
			'privacy'					=> SLOGAN.' - Privacy Policy',
			'termofuse'					=> SLOGAN.' - Terms of User',
			'signup_success'			=> SLOGAN.' - You Have Successfully Joined',
			'contactus'					=> SLOGAN.' - Contact Us',
			'create_group'				=> SLOGAN.' - Create Group',
			'groups'					=> SLOGAN.' - Community',
			'404'						=> SLOGAN.' - Page Not Found',
            '403'                       => SLOGAN.' - Access Denied',
            'index'                     => SLOGAN,
            'my_account'                => SLOGAN.' - Account Management',
            'style_change'              => SLOGAN.' - Style Changed',
            'lang_change'               => SLOGAN.' - Language Changed',
				 );
        if($extra != NULL)
        {
		Assign('subtitle',$subtitle_array[$subtitle]. $extra. SUBTITLE);
        }
        else
        {
        Assign('subtitle',$subtitle_array[$subtitle]. SUBTITLE);
        }
		}
?>