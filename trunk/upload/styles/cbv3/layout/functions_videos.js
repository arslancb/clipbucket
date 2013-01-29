function getEmbedCode(embed)
{
    switch(embed.type)
    {
        case "iframe":
        {
            var $code = '<iframe ';
            $code += 'src="'; //opening src attr
            $code += embed.src.url+'?embed=true';

            if(embed.src.params)
            {  
                $.each(embed.src.params, function(attr,val) {
                    $code += '&'+attr+'='+encodeURIComponent (val);
                });     
            }

            $code += '" '; //ending src attr

            if(embed.params)
            {
                $.each(embed.params, function(attr,val) {
                    $code += attr+'="'+(val)+'" ';
                }); 
            }
            $code += '>';
            $code +='</iframe>';
                        
            return $code;
        }
        break;
        case "embed_object":
        {
            $code = '<object ';
            $code += 'height="'+embed.params.height+"' "; //setting object height
            $code += 'width="'+embed.params.width+"' >"; //setting object width

            //adding src
            $code += '<param name="movie" value="';
            $code += embed.src.url+'?embed=true';

            if(embed.src.params)
            {
                $.each(embed.src.params, function(attr,val) {
                    $code += '&'+attr+'='+encodeURIComponent (val);
                });  
            }

            $code += '"></param>'; //ending src attr

            if(embed.params)
            {
                $.each(embed.params, function(attr,val) {
                    $code += '<param name="'+attr+'" value="'+val+'"></param>';
                });     
            }


            $code += '<embed ';
            $code += 'src="'; //opening src attr
            $code += embed.src.url+'?embed=true';

            if(embed.src.params)
            {
                $.each(embed.src.params, function(attr,val) {
                    $code += '&'+attr+'='+encodeURIComponent (val);
                });  
            }

            $code += '" '; //ending src attr

            if(embed.params)
            {
                $.each(embed.params, function(attr,val) {
                    $code += attr+'="'+(val)+'" ';
                }); 
            }
            $code += '>';
            $code += '</embed>';

            $code += '</object>';
                        
            return $code;
        }
                    
        break;

        case "embeded":
        {
            return $code_props['src'];
        }
        break;
    }
}
            
function embed_code_dim(width,height,obj)
{
    if(width && height)
    {
        embed.params.width = width;
        embed.params.height = height;
        $('#embed_code').html(getEmbedCode(embed));
    }
                
    $('.dim-box-selected').removeClass('dim-box-selected');
    $(obj).addClass('dim-box-selected');
                
}
            
function update_src_params(name,value)
{
    embed.src.params[name]= value;
    $('#embed_code').html(getEmbedCode(embed));
}
            
function toggleEmbedSrc(){
    var newEmbed = embed;
    embed = embed_alt;
    embed_alt = newEmbed;
    $('#embed_code').html(getEmbedCode(embed));   
}

function videos_action(action,broadcast)
    {
        var vids = new Array();
        
        $('input.check-item').each(function(index,obj)
        {
            if($(obj).prop('checked'))
            {
                
                if($(obj).val())
                    vids[index] = $(obj).val();
            }
        });
        
        switch(action){
            
            case "delete":
                {

                
                    amplify.request('videos',{
                        mode:"delete_videos",
                        vids : vids
                    },function(data)
                    {
                        if(data.err)
                        {
                            displayError(data.err);
                        }else
                        {
                            $.each(vids,function(index,value)
                            {
                                $('.video-box-'+value).fadeOut(250,function(){
                                    $(this).remove();
                                });
                            });
                        }
                    });
                }
                break;
            
            case "broadcast":
                {
                    amplify.request('videos',{
                            mode:"update_broadcast",
                            vids : vids,
                            type : broadcast
                        },function(data)
                        {
                            if(data.err)
                            {
                                displayError(data.err);
                            }else
                            {
                                $.each(vids,function(index,value)
                                {
                                    $('.video-box-'+value+' .broadcast-label')
                                    .html(broadcast_icons[broadcast]+' '+data.label);
                                });
                            }
                        });
                }
                break;
        }
        
        close_confirm();
    }