<?php
/**
 * Schema markup.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.1.3
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs schema HTML for sections of the site.
 *
 * @since 3.0.0
 */
function wpex_schema_markup( $location ) {
	echo wpex_get_schema_markup( $location );
}

/**
 * Returns schema HTML for sections of the site.
 *
 * @since 3.0.0
 */
function wpex_get_schema_markup( $location ) {

	if ( ! get_theme_mod( 'schema_markup_enable', true ) ) {
		return null;
	}

	$schema = '';

	switch ( $location ) {
		case 'html':
			$schema = 'itemscope itemtype="https://schema.org/WebPage"';
			break;
		case 'header';
			$schema = 'itemscope="itemscope" itemtype="https://schema.org/WPHeader"';
			break;
		case 'site_navigation';
			$schema = 'itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement"';
			break;
		case 'sidebar':
			$schema = 'itemscope="itemscope" itemtype="https://schema.org/WPSideBar"';
			break;
		case 'footer':
			$schema = 'itemscope="itemscope" itemtype="https://schema.org/WPFooter"';
			break;
		case 'headline':
			$schema = 'itemprop="headline"';
			break;
		case 'entry_content':
			$schema = 'itemprop="text"';
			break;
		case 'publish_date':
			$schema = 'itemprop="datePublished" pubdate';
			break;
		case 'date_modified':
			$schema = 'itemprop="dateModified"';
			break;
		case 'author_name':
			$schema = 'itemprop="name"';
			break;
		case 'author_link':
			$schema = 'itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person"';
			break;
		case 'image':
			$schema = 'itemprop="image"';
			break;
	}

	$schema = apply_filters( 'wpex_get_schema_markup', $schema, $location );

	if ( ! empty(  $schema ) ) {
		return ' ' . wp_strip_all_tags( $schema ); // note wp_strip_all_tags trims the final output.
	}

}