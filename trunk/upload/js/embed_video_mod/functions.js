// JavaScript Document
if(typeof String.prototype.escape === 'undefined'){
	String.prototype.escape = function() {
	    var tagsToReplace = {
	        '&': '&amp;',
	        '<': '&lt;',
	        '>': '&gt;'
	    };
	    return this.replace(/[&<>]/g, function(tag) {
	        return tagsToReplace[tag] || tag;
	    });
	};
}

var embed_check = baseurl+"/actions/embed_form_verifier.php";
function check_embed_code(objId){
	var  theForm = '#embedUploadForm'+objId;
	if($(theForm+" #embed_code").val() == "")
		alert("Embed code was empty");
	else if($(theForm+" #duration").val() == "")
		alert("Please enter video duration");
	else
	{
		$.post(embed_check, 
		{ 	
			embed_code : encode64($(theForm+" #embed_code").val()),
			duration : $(theForm+" #duration").val(),
			file_name : file_name,
			thumb_file : $(theForm+" #thumb_file").val(),
			verify_embed : 'yes'
		},				
		
		
		function (data) {
			if(data.err)
			{
				alert(data.err);
			}else{
				//var embed_code = $(theForm).find("#embed_code").val();
				//$(theForm).find("#embed_code").val(embed_code.escape());
				//console.log($(theForm).find("#embed_code").val());
				$(theForm).submit();
			}
		}, "json");
	}
}