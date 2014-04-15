
$(document).ready(function(){
	//INITIALIZE
	var video = $('#myVideo');
	var container = $('.videoContainer');
	var test = $(".videoContainer,.myVideo");
	//remove default control when JS loaded
/*
$( ".btmControl" ).append( "<div id=logo>Hello</div>" );
$("#logo").insertAfter("#hd");
*/


$('.btmControl').append('<div id="logo" class="logo hbtn" title="CB logo" > </div>');
$('#logo').prop("href","http://clip-bucket.com/");
$("#logo").insertAfter("#hd");
$('#logo').css({
	            'backgroundImage':'url(http://clip-bucket.com/img/logo.png)',
				'float':'right',
				'margin-right':'3px',
				'margin-top':'3px',
				
				
			});

  $("#logo").click(function(event) {
  window.location = 'http://clip-bucket.com/';
          
  });






	video[0].removeAttribute("controls");
	$('.control').show().css({'bottom':-60});
	$('.caption').show().css({'top':-200});
	$('.loading').fadeIn(500);
	$('.caption').fadeIn(500);
 
	//before everything get started
	    video.on('loadedmetadata', function() {
		
		//set video properties	
		$('.fcurrent').text(timeFormat(0));
		$('.fduration').text(timeFormat(video[0].duration));
		updateVolume(0, 0.7);
		$('.buffer').hide();
			
		//bind video events
		$('.videoContainer')
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
                   }   

                                     
              else 
                   { 
                     document.webkitCancelFullScreen(); 
                     $('.caption').show();
               	   	 $(".largescr").show();  
                   }  
                 
    }  
    else if ($.isFunction(container[0].mozRequestFullScreen)) {
              if($(this).hasClass("enterbtnFS"))
                  { 
                 	 container[0].mozRequestFullScreen();
                     $('.caption').hide();
                 	 $(".largescr").hide();
                  }
               else 
                  {  
                     document.mozCancelFullScreen();
               	     $('.caption').show();
               	   	 $(".largescr").show();
               	  } 
    
    }

    else { 
           alert('Your browsers doesn\'t support fullscreen');
    }
});



$(document).on('keydown',function(e)
{ 
    var key = e.charCode || e.keyCode;
    if( key == 122 )
        {   alert('test');
        	e.preventDefault();
        
        }
    else
        {}
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

	/*
	//
	$('.hdon').click(function() {
		$(this).toggleClass('hdoff');
		
		//if lightoff, create an overlay
		if(!$(this).hasClass('hdoff')) {
       
			$(this).removeClass('normal');
			
		}
		
		else {

			
		}
	});
	*/
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





$(".largescr").toggle(function(){
$(".videoContainer,#myVideo").height($(".videoContainer,#myVideo").height()+220);

},function(){
$(".videoContainer,#myVideo").height($(".videoContainer,#myVideo").height()-220);

});
$(".largescr").toggle(function(){
$(".videoContainer,#myVideo").width($(".videoContainer,#myVideo").width()+390);
$(".player").width($(".player").width()+390);
//$('.side-video').css({'margin-top':+943});
$('.cb-item-title-container').css({'margin-top':+250});
$(this).toggleClass('smallscr');

},function(){
$(".videoContainer,#myVideo").width($(".videoContainer,#myVideo").width()-390);
$(".player").width($(".player").width()-390);
$('.cb-item-title-container').css({'margin-top':+22});
//$('.side-video').css({'margin-top':0});
$(this).toggleClass('smallscr');

});


//Right click Menu 

$('.videoContainer').bind("contextmenu", function (e) {
    e.preventDefault();                 // To prevent the default context menu.
    $("#rightcmenu").css("left", e.pageX);   // For updating the menu position.
    $("#rightcmenu").css("top", e.pageY);    // 
    $("#rightcmenu").fadeIn(500, startFocusOut()); //  For bringing the context menu in picture.
});


function startFocusOut() {
    $(document).on("click", function () {   
        $("#rightcmenu").hide(500);              // To hide the context menu
        $('.videoContainer').off("click");           
    });
}



$(".clip").click(function(event) {
  window.location = 'http://clip-bucket.com/';
          
});
/*
$(".about").click(function(event) {

 window.location = '';

});
*/

$('.copy').click(function() {
alert(document.URL);

});






/*
$('.btmControl').add('<div id="logo" class="logo hbtn" title="CB logo" >Insert Div Content</div>');  

document.getElementById("logo").style.backgroundImage="url('http://clip-bucket.com/img/logo.png')";  
document.getElementById("logo").style.cssFloat="right";

*/


//Time format converter - 00:00
	var timeFormat = function(seconds){
		var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
		var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
		return m+":"+s;
	};
});


