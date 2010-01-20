<?php



	/**
	 * Loading Upload Form
	 */
	function load_upload_form($params)
	{
		global $file_name;
		echo '<p>Select video files from your computer to begin uploading.</p>';
		echo '<div class="upload_form_div clearfix">';
		echo '<input name="submit_upload" type="hidden" value="Upload Data Now!">';
		echo '<div id="progress_status" class="divStatus">';
		echo '</div>';
		echo '<div id="divStatus" class="divStatus moveL">Click \'Upload\' to select files</div>';
		echo '<div class="moveR">';
		echo '<span id="spanButtonPlaceHolder"></span>';
		echo '<input id="btnCancel" type="button" value="Cancel" 
		onClick="swfu.cancelQueue();" disabled="disabled" style="margin:0px 0px 1px 3px" />';
		echo '</div>';
		echo '</div>';
		echo '<div class="fieldset flash" id="fsUploadProgress"></div>';
	}

	
	function load_remote_upload_form($params)
	{
		global $file_name;
		if($params['class'])
			$class = ' '.$params['class'];
		echo '<div class="upload_form_div clearfix'.$class.'">';
		echo '<label for="check_url">Please enter remote file url</label><br>';
		echo '<input type="textfield" id="remote_file_url" name="remote_file_url"  class="upload_input"/>';
		echo '<input name="submit_upload" type="hidden" value="Upload Data Now!">';
		echo '<div id="remote_upload_result_cont"></div>';
		echo '<div align="right"><input type="button" name="check_url" id="check_url" value="Upload" onClick="check_remote_url()" class="'.$params['button_class'].'"></div>';
		echo '</div>';
	}
	
?>