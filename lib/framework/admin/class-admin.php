<?php

/**
 * Validates the incoming Settings Page configuration file.  It also
 * validates and sanitizes the default settings.
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Abstract Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Admin' ) ) :
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
abstract class River_Admin {

        
    /** Class Parameters ******************************************************/    
    /**
     * Menu page hook
     * 
     * @since 0.0.0
     * @var string
     */
    protected $page_hook;

    /**
     * ID of the admin menu and settings page.
     *
     * @since 0.0.0  
     * @var string  
     */
    protected $page_id;
    
    /**
     * Type of Settings Page
     * 
     * menu_page, submenu, or theme_page
     * 
     * @since 0.0.0
     * @var string
     */
    protected $page_type;
    
    /**
     * Page configuration parameters array
     * 
     * @since 0.0.0
     * @var array
     */
    protected $page;
    
    /**
     * The settings group name, i.e. database 
     * 
     * @since 0.0.0
     * @var string  
     */
    protected $settings_group; 
   
    /**
     * Array of the section names
     * 
     * @since 0.0.0
     * @var array 
     */
    protected $sections;
    
    /**
     * Associative array to the default fields
     * 
     * @since 0.0.0
     * @var array
     */
    protected $default_fields;     
       
    /**
     * Array of display form ids and classses
     * 
     * @since 0.0.0
     * @var array
     */
    protected $form;
    
    /**
     * Associate array of name/value pairs for each default setting/option
     * 
     * @since 0.0.0
     * @var array 
     */
    protected $defaults; 

    /** Getters ***************************************************************/    
    
    /**
     * Getter for class properties
     * 
     * @since 0.0.0
     * @param mixed     $name Property name to retrieve
     * @return mixed    Returns the value of the requested property
     */
    public function __get( $name ) {
        return $name;
    }
    
    /**
     * Get the Page ID
     * 
     * @since 0.0.0
     * @return string
     */
    public function get_page_id() {
        return $this->page_id;
    }
    
    /**
     * Get the Page Hook Name
     * 
     * @since 0.0.0
     * @return string
     */    
    public function get_page_hook() {
        return $this->page_hook;
    }
    
    /**
     * Get the settings group name
     * 
     * @since 0.0.0
     * @return string
     */     
    public function get_settings_group() {
        return $this->settings_group;
    }
    
    /**
     * Getter for the requested option name
     * 
     * @since 0.0.0
     * 
     * @param string    $option_name name of the option to retrieve
     * @return mixed    Returns the option requested either from cache or
     *                  the options database
     */
    public function get_option( $option_name ) {        
        return river_get_option( $this->settings_group, $option_name );
    } 
    
    /**
     * Builds the field name attribute to be used in a form field
     * 
     * Structure is settings_group[option_id]
     * 
     * @since 0.0.0
     * 
     * @param string    $option_id ID or key for the option
     * @return string   Completed field name
     */
    public function get_field_name( $option_id, $settings_group = null ) { 
        $settings_group = isset( $settings_group ) ? $settings_group : $this->settings_group;
        return sprintf( '%s[%s]', $settings_group, $option_id );
    }  
    
}
endif;