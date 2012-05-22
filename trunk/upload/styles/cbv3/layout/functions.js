
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
    amplify.request("main",{ "mode":"rating",type:type,
        id:id,rating:rating }//params,
        ,function(data){ 
            
            $('#video-rating-container')
            .html(data.template);
            loading('rating','hide');
        }
    );
}

