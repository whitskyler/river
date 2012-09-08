<?php

/**
 * SEO Settings Page Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  SEO Settings Page Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_SEO_Settings_Page' ) ) :
/**
 * Class for handling Settings Pages, per WordPress Settings API.
 * It handles Custom Menu Pages, Submenu Pages, and Theme Options Pages.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://codex.wordpress.org/Settings_API
 */
class River_SEO_Settings_Page extends River_Admin_Settings_Page {

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
            'id'                    => 'river_seo',
            'settings_group'        => 'river_seo_settings',
            'type'                  => 'sub_page', 
            'form'  => array(
                'id'                => 'river-seo',
                // Displayed under the page title
                'version'           => RIVER_VERSION,
                // Save button text
                'button_save_text'  => __( 'Save All Changes', 'river' ),             
            ),
            'page' => array(
                // id for this settings page
                'id'                => 'river_seo',
                'submenu'   => array(
                    'parent_slug'   => 'river',
                    'page_title'    => __( 'SEO', 'river' ),
                    'menu_title'    => __( 'SEO', 'river' ),
                    'capability'    => 'manage_options',
                    'menu_slug'     => 'river_seo',  
                ) 
            ),
            'sections' => array(
                'general'           => __( 'General', 'river'),
                'section2'          => __( 'Section 2', 'river')
            ), 
            'default_fields' => array()
        );


        /** General Section *******************************************************/

        // Example 'text' for a HTML text field
        $config['default_fields']['example_text'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_text',
            // element's label
            'title'             => __( 'Example Text Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the text input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => 'general',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => 'This is the placeholder',
        );

        // Example 'email' for a HTML text field
        $config['default_fields']['example_email'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_email',
            // element's label
            'title'             => __( 'Example Email Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Email input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'email',        
            // section these are assigned to
            'section_id'        => 'general',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'youremail@domain.com', 'river'),
        );    

        // Example 'url' for a HTML text field
        $config['default_fields']['example_url'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_url',
            // element's label
            'title'             => __( 'Example URL Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the URL input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'url',        
            // section these are assigned to
            'section_id'        => 'general',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'http://domain.com', 'river'),
        );     

        // Example 'textarea' for a HTML textarea field
        $config['default_fields']['example_textarea'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_textarea',
            // element's label
            'title'             => __( 'Example Textarea Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Textarea input.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'general',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => 'This is the placeholder',
        ); 

        /** Section 2 *************************************************************/    

        // Example 'checkbox' for a HTML checkbox field
        $config['default_fields']['example_checkbox2'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_checkbox2',
            // element's label
            'title'             => __( 'Example Checkbox Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Checkbox input.', 'river' ),
            // default value MUST be integer and 0 or 1
            'default'           => 1,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => 'section2',
        );

        $config['default_fields']['example_multicheck'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_multicheck',
            // element's label
            'title'             => __( 'Example Multicheck', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the multicheck.', 'river' ),
            // default value MUST be '' or an array, as shown
            'default'           => array( 'box1' => 'Choice 1', 'box3' => 'Choice 3' ),        
            // HTML field type
            'type'              => 'multicheck',        
            // section these are assigned to
            'section_id'        => 'section2',
            // Define the choices
            'choices' => array(
                'box1'  => array(
                    'name'      => 'box1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'box2' => array(
                    'name'      => 'box2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'box3' => array(
                    'name'      => 'box3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),            
                'box4' => array(
                    'name'      => 'box4',
                    'value'     => __( 'Choice 4', 'river' ),
                    'args'      => '',
                ),
            ),

            /** These are the Optional Arguments & do not have to be included *****/

            // (opt) add a custom class to the HTML element
            'class'             => '',
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'no_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string_choices',
            // (opt) sets an inline style
            'style'             => '',
        );    

        // Example 'radio' for a HTML radio fields
        $config['default_fields']['example_radio'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_radio',
            // element's label
            'title'             => __( 'Example Radio', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the Radio.', 'river' ),
            // default value MUST be a string and set to once of the choice keys
            'default'           => 'radio1',        
            // HTML field type
            'type'              => 'radio',        
            // section these are assigned to
            'section_id'        => 'section2',
            // Define the choices, minimum of 2+
            'choices' => array(
                'radio1'  => array(
                    'name'      => 'radio1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'radio2' => array(
                    'name'      => 'radio2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'radio3' => array(
                    'name'      => 'radio3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),
            ),
        );    

        // Example 'select' for a HTML select and its option fields
        $config['default_fields']['example_select'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_select',
            // element's label
            'title'             => __( 'Example Select', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the drop-down.', 'river' ),
            // default value MUST be a string and set to once of the choices
            'default'           => 'noselect',        
            // HTML field type
            'type'              => 'select',        
            // section these are assigned to
            'section_id'        => 'section2',
            // Define the choices (options), minimum of 2+
            'choices' => array(
                // Inserts a blank option
                'noselect'  => array(
                    'name'      => 'noselect',
                    'value'     => '',
                    'args'      => '',
                ),
                'select1'  => array(
                    'name'      => 'select1',
                    'value'     => __( 'Choice 1', 'river' ),
                    'args'      => '',
                ),
                'select2' => array(
                    'name'      => 'select2',
                    'value'     => __( 'Choice 2', 'river' ),
                    'args'      => '',
                ),
                'select3' => array(
                    'name'      => 'select3',
                    'value'     => __( 'Choice 3', 'river' ),
                    'args'      => '',
                ),
            ),
        );    
    
        $this->create( $config );
        
    }
    
    /**
     * Display a subheader on each section, such as a description or more
     * information
     * 
     * @since   0.0.3
     * 
     */
    public function display_section_callback() {
        
    }    
    
    /**
     * If defined in the $config file, build and render the Help Tab for
     * this Settings Page.
     * 
     * @uses help_tab()
     * @return
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_help_tab
     */
    public function help_tab() {
        
        $screen = get_current_screen();
        
        /*
         * Check if current screen is My Admin Page
         * Don't add help tab if it's not
         */
        if ( $screen->id != $this->page_hook )
            return;        
        
        $general = __( 'This is the help tab for the General Section.', 'river');


        $screen->add_help_tab( array(
            'id'         => 'river_settings_general_help_tab',
            'title'     => 'General Tab Help',
            'content'   => "<p>$general</p>"
        ));


        /**
         * Help Tab for the Appearance Section
         */
        $section2 = __( 'This is the help tab for Section 2.', 'river');

        $screen->add_help_tab( array(
            'id'         => 'river_settings_appearance_help_tab',
            'title'     => 'Appearance Tab Help',
            'content'   => "<p>$section2</p>"
        ));
            
    }    
}

add_action( 'river_admin_menu', 'river_add_admin_submenus' );
/**
 * Adds submenu items under River item in admin menu.
 *
 * @since 0.0.3
 * 
 * @return null Returns null if River menu is disabled
 */
function river_add_admin_submenus() {

    /** Do nothing, if not viewing the admin */
    if ( ! is_admin() )
        return;

    /** Don't add submenu items if River menu is disabled */
    if( ! current_theme_supports( 'river-seo-menu' ) )
        return;

    $current_user = wp_get_current_user();
    if ( ! get_the_author_meta( 'river_settings_seo_menu', $current_user->ID ) )
        return; // Disabled for this user
        
    $page = new River_SEO_Settings_Page();
      
}
endif;
