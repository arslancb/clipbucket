function LangChange( form ) {

	var newIndex = uichange.lang.selectedIndex;
    cururl = uichange.lang.options[ newIndex ].value;
    window.location.assign( cururl );

	}

function StyleChange( form ) {

	var newIndex = uichange.style.selectedIndex;
    cururl = uichange.style.options[ newIndex ].value;
    window.location.assign( cururl );

	}