isFullScreen = false;
isButtonFullscreen = false;
$(document).ready(function()
{

    //INITIALIZE
	var video = $('#myVideo');
	var container = $('.cont');
	var test = $(".cont,.myVideo");
	var loadmetadata = true;
	var _pause = false;
	
	//showing pause icon by before video load
	$('.btnPlay').addClass('paused');

	attachEvents();

	$('.loading').fadeIn(500);
	

    // setting player controls when video gets ready :)
  	var ready_state_interval =  setInterval(function(){

		var vid = document.getElementById("myVideo");

		var rdy = vid.readyState;
		if (rdy >= '2')
		{
			clearTimeout(ready_state_interval);
			//var source = get_current_video_source("myVideo");
			$('.fduration').text(timeFormat(video[0].duration));
	        $('.fcurrent').text(timeFormat(0));
			$('.loading').fadeOut(500);
			$('.caption').fadeIn(500);
		    if( autoplay == 'true'){
		    	setTimeout(startBuffer, 10);
		    	$('.btnPlay').addClass('paused');
		    }
		    else
		    {
		    	$('.init').fadeIn(2500);
				$('.btnPlay').removeClass('paused');
		    }
			updateVolume(0, 0.7);
        }
    },1000);
      
    // initializing the player click function  
	$('.cont').on("click",function(){
		if( autoplay == '')
		{
			$('.init').hide();
			$('.btnPlay').addClass('paused');
			$(this).unbind('click');
			video[0].play();
			$('.buffer').show();
		    //start to get video buffering data 
		    setTimeout(startBuffer, 10);
		}   
	});

	//setting up flag variable true for controls hover 
	var cb_controls = false;
	$('.control').on("mouseenter",function(){
		cb_controls = true;
	});

	//setting up flag variable false for controls 
	$('.control').on("mouseleave",function(){
		cb_controls = false;
	});
	
	// on mouse stop on container, controls get hide after 5 secs 
	var lastTimeMouseMoved = "";
    $('.cont').mousemove(function(e){
   	 	caption_show();
       	lastTimeMouseMoved = new Date().getTime();
       	var t=setInterval(function(){
           	var currentTime_new = new Date().getTime();
           	if(currentTime_new - lastTimeMouseMoved > 5000 && !_pause && !cb_controls)
           	{
				caption_hide();
            }
       	},1000);
   	}); 

    // on hover controils and captions get showed
    
    var on_player = false;
	$('.cont').on("mouseenter",function(){
		on_player = true;
		caption_show();

	});

	// on hover controils and captions get showed
	$('.cont').on("mouseleave",function(){
		on_player = false;
		if (!_pause)
		caption_hide();
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

	/*
	* Changes made by *Saqib Razzaq*
	* checks if windows size is less than 400
	* if it is, hides volumebar and shows it when
	* you hover over vol icon hiding video time	once  
	* you move away from sound icon it hides itself showing video time
	*/

	if ($( window ).width() < 400)
        {
	$("#volSec").hide();
	$("#soundIcon").mouseenter(function(){
		$("#volSec").show();
		$(".fcurrent").hide();
	});
	$(".volume").mouseleave(function(){
		$("#volSec").hide();
		$(".fcurrent").show();
	});
	}

	/* 
	* ==================
	* volume changes end
	* ==================
	*/ 

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
			_pause = false;
			
		}
		else {

			$('.init').show();
			$('.btnPlay').removeClass('paused');
			video[0].pause();
			_pause = true;
		}
	};

    	 
    $( "#replay_v" ).click(function() {
        video[0].play();
        $('#opacity').hide();
		$('#related_1').hide();
		$('.control').show();
		$('.caption').show();
		$('#web').show();
		//showing pause icon by before video load
		$('.btnPlay').addClass('paused');

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
    
    isButtonFullscreen = true;
    if($.isFunction(container[0].webkitRequestFullScreen)) 
    {
    		if(isFullScreen)
    		{
    			isFullScreen = false;
				document.webkitCancelFullScreen();
    		}
             else
             {
             	isFullScreen = true;
             	container[0].webkitRequestFullScreen();	
             }
    
    }

    else if($.isFunction(container[0].mozRequestFullScreen)) 
    {
    		if(isFullScreen)
    		{
    			isFullScreen = false;
				document.mozCancelFullScreen();
    		}
             else
             {
             	isFullScreen = true;
             	container[0].mozRequestFullScreen();
             		
             }              
    }  
   
   
});


var _HD_flag = false;
//HD on/off button clicked
$(".hdon").on('click', function() {
$(this).toggleClass('hdoff');
    $('.myVideo').removeClass('init');
    $('source', '#myVideo').eq(1).prependTo('#myVideo');
    $('#myVideo')[0].load();
    $('#myVideo')[0].play();
    $('.init').hide();
    $('.btnPlay').addClass('paused');
    _HD_flag = true;
    
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
	video.on('seeked', function() {
       $('.loading').fadeOut(200);
		
	 });
	
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
if (enlarge_small == 'true')
{
$('.btmControl').append('<div class="smallscr largescr hbtn" id="largescr" title="Enlarge/Small Size"></div>');
$('#largescr').insertBefore("#fs");
}



//Large screen function
$(".largescr").click(function() {
	$(this).toggleClass('smallscr');
	if(!$(this).hasClass('smallscr')) 
	{
		$(".cb_player").animate({height:'+=220px',width:'+=390px'},"fast");
		$('.html5_player_enlarge').addClass('col-lg-12').removeClass('col-lg-8');
	}
	else
	{
		$(".cb_player").animate({height:'-=220px',width:'-=390px'},"fast");
		$('.html5_player_enlarge').removeClass('col-lg-12').addClass('col-lg-8');
	}
});


//Right Click Menu

$('#cont').append('<div id="rightcmenu"></div>');
//$('#rightcmenu').append('<span id="op">CB Html5 menu</span>');
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
   window.open(homepath, '_blank');
 });


$(".about").click(function(event) {
  window.open(homepath, '_blank');
          
});



$('.copy').click(function() {
alert(document.URL);
});




//Logo
$('.cb-playerLogo').append('<div id="path" class="path hbtn"  > </div>');

$('#path').prop("href",product_link);
// $("#path").insertAfter("#hd");
$('#path').css({
	            'backgroundImage': 'url(data:image/png;base64,' + webpath + ')',
				'margin-right':'7px',
				'margin-top':'0px',
				'background-repeat':'no-repeat',
				'background-position' : '100% 50%',
				
				
			});

  $("#path").click(function(event) {
  window.open(product_link, '_blank');
          
  });


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


// Setting in-video logo for player
if( iv_logo_enable == 'yes')
{
	$('.cont').append('<img id="web"  src=data:image/png;base64,'+ web +'> ');
	$('#web').css({
	            'top' :  $top,
	            'left' :  $left,
	            'bottom' : $bottom,
	            'right' :  $right  ,
				'position': 'absolute',
	            'width': '100px',
	            'height': '30px',
	            'z-index' : '-1'
	           });
}



  //For multiserver plugin videos :)
if(files)
{
     var toggle = false;
     var time_var = false;
     var start_time = 0;

     $('#res').on('click',function(event){
         if(toggle == false) 
			     {
			     	 $('.video_files').show(10);
			     	 toggle = true;
		         }
	     else
			     {
			     	 $('.video_files').hide(10);
			     	 toggle = false;
			     }
	     event.stopPropagation();
     });
     



     $('html').click(function() {
     	$('.video_files').hide(100);
     	toggle =false;
	});

     // All multiserver Video Files (Json) 
	var jsonData = JSON.parse(files);

    // cheking for if 360 resolution is not available 
	if(!jsonData["360"])
	{
	    video.attr('src',jsonData["240"]);
	    $('#li_240').addClass('selected_player');
	}
	else
    $('#li_360').addClass('selected_player');

	$.each(jsonData, function (key, data) {

		$('#li_' + key).on('mouseenter', function(){
        	$(this).css({'background-color':'#000'});
         });
        $('#li_' + key).on('mouseleave', function(){
        	$(this).css({'background-color':'#1D1D1D'});
         });
        $('#li_' + key).on('click', function(){
		//getting current time variable for video to play .. on change resolution and passing to loadmetadata	
	    start_time = video[0].currentTime;
        //Changing source attribute for the required resolution .. 
        console.log("current_video=>"+jsonData[key]);
        video.attr('src',jsonData[key]);
        load_meta_data(start_time,video);
        time_var = true;
		});
    });

	$('#ul_files .list_player').click(function() {
    $('#ul_files .list_player.selected_player').removeClass('selected_player');
    $(this).closest('li').addClass('selected_player');
    });


}



/** 
* For multiserver plugin videos  <<--END-->> :)
*/


//Time format converter - 00:00
	var timeFormat = function(seconds){
		var m = Math.floor(seconds/60)<10 ? "0"+Math.floor(seconds/60) : Math.floor(seconds/60);
		var s = Math.floor(seconds-(m*60))<10 ? "0"+Math.floor(seconds-(m*60)) : Math.floor(seconds-(m*60));
		return m+":"+s;
	};


/**  
* Following function is used to attach fullscreen events to document
*/
function attachEvents (){
   
   $( document ).on( 'fullscreenchange', toggleFullScreen );
   $( document ).on( 'webkitfullscreenchange', toggleFullScreen );
   $( document ).on( 'mozfullscreenchange', toggleFullScreen );
   $( document ).on( 'MSFullscreenchange', toggleFullScreen );
}



/**  
* Following events are used to check fullscreen events
*/
document.addEventListener("fullscreenchange", function (e) {
   toggleFullScreen(e);
}, false);
document.addEventListener("mozfullscreenchange", function (e) {
    toggleFullScreen(e);
}, false);
document.addEventListener("webkitfullscreenchange", function (e) {
    toggleFullScreen(e);
}, false);



/**  
* Following function is used to show controls on exit fullscreen 
*/
function showControl(){
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


/**  
* Following function is used to show controls on enter fullscreen 
*/
function hideControl()
{
	$('.caption').show();
    $(".largescr").show();
    $('.cb-item-title-container').css({'margin-top':0});
    $(".control").hover(
        function() {
            $(this).unbind('mouseenter').unbind('mouseleave');
        });
}


/**  
* Following function is used to call events on toggle screen 
*/
function toggleFullScreen (e) {

    isFullScreen = ( document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement );

	    if ( isFullScreen ) {
	    	showControl();
	    } else {
	    	hideControl();
	     	
	    }
}

  
});


/**
* caption hide 
*/
function caption_hide()
{
	$('.control').stop().animate({'bottom':-51}, 500);
	$('.caption').stop().animate({'top':-200}, 500);
}


/**
* caption hide 
*/
function caption_show()
{
	$('.control').stop().animate({'bottom':0}, 100);
	$('.caption').stop().animate({'top':-7}, 100);
}

function load_meta_data(start_time,video)
{
    	//before everything get started
    	video.on('loadedmetadata', function() {
	    video[0].currentTime = start_time;
		video[0].play();
	    //set video properties	
		$('.loading').fadeOut(500);
        $('.init').hide();
	   	   
		
    });
}

function get_current_video_source(object_id)
{
	var video = document.getElementById(object_id);
	var src = video.currentSrc;
	return src;	    
}


/******CAUTION*****\
DO NOT REMOVE THIS COMMENTED CODE 

/***website logo***\

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


//before everything get started
   /* video.on('loadedmetadata', function() {
	    if (time_var == true){
		    video[0].currentTime = start_time;
		    video[0].play();
	    }

	    //set video properties	
		$('.fcurrent').text(timeFormat(0));
		$('.fduration').text(timeFormat(video[0].duration));
		$('.caption').fadeIn(500);
		updateVolume(0, 0.7);
		$('.loading').fadeOut(500);
        if( autoplay == 'true' && !_HD_flag){
		    setTimeout(startBuffer, 10);
		    $('.btnPlay').addClass('paused');
	    }
        if(time_var  == true){
			$('.init').hide();
		}	
		else{

	   	    if( autoplay == '' && !_HD_flag){
				$('.init').fadeIn(2500);
				$('.btnPlay').removeClass('paused');
			}
	   	}    
		loadmetadata = false;
    });*/

  // On press space vidoe play/pasue
	/*var play = false;
	$(window).keypress(function(e) {
		e.preventDefault();
	  	if (e.keyCode == 0) 
	  	{
	  		console.log(e.keyCode);
		  	if (!play)
		  	{
		  		play = true;
		  		$('.init').show();
				$('.btnPlay').removeClass('paused');
				video[0].pause();
				_pause = true;
		  	}
		  	else
		  	{
		  		play = false;
		  		$('.init').hide();
				$('.btnPlay').addClass('paused');
				video[0].play();
				_pause = false;
		  	}
	    }
	});*/
