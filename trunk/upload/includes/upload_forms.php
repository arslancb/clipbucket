<?php



	/**
	 * Loading Upload Form
	 */
	function load_upload_form($params)
	{
		global $file_name;
    	echo '<input id="file_uploads" name="file_uploads" type="file" />';
	}

	
	function load_remote_upload_form($params)
	{
		global $file_name;
		if($params['class'])
			$class = ' '.$params['class'];
		echo '<div class="upload_form_div clearfix'.$class.'">';
		echo '<label for="check_url" class="label">Please enter remote file url</label>';
		echo '<input type="textfield" id="remote_file_url" name="remote_file_url"  class="upload_input"/>';
		echo '<input name="submit_upload" type="hidden" value="Upload Data Now!">';
		echo '<div id="remote_upload_result_cont"></div>';
		echo '
				<div id="loading" align="center" style="width:400px"></div>
				<div class="remote_main_bar" id="main_bar">
				<div id="prog_bar" class="remote_prog_bar"></div>
				</div>
				<div id="dspeed" align="center" style="width:400px"></div>
				<div id="eta" align="center" style="width:400px"></div>
				<div id="status" align="center" style="width:400px"></div>
				<div id="time_took" align="center" style="width:400px"></div>
				<div id="results" align="center" style="width:400px"></div>
				';
		echo '<div align="right"><input type="button" name="check_url" id="check_url" value="Upload" onClick="check_remote_url()" class="'.$params['button_class'].'"></div>';
		echo '</div>';
	}
	
?>