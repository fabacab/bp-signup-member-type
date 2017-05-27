<?php
/**
 * The BP Signup Member Type plugin.
 *
 * WordPress plugin header information:
 *
 * * Plugin Name: BP Signup Member Type
 * * Plugin URI: https://github.com/meitar/bp-signup-member-type
 * * Description: Automatically assign a BuddyPress member type during registration.
 * * Version: 0.1
 * * Author: Meitar Moscovitz <meitarm+wordpress@gmail.com>
 * * Author URI: https://maymay.net/
 * * License: GPL-3.0
 * * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * * Text Domain: bp-signup-member-type
 * * Domain Path: /languages
 *
 * @link https://developer.wordpress.org/plugins/the-basics/header-requirements/
 *
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @copyright Copyright (c) 2017 by Maymay
 *
 * @package WordPress\Plugin\BP_Signup_Member_Type
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Disallow direct HTTP access.

/**
 * Base class that WordPress uses to register and initialize plugin.
 */
class BP_Signup_Member_Type {

    /**
     * String to prefix option names, settings, etc. in shared spaces.
     *
     * Some WordPress data storage areas are basically one globally
     * shared namespace. For example, names of options saved in WP's
     * options table must be globally unique. When saving data in any
     * such shared space, we need to prefix the name we use.
     *
     * @var string
     */
    const prefix = 'bp_smt_';

    /**
     * Entry point for the WordPress framework into plugin code.
     *
     * This is the method called when WordPress loads the plugin file.
     * It is responsible for "registering" the plugin's main functions
     * with the {@see https://codex.wordpress.org/Plugin_API WordPress Plugin API}.
     *
     * @uses add_action()
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     *
     * @return void
     */
    public static function register () {
        add_action( 'bp_include', array( __CLASS__, 'bp_include' ) );
        add_action( 'bp_init', array( __CLASS__, 'initialize' ) );

        add_action( 'plugins_loaded', array( __CLASS__, 'registerL10n' ) );

        register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
        register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivate' ) );
    }

    /**
     * Loads localization files from plugin's languages directory.
     *
     * @uses load_plugin_textdomain()
     *
     * @return void
     */
    public static function registerL10n () {
        load_plugin_textdomain( 'bp-signup-member-type', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Registers primary functionality, initializing plugin hooks.
     */
    public static function initialize () {
        if ( ! empty( bp_get_member_types() ) && bp_get_option( 'users_can_register' ) ) {
            add_action( 'bp_register_admin_settings', array( __CLASS__, 'bp_register_admin_settings' ) );
            add_action( 'bp_before_registration_submit_buttons', array( __CLASS__, 'bp_before_registration_submit_buttons' ) );
            add_action( 'bp_complete_signup', array( __CLASS__, 'bp_complete_signup' ) );
        }
    }

    /**
     * Method to run when the plugin is activated by a user in the
     * WordPress Dashboard admin screen.
     *
     * @uses self::checkPrereqs()
     *
     * @return void
     */
    public static function activate () {
        self::checkPrereqs();
    }

    /**
     * Checks system requirements and exits if they are not met.
     *
     * This first checks to ensure minimum WordPress and PHP versions
     * have been satisfied. If not, the plugin deactivates and exits.
     *
     * @global $wp_version
     *
     * @uses $wp_version
     * @uses self::get_minimum_required_versions()
     * @uses deactivate_plugins()
     * @uses plugin_basename()
     * @uses buddypress()
     *
     * @return void
     */
    public static function checkPrereqs () {
        global $wp_version;
        $v = self::get_minimum_required_versions();
        if ( version_compare( $v['wp_version'], $wp_version ) > 0 ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( sprintf(
                __( 'BP Signup Member Type requires at least WordPress version %1$s. You have WordPress version %2$s.', 'bp-signup-member-type' ),
                $v['wp_version'], $wp_version
            ) );
        }

        if ( ! function_exists( 'buddypress' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( sprintf(
                __( 'BP Signup Member Type at least BuddyPress version %1$s. You do not have BuddyPress installed.', 'bp-signup-member-type' ),
                $v['bp_version']
            ) );
        } else {
            if ( version_compare( $v['bp_version'], buddypress()->version ) > 0 ) {
                deactivate_plugins( plugin_basename( __FILE__ ) );
                wp_die( sprintf(
                    __( 'BP Signup Member Type requires at least BuddyPress version %1$s. You have BuddyPress version %2$s.', 'bp-signup-member-type' ),
                    $v['bp_version'], buddypress()->version
                ) );
            }
        }
    }

    /**
     * Returns the "Requires at least" value from plugin's readme.txt.
     *
     * @link https://wordpress.org/plugins/about/readme.txt WordPress readme.txt standard
     *
     * @return string|array Empty if no versions found, a single string of the version if found, or array with keyed versions.
     */
    public static function get_minimum_required_versions () {
        $lines = @file( plugin_dir_path( __FILE__ ) . 'readme.txt' );
        foreach ( $lines as $line ) {
            preg_match( '/^Requires at least: (?:WordPress|WP)?\s*([0-9.]+)(?:\s*\/\s*(?:BuddyPress|BP)?\s*([0-9.]+))?$/i', $line, $m );
            if ( $m && isset( $m[1] ) && isset( $m[2] ) ) {
                return array(
                    'wp_version' => $m[1],
                    'bp_version' => $m[2]
                );
            } else if ( $m && isset( $m[1] ) ) {
                return $m[1];
            }
        }
    }

    /**
     * Method to run when the plugin is deactivated by a user in the
     * WordPress Dashboard admin screen.
     *
     * @return void
     */
    public static function deactivate () {
        // TODO
    }

    /**
     * Loads BuddyPress-specific codebase.
     *
     * @see https://codex.buddypress.org/plugindev/checking-buddypress-is-active/
     */
    public static function bp_include () {
    }

    /**
     * Registers plugin settings into the BuddyPress settings section.
     */
    public static function bp_register_admin_settings () {
        add_settings_field(
            self::prefix . 'types_at_signup',
            esc_html__( 'Member types at registration', 'bp-signup-member-type' ),
            array( __CLASS__, 'renderTypesAtSignupSetting' ),
            'buddypress',
            'bp_main'
        );

        add_settings_field(
            self::prefix . 'allow_multiple',
            esc_html__( 'Multiple member type selection', 'bp-signup-member-type' ),
            array( __CLASS__, 'renderAllowMultipleSetting' ),
            'buddypress',
            'bp_main'
        );
    }

    /**
     * Prints the HTML for the "Types at Registration" setting.
     */
    public static function renderTypesAtSignupSetting () {
        $types = bp_get_member_types( array(), 'objects' );
        $allow = (array) bp_get_option( self::prefix . 'types_at_signup' );
        print '<p class="description">' .
            esc_html__( 'Choose the BuddyPress Member Type(s) you want to allow visitors to register as.', 'bp-signup-member-type' ) .
            '</p>';
        print '<ul>';
        foreach ( $types as $k => $obj ) {
?>
<li>
    <input type="checkbox"
        id="<?php print self::prefix . 'types_at_signup[' . esc_attr__( $k ) . ']'; ?>"
        name="<?php print self::prefix . 'types_at_signup[' . esc_attr__( $k ) . ']'; ?>"
        value="1"
        <?php checked( true, array_key_exists( $k, $allow ) ); ?>
    />
    <label for="<?php print self::prefix . 'types_at_signup[' . esc_attr__( $k ) . ']'; ?>">
        <?php esc_html_e( $obj->labels['name'] ); ?>
    </label>
</li>
<?php
        }
        print '</ul>';
    }

    /**
     * Prints the HTML for the "Allow multiple types" setting.
     */
    public static function renderAllowMultipleSetting () {
?>
<input type="checkbox"
    id="<?php print self::prefix . 'allow_multiple'; ?>"
    name="<?php print self::prefix . 'allow_multiple'; ?>"
    value="1"
    <?php checked( true, bp_get_option( self::prefix . 'allow_multiple' ) ); ?>
/>
<label for="<?php print self::prefix . 'allow_multiple'; ?>">
    <?php esc_html_e( 'Allow multiple member types at registration', 'bp-signup-member-type' ); ?>
</label>
<?php
    }


    /**
     * Prints the HTML for the BuddyPress registration form.
     */
    public static function bp_before_registration_submit_buttons () {
        $allowed_types = self::allowedTypes( 'objects' );
        $count = bp_get_option( self::prefix . 'allow_multiple' );
        // TODO: Do proper templating stuff here.
        print '<div>';
        print '<h2>';
        print esc_html( _n( 'Member Type', 'Member Types', $count + 1, 'bp-signup-member-types') );
        print '</h2>';
        print '<ul>';
        if ( $count ) {
            print self::renderSignupCheckboxListItems( $allowed_types );
        } else {
            print self::renderSignupRadioButtonListItems( $allowed_types );
        }
        print '</ul>';
        print '</div>';
    }

    /**
     * Renders the given member types in checkboxes
     *
     * @param array $member_types
     */
    private static function renderSignupCheckboxListItems ( $member_types ) {
        foreach ( $member_types as $type ) {
?>
<li>
    <input type="checkbox"
        id="<?php print esc_attr( self::prefix . 'member_type[' . $type->name . ']' ); ?>"
        name="<?php print esc_attr( self::prefix . 'member_type[' . $type->name . ']' ); ?>"
        value="<?php print esc_attr( $type->name ); ?>"
    >
    <label style="display:inline;" for="<?php print esc_attr( self::prefix . 'member_type[' . $type->name . ']' ); ?>">
        <?php esc_html_e( $type->labels['singular_name'] ); ?>
    </label>
</li>
<?php
        }
    }

    /**
     * Renders the given member types in radio buttons.
     *
     * @param array $member_types
     */
    private static function renderSignupRadioButtonListItems ( $member_types ) {
        foreach ( $member_types as $type ) {
?>
<li>
    <input type="radio"
        id="<?php print esc_attr( self::prefix . 'member_type[' . $type->name . ']' ); ?>"
        name="<?php print esc_attr( self::prefix . 'member_type' ); ?>"
        value="<?php print esc_attr( $type->name ); ?>"
    >
    <label style="display:inline;" for="<?php print esc_attr( self::prefix . 'member_type[' . $type->name . ']' ); ?>">
        <?php esc_html_e( $type->labels['singular_name'] ); ?>
    </label>
</li>
<?php
        }
    }

    /**
     * Gets the registered member types permitted during registration.
     * 
     * @param string $output Optional. The type of output to return. Accepts 'names'
     *                       or 'objects'. Default 'names'.
     *
     * @uses bp_get_option()
     * @uses bp_get_member_types()
     *
     * @return array A list of member types allowed at registration.
     */
    public static function allowedTypes ( $output = 'names' ) {
        $allow = bp_get_option( self::prefix . 'types_at_signup' );
        $types = bp_get_member_types( array(), $output );
        return array_intersect_key( $types, $allow );
    }

    /**
     * Processes the signup form's member types setting.
     */
    public static function bp_complete_signup () {
        if ( ! isset( $_POST['bp_smt_member_type'] ) ) {
            return;
        }

        $signup_types = array();
        $allowed_types = self::allowedTypes();
        $allow_multiple = bp_get_option( self::prefix . 'allow_multiple' );
        if ( $allow_multiple && is_array( $_POST[ self::prefix . 'member_type' ] ) ) {
            foreach ( $_POST[ self::prefix . 'member_type' ] as $t ) {
                $signup_types[] = $t;
            }
        } else if ( ! $allow_multiple && is_string( $_POST[ self::prefix . 'member_type' ] ) ) {
            $signup_types[] = $_POST[ self::prefix . 'member_type' ];
        }
        $member_types = array_intersect( $signup_types, $allowed_types );

        $wp_user = get_user_by( 'email', $_POST['signup_email'] );

        foreach ( $member_types as $t ) {
            bp_set_member_type( $wp_user->ID, $t, true );
        }
    }

}

BP_Signup_Member_Type::register();
