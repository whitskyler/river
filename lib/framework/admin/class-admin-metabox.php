<?php

/**
 * Admin Metabox Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Metabox Class
 * @since       0.0.10
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Admin_Metabox' ) ) :
/**
 * Class for handling a Metabox.
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.10
 */
abstract class River_Admin_Metabox extends River_Admin_Fields {
    
    /** Class Parameters ******************************************************/    
    
    /**
     * Metabox ID
     * 
     * @since 0.0.0
     * @var string
     */        
    protected $mb_id;
    
    /**
     * Post Types to attach this metabox
     * 
     * @since 0.0.0
     * @var array
     */ 
    protected $mb_post_type;
    
    /**
     * Metabox Title, displayed on the metabox
     * 
     * @since 0.0.0
     * @var string
     */     
    protected $mb_title;
    
    /**
     * Part of the page where the metabox will appear ('normal', 'advanced',
     * or 'side'). Default is 'advanced
     * 
     * @since 0.0.0
     * @var array
     */     
    protected $mb_context;
    
    /**
     * Context priority ('high', 'core', 'default', or 'low')
     * Default 'default'
     * 
     * @since 0.0.0
     * @var array
     */     
    protected $mb_priority;
    
    /**
     * Arguments to pass to the display callback.
     * 
     * @since 0.0.0
     * @var array
     */     
    protected $mb_callback_args;

    
    /** Constructor & Destructor **********************************************/
    
    /**
     * Let's create this Metabox
     * 
     * @since 0.0.9
     * 
     * @param array     $config Configuration for the new settings page                 
     */
    public function create( $config ) {
        
        $this->config_default_setup();
        
        if( ! isset( $config['id'] ) )        
            wp_die( sprintf( __( 'Metabox ID is not defined in %s', 'river' ), 
                    '$config[\'id\']' ) );               
        
        $this->mb_id = $this->settings_group = isset( $config['id'] ) ? $config['id'] : '';
        
        $this->mb_post_type = array();
        if( ! isset( $config['post_type'] ) || 'all' == $config['post_type'] ) {
            foreach ( (array) get_post_types( array( 'public' => true ) ) as $type )
                $this->mb_post_type[] = $type;
        }
        
        $this->mb_title = isset( $config['title'] ) ? $config['title'] : 'River Metabox';
        $this->mb_context = isset( $config['context'] ) ? $config['context'] : 'advanced';
        $this->mb_priority = isset( $config['priority'] ) ? $config['priority'] : 'default';
        $this->mb_callback_args = isset( $config['callback_args'] ) ? $config['callback_args'] : null;
        
        if ( ! isset( $this->mb_id ) || ! isset( $this->mb_title ) || ! isset( $this->mb_post_type ) )
            return;

        // Setup the filter defaults
        $this->setup_filter_defaults();        
        
        if( isset( $config[ 'default_fields' ] ) && is_array( $config[ 'default_fields' ] ) ) {
            
            $this->add_field_checker_filter();
            
            $default_fields = array();
            $defaults = array();            
            
            foreach( $config[ 'default_fields' ] as $key => $field ) {
                
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
                    apply_filters( "river_field_checker_{$this->mb_id}", $field );
                    
                if ( RIVER_FIELD_TYPE_ERROR == $response )                   
                    return;                    
                    
                $default_fields[$key] = $response;
                
                if ( 'heading' != $field['type']  )
                    $defaults[$key] = $field['default'];
                
                if ( 'timepicker' == $field['type']  )
                    $this->timepickers[] = $field;
                
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
        
        add_action( 'admin_head',            array( &$this, 'add_post_enctype' ) );
        add_action( 'admin_menu',            array( &$this, 'add_mb' ) );
        add_action( 'save_post',             array( &$this, 'save_mb'), 1, 2 );
        
        add_action( 'admin_enqueue_scripts', array( &$this, 'load_scripts' ), 10 );        
        add_action( 'admin_enqueue_scripts', array( &$this, 'load_styles' ), 10 );
        
        $this->add_display_field_filter();         
    }
    
    public function add_post_enctype() {
            echo '
            <script type="text/javascript">
            jQuery(document).ready(function(){
                    jQuery("#post").attr("enctype", "multipart/form-data");
                    jQuery("#post").attr("encoding", "multipart/form-data");
            });
            </script>';
    }    
    
    /**
     * Add the Metabox to each "configured" post type
     * 
     * @since 0.0.0
     * 
     * @link http://codex.wordpress.org/Function_Reference/add_meta_box
     */
    public function add_mb() {
        
        foreach( $this->mb_post_type as $type ) {
        
            add_meta_box( 
                    // HTML 'id' attribute of the edit screen section
                    $this->mb_id,
                    // Title of the edit screen section, visible to user
                    $this->mb_title, 
                    // $function Callback to display the metabox content
                    array( &$this, 'display_mb_callback' ),
                    // The type of Write screen on which to show the edit screen
                    // section ('post', 'page', 'link', or 'custom_post_type' 
                    // where custom_post_type is the custom post type slug)
                    $type,
                    // The part of the page where the edit screen section should 
                    // be shown ('normal', 'advanced', or 'side')
                    $this->mb_context,
                    // The priority within the context where the boxes should
                    // show ('high', 'core', 'default' or 'low')
                    $this->mb_priority,
                    // Arguments to pass into your callback function
                    $this->mb_callback_args 
                    );   
        }
        
    }
    
    /**
     * Save the metabox custom fields when saving a post or page, by grabbing
     * the array passed in $_POST, looping through it, validating & sanitizing
     * it, and then saving the field name (key) / value pair as a custom
     * field.
     *
     * @since 0.0.10
     *
     * @param integer       $post_id Post ID
     * @param stdClass      $post Post object
     * @return integer|null Returns post ID if the nonce was not correct or user
     *                      was not allowed to edit content. Returns null if 
     *                      doing autosave, ajax or cron, or saving a revision
     */    
    public function save_mb( $post_id, $post ) {
        
        $id = isset( $post_id ) ? $post_id : $post->ID;

        if( ! isset( $_POST[ $this->mb_id . '_mb_nonce' ] ) )
            return;
        
        // Verify the nonce
        $nonce = $_POST[ $this->mb_id . '_mb_nonce' ];
	if ( ! isset( $nonce ) || 
                ! wp_verify_nonce( $nonce, $this->mb_id . '_mb_nonce' ) )
		return $id;

        // Don't save if this is auto save routine, ajax, or future post.
        // If it is our form has not not been submitted, so we don't want to do
        // anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;
        
	// Verify user permissions
	if ( (  'page' == $_POST['post_type']  && 
                ! current_user_can( 'edit_page', $id ) ) || 
                ! current_user_can( 'edit_post', $id ) )
		return $id;

	// If this is a revision, don't save
	if ( 'revision' == $post->post_type )
            return;   
        
        $checks = array( 'checkbox' => 0, 'radio' => '0', 'multicheck' => array() );
        $data = isset( $_POST[ $this->mb_id ] ) ? $_POST[ $this->mb_id ] : array();
        
        foreach( $this->default_fields as $key => $field ) {
            $default_value = $field['default'];
            if( array_key_exists( $field['type'], $checks ) ) {
                
                $default_value = $checks[$field['type']];
                if ( 'radio' == $field['type'] && count( $field['choices'] ) > 1 )
                    $default_value = '';
            }
            
            $new_value = array_key_exists( $key, $data ) ? $data[$key] : $default_value;
            
            $old_value = river_get_custom_field( $key );
            $old_value = is_null( $old_value ) ? 
                $this->default_fields[$key]['default'] : $old_value;

            $new_value = $this->do_mb_validate_sanitize( $key, $new_value, $old_value );

            // no error occurred. Time to store the custom field
            if ( 'ERROR' !== $new_value )
                update_post_meta( $id, $key, $new_value );
            
        }
        
    }
    
    /** Callbacks *************************************************************/ 
    
    /**
     * Display/Render the metabox for this post or page
     * 
     * @since 0.0.0
     * 
     * @param stdClass      $post Post object 
     * @param array         $metabox array containing id, title, callback, &
     *                      callback args (if any were configured)
     */
    public function display_mb_callback( $post, $metabox ) {
        
        echo '<div id="river-container" class="metabox">';
        
        if ( function_exists( 'wp_create_nonce' ) )
            $river_mb_nonce = wp_create_nonce( $this->mb_id . '_mb_nonce' );
        
        printf( '<input type="hidden" name="%1$s" value="%2$s" />',
                $this->mb_id . '_mb_nonce', $river_mb_nonce);

        // Call the display_mb() method for the displaying the fields
        $this->display_mb_fields( $post, $metabox );
        
        echo '</div>';
    }
    
    
    /** Overload Methods ******************************************************/ 
    
    /**
     * Display/Render the metabox
     * 
     * USE THIS METHOD TO OVERLOAD IN CLASSES FOR CUSTOMIZING THE DISPLAY
     * 
     * @since 0.0.0
     * 
     * @param stdClass      $post Post object 
     * @param array         $metabox array containing id, title, callback, &
     *                      callback args (if any were configured)
     */    
    protected function display_mb_fields( $post, $metabox ) {
        
        foreach( $this->default_fields as $field_name => $field ) {
            
            $name = $this->get_field_name( $field_name, $this->mb_id );
            if( ! isset( $name ) || is_null( $name ) )
                continue;
            
            $value = river_get_custom_field( $field_name );
            $value = is_null( $value ) ? $field['default'] : $value;

            echo '<p>';
            if( isset( $field['title'] ) && ! empty( $field['title'] ) )
                printf( '<h4>%s</h4>', esc_html( $field['title'] ));                            

            echo apply_filters( "river_display_field_{$this->mb_id}", $field, $name, $value ); 
            echo '</p><br>';

        }        
    }
    
    
    /** Helper Functions ******************************************************/
    /**
     * Setup the config default arrays, which are used with wp_parse_args
     * in create();
     * 
     * @since 0.0.0
     */
    protected function config_default_setup() {
        
        $this->config_default_form = apply_filters(
                'river_config_default_form',
                array(
                    'id'                => 'river-form',
                    // Displayed under the page title
                    'version'           => '',
                    // Save button text
                    'button_save_text'  => __( 'Save All Changes', 'river' ),
                    'button_reset_text' => __( 'Reset All Options', 'river' ),
                )); 

    }     
    
    /**
     * Media Uploader setup
     * 
     * @since   0.0.0
     * 
     * @global type $pagenow
     * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
     */
    public function uploader_setup() {
	global $pagenow;
        
	if ( ( 'media-upload.php' == $pagenow ) || 
                ( 'async-upload.php' == $pagenow )) 
            add_filter( 'gettext', array( $this, 'replace_thickbox_text' ), 1, 3 );
	
    }    
    
    /**
     * To avoid confusion for the user, let's replace out Thickbox's button
     * text "Insert into Post" with something that makes more sense.
     * 
     * @since   0.0.0
     * 
     * @param string $translated_text
     * @param string $original_text
     * @return string Changed button text
     * 
     * @link http://codex.wordpress.org/Function_Reference/wp_get_referer
     * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
     */
    public function replace_thickbox_text( $translated_text, $original_text, $domain ) {
        
        if ( 'Insert into Post' == $original_text ) {

            if ( '' != strpos( wp_get_referer(), $this->page_id )  )
                return __('Load this one', 'river' );
        }

        return $translated_text;
    
    }
    
    /**
     * Enqueue the Style files
     * 
     * @since   0.0.0
     * 
     * @link    http://codex.wordpress.org/Function_Reference/wp_enqueue_style
     */
    public function load_styles( $hook ) {
        
  	if (    'post.php' == $hook || 'post-new.php' == $hook || 
                'page-new.php' == $hook || 'page.php' == $hook ) {        
        
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
     * @since   0.0.9
     * 
     * @uses    RIVER_ADMIN_URL
     * @uses    RIVER_VERSION
     * @uses    wp_enqueue_script()
     * @uses    wp_register_script()
     * @link    http://codex.wordpress.org/Function_Reference/wp_register_script
     */
    public function load_scripts( $hook ) {
        
  	if (    'post.php' == $hook || 'post-new.php' == $hook || 
                'page-new.php' == $hook || 'page.php' == $hook ) {

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
