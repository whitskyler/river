<?php

/**
 * River's action hooks
 *
 * These are the hooks available to the child theme, listed here to help
 * developers easily sort through functionality and extensibility.
 * 
 * @category    River 
 * @package     Framework Core
 * @subpackage  Hooks
 * @since       0.0.0
 * @author      CodeRiver Labs 
 * @license     http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link        http://coderiverlabs.com/
 */

/**
 * River Framework Initilization Hook, which executes after all hooked functions
 * are loaded.  This occurs BEFORE the child theme functions.php file is
 * executed.
 * 
 * Child Theme: use add_action( 'river_init', 'child_init_function' ) to add
 *              initialization actions, such as file loads (includes) to the
 *              framework.
 * 
 * @since 0.0.0
 */
function river_init() {
    do_action( 'river_init' );
}

/**
 * First hook prior to River loading the framework
 * 
 * @since 0.0.0
 */
function river_pre_river() {
    do_action( 'river_pre_river' );
}

/**
 * Hook fires after River constants are loaded but before the framework is
 * loaded.
 * 
 * @since 0.0.0
 */
function river_post_constants() {
    do_action( 'river_pre_framework' );
}