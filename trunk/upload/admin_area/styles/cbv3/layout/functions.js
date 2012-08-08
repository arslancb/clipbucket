function js(){
    return false;
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
 * toggles the sidebar for widgets
 * @param object
 */
function toggleSidebar($obj)
{
    $('.sidebar').hide();
    var text = $($obj).text();
    var ref = $($obj).attr('ref');

    $('#sidebar-dd-text').text(text);
    $('#'+ref).show();
}

/**
 * Create a widget inside the sidebar so that it can be used
 */
function createWidget($widgetId,$sideBarId)
{
    
    
    var $widget = $('#'+$widgetId);
    var $sidebar = $('#'+$sideBarId);
    var $widgetList = $('#'+$sideBarId+' .widgets-list');
    var $widgetTitle = $('#'+$widgetId+' .admin-widget-box-title').text();
    $sideBarId = $('#'+$sideBarId+' input[name=sidebar_id]').val();
    var $sideBarTitle = $('a[ref='+$sideBarId+']').text();
    
    if($('#'+$widgetId+'-'+$sideBarId).html())
    {
        alert($widgetTitle+' is already in '+$sideBarTitle)
        return false;
    }

    var $newWidget = '<div class="widget-bar" id="'+$widgetId+'-'+$sideBarId+'" >';
    $newWidget += '<div class="btn-group">';
    $newWidget += '<span class="btn widget-btn-long relative">';
    $newWidget += '<img src="'+imageurl+'/loaders/1.gif"';
    $newWidget += 'class="loader absolute" style="left:5px"/>';
    $newWidget +=  $widgetTitle+'</span>';
    $newWidget += '<button class="btn dropdown-toggle"';
    $newWidget += ' data-toggle="modal" ';
    $newWidget += ' data-target="#'+$widgetId+'-'+$sideBarId+'-modal" ';
    $newWidget += '><i class="caret"></i></button>';
    $newWidget += '</div>';
    $newWidget += '<input type="hidden" name="widgets[]" value="'+$widgetId+'"/>';
    $newWidget += '</div>';
      
    
    /*Widget Modal
    var WidgetModal = '<div class="modal hide fade"';
        WidgetModal += 'id="'+$widgetId+'-'+$sideBarId+'-modal">';
        WidgetModal +=$('#'+$widgetId+'--modal').html();
        WidgetModal +='</div>'
    
    //Append widget...*/

    $widgetList.append($newWidget);
       
    saveAndFetch($sideBarId,$widgetId); 
    

    
    return true;
}

/**
 * Save Sidebar ..
 */
function saveSidebar($Id,$widgetId)
{
    var FormData = $('#'+$Id+'-form').serialize();
    FormData = FormData + '&mode=update-sidebar';
    amplify.request( "update-sidebar",FormData,
        function( data ) {
            
        }
        );
}

/** 
 * Save and fetch widget form as well....
 */
function saveAndFetch($Id,$widgetId)
{
    var FormData = $('#'+$Id+'-form').serialize();
    FormData = FormData + '&mode=update-sidebar&fetch-widget='+$widgetId;
    amplify.request("update-sidebar",FormData,
        function(data){
            $('#modal-forms-'+$Id).append(data.data);
            $('#'+$widgetId+'-'+$Id+' .loader' ).hide();
        }
        );
}

/**
 * Save widget
 */
function saveWidget($bttn,$id)
{
    $($bttn).attr('disabled','disabled');
    $($bttn).addClass('disabled');
    $($bttn).text('Saving...');
    
    var FormData = $('#'+$id+'-form').serialize();
    FormData = FormData+'&mode=update-widget';
    amplify.request("update-sidebar",FormData,
        function(data){
            $($bttn).removeAttr('disabled');
            $($bttn).removeClass('disabled');
            $($bttn).text('Save');
            $($bttn).prev().trigger('click');
        }
        );
        
}

/**
 * Remove widget from sidebar...
 */

function deleteWidget($widgetId,$sidebarId){
   
    if($('#'+$widgetId+'-'+$sidebarId).html())
    {
        $('#'+$widgetId+'-'+$sidebarId).remove();
        saveSidebar($sidebarId); 
        $('#'+$widgetId+'-'+$sidebarId+'-modal').modal('hide').remove();
    }
}


/**
 * Add Category..
 */
function add_category()
{
    var formData = $('#add-category').serialize();
    formData += '&mode=add_category';
    
    loading('add-category');
    amplify.request("categories",formData,
        function(data){
            if(data.err)
            {
                displayError(data.err);
            }else if(data.data)
            {
                $('#categories-list').append(data.data);
                $('#category-'+data.cid).hide().fadeIn('slow');
                loading('add-category','hide');
                scrollTo('#category-'+data.cid);
            }
        }
        );
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




/**
 * Scroll to an elemet
 */
function scrollTo($element){
    $('html, body').animate({
        scrollTop: $($element).offset().top
    }, 'fast');
}

/**
 * Delete category
 * @param cid STRING
 */
function delete_category(cid,type)
{
    amplify.request("categories",{
        cid : cid,
        'mode' : 'delete_category',
        type:type
    },
    function(data){
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            $('#category-'+cid).fadeOut();
        }
    }
    );

}


/**
 * Make Default
 * @param cid STRING
 */
function make_default(cid,category_name,type)
{
    amplify.request("categories",{
        cid : cid,
        'mode' : 'make_default',
        'name':category_name,
        type:type
    },
    function(data){
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            displayMsg(data.msg)
        }
    }
    );
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
 * edit category...
 */
function edit_category(id,type)
{
    
    amplify.request("categories",{
        'mode' : 'edit_category',
        cid:id,
        type:type
    },
    function(data){
        if(data.success)
        {
            $('#edit-category-modal .form-basic').html(data.template);
            $('#edit-category-modal h3').html(data.title);
            $('#edit-category-modal .update-message').html('');
            $('#edit-category-modal').modal('show');
        }else
        if(data.err)
            displayError(data.err);
    }
    );
}

/**
 * Change Category
 * @param id INT
 */
function save_category(id)
{
    $('#save-category-button').addClass('disable');
    loading('save-category');
    
    var formData = $('#edit-category').serialize();
    formData += '&mode=save_category';

    amplify.request("categories",formData,
        function(data){
            if(data.err)
                $('#edit-category-modal .update-message').html('<div class="alert alert-danger">'+data.err+'</div>')
            else
                $('#edit-category-modal .update-message').html('<div class="alert alert-success">'+data.msg+'</div>') 
            loading('save-category','hide');
            $('#save-category-button').removeClass('disable');
        }
        );
}


/**
 * Update Order
 * 
 * @param id INT
 * @param order INT
 * @param type STRING
 */
function update_order(id,order,type)
{
    if(type=='category')
        var amplify_type = 'categories';
    
    loading(type+'-'+id);

    amplify.request(amplify_type,{
        "mode":"update_order",
        "cid":id,
        'order':order,
        type:type
    },
    function(data){
        if(data.err)
            displayError(data.err);
        loading(type+'-'+id,"hide");
    }
    );
}


/**
 * Add video profile..
 */
function add_video_profile()
{
    var $form = $('#video-profile-form').serialize();
    
    $('#video-profile-bttn').button('loading');
    
    $('#video-profile-alert').hide().html('');
    
    var postData  = $form+'&mode=add_profile';
    amplify.request('videos',postData,function(data){
        
        if(data.rel.err)
        {
            focusObj(data.rel.err,'error');
        }
        
        if(data.err)
        {
            $.each(data.err,function(rel,msg){
                $('#video-profile-alert').append(msg+'<br>').show();
            })
            $('#video-profile-bttn').button('reset');
        }else
        {
            $('#profile_id').val(data.profile_id);
            $('#video-profile-form').submit();
        }
    })
}


/**
 *  Update video profile...
 */
function update_video_profile(pid)
{
    var $form = $('#video-profile-form'+pid).serialize();
    
    $('#video-profile-bttn'+pid).button('loading');
    
    $('#video-profile-alert'+pid).hide().html('');
    
    var postData  = $form+'&mode=update_profile';
    amplify.request('videos',postData,function(data){
        
        if(data.rel.err)
        {
            focusObj(data.rel.err,'error');
        }
        
        if(data.err)
        {
            $.each(data.err,function(rel,msg){
                $('#video-profile-alert'+pid).append(msg+'<br>').show();
            })
            $('#video-profile-bttn').button('reset');
        }else
        {
            $('#video-profile-form'+pid).submit();
        }
    })
}

/**
 * Adjust notification sidebar height.
 */
function notificationHeight()
{
    var height = $(window).height();
    if(height<450)
        height = 450;
    $('.notification-container').height(height + 10);
    $('.home-box').css('min-height',height);
}


/** 
 * Add Note for admin
 */

function add_note()
{
    var $note = $('#note-text').val();

        amplify.request('main',{"mode":"add-note","note":$note},function(data){
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            var new_note = '<tr id="note-'+data.id+'" class="display-none"><td>';
            new_note += data.note;
            new_note += '</td>';
            new_note += '<td><a href="javascript:void(0)" class="icon-trash" onclick="delete_note(\''+data.id+'\')"></i></td></tr>';
            
            $('#note-text').val('');
            $('#notes-container').prepend(new_note);
            $('#note-'+data.id).fadeIn();
        }
    });

}

function delete_note($id)
{
    amplify.request('main',{mode:'delete-note',id:$id},function(data){
        if(data.err){
            displayError(data.err);
        }else
        {
            $('#note-'+$id).fadeOut(function(){ $(this).remove() });
        }
    })
}

function update_admin_home_order()
{
    var formData = $('#admin-home-blocks-form').serialize();
    formData += '&mode=update-home-block-order';
    
    amplify.request('main',formData,function(data){
        if(data.err){
            displayError(data.err);
        }else
        {
            
        }
    })
}