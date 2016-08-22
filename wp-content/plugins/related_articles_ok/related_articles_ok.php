<?php

/**
 * Plugin Name: Related Articles - OK
 * Plugin URI: http://www.omerkalim.com
 * Description: Related articles with respect to the current article detail page.
 * Version: 1.0
 * Author: Omer Kalim
 * Author URI: http://www.omerkalim.com
 * License: Private
 */

if (basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__)) exit('Please do not load this page directly');

class RelatedArticles{
	
    function __construct() {
        add_action('admin_menu', array( &$this, 'setting_relatedarticle') );
        
        add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_style') );
        
        //[Short Code]
        add_shortcode( 'related_articles_shortcode', array( &$this, 'display_relatedarticle' ) );
    }

    function enqueue_style() {
        wp_register_style( 'realted-article-stylesheet', plugins_url('realted-article.css', __FILE__) );
        wp_enqueue_style( 'realted-article-stylesheet' );
    }
    
    //Position Widget Header
    function display_relatedarticle ()  {
        
        if( is_single() && !is_preview() ){
            
            $new_content_output = '';
            $new_content_output .= '<div class="ra_Wrap">';

            $id = get_the_ID();
            $limit = 3;
            $relatedarticles_taxonomies = get_option('relatedarticles_taxonomies');

            $post_tags = wp_get_post_tags($id);
            $taxonomy_arrays = array();
            $taxonomy_arrays = array_merge($post_tags);

            foreach ($relatedarticles_taxonomies  as $value) {
                $taxonomy_temp = wp_get_post_terms($id, $value);

                $taxonomy_arrays = array_merge($taxonomy_arrays, $taxonomy_temp);
            }

            $term_arrays = array();
            foreach ($taxonomy_arrays as $single_arr) {
                $term_arrays[] = $single_arr->term_id;
            }

            $all_terms = implode(",", $term_arrays);

            global $wpdb;
            $query = "SELECT sub_table.ID, sub_table.TITLE, sub_table.SLUG, COUNT(1) AS counter 
                        FROM
                            (SELECT wp_posts.ID as ID, wp_posts.post_title as TITLE, wp_posts.post_name as SLUG, wp_posts.post_date as DATE, wp_terms.term_id
                            FROM wp_posts
                            INNER JOIN wp_term_relationships ON wp_posts.ID = wp_term_relationships.object_id
                            INNER JOIN wp_terms ON wp_term_relationships.term_taxonomy_id = wp_terms.term_id
                            WHERE wp_terms.term_id IN ( $all_terms )
                            AND wp_posts.post_status = 'publish'
                            AND wp_posts.ID <> $id
                            ORDER BY wp_posts.post_date DESC)
                        AS sub_table
                        GROUP BY sub_table.ID
                        ORDER BY counter DESC
                        LIMIT $limit";
            $result = $wpdb->get_results($query);

                $new_content_output .= '<h2>قد يعجبك أيضاً</h2>';
                    foreach ($result as $row) {
                        $related_id = $row->ID;
                        $related_title = $row->TITLE;


                        // Fetching image from the MP_Post filer, @TODO - to be changed after cropping tool plugin.
                        $related_image = apply_filters( 'get_post_image', $related_id, 'polaroid' );

                        //Fetching the normal image post meta, as this is stand alone plugin and cannot detect the user defined postmeta without hard coding
                        // $resized_images = get_post_meta($related_id, "image", true);

                        // if($resized_images == ""){
                        //     $related_image = "http://placehold.it/1440x720";
                        // }else{
                        //     $related_image = $resized_images;
                        // }
                        
                        // $related_image_str = substr($related_image, 0, strlen($related_image)-4);
                        // $related_image = $related_image_str . "-480x240.jpg";

                        $new_content_output .= '<div class="ra_box">';
                            $new_content_output .= '<div class="ra_thumb">';
                                $new_content_output .= '<a href="' . get_permalink( $related_id ) . '">';
                                    $new_content_output .= '<img class="img-responsive" src="' . $related_image . '" >';
                                $new_content_output .= '</a>';
                            $new_content_output .= '</div>';
                            $new_content_output .= '<div class="ra_description">';
                                $new_content_output .= '<a href="' . get_permalink( $related_id ) . '">';
                                    $new_content_output .= '<p>' . $related_title . '</p>';
                                $new_content_output .= '</a>';
                            $new_content_output .= '</div>';
                        $new_content_output .= '</div>';
                    }

            $new_content_output .= '</div>';   
            
            return $new_content_output;
        }
    }

    function setting_relatedarticle_admin() {
        include('relatedarticle_admin.php');
    }

    function setting_relatedarticle() {
        add_options_page("RelatedArticle", "RelatedArticle", 'administrator', "RelatedArticle", array( &$this, "setting_relatedarticle_admin" ) );
    }

}

$plugin_relatedarticle = new RelatedArticles();