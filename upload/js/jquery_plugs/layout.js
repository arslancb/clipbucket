var date_format = 'd-m-Y';
(function($){
	var initLayout = function() {
		var hash = window.location.hash.replace('#', '');
		var currentTab = $('ul.navigationTabs a')
							.bind('click', showTab)
							.filter('a[rel=' + hash + ']');
		if (currentTab.size() == 0) {
			currentTab = $('ul.navigationTabs a:first');
		}
		showTab.apply(currentTab.get(0));
		
		var now = new Date();
		$('.date_field').DatePicker({
			format:date_format,
			date: ['1960-01-01', now.getFullYear()-5+'-01-01'],
			current: '2008-07-31',
			starts: 1,
			calendars: 1,
			position: 'right',
			view: 'years',
			onBeforeShow: function(){
				$('.date_field').DatePickerSetDate($('.date_field').val(), true);
			},
			onChange: function(formated, dates){
				$('.date_field').val(formated);
				//if ($('#closeOnSelect input').attr('checked')) {
				//	$('.date_field').DatePickerHide();
				//}
			}
		});
		$('#widgetCalendar div.datepicker').css('position', 'absolute');
	};
	
	var showTab = function(e) {
		var tabIndex = $('ul.navigationTabs a')
							.removeClass('active')
							.index(this);
		$(this)
			.addClass('active')
			.blur();
		$('div.tab')
			.hide()
				.eq(tabIndex)
				.show();
	};
	
	EYE.register(initLayout, 'init');
})(jQuery)