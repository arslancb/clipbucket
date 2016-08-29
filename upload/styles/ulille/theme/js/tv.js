// Init Masonry
var container = document.querySelector('.grid');
var msnry = new Masonry( container, {
  // options
  columnWidth:'.grid-sizer',
  itemSelector: '.thumb',
  isFitWidth: true
});
// scroll to top by Codyhouse
jQuery(document).ready(function($){
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 150,
		//grab the "back to top" link
		$back_to_top = $('.back-top');

	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('back-top-on') : $back_to_top.removeClass('back-top-on back-top-fade');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('back-top-fade');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

});
/* center modal */
function centerModals($element) {
  var $modals;
  if ($element.length) {
    $modals = $element;
  } else {
    $modals = $('.modal-vcenter:visible');
  }
  $modals.each( function(i) {
    var $clone = $(this).clone().css('display', 'block').appendTo('body');
    var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
    top = top > 0 ? top : 0;
    $clone.remove();
    $(this).find('.modal-content').css("margin-top", top);
  });
}
$('.modal-vcenter').on('show.bs.modal', function(e) {
  centerModals($(this));
});
$(window).on('resize', centerModals);
// collapse infos
$(document).ready(function(){
	$('#collapseMetas').on('shown.bs.collapse', function () {
	  $("#extend-button").html('<span class="glyphicon glyphicon-triangle-top"></span> infos');
	});
	$('#collapseMetas').on('hidden.bs.collapse', function () {
	  $("#extend-button").html('<span class="glyphicon glyphicon-triangle-bottom"></span> infos');
	});
});