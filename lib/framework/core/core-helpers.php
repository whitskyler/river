<?php

/**
 * Core Helper Functions
 *
 * @category    River 
 * @package     Framework Core
 * @subpackage  Helpers
 * @since       0.0.8
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */


/**
 * Compares the keys of two arrays
 * 
 * Works with single and n-th dimensional arrays
 * 
 * @since 0.0.0
 * 
 * @param array         $array1 First array to be compared
 * @param array         $array2 Second array to be compared
 * @return bool         returns true if the keys in both arrays match;
 *                      else returns false
 */      
function river_arrays_keys_match( $array1, $array2 ) {


    // Check $array1 to $array2
    foreach ( $array1 as $key => $value ) {

        // if the key doesn't exist in $array1, store it
        if ( !array_key_exists( $key, $array2 ) ) {
            return false;

        // if the value is an array, step into it    
        } elseif ( is_array( $value ) ) {

            // if $array2 is not array, store it.
            if ( !is_array( $array2[$key] ) ) {
                return false;

            // $array2[key] is also an array.
            // Recursive time to step into the next level   
            } else {

                // Call array_compare again & store $differences in this new array
                $recursive_arr = $this->river_arrays_keys_match( $value, $array2[$key] );

                // if there are differences (i.e. $recursive_arr is not empty),
                // store it.
                if ( !$recursive_arr ) {
                    return false;

                };
            };

        // else if $array2[key] is not equal to $array1, store it.     
        } elseif ( $array2[$key] !== $value ) {
            return false;
        };
    };

    // Now backcheck $array2 to $array1
    foreach ( $array2 as $key => $value ) {

        // if the key doesn't exist in $array1, store it
        if ( !array_key_exists( $key, $array1 ) )
             return false;

    };

    return true;
}


/**
 * Get the specified option
 * 
 * To save database requests by calling from the cache.
 * 
 * @since 0.0.0
 * 
 * @param string    $option name of the option to retrieve
 * @param bool      $use_cache (opt) TRUE: Retrieve from the cache; else
 *                  retrieve from options database
 * @return mixed    Returns the option requested either from cache or
 *                  the options database
 */

/**
 * Returns the requested option.
 * 
 * To save some time on db hits, the settings pulled from the options database
 * are cached in $options_db_cache and each option that is requested is also
 * cached.  Since these are 'static' variables, they will persist during the
 * current page request only.
 *
 * @since 0.0.0
 *
 * @staticvar array     $options_db_cache Cache of all options pulled from the db
 * @staticvar array     $options_cache Requested option cache
 * 
 * @param string        $settings_group Name of the options|settings group
 * @param string        $option_name Name of the option to retrieve
 * @param bool          $use_cache (opt) TRUE: cache results; FALSE: pull from
 *                      options database and do not cache the value
 * @param bool          $return_all (opt) for debug only
 * @return mixed        Value of the requested option
 */
function river_get_option( $settings_group, $option_name, $use_cache = true, $return_all = false ) {
/**
 * Credits:  This code is adapted from the Genesis framework.
 */    
    
    // Caller does not want to use the cache
    if ( ! $use_cache ) {
        // Pull from the options db
        $options = get_option($settings_group);
        
        // Oops either the setting option doesn't exist or options
        // are not stored in the database yet.  Return '' 
        if ( !is_array( $options ) || !array_key_exists( $option_name, $options ) )
            return '';
        
        return is_array( $options[$settings_group][$option_name] ) ? 
                stripslashes_deep( $options[$settings_group][$option_name] ) : 
                stripslashes( wp_kses_decode_entities( $optionse[$settings_group][$option_name] ) ); 
    }
    
    // Let's setup the cache
    static $options_db_cache = array();
    static $options_cache = array(); 
    
    // Allow child themes to short-circuit options.
    $pre = apply_filters( 'river_pre_option_' . $option_name, false );
    if ( false !== $pre )
            return $pre;    

    // If the options database hasn't already been cached, do it now.
    if ( ! isset( $options_db_cache[$settings_group] ) )
        $options_db_cache[$settings_group] = get_option($settings_group);

    // If the requested $option_name is not in the cache, then cache it
    if( ! isset( $options_cache[$settings_group][$option_name] ) ) {
                  
        // Oops either the setting option doesn't exist or options
        // are not stored in the database yet.  Cache '' 
        if ( ! is_array( $options_db_cache[$settings_group] ) || 
                ! array_key_exists( $option_name, (array) $options_db_cache[$settings_group] ) ) {
            $options_cache[$settings_group][$option_name] = '';
            
        // Cache the option
        } else {
            
            // Add the option to cache
            $options_cache[$settings_group][$option_name] = 
                is_array( $options_db_cache[$settings_group][$option_name] ) ? 
                stripslashes_deep( $options_db_cache[$settings_group][$option_name] ) : 
                stripslashes( wp_kses_decode_entities( $options_db_cache[$settings_group][$option_name] ) ); 
        }

    }

if( ! $return_all )
    // Return the requested value to the caller
    return $options_cache[$settings_group][$option_name];        

return $options_cache;
//return $options_db_cache;

}

/**
 * Get and return the post's custom field meta data
 * 
 * @since 0.0.8
 * 
 * @global integer      $id Post ID (inside of The Loop)
 * @global stdClass     $post WordPress Post object
 * @param string        $field_name Custom field's Name (id)
 * @return bool|string  Returns sanitized value or false
 */
function river_get_custom_field( $field_name ) {
    
    global $id, $post;

    if ( is_null( $id ) && is_null( $post ) )
        return NULL;

    $post_id = is_null( $id )? $post->ID : $id;

    $custom_field = get_post_meta( $post_id, $field_name, true );

    // Sanitize and then return the custom field's value
    if ( ! is_null( $custom_field ) ) {
        
        return is_array( $custom_field ) ? 
            stripslashes_deep( $custom_field ): 
            stripslashes( wp_kses_decode_entities( $custom_field ) );
    }

    // Return FALSE if custom field is empty
    return NULL;    
    
}

