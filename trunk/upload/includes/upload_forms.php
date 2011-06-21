<?php



	/**
	 * Loading Upload Form
	 */
	function load_upload_form($params)
	{
		global $file_name;
		assign('params',$params);
    	Template(STYLES_DIR.'/global/upload_form.html',false);
	}

	
	function load_remote_upload_form($params=NULL)
	{
		global $file_name;
		if($params['class'])
			$class = ' '.$params['class'];
		echo '<div style="font-size:12px; padding:5px">
			'.lang('upload_remote_video_msg').'
			</div>';
		echo
		'<div class="upload_form_div clearfix'.$class.'" id="remoteUploadFormDiv">
        <label for="remote_file_url" class="label">'.lang('please_enter_remote_file_url').'</label>
        <input name="remote_file_url" type="textfield"  class="remoteUrlInput" 
        id="remote_file_url" value="'.lang('remote_upload_example').'"
        onclick="if($(this).val()==\''.lang('remote_upload_example').'\') $(this).val(\'\')"/>
        <input name="submit_upload" type="hidden" value="'.lang('upload_data_now').'">
        
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
			value="'.lang('grab_from_youtube').'" onClick="youtube_upload()" class="cbSubmitUpload">
            <input type="button" name="remoteUploadBttn" id="remoteUploadBttn" value="'.lang('upload').'" 
			onClick="'.get_remote_url_function().'" class="cbSubmitUpload">
            <input type="button" name="remoteUploadBttnStop" id="remoteUploadBttnStop" value="'.lang('cancel').'"   
			class="cbSubmitUpload" style="display:none"/>
        </div>
   	 	</div>
   	 	<div id="remoteForm"></div> ';
	}
	
?>