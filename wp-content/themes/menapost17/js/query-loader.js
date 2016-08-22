jQuery(function($) {
	var url = theme.router; // The path to the API endpoint, specified in an inline script inserted by wp_localize_script
	var extraContentArea = $(window).height(); // in pixels

	$.fn.queryScroll = function() {

		$(this).each(function() {

			var $context = $(this);
			var query = $context.attr('data-query');
			var template = $context.attr('data-template');
			var page = 1;
			var fetching = false;

			function fetchArticles(onComplete) {
				if(fetching) return;
				fetching = true;

				var request = url +
					'?action=wp-query' +
					'&query=' + encodeURIComponent(query + '&paged=' + encodeURIComponent(page)) +
					'&template=' + encodeURIComponent(template);

				page += 1;

				$.get(request)
				.done(function(response) {
					$context.append(response);
					if(onComplete) onComplete();
				})
				.fail(function(response) {
					if(response.status == 404) {
					} else {
						console.log('Host unexpectedly failed to deliver an article: ', response);
					}
				})
				.always(function() {
					fetching = false;
				});
			}

			// Determine how much verticle space needs to be filled with articles, measured in article heights.
			function hasRoomForArticles(errorMargin) {
				var windowBottom = $(window).scrollTop() + $(window).innerHeight();
				var elementBottom = $context.offset().top + $context.height();
				var spacePx = windowBottom + errorMargin - elementBottom;

				return spacePx > 0;
			}

			// Repeatedly fetch articles until fetch conditions are not met, but do not loop until the current request has finished.
			function fetchArticleWhileNecessary() {
				if(hasRoomForArticles(extraContentArea)) {
					fetchArticles(fetchArticleWhileNecessary);
				}
			}

			// Load articles on scroll, if necessary, unless we are already in the process of fetching articles.
			$(window).scroll(function() {
				if(!fetching) {
					fetchArticleWhileNecessary();
				}
			});

			// Load articles on resize, if necessary, unless we are already in the process of fetching articles.
			$(window).resize(function() {
				if(!fetching) {
					fetchArticleWhileNecessary();
				}
			});

			// Check if articles are needed every second.
			setInterval(function() {
				if(!fetching) {
					fetchArticleWhileNecessary();
				}
			}, 1000);

			// Load articles on page load.
			fetchArticleWhileNecessary();
		});
	};

	// Apply this plugin automatically to all article containers.
	$('[data-query-scroll]').queryScroll();
});

