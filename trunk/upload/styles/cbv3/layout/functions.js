
// CLIPBUCKET MAIN FUNCTIONS ----------------------


function displayConfirm( id, confirmMessage, onConfirm, heading ) {
	if ( !document.getElementById(id) ) {
		con = "<div id='"+id+"' class='modal hide confirm-modal'>";
		
		if ( heading ) {
			con += "<div id='modal_header_"+id+"' class='modal-header'>";
			con += "<h3>"+heading+"</h3>";
			con += "</div>";
		}
		
		con += "<div id='modal_body_"+id+"' class='modal-body'> ";
		con += "<span>"+confirmMessage+"</span>";
		con += "</div>";
		
		con += "<div id='modal_footer_"+id+"' class='modal-footer'>";
		con += "<div class='pull-left'><div id='function_loader_"+id+"' class='loading_pointer'><img src='"+imageurl+"/loaders/1.gif' /></div></div>";
		con += "<div class='pull-right'>";
		con += "<button data-dismiss='modal' class='btn '>Cancel</button>";
		con += "<button id='confirm_"+id+"' class='btn btn-primary '>Confirm</button>"
		con += "</div>";
		con += "</div>";
		
		con += "</div>";	
		
		$('body').append(con);	
	}
	
	$('#confirm_'+id).on('click',function( event ){
		event.preventDefault();

		if ( jQuery.isFunction(onConfirm) ) {
			onConfirm.call( this, id );
		} else {
			$('#'+id).modal('hide');
			if ( onConfirm.search('http://') == -1 ) {
				var error = ['Please check the function name provided,"'+onConfirm+'". Either it does not exist or name is not right. '];
				displayError( error );	
			} else {
				window.location = onConfirm;	
			}				
		}
	})
}

function delete_photo_ajax( id ) {
	var modal = $('#'+id), button = $('#'+this.id), buttonText = button.text();
	if ( button.hasClass('disabled') ) {
		return;	
	}
	
	disable_form_inputs( button.parents('.modal-footer').attr('id') );
	button.text('Deleting ...');
	var photo_id = id.split('_')[ id.split('_').length - 1 ];
	
	amplify.request('photos',{
		mode : 'delete_photo',
		id : photo_id	
	}, function( data ) {
		if( data.error ) {
			enable_form_inputs( button.parents('.modal-footer').attr('id') );
			button.text(buttonText);
			modal.modal('hide');
			displayError( data.error );
		}
		
		if ( data.success ) {
			if ( data.redirect_to ) {
				window.location = data.redirect_to;
			} else if ( $.cookie('pagedir') ) {
				window.location = $.cookie('pagedir');	
			} else {
				window.location = baseurl;	
			}
			modal.modal('hide');
		}
	});
}

/**
 *Function used to display an error message popup box
 */
function displayError(err)
{
    $('#error .modal-body p').html('');
     
    $.each(err,function(index,data){
        $('#error .modal-body p').append(data+'<br />');
    })
    $('#error').modal('show');
}

/**
 *Function used to display an error message popup box
 */
function displayMsg(msg)
{
    $('#msg .modal-body p').html('');
     
    $.each(msg,function(index,data){
        $('#msg .modal-body p').append(data+'<br />');
    })
    $('#msg').modal('show');
}


/**
 * Relative input highlight and add error
 */
function focusObj(err,type)
{
    $.each(err,function(rel,msg){
        $('#'+rel).parent().parent().addClass(type);
        $('#'+rel).parent().find('.help-inline').text(msg);
        
    })
}


/**
 * function used to hide or show loading pointer
 * 
 */
function loading_pointer(ID,toDo)
{
    var pointer = $('#'+ID+'-loader');
    
    if(toDo=='hide')
    { 
        pointer.hide();
    }
    else{
        pointer.show();
    }
}
function loading(ID,ToDo)
{ 
    return loading_pointer(ID,ToDo)
}


/**
 * Updates counts of an object such as
 * adding playlist will increase playlist_count
 */
function update_counter(obj,inc)
{
    var val = $(obj).text();
    val = parseInt(val);
    if(inc==1)
    {
        val += 1; 
        $(obj).text(val);
    }else
    {
        val -= 1; 
        $(obj).text(val);
    }
}


function toggleBox(bttn,box)
{
    if($(bttn).hasClass('active'))
    {
        $(box).hide();
        $(bttn).removeClass('active')
    }else
    {
        $(box).show();
        $(bttn).addClass('active')
    }
}

// CLIPBUCKET MAIN FUNCTIONS ----------------------



/**
 * Toggle watch video less and more
 */
function toggleLessMore(div,type)
{
    var LessHeight = 60; //in pixels
    
    if(type=='less')
    {
        $('#'+div).css('height',LessHeight);
        $('#'+div+'-less').hide();
        $('#'+div+'-more').show();
    }else
    {
        $('#'+div).css('height','auto');
        $('#'+div+'-less').show();
        $('#'+div+'-more').hide();
    }
}



/**
 * Rate object and get result..
 * 
 * cbv3Rate
 * 
 * @param id INT
 * @param rating INT
 * @param type STRING
 */
function cbv3rate(id,rating,type)
{
    loading('rating');
    amplify.request("main",{"mode":"rating",type:type,
        id:id,rating:rating}//params,
        ,function(data){ 
            
            $('#video-rating-container')
            .html(data.template);
            loading('rating','hide');
        }
    );
}


/**
 * Create play list
 */
function create_playlist(type)
{
    $('#create_playlist_bttn').button('loading');
    var formData = $('#create-playlist-modal form').serialize();
    formData += "&mode=create_playlist&type="+type;
    
    $('#create_playlist_bttn').button('loading');
    amplify.request('main',formData,function(data){
        
        if(data.err)
        {
            if(data.rel.err)
            {
                focusObj(data.rel.err,'error');
            }
            
            if(data.rel.err.length<1)
            {
                $.each(data.err,function(index,err)
                {
                    $('#create-playlist-error')
                        .append('<div>'+err+'</div>')
                        .show();
                });
            }
            
            $('#create_playlist_bttn').button('reset');
        }else
        {
            
            $('#create-playlist-modal').modal('hide');
            
            $('#playlist-list').append(data.template);
            $('#'+data.pid+'-playlist').hide().fadeIn('slow');
            
            update_counter('#playlists_count',1);
            
            updatePlaylistPage();
            $('#create_playlist_bttn').button('reset'); 
        }
    });
}



/**
 * toggle Playlist options, 
 * 
 * will hide controls if playlist counts is 0
 * otherwise show them
 */
function updatePlaylistPage()
{
    if($('.playlist-list').length>0)
    {
        $('.playlist-list-controls').show();
        $('.no-playlist').hide();
    }else{
        $('.playlist-list-controls').hide();
        $('.no-playlist').show();
    }
}

/**
 * Delete playlist
 */
function delete_playlist(pid)
{
    amplify.request('main',
    {mode:'delete_playlist',pid:pid},function(data){
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            displayMsg(data.msg);
            $('#'+pid+'-playlist').remove();
            updatePlaylistPage();
            update_counter('#playlists_count',-1);
        }
    })
}

/**
 * Add item into the playlist
 */
function add_to_playlist(pid,oid,type)
{
    var mainDiv = '#add-videos-to-playlist';

    loading('playlist-'+pid);
    
    amplify.request('main',{
        'mode'  : 'add_playlist_item',
        'pid'   : pid,
        'oid'   : oid,
        'type'  : type
        },
        function(data){
            $(mainDiv+' > .alert').hide();
            
            loading('playlist-'+pid,'hide');
            if(data.err)
            {
                $(mainDiv+' .alert-danger')
                .text(data.err[0])
                .show();
            }else
            {
                $('#no_playlist').remove();
                update_counter('#playlist-counter-'+pid,'1');
                $('#playlist-ul-'+pid+' .date_updated').
                text(data.updated);
            
                $(mainDiv+' .alert-success')
                .text(data.msg[0])
                .show();
            }
        }
    )
}

/**
 * Create new playlist..
 */
function create_playlist_quick(type,oid)
{
    var name = $('#playlist_name').val();
    var privacy = $('#playlist_privacy option:selected').val();
    var mainDiv = '#add-videos-to-playlist';
    
    $('#create_playlist_bttn').button('loading');
    
    amplify.request('main',{
        'mode' : 'create_playlist',
        'name' : name,
        'privacy'   : privacy,
        'type'  : type,
        'oid'   : oid
        },
        function(data){
            
            $('#create_playlist_bttn').button('reset');
            $(mainDiv+' > .alert').hide();
            
            if(data.err)
            {
                $(mainDiv+' .alert-danger')
                .text(data.err[0])
                .show();
            }else{

                $('#playlist_name').val('');
                $('#new-playlist-pointer')
                .after(data.ul_template);
                
                $('.add-playlist-box').show();
                
                $(mainDiv+' .alert-success')
                .text(data.msg[0])
                .show();
            }
        }
    )
    
}

function disable_form_inputs( id , enable ) {
	if ( !enable ) {
		$('#'+id+' :input').attr('disabled', 'disabled');	
	}
	
	if ( enable ) {
		$('#'+id+' :input').removeAttr('disabled');
	}
}

function enable_form_inputs( id ) {
	disable_form_inputs( id, true );	
}

function send_private_message(e) {
	var obj = $( e.target ), form = e.target.form, fields = $('#'+form.id ).serializeArray(), forward = {};
	$.each( fields, function(index, val) {
		forward[ val.name ] = val.value
	});
	
	forward['mode'] = 'send_photo_pm';
	disable_form_inputs( form.id )
	$('#private_message_response').fadeOut('fast').removeClass('alert-error alert-success');
	obj.button('loading');
	
	amplify.request('photos',forward, function( data ) {
		enable_form_inputs( form.id )
		if ( data.success ) {
			$('#private_message_response').addClass('alert-success').html( data.success ).show();
			form.reset();
			var autoClose = setTimeout(function() {
				obj.prev().trigger('click');
			},2000)
		}
		
		if ( data.error ) {
			$('#private_message_response').addClass('alert-error').html( data.error ).show();	
		}
		obj.button('reset');
	})
}