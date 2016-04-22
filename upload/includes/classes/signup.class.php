<?php
/**
 Name : Signup Class
 ****************************************************
 Don Not Edit These Classes , It May cause your script 
 not to run properly
 This source file is subject to the ClipBucket End-User 
 License Agreement, available online at:
 http://www.opensource.org/licenses/attribution.php
 By using this software, you acknowledge having read this 
 Agreement and agree to be bound thereby.
 ****************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 *****************************************************
*/
class signup {
	
	
	
	
	//This Function Is Used To Check Regiseration is allowed or not
	function Registration()
    {
        if(ALLOW_REG == 1)
        {
            return true;
        }
        return false;
	}
	
}
?>