document.addEventListener( 'click', function( event ) {
	var link = event.target.closest( '.wpex-social-share__link' );

	if ( ! link ) {
		return;
	}

	var tUrl = '',
		list = link.closest( '.wpex-social-share' ),
		parent = link.parentNode,
		pcls = parent.classList,
		sTitle = list.dataset.title,
		sUrl = list.dataset.url,
		specs = list.dataset.specs,
		fTitle = link.dataset.title;

	switch( true ) {

		// Twitter
		case pcls.contains( 'wpex-twitter' ):
			if ( list.dataset.twitterTitle ) {
				sTitle = list.dataset.twitterTitle;
			}
			tUrl = 'https://twitter.com/intent/tweet?text=' + sTitle + '&url=' + sUrl;
			if ( list.dataset.twitterHandle ) {
				tUrl += '&via=' + list.dataset.twitterHandle;
			}
		break;

		// Facebook
		case pcls.contains( 'wpex-facebook' ):
			tUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + sUrl;
		break;

		// Pinterest
		case pcls.contains( 'wpex-pinterest' ):
			tUrl = 'https://www.pinterest.com/pin/create/button/?url=' + sUrl;
			if ( list.dataset.image ) {
				tUrl += '&media=' + list.dataset.image;
			}
			if ( list.dataset.summary ) {
				tUrl += '&description=' + list.dataset.summary;
			}
		break;

		// Linkedin
		case pcls.contains( 'wpex-linkedin' ):
			tUrl = 'https://www.linkedin.com/shareArticle?mini=true&url=' + sUrl + '&title=' + sTitle;
			if ( list.dataset.summary ) {
				tUrl += '&summary=' + list.dataset.summary;
			}
			if ( list.dataset.source ) {
				tUrl += '&source=' + list.dataset.source;
			}
		break;

		// Email
		case pcls.contains( 'wpex-email' ):
			tUrl = 'mailto:?subject=' + list.dataset.emailSubject + '&body=' + list.dataset.emailBody;
			window.location.href = tUrl;
			return;
		break;

		// Other
		default:
			tUrl = link.getAttribute( 'href' );
			specs = '';
		break;

	}

	if ( ! tUrl ) {
		return;
	}

	window.open(
		tUrl,
		fTitle,
		specs
	).focus();

	event.preventDefault();
	event.stopPropagation();

} );