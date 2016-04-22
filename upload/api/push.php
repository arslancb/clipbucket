<?php


$request = $_REQUEST;
$mode = $request['mode'];

switch ($mode) {
    case "registerToken": {
        include('../includes/config.inc.php');
        include('global.php');
         global $db;
         $token = $request['token'];
         $response=$db->insert(tbl('token'),array('token'),array($token));
         if ($response)
                echo json_encode(array('success'=>'yes','msg'=>'token saved successfully'));
            else
                echo json_encode(array('err'=> lang(error('single'))));
    }
        break;
    default:{
        
         
    }
        
}

function send_video_notification($videoId){
            global $myquery ,$db;
            #checking if we have vid , so fetch the details
		if(!empty($videoId))
                    $vdetails = $myquery->get_video_details($videoId);
                
            $title=$vdetails['title'];
            // Put your private key's passphrase here:
            $passphrase = 'janjuajanjua';

            ////////////////////////////////////////////////////////////////////////////////

            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', 'E:\wamp\www\clipbucket_bug1\api\apns-dev.pem');
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

            // Open a connection to the APNS server
            $fp = stream_socket_client(
                    'ssl://gateway.sandbox.push.apple.com:2195', $err,
                    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

            if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

            echo 'Connected to APNS' . PHP_EOL;

            
            
            $token_result = $db->select(tbl('token'),'token');
            if($token_result){
                foreach($token_result as $token_value)
                {
                    // Create the pahanyload body
                    
                    $payload['aps'] = array('alert' => "'".$title."' has been Featured.", 'sound' => 'default');
                    $payload['details'] = array('type' => 'featured','videoId' => $videoId, 'title' => $title);
                    $output = json_encode($payload);



                    // Build the binary notification
                    $msg = chr(0) . pack('n', 32) . pack('H*', $token_value['token']) . pack('n', strlen($output)) . $output;

                    // Send it to the server
                    $result = fwrite($fp, $msg, strlen($msg));

                }
                 if (!$result)
                    echo 'Messages not delivered' . PHP_EOL;
            else
                    echo 'Messages successfully delivered' . PHP_EOL;
            
            }
            else
                 return json_encode(array('err'=> lang(error('single'))));
            
           // Close the connection to the server
            fclose($fp);

}

