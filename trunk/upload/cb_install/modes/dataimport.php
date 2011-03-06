<h2>Creating Database Tables and Importing data</h2>


<div id="resultsDiv" style="margin-top:20px">
	<img src="images/loading.gif" id="loading" />
    <?php if(!$upgrade): ?>
    <span id="current">creating database structure...</span>
    <?php
    else:
    ?>
    <span id="current">upgrading clipbucket...</span>
    <?php
    endif;
    ?>
</div>

<form name="installation" method="post" id="installation">
	<input type="hidden" name="dbhost" value="<?=@$_POST['dbhost']?>" 	/>
    <input type="hidden" name="dbpass" value="<?=@$_POST['dbpass']?>" 	/>
    <input type="hidden" name="dbname" value="<?=@$_POST['dbname']?>" 	/>
    <input type="hidden" name="dbuser" value="<?=@$_POST['dbuser']?>" 	/>
    <input type="hidden" name="dbprefix" value="<?=$_POST['dbprefix']?>" />
    <?php if($upgrade): ?>
    <input type="hidden" name="mode" value="finish_upgrade" />
    <?php
    else:
    ?>
    <input type="hidden" name="mode" value="adminsettings" />
    <?php
    endif;
    ?>
</form>

<script>
	$(document).ready()
	{
		<?php if($upgrade): ?>
		dodatabase('upgrade');
		<?php
		else:
		?>
		dodatabase('structure');
		<?php
		endif;
		?>
	}
</script>