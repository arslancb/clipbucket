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
    
    var post = $('#feed-form-'+fid+' textarea[name=comment_text]');
    
    if(post.hasClass('mention'))
    {
        post.mentionsInput('val', function(text) {
        post_val = text;
        }); 
    }else
        post_val = post.val();
    
    form_data += '&comment='+post_val;
    
    
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

function add_new_post(post,type,type_id,content_type,content_id,action)
{
    amplify.request('feeds',{
        'post'          : post,
        'object_type'   : type,
        'object_id'     : type_id,
        'content_type'  : content_type,
        'content_id'    : content_id,
        //'content'       : content,
        'mode'          : 'add_feed',
        'action'        : action
    },function(data){
        
        $('#add_new_post').button('reset');
        
        if(data.template)
        {
            //Apend the data...
            $('.cb-feeds').prepend(data.template);
            $('#feed-'+data.fid).hide().fadeIn(1000);
            $('#post_message').val('');
            
            return true;
        }else
        {
            displayError(data.err);
            return false;
        }        
    })
}


function cb_feed_target_select(type)
{
    
}

var feedSuggestParamsArr = Array();
feedSuggestParamsArr['friend']    = '&mode=friends';
feedSuggestParamsArr['group']     = '&mode=groups';
var feedSuggestParams = '';

    
function getFeedParams(type){
    feedSuggestParams = feedSuggestParamsArr[type];
}

function genFeedSuggestObj(feedSuggestionMode)
{
    $('#feed_suggestion').trigger('reset');
    
    getFeedParams(feedSuggestionMode);
    
    console.log('init gen feed suggestion');
    $("#share_feed_target").autoSuggest(baseurl+'/ajax/items.php', { 
        selectedItemProp: "name", 
        asHtmlID : 'feed_suggestion',
        searchObjProps: "name",
        selectionLimit : 1,
        limitText : false,
        selectionAdded : function(){
            $('input.share_feed_target').hide();
        },
        selectionRemoved : function(elem){
            elem.remove();
            $('input.share_feed_target').show();
        },
        formatList: function(data, elem){
            var my_image = data.image;
            var new_elem = elem.html("<img src='"+data.image+"' width='20' height='20'/> "+data.name);
            return new_elem;
        }
    });
}
     
     
/**
 * Gets new information..
 */
function cb_khabri()
{
     
    amplify.request('feeds',
    {
        mode : 'get_updates' 
    },function(data)
    {
        if(data.notifications)
        {
            $('#new_notifications_label')
            .html(data.notifications.total_new);
            
            display_notifications(data.notifications);
        }
    })
}

function display_notifications(notifications)
{
    //First lets remove existing elements
    var ids = notifications.ids;
    
    $.each(ids,function(index,value){
        $('#notification-block-'+value).remove();
    })
    
    $('#new_notifications').before(notifications.template);
}

function read_notifications()
{
    $('#new_notifications_label')
    .html('0');
    

    amplify.request('feeds',
    {
        mode : 'read_notification' 
    },function(data)
    {
        
    })
}