
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

function save_exif_settings() {
    var obj = $('#save_exif'), form = $('#exif_settings'), fields = form.serialize();
    var pid = $('#pid').val();

    $('#exif-error').hide().empty();
    $('#exif-success').hide().empty();
	
    disable_form_inputs( form.attr('id') );
    disable_form_inputs('modal_footer');
    obj.button('loading');
    loading_pointer('photo-'+pid);
	
    amplify.request('photos',fields+'&mode=inline_exif_setting', function( data ) {
        enable_form_inputs( form.attr('id') );
        enable_form_inputs('modal_footer');
		
        obj.button('reset');
        loading_pointer('photo-'+pid,'hide');
				
        if ( data.error ) {
            $('#exif-error').text( data.error ).show();
        }
		
        if ( data.success ) {
            $('#exif-success').text( data.success ).show();
            setTimeout(function(){
                $('#exif-settings').modal('hide');
            },1500);	
        }
    });	
}

function show_colors(e) {
    e.preventDefault();	
    var link = $( e.target ), text = link.text(), pid = link.attr('data-photo-id'), icon = link.find('i');
	
    if ( link.hasClass('is-loading-colors') || link.hasClass('no-colors-found') ) {
        return;	
    }
	
    if ( document.getElementById(pid+'-colors-modal') ) {
        $('#'+pid+'-colors-modal').modal('show');
        return;	
    }
	
    link.addClass('is-loading-colors').text(' Loading ... ').prepend( icon );
	
    amplify.request('photos',{
        mode : 'get_colors',
        id : pid	
    }, function( data ) {
		
        link.removeClass('is-loading-colors').text( text ).prepend( icon );
		
        if ( data.error ) {
            if ( typeof data.error == 'string' ) {
                error = [data.error];
                data.error = error	
            }
			
            displayError( data.error );
            link.addClass('no-colors-found').text(' No colors found').prepend( icon );
        }
		
        if ( data.success ) {
            $('body').append( data.template );
            $('#'+pid+'-colors-modal').modal('show');
            $('#'+pid+'-colors-modal .cb-tooltip').tooltip();	
        }
    });
	
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
        $(bttn).removeClass('active').removeClass('pressed');
    }else
    {
        $(box).show();
        $(bttn).addClass('active').addClass('pressed');
    }
}


/**
 * Get Comments via ajax
 */

var comments_voting = 'no';
function get_comments(type,type_id,last_update,pageNum,total,object_type,admin)
{

    amplify.request('main',
    {
        mode:'get_comments',
        page:pageNum,
        type:type,
        type_id:type_id,
        object_type : object_type,
        last_update : last_update,
        total_comments : total,
        comments_voting : comments_voting,
        admin : admin
    },function(data){
        

        $('#comments').hide();
        $('#comments').html(data.output);
        $('#comments').fadeIn('slow');
    })    
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
    amplify.request("main",{
        "mode":"rating",
        type:type,
        id:id,
        rating:rating
    }//params,
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
    {
        mode:'delete_playlist',
        pid:pid
    },function(data){
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
    console.log('playlist-'+oid+'-'+pid);
    loading('playlist-'+oid+'-'+pid);
    
    amplify.request('main',{
        'mode'  : 'add_playlist_item',
        'pid'   : pid,
        'oid'   : oid,
        'type'  : type
    },
    function(data){
        $(mainDiv+' > .alert').hide();
            
        loading('playlist-'+oid+'-'+pid,'hide');
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


/**
     * Save playlist text
     * @param item_id INT
     * @param text STRING
     */
function save_playlist_item_note(item_id,text)
{
    amplify.request('main',{
        'mode' : 'save_playlist_item_note',
        'item_id' : item_id,
        'text' : text
    },function(data){
        if(data.err){
            displayError(data.err);
        }else
        {
            $('.save-plst-text[data-id='+item_id+']')
            .button('reset');
        }
    })
}
    
    
/**
     * function used to remove item from playlist
     * 
     * @param itemid 
     */
function remove_playlist_item(itemid)
{
    loading('confirm');
        
    amplify.request('main',{
        mode : 'remove_playlist_item',
        item_id : itemid
    },function(data){
            
        //Incase there is any..
        $('#confirm').modal('hide');
        clear_confirm();

        loading('confirm','hide');
                    
        if(data.err)
            displayError(data.err);
        else{
            $('#'+itemid+'-pitem')
            .hide('fast')
            .remove();
                    
            update_manage_playlist_order();
            update_counter('#playlist_items_count',-1);
        }
    }
    );
}
    
/**
     * Update manage playlist order
     */
function update_manage_playlist_order(){     
    var $length = $('#playlist-manage > li').size();
        
    if($length>0){
        for(i=1;i<=$length;i++)
        {
            var my = i+1;
            $('#playlist-manage li:nth-child('+my+') ul .iteration')
            .text(i+'.');
        }
    }else{
        $('#no-playlist-items-div').show();
    }
}
    
/**
     * Function used to make confirmation about any action
     */
function cb_confirm(title,text,callback)
{
    $('#confirm .modal-header h3').text(title);
    $('#confirm .modal-body').html(text);
    $('#confirm-yes').bind('click',callback);    
    $('#confirm').modal('show');
}
    
    
/**
     * Clears confirm form events and text
     */
function clear_confirm(){
    $('#confirm .modal-header h3').text('');
    $('#confirm .modal-body').html('');
    $('#confirm-yes').unbind('click');    
}

function close_confirm(){
    
    clear_confirm();
    $('#confirm').modal('hide');
}
    
    
/**
 * Add comment
 */
function add_comment()
{
    var comment_form = '#comment-form';
    $('#add-comment-button').button('loading');
        
    form_data = $(comment_form).serialize();
    form_data += '&mode=add_comment';
        
    amplify.request('main',form_data,function(data){
            
        $('#add-comment-button').button('reset');
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            $('textarea[name=comment]').val('');
            $('#no_comments').hide();
            $('#latest_comment_container')
            .prepend(data.comment)
                
            $('#comment-text').val('');
            update_counter('#total_comments',1);
            $('#comment-'+data.cid).hide().fadeIn("slow");
        }
    })
}
    
    
/**
     * function to rate comment..
     */
function rate_comment(cid,thumb,type,typeid)
{
    amplify.request('main', 
    { 	
        mode : 'rate_comment',
        thumb : thumb,
        cid : cid,
        type : type,
        typeid : typeid
    },
    function(data)
    {
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            $('#comment-rating-'+cid).removeClass('btn-info')
            .removeClass('btn-info');
                 
            if(data.rating<0)
                $('#comment-rating-'+cid).addClass('btn-danger');
                
            if(data.rating>0)
                $('#comment-rating-'+cid).addClass('btn-info');
                
                
            $('#comment-rating-'+cid).text(data.rating);
        }
    });
}
    
    
    
/**
     * Add reply values to comment field...
     */
function add_reply(cid,author){
        
    var cancelBttn = ' <span>x</span> ';
    $('#reply-author').html('@'+author+' &nbsp;&nbsp;&nbsp;&nbsp;'+cancelBttn).parent().show()
    $('#reply-to').val(cid);
    $('#comment-text').focus();
}
    
/**
     * cancel reply ..
     */
function cancel_reply()
{
    $('#reply-author').html('').parent().hide();
    $('#reply-to').val('');
}
    
    
/**
     * Mark comment as spam..
     */
function spam_comment(cid,type,typeid)
{
        
    amplify.request('main',{ 	
        mode : 'spam_comment',
        cid : cid,
        type : type,
        typeid : typeid
    },function(data){
        if(data.msg)
        {
            $("#comment-container-"+cid).after(data.comment).remove();
        }
        if(data.err)
        {
            displayError(data.err)
        }
    })
        
}


/**
     * not spam function
     */
function unspam_comment(cid,type,typeid)
{
        
    amplify.request('main',{ 	
        mode : 'unspam_comment',
        cid : cid,
        type : type,
        typeid : typeid
    },function(data){
        if(data.msg)
        {
            $("#comment-container-"+cid).after(data.comment).remove();
        }
        if(data.err)
        {
            displayError(data.err)
        }
    })
        
}
    
    
/**
     * Delete comment...
     */
function delete_comment(cid,type)
{
    amplify.request('main',{ 	
        mode : 'delete_comment',
        cid : cid,
        type : type,
    },function(data){
        if(data.msg)
        {
            $("#comment-container-"+cid).fadeOut('slow',function(){
                $(this).remove();
            })
        }
        if(data.err)
        {
            displayError(data.err)
        }
    })
}
    
    
/**
     * Toggle upload options
     */
function toggle_upload($obj)
{
    $id = $($obj).attr('data-target');
    $('.upload-window').hide();
    $('#window-'+$id).show();

    $('.upload_options li').removeClass('active');
    $($obj).addClass('active');
}
    
    
    
function toggle_upload_list(obj)
{
    $fileid = $(obj).attr('rel');
    $('.upload_list').removeClass('upload_list_active');
    $(obj).addClass('upload_list_active');
    $('.upload-form').hide();
    $('#file-form-'+$fileid+' .upload-form').show();
}

function toggle_upload_tab(obj)
{
    $(obj).tab('show');
    $(obj).parent().children('.form-btn')
    .removeClass('active').addClass('disabled');
    $(obj).removeClass('disabled').addClass('active');
}

function share_object(form_id,type,bttn)
{
    if(bttn)
        $(bttn).button('loading')
    
    amplify.request('main',{
        mode : 'share_object',
        type : type,
        users : $("#"+form_id+" textarea:#share_users").val(),
        message : $("#"+form_id+" textarea:#message").val(),
        id : $("#"+form_id+" input:#objectid").val()
    },function(data){
        if(data.err)
        {
            displayError(data.err);
        }else
        {
         
            $('#'+form_id).hide();
            $('#share_email_success').show();
        }
        
        if(bttn)
            $(bttn).button('reset');
    });
}


function report(obj,bttn)
{
    $id = $(obj+' input[name=id]').val();
    $type = $(obj+' input[name=type]').val();
    $flag_type = $(obj+' input[name=flag_type]:checked').val();
    if(bttn)
        $(bttn).button('loading');
    
    
    $(obj+' .alert').hide().html('');
    
    amplify.request('main',{
        mode : 'flag_object',
        type : $type,
        flag_type : $flag_type,
        id : $id
    },function(data){
        if(data.err)
        {
            $(obj+' #report-error-msg').show().html(data.err);
        }else
        {
            $(obj+'  #report-success-msg').show().html(data.msg);
            $(obj).modal('hide');
        }
        
        if(bttn)
            $(bttn).button('reset');
    });
}




function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function prettyDate(time){
var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
        diff = (((new Date()).getTime() - date.getTime()) / 1000),
        day_diff = Math.floor(diff / 86400);

if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
        return;

return day_diff == 0 && (
        diff < 60 && "just now" ||
        diff < 120 && "1 minute ago" ||
        diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
        diff < 7200 && "1 hour ago" ||
        diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
        day_diff == 1 && "Yesterday" ||
        day_diff < 7 && day_diff + " days ago" ||
        day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
}

