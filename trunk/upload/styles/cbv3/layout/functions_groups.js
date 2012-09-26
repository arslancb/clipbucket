/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Add topic for groups..
 */
function add_topic()
{
    
    loading_pointer('add-group-topic');
    
    $('#add-topic-modal .alert').hide();
    $('#add-topic-bttn').button('loading');
    
    var $formFields = $('#add-topic-form').serialize();
    $formFields = $formFields+'&mode=add_topic';
    
    amplify.request('groups',$formFields,function(data){
        
        if(data.err)
        {
            var error_text = '<ul>';
            $.each(data.err,function(key,value){
                error_text += '<li>'+value+'</li>';
            })
            error_text +='</ul>';
            
            $('#add-topic-modal .alert').html(error_text).show();
        }else
        {
            $template = data.template;
            
            
            
            $('#add-topic-form')[0].reset();
            $('#add-topic-modal').modal('hide');
            
            $('#group-topics').prepend($template);
            $('#group-topic-'+data.tid).hide();
            
            $('#add-topic-modal').on('hidden', function () {
                $('#group-topic-'+data.tid).fadeIn();
            })

        }
        
        $('#add-topic-bttn').button('reset');
        
        loading_pointer('add-group-topic','hide');
    })
}


function groupToggleList($obj,$main,$scnd)
{
    $scnd = $('#'+$scnd);
    $main = $('#'+$main);
    $obj = $($obj);
       
    if($scnd.css('display')=='none')
    {
        $main.hide();
        $scnd.show();
        $obj.addClass('active');
    }else
    {
        $main.show();
        $scnd.hide();
        $obj.removeClass('active');
    }
}
    
function group_member_action($action)
{
    $('#action_mode').attr('name',$action);
    $('#group-members-form').submit();
}