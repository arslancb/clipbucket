/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var admin_ajax_url = baseurl+"/admin_area/ajax/";

amplify.request.define( "admin_video", "ajax", {
    url: admin_ajax_url+"/videos.php",
    type: "POST",
    dataType: "json",
    decoder: function( data, status, xhr, success, error ) {
        if ( status === "success" ) {
            success( data );
        } else {
            error( data );
        }
    }
});

function video_action(vid,action)
{
    var mode='';
    if(action=='activate')
    {
        $('#video-active-'+vid)
        .addClass('label-success')
        .removeClass('label-important')
        .text('Active');
        mode ='activate_video';
        
        
        $('#video-menu-'+vid+' li[data-key=deactivate]').show();
        $('#video-menu-'+vid+' li[data-key=activate]').hide();
    }
    
    if(action=='deactivate')
    {
        $('#video-active-'+vid)
        .removeClass('label-success')
        .addClass('label-important')
        .text('Inactive');
        mode ='deactivate_video';
        
        $('#video-menu-'+vid+' li[data-key=deactivate]').hide();
        $('#video-menu-'+vid+' li[data-key=activate]').show();
    }
    
    
    amplify.request('admin_video',{
        mode : mode,
        videoid : vid

    },function(data){ /** Add more stuff here **/    });
    
    return true;
}