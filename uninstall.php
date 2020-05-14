<?php
/**
 * BP Signup Member Type uninstaller.
 *
 * @link https://developer.wordpress.org/plugins/the-basics/uninstall-methods/#uninstall-php
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @package WordPress\Plugin\BP_Signup_Member_Type\Uninstaller
 */

// Don't execute any uninstall code unless WordPress core requests it.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit(); }

require_once plugin_dir_path( __FILE__ ) . 'bp-signup-member-type.php';

$my_prefix = BP_Signup_Member_Type::prefix;

// Delete plugin options.
delete_option( "{$my_prefix}allow_multiple" );
delete_option( "{$my_prefix}types_at_signup" );
