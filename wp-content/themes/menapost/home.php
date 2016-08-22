<?php

/**
 * Clean Up the Homepage UI
 */
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );

remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

remove_action( 'genesis_after_header', 'genesis_do_after_header' );
// add_action( 'genesis_after_header', 'mp_do_after_header' );

remove_action( 'genesis_header', 'genesis_do_header' );
add_action( 'genesis_header', 'mp_do_home_header' );

remove_action( 'genesis_loop', 'genesis_do_loop' ); 
add_action( 'genesis_loop', 'mp_home_loop' );

add_filter( 'wp_title', 'mp_home_title', 15, 3 );

function mp_home_title() {
    return get_bloginfo( 'name' );
}


/**
 * Creates the slider and the top navbar.
 * @link mp_header.php
 */
function mp_do_home_header() {
    $result_articles = get_featured_articles();
    include( CHILD_DIR  . '/mp_home_header.php' );
}

/**
 * Creates the navbar below the header.
 * @link mp_after_header.php
 */
function mp_do_after_header() {
    include( CHILD_DIR . '/mp_home_after_header.php' );
}

/**
 * Creates the article layout (includes the loop).
 * @link mp_home_loop.php
 */
function mp_home_loop() {

    $count = 0;
    $categories = mp_get_categories();
    
    $selected_category = get_query_var( 'category' );
    $selected_subcategory = get_query_var( 'subcategory' );
    
    $result_articles = get_articles_by_filter( get_sort_filter(), get_category_filter( $categories, $selected_category, $selected_subcategory ), $count, 0, true );
    
    $show_more_button = $count > Constants::HOMEPAGE_ARTICLES_COUNT;
    
    include( CHILD_DIR . '/mp_home_loop.php' );
}


/**
 * Loads genesis engine.
 */
genesis();
