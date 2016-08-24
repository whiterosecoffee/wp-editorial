/* Infiniscroll plugin
 *
 * Fetches articles for containers that have a `data-infiniscroll` attribute, from categories specified by that attribute, as a comma separated list of category ids. When one category runs out and returns a 404 for an article, infiniscroll switches to the next category, if there are any left.
 *
 * The article template can be specified by using the `data-template` attribute.
 */

function jsonDecoder( done ) {
	return function( json ) {
		return done( JSON.parse( json ));
	};
}

jQuery( function( $ ) {
	var url = theme.router; // The path to the API endpoint, specified in an inline script inserted by wp_localize_script
	var extraContentArea = $( window ).height() * 2; // in pixels

	$.fn.infiniscroll = function() {

		$( this ).each( function() {

			var $context = $( this );
			var id = $context.attr( 'id' );
			var categories = $context.attr( 'data-feeds' );
			var template = $context.attr( 'data-template' );
			var initialLoadCount = $context.attr( 'data-initial-load-count' ) || 1;
			var fetching = false;

			function fetchCategorizedArticlesIds( done ) {
				console.log('categories:', categories);
				var request = url +
					'?action=select-articles-from-categories' +
					'&categories=' + categories;

				$.get( request )
				.done( jsonDecoder( done ))
				.fail( function( response ) {
					console.log( 'Failed to retrieve categorized article list:', response );
				});
			}

			function fetchNewestArticlesIds( done ) {
				var request = url + '?action=select-newest-articles';

				$.get( request )
				.done( jsonDecoder( done ))
				.fail( function( response ) {
					console.log( 'Failed to retrieve newest articles list:', response );
				});
			}

			function fetchArticlesContent( ids, done ) {
				fetching = true;

				var request = url +
					'?action=render-article-list' +
					'&ids=' + ids.join( ',' ) +
					'&template=' + template;

				$.get( request )
				.done( done )
				.fail( function( response ) {
					if( response.status == 404 ) {
						console.log( 'Out of articles.' );
					} else {
						console.log( 'Host unexpectedly failed to deliver an article: ', response );
					}
				})
				.always( function() {
					fetching = false;
				});
			}

			function receiveArticles( articleHtml ) {
				$context.append( articleHtml );
			}

			// Determine how much verticle space needs to be filled with articles, measured in article heights.
			function articlesNeeded( errorMargin ) {
				var windowBottom = $( window ).scrollTop() + $( window ).innerHeight();
				var elementBottom = $context.offset().top + $context.height();
				var spacePx = windowBottom + errorMargin - elementBottom;

				if( spacePx <= 0 ) {
					// If there no space or less, then we do not need any more articles right now.
					return 0;
				}

				var articleHeight = $context.children().first().height();

				if( articleHeight === null ) {
					// No articles available for measurement. Load an arbitrary number for now.
					return initialLoadCount;
				} else {
					// Return the amount of space in article heights, rounding up.
					return Math.ceil( spacePx / articleHeight );
				}
			}

			// Repeatedly fetch articles until fetch conditions are not met
			function fetchArticleWhileNecessary( articleIds ) {
				if( !fetching && articleIds.length ) {
					var articlesToRequest = articlesNeeded( extraContentArea );
					var articlesSubset = articleIds.splice( 0, articlesToRequest );

					if( 0 < articlesToRequest ) {
						fetchArticlesContent( articlesSubset, receiveArticles );
					}
				}

				return articleIds; // Unused ids
			}

			function watchArticlesNeeded( articleIds ) {

				// Load articles on scroll, if necessary, unless we are already in the process of fetching articles.
				$( window ).scroll( function() {
					if( !fetching ) {
						articleIds = fetchArticleWhileNecessary( articleIds );
					}
				});

				// Load articles on resize, if necessary, unless we are already in the process of fetching articles.
				$( window ).resize( function() {
					if( !fetching ) {
						articleIds = fetchArticleWhileNecessary( articleIds );
					}
				});

				// Check if articles are needed every second.
				setInterval( function() {
					if( !fetching ) {
						articleIds = fetchArticleWhileNecessary( articleIds );
					}
				}, 1000 );

				// Load articles on page load.
				articleIds = fetchArticleWhileNecessary( articleIds );
			}

			if( categories ) {
				fetchCategorizedArticlesIds( watchArticlesNeeded );
			} else {
				fetchNewestArticlesIds( watchArticlesNeeded );
			}
		});
	};

	// Apply this plugin automatically to all article containers.
	$( '[data-infiniscroll]' ).infiniscroll();
});
