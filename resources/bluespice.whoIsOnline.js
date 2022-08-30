/**
 * Js for WhoIsOnline extension
 *
 * @author     Patric Wirth
 * @package    Bluespice_Extensions
 * @subpackage WhoIsOnline
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */
( function( mw, $, bs, d ){
	var interval = mw.config.get( 'bsgWhoIsOnlineInterval', 0 ) * 1000;
	if ( interval < 1 ) {
		return;
	}
	if ( !String.prototype.startsWith ) {
		// old browsers
		Object.defineProperty( String.prototype, 'startsWith', {
			value: function(search, rawPos) {
				var pos = rawPos > 0 ? rawPos|0 : 0;
				return this.substring(pos, pos + search.length) === search;
			}
		} );
	}
	var markers = {};
	var onlineMarkers = function() {
		$( '.bs-userminiprofile[data-bs-whoisonline-marker]' ).each( function() {
			var userName = $( this ).data( 'bs-whoisonline-marker' );
			if ( !userName || userName.length < 1 ) {
				return;
			}
			markers[userName] = 'unchecked';
		} );
		return markers;
	};
	var listener = function( result, Listener ) {
		if( result.success !== true ) {
			return;
		}

		markers = result['onlinemarkers'];
		$( '.bs-userminiprofile[data-bs-whoisonline-marker]' ).removeClass( function ( index, classString ) {
			var classes = classString.split(" "),
				remove = [];
			for ( var i = 0; classes.length > i; i++ ) {
				if ( classes[i].startsWith( 'bs-whoisonline-marker-' ) !== true ) {
					continue;
				}
				remove.push( classes[i] );
			}
			return remove.join(" ");
		} );

		$( '.bs-userminiprofile[data-bs-whoisonline-marker]' ).each( function() {
			var userName = $( this ).data( 'bs-whoisonline-marker' );
			if ( !userName || userName.length < 1 ) {
				return;
			}
			$( this ).addClass(
				'bs-whoisonline-marker-' + ( markers[userName] ? markers[userName] : 'unchecked' )
			);
		} );

		$( '.bs-whoisonline-portlet' ).each( function(){
			var portlet = result['portletItems'];
			$( this ).html( portlet );
		});

		$( '.bs-whoisonline-count').each( function(){
			$( this ).html( result['count'] );
		} );

		BSPing.registerListener(
			'WhoIsOnline',
			interval,
			[ { 'onlinemarkers': onlineMarkers() } ],
			listener
		);
	};
	BSPing.registerListener(
		'WhoIsOnline',
		interval,
		[ { 'onlinemarkers': onlineMarkers() } ],
		listener
	);

	$('.wo-link').on( 'mouseover', function ( e ) {
		$tooltip = $( this )[0].nextSibling;
		if ( !$( $tooltip ).hasClass( 'show' ) ) {
			$( $tooltip ).addClass( 'show' );
			$( $tooltip ).css( { 'left': this.offsetLeft } );
		}
		setTimeout( function () {
			$( $tooltip ).removeClass( 'show' );
		}, 5000 );
	} );

} )( mediaWiki, jQuery, blueSpice, document );