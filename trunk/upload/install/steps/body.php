<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ClipBucket v2 Installer</title>
<link href="./theme/blue.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/upgrader.js"></script>
</head>

<body>

<div class="container">
    <div class="header">
    
    </div>
    
    <!-- INCLUDING STEP <?=$step?> -->
    <?php include("step_".$step.".php") ?>
</div>

</body>
</html>