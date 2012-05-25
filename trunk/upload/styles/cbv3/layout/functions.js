
// CLIPBUCKET MAIN FUNCTIONS ----------------------


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