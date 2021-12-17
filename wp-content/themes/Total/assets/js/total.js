/**
 * Project: Total WordPress Theme.
 *
 * @version 5.3
 * @license All rights reserved.
 */

var wpex = {};

( function( l10n ) {

	'use strict';

	wpex = {

		/**
		 * Main init function.
		 */
		init: function() {
			this.config();
			this.replaceNoJsClass();
			this.bindEvents();
		},

		/**
		 * Define vars for caching.
		 */
		config: function() {
			this.config = {
				localScrollOffset: 0,
				localScrollSections: [],
				passiveListeners: this.passiveListenersSupport()
			};
		},

		/**
		 * Replaces the "wpex-no-js" body class with "wpex-js".
		 */
		replaceNoJsClass: function() {
			var bodyClass = document.body.className;
			bodyClass = bodyClass.replace(/wpex-no-js/, 'wpex-js');
			document.body.className = bodyClass;
		},

		/**
		 * Bind Events.
		 */
		bindEvents: function() {
			var self = this;

			/*** Fires as soon as the document is loaded ***/
			self.domReady( function() {

				document.body.classList.add( 'wpex-docready' );

				if ( self.retinaCheck() ) {
					document.body.classList.add( 'wpex-is-retina' );
				}

				if ( self.mobileCheck() ) {
					document.body.classList.add( 'wpex-is-mobile-device' );
				}

				self.localScrollSections();
				self.megaMenuAddClasses();
				self.superfish();
				self.dropdownMenuOnclick();
				self.dropdownMenuTouch();
				self.mobileMenu();
				self.hideEditLink();
				self.menuWidgetAccordion();
				self.inlineHeaderLogo();
				self.menuSearch();
				self.menuCart();
				self.skipToContent();
				self.backTopLink();
				self.goBackButton();
				self.smoothCommentScroll();
				self.toggleElements();
				self.toggleBar();
				self.localScrollLinks();
				self.customSelects();
				self.lightbox();
				self.masonryGrids();
				self.hoverStyles();
				self.overlaysMobileSupport();
				self.accessability();

			} );

			/*** Fires once the window has been loaded ***/
			window.addEventListener( 'load', function() {

				document.body.classList.add( 'wpex-window-loaded' );

				// Main.
				self.megaMenusWidth();
				self.megaMenusTop();
				self.parallax();
				self.stickyTopBar();
				self.vcTabsTogglesJS();
				self.headerOverlayOffset(); // Add before sticky header ( important ).
				self.equalHeights();
				self.localScrollHighlight();

				// Sticky functions.
				self.stickyHeader();
				self.stickyHeaderMenu();

				// Set localScrollOffset after site is loaded to make sure it includes dynamic items including sticky elements.
				self.parseLocalScrollOffset( 'init' );

				// Run methods after sticky header.
				self.footerReveal(); // Footer Reveal => Must run before fixed footer!!!
				self.fixedFooter(); // Fixed Footer => Must run after footerReveal!!!

				// Scroll to hash (must be last).
				if ( l10n.scrollToHash ) {
					window.setTimeout( function() {
						self.scrollToHash( self );
					}, parseInt( l10n.scrollToHashTimeout ) );
				}

			} );

			/*** Fires on window resize ***/
			window.addEventListener( 'resize', function() {
				self.parseLocalScrollOffset( 'resize' ); //update offsets incase elements changed size.
			} );

			/* Fires on Orientation Change - @deprecated 5.3
			window.addEventListener( 'orientationchange', function() {
				self.masonryGrids();
			} );
			*/

		},

		/**
		 * Superfish menus.
		 */
		superfish: function() {

			if ( 'function' !== typeof jQuery || 'function' !== typeof jQuery.fn.superfish ) {
				return;
			}

			jQuery( '#site-navigation ul.sf-menu' ).superfish( {
				delay: wpex_superfish_params.delay,
				speed: wpex_superfish_params.speed,
				speedOut: wpex_superfish_params.speedOut,
				cssArrows: false,
				disableHI: false,
				animation: {
					opacity: 'show'
				},
				animationOut: {
					opacity: 'hide'
				}
			} );

		},

		/**
		 * Onclick dropdown menu.
		 */
		dropdownMenuOnclick: function() {

			/*
			// @todo should we implement this?
			document.querySelectorAll( '.wpex-dropdown-menu--onclick .menu-item-has-children > a' ).forEach( function( element ) {
				if ( element.closest( '.megamenu__inner-ul' ) ) {
					return;
				}
				element.setAttribute( 'aria-expanded', 'false' );
				if ( l10n.dropdownMenuAriaLabel ) {
					var ariaLabel = l10n.dropdownMenuAriaLabel;
					element.setAttribute( 'aria-label', ariaLabel.replace( '%s', element.textContent.trim() ) );
				}
			} );*/

			document.addEventListener( 'click', function( event ) {

				var target = event.target;

				if ( ! target.closest( '.wpex-dropdown-menu--onclick .menu-item-has-children > a' ) ) {
					document.querySelectorAll( '.wpex-dropdown-menu--onclick .menu-item-has-children' ).forEach( function( element ) {
						element.classList.remove( 'wpex-active' );
						//element.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
					} );
					return;
				}

				document.querySelectorAll( '.wpex-dropdown-menu--onclick .menu-item-has-children' ).forEach( function( element ) {
					if ( ! element.contains( target ) ) {
						element.classList.remove( 'wpex-active' );
						//element.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
					}
				} );

				var li = target.closest( '.menu-item-has-children' );
				var a = target.closest( 'a' );

				if ( li.classList.contains( 'wpex-active' ) ) {
					li.classList.remove( 'wpex-active' );
					//a.setAttribute( 'aria-expanded', 'false' );
					if ( '#' === a.getAttribute( 'href' ) ) {
						event.preventDefault();
					}
				} else {
					li.classList.add( 'wpex-active' );
					//a.setAttribute( 'aria-expanded', 'true' );
					event.preventDefault();
				}

			} );

			document.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				var target = event.target.closest( '.wpex-dropdown-menu--onclick .menu-item-has-children.wpex-active' );
				if ( 27 !== code || ! target ) {
					return;
				}
				target.classList.remove( 'wpex-active' );
				//target.querySelector( 'a' ).setAttribute( 'aria-expanded', 'false' );
			} );

			document.querySelectorAll( '.wpex-dropdown-menu--onclick .sub-menu' ).forEach( function( dropdown ) {
				dropdown.addEventListener( 'keydown', function( event ) {
					var code = event.keyCode || event.which;
					if ( 27 === code ) {
						var closestParent = event.target.closest( '.menu-item-has-children.wpex-active' );
						if ( closestParent ) {
							closestParent.classList.remove( 'wpex-active' );
							var closestParentA = closestParent.querySelector( 'a' );
							//closestParentA.setAttribute( 'aria-expanded', 'false' );
							closestParentA.focus();
							event.stopPropagation();
						}
					}
				} );
			} );

		},

		/*
		 * Dropdown menu touch support.
		 */
		dropdownMenuTouch: function() {

			var self = this;
			var dragging = false;

			document.querySelectorAll( '.wpex-dropdown-menu--onhover .menu-item-has-children > a' ).forEach( function( element ) {
				element.addEventListener( 'touchend', function( event ) {
					if ( dragging ) {
						// hide dropdowns here?
						return;
					}

					var parent = element.closest( '.menu-item-has-children' );

					if ( parent.classList.contains( 'wpex-touched' ) ) {
						return; // already open allow clicking.
					}

					event.preventDefault();

					parent.classList.add( 'wpex-touched' );

				} );

				element.addEventListener( 'touchmove', function( event ) {
					dragging = true;
				}, self.config.passiveListeners ? { passive: true } : false );

				element.addEventListener( 'touchstart', function( event ) {
					dragging = false;
				}, self.config.passiveListeners ? { passive: true } : false );

			} );

			var clickingOutside = function( event ) {
				var target = event.target;
				// not this code will only run if menus are open so it's efficient.
				document.querySelectorAll( '.menu-item-has-children.wpex-touched' ).forEach( function( element ) {
					if ( element.contains( target ) ) {
						return;
					}
					element.classList.remove( 'wpex-touched' );
				} );
			};

			document.addEventListener( 'touchstart', clickingOutside, self.config.passiveListeners ? { passive: true } : false );
			document.addEventListener( 'touchmove', clickingOutside, self.config.passiveListeners ? { passive: true } : false );

		},

		/**
		 * Add "megamenu__inner-ul" classname to megamenu inner UL elements.
		 */
		megaMenuAddClasses: function() {
			document.querySelectorAll( '.main-navigation-ul .megamenu > .sub-menu' ).forEach( function( element ) {
				element.querySelectorAll( '.sub-menu' ).forEach( function( element ) {
					element.classList.add( 'megamenu__inner-ul' );
				} );
			} );
		},

		/**
		 * MegaMenus Width.
		 *
		 * @todo Instead of listening on resize perhaps we should run when resize ends.
		 *       since usually dropdowns won't be open while resizing.
		 */
		megaMenusWidth: function() {
			var navWrap = document.querySelector( '#site-navigation-wrap.wpex-stretch-megamenus' );

			if ( ! this.isVisible( navWrap ) ) {
				return;
			}

			var megaMenus = navWrap.querySelectorAll( '.megamenu > ul' );

			if ( ! megaMenus.length ) {
				return;
			}

			function setWidths() {
				var containerWidth = document.querySelector( '#site-header.header-one .container' ).getBoundingClientRect().width;
				var navWidth = navWrap.getBoundingClientRect().width;
				var navPos = parseInt( window.getComputedStyle( navWrap ).right ) || 0;
				megaMenus.forEach( function( element ) {
					element.style.width = containerWidth + 'px';
					element.style.marginLeft = -(containerWidth-navWidth-navPos) + 'px';
				} );
			} setWidths();

			window.addEventListener( 'resize', setWidths );
			window.addEventListener( 'orientationchange', setWidths ); // @todo deprecate?

		},

		/**
		 * MegaMenus Top Position.
		 */
		megaMenusTop: function() {
			var self = this;
			var navWrap = document.querySelector( '#site-navigation-wrap.wpex-stretch-megamenus:not(.wpex-flush-dropdowns)' );

			if ( ! this.isVisible( navWrap ) ) {
				return;
			}

			var megaMenus = navWrap.querySelectorAll( '.megamenu > ul');

			if ( ! megaMenus ) {
				return;
			}

			var header = document.querySelector( '#site-header.header-one' );

			function setTop() {
				if ( ! self.isVisible( navWrap ) ) {
					return;
				}
				var navHeight = navWrap.getBoundingClientRect().height;
				var topVal = ( ( header.getBoundingClientRect().height - navHeight ) / 2 ) + navHeight;
				megaMenus.forEach( function( element ) {
					element.style.top = topVal + 'px';
				} );
			} setTop();

			window.addEventListener( 'scroll', setTop, self.config.passiveListeners ? { passive: true } : false );
			window.addEventListener( 'resize', setTop );

			var menuItems = navWrap.querySelectorAll( '.megamenu > a' );
			menuItems.forEach( function( element ) {
				element.addEventListener( 'mouseenter', setTop, false );
			} );

		},

		/**
		 * Mobile Menu.
		 */
		mobileMenu: function() {
			switch ( l10n.mobileMenuStyle ) {
				case 'sidr':
					this.mobileMenuSidr();
					break;
				case 'toggle':
					this.mobileMenuToggle();
					break;
				case 'full_screen':
					this.mobileMenuFullScreen();
					break;
			}
		},

		/**
		 * Mobile Menu.
		 */
		mobileMenuSidr: function() {

			if ( 'undefined' === typeof l10n.sidrSource || 'undefined' === typeof window.sidr ) {
				return;
			}

			var sidrPlugin = window.sidr;
			var self = this;
			var body = document.body;
			var toggleBtn = document.querySelector( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' );

			// Add dark overlay to content.
			var sidrOverlay = document.createElement( 'div' );
			sidrOverlay.className = 'wpex-sidr-overlay wpex-hidden';
			body.appendChild( sidrOverlay );

			// Create sidr Element.
			sidrPlugin.new( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a', {
				name: 'sidr-main',
				source: l10n.sidrSource,
				side: l10n.sidrSide,
				timing: 'ease-in-out',
				displace: l10n.sidrDisplace,
				speed: parseInt( l10n.sidrSpeed ),
				renaming: true,
				bind: 'click',
				onOpen: function() {

					// Update toggle btn attributes.
					if ( toggleBtn ) {
						toggleBtn.setAttribute( 'aria-expanded', 'true' );
						toggleBtn.classList.add( 'wpex-active' );
					}

					// Prevent body scroll.
					if ( l10n.sidrBodyNoScroll ) {
						body.classList.add( 'wpex-noscroll' );
					}

					// Show Overlay.
					if ( sidrOverlay ) {
						sidrOverlay.classList.remove( 'wpex-hidden' );
						sidrOverlay.classList.add( 'wpex-custom-cursor' );
					}

					// Set focus styles.
					self.focusOnElement( document.querySelector( '#sidr-main' ) );

				},
				onClose: function() {

					// Update toggle btn attributes.
					if ( toggleBtn ) {
						toggleBtn.setAttribute( 'aria-expanded', 'false' );
						toggleBtn.classList.remove( 'wpex-active' );
					}

					// Remove body noscroll class.
					if ( l10n.sidrBodyNoScroll ) {
						body.classList.remove( 'wpex-noscroll' );
					}

					// Hide overlay.
					if ( sidrOverlay ) {
						sidrOverlay.classList.remove( 'wpex-custom-cursor' );
						sidrOverlay.classList.add( 'wpex-hidden' );
					}

				},
				onCloseEnd: function() {

					// Close active dropdowns.
					document.querySelectorAll( '.sidr-class-menu-item-has-children.active' ).forEach( function( element ) {
						element.classList.remove( 'active' );
						var ul = element.querySelector( 'ul' );
						if ( ul ) {
							ul.style.display = '';
						}
						var link = element.querySelector( 'a' );
						if ( link ) {
							var toggle = link.querySelector( '.wpex-open-submenu' );
							if ( toggle ) {
								toggle.setAttribute( 'aria-label', l10n.i18n.openSubmenu.replace( '%s', link.textContent.trim() ) );
								toggle.setAttribute( 'aria-expanded', 'false' );
							}
						}
					} );

					// Re-trigger stretched rows to prevent issues if browser was resized while
					// sidr was open.
					if ( l10n.sidrDisplace && 'function' === typeof window.vc_rowBehaviour ) {
						setTimeout( window.vc_rowBehaviour );
					}

				}

			} );

			// Cache sidr element.
			var sidr = document.querySelector( '#sidr-main' );
			var sidrInner = sidr.querySelector( '.sidr-inner' );

			// Add extra classes to the sidr element.
			sidr.classList.add( 'wpex-mobile-menu' );

			// Insert mobile menu close button.
			// @todo insert using PHP so it can be modified?
			var sidrCloseBtnEl = document.createElement( 'div' );
			sidrCloseBtnEl.className = 'sidr-class-wpex-close';

			var sidrCloseBtnA = document.createElement( 'a' );
			sidrCloseBtnA.href = '#';
			sidrCloseBtnA.setAttribute( 'role', 'button' );
			sidrCloseBtnEl.appendChild( sidrCloseBtnA );

			var sidrClosebtnIcon = document.createElement( 'span' );
			sidrClosebtnIcon.className = 'sidr-class-wpex-close__icon';
			sidrClosebtnIcon.setAttribute( 'aria-hidden', 'true' );
			sidrClosebtnIcon.innerHTML = '&times;';
			sidrCloseBtnA.appendChild( sidrClosebtnIcon );

			var sidrCloseBtnScreenReaderText = document.createElement( 'span' );
			sidrCloseBtnScreenReaderText.className = 'screen-reader-text';
			sidrCloseBtnScreenReaderText.textContent = l10n.mobileMenuCloseAriaLabel;
			sidrCloseBtnA.appendChild( sidrCloseBtnScreenReaderText );

			// Insert close button.
			sidr.insertBefore( sidrCloseBtnEl, sidr.firstChild );

			// Insert mobile menu extras.
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-top' ), sidrInner, 'prepend' );
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-bottom' ), sidrInner, 'append' );

			// Make sure dropdown-menu is included in sidr-main
			// which may not be included in certain header styles like dev header style.
			var sidrNavUl = sidr.querySelector( '.sidr-class-main-navigation-ul' );
			if ( sidrNavUl ) {
				sidrNavUl.classList.add( 'sidr-class-dropdown-menu' );
			}

			// Create menuAccordion.
			self.menuAccordion( document.querySelector( '#sidr-main .sidr-class-dropdown-menu' ) );

			// Remove sidr-class prefix from certain elements.
			// @todo can we optimize this? Perhaps we disable "renaming" in sidr and then do our own looping?
			self.removeClassPrefix(
				sidr.querySelectorAll( '[class*="sidr-class-fa"]' ),
				/^sidr-class-fa/,
				'sidr-class-'
			);
			self.removeClassPrefix(
				sidr.querySelectorAll( '[class*="sidr-class-ticon"]' ),
				/^sidr-class-ticon/,
				'sidr-class-'
			);
			self.removeClassPrefix(
				sidr.querySelectorAll( '[class^=sidr-class-wpex-cart-link]' ),
				/^sidr-class-wpex/,
				'sidr-class-'
			);
			self.removeClassPrefix(
				sidr.querySelectorAll( '.sidr-class-screen-reader-text' ),
				/^sidr-class-screen-reader-text/,
				'sidr-class-'
			);

			/*** Sidr - bind events ***/

			// Sidr close button
			document.addEventListener( 'click', function( event ) {
				if ( ! event.target.closest( '.sidr-class-wpex-close' ) ) {
					return;
				}
				event.preventDefault();
				sidrPlugin.close( 'sidr-main' );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
			} );

			// Close on resize past mobile menu breakpoint.
			window.addEventListener( 'resize', function() {
				if ( self.viewportWidth() >= l10n.mobileMenuBreakpoint ) {
					sidrPlugin.close( 'sidr-main' );
				}
			} );

			// Scroll to local links.
			if ( self.config.localScrollSections ) {
				document.addEventListener( 'click', function( event ) {
					var localScrollItem = event.target.closest( 'li.sidr-class-local-scroll > a' );
					if ( ! localScrollItem ) {
						return;
					}
					var hash = localScrollItem.hash;
					if ( hash && -1 !== self.config.localScrollSections.indexOf( hash ) ) {
						sidrPlugin.close( 'sidr-main' );
						self.scrollTo( hash );
						event.preventDefault();
						event.stopPropagation();
					}
				} );
			}

			// Close sidr when clicking on overlay.
			sidrOverlay.addEventListener( 'click', function( event ) {
				sidrPlugin.close( 'sidr-main' );
				event.preventDefault();
			} );

			// Close when clicking esc.
			sidr.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code ) {
					sidrPlugin.close( 'sidr-main' );
					if ( toggleBtn ) {
						toggleBtn.focus();
					}
				}
			} );

			// Remove mobile menu alt to clean up DOM.
			var mobileMenuAlt = document.querySelector( '#mobile-menu-alternative' );
			if ( mobileMenuAlt ) {
				mobileMenuAlt.parentNode.removeChild( mobileMenuAlt );
			}

		},

		/**
		 * Toggle Mobile Menu.
		 *
		 */
		mobileMenuToggle: function() {
			var self = this;
			var position = l10n.mobileToggleMenuPosition;
			var appendTo;
			var insertAfter;
			var mobileMenuContents = '';

			// Grab all content from menu and add into mobile-toggle-nav element.
			var mobileAlt = document.querySelector( '#mobile-menu-alternative' );
			if ( mobileAlt ) {
				var mobileAltMenu = mobileAlt.querySelector( '.dropdown-menu' );
				if ( mobileAltMenu ) {
					mobileMenuContents = mobileAltMenu.innerHTML;
				}
				mobileAlt.parentNode.removeChild( mobileAlt );
			}

			if ( ! mobileMenuContents ) {
				var navMenuUl = document.querySelector( '.main-navigation-ul' );
				if ( navMenuUl ) {
					mobileMenuContents = navMenuUl.innerHTML;
				}
			}

			// Create toggle Nav element.
			var mobileToggleNav = document.createElement( 'nav' );
				mobileToggleNav.className = 'mobile-toggle-nav wpex-mobile-menu wpex-clr wpex-togglep-' + position;
				mobileToggleNav.setAttribute( 'aria-label', l10n.mobileMenuAriaLabel );

			// Define appendTo or insertAfter els.
			if ( 'fixed_top' === l10n.mobileMenuToggleStyle ) {
				appendTo = document.querySelector( '#wpex-mobile-menu-fixed-top' );
				if ( appendTo ) {
					appendTo.appendChild( mobileToggleNav );
				}
			} else if ( 'absolute' === position ) {
				if ( 'navbar' === l10n.mobileMenuToggleStyle ) {
					appendTo = document.querySelector( '#wpex-mobile-menu-navbar' );
				} else {
					appendTo = document.querySelector( '#site-header' );
				}
			} else if ( 'afterself' === position ) {
				insertAfter = document.querySelector( '#wpex-mobile-menu-navbar' );
			} else {
				insertAfter = document.querySelector( '#site-header' );
			}

			// Insert the nav.
			if ( appendTo ) {
				appendTo.appendChild( mobileToggleNav );
			} else if ( insertAfter ) {
				self.insertAfter( mobileToggleNav, insertAfter );
			}

			// Add menu items to menu.
			var mobileToggleNavInner = document.createElement( 'div' );
				mobileToggleNavInner.className = 'mobile-toggle-nav-inner container';
			var mobileToggleNavUl = document.createElement( 'ul' );
				mobileToggleNavUl.className = 'mobile-toggle-nav-ul';

			mobileToggleNavUl.innerHTML = mobileMenuContents;
			mobileToggleNavInner.appendChild( mobileToggleNavUl );
			mobileToggleNav.appendChild( mobileToggleNavInner );

			// Remove all element styles and -1 tab index.
			document.querySelectorAll( '.mobile-toggle-nav-ul, .mobile-toggle-nav-ul *' ).forEach( function( element ) {
				element.removeAttribute( 'style' );
				element.removeAttribute( 'id' ); // prevent seo/accessability issues.
			} );

			// Add search to toggle menu.
			var mobileSearch = document.querySelector( '#mobile-menu-search' );
			if ( mobileSearch) {
				var mobileSearchDiv = document.createElement( 'div' );
					mobileSearchDiv.className = 'mobile-toggle-nav-search';
				mobileToggleNavInner.appendChild( mobileSearchDiv );
				mobileSearchDiv.appendChild( mobileSearch );
				mobileSearch.classList.remove( 'wpex-hidden' );
			}

			// Insert mobile menu extras.
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-top' ), mobileToggleNavInner, 'prepend' );
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-bottom' ), mobileToggleNavInner, 'append' );

			// Create menuAccordion.
			self.menuAccordion( mobileToggleNav );

			// Cache toggle button to use below.
			var toggleBtn = document.querySelector( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' );

			// On Show.
			function openToggle() {
				if ( l10n.animateMobileToggle ) {
					self.slideDown( mobileToggleNav, 300, function() {
						self.focusOnElement( mobileToggleNav );
						mobileToggleNav.classList.add( 'visible' );
					} );
				} else {
					mobileToggleNav.classList.add( 'visible' );
					self.focusOnElement( mobileToggleNav );
				}
				if ( toggleBtn ) {
					toggleBtn.classList.add( 'wpex-active' );
					toggleBtn.setAttribute( 'aria-expanded', 'true' );
				}
			}

			// On Close.
			function closeToggle() {
				if ( l10n.animateMobileToggle ) {
					self.slideUp( mobileToggleNav, 300, function() {
						mobileToggleNav.classList.remove( 'visible' );
					} );
				} else {
					mobileToggleNav.classList.remove( 'visible' );
				}
				mobileToggleNav.querySelectorAll( 'li.active > ul' ).forEach( function( element ) {
					self.slideUp( element );
				} );
				mobileToggleNav.querySelectorAll( '.active' ).forEach( function( element ) {
					element.classList.remove( 'active' );
				} );
				if ( toggleBtn ) {
					toggleBtn.classList.remove( 'wpex-active' );
					toggleBtn.setAttribute( 'aria-expanded', 'false' );
				}
			}

			// Show/Hide.
			document.addEventListener( 'click', function() {
				var button = event.target.closest( '.mobile-menu-toggle' );
				if ( ! button ) {
					if ( mobileToggleNav.classList.contains( 'visible' ) && ! event.target.closest( '.mobile-toggle-nav' ) ) {
						closeToggle();
					}
					return;
				}

				event.preventDefault();

				if ( mobileToggleNav.classList.contains( 'wpex-transitioning' ) ) {
					return;
				}

				if ( mobileToggleNav.classList.contains( 'visible' ) ) {
					closeToggle();
				} else {
					openToggle();
				}

			} );

			// Close when clicking esc.
			mobileToggleNav.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( mobileToggleNav.classList.contains( 'visible' ) && 27 === code ) {
					closeToggle();
					if ( toggleBtn ) {
						toggleBtn.focus();
					}
				}
			} );

			// Close on resize past mobile menu breakpoint.
			window.addEventListener( 'resize', function() {
				if ( mobileToggleNav.classList.contains( 'visible' ) && self.viewportWidth() >= l10n.mobileMenuBreakpoint ) {
					closeToggle();
				}
			} );

		},

		/**
		 * Overlay Mobile Menu.
		 */
		mobileMenuFullScreen: function() {
			var self = this;
			var menuHTML = null;
			var toggleBtn = document.querySelector( '.mobile-menu-toggle' );

			// Get menu contents
			var mobileAlt = document.querySelector( '#mobile-menu-alternative' );
			if ( mobileAlt ) {
				menuHTML = mobileAlt.innerHTML;
				mobileAlt.parentNode.removeChild( mobileAlt );
			} else {
				var mainMenu = document.querySelector( '#site-navigation .main-navigation-ul' );
				if ( mainMenu ) {
					menuHTML = mainMenu.innerHTML;
				}
			}

			if ( ! menuHTML ) {
				return;
			}

			// Create nav elements.
			var nav = document.createElement( 'div' );
				nav.className = 'full-screen-overlay-nav wpex-mobile-menu wpex-clr';
				if ( l10n.fullScreenMobileMenuStyle ) {
					nav.classList.add( l10n.fullScreenMobileMenuStyle );
				}
				nav.setAttribute( 'aria-expanded', 'false' );
			document.body.appendChild( nav );

			var navCloseButton = document.createElement( 'button' );
			navCloseButton.className = 'full-screen-overlay-nav-close';
			nav.appendChild( navCloseButton );

			var navCloseButtonIcon = document.createElement( 'span' );
			navCloseButtonIcon.className = 'full-screen-overlay-nav-close__icon';
			navCloseButtonIcon.innerHTML = '&times;';
			navCloseButtonIcon.setAttribute( 'aria-hidden', 'true' );
			navCloseButton.appendChild( navCloseButtonIcon );

			var navCloseButtonScreenText = document.createElement( 'span' );
			navCloseButtonScreenText.className = 'screen-reader-text';
			navCloseButtonScreenText.textContent = l10n.mobileMenuCloseAriaLabel;
			navCloseButton.appendChild( navCloseButtonScreenText );

			var navContent = document.createElement( 'div' );
				navContent.className = 'full-screen-overlay-nav-content';
			nav.appendChild( navContent );

			var navContentInner = document.createElement( 'div' );
				navContentInner.className = 'full-screen-overlay-nav-content-inner';
			navContent.appendChild( navContentInner );

			var navMenu = document.createElement( 'nav' );
				navMenu.className = 'full-screen-overlay-nav-menu';
			navContentInner.appendChild( navMenu );

			var navMenuUl = document.createElement( 'ul' );
				navMenu.appendChild( navMenuUl );

			navMenuUl.innerHTML = menuHTML;

			// Remove all styles.
			document.querySelectorAll( '.full-screen-overlay-nav, .full-screen-overlay-nav *' ).forEach( function( element ) {
				element.removeAttribute( 'style' );
				element.removeAttribute( 'id' );
			} );

			// Add mobile menu extras.
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-top' ), navContentInner, 'prepend' );
			self.insertExtras( document.querySelector( '.wpex-mobile-menu-bottom' ), navContentInner, 'append' );

			// Add search to toggle menu.
			var mobileSearch = document.querySelector( '#mobile-menu-search' );
			if ( mobileSearch ) {
				var searchLi = document.createElement( 'li' );
					searchLi.className = 'wpex-search';
				navMenuUl.appendChild( searchLi );
				searchLi.appendChild( mobileSearch );
				mobileSearch.classList.remove( 'wpex-hidden' );
			}

			var isAnimating = false;

			// Add dropdown toggles.
			document.addEventListener( 'click', function( event ) {

				// Lets make sure we are clicking the correct links.
				var target = event.target.closest( '.full-screen-overlay-nav-menu li.menu-item-has-children > a' );

				if ( ! target ) {
					return;
				}

				var parent = target.parentNode; // get the li element.

				if ( parent.classList.contains( 'local-scroll' ) ) {
					return;
				}

				if ( isAnimating ) {
					event.preventDefault();
					event.stopPropagation();
					return; // prevent click spam.
				}

				// Hide current element dropdowns only and allow clicking through to the link.
				if ( parent.classList.contains( 'wpex-active' ) ) {
					parent.classList.remove( 'wpex-active' );

					parent.querySelectorAll( 'li' ).forEach( function( element ) {
						element.classList.remove( 'wpex-active' ); // remove all active classes.
					} );

					parent.querySelectorAll( 'ul' ).forEach( function( element ) {
						isAnimating = true;
						self.slideUp( element, 300, function() {
							isAnimating = false;
						} );
					} );

					if ( parent.classList.contains( 'nav-no-click' ) ) {
						event.preventDefault();
						event.stopPropagation();
					}

				}
				// Show current element dropdown and hide all others.
				else {

					// Hide all other elements to create an accordion style toggle.
					nav.querySelectorAll( '.menu-item-has-children' ).forEach( function( element ) {
						if ( element.contains( target ) || ! element.classList.contains( 'wpex-active' ) ) {
							return;
						}
						var dropdown = element.querySelector( ':scope > ul' );
						if ( dropdown ) {
							element.classList.remove( 'wpex-active' ); // remove all active classes.
							isAnimating = true;
							self.slideUp( dropdown, 300, function() {
								isAnimating = false;
							} );
						}
					} );

					// Open dropdown.
					parent.classList.add( 'wpex-active' );
					isAnimating = true;
					self.slideDown( parent.querySelector( ':scope > ul' ), 300, function() {
						isAnimating = false;
					} );

					event.preventDefault();
					event.stopPropagation();

				}

			} );

			// Hide nav when clicking local scroll links.
			document.addEventListener( 'click', function( event ) {
				var localScrollItem = event.target.closest( '.full-screen-overlay-nav-menu .local-scroll > a' );
				if ( ! localScrollItem ) {
					return;
				}
				var hash = localScrollItem.hash;
				if ( hash && -1 !== self.config.localScrollSections.indexOf( hash ) ) {
					closeNav();
					event.preventDefault();
					event.stopPropagation();
				}
			} );

			// Show.
			toggleBtn.addEventListener( 'click', function( event ) {
				var button = event.target.closest( '.mobile-menu-toggle' );
				if ( ! button ) {
					return;
				}
				if ( nav.classList.contains( 'visible' ) ) {
					closeNav();
				} else {
					openNav();
				}
				event.preventDefault();
				event.stopPropagation();
			} );

			// Hide when clicking close button.
			document.addEventListener( 'click', function( event ) {
				if ( ! event.target.closest( '.full-screen-overlay-nav-close' ) ) {
					return;
				}
				closeNav();
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				event.preventDefault();
				event.stopPropagation();
			} );

			// Close when clicking esc.
			nav.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( nav.classList.contains( 'visible' ) && 27 === code ) {
					closeNav();
					if ( toggleBtn ) {
						toggleBtn.focus();
					}
				}
			} );

			// Open nav
			function openNav() {
				nav.classList.add( 'visible' );
				nav.setAttribute( 'aria-expanded', 'true' );

				if ( toggleBtn ) {
					toggleBtn.setAttribute( 'aria-expanded', 'true' );
				}

				// Add no scroll class to the body element.
				document.body.classList.add( 'wpex-noscroll' );

				var focus = function( event ) {
					self.focusOnElement( nav );
					nav.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the menu when opening after transition is complete to prevent issues.
				nav.addEventListener( 'transitionend', focus );

			}

			// Close nav
			function closeNav() {
				nav.classList.remove( 'visible' );
				nav.setAttribute( 'aria-expanded', 'false' );
				if ( toggleBtn ) {
					toggleBtn.setAttribute( 'aria-expanded', 'false' );
				}
				nav.querySelectorAll( '.wpex-active' ).forEach( function( element ) {
					element.classList.remove( 'wpex-active' );
					var drops = element.querySelector( ':scope > ul' );
					if ( drops ) {
						drops.style.display = 'none'; // no need to animate when closing.
					}
				} );
				document.body.classList.remove( 'wpex-noscroll' );
			}

		},

		/**
		 * Header Search.
		 */
		menuSearch: function() {
			var searchWrap = document.querySelector( '.header-searchform-wrap' );

			if ( ! searchWrap ) {
				return;
			}

			var searchInput = searchWrap.querySelector( 'input[type="search"]' );

			if ( ! searchInput ) {
				return;
			}

			if ( searchWrap ) {
				if ( searchWrap.dataset.placeholder ) {
					searchInput.setAttribute( 'placeholder', searchWrap.dataset.placeholder );
				}
				if ( searchWrap.dataset.disableAutocomplete ) {
					searchInput.setAttribute( 'autocomplete', 'off' );
				}
			}

			this.menuSearchDropdown();
			this.menuSearchOverlay();
			this.menuSearchHeaderReplace();

		},

		/**
		 * Dropdown search.
		 */
		menuSearchDropdown: function() {
			var self = this;
			var searchDropdownForm = document.querySelector( '#searchform-dropdown' );

			if ( ! searchDropdownForm ) {
				return;
			}

			var isOpen = false;
			var toggleBtn = null;
			var searchInput = searchDropdownForm.querySelector( 'input[type="search"]' );

			var show = function() {
				searchDropdownForm.classList.add( 'show' );

				document.querySelectorAll( 'a.search-dropdown-toggle, a.mobile-menu-search' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'true' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.add( 'active' );
					}
				} );

				searchInput.value = '';

				if ( 'function' === typeof jQuery ) {
					jQuery( document ).trigger( 'show.wpex.menuSearch' );
				}

				var focus = function( event ) {
					self.focusOnElement( searchDropdownForm, searchInput );
					searchDropdownForm.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the search when opening after transition is complete to prevent issues.
				searchDropdownForm.addEventListener( 'transitionend', focus );

				isOpen = true;

			};

			var hide = function() {
				searchDropdownForm.classList.remove( 'show' );
				document.querySelectorAll( 'a.search-dropdown-toggle, a.mobile-menu-search' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'false' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.remove( 'active' );
					}
				} );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				isOpen = false;
			};

			document.addEventListener( 'click', function( event ) {
				toggleBtn = event.target.closest( 'a.search-dropdown-toggle, a.mobile-menu-search' );
				if ( ! toggleBtn ) {
					if ( ! event.target.closest( '#searchform-dropdown' ) && isOpen ) {
						hide();
					}
					return;
				}
				event.preventDefault();
				if ( isOpen ) {
					hide();
				} else {
					show();
				}
			} );

			searchDropdownForm.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code && isOpen ) {
					hide();
				}
			} );

		},

		/**
		 * Overlay search.
		 */
		menuSearchOverlay: function() {
			var self = this;
			var searchOverlay = document.querySelector( '#wpex-searchform-overlay' );

			if ( ! searchOverlay ) {
				return;
			}

			var isOpen = false;
			var toggleBtn = null;
			var searchInput = searchOverlay.querySelector( 'input[type="search"]' );

			var show = function() {
				searchOverlay.classList.add( 'active' );

				document.querySelectorAll( 'a.search-overlay-toggle, a.mobile-menu-search, li.search-overlay-toggle > a' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'true' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.add( 'active' );
					}
				} );

				searchInput.value = ''; // reset value on re-open.

				if ( 'function' === typeof jQuery ) {
					jQuery( document ).trigger( 'show.wpex.menuSearch' );
				}

				var focus = function( event ) {
					self.focusOnElement( searchOverlay, searchInput );
					searchOverlay.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the search when opening after transition is complete to prevent issues.
				searchOverlay.addEventListener( 'transitionend', focus );

				isOpen = true;

			};

			var hide = function() {
				searchOverlay.classList.remove( 'active' );
				document.querySelectorAll( 'a.search-overlay-toggle, a.mobile-menu-search, li.search-overlay-toggle > a' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'false' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.remove( 'active' );
					}
				} );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				isOpen = false;
			};

			document.addEventListener( 'click', function( event ) {
				var target = event.target.closest( 'a.search-overlay-toggle, a.mobile-menu-search, li.search-overlay-toggle > a' );
				if ( ! target ) {
					if ( event.target.closest( '#wpex-searchform-overlay .wpex-close' ) && isOpen ) {
						hide();
					}
					return;
				}
				toggleBtn = target; // store toggle button for future focus.
				event.preventDefault();
				if ( isOpen ) {
					hide();
				} else {
					show();
				}
			} );

			searchOverlay.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code && isOpen ) {
					hide();
				}
			} );

		},

		/**
		 * Header Replace Search.
		 */
		menuSearchHeaderReplace: function() {
			var self = this;
			var headerReplace = document.querySelector( '#searchform-header-replace' );

			if ( ! headerReplace ) {
				return;
			}

			var isOpen = false;
			var toggleBtn = null;
			var searchInput = headerReplace.querySelector( 'input[type="search"]' );

			var show = function() {
				headerReplace.classList.add( 'show' );

				document.querySelectorAll( 'a.search-header-replace-toggle, a.mobile-menu-search' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'true' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.add( 'active' );
					}
				} );

				searchInput.value = '';

				if ( 'function' === typeof jQuery ) {
					jQuery( document ).trigger( 'show.wpex.menuSearch' );
				}

				var focus = function( event ) {
					self.focusOnElement( headerReplace, searchInput );
					headerReplace.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the search when opening after transition is complete to prevent issues.
				headerReplace.addEventListener( 'transitionend', focus );

				isOpen = true;

			};

			var hide = function() {
				headerReplace.classList.remove( 'show' );
				document.querySelectorAll( 'a.search-header-replace-toggle, a.mobile-menu-search' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'false' );
					var elementLi = element.closest( 'li' );
					if ( elementLi ) {
						elementLi.classList.remove( 'active' );
					}
				} );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				isOpen = false;
			};

			document.addEventListener( 'click', function( event ) {
				var target = event.target.closest( 'a.search-header-replace-toggle, a.mobile-menu-search' );
				if ( ! target ) {
					if ( ! event.target.closest( '#searchform-header-replace .searchform' ) && isOpen ) {
						hide();
					}
					return;
				}
				toggleBtn = target;
				event.preventDefault();
				if ( isOpen ) {
					hide();
				} else {
					show();
				}
			} );

			headerReplace.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code && isOpen ) {
					hide();
				}
			} );

		},

		/**
		 * Header Cart.
		 */
		menuCart: function() {
			var cartLink = document.querySelector( 'a.wcmenucart' );
			if ( cartLink && cartLink.classList.contains( 'go-to-shop' ) ) {
				return;
			}
			this.menuCartDropdown();
			this.menuCartOverlay();
		},

		/**
		 * Cart Dropdown.
		 */
		menuCartDropdown: function() {
			var self = this;
			var cartDropdown = document.querySelector( '#current-shop-items-dropdown' );

			if ( ! cartDropdown ) {
				return;
			}

			var toggleBtn = null;
			var isOpen = false;

			var show = function() {
				cartDropdown.classList.add( 'show' );

				document.querySelectorAll( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' ).forEach( function( element ) {
					element.classList.add( 'active' );
					element.setAttribute( 'aria-expanded', 'true' );
				} );

				if ( 'function' === typeof jQuery ) {
					jQuery( document ).trigger( 'show.wpex.menuCart' );
				}

				var focus = function( event ) {
					self.focusOnElement( cartDropdown );
					cartDropdown.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the search when opening after transition is complete to prevent issues.
				cartDropdown.addEventListener( 'transitionend', focus );

				isOpen = true;

			};

			var hide = function() {
				cartDropdown.classList.remove( 'show' );
				document.querySelectorAll( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' ).forEach( function( element ) {
					element.classList.remove( 'active' );
					element.setAttribute( 'aria-expanded', 'false' );
				} );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				isOpen = false;
			};

			document.addEventListener( 'click', function( event ) {

				toggleBtn = event.target.closest( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' );

				if ( ! toggleBtn ) {
					if ( ! event.target.closest( '#current-shop-items-dropdown' ) && isOpen ) {
						hide();
					}
					return;
				}

				event.preventDefault();

				if ( isOpen ) {
					hide();
				} else {
					show();
				}

			} );

			document.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code && isOpen ) {
					hide();
				}
			} );

		},

		/**
		 * Cart Overlay.
		 */
		menuCartOverlay: function() {
			var self = this;
			var cartOverlay = document.querySelector( '#wpex-cart-overlay' );

			if ( ! cartOverlay ) {
				return;
			}

			var toggleBtn = null;
			var isOpen = false;

			var show = function() {
				cartOverlay.classList.add( 'active' );

				document.querySelectorAll( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' ).forEach( function( element ) {
					element.classList.add( 'active' );
					element.setAttribute( 'aria-expanded', 'true' );
				} );

				if ( 'function' === typeof jQuery ) {
					jQuery( document ).trigger( 'show.wpex.menuCart' );
				}

				var focus = function( event ) {
					self.focusOnElement( cartOverlay );
					cartOverlay.removeEventListener( 'transitionend', focus ); // remove event as it keeps triggering.
				};

				// Focus on the search when opening after transition is complete to prevent issues.
				cartOverlay.addEventListener( 'transitionend', focus );

				isOpen = true;

			};

			var hide = function() {
				cartOverlay.classList.remove( 'active' );
				document.querySelectorAll( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' ).forEach( function( element ) {
					element.classList.remove( 'active' );
					element.setAttribute( 'aria-expanded', 'false' );
				} );
				if ( toggleBtn ) {
					toggleBtn.focus();
				}
				isOpen = false;
			};

			document.addEventListener( 'click', function( event ) {

				var target = event.target.closest( 'a.toggle-cart-widget, li.toggle-cart-widget > a, li.toggle-header-cart > a' );

				if ( ! target ) {
					if ( ! event.target.closest( '#wpex-cart-overlay .wpex-inner' ) && isOpen ) {
						hide();
					}
					return;
				}

				toggleBtn = target;

				event.preventDefault();

				if ( isOpen ) {
					hide();
				} else {
					show();
				}

			} );

			document.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 27 === code && isOpen ) {
					hide();
				}
			} );

		},

		/**
		 * Automatically add padding to row to offset header.
		 */
		headerOverlayOffset: function() {
			var header = document.querySelector( '#site-header' );

			if ( ! header || ! header.classList.contains( 'overlay-header' ) ) {
				return;
			}

			var elements = document.querySelectorAll( '.add-overlay-header-offset' );

			if ( ! elements.length ) {
				return;
			}

			var headerHeight = header.getBoundingClientRect().height;

			elements.forEach( function( element ) {
				var div = document.createElement( 'div' );
				div.className = 'overlay-header-offset-div wpex-w-100';
				element.prepend( div );
				setHeight( div );
				element.setAttribute( 'data-wpex-overlay-header-offset-init', 'true' );
			} );

			function setHeight( element ) {
				element.style.height = headerHeight + 'px';
			}

			function adjustHeight() {
				headerHeight = header.getBoundingClientRect().height;
				var divs = document.querySelectorAll( '.overlay-header-offset-div' );
				divs.forEach( function( element ) {
					setHeight( element );
				} );
			}

			window.addEventListener( 'resize', adjustHeight );

		},

		/**
		 * Hide post edit link.
		 */
		hideEditLink: function() {
			document.addEventListener( 'click', function( event ) {
				var hidePostLink = event.target.closest( 'a.hide-post-edit' );
				if ( hidePostLink ) {
					event.preventDefault();
					var postLinks = hidePostLink.closest( 'div.post-edit' );
					if ( postLinks ) {
						postLinks.parentNode.removeChild( postLinks );
					}
				}
			} );
		},

		/**
		 * Custom menu widget accordion.
		 */
		menuWidgetAccordion: function() {
			var self = this;
			var isAnimating = false;
			var duration = 300;

			if ( ! l10n.menuWidgetAccordion ) {
				return;
			}

			// Exclude from page animations.

			// Open toggle for active page.
			document.querySelectorAll( '#sidebar .widget_nav_menu .current-menu-ancestor, .widget_nav_menu_accordion .widget_nav_menu .current-menu-ancestor,#sidebar .widget_nav_menu .current-menu-item, .widget_nav_menu_accordion .widget_nav_menu .current-menu-item' ).forEach( function( element ) {
				element.classList.add( 'active' );
			} );

			// Toggle items.
			document.querySelectorAll( '#sidebar .widget_nav_menu, .widget_nav_menu_accordion .widget_nav_menu' ).forEach( function( element ) {
				element.querySelectorAll( '.menu-item-has-children' ).forEach( function( element ) {
					element.classList.add( 'parent' ); //@todo deprecate?
				} );
			} );

			// Show dropdown.
			var showDropdown = function( dropdown ) {
				isAnimating = true;
				self.slideDown( dropdown, duration, function() {
					isAnimating = false;
				} );
			};

			// Hide dropdown.
			var hideDropdown = function( dropdown ) {
				isAnimating = true;
				self.slideUp( dropdown, duration, function() {
					isAnimating = false;
				} );
			};

			// Listen for click events.
			document.addEventListener( 'click', function( event ) {
				var target = event.target.closest( '#sidebar .widget_nav_menu .menu-item-has-children > a, .widget_nav_menu_accordion .widget_nav_menu .menu-item-has-children > a' );
				if ( ! target ) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();

				if ( isAnimating ) {
					return; // prevent click spam.
				}

				var li = target.parentNode;
				var menu = target.closest( '.widget_nav_menu' );
				var dropdown = li.querySelector( '.sub-menu' );

				// Hide all other elements to create an accordion style menu.
				menu.querySelectorAll( '.menu-item-has-children' ).forEach( function( element ) {
					if ( element.contains( target ) ) {
						return;
					}
					// Hide all other dropdowns.
					var elementSub = element.querySelector( '.sub-menu' );
					if ( elementSub && element.classList.contains( 'active' ) ) {
						element.classList.remove( 'active' );
						hideDropdown( elementSub );
					}
				} );

				if ( ! dropdown ) {
					return;
				}

				if ( dropdown.classList.contains( 'wpex-transitioning' ) ) {
					return;
				}

				if ( li.classList.contains( 'active' ) ) {
					li.classList.remove( 'active' );
					hideDropdown( dropdown );
				} else {
					li.classList.add( 'active' );
					showDropdown( dropdown );
				}

			} );

		},

		/**
		 * Header 5 - Inline Logo.
		 */
		inlineHeaderLogo: function() {
			var self = this;
			var header = document.querySelector( '#site-header' );

			if ( ! header || ! header.classList.contains( 'header-five' ) ) {
				return;
			}

			var logo = document.querySelector( '#site-header.header-five #site-header-inner > .header-five-logo' );
			var nav = document.querySelector( '#site-header.header-five .navbar-style-five' );
			var menuLogoLi = null;

			if ( ! logo || ! nav ) {
				return;
			}

			var getLogoBeforeEl = function() {
				var navLiElements = document.querySelectorAll( '.navbar-style-five .main-navigation-ul > li' );
				var navLiElementsVisible = [];

				for ( var i=0; i < navLiElements.length; i++ ) {
					if ( self.isVisible( navLiElements[i] ) ) {
						navLiElementsVisible.push( navLiElements[i] );
					}
				}

				var navLiCount = navLiElementsVisible.length;
				var insertLogoAfterIndex = Math.round( navLiCount / 2 ) - parseInt( l10n.headerFiveSplitOffset );

				return navLiElementsVisible[insertLogoAfterIndex];
			};

			// Insert Logo into Menu.
			var insertLogo = function() {
				var insertLogoBeforeEl = getLogoBeforeEl();
				if ( ! insertLogoBeforeEl ) {
					return;
				}
				if ( self.viewportWidth() > l10n.mobileMenuBreakpoint ) {
					if ( ! menuLogoLi ) {
						menuLogoLi = document.createElement( 'li' );
						menuLogoLi.className = 'menu-item-logo';
					}
					menuLogoLi.appendChild( logo );
					insertLogoBeforeEl.parentNode.insertBefore( menuLogoLi, insertLogoBeforeEl.nextSibling );
					logo.classList.add( 'display' );
				}
			};

			// Move logo.
			var moveLogo = function() {
				var inlineLogo = document.querySelector( '.menu-item-logo .header-five-logo' );
				if ( self.viewportWidth() <= l10n.mobileMenuBreakpoint ) {
					if ( inlineLogo ) {
						var headerInner = document.querySelector( '#site-header-inner' );
						if ( headerInner ) {
							headerInner.insertBefore( inlineLogo, headerInner.firstChild );
						}
						if ( menuLogoLi ) {
							menuLogoLi.parentNode.removeChild( menuLogoLi );
						}
					}
				} else if ( ! inlineLogo ) {
					insertLogo(); // Insert logo to menu.
				}
			};

			// Run on init.
			insertLogo();

			// Run on resize.
			window.addEventListener( 'resize', moveLogo );

		},

		/**
		 * Prevent offset issues with the skipToContent link.
		 */
		skipToContent: function() {
			var self = this;
			document.addEventListener( 'click', function( event ) {
				if ( ! event.target.classList.contains( 'skip-to-content' ) ) {
					return;
				}
				var target = document.querySelector( event.target.getAttribute( 'href' ) );
				if ( target ) {
					target.setAttribute( 'tabIndex', '-1' );
					self.scrollTo( target, self.offset( target ).top - self.config.localScrollOffset );
					target.focus();
				}
				event.preventDefault();
				event.stopPropagation();
			} );
		},

		/**
		 * Back to top link.
		 */
		backTopLink: function() {
			var self = this;

			document.addEventListener( 'click', function( event ) {

				var target = event.target;

				if ( ! target.closest( 'a#site-scroll-top, a.wpex-scroll-top, .wpex-scroll-top a' ) ) {
					return;
				}

				var mainLink = target.closest( '#site-scroll-top' );

				if ( mainLink ) {
					target = mainLink;
				}

				var speed = parseInt( target.dataset.scrollSpeed || parseInt( l10n.localScrollSpeed ) );
				var easing = self.getEasing( target.dataset.scrollEasing );

				if ( easing && 'function' === typeof jQuery ) {
					jQuery( 'html, body' ).stop( true, true ).animate( {
						scrollTop : 0
					}, speed, easing );
				} else {
					window.scrollTo( {
						top: 0,
						behavior: 'smooth'
					} );
				}

				event.preventDefault();
				event.stopPropagation();

			} );

			var element = document.querySelector( '#site-scroll-top' );

			if ( ! element ) {
				return;
			}

			var offset = element.dataset.scrollOffset || 100;

			if ( 0 === offset ) {
				return;
			}

			var showHide = function( event ) {
				if ( window.pageYOffset > offset ) {
					element.classList.add( 'show' );
				} else {
					element.classList.remove( 'show' );
				}
			};

			window.addEventListener( 'scroll', showHide, self.config.passiveListeners ? { passive: true } : false );

		},

		/**
		 * Go back button
		 */
		goBackButton: function() {
			document.querySelectorAll( '.wpex-go-back' ).forEach( function( element ) {
				element.addEventListener( 'click', function( event ) {
					event.preventDefault();
					history.back();
				} );
			} );
		},

		/**
		 * Smooth Comment Scroll.
		 */
		smoothCommentScroll: function() {
			var self = this;
			document.addEventListener( 'click', function( event ) {
				if ( ! event.target.closest( '.comments-link' ) ) {
					return;
				}
				var target = document.querySelector( '#comments' );
				if ( ! target ) {
					return;
				}
				self.scrollTo(
					target,
					self.offset( target ).top - self.config.localScrollOffset - 20
				);
				event.preventDefault();
				event.stopPropagation();
			} );
		},

		/**
		 * Toggle Elements.
		 */
		toggleElements: function() {

			var getTargetElement = function( button ) {
				var target = button.getAttribute( 'aria-controls' );
				if ( target ) {
					return document.querySelector( target );
				}
			};

			document.addEventListener( 'click', function( event ) {
				var button = event.target.closest( 'a.wpex-toggle-element-trigger' );

				if ( ! button ) {
					return;
				}

				var hiddenElement = getTargetElement( button );

				if ( ! hiddenElement ) {
					return;
				}

				event.preventDefault();

				var hiddenElementParent = hiddenElement.parentNode;
				var triggerParent = button.closest( '.vc_section' ) || button.closest( '.vc_row' );
				var isOpen = hiddenElement.classList.contains( 'wpex-toggle-element--visible' );
				var isToggleElementContained = false;

				if ( triggerParent.contains( hiddenElement ) ) {
					isToggleElementContained = true;
				}

				var targetSection = isToggleElementContained ? triggerParent : document;

				// Hide all open elements
				targetSection.querySelectorAll( '.wpex-toggle-element--visible' ).forEach( function( element ) {
					element.classList.remove( 'wpex-toggle-element--visible' );
				} );

				// Reset triggers.
				targetSection.querySelectorAll( 'a.wpex-toggle-element-trigger' ).forEach( function( element ) {
					element.setAttribute( 'aria-expanded', 'false' );
					element.classList.remove( 'active' );
				} );

				var hideElement = function() {
					if ( button.classList.contains( 'vcex-button' ) ) {
						button.classList.remove( 'active' );
					}
					hiddenElement.classList.remove( 'wpex-toggle-element--visible' );
					button.setAttribute( 'aria-expanded', 'false' );
				};

				var showElement = function() {
					if ( button.classList.contains( 'vcex-button' ) ) {
						button.classList.add( 'active' );
					}
					button.setAttribute( 'aria-expanded', 'true' );
					hiddenElement.classList.add( 'wpex-toggle-element--visible' );

					// Fix issues with toggle elements inside toggle elements (@see vcex_icon_box).
					if ( hiddenElementParent.classList.contains( 'wpex-toggle-element' ) ) {
						hiddenElementParent.classList.add( 'wpex-toggle-element--visible' );
						hiddenElementParent.setAttribute( 'tabIndex', '-1' );
						hiddenElementParent.focus();
					} else {
						hiddenElement.setAttribute( 'tabIndex', '-1' );
						hiddenElement.focus();
					}

					/*var customEvent = new CustomEvent( 'wpex_toggle_element', {
						detail: {},
						bubbles: false,
						cancelable: true,

					} );
					hiddenElement.dispatchEvent( customEvent );*/
					window.dispatchEvent( new Event( 'resize' ) );
				};

				// Display target element
				if ( hiddenElement && hiddenElement.classList.contains( 'wpex-toggle-element' ) ) {
					if ( isOpen ) {
						hideElement();
					} else {
						showElement();
					}
				}

			} );

			// Return focus to tab when hitting the esc button.
			document.addEventListener( 'keydown', function( event ) {
				var toggleElement = event.target.closest( '.wpex-toggle-element--visible' );
				if ( toggleElement && 27 === event.keyCode ) {
					document.querySelectorAll( '.wpex-toggle-element-trigger[aria-expanded="true"]' ).forEach( function( element ) {
						if ( element.getAttribute( 'href' ) === '#' + toggleElement.getAttribute( 'id' ) ) {
							element.focus();
						}
					} );
				}
			} );

		},

		/**
		 * Toggle Bar.
		 */
		toggleBar: function() {

			var toggleBar = document.querySelector( '#toggle-bar-wrap' );

			if ( ! toggleBar ) {
				return;
			}

			var allowToggle = toggleBar.dataset.allowToggle;

			if ( allowToggle && 'false' !== allowToggle ) {
				this.toggleBarToggle( toggleBar );
			} else {
				this.toggleBarDismiss( toggleBar );
			}

		},

		/**
		 * Toggle Bar Toggle button.
		 */
		toggleBarToggle: function( toggleBar ) {
			var rememberState = ( 'true' === toggleBar.dataset.rememberState );
			var toggleButton = document.querySelector( '#toggle-bar-button' );

			document.addEventListener( 'click', function( event ) {
				var button = event.target.closest( 'a.toggle-bar-btn, a.togglebar-toggle, .togglebar-toggle > a' );
				if ( ! button ) {
					if ( 'visible' === getState() && toggleBar.classList.contains( 'close-on-doc-click' ) && ! event.target.closest( '#toggle-bar-wrap' ) ) {
						closeBar();
					}
					return;
				}
				if ( 'hidden' === getState() ) {
					openBar();
				} else {
					closeBar();
				}
				event.preventDefault();
				event.stopPropagation();
			} );

			function getState() {
				return toggleBar.dataset.state || 'hidden';
			}

			function openBar() {
				toggleBar.classList.add( 'active-bar' );
				toggleBar.dataset.state = 'visible';
				if ( toggleButton ) {
					toggleButton.setAttribute( 'aria-expanded', 'true' );
					if ( toggleButton.dataset.icon && toggleButton.dataset.iconHover ) {
						var icon = toggleButton.getElementsByClassName( toggleButton.dataset.icon );
						if ( icon.length ) {
							icon[0].className = toggleButton.dataset.iconHover;
						}
					}
				}
				setCookie( 'visible' );
			}

			function closeBar() {
				toggleBar.classList.remove( 'active-bar' );
				toggleBar.dataset.state = 'hidden';
				if ( toggleButton ) {
					toggleButton.setAttribute( 'aria-expanded', 'false' );
					if ( toggleButton.dataset.iconHover && toggleButton.dataset.icon ) {
						var icon = toggleButton.getElementsByClassName( toggleButton.dataset.iconHover );
						if ( icon.length ) {
							icon[0].className = toggleButton.dataset.icon;
						}
					}
				}
				setCookie( 'hidden' );
			}

			function setCookie( state ) {
				if ( rememberState ) {
					document.cookie = 'total_togglebar_state=' + state + '; path=/; Max-Age=604800; SameSite=Strict; Secure';
				}
			}

		},

		/**
		 * Toggle Bar Close Button
		 */
		toggleBarDismiss: function( toggleBar ) {
			document.addEventListener( 'click', function( event ) {
				var button = event.target.closest( '.toggle-bar-dismiss__button' );
				if ( ! button ) {
					return;
				}
				toggleBar.parentNode.removeChild( toggleBar );
				if ( 'true' === toggleBar.dataset.rememberState ) {
					document.cookie = 'total_togglebar_state=hidden; path=/; Max-Age=604800; SameSite=Strict; Secure';
				}
				event.preventDefault();
			} );
		},

		/**
		 * Advanced Parallax.
		 */
		parallax: function( context ) {
			var self = this;

			var init = function() {
				document.querySelectorAll( '.wpex-parallax-bg' ).forEach( function( element ) {

					// Disable on mobile.
					if ( element.classList.contains( 'not-mobile' ) && self.mobileCheck() ) {
						return;
					}

					// Parallax settings.
					var startPosition = 0;
					var velocity = element.dataset.velocity;
					var direction = element.dataset.direction;
					var fixed = element.dataset.fixed;
					var offset = null;

					// Get element dimensions and offsets.
					var elemHeight = element.getBoundingClientRect().height;
					var elemTop = self.offset( element ).top;
					var elemBottom = elemTop + element.getBoundingClientRect().height;

					// Get window dimensions and offsets.
					var winTop = self.winScrollTop();
					var winHeight = window.innerHeight;
					var viewTop =  winTop - 20;
					var viewBottom = winTop + winHeight + 20; // (adds 20px leeway)

					// Make sure element is in viewport.
					if ( elemTop >= viewBottom || elemBottom <= viewTop ) {
						return;
					}

					// If the element is below the fold, calculate the background image start position.
					if ( elemTop > winHeight ) {
						if ( 'none' !== direction ) {
							startPosition = ( elemTop - winHeight ) * Math.abs( velocity );
						}
					}

					// Calculate parallax position.
					var position = Math.ceil( startPosition + winTop * velocity );

					// Set background position.
					var xPos = '50%';
					var yPos = '50%';

					switch( direction ) {
						case 'left':
							xPos = position + 'px';
							break;
						case 'right':
							xPos = 'calc(100% + ' + -position + 'px)';
							break;
						case 'down':
							if ( 'true' === fixed ) {
								yPos = 'calc(100% + ' + (-position) + 'px)';
							} else {
								var computedStyles = window.getComputedStyle( element );
								offset = - ( winHeight -
									elemTop -
									elemHeight -
									parseInt( computedStyles.getPropertyValue( 'padding-top' ) ) -
									parseInt( computedStyles.getPropertyValue( 'padding-bottom' ) ) );

								yPos = 'calc(100% + ' + ( offset - winTop - position ) + 'px)';
							}
							break;
						default:
							if ( 'true' === fixed ) {
								yPos = position + 'px';
							} else {
								yPos = ( elemTop - winTop + position ) + 'px';
							}
							break;
					}

					element.style.backgroundPosition = xPos + ' ' + yPos;

				} );
			};

			init(); // run immediately so that bg's look good on load.

			window.addEventListener( 'scroll', init, self.config.passiveListeners ? { passive: true } : false );

		},

		/**
		 * Local Scroll Offset.
		 */
		parseLocalScrollOffset: function( instance ) {
			var self = this;
			var offset = 0;

			// Return custom offset.
			if ( l10n.localScrollOffset ) {
				self.config.localScrollOffset = l10n.localScrollOffset;
				return self.config.localScrollOffset;
			}

			// Adds extra offset via filter.
			if ( l10n.localScrollExtraOffset ) {
				offset = parseInt( offset ) + parseInt( l10n.localScrollExtraOffset );
			}

			// Fixed header.
			var stickyHeader = document.querySelector( '#site-header.fixed-scroll' );
			if ( stickyHeader ) {

				// Return 0 for small screens if mobile fixed header is disabled.
				if ( ! l10n.hasStickyMobileHeader && self.viewportWidth() < l10n.stickyHeaderBreakPoint ) {
					offset = parseInt( offset ) + 0;
				}

				// Return header height.
				else {

					// Shrink header.
					if ( stickyHeader.classList.contains( 'shrink-sticky-header' ) ) {
						// @todo can we remove the instance and use a different check?
						// Maybe we can add a data attribute to the header like data-sticky-init="false"
						// that we can check.
						if ( 'init' === instance || self.isVisible( stickyHeader ) ) {
							offset = parseInt( offset ) + parseInt( l10n.shrinkHeaderHeight );
						}
					}

					// Standard header.
					else {
						offset = parseInt( offset ) + stickyHeader.getBoundingClientRect().height;
					}

				}

			}

			// Loop through extra items.
			document.querySelectorAll( '.wpex-ls-offset,#wpadminbar,#top-bar-wrap-sticky-wrapper.wpex-can-sticky,#site-navigation-sticky-wrapper.wpex-can-sticky,#wpex-mobile-menu-fixed-top,.vcex-navbar-sticky-offset' ).forEach( function( element ) {
				if ( self.isVisible( element ) ) {
					offset = parseInt( offset ) + element.getBoundingClientRect().height;
				}
			} );

			/**
			 * Add 1 extra decimal to prevent cross browser rounding issues (mostly firefox).
			 *
			 * @todo remove?
			 */
			offset = offset ? offset - 1 : 0;

			self.config.localScrollOffset = offset;

			return self.config.localScrollOffset;

		},

		/**
		 * Scroll to function.
		 */
		scrollTo: function( hash, offset, callback ) {
			if ( ! hash ) {
				return;
			}

			var self = this;
			var target = null;
			var isLsDataLink = false;
			var lsOffset = self.config.localScrollOffset;
			var lsSpeed = parseInt( l10n.localScrollSpeed );
			var sections = document.querySelectorAll( '[data-ls_id]' );
			var localSection = null;
			var easing = self.getEasing();

			var scrollTo = function() {
				if ( easing && 'function' === typeof jQuery ) {
					jQuery( 'html, body' ).stop( true, true ).animate( {
						scrollTop: offset
					}, lsSpeed, easing );
				} else {
					window.scrollTo( {
						top: offset,
						behavior: 'smooth'
					} );
				}
			};

			// Use standard for loop since forEach doesn't support breaks.
			for ( var i=0; i < sections.length; i++ ) {
				if ( hash === sections[i].dataset.ls_id ) {
					localSection = sections[i];
					break;
				}
			}

			if ( localSection ) {
				target = localSection;
				isLsDataLink = true;
			} else if ( 'string' === typeof hash ) {
				if ( self.isSelectorValid( hash ) ) {
					target = document.querySelector( hash );
				}
			} else if ( hash.nodeType ) {
				target = hash;
			}

			// Target check.
			if ( target ) {

				// Change the target to parent tabs when linking to tab item.
				if ( target.classList.contains( 'vc_tta-panel' ) ) {
					var tab = target.closest( '.vc_tta-tabs' );
					if ( tab ) {
						offset = self.offset( tab ).top - lsOffset - 20;
					}
				}

				// Sanitize offset (target required).
				offset = offset || self.offset( target ).top - lsOffset;

				// Update hash.
				if ( l10n.localScrollUpdateHash && 'string' === typeof hash && isLsDataLink ) {
					window.location.hash = hash;
				}

				/**
				 * Mobile toggle Menu needs it's own code so it closes before the event fires
				 * to make sure we end up in the right place.
				 *
				 * @todo perhaps we should listen to click events on the mobile toggle nav and move this
				 * code out.
				 */
				var mobileToggleNav = document.querySelector( '.mobile-toggle-nav' );
				if ( mobileToggleNav && mobileToggleNav.classList.contains( 'visible' ) ) {

					// Make sure the nav height is removed from the offset height, but only
					// when its not absolutely positioned.
					if ( 'absolute' !== window.getComputedStyle( mobileToggleNav ).position ) {
						offset = offset - mobileToggleNav.getBoundingClientRect().height;
					}

					document.querySelectorAll( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' ).forEach( function( element ) {
						element.classList.remove( 'wpex-active' );
						element.setAttribute( 'aria-expanded', 'false' );
					} );

					if ( l10n.animateMobileToggle ) {
						self.slideUp( mobileToggleNav, 300, function() {
							mobileToggleNav.classList.remove( 'visible' );
							scrollTo();
						} );
					} else {
						mobileToggleNav.classList.remove( 'visible' );
						scrollTo();
					}
				}

				// Scroll to target.
				else {
					scrollTo();
				}

			}

		},

		/**
		 * Scroll to Hash.
		 */
		scrollToHash: function( self ) {
			var target;
			var offset = 0;
			var hash = location.hash;

			if ( '' == hash || '#' === hash || undefined == hash ) {
				return;
			}

			// Scroll to comments.
			if ( '#view_comments' === hash || '#comments_reply' === hash ) {
				target = document.querySelector( '#comments' );
				if ( target ) {
					offset = self.offset( target ).top - self.config.localScrollOffset - 20;
					self.scrollTo( target, offset );
				}
			}

			// Scroll to specific comment, fix for sticky header.
			if ( -1 !== hash.indexOf( 'comment-' ) && document.querySelector( '#site-header.fixed-scroll' ) ) {
				target = document.querySelector( hash );
				if ( target ) {
					offset = self.offset( target ).top - self.config.localScrollOffset - 20;
					self.scrollTo( target, offset );
				}
				return;
			}

			// Custom local scroll using #localscroll-{ID} for targeting elements on the page.
			if ( -1 !== hash.indexOf( 'localscroll-' ) ) {
				hash = hash.replace( 'localscroll-', '' );
			}

			// Scroll to hash.
			self.scrollTo( hash );

		},

		/**
		 * Local scroll links array.
		 */
		localScrollSections: function() {
			var self = this;

			/*
			Deprecated in 5.3 as it wasn't really doing anything for a long time now.
			// Add local-scroll class to links in menu with localscroll- prefix (if on same page).
			// Add data-ls_linkto attr.
			jQuery( '.main-navigation-ul li.menu-item a' ).each( function() {
				var $this = jQuery( this );
				var href = $this.attr( 'href' );
				if ( href && href.indexOf( 'localscroll-' ) != -1 ) {
					var parentLi = $this.parent( 'li' );
					parentLi.addClass( 'local-scroll' );
					parentLi.removeClass( 'current-menu-item' );
					var withoutHash = href.substr( 0, href.indexOf( '#' ) );
					var currentPage = location.href;
					currentPage = location.hash ? currentPage.substr( 0, currentPage.indexOf( '#' ) ) : location.href;
					if ( withoutHash == currentPage ) {
						var hash = href.substring( href.indexOf( '#' ) + 1 );
						$this.attr( 'data-ls_linkto', '#' + hash.replace( 'localscroll-', '' ) );
						$this.addClass( 'local-scroll' );
					}
				}
			} );
			*/

			// Define main vars.
			var array = [];
			var links = document.querySelectorAll( l10n.localScrollTargets );

			// Loop through links.
			links.forEach( function( link ) {

				var href = link.getAttribute( 'href' );
				var hash = href ? '#' + href.replace( /^.*?(#|$)/, '' ) : null;

				// Hash required.
				if ( hash && '#' !== hash ) {

					// Add data-ls_linkto to the links (used for section highlight).
					if ( ! link.hasAttribute( 'data-ls_linkto' ) ) {
						link.setAttribute( 'data-ls_linkto', hash );
					}

					// Data attribute targets.
					var section = document.querySelector( '[data-ls_id="' + hash + '"]' );
					if ( ! section ) {
						section = document.querySelector( hash );
					}

					if ( section && array.indexOf( hash ) == -1 ) {
						array.push( hash );
					}

				}

			} );

			self.config.localScrollSections = array;

			return self.config.localScrollSections;

		},

		/**
		 * Local Scroll link.
		 */
		localScrollLinks: function() {
			var self = this;

			document.addEventListener( 'click', function( event ) {
				var link = event.target.closest( l10n.localScrollTargets );

				if ( ! link ) {
					return;
				}

				var hash = link.dataset.ls_linkto || link.hash; // this.hash needed as fallback.

				// Don't try and scroll to links not in our array of allowed local scroll links.
				if ( ! self.config.localScrollSections || -1 == self.config.localScrollSections.indexOf( hash ) ) {
					return;
				}

				// Remove superfish dropdown class.
				if ( link.closest( '.sfHover' ) ) {
					link.parentNode.classList.remove( 'sfHover' );
				}

				// Scroll to hash.
				self.scrollTo( hash );

				// Clicking inside full-scren menu.
				// return early so we can do our own thing in mobileMenuFullScreen.
				if ( link.closest( '.full-screen-overlay-nav-menu .local-scroll > a' ) ) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();
			} );

			// Local Scroll - Woocommerce Reviews.
			document.addEventListener( 'click', function( event ) {

				if ( ! event.target.closest( 'body.single-product .entry-summary a.woocommerce-review-link' ) ) {
					return;
				}

				var target = document.querySelector( '.woocommerce-tabs' );
				var tab = document.querySelector( '.reviews_tab a' );

				if ( ! target || ! tab ) {
					return;
				}

				event.preventDefault();

				tab.click();

				var offset = self.offset( target ).top - self.config.localScrollOffset;
				self.scrollTo( target, offset );

			} );

		},

		/**
		 * Local Scroll Highlight on scroll.
		 *
		 * @todo update to highlight last item when we reach bottom of the page.
		 */
		localScrollHighlight: function() {
			if ( ! l10n.localScrollHighlight ) {
				return;
			}
			var self = this;
			var localScrollSections = self.config.localScrollSections;

			// Return if there aren't any local scroll items.
			if ( ! localScrollSections.length ) {
				return;
			}

			// Highlight active items.
			function highlightSections() {
				for ( var i=0; i < localScrollSections.length; i++ ) {
					highlightSection( localScrollSections[i] );
				}
			} //highlightSections(); // no need to fire on load.

			function highlightSection( section ) {

				var targetDiv = document.querySelector( '[data-ls_id="' + section + '"]' ) || document.querySelector( section );

				if ( ! targetDiv ) {
					return;
				}

				var highlightSection = false;
				var winTop = self.winScrollTop();
				var divPos = self.offset( targetDiv ).top - self.config.localScrollOffset - 1;
				var divHeight = targetDiv.offsetHeight;
				var highlightLinks = document.querySelectorAll( '[data-ls_linkto="' + section + '"]' );

				if ( winTop >= divPos && winTop < ( divPos + divHeight ) ) {
					highlightSection = true;
				} else {
					highlightSection = false;
				}

				if ( highlightSection ) {
					targetDiv.classList.add( 'wpex-ls-inview' );

					// prevent any sort of duplicate local scroll active links
					document.querySelectorAll( '.local-scroll.menu-item' ).forEach( function( element ) {
						element.classList.remove( 'current-menu-item' );
					} );
				} else {
					targetDiv.classList.remove( 'wpex-ls-inview' );
				}

				highlightLinks.forEach( function( element ) {
					if ( highlightSection ) {
						element.classList.add( 'active' );
					} else {
						element.classList.remove( 'active' );
					}
					var li = element.closest( 'li' );
					if ( li ) {
						if ( highlightSection ) {
							li.classList.add( 'current-menu-item' );
						} else {
							li.classList.remove( 'current-menu-item' );
						}
					}
				} );

			}

			window.addEventListener( 'scroll', highlightSections, self.config.passiveListeners ? { passive: true } : false );

		},

		/**
		 * Equal heights function.
		 *
		 * @note Must run before isotope method.
		 */
		equalHeights: function( context ) {

			if ( 'function' !== typeof window.wpexEqualHeights ) {
				return;
			}

			wpexEqualHeights( '.match-height-grid', '.match-height-content', context );
			wpexEqualHeights( '.match-height-row', '.match-height-content', context );
			wpexEqualHeights( '.vcex-feature-box-match-height', '.vcex-match-height', context );
			wpexEqualHeights( '.blog-equal-heights', '.blog-entry-inner', context );
			wpexEqualHeights( '.vc_row', '.equal-height-column', context );
			wpexEqualHeights( '.vc_row', '.equal-height-content', context );

			wpexEqualHeights( '.wpex-vc-row-columns-match-height', '.vc_column-inner', context ); // @deprecated 4.0

		},

		/**
		 * Footer Reveal Display on Load.
		 */
		footerReveal: function() {
			var self = this;
			var footerReveal = document.querySelector( '#footer-reveal' );
			var wrap = document.querySelector( '#wrap' );
			var main = document.querySelector( '#main' );

			if ( ! footerReveal || ! wrap || ! main ) {
				return;
			}

			function showHide() {

				// Disabled under 960 - why?
				if ( self.viewportWidth() < 960 ) {
					if ( footerReveal.classList.contains( 'footer-reveal' ) ) {
						footerReveal.classList.remove( 'footer-reveal' );
						footerReveal.classList.add( 'footer-reveal-visible' );
						wrap.style.removeProperty( 'margin-bottom' );
					}
					return;
				}

				var revealHeight = footerReveal.offsetHeight;
				var windowHeight = window.innerHeight;
				var heightCheck = 0;

				if ( footerReveal.classList.contains( 'footer-reveal' ) ) {
					heightCheck = wrap.offsetHeight + self.config.localScrollOffset;
				} else {
					heightCheck = wrap.offsetHeight + self.config.localScrollOffset - revealHeight;
				}

				// Hide the footer.
				if ( ( windowHeight > revealHeight ) && ( heightCheck > windowHeight ) ) {
					if ( footerReveal.classList.contains( 'footer-reveal-visible' ) ) {
						wrap.style.marginBottom = revealHeight + 'px';
						footerReveal.classList.remove( 'footer-reveal-visible' );
						footerReveal.classList.add( 'footer-reveal' );
					}
				}

				// Display the footer.
				else {
					if ( footerReveal.classList.contains( 'footer-reveal' ) ) {
						wrap.style.removeProperty( 'margin-bottom' );
						footerReveal.classList.remove( 'footer-reveal' );
						footerReveal.classList.remove( 'wpex-visible' );
						footerReveal.classList.add( 'footer-reveal-visible' );
					}
				}

			}

			function reveal() {
				if ( ! footerReveal.classList.contains( 'footer-reveal' ) ) {
					return;
				}
				if ( self.scrolledToBottom( main ) ) {
					footerReveal.classList.add( 'wpex-visible' );
				} else {
					footerReveal.classList.remove( 'wpex-visible' );
				}
			}

			// Fire right away.
			showHide();
			reveal();

			// Fire on events.
			window.addEventListener( 'scroll', reveal, self.config.passiveListeners ? { passive: true } : false );
			window.addEventListener( 'resize', showHide );

		},

		/**
		 * Set min height on main container to prevent issue with extra space below footer.
		 */
		fixedFooter: function() {

			if ( ! document.body.classList.contains( 'wpex-has-fixed-footer' ) ) {
				return;
			}

			var main = document.querySelector( '#main' );

			if ( ! main ) {
				return;
			}

			function run() {
				main.style.minHeight = ( main.offsetHeight + ( window.innerHeight - document.documentElement.offsetHeight ) ) + 'px';
			} run();

			window.addEventListener( 'resize', run );

		},

		/**
		 * Custom Selects.
		 */
		customSelects: function( context ) {

			if ( ! context || ! context.childNodes ) {
				context = document;
			}

			var self = this;
			var selects = context.querySelectorAll( l10n.customSelects );

			selects.forEach( function( element ) {
				var parent = element.parentNode;
				if ( parent.classList.contains( 'wpex-select-wrap' ) || parent.classList.contains( 'wpex-multiselect-wrap' ) ) {
					return;
				}
				var elementId = element.id;
				var className = elementId ? ' wpex-' + elementId : '';
				var addIcon = false;
				if ( self.isVisible( element ) ) { // @todo is this really needed?
					// Wrap the select
					var div = document.createElement( 'div' );
					if ( element.hasAttribute( 'multiple' ) ) {
						div.className = 'wpex-multiselect-wrap' + className + '';
					} else {
						div.className = 'wpex-select-wrap' + className + '';
						addIcon = true;
					}
					element = self.wrap( element, div );
					// Add icon to wrapper
					if ( addIcon ) {
						var icon = document.createElement( 'span' );
						icon.className = 'ticon ticon-angle-down';
						icon.setAttribute( 'aria-hidden', 'true' );
						div.appendChild( icon );
					}
				}
			} );

		},

		/**
		 * Masonry Grids.
		 */
		masonryGrids: function() {
			if ( 'undefined' === typeof Isotope ) {
				return;
			}

			var masonryGrids = document.querySelectorAll( '.wpex-masonry-grid' );

			var renderGrid = function( element ) {

				// Set settings for each item.
				var settings = {};
				if ( 'object' === typeof wpex_isotope_params ) {
					settings = Object.assign( {}, wpex_isotope_params ); // create new object of wpex_isotope_params
					settings.itemSelector = '.wpex-masonry-col'; // set correct itemSelector
				}

				// Get custom settings.
				if ( element.dataset.transitionDuration ) {
					settings.transitionDuration = parseFloat( element.dataset.transitionDuration ) + 's';
				}

				if ( element.dataset.layoutMode ) {
					settings.layoutMode = element.dataset.layoutMode;
				}

				// Initiate isotope.
				var iso = new Isotope( element, settings );

			};

			var initialize = function( element ) {
				if ( 'function' === typeof imagesLoaded ) {
					imagesLoaded( element, function() {
						renderGrid( element );
					} );
				} else {
					renderGrid( element );
				}
			};

			masonryGrids.forEach( function( element ) {
				if ( element.closest( '[data-vc-stretch-content]' ) ) {
					setTimeout( function() {
						initialize( element );
					}, 10 );
				} else {
					initialize( element );
				}
			} );

		},

		/**
		 * Lightbox wrapper method that calls all sub-lightbox methods.
		 */
		lightbox: function( context ) {
			this.autoLightbox();
			this.lightboxSingle( context );
			this.lightboxInlineGallery( context );
			this.lightboxGallery( context );
			this.lightboxCarousels( context );
		},

		/**
		 * Automatic Lightbox for images.
		 *
		 * @note has() is a jQuery extension and not part of the CSS specification, queries using :has()
		 * cannot take advantage of the performance boost provided by the native DOM querySelectorAll() method.
		 * We will need to change the autoLightbox targets to use parents and then loop through them to see if they have a
		 * direct img decendent that equals the href.
		 */
		autoLightbox: function() {
			if ( 'undefined' === typeof l10n.autoLightbox || ! l10n.autoLightbox || 'function' !== typeof jQuery ) {
				return;
			}
			// @see note when converting to vanilla js.
			var self = this;
			var imageExt = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'jfif', 'jpe'];
			jQuery( l10n.autoLightbox ).each( function() {
				var $this = jQuery( this );
				var href = $this.attr( 'href' );
				var ext = self.getUrlExtension( href );
				if ( href && imageExt.indexOf( ext ) !== -1 ) {
					if ( ! $this.parents( '.woocommerce-product-gallery' ).length ) {
						$this.addClass( 'wpex-lightbox' );
					}
				}
			} );
		},

		/**
		 * Single lightbox.
		 */
		lightboxSingle: function( $context ) {
			if ( 'function' !== typeof jQuery || 'undefined' === typeof jQuery.fancybox ) {
				return;
			}

			var self = this;
			var targets = '.wpex-lightbox, .wpex-lightbox-video, .wpb_single_image.video-lightbox a, .wpex-lightbox-autodetect, .wpex-lightbox-autodetect a';

			$context = $context || jQuery( 'body' );

			$context.on( 'click', targets, function( e ) {

				e.preventDefault();

				var $this = jQuery( this );

				if ( ! $this.is( 'a' ) ) {
					$this = $this.find( 'a' );
				}

				if ( $this.hasClass( 'wpex-lightbox-group-item' ) ) {
					return;
				}

				var customSettings = {};
				var opts = $this.data() || {};
				var src = $this.attr( 'href' ) || $this.data( 'src' ) || '';
				var type = $this.data( 'type' ) || '';
				var caption = $this.data( 'caption' ) || '';
				var show_title = $this.attr( 'data-show_title' ) || true;
				var oldOpts = $this.data( 'options' ) && self.parseObjectLiteralData( $this.data( 'options' ) ) || '';

				if ( ! opts.parsedOpts ) {

					if ( oldOpts ) {

						if ( $this.data( 'type' ) && 'iframe' === $this.data( 'type' ) ) {
							if ( oldOpts.width && oldOpts.height ) {
								opts.width  = oldOpts.width;
								opts.height = oldOpts.height;
							}
						}

						if ( oldOpts.iframeType && 'video' === oldOpts.iframeType ) {
							type = '';
						}

					}

					if ( 'iframe' === type && opts.width && opts.height ) {
						opts.iframe = {
							css: {
								'width': opts.width,
								'height': opts.height
							}
						};
					}

					if ( 'false' !== show_title ) {
						var title = $this.data( 'title' ) || '';
						if ( title.length ) {
							var titleClass = 'fancybox-caption__title';
							if ( caption.length ) {
								titleClass = titleClass + ' fancybox-caption__title-margin';
							}
							caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
						}
					}

					if ( caption.length ) {
						opts.caption = caption;
					}

					opts.parsedOpts = true; // prevent duplicating caption since we are storing new caption in data.

				}

				if ( $this.hasClass( 'wpex-lightbox-iframe' ) ) {
					type = 'iframe'; // for use with random modules.
				}

				if ( $this.hasClass( 'wpex-lightbox-inline' ) ) {
					type = 'inline'; // for use with random modules.
				}

				if ( 'inline' === type ) {
					opts.afterLoad = function( instance, current ) {
						jQuery( document ).trigger( 'wpex-modal-loaded' );
					};
				}

				if ( $this.hasClass( 'rev-btn' ) ) {
					type = '';
					opts = {}; // fixes rev slider issues.
				}

				jQuery.fancybox.open( [ {
					src: src,
					opts: opts,
					type: type
				} ], jQuery.extend( {}, wpex_fancybox_params, customSettings ) );

			} );

		},

		/**
		 * Inline Lightbox Gallery.
		 */
		lightboxInlineGallery: function( $context ) {
			if ( 'function' !== typeof jQuery || 'undefined' === typeof jQuery.fancybox ) {
				return;
			}

			$context = $context || jQuery( document );

			$context.on( 'click', '.wpex-lightbox-gallery', function( e ) {

				e.preventDefault();

				var $this = jQuery( this );
				var gallery = $this.data( 'gallery' ) || '';
				var items = [];

				if ( gallery.length && 'object' === typeof gallery ) {

					jQuery.each( gallery, function( index, val ) {
						var opts = {};
						var title = val.title || '';
						var caption = val.caption || '';
						if ( title.length ) {
							var titleClass = 'fancybox-caption__title';
							if ( caption.length ) {
								titleClass = titleClass + ' fancybox-caption__title-margin';
							}
							caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
						}
						if ( caption.length ) {
							opts.caption = caption;
						}
						opts.thumb = val.thumb || val.src;
						items.push( {
							src: val.src,
							opts: opts
						} );
					} );

					jQuery.fancybox.open( items, wpex_fancybox_params );

				}

			} );

		},

		/**
		 * Gallery lightbox
		 */
		lightboxGallery: function( $context ) {
			if ( 'function' !== typeof jQuery || 'undefined' === typeof jQuery.fancybox ) {
				return;
			}

			var self = this;
			$context = $context || jQuery( document );

			// Prevent conflicts (can't be a group item and a single lightbox item.
			document.querySelectorAll( 'a.wpex-lightbox-group-item' ).forEach( function( element ) {
				element.classList.remove( 'wpex-lightbox' );
			} );

			$context.on( 'click', 'a.wpex-lightbox-group-item', function( e ) {

				e.preventDefault();

				jQuery( '.wpex-lightbox-group-item' ).removeAttr( 'data-lb-index' ); // Remove all lb-indexes to prevent issues with filterable grids or hidden items.

				var $this = jQuery( this );
				var $group = $this.closest( '.wpex-lightbox-group' );
				var $groupItems = $group.find( 'a.wpex-lightbox-group-item:visible' );
				var customSettings = {};
				var items = [];
				var activeIndex = 0;

				$groupItems.each( function( index ) {

					var $item = jQuery( this );
					var opts = $item.data() || {};
					var src = $item.attr( 'href' ) || $item.data( 'src' ) || '';
					var title = '';
					var show_title = $item.attr( 'data-show_title' ) || true;
					var caption = $item.data( 'caption' ) || '';
					var oldOpts = $item.data( 'options' ) && self.parseObjectLiteralData( '({' + $item.data( 'options' ) + '})' ) || '';

					if ( ! opts.parsedOpts ) {

						opts.thumb = $item.data( 'thumb' ) || src;

						if ( oldOpts ) {
							opts.thumb = oldOpts.thumbnail || opts.thumb;
							if ( oldOpts.iframeType && 'video' === oldOpts.iframeType ) {
								opts.type = '';
							}
						}

						if ( 'false' !== show_title ) {
							title = $item.data( 'title' ) || $item.attr( 'title' ) || '';
							if ( title.length ) {
								var titleClass = 'fancybox-caption__title';
								if ( caption.length ) {
									titleClass = titleClass + ' fancybox-caption__title-margin';
								}
								caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
							}
						}

						if ( caption.length ) {
							opts.caption = caption;
						}

						opts.parsedOpts = true;

					}

					if ( src ) {

						$item.attr( 'data-lb-index', index );

						if ( $this[0] == $item[0] ) {
							activeIndex = index;
						}

						items.push( {
							src: src,
							opts: opts
						} );

					}

				} );

				jQuery.fancybox.open( items, jQuery.extend( {}, wpex_fancybox_params, customSettings ), activeIndex );

			} );

		},

		/**
		 * Carousel Lightbox.
		 *
		 * @todo place code in it's own file and load conditionally?
		 */
		lightboxCarousels: function( $context ) {
			if ( 'function' !== typeof jQuery || 'undefined' === typeof jQuery.fancybox ) {
				return;
			}

			$context = $context || jQuery( document );

			$context.on( 'click', '.wpex-carousel-lightbox-item', function( e ) {

				e.preventDefault();

				var $this = jQuery( this );
				var $parent = $this.parents( '.wpex-carousel' );
				var $owlItems = $parent.find( '.owl-item' );
				var items = [];
				var customSettings = {
					loop : true // carousels should always loop so it's not strange when clicking an item after scrolling.
				};

				$owlItems.each( function() {

					if ( ! jQuery( this ).hasClass( 'cloned' ) ) {

						var $item = jQuery( this ).find( '.wpex-carousel-lightbox-item' );

						if ( $item.length ) {

							var opts = {};
							var src = $item.attr( 'href' ) || $item.data( 'src' ) || '';
							var title = $item.data( 'title' ) || $item.attr( 'title' ) || '';
							var caption = $item.data( 'caption' ) || '';
							var show_title = $item.attr( 'data-show_title' ) || true;

							if ( 'false' !== show_title && title.length ) {
								var titleClass = 'fancybox-caption__title';
								if ( caption.length ) {
									titleClass = titleClass + ' fancybox-caption__title-margin';
								}
								caption = '<div class="' + titleClass + '">' + title + '</div>' + caption;
							}

							if ( caption.length ) {
								opts.caption = caption;
							}

							opts.thumb = $item.data( 'thumb' ) || src;

							items.push( {
								src  : src,
								opts : opts
							} );

						}

					}

				} );

				if ( items.length && 'object' === typeof items ) {
					var activeIndex = $this.data( 'count' ) - 1 || 0;
					jQuery.fancybox.open( items, jQuery.extend( {}, wpex_fancybox_params, customSettings ), activeIndex );
				}

			} );

		},

		/**
		 * Inline hover styles.
		 */
		hoverStyles: function() {

			var oldStyle,
				items,
				itemsLength,
				headCSS = '',
				cssObj = {},
				style,
				head;

			oldStyle = document.querySelector( '.wpex-hover-data' ); // querySelector will return null if nothing is found.
			if ( oldStyle ) {
				oldStyle.remove(); // prevent dups in VC front end.
			}

			items = document.querySelectorAll( '[data-wpex-hover]' );
			itemsLength = items.length;

			// No need to do anything if we don't have any items to style.
			if ( ! itemsLength ) {
				return;
			}

			// use a standard loop so we can access the index for adding unique classnames.
			for (var i = 0; i < itemsLength; i++) {
				parseItem( i );
			}

			if ( cssObj ) {
				for ( var css in cssObj ) {
					if ( cssObj.hasOwnProperty( css ) ) {
						headCSS += cssObj[css] + '{' + css + '}';
					}
				}
			}

			if ( headCSS ) {
				style = document.createElement( 'style' );
				style.classList.add( 'wpex-hover-data' );
				style.appendChild( document.createTextNode( headCSS ) );
				head = document.head || document.getElementsByTagName( 'head' )[0];
				head.appendChild( style );
			}

			function parseItem( index ) {

				var element,
					data,
					uniqueClass,
					classList,
					hoverCSS = '',
					target = '',
					parent;

				element = items[index];
				data = element.dataset.wpexHover;

				if ( ! data ) {
					return;
				}

				data = JSON.parse( data );

				// Remove any wpex-dhover-{int} classname that may have been previously added.
				// This is a fix for AJAX functions and front-end editor edits.
				classList = element.classList;
				for ( var i = 0; i < classList.length; i++ ) {
					if ( -1 !== classList[i].indexOf( 'wpex-dhover-' ) ) {
						element.classList.remove( classList[i] );
					}
				}

				// New unique classname based on index.
				uniqueClass = 'wpex-dhover-' + index;

				if ( data.parent ) {
					parent = element.closest( data.parent );
					if ( parent ) {
						parent.classList.add( uniqueClass + '-p' );
						element.classList.add( uniqueClass );
						target = '.' + uniqueClass + '-p:hover .' + uniqueClass;
					}
				} else {
					element.classList.add( uniqueClass );
					target = '.' + uniqueClass + ':hover';
				}

				for ( var key in data ) {
					if ( data.hasOwnProperty( key ) ) {
						if ( 'target' === key || 'parent' === key ) {
							continue;
						}
						hoverCSS += key + ':' +  data[key] + '!important;';
					}
				}

				if ( hoverCSS ) {
					if ( hoverCSS in cssObj ) {
						cssObj[hoverCSS] = cssObj[hoverCSS] + ',' + target;
					} else {
						cssObj[hoverCSS] = target;
					}
				}

			}

		},

		/**
		 * Overlay Mobile Support.
		 */
		overlaysMobileSupport: function() {
			var self = this;
			var supportsTouch = (window.matchMedia("(any-pointer: coarse)").matches);

			if ( ! supportsTouch ) {
				return;
			}

			var dragging = false;

			// Remove overlays completely if mobile support is disabled.
			document.querySelectorAll( '.overlay-parent.overlay-hh' ).forEach( function( element ) {
				if ( ! element.classList.contains( 'overlay-ms' ) ) {
					var overlay = element.querySelector( '.theme-overlay' );
					if ( overlay ) {
						element.parentNode.removeChild( element );
					}
				}
			} );

			var hideAllOverlays = function() {
				document.querySelectorAll( '.overlay-parent.wpex-touched' ).forEach( function( element ) {
					element.classList.remove( 'wpex-touched' );
				} );
			};

			// Add touch support to overlays.
			document.querySelectorAll( 'a.overlay-parent.overlay-ms.overlay-h, .overlay-parent.overlay-ms.overlay-h > a' ).forEach( function( element ) {

				element.addEventListener( 'touchend', function( event ) {

					// If we are moving no need to show anything.
					if ( dragging ) {
						hideAllOverlays();
						return;
					}

					var overlayParent = element.closest( '.overlay-parent' );

					if ( overlayParent.classList.contains( 'wpex-touched' ) ) {
						return; // overlay is open, allow clicking inside.
					}

					event.preventDefault();

					hideAllOverlays();
					overlayParent.classList.add( 'wpex-touched' );
				} );

				element.addEventListener( 'touchmove', function( event ) {
					dragging = true;
				}, self.config.passiveListeners ? { passive: true } : false );

				element.addEventListener( 'touchstart', function( event ) {
					dragging = false;
				}, self.config.passiveListeners ? { passive: true } : false );

			} );

			var clickingOutside = function( event ) {
				if ( ! event.target.closest( '.overlay-parent.wpex-touched' ) ) {
					hideAllOverlays();
				}
			};

			document.addEventListener( 'touchstart', clickingOutside, self.config.passiveListeners ? { passive: true } : false );
			document.addEventListener( 'touchmove', clickingOutside, self.config.passiveListeners ? { passive: true } : false );

		},

		/**
		 * Sticky Topbar.
		 */
		stickyTopBar: function() {
			var self = this;
			var isSticky = false;
			var offset = 0;
			var stickyTopbar = document.querySelector( '#top-bar-wrap.wpex-top-bar-sticky' );
			var wpToolbar = document.querySelector( '#wpadminbar' );
			var mobileMenu = document.querySelector( '#wpex-mobile-menu-fixed-top' );
			var customOffsets = document.querySelectorAll( '.wpex-sticky-el-offset' );
			var brkPoint = l10n.stickyTopBarBreakPoint;

			if ( ! stickyTopbar ) {
				return;
			}

			// Add wrapper.
			var stickyWrap = document.createElement( 'div' );
			stickyWrap.id = 'top-bar-wrap-sticky-wrapper';
			stickyWrap.className = 'wpex-sticky-top-bar-holder not-sticky';

			self.wrap( stickyTopbar, stickyWrap );

			// Get offset.
			function getOffset() {
				offset = 0; // Reset offset for resize.

				if ( self.isVisible( wpToolbar ) && 'fixed' === window.getComputedStyle( wpToolbar ).position ) {
					offset = offset + wpToolbar.getBoundingClientRect().height;
				}

				if ( self.isVisible( mobileMenu ) ) {
					offset = offset + mobileMenu.getBoundingClientRect().height;
				}

				customOffsets.forEach( function( element ) {
					if ( self.isVisible( element ) ) {
						offset = offset + element.getBoundingClientRect().height;
					}
				} );

				return offset;

			}

			// Stick the TopBar.
			function setSticky() {

				if ( isSticky ) {
					return;
				}

				// Add wrap class and toggle sticky class.
				stickyWrap.style.height = stickyTopbar.getBoundingClientRect().height + 'px';
				stickyWrap.classList.remove( 'not-sticky' );
				stickyWrap.classList.add( 'is-sticky' );

				// Add CSS to topbar.
				stickyTopbar.style.top = getOffset() + 'px';
				stickyTopbar.style.width = stickyWrap.getBoundingClientRect().width + 'px';

				// Set sticky to true.
				isSticky = true;

			}

			// Unstick the TopBar.
			function destroySticky() {

				if ( ! isSticky ) {
					return;
				}

				// Remove sticky wrap height and toggle sticky class.
				stickyWrap.style.height = '';
				stickyWrap.classList.remove( 'is-sticky' );
				stickyWrap.classList.add( 'not-sticky' );

				// Remove topbar css.
				stickyTopbar.style.width = '';
				stickyTopbar.style.top = '';

				// Set sticky to false.
				isSticky = false;

			}

			// Runs on load and resize.
			function initSticky() {

				if ( ! l10n.hasStickyTopBarMobile && ( self.viewportWidth() < brkPoint ) ) {
					stickyWrap.classList.remove( 'wpex-can-sticky' );
					destroySticky();
					return;
				}

				var windowTop = self.winScrollTop();

				stickyWrap.classList.add( 'wpex-can-sticky' );

				if ( isSticky ) {

					// Update sticky wrapper height incase it changed on resize.
					stickyWrap.style.height = stickyTopbar.getBoundingClientRect().height + 'px';

					// Update topbar top position and width incase it changed on resize.
					stickyTopbar.style.top = getOffset() + 'px';
					stickyTopbar.style.width = stickyWrap.getBoundingClientRect().width + 'px';

				} else {

					// Set sticky based on original offset.
					offset = self.offset( stickyWrap ).top - getOffset();

					// Set or destroy sticky.
					if ( 0 !== windowTop && windowTop > offset ) {
						setSticky();
					} else {
						destroySticky();
					}

				}

			}

			// On scroll actions for sticky topbar.
			function onScroll() {

				var windowTop = self.winScrollTop();

				// Set or destroy sticky based on offset.
				if ( ( 0 !== windowTop ) && ( windowTop >= ( self.offset( stickyWrap ).top - getOffset() ) ) ) {
					setSticky();
				} else {
					destroySticky();
				}

			}

			// Fire on init.
			initSticky();

			// Fire onscroll event.
			window.addEventListener( 'scroll', function() {
				if ( stickyWrap && stickyWrap.classList.contains( 'wpex-can-sticky' ) ) {
					onScroll();
				}
			}, self.config.passiveListeners ? { passive: true } : false );

			// Fire onResize.
			window.addEventListener( 'resize', initSticky );

			// On orientation change destroy sticky and recalculate.
			// @todo deprecate?
			window.addEventListener( 'orientationchange', function() {
				destroySticky();
				initSticky();
			} );
		},

		/**
		 * Return offSet for the sticky header and sticky header menu.
		 *
		 * @todo rename to stickyHeaderOffset
		 */
		stickyOffset: function() {
			var self = this;
			var offset = 0;

			// Offset sticky topbar.
			if ( self.isVisible( document.querySelector( '#top-bar-wrap-sticky-wrapper.wpex-can-sticky #top-bar-wrap' ) ) ) {
				offset = offset + document.querySelector( '#top-bar-wrap-sticky-wrapper.wpex-can-sticky' ).getBoundingClientRect().height;
			}

			// Offset mobile menu.
			var mobileMenu = document.querySelector( '#wpex-mobile-menu-fixed-top' );
			if ( self.isVisible( mobileMenu ) ) {
				offset = offset + mobileMenu.getBoundingClientRect().height;
			}

			// Offset adminbar.
			var wpToolbar = document.querySelector( '#wpadminbar' );
			if ( self.isVisible( wpToolbar ) && 'fixed' === window.getComputedStyle( wpToolbar ).position ) {
				offset = offset + wpToolbar.getBoundingClientRect().height;
			}

			// Custom elements.
			document.querySelectorAll( '.wpex-sticky-el-offset' ).forEach( function( element ) {
				if ( self.isVisible( element ) ) {
					offset = offset + element.getBoundingClientRect().height;
				}
			} );

			// Added offset via child theme.
			if ( l10n.addStickyHeaderOffset ) {
				offset = offset + l10n.addStickyHeaderOffset;
			}

			// Return offset.
			return offset;

		},

		/**
		 * Sticky header custom start point.
		 *
		 * @todo move inside stickyHeader()
		 */
		stickyHeaderCustomStartPoint: function() {
			var startPosition = l10n.stickyHeaderStartPosition;
			if ( startPosition && ! isNaN( startPosition ) ) {
				return startPosition;
			}
			var el = document.querySelector( startPosition );
			if ( el ) {
				return this.offset( el ).top;
			}
			return 0;
		},

		/**
		 * New Sticky Header.
		 */
		stickyHeader: function() {
			var self = this;
			var stickyStyle = l10n.stickyHeaderStyle;
			if ( 'standard' !== stickyStyle && 'shrink' !== stickyStyle && 'shrink_animated' !== stickyStyle ) {
				return;
			}

			var header = document.querySelector( '#site-header.fixed-scroll' );

			if ( ! header ) {
				return;
			}

			var isSticky = false;
			var isShrunk = false;

			// Add sticky wrap.
			var stickyWrap = document.createElement( 'div' );
			stickyWrap.id = 'site-header-sticky-wrapper';
			stickyWrap.className = 'wpex-sticky-header-holder not-sticky';

			self.wrap( header, stickyWrap );

			// Define main vars for sticky function.
			var brkPoint = l10n.stickyHeaderBreakPoint;
			var mobileSupport = l10n.hasStickyMobileHeader;
			var customStart = self.stickyHeaderCustomStartPoint();

			// Shrink support.
			var shrinkEnabled = l10n.hasStickyHeaderShrink;

			// Check if we are on mobile size.
			function pastBreakPoint() {
				return ( self.viewportWidth() < brkPoint ) ? true : false;
			}

			// Check if we are past the header.
			var pastHeaderBottomCheck = 0;
			if ( document.querySelector( '#overlay-header-wrap' ) ) {
				pastHeaderBottomCheck = self.offset( header ).top + header.getBoundingClientRect().height;
			} else {
				pastHeaderBottomCheck = self.offset( stickyWrap ).top + stickyWrap.getBoundingClientRect().height;
			}
			function pastheader() {
				if ( self.winScrollTop() > pastHeaderBottomCheck ) {
					return true;
				}
				return false;
			}

			// Check start position.
			function start_position() {
				var startPosition = customStart || self.offset( stickyWrap ).top;
				return startPosition - self.stickyOffset();
			}

			// Transform.
			function transformPrepare() {
				var windowTop = self.winScrollTop();
				if ( isSticky ) {
					header.classList.add( 'transform-go' ); // prevent issues when scrolling.
				}
				if ( windowTop <= 0 ) {
					header.classList.remove( 'transform-prepare' );
				} else if ( pastheader() ) {
					header.classList.add( 'transform-prepare' );
				} else {
					header.classList.remove( 'transform-prepare' );
				}
			}

			// Set sticky.
			function setSticky() {

				// Already stuck.
				if ( isSticky ) {
					return;
				}

				// Add wrap class and toggle sticky class.
				stickyWrap.style.height = header.getBoundingClientRect().height + 'px';
				stickyWrap.classList.remove( 'not-sticky' );
				stickyWrap.classList.add( 'is-sticky' );

				// Tweak header.
				header.classList.remove( 'dyn-styles' );
				header.style.top = self.stickyOffset() + 'px';
				header.style.width = stickyWrap.getBoundingClientRect().width + 'px';

				// Add transform go class.
				if ( header.classList.contains( 'transform-prepare' ) ) {
					header.classList.add( 'transform-go' );
				}

				// Set sticky to true.
				isSticky = true;

			}

			// Shrink/unshrink header.
			function shrink() {

				var check = true; // we already check if it's enabled before running this function.

				if ( pastBreakPoint() ) {
					if ( mobileSupport && l10n.hasStickyMobileHeaderShrink ) {
						check = true;
					} else {
						check = false;
					}
				}

				if ( check && pastheader() ) {
					if ( ! isShrunk && isSticky ) {
						header.classList.add( 'sticky-header-shrunk' );
						isShrunk = true;
					}
				} else {
					header.classList.remove( 'sticky-header-shrunk' );
					isShrunk = false;
				}

			}

			// Destroy actions.
			function destroyActions() {

				// Remove sticky wrap height and toggle sticky class.
				stickyWrap.classList.remove( 'is-sticky' );
				stickyWrap.classList.add( 'not-sticky' );

				// Do not remove height on sticky header for shrink header incase animation isn't done yet.
				// Can't update shrink as it may cause issues.
				if ( ! header.classList.contains( 'shrink-sticky-header' ) ) {
					stickyWrap.style.height = ''; //@todo remove for shrink as well?
				}

				// Reset header.
				header.classList.add( 'dyn-styles' );
				header.style.width = '';
				header.style.top = '';
				header.classList.remove( 'transform-go' );

				// Set sticky to false.
				isSticky = false;

				// Make sure shrink header is removed.
				header.classList.remove( 'sticky-header-shrunk' ); // Fixes some bugs with really fast scrolling.
				isShrunk = false;

			}

			// Destroy sticky.
			function destroySticky() {

				if ( ! isSticky ) {
					return;
				}

				if ( customStart ) {
					header.classList.remove( 'transform-go' );
					if ( isShrunk ) {
						header.classList.remove( 'sticky-header-shrunk' );
						isShrunk = false;
					}
				} else {
					header.classList.remove( 'transform-prepare' );
				}

				destroyActions();

			}

			// On load check.
			function initResizeSetSticky() {

				var windowTop = self.winScrollTop();

				if ( ! mobileSupport && pastBreakPoint() ) {
					destroySticky();
					stickyWrap.classList.remove( 'wpex-can-sticky' );
					header.classList.remove( 'transform-prepare' );
					return;
				}

				//header.classList.add( 'transform-go' );
				stickyWrap.classList.add( 'wpex-can-sticky' );

				if ( isSticky ) {

					// Update header height on resize incase it's changed height.
					// Can't update shrink as it may cause issues.
					if ( ! header.classList.contains( 'shrink-sticky-header' ) ) {
						stickyWrap.style.height = header.getBoundingClientRect().height + 'px';
					}

					header.style.top = self.stickyOffset() + 'px';
					header.style.width = stickyWrap.getBoundingClientRect().width + 'px';

				} else {

					if ( 0 !== windowTop && windowTop > start_position() ) {
						setSticky();
					} else {
						destroySticky();
					}

				}

				if ( shrinkEnabled ) {
					shrink();
				}

			}

			// On scroll function.
			function onScroll() {

				var windowTop = self.winScrollTop();

				if ( ! stickyWrap.classList.contains( 'wpex-can-sticky' ) ) {
					return;
				}

				// Animate scroll with custom start.
				if ( customStart ) {
					transformPrepare();
				}

				// Set or destroy sticky.
				if ( 0 != windowTop && windowTop >= start_position() ) {
					setSticky();
				} else {
					destroySticky();
				}

				// Shrink.
				if ( shrinkEnabled ) {
					shrink();
				}

			}

			// Fire on init.
			initResizeSetSticky();

			// Fire onscroll event.
			window.addEventListener( 'scroll', onScroll, self.config.passiveListeners ? { passive: true } : false );

			// Fire onResize.
			window.addEventListener( 'resize', initResizeSetSticky );

			// Destroy and run onResize function on orientation change.
			window.addEventListener( 'orientationchange', function() {
				destroySticky();
				initResizeSetSticky();
			} );

		},

		/**
		 * Sticky Header Menu.
		 */
		stickyHeaderMenu: function() {
			var self = this;
			var stickyNav = document.querySelector( '#site-navigation-wrap.fixed-nav' );

			if ( ! stickyNav ) {
				return;
			}

			var isSticky = false;
			var header = document.querySelector( '#site-header' );

			// Define sticky wrap.
			var stickyWrap = document.createElement( 'div' );
			stickyWrap.id = 'site-navigation-sticky-wrapper';
			stickyWrap.className = 'wpex-sticky-navigation-holder not-sticky';

			self.wrap( stickyNav, stickyWrap );

			// Add offsets.
			var stickyWrapTop = self.offset( stickyWrap ).top;
			var setStickyPos = stickyWrapTop - self.stickyOffset();

			// Shrink header function.
			function setSticky() {
				if ( isSticky ) {
					return;
				}

				// Add wrap class and toggle sticky class.
				stickyWrap.style.height = stickyNav.getBoundingClientRect().height + 'px';
				stickyWrap.classList.remove( 'not-sticky' );
				stickyWrap.classList.add( 'is-sticky' );

				// Add CSS to topbar.
				stickyNav.style.top = self.stickyOffset() + 'px';
				stickyNav.style.width = stickyWrap.getBoundingClientRect().width + 'px';

				// Remove header dynamic styles.
				if ( header ) {
					header.classList.remove( 'dyn-styles' );
				}

				// Update shrunk var.
				isSticky = true;

			}

			// Un-Shrink header function.
			function destroySticky() {

				// Not shrunk
				if ( ! isSticky ) {
					return;
				}

				// Remove sticky wrap height and toggle sticky class.
				stickyWrap.style.height = '';
				stickyWrap.classList.remove( 'is-sticky' );
				stickyWrap.classList.add( 'not-sticky' );

				// Remove navbar width.
				stickyNav.style.top = '';
				stickyNav.style.width = '';

				// Re-add dynamic header styles.
				if ( header ) {
					header.classList.add( 'dyn-styles' );
				}

				// Update shrunk var.
				isSticky = false;

			}

			// On load check.
			function initResizeSetSticky() {

				if ( self.viewportWidth() <= l10n.stickyNavbarBreakPoint ) {
					destroySticky();
					stickyWrap.classList.remove( 'wpex-can-sticky' );
					return;
				}

				var windowTop = self.winScrollTop();

				stickyWrap.classList.add( 'wpex-can-sticky' );

				if ( isSticky ) {
					// Already sticky, lets update height, width and offsets.
					stickyWrap.style.height = stickyNav.getBoundingClientRect().height + 'px';
					stickyNav.style.top = self.stickyOffset() + 'px';
					stickyNav.style.width = stickyWrap.getBoundingClientRect().width + 'px';
				} else {
					if ( windowTop >= setStickyPos && 0 !== windowTop ) {
						setSticky();
					} else {
						destroySticky();
					}

				}

			}

			// Sticky check / enable-disable.
			function onScroll() {

				if ( ! stickyWrap.classList.contains( 'wpex-can-sticky' ) ) {
					return;
				}

				var windowTop = self.winScrollTop();

				// Sticky menu.
				if ( 0 !== windowTop && windowTop >= setStickyPos ) {
					setSticky();
				} else {
					destroySticky();
				}

			}

			// Fire on init.
			initResizeSetSticky();

			// Fire onscroll event.
			window.addEventListener( 'scroll', onScroll, self.config.passiveListeners ? { passive: true } : false );

			// Fire onResize.
			window.addEventListener( 'resize', initResizeSetSticky );

			// Fire resize on flip.
			window.addEventListener( 'orientationchange', function() {
				destroySticky();
				initResizeSetSticky();
			} );

		},

		/**
		 * WPBAKERY Slider & Accordions.
		 */
		vcTabsTogglesJS: function() {

			if ( ! document.body.classList.contains( 'wpb-js-composer' ) || 'function' !== typeof jQuery ) {
				return;
			}

			jQuery( document ).on( 'afterShow.vc.accordion', function( event, options ) {

				if ( 'undefined' === typeof event ) {
					return;
				}

				var $this = jQuery( event.target );
				var tab = $this.data( 'vc.accordion' );

				if ( ! tab ) {
					return;
				}

				tab = tab.getTarget();

				if ( ! tab ) {
					return;
				}

				if ( ! tab.length ) {
					return;
				}

				if ( 'function' === typeof jQuery && 'function' === typeof jQuery.fn.sliderPro ) {
					tab.find( '.wpex-slider' ).each( function() {
						if ( jQuery( this ).data( 'sliderPro' ) ) {
							jQuery( this ).sliderPro( 'update' );
						}
					} );
				}

				if ( 'function' === typeof Isotope ) {
					tab.find( '.vcex-isotope-grid, .wpex-masonry-grid, .vcex-navbar-filter-grid' ).each( function() {
						var iso = Isotope.data( this );
						if ( iso ) {
							iso.layout();
						}
					} );
				}

			} );

		},

		/**
		 * Accessability fixes/enhancements.
		 */
		accessability: function() {

			// Add tabindex -1 to nav-no-click links.
			document.querySelectorAll( 'li.nav-no-click > a, li.sidr-class-nav-no-click > a' ).forEach( function( element ) {
				if ( element.closest( '.full-screen-overlay-nav-menu' ) && element.parentNode.classList.contains( 'menu-item-has-children' ) ) {
					return; // exclude parent menu items from the full-screen overlay menu.
				}
				element.setAttribute( 'tabIndex', '-1' );
			} );

			// Allow for opening WPBakery FAQ elements with the enter button.
			document.querySelectorAll( '.vc_toggle .vc_toggle_title' ).forEach( function( element ) {
				element.setAttribute( 'tabIndex', 0 );
				element.addEventListener( 'keydown', function( event ) {
					var code = event.keyCode || event.which;
					if ( 13 === code ) {
						event.target.click();
					}
				} );
			} );

		},

		/* Helpers.
		------------------------------------------------------------------------------ */

		/**
		 * Is the DOM ready?
		 */
		domReady: function( fn ) {
			if ( typeof fn !== 'function' || 'undefined' === typeof document ) {
				return;
			}

			var readyState = document.readyState;

			// If document is already loaded, run method.
			if ( readyState === 'interactive' || readyState === 'complete' ) {
				return setTimeout( fn ); // Timeout prevents issues with dependencies when using async.
			}

			// Otherwise, wait until document is loaded.
			document.addEventListener( 'DOMContentLoaded', fn, false );

		},

		/**
		 * Retina Check.
		 */
		retinaCheck: function() {
			var mediaQuery = '(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)';
			if ( window.devicePixelRatio > 1 ) {
				return true;
			}
			if ( window.matchMedia && window.matchMedia( mediaQuery ).matches ) {
				return true;
			}
			return false;
		},

		/**
		 * Mobile Check.
		 */
		mobileCheck: function() {
			if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
				return true;
			}
		},

		/**
		 * Check if passive event listeners are supported by the browser.
		 */
		passiveListenersSupport: function() {
			var supportsPassive = false;
			try {
				var opts = Object.defineProperty({}, 'passive', {
				get: function() {
						supportsPassive = true;
					}
				} );
				window.addEventListener( 'testPassive', null, opts);
				window.removeEventListener( 'testPassive', null, opts);
			} catch (e) {}
			return supportsPassive;
		},

		/**
		 * Get easing value.
		 */
		getEasing: function( customEasing ) {
			if ( ! l10n.localScrollEasing || 'function' !== typeof jQuery || 'undefined' === typeof jQuery.easing || 'function' !== typeof jQuery.easing.jswing ) {
				return;
			}
			var easing = customEasing || l10n.localScrollEasing;
			if ( ! jQuery.easing.hasOwnProperty( easing ) ) {
				easing = 'swing';
			}
			return easing;
		},

		/**
		 * Viewport width.
		 */
		viewportWidth: function() {
			var e = window, a = 'inner';
			if ( ! ( 'innerWidth' in window ) ) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return e[ a+'Width' ];
		},

		/**
		 * Check if a given selector is valid.
		 */
		isSelectorValid: function( selector ) {
			var queryCheck = function( s ) {
				document.createDocumentFragment().querySelector( s );
			};
			try {
				queryCheck( selector );
			} catch( error ) {
				return false;
			}
			return true;
		},

		/**
		 * SlideUp element.
		 */
		slideUp: function( target, duration, callback ) {

			// Sanity check.
			if ( ! target ) {
				return;
			}

			// Get current element display.
			var display = window.getComputedStyle( target ).display;

			// Element already closed.
			if ( 'none' === display ) {
				return;
			}

			// Allow for CSS defined transition duration.
			var elDuration = window.getComputedStyle( target ).transitionDuration;

			if ( ! elDuration || '0s' !== elDuration ) {
				duration = parseFloat( elDuration ) * ( elDuration.indexOf( 'ms' ) >- 1 ? 1 : 1000 );
			}

			if ( ! duration ) {
				duration = 300;
			}

			// Add classname that we can check to prevent from doing other things during transition.
			target.classList.add( 'wpex-transitioning' );

			// Set transition duration for animation.
			target.style.transitionProperty = 'height, margin, padding';
			target.style.transitionDuration = duration + 'ms';

			// Set element height.
			target.style.height = target.offsetHeight + 'px';
			target.offsetHeight; // get height so that browser re-paints element.
			target.style.overflow = 'hidden';

			// Reset element height.
			target.style.height = 0;
			target.style.paddingTop = 0;
			target.style.paddingBottom = 0;
			target.style.marginTop = 0;
			target.style.marginBottom = 0;

			// Remove properties after animation has finished
			setTimeout( function() {
				target.style.display = 'none';
				target.style.removeProperty( 'height' );
				target.style.removeProperty( 'padding-top' );
				target.style.removeProperty( 'padding-bottom' );
				target.style.removeProperty( 'margin-top' );
				target.style.removeProperty( 'margin-bottom' );
				target.style.removeProperty( 'overflow' );
				target.style.removeProperty( 'transition-duration' );
				target.style.removeProperty( 'transition-property' );
				target.classList.remove( 'wpex-transitioning' );
				if ( callback ) {
					callback();
				}
			}, duration );

		},

		/**
		 * SlideDown element.
		 */
		slideDown: function( target, duration, callback ) {

			// Sanity check.
			if ( ! target ) {
				return;
			}

			// Get current element display.
			var display = window.getComputedStyle( target ).display;

			// Already open.
			if ( 'block' === display ) {
				return;
			}

			// Allow for CSS defined transition duration.
			var elDuration = window.getComputedStyle( target ).transitionDuration;

			if ( ! elDuration || '0s' !== elDuration ) {
				duration = parseFloat( elDuration ) * ( elDuration.indexOf( 'ms' ) >- 1 ? 1 : 1000 );
			}

			if ( ! duration ) {
				duration = 300;
			}

			// Add classname that we can check to prevent from doing other things during transition.
			target.classList.add( 'wpex-transitioning' );

			// Remove inline display if it had previously been toggled.
			target.style.removeProperty( 'display' );

			// Element needs to be visible to calculate height and animate.
			if ( 'none' === display ) {
				display = 'block';
			}

			target.style.display = display;
			target.style.transitionProperty = 'none'; // prevent possible animation when calculating height.

			var height = target.offsetHeight;

			// Reset height so we can animate it.
			target.style.overflow = 'hidden';
			target.style.height = 0;
			target.style.paddingTop = 0;
			target.style.paddingBottom = 0;
			target.style.marginTop = 0;
			target.style.marginBottom = 0;
			target.offsetHeight; // get height so that browser re-paints element.
			target.style.boxSizing = 'border-box';

			// Add transition duration for animation.
			target.style.transitionProperty = 'height, margin, padding';
			target.style.transitionDuration = duration + 'ms';

			// Set element height using a timeout otherwise animation won't work.
			target.style.height = height + 'px';
			target.style.removeProperty( 'padding-top' );
			target.style.removeProperty( 'padding-bottom' );
			target.style.removeProperty( 'margin-top' );
			target.style.removeProperty( 'margin-bottom' );

			// Remove properties after animation has finished.
			setTimeout( function() {
				target.style.removeProperty( 'height' );
				target.style.removeProperty( 'overflow' );
				target.style.removeProperty( 'transition-duration' );
				target.style.removeProperty( 'transition-property' );
				target.classList.remove( 'wpex-transitioning' );
				if ( callback ) {
					callback();
				}
			}, duration );

		},

		/**
		 * Set correct focus states for custom elements.
		 *
		 * @param {HTMLElement} el
		 */
		focusOnElement: function( element, initialFocus ) {
			var self = this;
			var focusElements = element.querySelectorAll( 'button, [href], input, select, textarea, a,[tabindex]:not([tabindex="-1"])' );

			if ( ! focusElements.length ) {
				return;
			}

			var focus = [];

			for ( var i=0; i < focusElements.length; i++ ) {
				if ( self.isVisible( focusElements[i] ) ){
					focus.push( focusElements[i] );
				}
			}

			if ( ! focus.length ) {
				return;
			}

			var firstFocus = focus[0];
			var lastFocus = focus[focus.length - 1];

			// Add initial focus.
			if ( initialFocus ) {
				initialFocus.focus();
			} else {
				firstFocus.focus();
			}

			// Redirect last tab to first input.
			lastFocus.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 9 === code && ! event.shiftKey ) {
					event.preventDefault();
					firstFocus.focus();
				}
			} );

			// Redirect first shift+tab to last input.
			firstFocus.addEventListener( 'keydown', function( event ) {
				var code = event.keyCode || event.which;
				if ( 9 === code && event.shiftKey ) {
					event.preventDefault();
					lastFocus.focus();
				}
			} );

		},

		/**
		 * Wrap an element.
		 */
		wrap: function( element, wrapper ) {
			if ( ! element.childNodes ) {
				element = [element];
			}
			if ( element.nextSibling ) {
				element.parentNode.insertBefore( wrapper, element.nextSibling );
			} else {
				element.parentNode.appendChild( wrapper );
			}
			wrapper.appendChild( element );
		},

		/**
		 * Insert element after another.
		 */
		insertAfter: function( newNode, referenceNode ) {
			referenceNode.parentNode.insertBefore( newNode, referenceNode.nextSibling );
		},

		/**
		 * Get element offset.
		 */
		offset: function( element ) {
			var rect = element.getBoundingClientRect();
			return {
				top: rect.top + this.winScrollTop(),
				left: rect.left + this.winScrollTop(),
			};
		},

		/**
		 * Check if element is visible.
		 */
		isVisible: function( element ) {
			if ( ! element ) {
				return false;
			}
			return !!( element.offsetWidth || element.offsetHeight || element.getClientRects().length );
		},

		/**
		 * Check if element is empty
		 */
		isEmpty: function( element ) {
			return ! element || '' === element.innerHTML;
		},

		/**
		 * Grabs content and inserts into another element.
		 */
		insertExtras: function( element, target, method ) {
			if ( ! element || ! target ) {
				return;
			}
			switch ( method ) {
				case 'append':
					target.appendChild( element );
					break;
				case 'prepend':
					target.insertBefore( element, target.firstChild );
					break;
			}
			element.classList.remove( 'wpex-hidden' );
		},

		/**
		 * Returns extension from URL.
		 *
		 * @todo move into autoLightbox.
		 */
		getUrlExtension: function( url ) {
			var ext = url.split( '.' ).pop().toLowerCase();
			var extra = ext.indexOf( '?' ) !== -1 ? ext.split( '?' ).pop() : '';
			ext = ext.replace( extra, '' );
			return ext.replace( '?', '' );
		},

		/**
		 * Returns the window scrollTop position.
		 */
		winScrollTop: function() {
			var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
			if ( scrollTop < 0 ) {
				scrollTop = 0; // return 0 if negative to prevent issues with elastic scrolling in Safari.
			}
			return scrollTop;
		},

		/**
		 * Check if window has scrolled to bottom of element.
		 */
		scrolledToBottom: function( element ) {
			var check = this.winScrollTop() >= element.offsetTop + element.offsetHeight - window.innerHeight;
			return check;
		},

		/**
		 * Parses data attribute and returns object.
		 */
		parseObjectLiteralData: function( data ) {
			if ( 'function' !== typeof jQuery ) {
				return;
			}
			var properties = data.split( ',' );
			var obj = {};
			jQuery.each( properties, function(index, item) {
				var tup = item.split(':');
				obj[tup[0]] = tup[1];
			} );
			return obj;
		},

		/**
		 * Remove class prefix.
		 */
		removeClassPrefix: function( elements, regex, prefix ) {
			elements.forEach( function( element ) {
				var classes = element.classList;
				for ( var j=0; j < classes.length; j++ ) {
					if ( regex.test( classes[j] ) ) {
						var newclass = classes[j].replace( prefix, '' );
						element.classList.replace( classes[j], newclass );
					}
				}
			} );
		},

		/**
		 * Creates accordion menu.
		 */
		menuAccordion: function( menu ) {
			if ( ! menu ) {
				return;
			}

			var self = this;
			var isAnimating = false;

			// Add toggle buttons.
			menu.querySelectorAll( '.menu-item-has-children, .sidr-class-menu-item-has-children' ).forEach( function( menuItem ) {
				var link = menuItem.querySelector( 'a' );
				if ( ! link ) {
					return;
				}

				var toggleBtn = document.createElement( 'button' );
				toggleBtn.className = 'wpex-open-submenu';
				toggleBtn.setAttribute( 'aria-haspopup', 'true' );
				toggleBtn.setAttribute( 'aria-expanded', 'false' );
				toggleBtn.setAttribute( 'aria-label', l10n.i18n.openSubmenu.replace( '%s', link.textContent.trim() ) );

				var toggleBtnIcon = document.createElement( 'span' );
				toggleBtnIcon.className = 'ticon ticon-angle-down';
				toggleBtnIcon.setAttribute( 'aria-hidden', 'true' );

				toggleBtn.appendChild( toggleBtnIcon );
				link.appendChild( toggleBtn );
			} );

			var closeSubmenu = function( submenu ) {
				var li = submenu.closest( 'li.active' );
				li.classList.remove( 'active' );
				var link = li.querySelector( 'a' );
				var toggle = li.querySelector( '.wpex-open-submenu' );
				toggle.setAttribute( 'aria-expanded', 'false' );
				toggle.setAttribute( 'aria-label', l10n.i18n.openSubmenu.replace( '%s', link.textContent.trim() ) );
				isAnimating = true;
				self.slideUp( submenu, null, function() {
					isAnimating = false;
				} );
			};

			// Add click event.
			document.addEventListener( 'click', function( event ) {
				var button = event.target.closest( '.wpex-open-submenu' );
				if ( ! button || ! menu.contains( button ) ) {
					return;
				}

				var li = button.closest( 'li' );

				if ( ! li ) {
					return;
				}

				var ul = li.querySelector( 'ul' );

				if ( ! ul ) {
					return;
				}

				var link = li.querySelector( 'a' );

				if ( ! link ) {
					return;
				}

				event.preventDefault();
				event.stopPropagation(); // needed since button is inside link.

				if ( isAnimating ) {
					return; // prevent click spam.
				}

				// Closing.
				if ( li.classList.contains( 'active' ) ) {

					// Close child submenus.
					li.querySelectorAll( 'li.active > ul' ).forEach( function( submenu ) {
						closeSubmenu( submenu );
					} );

					// Close self.
					li.classList.remove( 'active' );
					button.setAttribute( 'aria-expanded', 'false' );
					button.setAttribute( 'aria-label', l10n.i18n.openSubmenu.replace( '%s', link.textContent.trim() ) );
					isAnimating = true;
					self.slideUp( ul, null, function() {
						isAnimating = false;
					} );
				}

				// Opening.
				else {
					button.setAttribute( 'aria-expanded', 'true' );
					button.setAttribute( 'aria-label', l10n.i18n.closeSubmenu.replace( '%s', link.textContent.trim() ) );

					// Close all open submenus that arent parents of this submenu.
					menu.querySelectorAll( 'li.active > ul' ).forEach( function( submenu ) {
						if ( ! submenu.contains( ul ) ) {
							closeSubmenu( submenu );
						}
					} );

					// Open self.
					isAnimating = true;
					self.slideDown( ul, null, function() {
						isAnimating = false;
					} );
					li.classList.add( 'active' );
				}

			} );

		},

		/**
		 * Fallbacks for old functions.
		 *
		 * @todo deprecate
		 */
		sliderPro: function( $context ) {
			if ( 'function' === typeof window.wpexSliderPro ) {
				wpexSliderPro();
			}
		},
		loadMore: function() {
			if ( 'function' === typeof window.wpexLoadMore ) {
				wpexLoadMore();
			}
		}

	};

	// Start things up
	wpex.init();

} ) ( wpex_theme_params );