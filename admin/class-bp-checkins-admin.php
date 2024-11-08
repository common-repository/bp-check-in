<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
if ( ! class_exists( 'Bp_Checkins_Admin' ) ) :
	class Bp_Checkins_Admin {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Plugin_settings_tabs
		 * 
		 * @since    1.0.0
		 * @access   public
		 * @var mixed
		 */
		public $plugin_settings_tabs;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;
			$this->bpchk_save_general_settings();

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			$current_screen = get_current_screen();			
			if ( isset( $current_screen->id ) && in_array( $current_screen->id, array( 'toplevel_page_wbcomplugins' , 'wb-plugins_page_bp-checkins' ) ) ) {			
				wp_enqueue_style( $this->plugin_name . '-font-awesome', BPCHK_PLUGIN_URL . 'public/css/font-awesome.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bp-checkins-admin.css', array(), $this->version, 'all' );
			}

		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			if ( strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), 'bp-checkins' ) !== false ) {
				wp_enqueue_script( $this->plugin_name . '-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-admin.js', array( 'jquery' ), $this->version, false );

				wp_localize_script(
					$this->plugin_name,
					'bpchk_admin_js_obj',
					array(
						'ajaxurl'    => admin_url( 'admin-ajax.php' ),
						'ajax_nonce' => wp_create_nonce( 'bp-checkin-nonce' ),
					)
				);
			}

		}

		/**
		 * Register a menu page to handle checkins settings
		 *
		 * @since    1.0.0
		 */
		public function bpchk_add_menu_page() {
			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {

				add_menu_page( esc_html__( 'WB Plugins', 'bp-checkins' ), esc_html__( 'WB Plugins', 'bp-checkins' ), 'manage_options', 'wbcomplugins', array( $this, 'bpchk_admin_settings_page' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'bp-checkins' ), esc_html__( 'General', 'bp-checkins' ), 'manage_options', 'wbcomplugins' );
			}
			add_submenu_page( 'wbcomplugins', esc_html__( 'BuddyPress Check-ins Settings Page', 'bp-checkins' ), esc_html__( 'Check-ins', 'bp-checkins' ), 'manage_options', 'bp-checkins', array( $this, 'bpchk_admin_settings_page' ) );
		}

		/**
		 * Actions performed to create a submenu page content
		 */
		public function bpchk_admin_settings_page() {
			global $allowedposttags;
			$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'bpchk-welcome';
			?>
			<div class="wrap">
				<div class="wbcom-bb-plugins-offer-wrapper">
					<div id="wb_admin_logo">
						<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/?utm_source=pluginoffernotice&utm_medium=community_banner" target="_blank">
							<img src="<?php echo esc_url( BPCHK_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
						</a>
					</div>
				</div>
				<div class="wbcom-wrap">
					<div class="blpro-header">
						<div class="wbcom_admin_header-wrapper">
							<div id="wb_admin_plugin_name">
								<?php esc_html_e( 'BuddyPress Check-ins', 'bp-checkins' ); ?>
								<span><?php /* translators: %s: */
								printf( esc_html__( 'Version %s', 'bp-checkins' ), esc_attr( BPCHK_PLUGIN_VERSION ) );
								?></span>
							</div>
							<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
						</div>
					</div>
					<div class="wbcom-admin-settings-page">
						<?php $this->bpchk_plugin_settings_tabs(); ?>
						<div class="wbcom-tab-content">
							<form method="POST" action="">
								<?php
								settings_errors();
								if ( filter_input( INPUT_POST, 'bpchk-submit-general-settings' ) !== null ) {
									$success_msg  = "<div class='setting-error-settings_updated' id='button.notice-dismiss:before'>";
									$success_msg .= '<p>' . __( '<strong>Settings Saved.</strong>', 'bp-checkins' ) . '</p>';
									$success_msg .= '</div>';
									echo wp_kses( $success_msg, $allowedposttags );
								}

								settings_fields( $tab );
								?>
								<?php do_settings_sections( $tab ); ?>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Actions performed to create tabs on the sub menu page
		 */
		public function bpchk_plugin_settings_tabs() {
			$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'bpchk-welcome';
			echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
				echo '<li class="' . esc_attr( $tab_key ) . '"><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=' . esc_attr( $this->plugin_name ) . '&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
			}
			echo '</div></ul></div>';
		}

		/**
		 * General Tab.
		 */
		public function bpchk_plugin_settings() {
			// General settings tab.
			$this->plugin_settings_tabs['bpchk-welcome'] = __( 'Welcome', 'bp-checkins' );
			add_settings_section( 'bpchk-welcome', ' ', array( &$this, 'bpchk_welcome_content' ), 'bpchk-welcome' );

			// General settings tab.
			$this->plugin_settings_tabs['bp-checkins'] = __( 'General', 'bp-checkins' );
			register_setting( 'bp-checkins', 'bp-checkins' );
			add_settings_section( 'bp-checkins-section', ' ', array( &$this, 'bpchk_general_settings_content' ), 'bp-checkins' );

			// Setup Map Tab.
			$this->plugin_settings_tabs['map-settings'] = __( 'Map Settings (PRO)', 'bp-checkins' );
			add_settings_section( 'map-settings-section', ' ', array( $this, 'bpchk_map_settings_content' ), 'map-settings' );

			// Setup xProfile Location Tab.
			$this->plugin_settings_tabs['xprofile-location'] = __( 'xProfile Location (PRO)', 'bp-checkins' );
			add_settings_section( 'xprofile-location', ' ', array( $this, 'bpchk_xprofile_options_content' ), 'xprofile-location' );

			// Setup Group Location Tab.
			$this->plugin_settings_tabs['group-location'] = __( 'Group Location (PRO)', 'bp-checkins' );
			add_settings_section( 'group-location', ' ', array( $this, 'bpchk_groups_settings_content' ), 'group-location' );

			// Members directory settings tab.
			$this->plugin_settings_tabs['members-settings'] = __( 'Members Mashup (PRO)', 'bp-checkins' );
			add_settings_section( 'members-settings-section', ' ', array( $this, 'bpchk_members_dir_settings_content' ), 'members-settings' );

			// Groups directory settings tab.
			$this->plugin_settings_tabs['groups-settings'] = __( 'BP Groups Directory (PRO)', 'bp-checkins' );
			add_settings_section( 'groups-settings-section', ' ', array( $this, 'group_directory_settings_content' ), 'groups-settings' );

			// Support tab.
			$this->plugin_settings_tabs['bpchk-support'] = __( 'Support', 'bp-checkins' );
			register_setting( 'bpchk-support', 'bpchk-support' );
			add_settings_section( 'bpchk-support-section', ' ', array( &$this, 'bpchk_support_settings_content' ), 'bpchk-support' );
		}

		public function bpchk_welcome_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-welcome-page.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-welcome-page.php';
			}
		}
		/**
		 * General Tab Content
		 */
		public function bpchk_general_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-general-settings.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-general-settings.php';
			}
		}

		/**
		 * Map settings Tab Content
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function bpchk_map_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-admin-map.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-admin-map.php';
			}
		}

		/**
		 * XProfile Setup Tab Content.
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function bpchk_xprofile_options_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-admin-xprofile.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-admin-xprofile.php';
			}
		}

		/**
		 * XProfile Setup Tab Content.
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function bpchk_groups_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-group-location-settings.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-group-location-settings.php';
			}
		}

		/**
		 * Advanced Tab Content
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function bpchk_members_dir_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-admin-members-dir.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-admin-members-dir.php';
			}
		}

		/**
		 * Advanced Tab Content.
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function group_directory_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-admin-groups-dir.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-admin-groups-dir.php';
			}
		}

		/**
		 * Support Tab Content
		 */
		public function bpchk_support_settings_content() {
			if ( file_exists( dirname( __FILE__ ) . '/includes/bp-checkins-support-settings.php' ) ) {
				require_once dirname( __FILE__ ) . '/includes/bp-checkins-support-settings.php';
			}
		}

		/**
		 * Save Plugin General Settings
		 */
		public function bpchk_save_general_settings() {
			if ( ! isset( $_POST['bpci_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpci_nonce'] ) ), 'bpci_option_nonce' ) ) {
				return false;
			}			
			if ( filter_input( INPUT_POST, 'bpchk-submit-general-settings' ) !== null ) {
				$checkin_by = '';
				if ( filter_input( INPUT_POST, 'bpchk-checkin-by' ) !== null ) {
					$checkin_by = isset( $_POST['bpchk-checkin-by'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-checkin-by'] ) ) : '';
				}

				$admin_settings = array(
					'apikey'                => isset( $_POST['bpchk-api-key'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-api-key'] ) ) : '',
					'checkin_by'            => $checkin_by,
					'range'                 => isset( $_POST['bpchk-google-places-range'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-google-places-range'] ) ) : '',					
					'placetypes'            => ( ! empty( $_POST['bpchk-google-place-types'] ) ) ? map_deep( wp_unslash( $_POST['bpchk-google-place-types'] ), 'sanitize_text_field' ) : array(),
					'tab_visibility'        => isset( $_POST['bpchk-tab-visibilty'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-tab-visibilty'] ) ) : '',
					'tab_name'              => isset( $_POST['bpchk-tab-name'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-tab-name'] ) ) : '',
					'enable_location_field' => isset( $_POST['bpchk-enable-xprofile-filed'] ) ? sanitize_text_field( wp_unslash( $_POST['bpchk-enable-xprofile-filed'] ) ) : '',
				);
				bp_update_option( 'bpchk_general_settings', $admin_settings );

			}
		}

		/**
		 * Ajax served to delete the group type
		 */
		public function bpchk_verify_apikey() {
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'bp-checkin-nonce' ) ) {
				exit();
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				exit();
			}			
			if ( isset( $_POST['action'] )  && 'bpchk_verify_apikey' == $_POST['action'] ) {				
				$apikey    = isset( $_POST['apikey'] ) ? sanitize_text_field( wp_unslash( $_POST['apikey'] ) ) : '';
				$latitude  = isset( $_POST['latitude'] ) ? sanitize_text_field( wp_unslash( $_POST['latitude'] ) ) : '';
				$longitude = isset( $_POST['longitude'] ) ? sanitize_text_field( wp_unslash( $_POST['longitude'] ) ) : '';
				$radius    = 10000;
				
				$response        = Bp_Checkins::bpchk_fetch_google_places( $apikey, $latitude, $longitude, $radius );
				$code            = wp_remote_retrieve_response_code( $response );
				$response_body   = wp_remote_retrieve_body( $response );
				$response_status = json_decode( $response_body, true );
				if ( 200 !== $code ) {
					$message = 'not-verified';
					bp_update_option( 'bpchk_apikey_verified', 'no' );
				} elseif ( 'REQUEST_DENIED' === $response_status['status'] ) {
					$message = 'not-verified';
					bp_update_option( 'bpchk_apikey_verified', 'no' );
				} else {
					$message = 'verified';
					bp_update_option( 'bpchk_apikey_verified', 'yes' );
				}

				$response = array( 'message' => $message );
				wp_send_json_success( $response );
				die;
			}
		}

		/**
		 * This function will list the checkin link in the dropdown list.
		 *
		 * @param    array $wp_admin_nav    BuddyPress Check-ins nav array.
		 */
		public function bpchk_setup_admin_bar_links( $wp_admin_nav = array() ) {
			global $wp_admin_bar, $bp_checkins;

			$checkin_tab_name   = isset( $bp_checkins->tab_name ) ? $bp_checkins->tab_name : '';
			$profile_menu_slug  = apply_filters( 'bpchk_member_profile_checkin_tab_slug', sanitize_title( $checkin_tab_name ) );
			$profile_menu_title = apply_filters( 'bpchk_member_profile_checkin_tab_name', esc_html( $checkin_tab_name ) );

			$base_url = bp_loggedin_user_domain() . $profile_menu_slug;
			if ( is_user_logged_in() ) {
				$wp_admin_bar->add_menu(
					array(
						'parent' => 'my-account-buddypress',
						'id'     => 'my-account-' . $profile_menu_slug,
						'title'  => $profile_menu_title,
						'href'   => trailingslashit( $base_url ),
					)
				);
			}
		}

		/**
		 * Add check in activity stting in youzr settings.
		 *
		 * @param array $post_types
		 * @return array $post_types
		 */
		public function bp_checkin_add_yozer_activity_setting( $post_types ) {
			$post_types['activity_bpchk_chkins'] = __( 'Activity Check-Ins', 'bp-checkins' );

			return $post_types;
		}

		/**
		 * Hide all notices from the setting page.
		 *
		 * @return void
		 */
		public function bpchk_hide_all_admin_notices_from_setting_page() {
			$wbcom_pages_array  = array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'bp-checkins' );
			$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

			if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
				remove_action( 'admin_notices', 'bpcheckins_same_blog' );
				remove_action( 'admin_notices', 'bpcheckins_same_network_config' );
				remove_action( 'admin_notices', 'bpchk_required_plugin_admin_notice' );
				remove_action( 'admin_notices', 'display_admin_notice' );				
			}

		}

	}
endif;
