
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

