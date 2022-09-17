<?php 


$db->Execute("INSERT INTO ".tbl('config')." (`configid`, `name`, `value`) VALUES
(NULL, 'elastic_server_ip', 'localhost');");

?>