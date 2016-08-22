// Check if the browser is outdated - redirect to error page.
(function($) {
	if( $.browser.msie && parseInt( $.browser.version ) <= 8 ) {
		document.location.href = '/browserupgrade';
	}
})( jQuery );

if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  var msViewportStyle = document.createElement('style')
  msViewportStyle.appendChild(
    document.createTextNode(
      '@-ms-viewport{width:auto!important}'
    )
  )
  document.querySelector('head').appendChild(msViewportStyle)
}

function viewport() {
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
}

function isElementInViewport (el) {

    //special bonus for those using jQuery
    if (el instanceof jQuery) {
        el = el[0];
    }

    var rect = el.getBoundingClientRect();

    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

//TAG CLASS Get and Set
function saveLastTagClass() {
   localStorage.setItem( 'tag-class',  jQuery('.article-tags-filter a.active').attr('title') || '*');
}

function saveReferrer(){
    localStorage.setItem( 'last-referer', document.referrer);
}
function getLastTagClass() {
    return localStorage.getItem( 'tag-class' );
}


// Persists state on backpress
(function ($) {

	function isHomePage() {
		return $( '[data-page="home"]' ).size() > 0;
	}

	function saveLastPage() {
		localStorage.setItem( 'last-page', $( '[data-page]' ).attr('data-page') );
	}

	function getLastPage() {
		return localStorage.getItem( 'last-page' );
	}

	function saveLastPageUrl() {
		if( localStorage ) {
			localStorage.setItem( 'last-page-url', document.URL );
		}
	}

	function getLastPageUrl() {
		if( localStorage ) {
			return localStorage.getItem( 'last-page-url' );
		}
	}

	function saveArticleId( articleId ) {
		if( localStorage ) {
			localStorage.setItem( 'article-id', articleId );
		}
	}

	function getArticleId() {
		if( localStorage ) {
			return localStorage.getItem( 'article-id' );
		}
	}

	function removeArticleId() {
		localStorage.removeItem( 'article-id' );
	}

	function saveArticleUrl( url ) {
		if( localStorage ) {
			localStorage.setItem( 'article-url', url );
		}
	}

	function getArticleUrl() {
		if( localStorage ) {
			return localStorage.getItem( 'article-url' );
		}
	}

	function saveArticlesList( article ) {
		var articlesList = localStorage.getItem( 'articles-list' );
		if( articlesList ) {
			articlesList = JSON.parse( articlesList );
			articlesList.push( article );
			localStorage.setItem( 'articles-list',  JSON.stringify( articlesList ) );
		} else {
			var list = [];
			list.push( article );
			localStorage.setItem( 'articles-list',  JSON.stringify( list ) );
		}
	}

	function clearLocalStorage() {
		localStorage.removeItem( 'articles-list' );
	}

	function reloadArticles() {
		var articlesList = localStorage.getItem( 'articles-list' );
		if( articlesList ) {
			articlesList = JSON.parse( articlesList );
			for( i = 0; i < articlesList.length; i++ ) {
				var entry = articlesList[i];
				$.tmpl( $( '#item-template' ), entry ).appendTo( $('#article-list-grid-view') );
				$( '#article-list-grid-view' ).trigger( 'list-updated' );
				var totalCount = parseInt( $('[data-total-articles]').attr( 'data-total-articles' ) );
				if( totalCount == $('#article-teasers [data-article-id]').length)
					$('[data-total-articles]').parent().hide();

				if( !backend_object.sharing_enabled ) {
					$( '.social-link' ).removeAttr( 'target' ).attr( 'href', '#0' );
				}
			}
		}
	}

	$( 'body' ).on( 'click', '[data-element="article-link"]', function (e) {
		var articleElem = $( this ).closest( 'article' );
		saveArticleId( articleElem.attr( 'data-article-id' ) );
		saveArticleUrl( $( this ).attr( 'href' ) );
	});

	$( '#view-more-button' ).on( 'list-updated', function ( e, data ) {
		saveArticlesList( data );
	});

	var flag = false;
	if( isHomePage() && getArticleUrl() == getLastPageUrl() && $('#page_is_dirty').val() == 1 ) {
		if(window.location.hash.indexOf('article') !== -1)
			window.location.hash = "";
		$(window).load( function() {
			reloadArticles();
			setTimeout( function() {
				var articleId = getArticleId();
				if( articleId )
					window.location.hash = 'article-' + getArticleId();
				removeArticleId();
			}, 1000);
		});
		flag = true;
	}

	if( !flag && isHomePage() && getLastPage() == 'home' ) {
		clearLocalStorage();
	}

	saveLastPageUrl();
	saveLastPage();

	(function mark_page_dirty() {
		$('#page_is_dirty').val('1');
	})();



})( jQuery );


(function ($) {

	var currentPage = $( '[data-page]' );
	if( localStorage &&  currentPage.size() > 0 ) {

		if( document.referrer == "" || document.referrer.indexOf(location.protocol + "//" + location.host) 	!== 0 ) {
			localStorage.setItem( 'lastView', 'grid' );
		}

		currentPage = currentPage.attr( 'data-page' );

		if( localStorage.getItem( 'lastPage' ) == 'home' && currentPage != 'home' ) {
			localStorage.setItem( 'lastView', 'grid' );
		}

		localStorage.setItem( 'lastPage', currentPage );
	}

	if( $('[data-page]').size() ) {
		$('.site-container').addClass($("[data-page]").attr('data-page'));
	}

	function insertParam( key, value, returnString, onlyOne ) {
	    key = encodeURI(key); value = encodeURI(value);

	    var kvp = [ '' ];
	    if( !onlyOne )
	    	kvp = document.location.search.substr(1).split('&');

	    var i=kvp.length; var x; while(i--)
	    {
	        x = kvp[i].split('=');

	        if (x[0]==key)
	        {
	            x[1] = value;
	            kvp[i] = x.join('=');
	            break;
	        }
	    }

	    if(i<0) {kvp[kvp.length] = [key,value].join('=');}

	    //this will reload the page, it's likely better to store this until finished
	    if( returnString )
	    	return kvp.join( '&' );
	    document.location.search = kvp.join('&');
	}

	function removeParam(key) {
		var sourceURL = window.location.href;
	    var rtn = sourceURL.split("?")[0],
	        param,
	        params_arr = [],
	        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
	    if (queryString !== "") {
	        params_arr = queryString.split("&");
	        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
	            param = params_arr[i].split("=")[0];
	            if (param === key) {
	                params_arr.splice(i, 1);
	            }
	        }
	        if( params_arr.length > 0)
	        	rtn = rtn + "?" + params_arr.join("&");
	    }
	    window.location.href = rtn;
	}

	function getParameterByName(name) {
	    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	        results = regex.exec(location.search);
	    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}

	function showGrid() {
		$('a.list').removeClass('active');
		$('#article-teasers ul').removeClass('list fadeInDown').addClass('grid');
                // $('#article-list-grid-view').isotope({layoutMode:'fitRows',});
	}

	function showList() {
		$('a.grid').removeClass('active');
		$('#article-teasers ul').removeClass('grid fadeInUp').addClass('list');
                // $('#article-list-grid-view').isotope({layoutMode:'vertical',});
	}

	var homeArticleNavBar = $('a[data-nav]');
	if( homeArticleNavBar.size() ) {

		if( localStorage ) {
			var selectedView = localStorage.getItem( 'lastView' ) ? localStorage.getItem( 'lastView' ) : 'grid';
			if(selectedView == "list") {
				$('a[data-nav="list"]').addClass('active');
				showList();
			} else {
				$('a[data-nav="grid"]').addClass('active');
				showGrid();
			}
		}
	}

	$('#article-teasers').on('touchstart', '.article-activity', function(e) {
		$( this ).children( '.activitybar-horizontal' ).addClass( 'active' );

	}).on('mouseenter', '.article-activity', function(e) {
		$( this ).children( '.activitybar-horizontal' ).addClass( 'active' );

	});

	$('#article-teasers').on('touchend', '.article-activity', function(e) {
		$( this ).children( '.activitybar-horizontal' ).removeClass( 'active' );

	}).on('mouseleave', '.article-activity', function(e) {
		$( this ).children( '.activitybar-horizontal' ).removeClass( 'active' );

	});

	$('a[data-nav]').on('click',function(e) {
		var selectedView = '';
		if ($(this).hasClass('grid')) {
			$(this).addClass('active');
			showGrid();
			selectedView = 'grid';
		}
		else if($(this).hasClass('list')) {
			$(this).addClass('active');
			showList();
			selectedView = 'list';
		}

		if( localStorage ) {
			localStorage.setItem( 'lastView', selectedView );
		}
	});

	// Sidebar filters
	var dataMenu = $('.sidebar-filter [data-menu-selected="true"]');
	if( dataMenu.size() ) {
		function showSelectedItems(elem) {
			var selectedType = elem.attr('data-menu-type');
			$('.article-teaser li[data-type!="' + selectedType + '"]').hide();

			$('[data-action-type="refresh"]').attr( 'data-selected-type', selectedType );

			var itemsToBeViewed = $('.article-teaser li[data-type="' + selectedType + '"]');
 			itemsToBeViewed.show();
		}

		var selectedElem = $('[data-menu-selected="true"]');

		showSelectedItems(selectedElem);

		$('a[data-menu-type]').click( function (event) {
			showSelectedItems($(event.target || event.srcElement));
		});

		$('[data-action-type="refresh"]').click( function (event) {
			var icon = $( '[data-action-type="refresh"] i' );
			var selectedType = $( this ).attr( 'data-selected-type' );
			var offset = $( '[data-menu-type="' + selectedType + '"]' ).attr( 'data-posts-offset' );

			if(!offset) {
				offset = 5;
			}

			var data = {
				action: 'mp_sidebar_load_next',
				mp_nonce: backend_object.mp_nonce_load_more,
				type: selectedType,
				offset: offset,
				post_id: $( 'article[data-article-id]' ).attr( 'data-article-id' )
			};

			$.ajax( {
				url: backend_object.ajax_url,
				data: data
			}).done(function( response ) {
					console.log(response);
					if( response.success ) {
						$('.article-sidebar .article-teaser li[data-type="' + selectedType + '"]').remove();
						$( '[data-menu-type="' + selectedType + '"]' ).attr( 'data-posts-offset', response.data.offset );
						response.data.result.forEach( function ( entry ) {
							entry = $.parseJSON( entry );
							entry.type = selectedType;
							$.tmpl( $( '#sidebar-item-template' ), entry ).appendTo( $('.article-sidebar .article-teaser') );
						} );
					}
				});
		});

	}


	// Homepage filter selection
	$('[data-query-value]').on( 'click', function ( e ) {
		var selectedValue = $( this ).attr( 'data-query-value' );
		var selectedKey = $( this ).closest( '[data-query-key]' ).attr( 'data-query-key' );
		if( selectedKey == 'category'  ) {
			return;
		} else {
			if( selectedKey == 'category' ) {
				var queryString = insertParam( selectedKey, selectedValue, true, true );
				window.location.href = '/?' + queryString;
			} else {
				insertParam( selectedKey, selectedValue );
			}
		}
	});
	function getSelectedCategory() {
		var path = window.location.pathname;
		var matches = path.match( /^\/topic\/([^\/]*)\/?/ );
		if( matches && matches.length > 0 ) {
			return matches[1];
		} else {
			if( $( '[data-article-post-tag]' ).size() ) {
				return $( '[data-article-post-tag]' ).attr( 'data-article-post-tag' );
			}
		}
		return "";
	}

	function getSelectedSortFilter() {
		var path = window.location.pathname;
		var filter = "recent";
		var paths = path.split( '/' ).filter(function(e) {
			return e !== "";
		});

		if(paths && paths.length > 0)
			filter = paths[paths.length - 1];
		return filter;

	}

	$(document).ready(function() {
		//var selectedCategory = getParameterByName( 'category' );
		var selectedCategory = getSelectedCategory( 'category' );
		var selectedSortFilter = getSelectedSortFilter();



		$( '[data-query-key="category"] [data-query-value]' ).removeClass( 'active' );

		if( $( '[data-page]' ).attr('data-page') == 'home' || ( $( '[data-page*="post-tag-landing"]' ).size() && $( '[data-query-key="category"] [data-query-value="' + selectedCategory + '"]' ).size() > 0 ) ) {
			if( selectedCategory == "" || $( '[data-query-key="category"] [data-query-value="' + selectedCategory + '"]' ).size() == 0 ) {
				$( '[data-query-key="category"] [data-query-default]' ).addClass('active');
			} else {
				$( '[data-query-key="category"] [data-query-value="' + selectedCategory + '"]' ).addClass( 'active' );
			}
		}

		$('[data-menu="category-selected"]').text( $( '[data-query-key="category"] a.active:first' ).text() );

		selectedSortFilter = selectedSortFilter ? selectedSortFilter : 'recent';

		if( $( '[data-query-key="sort"] [data-query-value="' + selectedSortFilter + '"]' ).size() == 0 ) {
			selectedSortFilter = "recent";
		}

		$( '[data-query-key="sort"] [data-query-value="' + selectedSortFilter + '"]' ).closest( 'li' ).hide();

		// Removed the selected category from the dropdown in mobile on homepage.
		$('.category-dropdown-menu li a.active, [data-dropdown-menu="tags-dropdown"] li a.active').hide();


		var iOS = /(iPhone|iPod)/g.test( navigator.userAgent );
		if( iOS ) {
			$( '[data-ios-only="true"]' ).show();
		} else {
			$( '[data-ios-only="true"]' ).hide();
		}

		if( $('[data-article-sub-post-tag]').size() ) {
			var selectedSubTag = $('[data-article-sub-post-tag]').attr('data-article-sub-post-tag');

			$( '[data-query-key="category"] [data-query-value="' + selectedSubTag +'"]' ).addClass( 'active' );
		}
	});

	$('[data-query-value]').css('cursor', 'pointer');

})(jQuery);


(function ($) {

	// Make images with caption, responsive. Removes the inline width
	$('.article-detail-content [id^=attachment_]').removeAttr('style');

	$('.onscroll-nav').hide();
	if( $('article .article-horizontal-share:first').size() ) {
		$(document).scroll(function () {
			var y = $(this).scrollTop() + $( '.navbar:first' ).height();

			if( viewport().width < 768 ) {
				var activityBar = $( '.blog-post-meta .activitybar-horizontal' );
				if( y > (activityBar.offset().top + activityBar.height()) ) {
					$('.onscroll-nav').fadeIn();
					$('.desktop-nav.main-nav').hide();
					$('.mobile-nav.main-nav').hide();
					$('.login-dropdown').parent().removeClass('open');
				} else {
					$('.onscroll-nav').fadeOut();
					$('.desktop-nav.main-nav').show();
					$('.mobile-nav.main-nav').show();
					$('.nav-stick.activitybar-horizontal ul').fadeIn();
					$('body').removeClass('nav-expanded');
				}

			} else {
				if (y > $('.mp-article-content').offset().top) {
					$('.onscroll-nav').fadeIn();
					$('.desktop-nav.main-nav').hide();
					$('.mobile-nav.main-nav').hide();
					$('.login-dropdown').parent().removeClass('open');
				} else {
					$('.onscroll-nav').fadeOut();
					$('.desktop-nav.main-nav').show();
					$('.mobile-nav.main-nav').show();
					$('.nav-stick.activitybar-horizontal ul').fadeIn();
					$('body').removeClass('nav-expanded');
				}
			}

		});
	}

	if( $('.site-container.home').size() ) {
		$(document).scroll(function () {
			var y = $(this).scrollTop() + $( '.navbar:first' ).height();

			if (y > $('.site-inner').offset().top+16) {
				$('.onscroll-nav').fadeIn();
				$('.desktop-nav.main-nav').hide();
				$('.mobile-nav.main-nav').hide();
			} else {
				$('.onscroll-nav').hide();
				$('.desktop-nav.main-nav').show();
				$('.mobile-nav.main-nav').show();
				$('body').removeClass('nav-expanded');
				$('ul.category').fadeIn();
			}

		});
	}

	if( $('.site-container.article-detail').size() ) {
		$(document).scroll(function () {
			if ( isElementInViewport( $( '#comments' ) ) ) {
				$('.facebook-like').addClass('slide');
			} else {
				$('.facebook-like').removeClass('slide');
			}
		});
	}

	if( $('body.tax-seasonal').size() && $('[data-article-post-tag] .container-main.grid').size() ) {
		$(document).scroll(function () {
			var y = $(this).scrollTop() + $( '.navbar:first' ).height();

			if (y > $('[data-article-post-tag] .container-main.grid').offset().top) {
				$('.onscroll-nav').fadeIn();
				$('.desktop-nav.main-nav').hide();
				$('.mobile-nav.main-nav').hide();
			} else {
				$('.onscroll-nav').hide();
				$('.desktop-nav.main-nav').show();
				$('.mobile-nav.main-nav').show();
				$('body').removeClass('nav-expanded');
				$('ul.category').fadeIn();
			}
		});
	}

})(jQuery);


(function ($) {

	var readingListElems = $('[data-action="reading-list"]');

	if( !readingListElems )
		return;

	readingListElems.each( function (index, elem) {
		$( elem ).filter( '[data-complete="true"]' ).each( function() {
			$( this ).addClass( 'active' );
		});
	});

	$( 'body' ).on( 'added-to-reading-list', '[data-complete="true"]' , function (event) {
		$( this ).addClass( 'active animated bounceIn' );
	});

})(jQuery);

(function ($) {
	function convertDigitIn( enDigit ) {
		var newValue = "";
		for ( var i = 0; i < enDigit.length; i++ )
		{
			var ch = enDigit.charCodeAt(i);
			if (ch >= 48 && ch <= 57) {
            // european digit range
	            var newChar = ch + 1584;
	            newValue = newValue + String.fromCharCode(newChar);
	        }
	        else
	        	newValue = newValue + String.fromCharCode(ch);
	    }
    	return newValue;
	}

	function convertNumbers( elem ) {
		var text = elem.text();
		var englishNumbers = text.match(/(\d+)/g);
		if(englishNumbers) {
		englishNumbers.forEach( function (entry) {
			text = text.replace( entry, convertDigitIn(entry) );
			elem.text(text);
		});
		}
	}

	function applyColors() {
		var categories = [];
		$('[data-category]').each(function () {
			var category = $(this).attr('data-category');
			if($.inArray(category, categories)) {
				categories.push(category);
			}
			categories.sort();
			$(this).addClass( 'category-' + (categories.indexOf( category ) + 1) );
		});
	}


	// $('[data-convert-numbers]').each( function (index, elem) {
	// 	convertNumbers( $(this) );
	// 	$(this).on( 'textChange', function (event) {
	// 		convertNumbers( $(this) );
	// 	});
	// });

	$( '#article-list-grid-view' ).on( 'list-updated', function ( e ) {
		// if( $( 'a.list' ).hasClass( 'active' ) ) {
		// 	$('#article-teasers ul li').removeClass('col-lg-4 col-md-4 col-sm-6 col-xs-12').addClass('col-md-12');
		// } else if( $( 'a.grid' ).hasClass( 'active' ) ) {
		// 	$('#article-teasers ul li').removeClass('col-md-12').addClass('col-lg-4 col-md-4 col-sm-6 col-xs-12');
		// }

		// $('[data-convert-numbers]').each( function ( index, elem ) {
		// 	convertNumbers( $(this) );
		// } );
		$( '[data-complete="true"]' ).each( function() {
			$( this ).addClass( 'active' );
		});

		applyColors();
	});


	$('body').on('removed-from-reading-list', '[data-action="reading-list"]', function() {
		var elem = $( this );
		var page = $( '[data-page]' ).attr( 'data-page' );
		var view = $( '[data-article-view]' ).attr( 'data-article-view' );

		if( view && page === 'profile' && view === 'bookmarks' ) {
			var article = elem.closest( 'article' ).parent();
			if( $( '#article-teasers article').length <= 1 ) {
				document.location.reload();
			} else {
				article.remove();
				if( $( '[data-article-count]' ).size() ) {
					var oldCount = parseInt( $('[data-article-count]').attr( 'data-article-count' ) );
					$( '[data-article-count]' ).attr( 'data-article-count', oldCount - 1 );

					var oldTotal = parseInt( $('[data-total-articles]').attr( 'data-total-articles' ) );
					$( '[data-total-articles]' ).attr( 'data-total-articles', oldTotal - 1 );

				}
			}
			$( 'body' ).trigger('reading-list-page-updated');
		}

	});

	function makeFooterAbsolute(ignoreArticleCount) {
		if( ignoreArticleCount || $( '#article-teasers article').length <= 1 ) {
			$('.site-footer').css('position', 'absolute').css('bottom', 0);
		}
	}

	$('body').on('reading-list-page-updated', function() {
		if( viewport().width < 768 && $( '#article-teasers article').length == 1 )
			return;
		makeFooterAbsolute();
	});

	if( ( $( '[data-page]' ).attr( 'data-page' ) !== 'home' && viewport().width > 768 && $( '.no-articles.well' ).is(":visible") ) ||
			( $( '[data-page]' ).attr( 'data-page' ) === 'profile' && $( '[data-article-view]' ).attr( 'data-article-view' ) === 'bookmarks' && $( '#article-teasers article').length <= 1 ) ) {

		if( !( viewport().width < 768 && $( '#article-teasers article').length <= 1 ) && $( '[data-page*="series"]' ).size() === 0 && $( '[data-page*="author"]' ).size() === 0 ) {
			makeFooterAbsolute();
		}

		if( $( '#article-teasers article').length === 0 )
			$( '.profile-menu-left' ).hide();
	}

	// Make footer absolute for tv series header
	if( $('.tv-series-header').size() && viewport().width > 768 && ( $('.no-articles.well').size() ) ) {
		makeFooterAbsolute(true);
	}

	var modalLink = $('[data-target="#loginModal"]');
	modalLink.on('click', function() {
		var elem = $(this);
		if( elem.attr('data-href') ) {
			window.location.hash = elem.attr('data-href');
		}
	});



	/** Sidebar Fix **/

	function scrollFix( elem ) {
		var scrolling = false;
		var navbarHeight = $( '.navbar:first' ).height();
		var start = getStart();
		var stop = getStop();

		function getStop() {
			var height = elem.height();
			if( elem.is( '[data-bottom-offset]' ) ) {
				height = height - elem.attr( 'data-bottom-offset' );
			}

			if( viewport().width >= 768 && viewport().width < 992 ) {
				return $( '.article-sidebar' ).offset().top - height;
			}

			return $( 'footer.footer-container' ).offset().top - height;
		}

		function getStart() {
			return $( '.article-detail-content' ).offset().top;
		}

		function checkPosition() {
			var screenWidth = viewport().width;
			if( elem.attr( 'data-min-width' ) > screenWidth ) {
				elem.removeClass( 'affix' );
				elem.removeClass( 'affix-out' );
				//elem.removeAttr( 'style' );
				return;
			}

			start = getStart();
			stop = getStop();
			var currentScrollPosition = $(window).scrollTop() + navbarHeight;

			if( currentScrollPosition > start && currentScrollPosition <= stop ) {
				if( elem.hasClass( 'affix-out' ) ) {
					elem.removeClass( 'affix-out' );
					elem.css( 'top', '' );
				}

				elem.addClass( 'affix' );
				elem.css( 'top', navbarHeight);
				scrolling = true;

				if( screenWidth > 1200 && elem.is( '[data-offset-left]' ) ) {
					elem.css( 'left', elem.attr( 'data-offset-left' ) );
				}


			}

			if( currentScrollPosition > stop ) {
				elem.removeClass( 'affix' );
				elem.addClass( 'affix-out' );
				//elem.removeAttr( 'style' );
				elem.css( 'top', stop - start);
			}

			if( currentScrollPosition <= start ) {
				elem.removeClass( 'affix' );
				elem.removeClass( 'affix-out' );
				//elem.removeAttr( 'style' );
			}

		}

		setInterval( checkPosition, 50, true);

	}

	$(document).ready(function() {
		$('.article-detail-content').fitVids();


		if( $('[data-page="article-detail"]').length ) {
			var verticalActivityBar = $( 'article .activitybar-vertical' );

			var sidebar = $( '.article-sidebar' );


			
			scrollFix( verticalActivityBar );
			scrollFix( sidebar );
		}


			function resizeBlogPostMeta() {
				if( viewport().width < 992 ) {
					$( '.blog-post-meta' ).width( '100%' );
				} else {
					if( $( '.b-sidebar' ).size() > 0 ) {
						$( '.blog-post-meta' ).width( $('.article-heading').width() - 380 );
					} else {
						$( '.blog-post-meta' ).width( $('.article-heading').width() - 300 );
					}
				}
			}
			resizeBlogPostMeta();

			$('.content').on( 'resize-meta', function() {
			resizeBlogPostMeta();
		});

		$(window).resize( function() {
			resizeBlogPostMeta();
		});

	});

	//popover
	(function($) {
	    var oldHide = $.fn.popover.Constructor.prototype.hide;

	    $.fn.popover.Constructor.prototype.hide = function() {
	        if (this.options.trigger === "hover" && this.tip().is(":hover")) {
	            var that = this;
	            // try again after what would have been the delay
	            setTimeout(function() {
	                return that.hide.call(that, arguments);
	            }, that.options.delay.hide);
	            return;
	        }
	        oldHide.call(this, arguments);
	    };
	})(jQuery);

	$('#like-top, #like-left').popover({
		html:true,
		trigger: 'hover',
		delay: { hide: 500 },
		container: '.blog-post-meta'
	});

 		//Navigation Menu Slider
        $('.nav-expander').on('click',function(e){
      		e.preventDefault();
      		$('body').toggleClass('nav-expanded');
      		$('ul.category').fadeOut();
      		$('.nav-stick.activitybar-horizontal ul').fadeOut();
      		$('.nav-inner .nav').fadeOut();

      	});
      	$('#nav-close, .offcanvas-overlay').on('click',function(e){
      		e.preventDefault();
      		$('body').removeClass('nav-expanded');
      		$('ul.category').fadeIn();
      		$('.nav-stick.activitybar-horizontal ul').fadeIn();
      		$('.nav-inner .nav').fadeIn();
      	});

      	// $('.site-header').on('click',function(e){

      	// 	if ($(this).hasClass('nav-expanded')) {
	      // 		$(this).toggleClass('nav-expanded');
	      // 		$('ul.category').fadeIn();
	      // 		alert("Youppy!");
      	// 	}

      	// });


      	// Initialize navgoco with default options
        $(".main-menu").navgoco({
            caret: '<span class="caret"></span>',
            accordion: false,
            openClass: 'open',
            save: true,
            cookie: {
                name: 'navgoco',
                expires: false,
                path: '/'
            },
            slide: {
                duration: 300,
                easing: 'swing'
            }
        });


    //Close dropdown menu on scroll

    $(window).scroll(function() {
	    var scroll = $(window).scrollTop();

	    if ((scroll >= 100) && (viewport > 768)) {
	        $(".dropdown-menu").parent().removeClass("open");
	    }
	});

	//Long title
	$('.grid .article-content h1').each( function () { if( $(this).text().length >= 80 ) $(this).addClass('long-title'); } );

	//Search events

	$( '[data-target="search-open"]' ).on( 'click', function() {
		var target = $( '[data-element="search-form"]' );
		target.addClass( 'visible' );

		setTimeout( function () {
			$( '.search-form input' ).focus();
		}, 1000);

	});

	$( '[data-target="search-close"]' ).on( 'click', function() {
		var target = $( '[data-element="search-form"]' );
		target.removeClass( 'visible' );
	});

	//Explore events

	$( '[data-target="explore-open"]' ).on( 'click', function() {
		var target = $( '[data-element="explore-form"]' );
		target.addClass( 'visible' );
	});

	$( '[data-target="explore-close"]' ).on( 'click', function() {
		var target = $( '[data-element="explore-form"]' );
		target.removeClass( 'visible' );
	});

	$( '[data-page-number]' ).each( function() {
		var self = $( this );
		var href = self.attr( 'href' );

		href = href.replace( /\?page(.*)/, '');
		href = href + '?page=' + self.attr( 'data-page-number' );

		self.attr( 'href', href );
	});

	$( '#remove-popup' ).on( 'click', function() {
		$('.facebook-like').remove();
	});


	if( !backend_object.sharing_enabled ) {
		$( '.footer-social a' ).attr( 'href', '#0' ).removeAttr( 'target' );
	}

	$( '.footer-newsletter form' ).bootstrapValidator({
		fields: {
			mc_mv_EMAIL: {
				validators: {
                    notEmpty: {
                        message: backend_object.email_address_required
                    },
                    emailAddress: {
                        message: backend_object.email_address_invalid
                    }
            	}
			}
		}
	}).on('success.form.bv', function (e) {

			var form = $( e.target || e.srcElement );

			if( !form.data('bootstrapValidator').isValid() ) {
				return false;
			}

			$.ajax({
				url: form.attr( 'action' ),
				method: 'post',
				data: form.serialize(),
				beforeSend: function() {
					$( '#messagebox-modal [data-element="message-box"]' ).html( '' );
					$( '#messagebox-modal' ).modal( 'show' );
					$( '[data-element="loading-icon"]' ).show();
					$( '.footer-newsletter form button' ).attr('disabled', 'true');
				},
				complete: function() {
					$( '.footer-newsletter form button' ).removeAttr('disabled', 'false');
					$( 'input[name="mc_mv_EMAIL"]').val( '' );
					form.data('bootstrapValidator').updateStatus( 'mc_mv_EMAIL', 'INVALID', 'notEmpty' );
				},
				success: function( response ) {
					$( '[data-element="loading-icon"]' ).hide();
					if( response.indexOf( 'success' ) != -1 ) {
						$( '#messagebox-modal [data-element="message-box"]' ).html( backend_object.newsletter_success_message );
					} else {
						$( '#messagebox-modal [data-element="message-box"]' ).html( backend_object.newsletter_error_message );
					}
					form.data('bootstrapValidator').resetForm();
				}
			});

			e.preventDefault();
		});


	// Explore
	var exploreDropDowns = $( '[data-element="explore"]' );
	if( exploreDropDowns.size() ) {

		exploreDropDowns.find( 'a[data-parameter-value]' ).on( 'click', function ( e ) {
			var selected = $( this );

			selected.closest( '[data-drop-down]' ).find( '[data-toggle] div' ).text( selected.text() );
			selected.closest( 'ul' ).find( 'li.hidden' ).removeClass( 'hidden selected' );
			selected.closest( 'li' ).addClass( 'hidden selected' );
		});
		exploreDropDowns.find( 'ul[data-parameter-key]' ).find( 'li:first' ).addClass( 'hidden selected' );

		$( '[data-navigate="explore"]' ).on( 'click', function ( e ) {
			var self = $( this );
			var href = self.attr( 'href' ) + '?mood=' + $( '[data-parameter-key="mood"] li.selected a' ).attr( 'data-parameter-value' ) + '&reading-time=' + $( '[data-parameter-key="reading-time"] li.selected a' ).attr( 'data-parameter-value' );
			console.log( href );
			self.attr( 'href',  href);
		} );

	}

	// Copy to Clipboard
	ZeroClipboard.config( { moviePath: '/wp-content/themes/menapost/js/vendor/ZeroClipboard.swf' } );
	var client = new ZeroClipboard( $( ".copy-button" ) );
	var sizeText= $('#label-copy-short-url').width();
	$('#label-copy-short-url').css('width',sizeText+25);

	client.on( 'complete', function() {
		var orgText = $('#label-copy-short-url').text();

		$('#label-copy-short-url').text('تم النسخ');


		setTimeout(function() {
			$('#label-copy-short-url').text(orgText);
		}, 1000);
	});


	// Lazy loading images.
	$( 'img.lazy-load' ).lazyload();
	$( '#article-list-grid-view' ).on( 'list-updated', function ( e, data ) {
		$( '[data-article-id]:last img.lazy-load' ).lazyload();
	});

	var selectedTag = $('span.seasonal-selected.desktop');
	if(selectedTag.size() ) {

		function alignTags() {
			var offset = ( $('body').width() + $('body').offset().left ) - ( selectedTag.offset().left + selectedTag.outerWidth());
			$('li.sub-filter').css('right', offset);
		}

		alignTags();

		$(window).on( 'resize load', function() {
			alignTags();
		});
	}

	var seasonalTagSelected = $( '.seasonal-selected' );
	if( seasonalTagSelected.size() ) {
		$( '.site-container' ).addClass( 'seasonal-tag' );

		// If mobile then hide all the tags.
		$('.seasonal-selected.mobile').siblings('.parent-tag-mobile').hide();
	}

	$('.search-form form').on('submit', function() {

		var val = $( this ).find( 'input#s' ).val();
		if( val ) {
			window.location.href = backend_object.home_url + "/search/" + encodeURIComponent( val );
			return false;
		}

		return false;
	});

})(jQuery);

// if( viewport().width < 768 ){
// 	jQuery("#article-list-grid-view li")
//     .velocity("transition.slideUpIn", { stagger: 450 })
//     .delay(750);
// }

    // .velocity({ opacity: 0 }, 750)
    // .velocity("reverse")


(function($) {
    jQuery('#emblem_link').click(function () {
        if( jQuery(".featured-cover").attr("data-page") === "home" ){
            jQuery('body,html').velocity("scroll", { duration: 1200, easing: "easein" });
            return false;
        }
    });

    // Go to Top Function for Homepage
jQuery(document).ready(function(){

	//Check to see if the window is top if not then display button
	jQuery(window).scroll(function(){
		if (jQuery(this).scrollTop() > 600) {
			jQuery('.scrollToTop').fadeIn();
		} else {
			jQuery('.scrollToTop').fadeOut();
		}
	});

	//Click event to scroll to top
	jQuery('.scrollToTop').click(function(){
		jQuery('html, body').animate({scrollTop : 0},800);
		return false;
	});

});

})( jQuery );


// Add share buttons to images and vidoes.
(function ($) {

	// Checks if we are on the article detail page
	if( !$( '[data-page="article-detail"]' ).size() ) {
		return;
	}

	// Add the class 'micro-content' to p tag which has image
	$('.article-detail-content p:has(img, iframe), .article-detail-content div[id^="attachment"]').addClass( 'micro-content' ).append( $.tmpl( $( '#micro-content-template' ), '' ) );

	$( '.share-micro-content a' ).on( 'click', function() {
		var new_window = window.open(
			$( this ).attr( 'href' ),
			'Social Sharing',
			'height=500,width=600'
			);
		new_window.focus();
		return false;
	} );

})( jQuery );


(function($) {

    $( '.suggestion-btn' ).on('click', function(e){

        $('#suggestion_div').show();
        $("#corrector-name").val("");
        $("#corrector-email").val("");
        $("#correction-text").val("");
        $("#newsletter-checkbox").prop( "checked", "checked" );
        text_max = 1000;
        $('.char-count').html(text_max);
    });

    var text_max = 1000;
    $('.char-count').html(text_max);
    $('#correction-text').keyup(function() {
        var text_length = $('#correction-text').val().length;
        var text_remaining = text_max - text_length;

        $('.char-count').html(text_remaining);
    });

//    jQuery('.suggest-edit-modal h2').html( jQuery('.article-heading h1').html() );
//    jQuery('.suggest-edit-modal h3').html( jQuery('.cite h5 a').html() );

    jQuery('#hidden_post_id').val( jQuery('article').attr('data-article-id') );
    jQuery('#hidden_author_id').val( jQuery('article').attr('data-author-id') );

    jQuery('.suggest-edit-modal form').bootstrapValidator({
        message: 'نأسف لكن يجب ملئ الحقل',
        fields: {
            "corrector-name": {
                message: '',
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    }
                }
            },
            "corrector-email": {
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    },
                    emailAddress: {
                        message: 'نأسف لكن يبدو أن العنوان البريدي الذي أدخلته غير صحيح'
                    }
                }
            },
            "correction-text": {
                message: '',
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    }
                }
            }
        }
//    }).on( 'submit', function (e) {
    }).on('success.form.bv', function (e) {

        e.preventDefault();

        var data = {
            action: 'ajax_add_suggest_correction',

            hidden_post_id: $('article').attr('data-article-id'),
            hidden_author_id: $('article').attr('data-author-id'),
            corrector_name: $("#corrector-name").val(),
            corrector_email: $("#corrector-email").val(),
            correction_text: $("#correction-text").val()
        };

        $.ajax({
            url: backend_object.ajax_url,
            method: 'post',
            data: data,
            success: function( response ) {

                $( '#suggestion_div').slideUp();
                $( '#email_notification' ).html("تم إخطار اقتراحكم، شكرا لك!").slideDown().delay(1500).slideUp().queue(function(next){
                $( '#suggest-modal' ).modal( 'hide' );
                    next();

                    if( $("#newsletter-checkbox").is(':checked') === true  ){

                        var url = $('.footer-newsletter form').attr("action");
                        var mc_submit_type = "js";
                        var mcsf_action = "mc_submit_signup_form";
                        var mc_signup_submit = "Subscribe";
                        var nonce = $('#_mc_submit_signup_form_nonce').val();

                        $.post(backend_object.ajax_url,
                                {
                                    mc_submit_type:mc_submit_type,
                                    mcsf_action:mcsf_action,
                                    mc_signup_submit:mc_signup_submit,
                                    _mc_submit_signup_form_nonce:nonce,
                                    mc_mv_EMAIL:$("#corrector-email").val()
                                });


//                        $( '[name="mc_mv_EMAIL"]' ).val( $("#corrector-email").val() );
//                        $( '.footer-newsletter form' ).submit();
//
//                        $( '#newsletter-submit-button' ).removeAttr("disabled");
                    }
                });
            },
            complete: function() {
            	$( '.suggest-edit-modal form' ).data( 'bootstrapValidator' ).resetForm();
            }
        });

    });

	$('#suggest-modal').on('hide.bs.modal', function() {
		$( '.suggest-edit-modal form' ).data( 'bootstrapValidator' ).resetForm();
	});
})( jQuery );


(function($) {

    $( '#contact-form-edit' ).on('click', function(e){

//        alert("EMAIL ICON Clicked");

        $('#contact_form_div').show();
        $("#contact-name").val("");
        $("#contact-email").val("");
        $("#contact-subject").val("");
        $("#contact-form-text").val("");
        text_max = 1000;
        $('.char-count').html(text_max);
    });

    var text_max = 1000;
    $('.char-count').html(text_max);
    $('#contact-form-text').keyup(function() {
        var text_length = $('#contact-form-text').val().length;
        var text_remaining = text_max - text_length;

        $('.char-count').html(text_remaining);
    });

    function sendNewsletter() {
    	if( $("#contact-form-newsletter-checkbox").is(':checked') === true  ){

            var url = $('#contact_form_div form').attr("action");
            var mc_submit_type = "js";
            var mcsf_action = "mc_submit_signup_form";
            var mc_signup_submit = "Subscribe";
            var nonce = $('#_mc_submit_signup_form_nonce').val();

            $.post(backend_object.ajax_url,
                    {
                        mc_submit_type : mc_submit_type,
                        mcsf_action : mcsf_action,
                        mc_signup_submit : mc_signup_submit,
                        _mc_submit_signup_form_nonce : nonce,
                        mc_mv_EMAIL : $("#contact-email").val()
                    });
        }
    }

    jQuery('.contact-form-modal form').bootstrapValidator({
        message: ' الرجاء التأكد من ملئ جميع الحقول',
        fields: {
            "contact-name": {
                message: '',
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    }
                }
            },
            "contact-email": {
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    },
                    emailAddress: {
                    	message: 'نأسف لكن يبدو أن العنوان البريدي الذي أدخلته غير صحيح'
                    }
                }
            },
            "contact-subject": {
                message: '',
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    }
                }
            },
            "contact-form-text": {
                message: '',
                validators: {
                    notEmpty: {
                        message: 'نأسف لكن يجب ملئ الحقل'
                    }
                }
            }
        }
    }).on('success.form.bv', function (e) {

        e.preventDefault();

        var data = {
            action: 'ajax_contact_form',

            contact_name: $("#contact-name").val(),
            contact_email: $("#contact-email").val(),
            contact_subject: $("#contact-subject").val(),
            contact_form_text: $("#contact-form-text").val()
        };

        $.ajax({
            url: backend_object.ajax_url,
            method: 'post',
            data: data,
            success: function( response ) {

            	sendNewsletter();

                $( '#contact_form_div').slideUp();
                $( '#contact_form_notification' ).html("شكرا. سنكون على اتصال.").slideDown().delay(1500).slideUp().queue(function(next){
                $( '#contact-form-modal' ).modal( 'hide' );
                    next();
                });
            },
            complete: function() {
            	$( '.contact-form-modal form' ).data( 'bootstrapValidator' ).resetForm();
            }
        });

    });

	$('#contact-form-modal').on('hide.bs.modal', function() {
		$( '.contact-form-modal form' ).data( 'bootstrapValidator' ).resetForm();
	});
})( jQuery );

// Scrum Item 248 - Highlight Search Result Query Text
(function( $ ) {
	// Select all the bold <b> tags inside search content detail text and add class orange
	$( '.search-content-section article .detail-text b:not(:contains("..."))' ).addClass( 'orange' );

})( jQuery );

// Scrum Item 311 - Off focus modal
(function($) {

	if( $('.article-detail').size() !== 0 ) {
		var topExit = 0;
		var lastMousePosition = 0;
		var currentMousePosition = 0;

		function trackMouseMove(e) {
			var scrollTop = $(window).scrollTop();
			topexit = scrollTop + 5;

			currentMousePosition = e.clientY;

			if(e.pageY <= topexit && currentMousePosition < lastMousePosition && $('body.mobile-nav-closed').length) {

				setTimeout(function() {
					$('#off-focus-modal').modal('show');
					$(document).off('mousemove', trackMouseMove);
				}, 100);

			}

			lastMousePosition = currentMousePosition;
		}

		$(document).on('mousemove', trackMouseMove);
	}

})(jQuery);

// Scrum Item 104 - As a User, I want an inline video to pause if
// I can only view less than 50% of the video. The video should resume
// if I scroll back up to more than 50% of the video.
(function($) {

	// If there are no youtube videos return.
	if( !$('iframe[src*="youtube"]').size() ) {
		return;
	}

	// Load YouTube Frame API
	(function() { // Closure, to not leak to the scope
	  var s = document.createElement("script");
	  s.src = (location.protocol == 'https:' ? 'https' : 'http') + "://www.youtube.com/player_api";
	  var before = document.getElementsByTagName("script")[0];
	  before.parentNode.insertBefore(s, before);
	})();

	// Returns true if the element is 50% visible on screen.
	$.fn.isHalfOnScreen = function(){
	    var viewport = {};

	    viewport.top = $(window).scrollTop() + $('.onscroll-nav').height(); // Adds the off-canvas nav bar height.
	    viewport.bottom = viewport.top + $(window).height();

	    var bounds = {};

	    bounds.top = this.offset().top;
	    bounds.bottom = bounds.top + this.outerHeight();

	    if(viewport.top > (bounds.top + (this.outerHeight() / 2))) {
	    	return false;
	    } else if(viewport.bottom < (bounds.bottom - (this.outerHeight() / 2))) {
	    	return false;
	    } else {
	    	return true;
	    }
	};


	// Returns the youtube video frame ID
	function getFrameID(id){
	    var elem = document.getElementById(id);
	    if (elem) {
	        if(/^iframe$/i.test(elem.tagName)) return id; //Frame, OK
	        // else: Look for frame
	        var elems = elem.getElementsByTagName("iframe");
	        if (!elems.length) return null; //No iframe found, FAILURE
	        for (var i=0; i<elems.length; i++) {
	           if (/^https?:\/\/(?:www\.)?youtube(?:-nocookie)?\.com(\/|$)/i.test(elems[i].src)) break;
	        }
	        elem = elems[i]; //The only, or the best iFrame
	        if (elem.id) return elem.id; //Existing ID, return it
	        // else: Create a new ID
	        do { //Keep postfixing `-frame` until the ID is unique
	            id += "-frame";
	        } while (document.getElementById(id));
	        elem.id = id;
	        return id;
	    }
	    // If no element, return null.
	    return null;
	}

	// Define YT_ready function.
	var YT_ready = (function() {
	    var onReady_funcs = [], api_isReady = false;
	    /* @param func function     Function to execute on ready
	     * @param func Boolean      If true, all qeued functions are executed
	     * @param b_before Boolean  If true, the func will added to the first
	                                 position in the queue*/
	    return function(func, b_before) {
	        if (func === true) {
	            api_isReady = true;
	            while (onReady_funcs.length) {
	                // Removes the first func from the array, and execute func
	                onReady_funcs.shift()();
	            }
	        } else if (typeof func == "function") {
	            if (api_isReady) func();
	            else onReady_funcs[b_before?"unshift":"push"](func);
	        }
	    }
	})();
	// This function will be called when the API is fully loaded
	window.onYouTubePlayerAPIReady = function() {
		YT_ready(true)
	};

	var players = []; // Define an array YoutubeVideo objects, to enable later function calls, without
	            // having to create a new class instance again.

	// Add function to execute when the API is ready
	YT_ready(function(){
		// Iterate over youtube video elements
		$('iframe[id*="fitvid"][src*="youtube"]').each(function () {
			var frameID = getFrameID($(this).attr('id'));
		    if (frameID) { //If the frame exists

		        players[frameID] = new YoutubeVideo(
		        	frameID,
		        	new YT.Player(frameID, {
			            events: {
			                "onStateChange": onStateChanged // Registers for the onStateChange Event
			            }
		        	})
		        );
		    }

		});
	});

	// Updates the state of the player
	function onStateChanged(event) {
	    players[event.target.a.id].changeState(event.data);
	}

	// Encapsulates the details of the youtube video, including its frameID, state
	function YoutubeVideo(frameID, player) {
		this.frameID = frameID;
		this.player = player;
		this.autoPause = false; // Defines if the video is paused by script.
		this.$frame = $('#' + frameID);

		// Creates a proxy handler to inject the context.
		this.scrollHandler = $.proxy(this.trackingPosition, this);

		// Player states as defined on the following link
		// https://developers.google.com/youtube/js_api_reference#Playback_status
		this.states = {
			'-1': "unstarted",
			'0' : "ended",
			'1' : "playing",
			'2' : "paused",
			'3' : "buffering",
			'5' : "video cued",
		};
	}

	YoutubeVideo.prototype.changeState = function(state) {
		this.state = this.states[state];

		switch(this.state) {
			case "playing":
				if(!this.autoPause) {
					this.startTracking();
				}
				break;
			case "ended":
				this.autoPause = false;
				this.stopTracking();
				break;
			default:
				break;
		}
	}

	YoutubeVideo.prototype.startTracking = function() {
		$(window).on('scroll', this.scrollHandler);
	}
	YoutubeVideo.prototype.stopTracking = function() {
		$(window).off('scroll', this.scrollHandler);
	}
	YoutubeVideo.prototype.trackingPosition = function() {
		if(!this.$frame.isHalfOnScreen()) {
			this.autoPause = true;
			this.player.pauseVideo();
		} else {
			this.player.playVideo();
		}
	}


})(jQuery);