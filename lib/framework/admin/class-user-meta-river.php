<?php

/**
 * River User Meta Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  User Meta Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_User_Meta' ) ) :
/**
 * Class for handling adding, editing, & saving the user meta for a new
 * section of fields called 'User Permissions', 'Author Archive Settings',
 * 'SEO Settings', and 'Layout Settings'.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 */
class River_User_Meta extends River_Admin_User_Meta {
    
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
            'id'                    => 'river_meta',
            'title'                 => __( 'River Settings', 'river' ),
            'sections' => array(
                'user_permission' => array( 
                    'title'         => __( 'User Permissions', 'river'),
                    'desc'          => '',
                    'capability'    => 'edit_users',
                ),
                'author_archive' => array( 
                    'title'         => __( 'Author Archive Settings', 'river'),
                    'desc'          => __( 'These settings apply to this author\'s archive pages.', 'river' ),
                    'capability'    => 'publish_posts',
                ),                
                'seo' => array( 
                    'title'         => __( 'SEO Settings', 'river'),
                    'desc'          => __( 'These settings apply to this author\'s archive pages.', 'river' ),
                    'capability'    => 'publish_posts',
                ),
                'layout' => array( 
                    'title'         => __( 'Layout Settings', 'river'),
                    'desc'          => __( 'These settings apply to this author\'s archive pages.', 'river' ),
                    'capability'    => 'publish_posts',
                ),
            )
        );

        $config['default_fields']['river_settings_menu'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_settings_menu',
            // element's label
            'title'             => __( 'River Settings Menu', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enable River Settings Menu?', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'user_permission',
        );   

        $config['default_fields']['river_settings_seo_menu'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_settings_seo_menu',
            // element's label
            'title'             => __( 'River Settings SEO Menu', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enable River Settings SEO Menu?', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'user_permission',
        );
        
        // Author Archive Settings
        $config['default_fields']['river_author_headline'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_author_headline',
            // element's label
            'title'             => __( 'Author Headline', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Will display in the <h1></h1> tag at the top of the first page', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => 'author_archive',
        );
        
        $config['default_fields']['river_author_intro'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_author_intro',
            // element's label
            'title'             => __( 'Intro Text', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This text will be the first paragraph, and display on on the first page', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'author_archive',
        );
        

        $config['default_fields']['river_author_box_single'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_author_box_single',
            // element's label
            'title'             => __( 'Author Box Single', 'river' ),
            // (opt) description displayed under the element
            'desc'              => 'Enable Author box on this User\'s Posts?',
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'author_archive',
        );   

        $config['default_fields']['river_author_box_archive'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_author_box_archive',
            // element's label
            'title'             => __( 'Author Box Archive', 'river' ),
            // (opt) description displayed under the element
            'desc'              => 'Enable Author Box on this User\'s Archives?',
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'author_archive',
        );
        
        // SEO Settings
        $config['default_fields']['river_author_doctitle'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_author_doctitle',
            // element's label
            'title'             => __( 'Doctitle <title>', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => 'seo',
        );
        
        $config['default_fields']['river_author_meta_description'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_author_meta_description',
            // element's label
            'title'             => __( 'META Description', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'seo',
        );
        
        $config['default_fields']['river_author_keywords'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_author_keywords',
            // element's label
            'title'             => __( 'META Keywords', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Comma separated list', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => 'seo',
        ); 
        
        $config['default_fields']['river_noindex'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_noindex',
            // element's label
            'title'             => __( 'Robots Meta', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Apply noindex to this archive?', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'seo',
        );   

        $config['default_fields']['river_nofollow'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_nofollow',
            // element's label
            'title'             => '',
            // (opt) description displayed under the element
            'desc'              => __( 'Apply nofollow to this archive?', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'seo',
        ); 

        $config['default_fields']['river_noarchive'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_noarchive',
            // element's label
            'title'             => '',
            // (opt) description displayed under the element
            'desc'              => __( 'Apply noarchive to this archive?', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'seo',
        );
        
        $config['default_fields']['river_main_layout'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_main_layout',
            // element's label
            'title'             => __( 'Main Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'D is the default set in the Theme Settings.', 'river' ),
            // element's default value
            'default'           => 'default',
            // HTML element type
            'type'              => 'imgselect',
            'section_id'        => 'layout',
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
        
        $this->create( $config );
        
    } 
    
  
    
   
    
        
} // end of class


add_action( 'after_setup_theme', 'river_add_user_meta_river' );
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
function river_add_user_meta_river() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
    
    $current_user = wp_get_current_user();
    if ( ! current_user_can( 'edit_posts', $current_user->ID ) )
        return false; 
    $um = new River_User_Meta();

}

endif; // end of class_exists
