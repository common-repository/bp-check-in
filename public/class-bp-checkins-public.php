<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Checkins
 * @subpackage Bp_Checkins/public
 **/

if ( ! class_exists( 'Bp_Checkins_Public' ) ) :

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Bp_Checkins
	 * @subpackage Bp_Checkins/public
	 * @author     Wbcom Designs <admin@wbcomdesigns.com>
	 */
	class Bp_Checkins_Public {

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
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      array    $bp_checkins    The plugin settings.
		 */
		public $bp_checkins;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of the plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			global $bp_checkins;
			$this->plugin_name = $plugin_name;
			$this->version     = $version;
			$this->bp_checkins = &$bp_checkins;
		}

		/**
		 * Render location pickup html to buddypress what's new section.
		 *
		 * @since    1.0.7
		 */
		public function render_location_pickup_html() {
			global $bp_checkins;
			global $allowedposttags;
			$checkin_html = '';
			if ( is_user_logged_in() ) {

				if ( ! self::bp_checkins_is_youzer_activity() ) {
					return;
				}

				// Create the checkin html.
				if ( $bp_checkins->apikey ) {
					$checkin_html .= '<div class="post-elements-buttons-item bpchk-marker-container"><div class="checkins-icon bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="' . esc_attr__( 'Add a place', 'bp-checkins' ) . '"><i class="wb-icons wb-icon-map-pin bpchk-allow-checkin"></i></div></div>';
				}
			}
			echo wp_kses_post( $checkin_html );
		}

		/**
		 * Render location pickup map panle html.
		 *
		 * @since    1.9.2
		 */
		public function render_location_map_panle_html() {
			global $bp_checkins;
			global $allowedposttags;
			$checkin_html = '';
			if ( is_user_logged_in() ) {

				if ( ! self::bp_checkins_is_youzer_activity() ) {
					return;
				}

				// Create the checkin html.
				if ( $bp_checkins->apikey ) {
					$add_cat_nonce = wp_create_nonce( 'bpchk-checkins-activity-post' );
					$checkin_html .= '<div class="bp-checkins bp-checkin-panel">';

					$checkin_html     .= '<div class="checkin-by-autocomplete">';
						$checkin_html .= '<input type="text" id="bpchk-autocomplete-place" name="bp-checkin-address" placeholder="' . __( 'Start typing your location...', 'bp-checkins' ) . '" />';
						$checkin_html .= '<input type="hidden" name="bp-checkin-lat" id="bpchk-checkin-place-lat" />';
						$checkin_html .= '<input type="hidden" name="bp-checkin-lng" id="bpchk-checkin-place-lng" />';
						$checkin_html .= '<input type="hidden" name="bpchk-checkins-activity-nonce" value="' . esc_attr( $add_cat_nonce ) . '">';
						$checkin_html .= '<input type="checkbox" name="bp-checkin-add-as-place" value="yes" id="bpchk-add-as-place" checked />';
						$checkin_html .= '<label for="bpchk-add-as-place">' . __( 'Add this location to Check-ins tab', 'bp-checkins' ) . '</label>';						
					$checkin_html     .= '</div>';
					$checkin_html     .= '<div class="checkin-by-autocomplete-map" id="checkin-by-autocomplete-map"></div>';										
					$checkin_html     .= '<div class="clear"></div>';

					$checkin_html .= '</div>';
				}
			}
			 $allowed_tags = array(
				'div'    => array(
					'class' => array(),
					'id'    => array(),
				),
				'input'  => array(
					'type'        => array(),
					'id'          => array(),
					'name'        => array(),
					'placeholder' => array(),
					'value'       => array(),
					'checked'     => array(),
				),
			);
			echo wp_kses( $checkin_html, $allowed_tags );
		}


		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			global $bp_checkins, $post;

			$current_component = '';
			if ( isset( $post->ID ) && $post->ID != '' && $post->ID != '0' ) {
				$_elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
				if ( $_elementor_data != '' && str_contains($_elementor_data, 'bp_newsfeed_element_widget') || str_contains($_elementor_data, 'buddypress_shortcode_activity_widget') || str_contains($_elementor_data, 'bbp-activity')) {
					$current_component = 'activity';
				}
			}

			$checkin_tab_slug = isset( $bp_checkins->tab_name ) ? $bp_checkins->tab_name : 'checkin';
			$checkin_tab_slug = apply_filters( 'bpchk_member_profile_checkin_tab_slug', sanitize_title( $checkin_tab_slug ) );
			if ( bp_is_groups_component()
					|| bp_is_activity_component()
					|| bp_is_profile_component()
					|| strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), $checkin_tab_slug )
					|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) )
					|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bppfa_postform' ) ) )
					|| ( is_single() && get_post_type() == 'business' )
					|| $current_component == 'activity'
					) {
				$rtl_css = is_rtl() ? '-rtl' : '';
				wp_enqueue_style( $this->plugin_name . '-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $rtl_css . '/bp-checkins-public.css', array(), $this->version, 'all' );
			}

						wp_register_style(
							$handle = 'wb-icons',
							$src    = plugin_dir_url( __FILE__ ) . 'css/wb-icons.css',
							$deps   = array(),
							$ver    = time(),
							$media  = 'all'
						);
			if ( ! wp_style_is( 'wb-icons', 'enqueued' ) ) {
				wp_enqueue_style( 'wb-icons' );
			}
		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			global $bp_checkins, $post;
			$current_component = '';
			if ( isset( $post->ID ) && $post->ID != '' && $post->ID != '0' ) {
				$_elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
				if ( $_elementor_data != '' && str_contains($_elementor_data, 'bp_newsfeed_element_widget') || str_contains($_elementor_data, 'buddypress_shortcode_activity_widget') || str_contains($_elementor_data, 'bbp-activity')) {
					$current_component = 'activity';
				}
			}

			$checkin_tab_slug = isset( $bp_checkins->tab_name ) ? $bp_checkins->tab_name : 'checkin';
			$checkin_tab_slug = apply_filters( 'bpchk_member_profile_checkin_tab_slug', sanitize_title( $checkin_tab_slug ) );
			if ( bp_is_groups_component()
					|| bp_is_activity_component()
					|| bp_is_profile_component()
					|| strpos( filter_input( INPUT_SERVER, 'REQUEST_URI' ), $checkin_tab_slug )
					|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) )
					|| ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'bppfa_postform' ) ) )
					|| ( is_single() && get_post_type() == 'business' )
					|| $current_component == 'activity'
					) {
				wp_enqueue_script( 'jquery-ui-accordion' );
				wp_enqueue_script( $this->plugin_name . '-google-places-api', 'https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=' . $bp_checkins->apikey, array( 'jquery' ), $this->version, false );
				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bp-checkins-public.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
				if ( is_user_logged_in() && bp_is_active( 'xprofile' ) ) {
					if ( xprofile_get_field_id_from_name( 'Location' ) ) {
						$bpchk_location_id = xprofile_get_field_id_from_name( 'Location' );
						$bpchk_loc_xprof   = 'field_' . $bpchk_location_id;
					}
				}
				if ( empty( $bpchk_loc_xprof ) ) {
					$bpchk_loc_xprof = '';
				}
				wp_localize_script(
					$this->plugin_name,
					'bpchk_public_js_obj',
					array(
						'ajaxurl'          => admin_url( 'admin-ajax.php' ),
						'checkin_by'       => $bp_checkins->checkin_by,
						'bpchk_loc_xprof'  => $bpchk_loc_xprof,
						'buddyboss'        => buddypress()->buddyboss,
						'add_checkin_text' => __( 'Add a place', 'bp-checkins' ),
						'bpchk_ajax_nonce' => wp_create_nonce( 'ajax-nonce' ),
					)
				);
			}
		}

		/**
		 * Register a new tab in member's profile - Checkin
		 *
		 * @since    1.0.1
		 */
		public function bpchk_member_profile_checkin_tab() {
			if ( bp_is_my_profile() ) {

				global $bp_checkins;
				$bpchk_settings = bp_get_option( 'bpchk_general_settings' );
				if ( isset( $bp_checkins->tab_visibility ) && '1' !== $bp_checkins->tab_visibility ) {
					return;
				}

				$displayed_uid    = bp_displayed_user_id();
				$parent_slug      = 'checkin';
				$my_places_link   = bp_core_get_userlink( $displayed_uid, false, true ) . $parent_slug . '/check-ins';
				$checkin_tab_name = isset( $bpchk_settings['tab_name'] ) ? $bpchk_settings['tab_name'] : '';
				$checkin_tab_slug = isset( $bp_checkins->tab_name ) ? $bp_checkins->tab_name : 'checkin';

				bp_core_new_nav_item(
					array(
						'name'                    => apply_filters( 'bpchk_member_profile_checkin_tab_name', esc_html( $checkin_tab_name ) ),
						'slug'                    => apply_filters( 'bpchk_member_profile_checkin_tab_slug', sanitize_title( $checkin_tab_slug ) ),
						'screen_function'         => array( $this, 'bpchk_checkins_activity_show_screen' ),
						'position'                => 75,
						'default_subnav_slug'     => 'check-ins',
						'show_for_displayed_user' => true,
					)
				);
			}
		}

		/**
		 * Screen function for listing all my places in menu item
		 */
		public function bpchk_checkins_activity_show_screen() {
			add_action( 'bp_template_content', array( $this, 'bpchk_checkins_tab_function_to_show_content' ) );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}

		/**
		 * My Places - Content
		 */
		public function bpchk_checkins_tab_function_to_show_content() {
			$file = BPCHK_PLUGIN_PATH . 'public/templates/checkin/bp-checkins-activity.php';
			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}

		/**
		 * Return country from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_country( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'country', $jsondata['results'][0]['address_components'] );
		}

		/**
		 * Return province from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_province( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'administrative_area_level_1', $jsondata['results'][0]['address_components'], true );
		}

		/**
		 * Return city from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_city( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'locality', $jsondata['results'][0]['address_components'] );
		}

		/**
		 * Return street from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_street( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'street_number', $jsondata['results'][0]['address_components'] ) . ' ' . self::bpchk_find_long_name_given_type( 'route', $jsondata['results'][0]['address_components'] );
		}

		/**
		 * Return postal code from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_postalcode( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'postal_code', $jsondata['results'][0]['address_components'] );
		}

		/**
		 * Return country code from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_country_code( $jsondata ) {
			return self::bpchk_find_long_name_given_type( 'country', $jsondata['results'][0]['address_components'], true );
		}

		/**
		 * Return formatted address from json data
		 *
		 * @param    array $jsondata    Google places api data.
		 */
		public static function google_get_address( $jsondata ) {
			return $jsondata['results'][0]['formatted_address'];
		}

		/**
		 * Searching in Google Geo json, return the long name given the type.
		 * (If short_name is true, return short name)
		 *
		 * @param    string  $type  The type of the place.
		 * @param    array   $array    The place type array.
		 * @param    boolean $short_name    Short name exist.
		 */
		public static function bpchk_find_long_name_given_type( $type, $array, $short_name = false ) {
			foreach ( $array as $value ) {
				if ( in_array( $type, $value['types'], true ) ) {
					if ( $short_name ) {
						return $value['short_name'];
					}
					return $value['long_name'];
				}
			}
		}


		/**
		 * Add location xprofile field.
		 *
		 * @since 1.0.1
		 */
		public function bpchk_add_location_xprofile_field() {
			global $bp_checkins;

			// Check if the BuddyPress XProfile component is active, exit the function if it's not.
			if( ! bp_is_active( 'xprofile' ) ){
				return;
			}

			if ( ! isset( $bp_checkins->enable_location_field ) && '1' !== $bp_checkins->enable_location_field ) {
				return;
			}

			if ( xprofile_get_field_id_from_name( 'Location' ) ) {
				return;
			}			

			$location_list_args = array(
				'field_group_id' => 1,
				'type'           => 'textbox',
				'name'           => 'Location',
				'description'    => 'Please select your location',
				'is_required'    => false,
				'can_delete'     => true,
				'order_by'       => 'default',
			);
			xprofile_insert_field( $location_list_args );

			if ( function_exists( 'bp_core_xprofile_update_profile_completion_user_progress' ) ) {
				bp_core_xprofile_update_profile_completion_user_progress();
			}
		}

		/**
		 * Ajax request to save location xprofile field.
		 *
		 * @since 1.0.1
		 */
		public function bpchk_save_xprofile_location() {
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
				return;
			}
			$action = isset( $_POST['action'] ) ? sanitize_text_field( wp_unslash( $_POST['action'] ) ) : '';
			if ( $action && 'bpchk_save_xprofile_location' === $action ) {
				$args = array(
					'place'     => isset( $_POST['place'] ) ? sanitize_text_field( wp_unslash( $_POST['place'] ) ) : '',					
					'latitude'  => isset( $_POST['latitude'] ) ? sanitize_text_field( wp_unslash( $_POST['latitude'] ) ) : '',					
					'longitude' => isset( $_POST['longitude'] ) ? sanitize_text_field( wp_unslash( $_POST['longitude'] ) ) : '',
				);
				if ( xprofile_get_field_id_from_name( 'Location' ) ) {
					$bpchk_location_id = xprofile_get_field_id_from_name( 'Location' );
					bp_xprofile_update_meta( $bpchk_location_id, 'data', 'bpchk_loc_xprofile', $args );
				}
			}
			exit;
		}

		/**
		 * Function to filter location xprofile field value at profile page.
		 *
		 * @since 1.0.1
		 * @param string $field_value Value for the profile field.
		 * @param string $field_type  Type for the profile field.
		 * @param int    $field_id    ID for the profile field.
		 */
		public function bpchk_show_xprofile_location( $field_value, $field_type, $field_id ) {
			global $bp_checkins;
			if ( xprofile_get_field_id_from_name( 'Location' ) ) {
				$bpchk_location_id = xprofile_get_field_id_from_name( 'Location' );
				if ( $field_id === $bpchk_location_id ) {
					$loc_xprof_meta = bp_xprofile_get_meta( $bpchk_location_id, 'data', 'bpchk_loc_xprofile' );
					if ( ! empty( $loc_xprof_meta ) && is_array( $loc_xprof_meta ) ) {

						$field_value = '<a class=checkin-loc href="http://maps.google.com/maps/place/' . $loc_xprof_meta['place'] . '/@' . $loc_xprof_meta['latitude'] . ',' . $loc_xprof_meta['longitude'] . '" target="_blank" title="' . $loc_xprof_meta['place'] . '">' . $loc_xprof_meta['place'] . '</a>';
						return $field_value;
					}
				}
				return $field_value;
			} else {
				return $field_value;
			}
		}

		/**
		 * Function to add checkin activity types.
		 *
		 * @since 1.0.1
		 * @param array $types Value for the profile field.
		 */
		public function bpchk_add_checkin_activity_type( $types ) {
			$types[] = 'activity_bpchk_chkins';
			return $types;
		}

		/**
		 * Function to register activity action.
		 *
		 * @since 1.0.1
		 */
		public function custom_plugin_register_activity_actions() {

			$component_id = buddypress()->activity->id;

			bp_activity_set_action(
				$component_id,
				'activity_bpchk_chkins',
				__( 'Check-ins Update', 'bp-checkins' ),
				array( $this, 'bp_activity_format_activity_action_activity_bpchk_chkins' ),
				__( 'Check-ins', 'bp-checkins' ),
				array( 'activity', 'member', 'group', 'member_groups' )
			);
		}

		/**
		 * Format 'activity_update' activity actions.
		 *
		 * @since 1.0.1
		 *
		 * @param string $action   Static activity action.
		 * @param object $activity Activity data object.
		 * @return string $action
		 */
		public function bp_activity_format_activity_action_activity_bpchk_chkins( $action, $activity ) {

			$action = sprintf(
				/* translators: s: User name */
				__( '%s checked-in', 'bp-checkins' ),
				bp_core_get_userlink( $activity->user_id )
			);

			/**
			 * Filters the formatted activity action update string.
			 *
			 * @since 1.2.0
			 *
			 * @param string               $action   Activity action string value.
			 * @param BP_Activity_Activity $activity Activity item object.
			 */
			return apply_filters( 'bp_activity_new_checkin_action', $action, $activity );
		}

		/**
		 * Function to set activity type activity_bpchk_chkins.
		 *
		 * @since 1.0.1
		 * @param array $activity_object Activity object.
		 */
		public function bpchk_update_activity_type_checkins( $activity_object ) {
			 // Verify the nonce
			if ( ! isset( $_POST['bpchk-checkins-activity-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpchk-checkins-activity-nonce'] ) ), 'bpchk-checkins-activity-post' ) ) {
				return; // Nonce failed, so stop execution
			}			
			$place_details = array();

			if ( isset( $_POST['bp-checkin-address'] ) && $_POST['bp-checkin-address'] != '' ) { //phpcs:ignore
				$place_details['place']           = isset( $_POST['bp-checkin-address'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-address'] ) ) : ''; //phpcs:ignore
				$place_details['latitude']        = isset( $_POST['bp-checkin-lat'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lat'] ) ) : ''; //phpcs:ignore
				$place_details['longitude']       = isset( $_POST['bp-checkin-lng'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lng'] ) ) : ''; //phpcs:ignore
				$place_details['add_as_my_place'] = isset( $_POST['bp-checkin-add-as-place'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-add-as-place'] ) ) : ''; //phpcs:ignore 
			}
			if ( ! empty( $place_details ) ) {				
				$activity_object->type = 'activity_bpchk_chkins';
			}
		}

		/**
		 * Action performed to save the activity update to show the checkin.
		 *
		 * @param string $content The actvity content.
		 * @param int    $user_id User id.
		 * @param int    $activity_id Activity id.
		 * @since 1.0.1
		 */
		public function bpchk_update_meta_on_post_update( $content, $user_id, $activity_id ) {			
			global $wpdb, $bp_checkins;

			$activity_tbl = $wpdb->base_prefix . 'bp_activity';
			$apikey       = $bp_checkins->apikey;

			$place_details = array();

			 // Verify the nonce
			if ( ! isset( $_POST['bpchk-checkins-activity-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpchk-checkins-activity-nonce'] ) ), 'bpchk-checkins-activity-post' ) ) {
				return; // Nonce failed, so stop execution
			}
			if ( isset( $_POST['bp-checkin-address'] ) && $_POST['bp-checkin-address'] != '' ) {
				$place_details['place']           = isset( $_POST['bp-checkin-address'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-address'] ) ) : '';
				$place_details['latitude']        = isset( $_POST['bp-checkin-lat'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lat'] ) ) : '';
				$place_details['longitude']       = isset( $_POST['bp-checkin-lng'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lng'] ) ) : '';
				$place_details['add_as_my_place'] = isset( $_POST['bp-checkin-add-as-place'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-add-as-place'] ) ) : '';
			}
			if ( ! empty( $place_details ) ) {
				$place           = $place_details['place'];
				$longitude       = $place_details['longitude'];
				$latitude        = $place_details['latitude'];			

				$location_html = '' . __( '-at', 'bp-checkins' ) . ' <a class=checkin-loc href="http://maps.google.com/maps/place/' . $place . '/@' . $latitude . ',' . $longitude . '" target="_blank" title="' . $place . '">' . $place . '</a>';
				$content      .= $location_html;
				// Update the activity content to post the checkin along with the post update.
				if ( false === strpos( $content, '-at <a class="checkin-loc"' ) ) {
					$wpdb->update(
						$activity_tbl,
						array( 'content' => $content ),
						array( 'id' => $activity_id ),
						array( '%s' ),
						array( '%d' )
					);
					// Update the location details in activity meta.
					bp_activity_update_meta( $activity_id, 'bpchk_place_details', $place_details );
				}
				if ( 'yes' === $place_details['add_as_my_place'] ) {
					$bpchk_fav_places = get_user_meta( $user_id, 'bpchk_fav_places', true );
					$place_get_url    = 'https://maps.googleapis.com/maps/api/geocode/json?key=' . $apikey . "&latlng=$latitude,$longitude&sensor=false";
					$response         = wp_remote_get( $place_get_url );

					$response_code = wp_remote_retrieve_response_code( $response );
					if ( 200 === $response_code ) {
						$jsondata         = json_decode( wp_remote_retrieve_body( $response ), true );
						$place_visit_date = date( 'Y-m-d', time() );

						if ( $jsondata['results'][0]['formatted_address'] ) {
							$address                      = array();
							$address['latitude']          = $latitude;
							$address['longitude']         = $longitude;
							$address['activity_id']       = $activity_id;
							$address['place']             = $place;
							$address['country']           = self::google_get_country( $jsondata );
							$address['province']          = self::google_get_province( $jsondata );
							$address['city']              = self::google_get_city( $jsondata );
							$address['street']            = self::google_get_street( $jsondata );
							$address['postal_code']       = self::google_get_postalcode( $jsondata );
							$address['country_code']      = self::google_get_country_code( $jsondata );
							$address['formatted_address'] = self::google_get_address( $jsondata );
							$address['visit_date']        = $place_visit_date;
							if ( $bpchk_fav_places ) {
								array_push( $bpchk_fav_places, $address );
								update_user_meta( $user_id, 'bpchk_fav_places', $bpchk_fav_places );
							} else {
								$fav_places   = array();
								$fav_places[] = $address;
								update_user_meta( $user_id, 'bpchk_fav_places', $fav_places );
							}
						}
					}
				}
			}
		}

		/**
		 * Action performed to save the group activity update to show the checkin.
		 *
		 * @param string $content The group activity content.
		 * @param int    $user_id The user id.
		 * @param int    $group_id The group id.
		 * @param int    $activity_id The group id.
		 * @since 1.0.1
		 */
		public function bpchk_update_group_meta_on_post_update( $content, $user_id, $group_id, $activity_id ) {			
			global $wpdb;

			$activity_tbl = $wpdb->base_prefix . 'bp_activity';

			$place_details = array();

			// Verify the nonce
			if ( ! isset( $_POST['bpchk-checkins-activity-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpchk-checkins-activity-nonce'] ) ), 'bpchk-checkins-activity-post' ) ) {
				return; // Nonce failed, so stop execution
			}
				
			if ( isset( $_POST['bp-checkin-address'] ) && $_POST['bp-checkin-address'] != '' ) {
				$place_details['place']           = isset( $_POST['bp-checkin-address'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-address'] ) ) : '';
				$place_details['latitude']        = isset( $_POST['bp-checkin-lat'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lat'] ) ) : '';
				$place_details['longitude']       = isset( $_POST['bp-checkin-lng'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lng'] ) ) : '';
				$place_details['add_as_my_place'] = isset( $_POST['bp-checkin-add-as-place'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-add-as-place'] ) ) : '';
			}

			if ( ! empty( $place_details ) ) {
				$place           = $place_details['place'];
				$longitude       = $place_details['longitude'];
				$latitude        = $place_details['latitude'];
				$add_as_my_place = $place_details['add_as_my_place'];

				$location_html = '' . __( '-at', 'bp-checkins' ) . ' <a class=checkin-loc href="http://maps.google.com/maps/place/' . $place . '/@' . $latitude . ',' . $longitude . '" target="_blank" title="' . $place . '">' . $place . '</a>';
				$content      .= $location_html;
				$pos           = strpos( $content, '-at <a class="checkin-loc"' );
				// Update the activity content to post the checkin along with the post update.
				if ( false === $pos ) {
					$wpdb->update(
						$activity_tbl,
						array( 'content' => $content ),
						array( 'id' => $activity_id ),
						array( '%s' ),
						array( '%d' )
					);

					// Update the location details in activity meta.
					bp_activity_update_meta( $activity_id, 'bpchk_place_details', $place_details );
				}

				if ( 'yes' === $add_as_my_place ) {
					$bpchk_fav_places = get_user_meta( $user_id, 'bpchk_fav_places', true );
					$place_get_url    = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
					$response         = wp_remote_get( $place_get_url );

					$response_code = wp_remote_retrieve_response_code( $response );

					if ( 200 === $response_code ) {
						$jsondata         = json_decode( wp_remote_retrieve_body( $response ), true );
						$place_visit_date = date( 'Y-m-d', time() );

						if ( isset( $jsondata['results'][0]['formatted_address'] ) ) {
							$address                      = array();
							$address['latitude']          = $latitude;
							$address['longitude']         = $longitude;
							$address['activity_id']       = $activity_id;
							$address['place']             = $place;
							$address['country']           = self::google_get_country( $jsondata );
							$address['province']          = self::google_get_province( $jsondata );
							$address['city']              = self::google_get_city( $jsondata );
							$address['street']            = self::google_get_street( $jsondata );
							$address['postal_code']       = self::google_get_postalcode( $jsondata );
							$address['country_code']      = self::google_get_country_code( $jsondata );
							$address['formatted_address'] = self::google_get_address( $jsondata );
							$address['visit_date']        = $place_visit_date;

							if ( $bpchk_fav_places ) {
								array_push( $bpchk_fav_places, $address );
								update_user_meta( $user_id, 'bpchk_fav_places', $bpchk_fav_places );
							} else {
								$fav_places   = array();
								$fav_places[] = $address;
								update_user_meta( $user_id, 'bpchk_fav_places', $fav_places );
							}
						}
					}
				}
			}
		}

		/**
		 * To set activity action for check-in type activity in group.
		 *
		 * @param string $activity_action The group activity action.
		 * @since 1.0.1
		 */
		public function bpchk_groups_activity_new_update_action( $activity_action ) {
			global $bp;
			$user_id = bp_loggedin_user_id();
			if ( ! isset( $_POST['bp-checkin-address'] ) ) {
				return $activity_action;
			}
			$place_details = array();			
			// Verify the nonce
			if ( ! isset( $_POST['bpchk-checkins-activity-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bpchk-checkins-activity-nonce'] ) ), 'bpchk-checkins-activity-post' ) ) {
				return; // Nonce failed, so stop execution
			}
			if ( isset( $_POST['bp-checkin-address'] ) && $_POST['bp-checkin-address'] != '' ) {
				$place_details['place']           = isset( $_POST['bp-checkin-address'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-address'] ) ) : '';
				$place_details['latitude']        = isset( $_POST['bp-checkin-lat'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lat'] ) ) : '';
				$place_details['longitude']       = isset( $_POST['bp-checkin-lng'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-lng'] ) ) : '';
				$place_details['add_as_my_place'] = isset( $_POST['bp-checkin-add-as-place'] ) ? sanitize_text_field( wp_unslash( $_POST['bp-checkin-add-as-place'] ) ) : '';
			}
			if ( ! empty( $place_details ) ) {
				if ( function_exists('bp_get_version') && version_compare(bp_get_version(), '12.0.0', '>=') ) {
					/* translators: %s: */
					$activity_action = sprintf( __( '%1$s checked-in in the group %2$s', 'bp-checkins' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_url( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );	
				}else{
				/* translators: %s: */
					$activity_action = sprintf( __( '%1$s checked-in in the group %2$s', 'bp-checkins' ), bp_core_get_userlink( $user_id ), '<a href="' . bp_get_group_permalink( $bp->groups->current_group ) . '">' . esc_attr( $bp->groups->current_group->name ) . '</a>' );
				}
			}
			return $activity_action;
		}

			/**
			 * Show mep on checkin activities
			 *
			 * @since 1.0.1
			 */
		public function bpchk_show_google_map_in_checkin_activity() {
			$activity_id = bp_get_activity_id();
			global $wpdb, $bp_checkins;
			$activity_meta_tbl = $wpdb->base_prefix . 'bp_activity_meta';
			$place             = bp_activity_get_meta( $activity_id, 'bpchk_place_details', true );

			if ( ! empty( $place )  ) {
				$apikey        = $bp_checkins->apikey;	
				$latitude      = isset( $place['latitude'] ) ? $place['latitude'] :'';
				$longitude     = isset( $place['longitude'] ) ? $place['longitude'] :'';
				$place_get_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
				$response      = wp_remote_get( $place_get_url );
				if ( is_wp_error( $response ) ) {
					return; // Handle the error
				}

				$response_code = wp_remote_retrieve_response_code( $response );

				$formatted_address = $place['place'];
				if ( 200 === $response_code ) {
					$jsondata = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( isset( $jsondata['results'][0]['formatted_address'] ) ) {
						$formatted_address = self::google_get_address( $jsondata );

					}
				} else {
					$formatted_address = $place['place'];
				}
				$map_url = 'https://www.google.com/maps/embed/v1/place?key=' . $apikey . '&q=' . $formatted_address;
				echo '<div id="bpchk-place-map"><iframe frameborder="0" style="border:0" src="' . esc_url( $map_url ) . '" allowfullscreen></iframe></div>';
			}
		}
		public function bpchk_show_google_map_in_checkin_emded_activity( $content, $activity_content ) {
			ob_start();
			$activity_id = bp_get_activity_id();
			global $wpdb, $bp_checkins;
			$activity_meta_tbl = $wpdb->base_prefix . 'bp_activity_meta';
			$place             = bp_activity_get_meta( $activity_id, 'bpchk_place_details', true );

			if ( ! empty( $place ) ) {
				$apikey        = $bp_checkins->apikey;
				$latitude      = ( isset( $place['latitude'] ) ) ? $place['latitude'] : '';
				$longitude     = ( isset( $place['longitude'] ) ) ? $place['longitude'] : '';
				$place_get_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&sensor=false";
				$response      = wp_remote_get( $place_get_url );

				$response_code     = wp_remote_retrieve_response_code( $response );
				$formatted_address = $place['place'];
				if ( 200 === $response_code ) {
					$jsondata = json_decode( wp_remote_retrieve_body( $response ), true );
					if ( isset( $jsondata['results'][0]['formatted_address'] ) ) {
						$formatted_address = self::google_get_address( $jsondata );
					}
				} else {
					$formatted_address = $place['place'];
				}
				$map_url = 'https://www.google.com/maps/embed/v1/place?key=' . $apikey . '&q=' . $formatted_address;
				echo '<div id="bpchk-place-map"><iframe frameborder="0" style="border:0" src="' . esc_url( $map_url ) . '" allowfullscreen></iframe></div>';
			}
			return $content . ob_get_clean();
		}

		/**
		 * Ajax served to delete checkin locations from check-ins tab.
		 */
		public function bpchk_delete_user_checkin_location() {
			global $bp;
			$activity_id      = filter_input( INPUT_POST, 'checkin_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS );			
			$bpchk_fav_places = get_user_meta( bp_loggedin_user_id(), 'bpchk_fav_places', true );
			
			if ( ! empty( $bpchk_fav_places ) ) {				
				foreach ( $bpchk_fav_places as $key => $fav_places ) {				
					if ( $fav_places['activity_id'] == $activity_id ) {					
						unset( $bpchk_fav_places[ $key ] ); // Unset the array key
					}
				}
				// Reindex the array after unsetting
				$bpchk_fav_places = array_values( $bpchk_fav_places );
				
				// Update the user meta
				update_user_meta( bp_loggedin_user_id(), 'bpchk_fav_places', $bpchk_fav_places );
			}
			die;
		}

			/**
			 * Add buddypress chckin activity on yozer activity
			 *
			 * @param array $post_types
			 * @return array
			 */
		public function bp_checkin_allow_youzer_activity( $post_types ) {
			if ( is_array( $post_types ) ) {
				array_push( $post_types, 'activity_bpchk_chkins' );
			}
			return $post_types;
		}

			/**
			 * Static function to check if youzer wall option enable or not.
			 *
			 * @return boolean
			 */
		public static function bp_checkins_is_youzer_activity() {
			$is_enable = true;
			if ( class_exists( 'Youzer' ) ) {
				$unallowed_activities = yz_option( 'yz_unallowed_activities' );
				if ( ! empty( $unallowed_activities ) ) {
					$unallowed_activities = (array) array_flip( $unallowed_activities );

					if ( ! array_key_exists( 'activity_bpchk_chkins', $unallowed_activities ) ) {
						$is_enable = true;
					} else {
						$is_enable = false;
					}
				}
			}
			return $is_enable;
		}

		public function bp_checkin_hide_profile_field( $retval ) {
			global $bp_checkins;

			if ( '1' === $bp_checkins->enable_location_field ) {

				$field_id = xprofile_get_field_id_from_name( 'Location' );

				// hide the field on profile view tab
				if ( ! bp_is_user_profile_edit() ) {
					$retval['exclude_fields'] = $field_id; // ID's separated by comma
				}

				// hide the field on profile edit tab
				if ( bp_is_user_profile_edit() ) {
					$retval['exclude_fields'] = $field_id; // ID's separated by comma
				}

				// allow field on register page
				if ( bp_is_register_page() ) {
					$retval['include_fields'] = $field_id; // ID's separated by comma
				}
			}
			return $retval;
		}

		/**
		 * Embedded activity content.
		 *
		 * @since BuddyPress 9.1.1
		 *
		 * @param string $activity_content  Embedded activity
		 */
		public function bp_checkin_activity_embed_add_inline_styles() {
			?>
			<style type="text/css">
				#bpchk-place-map {
					width: 100%;
					margin-top: 15px;
				}
				#bpchk-place-map iframe {
					width: 100%;
				}
			</style>
			<?php
		}

	}
endif;
