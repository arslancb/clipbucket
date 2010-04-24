// JavaScript Document
var embed_check = baseurl+"/actions/embed_form_verifier.php";
function check_embed_code()
{
	if($("#embed_code").val() == "")
		alert("Embed code was empty");
	else if($("#duration").val() == "")
		alert("Please enter video duration");
	else
	{
		$.post(embed_check, 
		{ 	
			embed_code : $("#embed_code").val(),
			duration : $("#duration").val(),
			file_name : file_name,
			thumb_file : $("#thumb_file").val()
		},				
		
		function (data) {
			if(data.err)
			{
				alert(data.err);
			}else{
				//alert("est");
				$("#"+upload_form_name).submit();
			}
		}, "json");
	}
}