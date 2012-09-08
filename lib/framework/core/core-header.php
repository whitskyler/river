<?php

/**
 * header.php Core Functions
 *
 * @category    River 
 * @package     Framework Core
 * @subpackage  Header
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 */


function river_title_build(  $title, $sep, $seplocation ) {
    
}
add_action( 'river_title', 'wp_title' );
add_filter( 'wp_title', 'river__build', 10, 3 );

/**
 * Wraps the page's title in <title> tags.
 *
 * @since 0.0.0
 *
 * @param string $title Document title
 * @return string Plain text or HTML markup
 */
function river_title( $title ) {
    
    //return is_feed() || is_admin() ? $title : sprintf( "<title>%s</title>\n", $title );    

    if ( is_feed()  || is_admin() ) {
        return $title;
    } else {
        return sprintf( "<title>%s</title>\n", $title );
    }
}
add_filter( 'wp_title', 'crl_title_tag_wrap', 20 );

?>
