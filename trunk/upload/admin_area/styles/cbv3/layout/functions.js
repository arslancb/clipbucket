
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
        $newWidget += '<span class="btn widget-btn-long">';
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
    
    
    var WidgetModal = saveAndFetch($sideBarId,$widgetId); 
    $('#modal-forms-'+$sideBarId).append(WidgetModal);
    
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
            return data;
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
    }
}