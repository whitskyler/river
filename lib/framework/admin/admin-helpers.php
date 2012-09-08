<?php

/**
 * Admin Helper Functions
 *
 * @category    River 
 * @package     Framework Core
 * @subpackage  Helpers
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */


/**
 * Redirects the user to an admin page, and adds query args to the URL string
 * for alerts, etc.
 *
 * @since 0.0.0
 *
 * @param string $page Menu slug
 * @param array $query_args Optional. Associative array of query string
 * arguments (key => value). Default is an empty array
 * @return null Returns early if first argument is falsy
 */
function river_admin_redirect( $page, $query_args = array() ) {

    if ( ! $page )
        return;
    $menu_page_url = menu_page_url( $page, 0 );
    $rawurl = html_entity_decode( menu_page_url( $page, 0 ) );

    foreach ( (array) $query_args as $key => $value ) {
        if ( empty( $key ) && empty( $value ) )
            unset( $query_args[$key] );
    }

    $url = add_query_arg( $query_args, $rawurl ); 

    wp_redirect( esc_url_raw( $url ) );

}

/**
 * Add a separator on the admin menu bar
 * 
 * @param string    $position Separator position on admin menu bar
 * @param string    $capability
 * @return
 */
function river_add_admin_menu_separator( $position, $capability ) {

    if( ! is_string( $position ) || ! is_string( $capability ) )
        return;


    $GLOBALS['menu'][$position] = array( 
        '',  
        $capability, 
        'separator',
        '', 
        'river-separator wp-menu-separator' );            
  
}

/**
 * Determines if this page is a River Admin Page
 *
 * @since 0.0.0
 *
 * @global string   $page_hook Page hook for current page
 * @param string    $pagehook Page hook string to check
 * @return boolean  Returns true if the global $page_hook matches given 
 *                  $pagehook. False otherwise
 */
function river_is_menu_page( $pagehook = '' ) {

    global $page_hook; // WHERE DOES THIS COME FROM????????

    if ( isset( $page_hook ) && $page_hook == $pagehook )
        return true;

    // Check the global $_REQUEST
    if ( isset( $_REQUEST['page'] ) && ( $pagehook == $_REQUEST['page'] ) )
        return true;

    return false;

}
