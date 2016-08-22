// Page views update
(function ($){

	$( '[data-page-views-indicator]' ).hide();

	$(document).on('ready', function() {
		// If not article detail page then return
		if( $( '.article-detail' ).size() ==! 0 ) {
			return true; //Nathan - temp turn off PK article counter due to bad requests


			var postId = parseInt( $( 'article' ).attr( 'data-article-id' ) );

			$.post( backend_object.ajax_url, 
				{ action: 'increment_views', _wpnonce: backend_object.mp_nonce_increment_views, post_id: postId } )
			.done( function(response) {
				if(response !== -1 && response.success) {
					$( '[data-page-views-indicator]' ).text( response.data );
				}
			}).always(function (){
				$( '[data-page-views-indicator]' ).show();
			});

			// If the page contains list/grid of articles	
		} else if( $('article:has([data-page-views-indicator="readonly"])').size() !== 0 ) {

			var postIds = [];

			$('article:has([data-page-views-indicator="readonly"])').each(function (e) {
				postIds.push( $(this).attr('data-article-id') );
			});

			$.post( backend_object.ajax_url, 
				{ action: 'get_views', _wpnonce: backend_object.mp_nonce_get_views, post_ids: postIds } )
			.done( function(response) {
				if(response !== -1 && response.success) {
					response.data.forEach(function (e) {
						$('article[data-article-id="' + e.post_id + '"] [data-page-views-indicator="readonly"]').text( e.views );												
					});
				}
			}).always(function (){
				$( '[data-page-views-indicator]' ).show();
			});	
		}

	});



})(jQuery);

(function() {
	// Google Plus
	window.___gcfg = {lang: 'ar'};

	(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/platform.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();
})();

(function($) {

	$.ajaxSetup({
		beforeSend: function() {
			$('#loading').show();
		},
		complete: function() {
			$('#loading').hide();
		}
	});

	// Opens the social link in a popup.
	(function() {
		var social_links = $('.social-link');

		if( !backend_object.sharing_enabled ) {
			social_links.removeAttr( 'target' );
			social_links.attr( 'href', '#0' );
			return false;
		}

		function getPermalink( elem ) {
			var article = elem.closest('article');
			
			if( !article.size() ) {
				article = $( 'article' );
			}

			var permalink = elem.attr( 'href' ).replace( '{URL}', article.attr( 'data-perma-link' ) );
			if( elem.attr( 'data-activity-name' ) == 'twitter' && permalink.indexOf( '{TITLE}' ) != -1 ) {
				permalink = permalink.replace( '{TITLE}', encodeURIComponent( article.attr( 'data-title' ) ) );
			}
			return permalink;
		}

		if(social_links.size()) {
			$( 'body' ).on( 'click', '.social-link', function (event) {
				var elem = $( this );
				var permalink = getPermalink( elem );
				if( elem.hasClass('nopopup') || elem.parent().is( '[data-ios-only]' ) ) {
					elem.attr('href', permalink );
					return true;
				} 

				event.preventDefault();
				var new_window = window.open(
					permalink,
					'Social Sharing',
					'height=500,width=600');
				new_window.focus();
				return false;
			});
		}	
	})();


	// Activity Value

	(function () {
		var activityIndicator = $( 'article[data-activity-update="true"]');

		if( !activityIndicator.size() ) {
			return;
		}

		var twitterApi = "http://urls.api.twitter.com/1/urls/count.json?callback=?&url={URL}";
		var facebookApi = "https://api.facebook.com/method/links.getStats?urls={URL}&format=json";
		var linkedinApi = "http://www.linkedin.com/countserv/count/share?callback=?&url={URL}&format=jsonp";
		var pinInterestApi = "http://api.pinterest.com/v1/urls/count.json?callback=?&url={URL}";
		var googlePlusApi = backend_object.ajax_url;
		var readingListApi = backend_object.ajax_url;

		function getUrl( api, url ) {
			return api.replace( '{URL}', url );
		}

		function updateCount( type, newCount, elem ) {
			type = type.replace( '_', '-' );
			var oldCount = parseInt( elem.attr('data-activity-' + type) );
			newCount = parseInt( newCount );
			if( oldCount != newCount ) {
				var totalElem = elem;
				var oldTotal = parseInt( totalElem.attr( 'data-activity-total' ) );
				var newTotal = oldTotal - oldCount + newCount;

				$.getJSON( backend_object.ajax_url, { 
					action: 'update_activity_value', 
					type: type.replace( '-', '_' ), 
					mp_nonce: backend_object.mp_nonce_update_activity_value,
					count: newCount, 
					postId : elem.attr('data-article-id') 
				} ).done( function( data ) { 
					if( data != -1 )  {
						elem.attr('data-activity-' + type, data.new_count );
						totalElem.attr( 'data-activity-total', data.total );
						$( '[data-activity-indicator]' ).text( data.total_str );
						$('[data-activity-show="' + type + '"]').text( data.new_count_str );
					}
				} );
			}
		}

		function getTwitterCount( url, elem ) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: getUrl( twitterApi, url ),
				success: function (data) {
					updateCount( 'twitter', data.count, elem );
				}
			});
		}

		function getFacebookCount( url, elem ) {
			$.getJSON( getUrl( facebookApi, url ) ).done( function (data) {
				updateCount( 'facebook_like', data[0].like_count, elem );
				updateCount( 'facebook_share', data[0].share_count, elem );
				updateCount( 'comment', data[0].commentsbox_count, elem );
			});
		}
		
		function getGooglePlusCount( url, elem ) {
			$.getJSON( googlePlusApi, { 
				url: url, 
				action: 'google_plus_count', 
				type: 'googlePlus',
				mp_nonce: backend_object.mp_nonce_google_plus_count,  
			} ).done( function( data ){
				if( data != -1 )
					updateCount( 'googleplus', data.count, elem );
			});

		}

		function getPinInterestCount( url, elem ) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: getUrl( pinInterestApi, url ),
				success: function (data) {
					updateCount( 'pininterest', data.count, elem );
				}
			});
		}

		function getLinkedInCount( url, elem ) {
			$.ajax({
				type: 'GET',
				dataType: 'jsonp',
				url: getUrl( linkedinApi, url ),
				success: function (data) {
					updateCount( 'linkedin', data.count, elem );
				}
			});
		}

		function getReadingListCount( url, elem ) {
			$.getJSON( readingListApi, { 
				post_id: elem.closest( 'article' ).attr( 'data-article-id' ), 
				action: 'reading_list_count',
				mp_nonce: backend_object.mp_nonce_get_bookmark_count, 
			} ).done( function( data ){
					if( data != -1)
						updateCount( 'bookmark', data, elem );
				});
		}

		activityIndicator.each( function (index, elem) {
			var $this = $( this );
			var url = $this.closest( 'article' ).attr( 'data-activity-url' );
			var fullUrl = $this.closest( 'article' ).attr( 'data-activity-full-url' );
			getTwitterCount( fullUrl, $this );
			getFacebookCount( fullUrl, $this );
			getGooglePlusCount( url, $this );
			getReadingListCount( url, $this );
			// getPinInterestCount( url, $this );
			// getLinkedInCount( url, $this );

			// $this.closest( 'article' ).find( '[data-activity-name="email"]' ).on( 'click', function (event) {
			// 	updateCount( 'email', parseInt($this.attr('data-activity-email')) + 1, $this );	
			// 	return false;
			// });
		});

		

	})();



	// Reading List
	(function () {
		var elem = $('[data-action="reading-list"]');

		if( localStorage && backend_object.logged_in_id != 0 ) {
			var articleId = localStorage.getItem( 'addToReadingList' );

			if( articleId ) {
				localStorage.removeItem( 'addToReadingList' );
				var articleReadingListIcon = $( 'article[data-article-id="' + articleId + '"] [data-action="reading-list"]' );
				if(articleReadingListIcon.attr("data-complete") == "true") {
					return;
				}
				if( articleReadingListIcon.size() ) {
					$(window).load( function() {
						articleReadingListIcon.focus();
						articleReadingListIcon.click();
					});
				}
			}

		}


		if(!elem)
			return;
		$('body').on('click', '[data-action="reading-list"]', function(event) {
			elem = $(event.target || event.srcElement);
			// If not logged in, redirect to login page.
			if( backend_object.logged_in_id == 0 ) {
				$('#loginModal').modal('show');
				event.preventDefault();
				event.stopPropagation();
				if( localStorage ) {
					localStorage.setItem( 'addToReadingList', elem.closest( 'article' ).attr('data-article-id') );
				}

				return;
			}

			// Get the id
			var data = {
				post_id: elem.closest('article').attr('data-article-id')
			};

			// Add to reading list
			if(elem.attr('data-command') == "add") {
				if(elem.attr("data-complete") == "true") {
					return;
				}
				data.action = 'add_to_reading_list';
				data._wpnonce = backend_object.mp_nonce_add;
				$.post(backend_object.ajax_url, data, function(response) {
					if(response == "Success") {
						elem.attr("data-complete", "true");
						elem.trigger( 'added-to-reading-list' );
					} else {
						//alert("An error occured while performing the action.");
					} 
				}); 
			} 
			event.stopPropagation();
			return false;
		});

		$('body').on('click touchstart', '[data-complete="true"]', function (event) {
				var elem = $( this );
				var data = {};
				data.post_id = elem.closest( 'article' ).attr( 'data-article-id' );
				data.action = 'remove_from_reading_list';
				data._wpnonce = backend_object.mp_nonce_remove;
				$.post(backend_object.ajax_url, data, function(response) {
					if(response == "Success") {
						elem.attr("data-complete", "false");
						elem.trigger( 'removed-from-reading-list' );
					} else {
						//alert("An error occured while performing the action.");
					}
				});
				event.stopPropagation();
				return false;
			});

		$('body').on('removed-from-reading-list', '[data-action="reading-list"]', function() {
			$( this ).removeClass( 'active' );	
		});

		var contentLoading = false;

		$( '#view-more-button' ).on( 'click touchstart', function ( e ) {
			var elem = $( e.target || e.srcElement );
			var page = $('[data-page]').attr('data-page').split(' ')[0];


			var data = {
				action: 'mp_' + page + '_load_more',
				mp_nonce: backend_object.mp_nonce_load_more,
				start: $('#article-teasers [data-article-id]').length,
				filter: $( '[data-article-filter]' ).attr( 'data-article-filter' )
			};

			switch( page ) {
				case 'home': 
					data.category = $('[data-query-key="category"] .active').attr('data-query-value');
					if( $('[data-sub-category]').size() )
						data.subcategory = $('[data-sub-category]').attr('data-sub-category');
					break;
				case 'profile':
					data.view = $( '[data-article-view]' ).attr('data-article-view');
					if( data.view == "bookmarks" ) {
						data.start = parseInt( $( '[data-article-count]' ).attr( 'data-article-count' ) );
					}
					break;
				case 'mood-landing':
					data.mood = $( '[data-article-mood]' ).attr('data-article-mood');
					break;	
				case 'post-tag-landing':
					data.post_tag = $( '[data-article-post-tag]' ).attr('data-article-post-tag');

					if( $( '[data-article-sub-post-tag]' ).size() ) {
						data.sub_post_tag = $( '[data-article-sub-post-tag]' ).attr('data-article-sub-post-tag');
					}
					if( $( '[data-series-sub-tag]' ).size() ) {
						data.series_sub_tag = $( '[data-series-sub-tag]' ).attr('data-series-sub-tag');
					}
					break;
				case 'author':
					data.author_id = $( '[data-article-author]' ).attr('data-article-author');
					break;
				case 'explore':
					data.reading_time = $( '[data-article-reading-time]' ).attr('data-article-reading-time');
					data.mood = $( '[data-article-mood]' ).attr('data-article-mood');
					break;
			}

			$.ajax( {
				url: backend_object.ajax_url,
				method: 'get',
				data: data,
				success: function ( response ) {
					response.data.forEach( function ( entry ) {
						entry = $.parseJSON( entry );
						$( '#view-more-button' ).trigger( 'list-updated', entry );
						$.tmpl( $( '#item-template' ), entry ).appendTo( $('#article-list-grid-view') );
						$( '#article-list-grid-view' ).trigger( 'list-updated' );
						var totalCount = parseInt( $('[data-total-articles]').attr( 'data-total-articles' ) );
						if( totalCount == $('#article-teasers [data-article-id]').length)
							$('[data-total-articles]').parent().hide();
						
						if( !backend_object.sharing_enabled ) {
							$( '.social-link' ).removeAttr( 'target' ).attr( 'href', '#0' );
						}

						if( $( '[data-article-count]' ).size() ) {
							var oldCount = parseInt( $('[data-article-count]').attr( 'data-article-count' ) );
							$( '[data-article-count]' ).attr( 'data-article-count', oldCount + 1 );
						}
					} );
					contentLoading = false;
				},
				beforeSend: function() {
					$( '#view-more-button i' ).removeClass( 'icon-plus-circled ' ).addClass( 'icon-loading animate-spin' );
					$( '#view-more-button' ).attr( 'disabled', true );
				},
				complete: function() {
					$( '#view-more-button i' ).addClass( 'icon-plus-circled ' ).removeClass( 'icon-loading animate-spin' );	
					$( '#view-more-button' ).attr( 'disabled', false );
				}
			});
		});

		// Infinite Scrolling.
		if( $( '#view-more-button' ).size() ) {
			$(window).scroll(function() {

			    var wintop = $(window).scrollTop(), 
			    	docheight = $(document).height(), 
			    	winheight = $(window).height();
			    var scrolltrigger = 0.8;

			    if ( $( '#view-more-button-container' ).is( ':visible' ) && 
			    		!contentLoading && ( (wintop/(docheight-winheight)) > scrolltrigger ) ) {
			    	contentLoading = true;
			    	$( '#view-more-button' ).trigger( 'click' );
			    }
			});
		}

	})();

	
})(jQuery);
