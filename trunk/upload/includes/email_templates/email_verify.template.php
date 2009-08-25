
					<?php 
					$body ="Hello $username,
Thank For Joining Us, Your Account Details are

Username     : $username
Password     : $password
Email        : $email
Date Joined  : $cur_date

Your Account Is Inactive Please Activate it by using following link 

<a href=$baseurl/activation.php?username=$username&avcode=$avcode>Click Here</a>

$baseurl/activation.php?username=$username&avcode=$avcode

====================
Regards
$title" 
					?>
					