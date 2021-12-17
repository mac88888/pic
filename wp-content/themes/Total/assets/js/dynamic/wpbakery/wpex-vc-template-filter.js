document.addEventListener( 'click', function( event ) {

	var target = event.target;

	if ( ! target.closest( '.wpex-vc-template-list__filter-button' ) ) {
		return;
	}

	event.preventDefault();

	target.setAttribute( 'aria-pressed', 'true' );

	var category = target.dataset.category;
	var filterLinks = target.parentElement.getElementsByTagName( 'a' );
	var templates = document.getElementsByClassName( 'wpex-vc-template-list__item' );

	for (var f = 0; f < filterLinks.length; f++) {
		filterLinks[f].setAttribute( 'aria-pressed', 'false' );
	}

	for (var t = 0; t < templates.length; t++) {
		var element = templates[t];
		element.classList.remove( 'wpex-vc-template-list__item--hidden' );
		if ( '*' !== category && category !== element.dataset.wpexCategory ) {
			element.classList.add( 'wpex-vc-template-list__item--hidden' );
		}
	}

} );