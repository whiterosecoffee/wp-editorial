<?php /* Template Name: HTML5 Home w/ Tabs */?>
<?php global $deviceType;
$cat1=3; $cat2=3; $cat3=3;
if($deviceType == "mobile"){$cat1=2; $cat2=0; $cat3=0;}
else if($deviceType == "tablet"){$cat1=2; $cat2=2; $cat3=2;}
?>
<?php
?>
<style>
	@media (max-width: 61.25em) {
  .gutter {
    display: none; }

  /*Gutter Allowance = number of gutters * gutter width / number of columns. */ }
@media (min-width: 61.25em) {
  .kasraNewest {
    width: 23.89333%; }

  .kasraMostViewed {
    width: 23.89333%; }

  .kasraMostShared {
    width: 48.89333%; } }
/* Scss Document */
.gradient {
  filter: none; }

/* IE 9 support for mulistop svg gradients. */
/* Scss Document */
/* Fonts */
@font-face {
  font-family: 'Droid Arabic Kufi';
  font-style: normal;
  font-weight: normal;
  src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/droidarabickufi/v2/DroidKufi-Regular.eot?#iefix) format("embedded-opentype"), url(//themes.googleusercontent.com/static/fonts/earlyaccess/droidarabickufi/v2/DroidKufi-Regular.woff) format("woff"), url(//themes.googleusercontent.com/static/fonts/earlyaccess/droidarabickufi/v2/DroidKufi-Regular.ttf) format("truetype"); }
h1, h2, h3, h4, h5, h6 {
  font-family: 'Droid Arabic Kufi';
  color: #4d4d4d;
  font-weight: normal;
  direction: rtl; }

p, a, li, span {
  direction: rtl; }

/* Colours */
body {
  background-color: #f6f6f6;
  direction: ltr;
  text-align: right;
  overflow-x: hidden; }


.kasraNewest,
.kasraMostViewed,
.kasraMostShared,
.newestArticles .featuredImage,
.newestArticles .tileTitle {
  float: right; }

.col-title {
  margin-top: 25px;
  position: relative; }
  @media (min-width: 61.25em) {
    .col-title {
      border-top: 5px solid #ff9100; } }
  @media (max-width: 61.25em) {
    .col-title {
      width: 32%; } }
  @media (max-width: 30.5em) {
    .col-title {
      width: 32%; } }

.tabs.kasraMostShared, .tabs.kasraMostViewed {
  margin-left: 2%; }
  @media (min-width: 61.25em) {
    .tabs.kasraMostShared, .tabs.kasraMostViewed {
      margin-left: 0; } }

@media (min-width: 61.25em) {
  .tabNav {
    margin-bottom: 15px; } }

.desktop .tabTitle {
  /* added for when Desktop users have small viewports and are in tabs */
  cursor: pointer; }
  @media (min-width: 61.25em) {
    .desktop .tabTitle {
      cursor: default; } }



@media (min-width: 61.25em) {
  #tabs {
    padding-top: 0; } }


@media screen and (max-width: 48em) { /* 48em = 768px, the width of an iPad in portrait */
		.tabContent {
		  background-color: white;
		  clear: right;
		  padding: 20px 0;
		  border-top: 2px solid #e8ebeb; }
		  @media (min-width: 61.25em) {
		    .tabContent {
		      padding-top: 0;
		      background-color: transparent;
		      border-top: 0; } }

		.tabContent section {
		  display: none; }
		  @media (min-width: 61.25em) {
		    .tabContent section {
		      display: block; } }

		.tabContent section.content-current {
		  display: inline-block; }

		.col-title h1 {
		  padding: 7px 7px 7px 0;
		  border-left: 2px solid #e8ebeb;
		  border-right: 2px solid #e8ebeb;
		  background-color: white;
		  margin-bottom: 0;
		  color: #e8ebeb;
		  border-top: 2px solid #e8ebeb;
		  position: relative;
		  bottom: -2px;
		}
		  @media (min-width: 61.25em) {
			  	.col-title h1 {
			      color: #ff9100;
			      border-top: none;
			      border-bottom: 2px solid #e8ebeb;
			      position: static; }
			    }
		  @media (max-width: 30.5em) {
					.col-title h1 {
			      font-size: 1em; }
			    }

		h1.tab-current {
			padding: 7px 7px 7px 0;
		  color: #ff9100;
		  border-top: 0;
		  border-left: 0;
		  border-right: 0;
		  border-bottom: 3px solid #ff9100; }
		  @media (min-width: 61.25em) {
		    .tab-current {
		      border-top: none;
		      border-bottom: 2px solid #e8ebeb;
		      border-left: 2px solid #e8ebeb;
		      border-right: 2px solid #e8ebeb; }
		  }
}


</style>
<div id="tabs" class="floatfix">
    <nav class="tabNav floatfix">
        <div class="col-title tabs kasraMostShared">
            <h1 href="#most-shared" class="tabTitle">شاركوها</h1>
        </div>
        <div class="gutter gutterTablet"></div>
        <div class="col-title tabs kasraMostViewed">
            <div class="col-emblem"><i class="icon-kasra-emblem"></i>
            </div>
            <div class="triangle-bottom"></div>
                <h1 href="#most-viewed" class="tabTitle">ينكسر الان</h1>
        </div>
        <div class="gutter gutterDesktop"></div>
        <div class="col-title tabs kasraNewest">
            <h1 href="#newest" class="tabTitle">جديد</h1>
        </div>
    </nav>
    <div class="tabContent" class="floatfix">
        <section
			id="most-shared"
			class="kasraMostShared articles <?php echo ($deviceType); ?>"

			data-infiniscroll
			data-feeds="1,3"
			data-template="article-excerpt"
			data-initial-load-count="3"><h1>1</h1>
		</section><!--most-shared-->
        <div class="gutter gutterTablet"></div>
        <?php if($deviceType != "mobile"){ ?>
            <section id="most-viewed" class="kasraMostViewed articles"><h1>2</h1>

                <?php $args = array(
                    'numberposts'		=> $cat2,
                    'offset'            => 5,
                    'post_type'			=> 'article',
                    'order-by'			=> 'rand',
                    'post_status'		=> 'publish'
                    );$query = get_posts($args);
                $allPosts = get_posts( $args );
                foreach ( $allPosts as $post ) : setup_postdata( $post ); ?>
                    <article class="articleExcerpt home-grid-box mostViewed floatfix">
                        <?php get_template_part('views/article-excerpt'); ?>
                    </article>
                <?php endforeach;?>
                <?php wp_reset_postdata();?>
            </section><!--most-viewed-->
           	<div class="gutter gutterDesktop"></div>
            <section id="newest" class="kasraNewest articles"><h1>3</h1>

                <?php $args = array(
                    'numberposts'		=> $cat3,
                    'offset'            => 10,
                    'post_type'			=> 'article',
                    'order-by'			=> 'rand',
                    'post_status'		=> 'publish'
                    );$query = get_posts($args);
                $allPosts = get_posts( $args );
                foreach ( $allPosts as $post ) : setup_postdata( $post ); ?>
                    <article class="articleExcerpt home-grid-box newestArticles floatfix">
                        <?php get_template_part('views/newest-excerpt');?>
                    </article>
                <?php endforeach;?>
                <?php wp_reset_postdata();?>
            </section><!--newest-->
    </div> <!-- tabContent -->
</div> <!-- tabs -->

<?php } /* END if != "mobile" */?>
<script type="text/javascript">
    ;( function( window ) {


    'use strict';
    function extend( a, b ) {
        for( var key in b ) {
            if( b.hasOwnProperty( key ) ) {
                a[key] = b[key];
            }
        }
        return a;
    }

    function CBPFWTabs( el, options ) {
        this.el = el;
        this.options = extend( {}, this.options );
        extend( this.options, options );
        this._init();
    }

    CBPFWTabs.prototype.options = {
        start : 0
    };

    CBPFWTabs.prototype._init = function() {
        // tabs elemes
        this.tabs = [].slice.call( this.el.querySelectorAll( '.tabTitle' ) );
        // content items
        this.items = [].slice.call( this.el.querySelectorAll( '.tabContent > section' ) );
        // current index
        this.current = -1;
        // show current content item
        this._show();
        // init events
        this._initEvents();
    };

    CBPFWTabs.prototype._initEvents = function() {
        var self = this;
        this.tabs.forEach( function( tab, idx ) {
            tab.addEventListener( 'click', function( ev ) {
                ev.preventDefault();
                self._show( idx );
            } );
        } );
    };

    CBPFWTabs.prototype._show = function( idx ) {
        if( this.current >= 0 ) {
			this.tabs[ this.current ].className = '';
			this.items[ this.current ].className = '';
		}
		// change current
		this.current = idx != undefined ? idx : this.options.start >= 0 && this.options.start < this.items.length ? this.options.start : 0;
		this.tabs[ this.current ].className = 'tab-current';
		this.items[ this.current ].className += (' content-current');
    };

    // add to global namespace
    window.CBPFWTabs = CBPFWTabs;

})( window );
</script>
<script type="text/javascript">
    new CBPFWTabs( document.getElementById( 'tabs' ) );
</script>
