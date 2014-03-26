<?php
$user_data = array();
$user_data['email'] = 'mshoaibu@yahoo.com';
$user_data['pwd']   = '123';


// cURL code
$ch = curl_init('http://localhost/rest2/api.php?mode=deleteUser&id=2');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
// curl_setopt($ch, CURLOPT_POSTFIELDS, $user_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);
echo $response;
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo $http_code; 
?>