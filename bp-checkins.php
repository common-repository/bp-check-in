<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Bp_Checkins
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs - BuddyPress Check-ins
 * Plugin URI:        https://wbcomdesigns.com/downloads/buddypress-checkins/
 * Description:       BuddyPress Check-ins allows members to share their location when posting activities; you can add places where you visited, nearby locations using Google Place API.
 * Version:           2.3.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-checkins
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Define Plugin Constants.
if ( ! defined( 'BPCHK_PLUGIN_VERSION' ) ) {
	define( 'BPCHK_PLUGIN_VERSION', '2.3.0' );
}
if ( ! defined( 'BPCHK_PLUGIN_PATH' ) ) {
	define( 'BPCHK_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BPCHK_PLUGIN_URL' ) ) {
	define( 'BPCHK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'BP_CHECKINS_PLUGIN_BASENAME' ) ) {
	define( 'BP_CHECKINS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'BPCHK_TEXT_DOMAIN' ) ) {
	define( 'BPCHK_TEXT_DOMAIN', 'bp-checkins' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-checkins-activator.php
 */
function activate_bp_checkins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins-activator.php';
	Bp_Checkins_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-checkins-deactivator.php
 */
function deactivate_bp_checkins() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins-deactivator.php';
	Bp_Checkins_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_checkins' );
register_deactivation_hook( __FILE__, 'deactivate_bp_checkins' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bp-checkins.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_checkins() {
	$plugin = new Bp_Checkins();
	$plugin->run();
}

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires BuddyPress to be installed and active
 */
add_action( 'bp_loaded', 'bpchk_plugin_init' );

/**
 * Check plugin requirement on plugins loaded,this plugin requires BuddyPress to be installed and active.
 *
 * @since    1.0.0
 */
function bpchk_plugin_init() {
	if ( bp_checkins_check_config() ) {
		run_bp_checkins();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bpchk_plugin_links' );
	}
}

function bp_checkins_check_config() {
	global $bp;

	$config = array(
		'blog_status'    => false,
		'network_active' => false,
		'network_status' => true,
	);
	if ( get_current_blog_id() === bp_get_root_blog_id() ) {
		$config['blog_status'] = true;
	}

	$network_plugins = get_site_option( 'active_sitewide_plugins', array() );

	// No Network plugins
	if ( empty( $network_plugins ) ) {

		// Looking for BuddyPress and bp-activity plugin.
		$check[] = $bp->basename;
	}
	$check[] = BP_CHECKINS_PLUGIN_BASENAME;

	// Are they active on the network ?
	$network_active = array_diff( $check, array_keys( $network_plugins ) );

	// If result is 1, your plugin is network activated
	// and not BuddyPress or vice & versa. Config is not ok.
	if ( count( $network_active ) == 1 ) {
		$config['network_status'] = false;
	}

	// We need to know if the plugin is network activated to choose the right
	// notice ( admin or network_admin ) to display the warning message.
	$config['network_active'] = isset( $network_plugins[ BP_CHECKINS_PLUGIN_BASENAME ] );

	// if BuddyPress config is different than bp-activity plugin.
	if ( ! $config['blog_status'] || ! $config['network_status'] ) {

		$warnings = array();
		if ( ! bp_core_do_network_admin() && ! $config['blog_status'] ) {
			add_action( 'admin_notices', 'bpcheckins_same_blog' );
			$warnings[] = __( 'BuddyPress Check-ins requires to be activated on the blog where BuddyPress is activated.', 'bp-checkins' );
		}

		if ( bp_core_do_network_admin() && ! $config['network_status'] ) {
			add_action( 'admin_notices', 'bpcheckins_same_network_config' );
			$warnings[] = __( 'BuddyPress Check-ins and BuddyPress need to share the same network configuration.', 'bp-checkins' );
		}
		$bp_active_components = bp_get_option( 'bp-active-components' );
		if ( ! array_key_exists( 'activity', $bp_active_components ) ) {
			add_action( $config['network_active'] ? 'network_admin_notices' : 'admin_notices', 'bpchk_plugin_require_activity_component_admin_notice' );
			$warnings[] = __( 'Activity component required.', 'bp-checkins' );
		}
		if ( ! empty( $warnings ) ) :
			return false;
		endif;
	}
	return true;
}
function bpcheckins_same_blog() {
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Check-ins requires to be activated on the blog where BuddyPress is activated.', 'bp-checkins' ) )
	. '</p></div>';
}

function bpcheckins_same_network_config() {
	echo '<div class="error"><p>'
	. esc_html( __( 'BuddyPress Check-ins and BuddyPress need to share the same network configuration.', 'bp-checkins' ) )
	. '</p></div>';
}

/**
 * Function to through notice when buddypress activity component is not activated.
 *
 * @since    1.0.0
 */
function bpchk_plugin_require_activity_component_admin_notice() {
	$bpchk_plugin = 'BuddyPress Checkin';
	$bp_component = 'BuddyPress\'s Activity Component';

	echo '<div class="error"><p>'
	/* translators: 1: Name of the plugin 2: Name of the dependent plugin */
	. sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be active.', 'bp-checkins' ), '<strong>' . esc_attr( $bpchk_plugin ) . '</strong>', '<strong>' . esc_attr( $bp_component ) . '</strong>' )
	. '</p></div>';
}

/**
 * Function to set plugin actions links.
 *
 * @param    array $links    Plugin settings link array.
 * @since    1.0.0
 */
function bpchk_plugin_links( $links ) {
	$bpchk_links = array(
		'<a href="' . admin_url( 'admin.php?page=bp-checkins' ) . '">' . __( 'Settings', 'bp-checkins' ) . '</a>',
		'<a href="https://wbcomdesigns.com/contact/" target="_blank">' . __( 'Support', 'bp-checkins' ) . '</a>',
	);
	return array_merge( $links, $bpchk_links );
}

/**
 *  Check if buddypress activate.
 */
function bpchk_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) || class_exists( 'Bp_Checkins_Pro' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'bpchk_required_plugin_admin_notice' );
	}
}

add_action( 'admin_init', 'bpchk_requires_buddypress' );
/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  1.1.0
 */
function bpchk_required_plugin_admin_notice() {
	$bpquotes_plugin = esc_html__( 'BuddyPress Check-ins', 'bp-checkins' );
	if ( class_exists( 'Bp_Checkins_Pro' ) ) {

		$checkin_pro_plugin = esc_html__( 'BuddyPress Check-ins Pro', 'bp-checkins' );
		echo '<div class="error"><p>';

		printf(
			/* translators: 1: Name of the plugin 2: Name of the dependent plugin */
			esc_html__( 'Deactivate %1$s to use %2$s plugin.', 'bp-checkins' ),
			'<strong>' . esc_html( $checkin_pro_plugin ) . '</strong>',
			'<strong>' . esc_html( $bpquotes_plugin ) . '</strong>'
		);
		echo '</p></div>';
	} else {
		$bp_plugin = esc_html__( 'BuddyPress', 'bp-checkins' );
		echo '<div class="error"><p>';

		printf(
			/* translators: 1: Name of the plugin 2: Name of the dependent plugin */
			esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'bp-checkins' ),
			'<strong>' . esc_html( $bpquotes_plugin ) . '</strong>',
			'<strong>' . esc_html( $bp_plugin ) . '</strong>'
		);
		echo '</p></div>';
	}
}


/**
 * Load the plugin text domain for translation.
 *
 * @since    1.6.1
 */
function bpchk_load_plugin_textdomain() {

	load_plugin_textdomain(
		'bp-checkins',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);
}
add_action( 'plugins_loaded', 'bpchk_load_plugin_textdomain' );


/**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'bpchk_activation_redirect_settings' );
function bpchk_activation_redirect_settings( $plugin ) {
	if ( class_exists( 'BuddyPress' ) && ! class_exists( 'Bp_Checkins_Pro' ) ) {		
		if ( $plugin === plugin_basename( __FILE__ ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] === plugin_basename( __FILE__ ) ) { //phpcs:ignore		
			wp_redirect( admin_url( 'admin.php?page=bp-checkins' ) );
			exit;
		}
	}
	if ( $plugin == $_REQUEST['plugin'] && class_exists( 'Buddypress' ) ) { //phpcs:ignore
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate-plugin' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) { //phpcs:ignore		
			set_transient( '_bpchk_is_new_install', true, 30 );
		}
	}
}

/**
 * Bpchk_do_activation_redirect
 *
 * @return void
 */
function bpchk_do_activation_redirect() {
	if ( get_transient( '_bpchk_is_new_install' ) ) {
		delete_transient( '_bpchk_is_new_install' );
		wp_safe_redirect( admin_url( 'admin.php?page=bp-checkins' ) );

	}
}
add_action( 'admin_init', 'bpchk_do_activation_redirect' );
