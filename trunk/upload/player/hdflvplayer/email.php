<?php
/***************************************************\
 * PHP 4.1.0+ version of email script. For more
 * information on the mail() function for PHP, see
 * http://www.php.net/manual/en/function.mail.php
\***************************************************/


// First, set up some variables to serve you in
// getting an email.  This includes the email this is
// sent to (yours) and what the subject of this email
// should be.  It's a good idea to choose your own
// subject instead of allowing the user to.  This will
// help prevent spam filters from snatching this email
// out from under your nose when something unusual is put.

$to = $_POST["to"];
$from = $_POST["from"];
$url = $_POST["url"];







$subject = "You have received a video!";

// variables are sent to this PHP page through
// the POST method.  $_POST is a global associative array
// of variables passed through this method.  From that, we
// can get the values sent to this page from Flash and
// assign them to appropriate variables which can be used
// in the PHP mail() function.


// header information not including sendTo and Subject
// these all go in one variable.  First, include From:
$headers = "From: "."<" . $_POST["from"] .">\r\n";
// next include a replyto
$headers .= "Reply-To: " . $_POST["from"] . "\r\n";
// often email servers won't allow emails to be sent to
// domains other than their own.  The return path here will
// often lift that restriction so, for instance, you could send
// email to a hotmail account. (hosting provider settings may vary)
// technically bounced email is supposed to go to the return-path email
$headers .= "Return-path: " . $_POST["from"];

// now we can add the content of the message to a body variable





$message = $_POST["note"] . "\n\n";
$message .= "Video URL: " . $url;


// once the variables have been defined, they can be included
// in the mail function call which will send you an email
mail($to, $subject, $message, $headers);

?>