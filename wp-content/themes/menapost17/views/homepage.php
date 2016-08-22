<div class="<?php echo ($deviceType); ?> tabContent content floatfix">
	<section
		id="most-shared"
		class="kasraMostShared articles"

		data-infiniscroll
		data-feeds="1,3"
		data-template="article-excerpt"
		data-initial-load-count="3"

		data-role="panel">
	</section><!--most-shared-->

	<div class="gutter gutterTablet"></div>

		<section
			id="most-viewed"
			class="kasraMostViewed articles active"

			data-infiniscroll
			data-feeds="2,4"
			data-template="article-excerpt"
			data-initial-load-count="6"

			data-role="panel">
		</section><!--most-viewed-->

		<div class="gutter gutterDesktop"></div>

		<section
			id="newest"
			class="kasraNewest articles"

			data-infiniscroll
			data-template="article-excerpt"
			data-initial-load-count="20"

			data-role="panel">
		</section><!--newest-->
</div> <!-- .tabContent -->
</div> <!-- #tabs -->
<style type="text/css">
	@media screen and (max-width: 42.5em) { /* 48em = 768px, the width of an iPad in portrait */
		.gutter { display: none;}
		.col-title { width: 33.3%; border-top: 0px; margin-bottom: -0px;}
		.tabContent {
			clear: right;
			padding: 20px 0;
		}
		@media (min-width: 61.25em) {
			.tabContent {
			padding-top: 0;
			background-color: transparent;
			border-top: 0; } }

		/*.tabContent section {
			display: none; }
		.tabContent section:before,
		.tabContent section:after { content:''; display: table;}
		.tabContent section:after { clear: both;}

		.tabContent section.content-current {
			display: block; }*/

		[data-role=panel]:not(.active){ display: none; }
	}

	@media (min-width: 61.25em) {
		h1.tab-current {
			background-color: white;
			border-top: none;
			border-bottom: 2px solid #e8ebeb;
			border-left: 2px solid #e8ebeb;
			border-right: 2px solid #e8ebeb;
	}
}
</style>
