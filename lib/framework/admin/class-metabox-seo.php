<?php

/**
 * Admin Metabox Class
 *
 * @category    River 
 * @package     Framework Admin
 * @subpackage  Admin Metabox Class
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 * 
 */

if ( !class_exists( 'River_Metabox_SEO' ) ) :
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
class River_Metabox_SEO extends River_Admin_Metabox {
    
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
            'id'                => 'river_seo',
            'title'             => 'River SEO',
            'post_type'         => 'all',
            'context'           => 'advanced',
            'priority'          => 'default',
            'callback_args'     => '',
            'default_fields'    => array(),
        );

        $config['default_fields']['river_seo_title'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_title',
            // element's label
            'title'             => __( 'Title', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Search engines typically allow up to 60 characters.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => '',
        ); 
        
        $config['default_fields']['river_seo_description'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_description',
            // element's label
            'title'             => __( 'Description', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Search engines typically allow up to 160 characters.', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => '',
        );
        
        $config['default_fields']['river_seo_keywords'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_keywords',
            // element's label
            'title'             => __( 'Keywords', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Separate each keyword by a comma', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => '',
        ); 
        
        $config['default_fields']['river_seo_canonical'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_canonical',
            // element's label
            'title'             => __( 'Canonical URI', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Adds a <link rel="canonical" href="this field">', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => '',
        );        
        
        $config['default_fields']['river_seo_redirect'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_redirect',
            // element's label
            'title'             => __( '301 Redirect URI', 'river' ),
            // (opt) description displayed under the element
            'desc'              => __( 'Separate each keyword by a comma', 'river' ),
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'text',        
            // section these are assigned to
            'section_id'        => '',
        );
        
        $config['default_fields']['river_seo_tracking'] = array(
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_tracking',
            // element's label
            'title'             => __( 'Tracking or Conversion Code', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value
            'default'           => '',        
            // HTML field type
            'type'              => 'textarea',        
            // section these are assigned to
            'section_id'        => '',
            // (opt) Specify the sanitization filter here; else it's set
            // automatically in the Settings Sanitizer Class
            'sanitizer_filter'  => 'unfiltered_html',
            // (opt) Specify the validation filter here; else it's set
            // automatically in the Settings Sanitizer Class        
            'validator_filter'  => 'string',            
        );
        
        $config['default_fields']['river_seo_noindex'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_noindex',
            // element's label
            'title'             => __( 'Apply noindex', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => '',
        );   

        $config['default_fields']['river_seo_nofollow'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_nofollow',
            // element's label
            'title'             => __( 'Apply nofollow', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => '',
        ); 
        
        $config['default_fields']['river_seo_noarchive'] = array(        
            // settings ID for settings array & HTML element
            'id'                => 'river_seo_noarchive',
            // element's label
            'title'             => __( 'Apply noarchive', 'river' ),
            // (opt) description displayed under the element
            'desc'              => '',
            // default value MUST be integer and 0 or 1
            'default'           => 0,        
            // HTML field type
            'type'              => 'checkbox',        
            // section these are assigned to
            'section_id'        => '',
        );         
        
        $this->create( $config );
        
    }    
    
    /** Display Metabox *******************************************************/ 
    
    /**
     * Display/Render the metabox
     * 
     * We are overloading the parent's method in order to customize this metabox
     * 
     * @since 0.0.0
     * 
     * @param stdClass      $post Post object 
     * @param array         $metabox array containing id, title, callback, &
     *                      callback args (if any were configured)
     */   
    public function display_mb_fields( $post, $metabox ) {
        ?>
        <table>
            <tbody>
        <?php
        
        foreach( $this->default_fields as $field_name => $field ) {
            
            $name = $this->get_field_name( $field_name, $this->mb_id );
            if( ! isset( $name ) || is_null( $name ) )
                continue;
            
            $value = river_get_custom_field( $field_name );
            $value = is_null( $value ) ? $field['default'] : $value;
            
            if( 'river_seo_noindex' == $field_name ) { ?>
                <tr>
                    <td style="text-align: left" colspan ="2">
                        <h3><?php _e( 'Robots Meta', 'river' ); ?></h3>
                    </td>
                </tr>
            <?php } ?>
                <tr>
                    <th style="width: 25%; text-align: left;">
                        <?php echo esc_html( $field['title'] ); ?>
                    </th>
                    <td>
                        <?php
                        echo apply_filters( "river_display_field_{$this->mb_id}", 
                                $field, $name, $value );

                        if(     'river_seo_title' === $field_name || 
                                'river_seo_description' === $field_name ) {
                            $count = strlen( $value );
                            printf( '<input id="%1$s" type="text" value="%2$s"'.
                                'name="%s" maxlength="3" '.
                                'size="3" readonly="" ' .
                                 'style="width: 40px !important; text-align: center;">',
                                    esc_attr( $field_name . '_chars' ), 
                                    esc_attr( $count ) );
                            printf( ' %s', __( 'Characters', 'river' ) );
                        }

                        ?>
                    </td>                        
                </tr>
            <?php
        }
        ?>

            </tbody>
        </table>                    
        <?php
    }    
    
        
} // end of class


add_action( 'after_setup_theme', 'river_add_seo_mb' );
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
function river_add_seo_mb() {

    // Oops not viewing Admin. Just return
    if ( ! is_admin() )
        return;
  
    /**
     * Let's do some checks to ensure we should proceed
     */
    if ( ! current_theme_supports( 'river-metabox-seo' ) )
        return; // Programmatically disabled  

    $current_user = wp_get_current_user();
    if ( ! current_user_can( 'edit_posts', $current_user->ID ) )
        return false; 

    $mb = new River_Metabox_SEO();
}

endif; // end of class_exists
