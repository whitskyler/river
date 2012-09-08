<?php

/**
 * River Framework Initialization
 * 
 * Initializes the framework by declaring the constants, including the needed
 * files, declaring the theme supports, and more.
 *
 * @category    River 
 * @package     Framework
 * @subpackage  Init
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

function river_define_constants() {
    
    /** Define Parent Theme ***************************************************/    

    define( 'RIVER_NAME', 'River' );
    define( 'RIVER_VERSION', '0.0.0' );
    define( 'RIVER_DB_VERSION', '000' );
    define( 'RIVER_RELEASE_DATE', date_i18n( 'F j, Y', '1340211600' ) );
    
    /** Define Paths **********************************************************/     
    define( 'RIVER_DIR', get_template_directory() );
    define( 'RIVER_URL', get_template_directory_uri() );
    define( 'CHILD_DIR', get_stylesheet_directory() );
    define( 'CHILD_URL', get_stylesheet_directory_uri() );
    
    // Assets
    define( 'RIVER_ASSETS_DIR', RIVER_DIR . '/assets' );    
    define( 'RIVER_ASSETS_URL', RIVER_URL . '/assets' );
    define( 'RIVER_CSS_DIR', RIVER_ASSETS_DIR . '/css' );    
    define( 'RIVER_CSS_URL', RIVER_ASSETS_URL . '/css' );
    define( 'RIVER_IMAGES_DIR', RIVER_ASSETS_DIR . '/images' );    
    define( 'RIVER_IMAGES_URL', RIVER_ASSETS_URL . '/images' );
    define( 'RIVER_JS_DIR', RIVER_ASSETS_DIR . '/js' );    
    define( 'RIVER_JS_URL', RIVER_ASSETS_URL . '/js' ); 
    
    // Lib
    define( 'RIVER_LIB_DIR', RIVER_DIR . '/lib' );    
    define( 'RIVER_LIB_URL', RIVER_URL . '/lib' );
    
    // BuddyPress
    define( 'RIVER_BP_DIR', RIVER_LIB_DIR . '/buddypress' );    
    define( 'RIVER_BP_URL', RIVER_LIB_URL . '/buddypress' );
    
    // Framework
    define( 'RIVER_ADMIN_DIR', RIVER_LIB_DIR . '/framework/admin' );    
    define( 'RIVER_ADMIN_URL', RIVER_LIB_URL . '/framework/admin' );
    define( 'RIVER_CORE_DIR', RIVER_LIB_DIR . '/framework/core' );    
    define( 'RIVER_CORE_URL', RIVER_LIB_URL . '/framework/core' );
    define( 'RIVER_METABOX_DIR', RIVER_LIB_DIR . '/framework/metabox' );    
    define( 'RIVER_METABOX_URL', RIVER_LIB_URL . '/framework/metabox' );    
    define( 'RIVER_TEMPLATE_PARTS_DIR', RIVER_LIB_DIR . '/framework/template-parts' );    
    define( 'RIVER_TEMPLATE_PARTS_URL', RIVER_LIB_URL . '/framework/template-parts' ); 
    define( 'RIVER_WIDGETS_DIR', RIVER_LIB_DIR . '/framework/widgets' );    
    define( 'RIVER_WIDGETS_URL', RIVER_LIB_URL . '/framework/widgets' );
    
    // Languages
    define( 'RIVER_LANG_DIR', RIVER_LIB_DIR . '/languages' );    
    define( 'RIVER_LANG_URL', RIVER_LIB_URL . '/languages' );
    
    /** Define Database Constants *********************************************/
    define( 'RIVER_SETTINGS', 'river_settings' );
    define( 'RIVER_SEO_SETTINGS', 'river_seo_settings' );
    define( 'RIVER_SANITIZER_ERROR', 'RIVER SANITIZER ERROR');
    define( 'RIVER_FIELD_TYPE_ERROR', 'RIVER FIELD TYPE ERROR');
    
}
add_action( 'river_init', 'river_define_constants' );

/**
 * 
 * 
 * @since 0.0.0
 */
function river_load_includes() {
    
    // Run the river_pre_framework hook, which is called from the Child theme
    do_action( 'river_pre_framework' );
    
    /** Core ******************************************************************/     
    require_once( RIVER_CORE_DIR . '/core-helpers.php' );    
    
    /** Admin *****************************************************************/
    if ( is_admin() ) {
        require_once( RIVER_ADMIN_DIR . '/class-admin.php' );
        require_once( RIVER_ADMIN_DIR . '/class-admin-sanitizer.php' ); 
        require_once( RIVER_ADMIN_DIR . '/class-admin-fields.php' );      
        require_once( RIVER_ADMIN_DIR . '/admin-helpers.php'); 
        
        // Theme Settings Pages
        require_once( RIVER_ADMIN_DIR . '/class-admin-settings-page.php' );        
        require_once( RIVER_ADMIN_DIR . '/class-settings-river.php' );
        require_once( RIVER_ADMIN_DIR . '/class-settings-seo.php' );
        
        // Inpost metaboxes
        require_once( RIVER_ADMIN_DIR . '/class-admin-metabox.php' );        
        require_once( RIVER_ADMIN_DIR . '/class-metabox-seo.php' );
        require_once( RIVER_ADMIN_DIR . '/class-metabox-layouts.php' );
        
        // User Meta
        require_once( RIVER_ADMIN_DIR . '/class-admin-user-meta.php' );        
        require_once( RIVER_ADMIN_DIR . '/class-user-meta-river.php' );        
        
    }
  
    
}
add_action( 'river_init', 'river_load_includes' );

function river_add_theme_supports() {
    
    add_theme_support( 'menus' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'river-metabox-layouts' );
    add_theme_support( 'river-metabox-example' );    
    add_theme_support( 'river-metabox-seo' );
    add_theme_support( 'river-admin-menu' );
    add_theme_support( 'river-seo-menu' );
    add_theme_support( 'river-theme-options-menu' );

    
//    if ( ! current_theme_supports( 'river-menus' ) )
//        add_theme_support( 'river-menus', array( 
//            'top_menu' => __( 'Top Menu', 'river' ), 
//            'main_menu' => __( 'Main Menu', 'river' ), 
//            'footer_menu' => __( 'Footer Menu', 'river' )                
//        ));

    
}
add_action( 'river_init', 'river_add_theme_supports' );


/**
 * Everything is loaded.  Open the dam and let the River run.
 */
do_action( 'river_init' );


// For debugging
// Dumps all hooks that fires all actions and filters
// @link http://nacin.com/2010/04/23/5-ways-to-debug-wordpress/
//add_action( 'all', create_function( '', 'var_dump( current_filter() );' ) );