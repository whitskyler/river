<?php

/**
 * WordPress Cleanup core functions
 *
 * @category    River 
 * @package     Framework Core
 * @subpackage  WP Cleanup
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 */


function river_wp_cleanup() {
    
//    // launching operation cleanup
//    add_action('init', 'bones_head_cleanup');
//    // remove WP version from RSS
//    add_filter('the_generator', 'bones_rss_version');
//    // remove pesky injected css for recent comments widget
//    add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
//    // clean up comment styles in the head
//    add_action('wp_head', 'bones_remove_recent_comments_style', 1);
//    // clean up gallery output in wp
//    add_filter('gallery_style', 'bones_gallery_style');
//
//    // enqueue base scripts and styles
//    add_action('wp_enqueue_scripts', 'bones_scripts_and_styles', 999);
//    // ie conditional wrapper
//    add_filter( 'style_loader_tag', 'bones_ie_conditional', 10, 2 );
//    
//    // launching this stuff after theme setup
//    add_action('after_setup_theme','bones_theme_support');	
//    // adding sidebars to Wordpress (these are created in functions.php)
//    add_action( 'widgets_init', 'bones_register_sidebars' );
//    // adding the bones search form (created in functions.php)
//    add_filter( 'get_search_form', 'bones_wpsearch' );
//    
//    // cleaning up random code around images
//    add_filter('the_content', 'bones_filter_ptags_on_images');
//    // cleaning up excerpt
//    add_filter('excerpt_more', 'bones_excerpt_more');   
    
}
add_action('after_setup_theme','river_wp_cleanup', 15);

/**
 * WordPress adds a lot of tags into the <head>.  Time to clean it up.
 * Note: None of these add value, especially for SEO, but they do make the
 * site load slower.
 * 
 * @since 0.0.0
 * 
 * @uses remove_action()
 */
function river_head_cleanup() {

    /**
     * For security, let's remove the meta tag that identifies this site
     * as a WordPress site
     */
    remove_action( 'wp_head', 'wp_generator' );
    
    // Relationship link tag, adjacent posts rel link tags
//    if ( ! river_get_seo_option( 'head_adjacent_posts_rel_link' ) )
//        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    
    // Windows Live Writer support
    if ( ! river_get_seo_option( 'head_wlwmanifest_link' ) )
        remove_action( 'wp_head', 'wlwmanifest_link' );
    
    // Shortlink tag
    if ( ! river_get_seo_option( 'head_shortlink' ) )
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
        
    if( ( is_single() && ! river_get_option( 'comments_posts' ) ) ||
           is_page() && ! river_get_option( 'comments_pages' ) )   
        remove_action( 'wp_head', 'feed_links_extra', 3 );
    
                        
    // Really Simple Discovery (EditURI) link
    remove_action( 'wp_head', 'rsd_link' );                               
                     
    // Index link
    remove_action( 'wp_head', 'index_rel_link' );                         
    // Previous link
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            
    // Start link
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             
    // Adjacent posts link
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); 
    
    // Remove the Recent Comments Widget's inline CSS
    if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) 
        remove_filter('wp_head', 'wp_widget_recent_comments_style' );    

}
add_action( 'get_header', 'river_head_cleanup' );



