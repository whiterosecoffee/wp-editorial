<?php

do_action( 'navbar' );

$search_query = get_search_query();
//$search_results = mp_get_search_results( $search_query );
$search_results = FALSE;
$total_results = 0;
$query = '';

try {
	$page = (get_query_var('page')) ? get_query_var('page') : 1;
	extract( GoogleSearch::get_results( $search_query, $page ) );
} catch( Exception $e ) {
	$search_results = FALSE;
}

?>
<section class="search-content-section">
<header class="search-content-header" data-page="search-results">
	<h1><?php printf( __( 'Keyword %s .. Results %d', 'menapost-theme' ), '<span class="orange">' . $query . '</span>', $total_results ); ?></h1>
</header>

<?php if( $search_results ) : ?>

<ol start="<?php echo $search_results[ 'offset' ]; ?>">
	
	<?php foreach ($search_results[ 'items' ] as $article) : ?>
	<li>
		<article>
		<a href="<?php echo $article->url; ?>">
			<div><?php echo $article->title ?></div></a>
			<div class="detail-text hidden-xs"><?php echo $article->description; ?></div>
			<div class="info-footer separator">
				<?php $author = $article->get_author();
				if( $author ) : ?>
					<span><?php echo $author; ?></span>
				<?php endif; ?>
			</div>
			<div class="info-footer">
				<span><?php echo $article->get_date(); ?></span>
			</div>
		
		</article>
	</li>
	<?php endforeach; ?>

</ol>

<div class="search-pagination">
	<ul class="pagination">

		<?php
			$current_page = $search_results[ 'page' ];
			$total_pages = $search_results[ 'total_pages' ];

			$page_numbers = range( 1, $total_pages );
			$slice_offset = $current_page <= 5 ? 
								0 : 
								( ( $total_pages - $current_page <= 5 ) ? 
									$total_pages - 10 :
									$current_page - 5 ); 
			$page_numbers = array_slice( $page_numbers,  $slice_offset, 10 );
			$page_numbers = array_reverse( $page_numbers );
		?>
		<?php if( $current_page != $total_pages ): ?>
			<li><a href="<?php echo home_url() . $_SERVER["REQUEST_URI"] ?>" data-page-number="<?php echo $current_page + 1; ?>"> >> </a></li>
		<?php endif; ?>

		<?php foreach( $page_numbers as $i ) : ?>
		<li <?php echo $current_page == $i ? 'class="active"' : ''; ?>><a href="<?php echo home_url() . $_SERVER["REQUEST_URI"] ?>" data-page-number="<?php echo $i; ?>"><?php echo $i; ?></a></li>
		<?php endforeach; ?>

		<?php if( $current_page != 1 ): ?>
			<li><a href="<?php echo home_url() . $_SERVER["REQUEST_URI"] ?>" data-page-number="<?php echo $current_page - 1; ?>"> << </a></li>
		<?php endif; ?>

		<li><span><?php echo __( 'Page', 'menapost-theme' ) . ': '; ?>&nbsp;</span></li>
	</ul>
</div>
<?php else: ?>

<?php get_template_part( 'mp_loop_else' ); ?>

<?php endif; ?>

</section>