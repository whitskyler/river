<?php

/**
 * User Meta Class
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

if ( !class_exists( 'River_Admin_User_Meta' ) ) :
/**
 * Class for handling adding, editing, and saving additional User Meta fields
 * to the WordPress user profile page.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.0
 * 
 * @link    http://codex.wordpress.org/Settings_API
 */
abstract class River_Admin_User_Meta extends River_Admin_Fields {
    
    /** Class Parameters ******************************************************/
    
    /**
     * User Meta ID
     * 
     * @since 0.0.0
     * @var string
     */        
    protected $um_id;
    
    /**
     * Header Title
     * 
     * @since 0.0.0
     * @var string
     */        
    protected $um_title; 
    
    /**
     * Section Titles & Descriptions
     * 
     * @since 0.0.0
     * @var string
     */        
    protected $um_sections;     
    
    /**
     * Field IDs/Keys
     * 
     * @since 0.0.0
     * @var array
     */        
    protected $um_field_ids = array(); 
        
    /**
     * This User Meta only contains choice fields, i.e. checkboxes, radio, etc.
     * 
     * @since 0.0.0
     * @var boolean
     */    
    protected $um_all_choice;
        
    /**
     * Array containing the choice fields, i.e. checkboxes, radio, etc., and
     * their default value when none is passed in from $_POST
     * 
     * @since 0.0.0
     * @var boolean
     */ 
    protected $checks = array( 'checkbox' => 0, 'imgselect' => '',
        'radio' => '0', 'multicheck' => array() );
    
    /** Constructor & Destructor **********************************************/
    
    /**
     * Let's create this Metabox
     * 
     * @since 0.0.9
     * 
     * @param array     $config Configuration for the new settings page                 
     */
    public function create( $config ) {
        
        if( ! isset( $config['id'] ) )        
            wp_die( sprintf( __( 'User Meta ID is not defined in %s', 'river' ), 
                    '$config[\'id\']' ) );
        
        if( ! isset( $config['sections'] ) )        
            wp_die( sprintf( __( 'User Meta Sections is not defined in %s', 'river' ), 
                    '$config[\'sections\']' ) );        
        
        $this->um_id = $this->settings_group = isset( $config['id'] ) ? $config['id'] : '';
        $this->um_title = isset( $config['title'] ) ? $config['title'] : '';
        if( isset( $config['sections'] ) && is_array( $config['sections'] ) ) {
            foreach( $config['sections'] as $key => $value )
                $this->sections[$key] = wp_parse_args ( 
                        $value, 
                        array( 
                            'title'         => '',
                            'desc'          => '',
                            'capability'    => '',
                            'default_fields'=> array(),
                        ) );
            
        }
        
        if ( ! isset( $this->um_id ) || ! isset( $this->um_title ) || ! isset( $this->sections ) )
            return;

        // Setup the filter defaults
        $this->setup_filter_defaults();        
        
        if( isset( $config[ 'default_fields' ] ) && is_array( $config[ 'default_fields' ] ) ) {
            
            $this->add_field_checker_filter();
            
            $default_fields = array();
            $defaults = array();
            $this->um_all_choice = true;
            
            foreach( $config[ 'default_fields' ] as $key => $field ) {
                
                // One of the fields is not a choice type
                if( ! array_key_exists( $key, $this->checks ) )
                   $this->um_all_choice = false; 
                
                // Check that the field type in the config file for this option
                // is in the available_field_types array.  If no, skip this one.
                if( ! array_key_exists( $field['type'], 
                        $this->get_available_field_types_filters() ) )
                    return;
                
                $field['sanitizer_filter'] = $this->get_field_sanitizer_filter( $field );
                $field['validator_filter'] = $this->get_field_validator_filter( $field );
                if( ! $field['sanitizer_filter'] || ! $field['validator_filter'] )                    
                    return;
                
                $response = 
                    apply_filters( "river_field_checker_{$this->um_id}", $field );
                    
                if ( RIVER_FIELD_TYPE_ERROR == $response )                   
                    return;                    
                    
                $default_fields[$key] = $response;
                
                $this->um_field_ids[$key] = '';
                
                if ( 'heading' != $field['type']  )
                    $defaults[$key] = $field['default'];
                
                $this->sections[$field['section_id']]['default_fields'][$key] = $field;
            }
           
            $this->default_fields = isset( $default_fields ) & is_array( $default_fields ) && ! empty( $default_fields ) ? $default_fields : '';
            $this->defaults = isset( $defaults ) & is_array( $defaults ) && ! empty( $defaults ) ? $defaults : '';                                    
        }        
        
        // Return if there's no default_fields or defaults
        if( ! $this->default_fields || ! $this->defaults )
            return;        
        
        $this->hooks();
    }    
    
    
    /** Setup Functions *******************************************************/   
    
    /**
     * Hooks and Filters
     * 
     * @since   0.0.0
     * 
     * @uses    add_action()
     */
    private function hooks() {
        
        add_action( 'show_user_profile',        array( $this, 'add_profile_fields' ) );
        add_action( 'edit_user_profile',        array( $this, 'add_profile_fields' ) );
        add_action( 'personal_options_update',  array( &$this, 'save_user_meta' ) );
        add_action( 'edit_user_profile_update', array( &$this, 'save_user_meta' ) );         
        
        add_action( 'admin_enqueue_scripts',    array( &$this, 'load_scripts' ), 10 );        
        add_action( 'admin_enqueue_scripts',    array( &$this, 'load_styles' ), 10 );
        
        $this->add_display_field_filter();         
    } 
       
    /**
     * Adds / updates user meta when user profile page is saved ("update profile").
     *
     * @since 0.0.0
     *
     * @param integer   $user_id User ID
     * @return null     Returns null if current user cannot edit users, 
     *                  or no meta fields submitted
     */    
    public function save_user_meta( $user_id ) {

        // If all the fields are choice fields, then if all of them are unchecked
        // the fields are not submitted.  We handle this by knowing that this
        // um only contains choice fields.
	if ( ! $this->um_all_choice && 
                ( ! isset( $_POST[$this->um_id] ) || ! is_array( $_POST[$this->um_id] ) ) )
            return;
        
        // Extra security check
        if ( ! isset( $_POST['user_id'] ) || $user_id !== (int) $_POST['user_id'] )
            return;
        
        $meta = isset( $_POST[ $this->um_id ] ) ? $_POST[ $this->um_id ] : array();
        
        foreach( $this->sections as $section_key => $section ) {
            
            if ( ! current_user_can( $section['capability'], $user_id ) )
                continue;
            
            // Loop through each of the fields
            foreach( $section['default_fields'] as $key => $field ) {
                // Get the default value first, as we need this in case this field
                // field is not in the POST
                $default_value = $field['default'];
                if( array_key_exists( $field['type'], $this->checks ) ) {

                    $default_value = $this->checks[$field['type']];
                    if ( 'radio' == $field['type'] && count( $field['choices'] ) > 1 )
                        $default_value = '';
                }

                $new_value = array_key_exists( $key, $meta ) ? $meta[$key] : $default_value;
                // Get the old value
                $old_value = get_the_author_meta( $key, $user_id );

                // Time to validate to make sure the submitted field value is what
                // we expect it to be.  Then we'll sanitize it.
                $new_value = $this->do_mb_validate_sanitize( $key, $new_value, $old_value );

                // no error occurred. Time to store the user meta
                if ( 'ERROR' !== $new_value )
                    update_user_meta( $user_id, $key, $new_value );

            }  
        }
    }
    
    /**
     * Add fields to the user profile for the selected user, if the user has
     * permission
     *
     * @since 0.0.0
     *
     * @param WP_User   $user User object
     * @return false    Return false if current user cannot edit users
     */    
    public function add_profile_fields( $user ) {
        
        echo '<div id="river-container" class="user-meta">';        
        
        if( isset( $this->um_title ) )
            printf( '<h2>%s</h2>', $this->um_title );
        
        foreach( $this->sections as $key => $section ) {
            
            if ( ! current_user_can( $section['capability'], $user->ID ) )
                continue;            
            
            printf( '<h3>%s</h3><span class="description">%s</span>',
                    esc_html( $section['title'] ),
                    esc_html( $section['desc'] ) );
	?>

            <table class="form-table">
                <tbody>

                    <?php 
                    // Loop through all the fields in this section
                    foreach( $section['default_fields'] as $field_name => $field )
                        $this->display_user_meta_fields( $user, $field_name, $field );
                    ?>

                </tbody>
            </table>
	<?php 
        }
        echo '</div>';        
    }    
    
    /** Overload Methods ******************************************************/ 
    
    /**
     * Display/Render the user meta field
     * 
     * USE THIS METHOD TO OVERLOAD IN CLASSES FOR CUSTOMIZING THE DISPLAY
     * 
     * @since 0.0.0
     * 
     * @param WP_User   $user User object
     * @param string    $field_name Field's ID/Key
     * @param array     $field Associative array of the field
     * @return null     Returns if error occurs 
     */    
    protected function display_user_meta_fields( $user, $field_name, $field ) {
            
        $name = $this->get_field_name( $field_name, $this->um_id );
        if( ! isset( $name ) || is_null( $name ) )
            return;

        $value = get_the_author_meta( $field_name, $user->ID );

        if( '' == $value && array_key_exists( $field['type'], $this->checks ) )
            $value = $field['default'];
        ?>

        <tr>
            <th scope="row" valign="top">
                <?php
                if( isset( $field['title'] ) && ! empty( $field['title'] ) )
                    printf( '%s', esc_html( $field['title'] ));                     
                ?>
            </th>
            <td>
                <?php echo apply_filters( 
                        "river_display_field_{$this->um_id}", $field, $name, $value ); ?>
            </td>
        </tr>
        <?php   
    }    
    
    /** Helper Functions ******************************************************/    
    
    /**
     * Enqueue the Style files
     * 
     * @since   0.0.0
     * 
     * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style
     */
    public function load_styles( $hook ) {
        
  	if ( 'profile.php' == $hook || 'user-edit.php' == $hook ) {        
        
            wp_register_style( 'river_admin_css', RIVER_ADMIN_URL . '/assets/css/river-admin.css' );
            wp_register_style( 'jquery_timepicker_css', RIVER_ADMIN_URL . '/assets/css/jquery.timepicker.css' ); 
            
            wp_enqueue_style( 'river_admin_css' );
            wp_enqueue_style( 'jquery_timepicker_css' );

            // Media Uploader Stylesheet
            wp_enqueue_style( 'thickbox' );
        }
    }    
    
    /**
     * Enqueue the script files
     * 
     * @since   0.0.0
     * 
     * @uses    RIVER_ADMIN_URL
     * @uses    RIVER_VERSION
     * @uses    wp_enqueue_script()
     * @uses    wp_register_script()
     * @link    http://codex.wordpress.org/Function_Reference/wp_register_script
     */
    public function load_scripts( $hook ) {
        
  	if ( 'profile.php' == $hook || 'user-edit.php' == $hook ) {

            wp_register_script( 
                    'river-admin', 
                    RIVER_ADMIN_URL . '/assets/js/river-admin.js', 
                    array( 'jquery', 'media-upload', 'thickbox', 
                        'jquery-ui-datepicker', 'jquery-ui-core' ), 
                    '0.0.9', true );
            
            // @link http://jscolor.com/
            wp_register_script( 'jscolor', RIVER_ADMIN_URL . '/assets/js/jscolor.js', '', '', true);
            wp_register_script( 'jquery-river', RIVER_ADMIN_URL . '/assets/js/jquery.river.min.js', '', '0.0.9');            

            wp_enqueue_script( 'river-admin' );
            wp_enqueue_script( 'jscolor' );
            wp_enqueue_script( 'jquery-river' );
            
            // Variables to pass to the riverAdmin script
            $pass_to_script = array(
                'timepicker'     => $this->timepickers,
            );
            
            wp_localize_script( 'river-admin', 'riverAdminParams', $pass_to_script );              
        }
    }      
    
} // end of class

endif; // end of class_exists