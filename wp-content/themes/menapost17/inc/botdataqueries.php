<?php

class WP_Query_Post_Stats extends WP_Query {
	 var $stats_category;

    function __construct($args=array()) {
        add_filter('posts_join', array($this, 'join_stats'));
        if($args['stats_category']) {
        	$this->stats_category = $args['stats_category'];
        	remove_filter( 'posts_where', array($this, 'add_stats_category'));
        	add_filter('posts_where', array($this, 'add_stats_category'));
        } 
        parent::query($args);
    }

    function join_stats() {
        global $wpdb;
        return $wpdb->prepare(" JOIN post_stats ON wp_posts.id = post_stats.post_id ", '');
    }

    function add_stats_category($where) {
        global $wpdb;
        return $wpdb->prepare(" AND post_stats.category = %d ", $this->stats_category);
    }
}


function renderForBot($categories,$template,$datafunc) {

	ob_start();

 	foreach ($categories as $key => $category) {
 		$q = $datafunc((get_query_var('paged')) ? get_query_var('paged') : 1,$category);
	 	if ($q->have_posts()) { 
		    while ($q->have_posts()) { 
		    	$q->the_post();
		    	$html .= get_template_part('views/'.$template);
		    } 
		}
	} 

	$res = new StdClass(); 
	$res->html = ob_get_contents();
	$res->q = $q;
	ob_end_clean();
	return $res;

}


function fetchNewPostsForBot($page) {
	global $wpdb;

	$query_params = array(
    	'post_type'         => 'post',
    	'post_status'       => 'publish',
    	'posts_per_page'    => 12,
   		'paged'				=>	$page
	);

	wp_reset_query(); 
	return new WP_Query_Post_Stats( $query_params ); 

}


function fetchCategoryPostsForBot($page,$category=1) {
	global $wpdb;

	$query_params = array(
    	'post_type'         => 'post',
    	'post_status'       => 'publish',
    	'posts_per_page'    => 12,
   		'paged'				=>	$page,
   		'stats_category'	=>	$category
	);

	wp_reset_query(); 
	return new WP_Query_Post_Stats( $query_params );

}
