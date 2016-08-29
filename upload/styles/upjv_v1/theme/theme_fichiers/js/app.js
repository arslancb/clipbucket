(function($){$(window).load(function(){
	// Onglets meta menu et onglets (tag specifique)
	$("#meta_menu_tabs").tabs({
		collapsible: true,
		active: false
	});
	$("div.onglets").tabs();
	Responsivr.run({
		small: {
			upTo: 560,
			enter: function() {
				$("#meta_menu_tabs").hide();
				$('[data-unfold-target="menu_principal"], [data-unfold-target="recherche_simple"]').hide();
			},
			exit: function() {
				$("#meta_menu_tabs").show();
				$('[data-unfold-target="menu_principal"], [data-unfold-target="recherche_simple"]').show();
			}
		},
		medium: {
			upTo: 880,
			enter: function() {
				$("#pied_page_rubriques [data-unfold-target]").hide();
			}
		}
	}, {
		alias: "large"
	}, function() {
//		$("#meta_menu_tabs").toggle(Responsivr.isNot("small"));
		
		if (Responsivr.is("medium") || Responsivr.is("small")) {
			$("#pied_page_rubriques [data-unfold-target]").hide();
		}
	});
	
	// Carrousel
	$(".cycle-slideshow").each(function() {
		var $carrousel = $(this); 
		$carrousel.find('.cycle-play-pause').on('click', function() {
			var event = $carrousel.is('.cycle-paused') === true ? 'resume' : 'pause';
			$carrousel.cycle(event);
		});
	});
	
	var changerDateArchivage = function(force) {
		var value = $("input#DATE_FIN_EVENEMENT").val();
		var dateArchivageActuelle = $("input#DATE_ARCHIVAGE").val();
		if (dateArchivageActuelle !== undefined && (dateArchivageActuelle.length == 0 || force)) {
			var d = value.substr(0, 2);
			var m = value.substr(3, 2) - 1;
			var y = value.substr(6, 4);
			var date = new Date(y, m, d);
			var oneDay = 3600 * 24 * 1000; //ms
			var dateArchivage = new Date(+date + oneDay);
			var dd = dateArchivage.getDate();
			var mm = dateArchivage.getMonth() + 1;
			var yy = dateArchivage.getFullYear();
			var dateArchivageString = (dd < 10 ? "0" : "") + dd + "/" + (mm < 10 ? "0" : "") + mm + "/" + yy;
			$("input#DATE_ARCHIVAGE").val(dateArchivageString);
		}
	};
	
	changerDateArchivage(false);	
	// Saisie front d'actu
	$("input#DATE_FIN_EVENEMENT").change(function() {
		changerDateArchivage(true);
	});
	
	// Liens → forcer l'ouverture dans un nouvel onglet + précision dans le [title]
	$('a[data-newtab]').each(function() {
		var title = $(this).attr('title') || '';
		if (title.length > 0) {
			title+= ' ';
		}
		title+= '(Nouvelle fenêtre)';
		$(this).attr('title', title);
		return this;
	}).on('click', function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		window.open(href, '_blank');
		return false;
	});
	
});})(jQuery);