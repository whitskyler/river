<?php

/**
 * Handles the declaration, structure, and rendering of fields
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Fields Class
 * @since       0.0.9
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Admin_Fields' ) ) :
/**
 * Handles the declaration, structure, and rendering of fields
 *
 * @category    River
 * @package     Framework Admin
 *
 * @since       0.0.9
 */
abstract class River_Admin_Fields extends River_Admin_Sanitizer {
    
    /** Class Parameters ******************************************************/    
    
    /**
     * Array of all the configured timepickers
     * 
     * Used to pass to the javascript for the timepickers script
     * 
     * @since 0.0.9
     * @var array
     */     
    protected $timepickers = array();
    
    /** Field Sanitizer & Validator Filters ***********************************/  
    
    /**
     * Get the Sanitizer Filter for the incoming field
     * 
     * @since 0.0.4
     * 
     * @param type      $field Field to get the filter
     * @return string   Returns the filter or ''
     */
    protected function get_field_sanitizer_filter( $field ) {
        
        // If no sanitizer filter was defined, need to attach one
        // by the HTML element 'type'         
        if( ! isset( $field['sanitizer_filter'] ) ) {

            foreach( $this->sanitizer_filters as $filter => $type ) {

                // if this field's type is in the sanitizer array,
                // save it
                if( in_array( $field['type'], $type ) ) {                    
                    $field['sanitizer_filter'] = $filter;
                    break; // found it, break out of loop
                }
            }
        }
        
        return isset( $field['sanitizer_filter'] ) ? $field['sanitizer_filter'] : '';
    }
    
    /**
     * Get the Validator Filter for the incoming field
     * 
     * @since 0.0.4
     * 
     * @param type      $field Field to get the filter
     * @return string   Returns the filter or ''
     */    
    protected function get_field_validator_filter( $field ) {

        // If no validator filter was defined, need to attach one
        // by the HTML element 'type'
        if( ! isset( $field['validator_filter'] ) ) {

            foreach( $this->validator_filters as $filter => $type ) {

                // Check the type and class against the validator filters
                if( in_array( $field['type'], $type ) || 
                        ( isset( $field['class'] ) && in_array( $field['class'], $type ) ) ) {                    
                    $field['validator_filter'] = $filter;
                    break; // found it, break out of loop
                }
            }
        } 
        
        return isset( $field['validator_filter'] ) ? $field['validator_filter'] : '';        
        
    }
 
    /** Field Checker *********************************************************/  
    
    /**
     * Add field checker filter to 'river_field_checker_{$this->settings_group}'
     *
     * @since 0.0.4
     *
     * @return boolean Returns true when complete
     */
    protected function add_field_checker_filter() {

        add_filter( "river_field_checker_{$this->settings_group}", array( $this, 'field_checker' ), 10, 1 );

        return true;

    } 
    /**
     * Check the passed field
     * 
     * @since 0.0.4
     * 
     * @param array     $field Field to check
     * @return mixed    $field or RIVER_FIELD_TYPE_ERROR
     */
    public function field_checker( $field ) {
        
        $response = $this->do_field_filter( $field['type'], $field );
        
        return $response;
        
    }
      
    /**
     * Checks field filter exists, and if so, passes the value through it.
     * 
     * @uses call_user_func() to provide the callback to assigned filter
     *
     * @since 0.0.4
     *
     * @param string    $filter Field Type filter type
     * @param array     $field contains the entire field config
     * @return mixed    Returns filtered value
     */
    protected function do_field_filter( $filter, $field ) {

        $available_filters = $this->get_available_field_types_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $field;
        
        // Callback to the assigned filter
        return call_user_func( $available_filters[$filter], $field );

    }

    /**
     * Return array of known field type filter types.
     *
     * Array can be filtered via 'river_available_field_type_filters' to let
     * child themes and plugins add their own filters.
     *
     * @since 0.0.9
     *
     * @return array    Associative array containing the filter types
     *                  as the keys and filter method callback as the values
     */
    public function get_available_field_types_filters() {

        $default_filters = array(
                'checkbox'          => array( $this, 'f_checkbox'       ),
                'colorpicker'       => array( $this, 'f_text'           ),
                'datepicker'        => array( $this, 'f_text'           ),        
                'email'             => array( $this, 'f_text'           ),        
                'heading'           => array( $this, 'f_heading'        ),
                'imgselect'         => array( $this, 'f_imgselect'      ),
                'multicheck'        => array( $this, 'f_choices'        ),
                'radio'             => array( $this, 'f_choices'        ),
                'select'            => array( $this, 'f_choices'        ),            
                'text'              => array( $this, 'f_text'           ),
                'textarea'          => array( $this, 'f_text'           ),
                'timepicker'        => array( $this, 'f_timepicker'     ),
                'upload-image'      => array( $this, 'f_text'           ),
                'url'               => array( $this, 'f_text'           ),
                'wysiwyg'           => array( $this, 'f_wysiwyg'        ),
        );        

        return apply_filters( 'river_available_field_type_filters', $default_filters );
                
    } 
    
    /**
     * Checkbox Checker:  checks the keys/structure of the $field and that
     * the default value is set, an integer, and equal to 0 or 1.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */
    public function f_checkbox( $field ) {
        
        if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $field ) )
            return RIVER_FIELD_TYPE_ERROR;

        // Check if default is set and is an integer
        if ( !isset( $field['default'] ) || ! is_integer( $field['default'] ) )
            return RIVER_FIELD_TYPE_ERROR;
        
        // Check that the default is set to either 0 or 1
        return 0 == $field['default'] || 1 == $field['default'] ? 
                $field : RIVER_FIELD_TYPE_ERROR;

    }    

    /**
     * Choices Checker: checks key structure, choice key structure, and default
     * value is set properly and contained in the choices.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */    
    public function f_choices( $field ) {

        // First, check that the keys are setup properly
        if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $field, null, TRUE ) )
            return RIVER_FIELD_TYPE_ERROR;

        // Need to have a default
        if ( ! isset( $field['default'] ) )
             return RIVER_FIELD_TYPE_ERROR;   

        
        // Now let's check the structure of the choices
        $choice_args = array(
            'name'      => '',
            'value'     => '',
            'args'      => '',            
        );
        
        foreach( $field['choices'] as $ckey => $choice ) {
            
            if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $choice, $choice_args ) )
                return RIVER_FIELD_TYPE_ERROR;
        }
        
        // Next check the defaults and compare them to choices
        if( RIVER_FIELD_TYPE_ERROR == 
                $this->check_default_against_choices( $field['default'], 
                        $field['choices'], $field['type'] ) )
             return RIVER_FIELD_TYPE_ERROR;              
       
        
        // If we get this far, it passes
        return $field;
    }  
    
    
    /**
     * Heading checker: checks key structure.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */     
    public function f_heading( $field ) {
            
       // Example 'heading' for a HTML <h4> tag
        $args = array(
            // field's ID for field's array & HTML element
            'id'                => '',
            // This is the text between the <h4> tags
            'desc'              => '',      
            // HTML field type
            'type'              => 'heading',        
            // section these are assigned to
            'section_id'        => '',
        );  
        
        return $this->check_keys_structure( $field, $args );    
    }
    
    /**
     * imgselect Checker: checks key structure, choice key structure, default
     * value is set properly and contained in the choices, and the choice args
     * is properly setup
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */      
    public function f_imgselect( $field ) {
        
        // Run f_choices to check the main portion
        if ( RIVER_FIELD_TYPE_ERROR == $this->f_choices( $field ) )
                return RIVER_FIELD_TYPE_ERROR;

        // Check that the choices args array is setup properly
        $args = array(
            'type'      => 'img',
            'value'     => '',
            'title'     => '',          
            'alt'       => '',                            
        );
        
        foreach( $field['choices'] as $ckey => $choice ) {
            
            if ( ! isset( $choice['args'] ) || ! is_array( $choice['args'] ) )
                return RIVER_FIELD_TYPE_ERROR;
            
            if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $choice['args'], $args ) )
                return RIVER_FIELD_TYPE_ERROR;
        }        
        
        // If we get this far, it passes        
        return $field;
    }
    
    /**
     * Text checker: checks key structure and default value is a string.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */    
    public function f_text( $field ) {
        
        $placeholder = isset( $field['placeholder'] ) ? TRUE : FALSE;
        
        if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $field, null, FALSE, $placeholder ) )
            return RIVER_FIELD_TYPE_ERROR;
            
        // Check if default is set and is an integer
        return is_string( $field['default'] ) ? $field : RIVER_FIELD_TYPE_ERROR;    
    }
    
    /**
     * Text checker: checks key structure and default value is a string.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */    
    public function f_timepicker( $field ) {
        
        $args = array(
            'id'                => '',
            'title'             => '',
            'desc'              => '',
            'default'           => '',
            'type'              => '',
            'section_id'        => '',
            'class'             => '',
            'placeholder'       => '',            
            'sanitizer_filter'  => '',
            'validator_filter'  => '',
            'style'             => '',
            'args'  => array(
                /** Options ***************************************************/
                
                // The character to use to separate hours and minutes. (default: ':')
                'time_separator'                => ':',
                //Define whether or not to show a leading zero for hours < 10. (default: true)
                'show_leading_zero'             => 'true',
                // Define whether or not to show a leading zero for minutes < 10. (default: true)
                'show_minutes_leading_zero'     => 'true',
                // Define whether or not to show AM/PM with selected time. (default: false)
                'show_period'                   => 'false',
                // Define if the AM/PM labels on the left are displayed. (default: true)
                'show_period_labels'            => 'true',
                // The character to use to separate the time from the time period.
                'period_separator'              => ' ',
                // Define an alternate input to parse selected time to
                'alt_field'                     => '#alternate_input',
                // Used as default time when input field is empty or for inline timePicker
                // (set to 'now' for the current time, '' for no highlighted time,
                // default value: 'now')
                'default_time'                  => 'now',
                
                /** trigger options *******************************************/
                // Define when the timepicker is shown.
                // 'focus': when the input gets focus, 'button' when the button trigger element is clicked,
                // 'both': when the input gets focus and when the button is clicked.
                'show_on'                       => 'focus',
                // jQuery selector that acts as button trigger. ex: '#trigger_button'
                'button'                        => 'null',
                
                /** Localization **********************************************/
                // Define the locale text for "Hours"
                'hour_text'                     => __( 'Hour', 'river' ),
                // Define the locale text for "Minutes"
                'minute_text'                   => __( 'Minutes', 'river' ),
                // Define the locale text for periods
                'am_pm_text' => array(
                    'am'                        => __( 'AM', 'river' ),
                    'pm'                        => __( 'PM', 'river' ),
                ),
                
                /** Position **************************************************/
                // Corner of the dialog to position, used with the jQuery UI 
                // Position utility if present.
                'my_position'                   => 'left top',
                // Corner of the input to position
                'at_position'                   => 'left bottom',
                
                /** custom hours and minutes **********************************/
                'hours' => array(
                    // First displayed hour
                    'starts'                    => 0,
                    // Last displayed hour
                    'ends'                      => 23
                ),
                'minutes' => array(
                    // First displayed minute
                    'starts'                    => 0,
                    // Last displayed minute
                    'ends'                      => 59,
                    // Interval of displayed minutes
                    'interval'                  => 1,
                ),
                // Number of rows for the input tables, minimum 2, makes more 
                // sense if you use multiple of 2
                'rows'                          => 4,
                // Define if the hours section is displayed or not. 
                // Set to 0 to get a minute only dialog
                'show_hours'                    => 'true',
                // Define if the minutes section is displayed or not. 
                // Set to 0 to get an hour only dialog
                'show_minutes'                  => 'true',
                
                /** buttons ***************************************************/
                // shows an OK button to confirm the edit
                'show_close_button'             => 'false',
                // Text for the confirmation button (ok button)
                'close_button_text'             => __( 'Done', 'river' ),
                // Shows the 'now' button
                'show_now_button'               => 'false',
                // Text for the now button
                'now_button_text'               => __( 'Now', 'river' ),
                // Shows the deselect time button
                'show_deselect_button'          => 'false',
                // Text for the deselect button
                'deselect_button_text'          => __( 'Deselect', 'river' ),
            ),
        ); 
        
        if ( ! isset( $field['placeholder'] ) )
           $field['placeholder'] = '';

        if ( ! isset( $field['class'] ) )
            $field['class'] = '';

        if ( ! isset( $field['style'] ) )
            $field['style'] = '';        
        
        if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $field, $args ) )
            return RIVER_FIELD_TYPE_ERROR;
            
        // Check if default is set and is an integer
        return is_string( $field['default'] ) ? $field : RIVER_FIELD_TYPE_ERROR;    
    } 
    
   /**
     * Wysiwyg checker: checks key structure and default value is a string.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field The field to check
     * @return str|arr  Returns the $field array if it passes the checks;
     *                  else returns error message 
     */    
    public function f_wysiwyg( $field ) {
        
        $args = array(
            'id'                => '',
            'title'             => '',
            'desc'              => '',
            'default'           => '',
            'type'              => '',
            'section_id'        => '',            
            'sanitizer_filter'  => '',
            'validator_filter'  => '',
            'args'  => array(
                 // use wpautop, default is TRUE
                'wpautop'       => TRUE,
                // Whether to display media insert/upload buttons, default is TRUE
                'media_buttons' => TRUE,
                // The name assigned to the generated textarea and passed parameter 
                // when the form is submitted. (may include [] to pass data as array)
                // default: $editor_id
                'textarea_name' => '',
                // The number of rows to display for the textarea
                'textarea_rows' => get_option('default_post_edit_rows', 10),
                // The tabindex value used for the form field, default: none
                'tabindex'      => '',
                // Additional CSS styling applied for both visual and HTML editors 
                // buttons, needs to include <style> tags, can use "scoped"
                'editor_css'    => '',
                // Any extra CSS Classes to append to the Editor textarea
                'editor_class'  => '',
                // Whether to output the minimal editor configuration used 
                // in PressThis.  default: FALSE
                'teeny'         => FALSE,
                // Whether to replace the default fullscreen editor with DFW (needs 
                // specific DOM elements and css).  default: FALSE
                'dfw'           => FALSE,
                // Load TinyMCE, can be used to pass settings directly to TinyMCE 
                // using an array(). default: TRUE
                'tinymce'       => TRUE,
                // Load Quicktags, can be used to pass settings directly to 
                // Quicktags using an array(). default: TRUE
                'quicktags'     => TRUE,
            ),
        );      
        
        if ( RIVER_FIELD_TYPE_ERROR == $this->check_keys_structure( $field, $args ) )
            return RIVER_FIELD_TYPE_ERROR;
            
        // Check if default is set and is an integer
        return is_string( $field['default'] ) ? $field : RIVER_FIELD_TYPE_ERROR;    
    }    
    
    /**
     * Check the keys to make sure they match
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param array     $field
     * @param array     $args (opt) If not passed in, method will set automatically
     * @param bool      $has_choices If this field type has choices, set to
     *                  TRUE and choices will be added to $args
     * @return mixed    Returns $field if it passed; else error message
     */
    private function check_keys_structure( $field, $args = null, $has_choices = FALSE, $has_placeholder = FALSE ) {
        
        // If $args is not passed, then it's the default structure
        if ( is_null( $args ) ) {
            
            $args = array(
                'id'                => '',
                'title'             => '',
                'desc'              => '',
                'default'           => '',
                'type'              => '',
                'section_id'        => '',
                'class'             => '',
                'sanitizer_filter'  => '',
                'validator_filter'  => '',
                'style'             => '',
            );        
            if ( $has_choices )
                $args['choices'] = array();
            
            if ( $has_placeholder )
                $args['placeholder'] = '';
            
            if ( ! isset( $field['class'] ) )
                $field['class'] = '';

            if ( ! isset( $field['style'] ) )
                $field['style'] = '';            
        }

        $key_diff = array_diff_key( $args, $field );
        $key_diff1 = array_diff_key( $field, $args );
        
        if( ! empty( $key_diff ) || ! empty ( $key_diff1 ) )
            return RIVER_FIELD_TYPE_ERROR;
        
        return $field;
    }

    /**
     * Check default against the choices to make sure the default(s) in the
     * choices array.
     * 
     * @uses RIVER_FIELD_TYPE_ERROR
     * 
     * @since 0.0.4
     * 
     * @param mixed     $default Default field to check
     * @param array     $choices array of choices
     * @param type      $type field type
     * @return mixed    Returns $field if it passed; else error message
     */
    private function check_default_against_choices( $default, $choices, $type ) {
        
        switch ( $type ) {
            
            case 'imgselect':
            case 'radio':
            case 'select':
                
                if ( ! is_string( $default ) || 
                        ! array_key_exists( $default, $choices ) )
                    return RIVER_FIELD_TYPE_ERROR;                 
                break;

            case 'multiselect':
            case 'multicheck':
            default:
                
                // if an array, then we check each default to make sure it's
                // in the choices array
                if( is_array( $default ) ) {

                    foreach( $default as $skey => $svalue ) {
                        if ( ! array_key_exists( $skey, $choices ) )
                                return RIVER_FIELD_TYPE_ERROR;
                    }
                } else {
                    return RIVER_FIELD_TYPE_ERROR;
                }               
        }
        
        return $default;
       
    }
    
    /** Display | Render Field ************************************************/     
 
    /**
     * Add display field filter to 'river_display_field_{$this->settings_group}'
     *
     * @since 0.0.4
     *
     * @return boolean Returns true when complete
     */
    protected function add_display_field_filter() {

        add_filter( "river_display_field_{$this->settings_group}", array( $this, 'display_field' ), 10, 3 );

        return true;

    } 
    /**
     * Display the called field on the form
     * 
     * @since 0.0.4
     * 
     * @param array     $field Field to check
     * @return mixed    $field or RIVER_FIELD_TYPE_ERROR
     */
    public function display_field( $field, $name, $value ) {
        
        $response = $this->do_display_field_filter( $field['type'], $field, $name, $value );
        
        return $response;
        
    }
      
    /**
     * Checks display field filter exists, and if so, passes the value through it.
     * 
     * @uses call_user_func() to provide the callback to assigned filter
     *
     * @since 0.0.4
     *
     * @param string    $filter Field Type display filter type
     * @param array     $field contains the entire field config
     * @return mixed    Returns filtered value
     */
    protected function do_display_field_filter( $filter, $field, $name, $value ) {

        $available_filters = $this->get_available_display_field_types_filters();

        if ( ! in_array( $filter, array_keys( $available_filters ) ) )
            return $field;
        
        // Callback to the assigned filter
        return call_user_func( $available_filters[$filter], $field, $name, $value );

    }

    /**
     * Return array of known display field type filter types.
     *
     * Array can be filtered via 'river_available_display_field_type_filters' to let
     * child themes and plugins add their own filters.
     *
     * @since 0.0.4
     *
     * @return array    Associative array containing the filter types
     *                  as the keys and filter method callback as the values
     */
    public function get_available_display_field_types_filters() {

        $default_filters = array(
                'checkbox'          => array( $this, 'd_checkbox'       ),
                'colorpicker'       => array( $this, 'd_colorpicker'    ),
                'datepicker'        => array( $this, 'd_datepicker'     ),        
                'email'             => array( $this, 'd_text'           ),        
                'heading'           => array( $this, 'd_heading'        ),
                'imgselect'         => array( $this, 'd_imgselect'      ),
                'multicheck'        => array( $this, 'd_multicheck'     ),
                'radio'             => array( $this, 'd_radio'          ),
                'select'            => array( $this, 'd_select'         ),            
                'text'              => array( $this, 'd_text'           ),
                'textarea'          => array( $this, 'd_textarea'       ),
                'timepicker'        => array( $this, 'd_timepicker'     ),
                'upload-image'      => array( $this, 'd_upload_image'   ),
                'url'               => array( $this, 'd_text'           ),
                'wysiwyg'           => array( $this, 'd_wysiwyg'        ),            
        );        

        return apply_filters( 'river_available_display_field_type_filters', $default_filters );
                
    }     
    
    /** Display | Render Field ************************************************/     
    
    /**
     * Display | Render the Checkbox field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_checkbox( $field, $name, $value ) {

        $output = sprintf( '<input id="%1$s" class="checkbox%2$s" type="checkbox" ' .
                'name="%3$s" value="1" %4$s />',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),
                checked( $value, 1, false ) );

        $output .= sprintf( '<label for="%1$s">%2$s</label>',
                esc_attr( $field['id'] ),
                esc_html( $field['desc'] ) ); 
            
        return $output;
        
    }
     
    /**
     * Display | Render the Colorpicker field
     * 
     * @since 0.0.9
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_colorpicker( $field, $name, $value ) {

        return sprintf( '<input id="%1$s" class="color%2$s" name="%3$s" ' .
                'value="%4$s" />', 
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),
                esc_attr( $value ) ); 
        
    }
    
    /**
     * Display | Render the Datepicker field
     * 
     * @since 0.0.9
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_datepicker( $field, $name, $value ) {
        
        $output = sprintf( '<input id="%1$s" class="datepicker%2$s" type="text" ' .
                'name="%3$s" value="%4$s" placeholder="%5$s" />',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),                       
                esc_attr( $value ),                        
                isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '' );

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
        
        return $output;
        
    }   
    
    /**
     * Display | Render the Heading field within <h4> tag
     * 
     * @since 0.0.4
     * 
     * @param array     $field Field's definition
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_heading( $field ) {

        return sprintf ( '<h4 class="heading">%s</h4>',
                    esc_html( $field['desc'] ) );
        
    }
    
    /**
     * Display | Render the imgselect field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_imgselect( $field, $name, $value ) {

        $output = sprintf( '<div id="%s" class="imgselect">', esc_attr( $field['id'] ) );

        foreach ( $field['choices'] as $key => $choice ) {

            $output .= sprintf( '<label class="imgselect%1$s" title="%2$s">',
                    $key == $value ? ' selected' : '',
                    esc_attr( $choice['args']['title'] ) );

            $output .= sprintf( '<input id="%1$s" class="radio%2$s" type="radio" ' .
                    'name="%3$s" value="%1$s" %4$s /><br>',
                    esc_attr( $key ),
                    isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                    esc_attr( $name ),
                    checked( $value, $key, false ) );

            $output .= sprintf( '<img src="%1$s" title="%2$s" alt="%3$s" style="%4$s"/>',
                    esc_html( $choice['args']['value'] ),
                    esc_attr( $choice['args']['title'] ),
                    esc_attr( $choice['args']['alt'] ),
                    isset( $field['style'] ) ? 
                        esc_attr( $field['style'] ) : ''
                    );

            $output .= '</label>';
        }
            
        $output .= '</div>';
        
        if ( ! empty( $field['desc'] ) )
            $output .=sprintf( '<br /><span class="description">%s</span>',
                        esc_html( $field['desc'] ) ); 
            
        return $output;
        
    } 
    
    /**
     * Display | Render the Multicheck field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_multicheck( $field, $name, $value ) {

        $checked = isset( $value ) ? $value : '0';

        $output = '<ul class="multicheck">';   
        $i = 0;
        foreach ( $field['choices'] as $key => $choice ) {

            if( is_array ( $checked ) ) {
                $is_checked = array_key_exists($key, $checked) ? 'checked="checked"' : null;
            } else {
                $is_checked = $key == $checked ? 'checked="checked"' : null;
            }
            $output .= '<li>';

            $output .= sprintf( '<input id="%1$s%6$s" class="multicheck%2$s" type="checkbox" ' .
                    'name="%3$s[%4$s]" value="%4$s" %5$s data-key="%1$s"/>',
                    esc_attr( $field['id'] ),
                    isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                    esc_attr( $name ),
                    esc_attr ( $key ),
                    isset( $is_checked ) ? 'checked="checked"' : '',
                    $i );

            $output .= sprintf( '<label for="%1$s">%2$s</label>',
                    esc_attr( $field['id'] . $i ),
                    esc_html( $choice['value'] ) );

            $output .= '</li>';
            $i++;
        }
        $output .= '</ul>';

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
            
        return $output;
        
    } 
    
    /**
     * Display | Render the Radio field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_radio( $field, $name, $value ) {

        $output = '<ul class="radio">';   
        $i = 0;
        foreach ( $field['choices'] as $key => $choice ) {
            $output .= '<li>';

            $output .= sprintf( '<input id="%1$s%6$s" class="radio%2$s" type="radio" ' .
                    'name="%3$s" value="%4$s" %5$s />',
                    esc_attr( $field['id'] ),
                    isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                    esc_attr( $name ),
                    esc_attr ( $key ),
                    checked( $value, $key, false ),
                    $i );

            $output .= sprintf( '<label for="%1$s">%2$s</label>',
                    esc_attr( $field['id'] . $i ),
                    esc_html( $choice['value'] ) );

            $output .= '</li>';
            $i++;
        }
        $output .= '</ul>';

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
            
        return $output;
    }
    
    /**
     * Display | Render the Select field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_select( $field, $name, $value ) {

        $output = sprintf ( '<select class="select%1$s" name="%2$s">',
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ) );

        // Now load up each of the other <options> in 'choices'
        foreach ( $field['choices'] as $key => $choice ) {         

            $output .= sprintf( '<option value="%1$s" %2$s>%3$s</option>',
                    esc_attr( $key ), 
                    selected( $value, $key, false ),
                    esc_html( $choice['value'] )
                    );
        }
        $output .= '</select>';


        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );       
        
        return $output;
    }
    
    /**
     * Display | Render the Text field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_text( $field, $name, $value ) {
         
        $output = sprintf( '<input id="%1$s" class="regular-text%2$s" type="text" ' .
                'name="%3$s" value="%4$s" placeholder="%5$s" />',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),                       
                esc_attr( $value ),                        
                isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '' );

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
        
        return $output;
    }
    
    /**
     * Display | Render the Textarea field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_textarea( $field, $name, $value ) {
        
        $output = sprintf( '<textarea id="%1$s" class="textarea%2$s" ' .
                'name="%3$s" placeholder="%4$s"  rows="5" cols="30">' .
                '%5$s</textarea>',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),
                isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '',
                esc_attr( $value ) );

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
        
        return $output;
    } 
    
    
    /**
     * Display | Render the Timepicker field
     * 
     * @since 0.0.9
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_timepicker( $field, $name, $value ) {

        $output = sprintf( '<input id="%1$s" class="timepicker%2$s" type="text" ' .
                'name="%3$s" value="%4$s" placeholder="%5$s" size="10" />',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),                       
                esc_attr( $value ),                        
                isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : '' );      
        
        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );  
        
        return $output;
        
    }
    
    /**
     * Display | Render the image-uploader field
     * 
     * @since 0.0.7
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_upload_image( $field, $name, $value ) {
                
        $output = sprintf( '<input id="%1$s" class="upload-url%2$s" type="text" ' .
                'name="%3$s" value="%4$s" />',
                esc_attr( $field['id'] ),
                isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                esc_attr( $name ),                       
                esc_attr( $value ) );                        

        $output .= sprintf( '<input id="%1$s" class="upload-button button" type="button" ' .
                'name="upload_button" value="%2$s" title="%3$s" />',
                esc_attr( $field['id'] ),
                esc_attr( __( 'Upload', 'river' ) ), 
                esc_attr( $field['title'] ) );         

        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );

        $output .= sprintf ( '<div id="image-preview" style="%s">' ,
                empty( $value ) ? 'display: none;' : 'display:block' );

            // Display the image tag
            $output .= sprintf( '<img id="%1$s" class="upload-url%2$s" src="%3$s" />',
                    esc_attr( $field['id'] ),
                    isset( $field['class'] ) ? esc_attr( " {$field['class']}" ) : '',
                    esc_attr( $value ) );
            // Delete image
            $output .= '<a class="delete-image button" href="#">Remove Image</a>';

        $output .= '</div>';

        return $output;
        
    } 
 
    /**
     * Display | Render the WYSIWYG field
     * 
     * @since 0.0.9
     * 
     * @param array     $field Field's definition
     * @param string    $name Field's name name="$name"
     * @param string    $value Field's value value="$value"
     * @return string   Formatted string containing the form field with
     *                  passed parameters properly escaped
     */
    public function d_wysiwyg( $field, $name, $value ) {
        
        $field['args']['textarea_name'] = $name;
        
        $output = wp_editor( 
                    wp_kses_post( $value ), 
                    esc_attr( $name ), 
                    $field['args'] );
        
        if ( ! empty( $field['desc'] ) )
            $output .= sprintf( '<br /><span class="description">%s</span>',
                esc_html( $field['desc'] ) );        
        
        return $output;        
        
    }    
    
} // end of class

endif; // end of class_exists
