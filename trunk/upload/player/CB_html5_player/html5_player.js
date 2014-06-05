
$(document).ready(function(){
	//INITIALIZE
	var video = $('#myVideo');
	var container = $('.cont');
	var test = $(".cont,.myVideo");


	 video[0].removeAttribute("controls");
	$('.control').show().css({'bottom':-60});
	$('.caption').show().css({'top':-200});
	$('.loading').fadeIn(500);
	$('.caption').fadeIn(500);
 

	
	//before everything get started
	    video.on('loadedmetadata', function() {
		//set video properties	
		video.attr('poster', '');
		$('.fcurrent').text(timeFormat(0));
		$('.fduration').text(timeFormat(video[0].duration));
		updateVolume(0, 0.7);
		$('.buffer').hide();
			
		//bind video events
		$('.cont')
		.hover(function() {
			$('.control').stop().animate({'bottom':0}, 100);
			$('.caption').stop().animate({'top':-7}, 600);
		}, function() {
			if(!volumeDrag && !timeDrag){
				$('.control').stop().animate({'bottom':-51}, 500);
				$('.caption').stop().animate({'top':-200}, 500);
			}
		})
		.on('click', function() {
			$('.init').hide();
			$('.btnPlay').addClass('paused');
			$(this).unbind('click');
			video[0].play();
			$('.buffer').show();
			//set video properties
		    //start to get video buffering data 
		    setTimeout(startBuffer, 150);
			});
		$('.init').fadeIn(2500);
	});
	
	//display video buffering bar
	var startBuffer = function() {
		var currentBuffer = video[0].buffered.end(0);
		var maxduration = video[0].duration;
		var perc = 100 * currentBuffer / maxduration;
		$('.bufferBar').css('width',perc+'%');
			
		if(currentBuffer < maxduration) {
			setTimeout(startBuffer, 2000);
		}
	};	
	
	//display current video play time
	video.on('timeupdate', function() {
		var currentPos = video[0].currentTime;
		var maxduration = video[0].duration;
		var perc = 100 * currentPos / maxduration;
		$('.timeBar').css('width',perc+'%');	
		$('.fcurrent').text(timeFormat(currentPos));	
	});
	
	//CONTROLS EVENTS
	//video screen and play button clicked
	video.on('click', function() { playpause(); } );
	$('.btnPlay').on('click', function() { playpause(); } );
    $('.caption').on('click', function() { playpause(); } ); 
    $('.init').on('click', function() { playpause(); } ); 
	var playpause = function() {
		if(video[0].paused || video[0].ended) {
            $('.init').hide();
			$('.btnPlay').addClass('paused');
			video[0].play();
			
		}
		else {

			 $('.init').show();
			$('.btnPlay').removeClass('paused');
			video[0].pause();
		}
	};
	 
    $( "#replay_v" ).click(function() {
        video[0].play();
        $('#opacity').hide();
		$('#related_1').hide();
		$('.control').show();
		$('.caption').show();
		$('#web').show();

     });
   $( "#cancel_v" ).click(function() {
        
        $('#opacity').hide();
		$('#related_1').hide();
		$('.control').show();
		$('.caption').show();
		$('.init').show();
        $('#web').show();
     });


	//speed text clicked
	$('.btnx1').on('click', function() { fastfowrd(this, 1); });
	$('.btnx3').on('click', function() { fastfowrd(this, 3); });
	var fastfowrd = function(obj, spd) {
		$('.text').removeClass('selected');
		$(obj).addClass('selected');
		video[0].playbackRate = spd;
		video[0].play();
	};
	
	//stop button clicked
	$('.btnStop').on('click', function() {
		$('.btnPlay').removeClass('paused');
		updatebar($('.progress').offset().left);
		video[0].pause();
	});

                     


$('.btnFS').on('click', function() {
$(this).toggleClass('enterbtnFS');
    if($.isFunction(container[0].webkitRequestFullScreen)) {
              if($(this).hasClass("enterbtnFS")) 
                   {
                     container[0].webkitRequestFullScreen(); 

                     $('.caption').hide();
                 	 $(".largescr").hide();
                 	

                     $(".control").hover(
                     function() {
                     $('.control').stop().animate({'bottom':0}, 100);
                     },
                     function() {
                     $('.control').stop().animate({'bottom':-40}, 1000);
                     });
                      
                   
                    
                   }   
                   else 
                   { 
                     document.webkitCancelFullScreen(); 
                     $('.caption').show();
               	   	 $(".largescr").show(); 
               	   	 $('.cb-item-title-container').css({'margin-top':0}); 
               	   	
               	   	   $(".control").hover(
                       function() {
                       $(this).unbind('mouseenter').unbind('mouseleave');
                       });



                   }  
                 
    }  
    else if ($.isFunction(container[0].mozRequestFullScreen)) {
              if($(this).hasClass("enterbtnFS"))
                  { 
                 	 container[0].mozRequestFullScreen();
                     $('.caption').hide();
                 	 $(".largescr").hide();

                      $(".control").hover(
                     function() {
                     $('.control').stop().animate({'bottom':0}, 100);
                     },
                     function() {
                     $('.control').stop().animate({'bottom':-40}, 1000);
                     });





                  }
               else 
                  {  
                     document.mozCancelFullScreen();
               	     $('.caption').show();
               	   	 $(".largescr").show();
               	   	 $('.cb-item-title-container').css({'margin-top':0});
               	   
                       $(".control").hover(
                       function() {
                       $(this).unbind('mouseenter').unbind('mouseleave');
                       });
                    
               	  } 
    
    }
             else { 
                     alert('Your browsers doesn\'t support fullscreen');
    }
});



   





//HD on/off button clicked
$(".hdon").on('click', function() {
$(this).toggleClass('hdoff');
    $('.myVideo').removeClass('init');
    $('source', '#myVideo').eq(1).prependTo('#myVideo');
    $('#myVideo')[0].load();
    $('#myVideo')[0].play();
    

    $('.btnPlay').addClass('paused');
    video[0].pause();
    
});

	
	
	//sound button clicked
	$('.sound').click(function() {
		video[0].muted = !video[0].muted;
		$(this).toggleClass('muted');
		if(video[0].muted) {
			$('.volumeBar').css('width',0);
		}
		else{
			$('.volumeBar').css('width', video[0].volume*100+'%');
		}
	});
	
	//VIDEO EVENTS
	//video canplay event
	video.on('canplay', function() {
		$('.loading').fadeOut(100);
	});
	
	//video canplaythrough event
	//solve Chrome cache issue
	var completeloaded = false;
	video.on('canplaythrough', function() {
		completeloaded = true;
	});
	
	//video ended event
	video.on('ended', function() {
		$('.btnPlay').removeClass('paused');
		video[0].pause();
		$('#opacity').show();
		$('#related_1').show();
		$('.control').hide();
		$('.caption').hide();
		$('#web').hide();
	});

	//video seeking event
	video.on('seeking', function() {
		//if video fully loaded, ignore loading screen
		if(!completeloaded) { 
			$('.loading').fadeIn(200);
		}	
	});
	
	//video seeked event
	video.on('seeked', function() { });
	
	//video waiting for more data event
	video.on('waiting', function() {
		$('.loading').fadeIn(200);
	});
	
	//VIDEO PROGRESS BAR
	//when video timebar clicked
	var timeDrag = false;	/* check for drag event */
	$('.progress').on('mousedown', function(e) {
		timeDrag = true;
		updatebar(e.pageX);
	});
	$(document).on('mouseup', function(e) {
		if(timeDrag) {
			timeDrag = false;
			updatebar(e.pageX);
		}
	});
	$(document).on('mousemove', function(e) {
		if(timeDrag) {
			updatebar(e.pageX);
		}
	});
	var updatebar = function(x) {
		var progress = $('.progress');
		
		//calculate drag position
		//and update video currenttime
		//as well as progress bar
		var maxduration = video[0].duration;
		var position = x - progress.offset().left;
		var percentage = 100 * position / progress.width();
		if(percentage > 100) {
			percentage = 100;
		}
		if(percentage < 0) {
			percentage = 0;
		}
		
		$('.timeBar').css('width',percentage+'%');	
		video[0].currentTime = maxduration * percentage / 100;
	};

	//VOLUME BAR
	//volume bar event
	var volumeDrag = false;
	$('.volume').on('mousedown', function(e) {
		volumeDrag = true;
		video[0].muted = false;
		$('.sound').removeClass('muted');
		updateVolume(e.pageX);
	});
	$(document).on('mouseup', function(e) {
		if(volumeDrag) {
			volumeDrag = false;
			updateVolume(e.pageX);
		}
	});
	$(document).on('mousemove', function(e) {
		if(volumeDrag) {
			updateVolume(e.pageX);
		}
	});
	var updateVolume = function(x, vol) {
		var volume = $('.volume');
		var percentage;
		//if only volume have specificed
		//then direct update volume
		if(vol) {
			percentage = vol * 100;
		}
		else {
			var position = x - volume.offset().left;
			percentage = 100 * position / volume.width();
		}
		
		if(percentage > 100) {
			percentage = 100;
		}
		if(percentage < 0) {
			percentage = 0;
		}
		
		//update volume bar and video volume
		$('.volumeBar').css('width',percentage+'%');	
		video[0].volume = percentage / 100;
		
		//change sound icon based on volume
		if(video[0].volume == 0){
			$('.sound').removeClass('sound2').addClass('muted');
		}
		else if(video[0].volume > 0.5){
			$('.sound').removeClass('muted').addClass('sound2');
		}
		else{
			$('.sound').removeClass('muted').removeClass('sound2');
		}
		
	};




//getting large screen button only for watch video page 
if (window.location == homepath+'/watch_video.php?v='+key)

{

$('.btmControl').append('<div class="smallscr largescr hbtn" id="largescr" title="Enlarge/Small Size"></div>');
$('#largescr').insertAfter("#fs");

}

else
{}





//Large screen function
$(".largescr").click(function() {
	
 $(this).toggleClass('smallscr');


if(!$(this).hasClass('smallscr')) {
			$(".cont").animate({height:'+=220px',width:'+=390px'},"fast");
            $(".player").animate({width:'+=390px'},"fast");
			$('.cb-item-title-container').css({'margin-top':+250});	
			 
		}
		
		                     else{
			$(".cont").animate({height:'-=220px',width:'-=390px'},"fast");
		    $(".player").animate({width:'-=390px'},"fast");
		    $('.cb-item-title-container').css({'margin-top':'0px'});
			
		}
});


//Right click Menu 


//Right Click Menu

$('#cont').append('<div id="rightcmenu"></div>');
$('#rightcmenu').append('<span id="op">CB Html5 menu</span>');
$('#rightcmenu').append('<ul id="ritems"></ul>');
$('#ritems').append('<li id="copy"  class="rlist copy">Show Video link</li>');
$('#ritems').append('<li class="rlist about">About</li>');
$('#ritems').append('<li class="rlist clip">Powered by Clipbucket</li>');

$('.cont').bind("contextmenu", function (e) {
    e.preventDefault();                 // To prevent the default context menu.
    $("#rightcmenu").css("left", e.pageX);   // For updating the menu position.
    $("#rightcmenu").css("top", e.pageY);    // 
    $("#rightcmenu").fadeIn(500, startFocusOut()); //  For bringing the context menu in picture.
});


function startFocusOut() {
    $(document).on("click", function () {   
        $("#rightcmenu").hide(500);              // To hide the context menu
        $('.cont').off("click");           
    });
}

$(".clip").click(function(event) {
  window.location = 'http://clip-bucket.com/';
 });


$(".about").click(function(event) {
  window.location = homepath;
          
});



$('.copy').click(function() {
alert(document.URL);
});




//Logo
$('.btmControl').append('<div id="path" class="path hbtn"  > </div>');

$('#path').prop("href","http://clip-bucket.com/");
$("#path").insertAfter("#hd");
$('#path').css({
	            'backgroundImage': 'url(data:image/png;base64,' + webpath + ')',
				'float':'right',
				'margin-right':'3px',
				'margin-top':'3px',
				
				
			});

  $("#path").click(function(event) {
  window.location = 'http://clip-bucket.com/';
          
  });



//website logo

/*$('.cont').append('<div><img id="web"  src=data:image/png;base64,'+ web +'> </div>');
$('#web').css({
	            'top' :  $top,
	            'left' :  $left,
	            'bottom' : $bottom,
	            'right' :  $right  ,
				'position': 'absolute',
	            'width': '100px',
	            'height': '30px',
	           
				
				
			});

*/

$('#name_v,#thumb_v').mouseover(function() {
	$(this).css({'opacity':'1',
'border': '0px solid #000',
'box-shadow':'1px 0 5px #fff',
'-moz-box-shadow':'1px 0 5px #fff',
'-webkit-box-shadow':'1px 0 5px #fff',});
});

$('#name_v,#thumb_v').mouseout(function() {
	$(this).css({'opacity':'.9',
'border': '0px solid #000',
'box-shadow':'0px 0 0px #fff',
'-moz-box-shadow':'0px 0 0px #fff',
'-webkit-box-shadow':'0px 0 0px #fff',});
});







//Time format converter - 00:00
	var timeFormat = function(seconds){
		var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
		var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
		return m+":"+s;
	};
});


