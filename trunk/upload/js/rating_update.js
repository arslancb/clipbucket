
/**
 * Function used to load editor's pic video
 */
function rate(id,rating,type)
{
	var page = baseurl+'/ajax.php';
	$.post(page, 
	{ 	
		mode : 'rating',
		id:id,
		rating:rating,
		type:type
	},
	function(data)
	{
		if(!data)
			alert("No data");
		else
			$("#rating_container").html(data);
	},'text');
}