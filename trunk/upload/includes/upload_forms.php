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
		echo
		'<div class="upload_form_div clearfix'.$class.'" id="remoteUploadFormDiv">
        <label for="remote_file_url" class="label">'.lang('please_enter_remote_file_url').'</label>
        <input name="remote_file_url" type="textfield"  class="remoteUrlInput" 
        id="remote_file_url" value="e.g http://clipbucket.com/sample.flv" 
        onclick="if($(this).val()==\'e.g http://clipbucket.com/sample.flv\') $(this).val(\'\')"/>
        <input name="submit_upload" type="hidden" value="Upload Data Now!">
        
        <div id="remote_upload_result_cont"></div>
        <div class="remote_main_bar" id="main_bar">
            <div id="prog_bar" class="remote_prog_bar"></div>
        </div>
        <div align="center" id="loading" style="margin:5px 0px"></div>
        <div>
            '.lang('remoteDownloadStatusDiv').'
        </div>                
        <div align="right">
			<input type="button" name="ytUploadBttn" id="ytUploadBttn" 
			value="Grab from youtube" onClick="youtube_upload()" class="cbSubmitUpload">
            <input type="button" name="remoteUploadBttn" id="remoteUploadBttn" value="Upload" onClick="check_remote_url()" class="cbSubmitUpload">
            <input type="button" name="remoteUploadBttnStop" id="remoteUploadBttnStop" value="Cancel"   class="cbSubmitUpload" style="display:none"/>
        </div>
   	 	</div>
   	 	<div id="remoteForm"></div> ';
	}
	
?>