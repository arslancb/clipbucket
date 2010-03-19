//include('popup_image.js');
var page = baseurl+'/actions/admin.php';
function ShowHint(text){
	return fixedtooltip('<table bgcolor="#f1f1f1" width="400px" class="des_table"><tr><td>'+text+'</td></tr></table>', this, event, '0px');
}


function add_note(noteId)
{
	var note = $(noteId).val();
	if(!note)
		alert("Please enter something");
	else
	{
		$.post(page, 
		{ 	
			mode : 'add_note',
			note : note,
		},
		function(data)
		{
			$("#no_note").css("display","none");
			var the_note = '<div class="item" id="note-'+data.id+'"><img src="'+imageurl+'/cross.png" class="delete_note" onclick="delete_note(\''+data.id+'\');" />'+data.note+'</div>';
			$("#the_notes").append(the_note);
			$(noteId).val('');
		},'json');
	}
}
function delete_note(id)
{
	$.post(page, 
	{ 	
		mode : 'delete_note',
		id : id,
	},
	function(data)
	{
		$("#note-"+id).slideUp();
	},'text');
}


function toggle_search(id)
{
	$('#'+id).slideToggle();
	if($.cookie("show_"+id+"_search")=="show")
		$.cookie("show_"+id+"_search","hide")	
	else
		$.cookie("show_"+id+"_search","show")
}


function toggle_menu()
{
	if($.cookie("admin_menu")=="show" || !$.cookie("admin_menu"))
	{
		$('#left_column').animate({ 
        width: "15px",
    	}, 200,function(){ $('#left_column').removeClass('left_menu').addClass('left_menu_0')}  );
		
		$('#contentcolumn').animate({ 
        marginLeft: "25px",
    	}, 200 ).removeClass('contentcolumn').addClass('contentcolumn0');
		
		$.cookie("admin_menu","hide");	
	}else
	{
		$('#left_column').animate({ 
        width: "232px",
    	}, 200 ).removeClass('left_menu_0').addClass('left_menu');
		
		$('#contentcolumn').animate({ 
        marginLeft: "248px",
    	}, 200 ).removeClass('contentcolumn0').addClass('contentcolumn');		
		
		$.cookie("admin_menu","show");
	}
}

function admin_spam_comment(cid)
	{

		$.post(page, 
		{ 	
			mode : 'spam_comment',
			cid : cid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				if(data.msg)
				{
					$("#comment_"+cid).css({'backgroundColor' : '#ffd7d7'});
					$("#spam_comment_"+cid).fadeOut(350, function() {
							$("#remove_spam_comment_"+cid).fadeIn(350);
					});

				}
				if(data.err)
				{
					alert(data.err)
				}
			}
		},'json');
	}
	
function admin_remove_spam(cid)
	{

		$.post(page, 
		{ 	
			mode : 'remove_spam',
			cid : cid
		},
		function(data)
		{
			if(!data)
				alert("No data");
			else
			{
				if(data.msg)
				{
					$("#comment_"+cid).css({'backgroundColor' : '#f3f3f3'});
					$("#remove_spam_comment_"+cid).fadeOut(350, function() {
							$("#spam_comment_"+cid).fadeIn(350);
					});
				}
				if(data.err)
				{
					alert(data.err)
				}
			}
		},'json');
	}	