<?php

    require '../../includes/config.inc.php';
    
    if (isset($_POST)){
	// Debug
// 	echo '<pre>';
// 	print_r($_POST);
// 	echo '</pre>';
	
	if ($_POST['page'] != ''){
		require($_POST['page']);
	}
    }

?>