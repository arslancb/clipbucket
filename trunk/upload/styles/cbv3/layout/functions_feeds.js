/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



function like_feed(feedId,liked)
{
    var obj = $('#likeable-'+feedId);
    var toggle_text = obj.attr('data-toggle-text');
    var liked = obj.attr('data-like');
    
    if(liked=='yes')
        obj.attr('data-like','no');
    else
        obj.attr('data-like','yes');
    
    obj.attr('data-toggle-text',obj.text());
    
    obj.text(toggle_text);
    
    amplify.request('feeds',{
        mode : 'like_feed',
        feed_id : feedId,
        liked : liked
    },function(data){
        $('#like-phrase-'+feedId).text(data.phrase);
    })
}


function get_friends()
{
    amplify.request('main',{
        mode : "get_friends"
    },function(data){
        
    })
}


    
/**
 * Add comment
 */
function add_feed_comment(fid)
{
    form_data = $('#feed-form-'+fid).serialize();
    form_data += '&mode=add_feed_comment';
    form_data += '&feed_id='+fid;
    
   $('#add-comment-'+fid).attr('disabled','disabled');
    
    amplify.request('feeds',form_data,function(data){
        
        $('#add-comment-'+fid).removeAttr('disabled');
        
        if(data.err)
        {
            displayError(data.err);
        }else
        {
            $('#add-comment-'+fid).val('');
            $('#new_comment_placeholder_'+fid).before(data.comment);
        }
    })
}

/**
 * function used to add a feed
 */

function add_new_post(type,type_id,content_type,content_id,content)
{
    $('#add_new_post').button('loading');
    
}