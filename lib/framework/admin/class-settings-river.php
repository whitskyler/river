<?php

/**
 * Admin Page Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Page Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Settings_Page' ) ) :
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
class River_Settings_Page extends River_Admin_Settings_Page {
    
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
            'id'                    => 'river',
            'settings_group'        => 'river_settings',
            'type'                  => 'main_page', 
            'form'  => array(
                'id'                => 'river-form',
                // Displayed under the page title
                'version'           => RIVER_VERSION,
            ),
            'page' => array(
                // id for this settings page
                'id'                => 'river',
                'main_menu' => array(
                    'page_title'    => 'River Settings',
                    'menu_title'    => 'River',
                    'capability'    => 'manage_options',
                    'icon_url'      => '',
                    'position'      => '58.996',
                    'separator'     => array(
                        'position'  => '58.995',
                        'capability'=> 'edit_theme_options',
                    ),
                ),
                'first_submenu'   => array(
                    'parent_slug'   => 'river',
                    'page_title'    => __( 'River Settings', 'river' ),
                    'menu_title'    => __( 'River Settings', 'river' ),
                    'capability'    => 'manage_options',  
                )            
            ),
            'sections' => array(
                'general'           => __( 'General', 'river'),
                'section2'          => __( 'Section 2', 'river'),
                'appearance'        => __( 'Appearance', 'river'),
                'scripts'           => __( 'Scripts', 'river')            
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
        $config['default_fields']['example_checkbox'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_checkbox',
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



        /** Appearance Section ************************************************/

        // Example 'imgselect', which is a HTML radio buttons but with an image
        // shown instead of the radio button.  Ways to use may be for page layout
        // selection, footer layout, etc.    
        $config['default_fields']['main_layout'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'main_layout',
            // element's label
            'title'             => __( 'Main Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the img select.', 'river' ),
            // element's default value
            'default'           => 'layout2',
            // HTML element type
            'type'              => 'imgselect',
            // section these are assigned to
            'section_id'        => 'appearance',
            'choices' => array(
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

        $config['default_fields']['footer_layout'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'footer_layout',
            // element's label
            'title'             => __( 'Footer Layout', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'This is a description for the img select.', 'river' ),
            // element's default value
            'default'           => 'layout1',
            // HTML element type
            'type'              => 'imgselect',
            // section these are assigned to
            'section_id'        => 'appearance',
            'choices' => array(
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

        // Example 'colorpicker' for a HTML colorpicker field
        $config['default_fields']['example_colorpicker'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'example_colorpicker',
            // element's label
            'title'             => __( 'Example Colorpicker Input', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Click on the field to select the new color.', 'river' ),
            // default value
            'default'           => 'FFFFFF',        
            // HTML field type
            'type'              => 'colorpicker',        
            // section these are assigned to
            'section_id'        => 'appearance',
        );       


        // Example 'upload-image' for a HTML text field + image previewer
        // Uses WordPress media loader and .jscolor
        $config['default_fields']['header_logo'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'header_logo',
            // element's label
            'title'             => __( 'Header Logo', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter the URL to your logo for the theme header.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'upload-image',        
            // section these are assigned to
            'section_id'        => 'appearance',
            'placeholder'       => __( 'Click to select the image.', 'river' ),
        );    

        // Example 'upload-image' for a HTML text field + image previewer
        // Uses WordPress media loader and .jscolor
        $config['default_fields']['favicon'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'favicon',
            // element's label
            'title'             => __( 'Favicon', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter the URL to your custom favicon. It should be 16x16 pixels in size.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'upload-image',        
            // section these are assigned to
            'section_id'        => 'appearance',
            'placeholder'       => __( 'Click to select the image.', 'river' ),
        );

        // Example 'textarea' for a HTML textarea field
        $config['default_fields']['custom_css'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'custom_css',
            // element's label
            'title'             => __( 'Custom Styles', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter any custom CSS here to apply it to your theme.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'appearance',

            /** These are the Optional Arguments & do not have to be included *****/        

            // (opt) add a custom class to the HTML element
            'class'             => '',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'Enter custom css code here', 'river'),
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'no_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string',
            // (opt) sets an inline style
            'style'             => '',
        );  

        // Example 'textarea' for a HTML textarea field
        $config['default_fields']['tracking_code'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'tracking_code',
            // element's label
            'title'             => __( 'Tracking Code', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter your Google Analytics (or other) tracking code here.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'scripts',

            /** These are the Optional Arguments & do not have to be included *****/        

            // (opt) add a custom class to the HTML element
            'class'             => '',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'Paste tracking code here', 'river'),
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'unfiltered_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string',
            // (opt) sets an inline style
            'style'             => '',
        );

        // Example 'textarea' for a HTML textarea field
        $config['default_fields']['header_scripts'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'header_scripts',
            // element's label
            'title'             => __( 'Header Scripts', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter scripts or code to be place in wp_head()', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'scripts',

            /** These are the Optional Arguments & do not have to be included *****/        

            // (opt) add a custom class to the HTML element
            'class'             => '',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'Paste scripts or code here', 'river'),
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'unfiltered_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string',
            // (opt) sets an inline style
            'style'             => '',
        );

        // Example 'textarea' for a HTML textarea field
        $config['default_fields']['footer_scripts'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'footer_scripts',
            // element's label
            'title'             => __( 'Footer Scripts', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Enter scripts or code to be place in wp_footer()', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => 'scripts',

            /** These are the Optional Arguments & do not have to be included *****/        

            // (opt) add a custom class to the HTML element
            'class'             => '',
            // (opt) Sets a placeholder in the form's text field
            'placeholder'       => __( 'Paste scripts or code here', 'river'),
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'unfiltered_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string',
            // (opt) sets an inline style
            'style'             => '',
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
         * Help Tab for the Section 2
         */
        $section2 = __( 'This is the help tab for Section 2.', 'river');

        $screen->add_help_tab( array(
            'id'         => 'river_settings_section2_help_tab',
            'title'     => 'Section 2 Tab Help',
            'content'   => "<p>$section2</p>"
        ));        


        /**
         * Help Tab for the Appearance Section
         */
        $appearance = __( 'This is the help tab for the Appearance Section.', 'river');

        $screen->add_help_tab( array(
            'id'         => 'river_settings_appearance_help_tab',
            'title'     => 'Appearance Tab Help',
            'content'   => "<p>$appearance</p>"
        ));
        
        /**
         * Help Tab for the Scripts Section
         */
        $scripts = __( 'This is the help tab for the Scripts Section.', 'river');

        $screen->add_help_tab( array(
            'id'         => 'river_settings_scripts_help_tab',
            'title'     => 'Scripts Tab Help',
            'content'   => "<p>$scripts</p>"
        ));        
            
    }     
}

add_action( 'after_setup_theme', 'river_add_admin_menu' );
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
function river_add_admin_menu() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
  
    /**
     * Let's do some checks to ensure we should proceed
     */
    if ( ! current_theme_supports( 'river-admin-menu' ) )
        return; // Programmatically disabled  
    
    $current_user = wp_get_current_user();
    if ( ! get_the_author_meta( 'river_settings_menu', $current_user->ID ) )
        return; // Disabled for this user
      
    $page = new River_Settings_Page();
    // Let's do the submenus now
    do_action( 'river_admin_menu' );

}
endif;