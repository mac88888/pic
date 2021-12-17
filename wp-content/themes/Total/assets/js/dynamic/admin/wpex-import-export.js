( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		$( '.wpex-theme-import-export__highlight' ).click( function( e ) {
			$( '.wpex-theme-import-export__settings' ).focus().select();
			return false;
		} );

		$( '.wpex-theme-import-export__delete' ).click( function( e ) {
			$( this ).hide();
			$( '.wpex-theme-import-export__warning, .wpex-theme-import-export__delete-cancel' ).show();
			$( '.wpex-theme-import-export__submit' ).val( wpex_import_export_l10n.confirmReset );
			$( '.wpex-theme-import-export__reset' ).val( '-1' );
			return false;
		} );

		$( '.wpex-theme-import-export__delete-cancel' ).click( function( e ) {
			$( this ).hide();
			$( '.wpex-theme-import-export__warning' ).hide();
			$( '.wpex-theme-import-export__delete' ).show();
			$( '.wpex-theme-import-export__submit' ).val( wpex_import_export_l10n.importOptions );
			$( '.wpex-theme-import-export__reset' ).val( '' );
			return false;
		} );

	} );

} ) ( jQuery );