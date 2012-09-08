<?php

/**
 * Layouts Metabox Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Metabox Layouts
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Metabox_Layouts' ) ) :
/**
 * Class for handling a Metabox.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://codex.wordpress.org/Settings_API
 */
class River_Metabox_Layouts extends River_Admin_Metabox {
    
    /** Constructor & Destructor **********************************************/
    
    /**
     * Let's create this Settings Page
     * 
     * NOTE:  ONLY INSTANTIATE ONE SETTINGS PAGE AT A TIME!
     * 
     * @since 0.0.0
     * 
     * @param array     $config Configuration for the new settings page                 
     */
    public function __construct() { 
        
        $config = array(
            'id'                => 'river_layouts',
            'title'             => 'River Layouts',
            'post_type'         => 'all',
            'context'           => 'advanced',
            'priority'          => 'default',
            'callback_args'     => '',
            'default_fields'    => array(),
        );
        
        $config['default_fields']['river_layouts_main_layout'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_layouts_main_layout',
            // element's label
            'title'             => __( 'Main Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'D is the default set in the Theme Settings.', 'river' ),
            // element's default value
            'default'           => 'default',
            // HTML element type
            'type'              => 'imgselect',
            'section_id'        => '',
            'choices' => array(
                'default' => array(
                    'name'      => 'default',
                    'value'     => __( 'Default', 'river' ),
                    'args'      => array(
                        'type'      => 'img',                  
                        'value'     => RIVER_ADMIN_URL . '/assets/images/default.gif',
                        // Image title
                        'title'     => 'Default', 
                        // Image alt
                        'alt'       => 'Default',
                    ),
                ),
                'layout1' => array(
                    'name'      => 'layout1',
                    'value'     => __( 'Layout 1', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        // Image source URL and filename.  This is the image that
                        // is shown instead of the radio button                    
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content.gif',
                        // Image title
                        'title'     => 'Content', 
                        // Image alt
                        'alt'       => '',                    
                    ),
                ),
                'layout2' => array(
                    'name'      => 'layout2',
                    'value'     => __( 'Layout 2', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content-sidebar.gif',
                        'title'     => 'Content-Sidebar',          
                        'alt'       => 'Content-Sidebar',                    
                    ),
                ),
                'layout3' => array(
                    'name'      => 'layout3',
                    'value'     => __( 'Layout 3', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-content.gif',
                        'title'     => 'Sidebar-Content',          
                        'alt'       => 'Sidebar-Content',                    
                    ),
                ),
                'layout4' => array(
                    'name'      => 'layout4',
                    'value'     => __( 'Layout 4', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/content-sidebar-sidebar.gif',
                        'title'     => 'Content-Sidebar-Sidebar',          
                        'alt'       => 'Content-Sidebar-Sidebar',                    
                    ),
                ),
                'layout5' => array(
                    'name'      => 'layout5',
                    'value'     => __( 'Layout 5', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-content-sidebar.gif',
                        'title'     => 'Sidebar-Content-Sidebar',          
                        'alt'       => '',                    
                    ),
                ),
                'layout6' => array(
                    'name'      => 'layout6',
                    'value'     => __( 'Layout 6', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/sidebar-sidebar-content.gif',
                        'title'     => 'Sidebar-Sidebar-Content',          
                        'alt'       => 'Sidebar-Sidebar-Content',                    
                    ),
                ),            
            ),
            'style' => 'display: inline;',
        ); 

        $config['default_fields']['river_layouts_footer_layout'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_layouts_footer_layout',
            // element's label
            'title'             => __( 'Footer Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'D is the default set in the Theme Settings.', 'river' ),
            // element's default value
            'default'           => 'default',
            // HTML element type
            'type'              => 'imgselect',
            'section_id'        => '',
            'choices' => array(
                'default' => array(
                    'name'      => 'default',
                    'value'     => __( 'Default', 'river' ),
                    'args'      => array(
                        'type'      => 'img',                  
                        'value'     => RIVER_ADMIN_URL . '/assets/images/default.gif',
                        // Image title
                        'title'     => 'Default', 
                        // Image alt
                        'alt'       => 'Default',  
                    ),                        
                ),                
                'layout1' => array(
                    'name'      => 'layout1',
                    'value'     => __( 'Layout 1', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        // Image source URL and filename.  This is the image that
                        // is shown instead of the radio button 
                        'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-0.png',
                        // Image title
                        'title'     => 'None',          
                        // Image alt
                        'alt'       => 'None',                    
                    ),
                ),
                'layout2' => array(
                    'name'      => 'layout2',
                    'value'     => __( 'Layout 2', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-1.png',
                        'title'     => 'One',          
                        'alt'       => 'one',                    
                    ),
                ),
                'layout3' => array(
                    'name'      => 'layout3',
                    'value'     => __( 'Layout 3', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-2.png',
                        'title'     => 'Two',          
                        'alt'       => 'two',                    
                    ),
                ),
                'layout4' => array(
                    'name'      => 'layout4',
                    'value'     => __( 'Layout 4', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-3.png',
                        'title'     => 'Three',          
                        'alt'       => 'three',                    
                    ),
                ),
                'layout5' => array(
                    'name'      => 'layout5',
                    'value'     => __( 'Layout 5', 'river' ),
                    'args'      => array(
                        'type'      => 'img',
                        'value'     => RIVER_ADMIN_URL . '/assets/images/footer-widgets-4.png',
                        'title'     => 'Four',          
                        'alt'       => 'four',                    
                    ),
                ),           
            ),
            'style' => 'display: inline;',
        );         
        
        $this->create( $config );
    } 
        
} // end of class


add_action( 'after_setup_theme', 'river_add_layouts_mb' );
/**
 * Adds River top-level item in admin menu.
 *
 * Calls the river_admin_menu hook at the end - all submenu items should be
 * attached to that hook to ensure correct ordering.
 *
 * @since 0.0.0
 *
 * @return null Returns null if River menu is disabled, or disabled for current user
 */
function river_add_layouts_mb() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
  
    /**
     * Let's do some checks to ensure we should proceed
     */
    if ( ! current_theme_supports( 'river-metabox-layouts' ) )
        return; // Programmatically disabled  
    
    $current_user = wp_get_current_user();
    if ( ! current_user_can( 'edit_posts', $current_user->ID ) )
        return false; 
      
    $mb = new River_Metabox_Layouts();

}

endif; // end of class_exists