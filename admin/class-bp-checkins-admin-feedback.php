<?php
/**
 * Plugin review class.
 * Prompts users to leave a review of the plugin on WordPress.org after they have used it for a certain period.
 *
 * Inspired by code from Rhys Wynne
 * https://winwar.co.uk/2014/10/ask-wordpress-plugin-reviews-week/
 *
 * @package Bp_Checkins
 */

if ( ! class_exists( 'Bp_Checkins_Admin_Feedback' ) ) :

	/**
	 * Class to manage user feedback for the plugin.
	 * Prompts users to leave a review after a set time.
	 */
	class Bp_Checkins_Admin_Feedback {

		/**
		 * Plugin slug.
		 *
		 * @var string $slug Slug used to identify the plugin.
		 */
		private $slug;

		/**
		 * Plugin name.
		 *
		 * @var string $name Name of the plugin.
		 */
		private $name;

		/**
		 * Time limit for displaying the review prompt.
		 *
		 * @var int $time_limit Time in seconds before showing the review prompt.
		 */
		private $time_limit;

		/**
		 * Option to disable the review prompt.
		 *
		 * @var string $nobug_option Option key for disabling the review prompt.
		 */
		public $nobug_option;

		/**
		 * Option key for storing the plugin activation date.
		 *
		 * @var string $date_option Option key for the plugin activation date.
		 */
		public $date_option;

		/**
		 * Constructor method.
		 * Initializes class properties and hooks actions.
		 *
		 * @param array $args Arguments to set the plugin slug, name, and time limit.
		 */
		public function __construct( $args ) {
			$this->slug = $args['slug'];
			$this->name = $args['name'];

			// Option keys for activation date and disabling the prompt.
			$this->date_option  = $this->slug . '_activation_date';
			$this->nobug_option = $this->slug . '_no_bug';

			// Set the time limit for the prompt (default is one week).
			$this->time_limit = isset( $args['time_limit'] ) ? $args['time_limit'] : WEEK_IN_SECONDS;

			// Hooks for checking installation date and setting the 'no bug' option.
			add_action( 'admin_init', array( $this, 'check_installation_date' ) );
			add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
		}

		/**
		 * Converts a time duration from seconds into human-readable format.
		 *
		 * @param int $seconds Time in seconds.
		 * @return string Human-readable time duration.
		 */
		public function seconds_to_words( $seconds ) {

			// Calculate the number of years.
			$years = ( intval( $seconds ) / YEAR_IN_SECONDS ) % 100;
			if ( $years > 1 ) {
				/* translators: Number of years */
				return sprintf( __( '%s years', 'bp-checkins' ), $years );
			} elseif ( $years > 0 ) {
				return __( 'a year', 'bp-checkins' );
			}

			// Calculate the number of weeks.
			$weeks = ( intval( $seconds ) / WEEK_IN_SECONDS ) % 52;
			if ( $weeks > 1 ) {
				/* translators: Number of weeks */
				return sprintf( __( '%s weeks', 'bp-checkins' ), $weeks );
			} elseif ( $weeks > 0 ) {
				return __( 'a week', 'bp-checkins' );
			}

			// Calculate the number of days.
			$days = ( intval( $seconds ) / DAY_IN_SECONDS ) % 7;
			if ( $days > 1 ) {
				/* translators: Number of days */
				return sprintf( __( '%s days', 'bp-checkins' ), $days );
			} elseif ( $days > 0 ) {
				return __( 'a day', 'bp-checkins' );
			}

			// Calculate the number of hours.
			$hours = ( intval( $seconds ) / HOUR_IN_SECONDS ) % 24;
			if ( $hours > 1 ) {
				/* translators: Number of hours */
				return sprintf( __( '%s hours', 'bp-checkins' ), $hours );
			} elseif ( $hours > 0 ) {
				return __( 'an hour', 'bp-checkins' );
			}

			// Calculate the number of minutes.
			$minutes = ( intval( $seconds ) / MINUTE_IN_SECONDS ) % 60;
			if ( $minutes > 1 ) {
				/* translators: Number of minutes */
				return sprintf( __( '%s minutes', 'bp-checkins' ), $minutes );
			} elseif ( $minutes > 0 ) {
				return __( 'a minute', 'bp-checkins' );
			}

			// Calculate the number of seconds.
			$seconds = intval( $seconds ) % 60;
			if ( $seconds > 1 ) {
				/* translators: Number of seconds */
				return sprintf( __( '%s seconds', 'bp-checkins' ), $seconds );
			} elseif ( $seconds > 0 ) {
				return __( 'a second', 'bp-checkins' );
			}
		}

		/**
		 * Checks the installation date to determine if the review prompt should be displayed.
		 */
		public function check_installation_date() {
			// Check if the review prompt has been disabled.
			if ( ! get_site_option( $this->nobug_option ) || false === get_site_option( $this->nobug_option ) ) {
				// If not, set the activation date option.
				add_site_option( $this->date_option, time() );

				// Retrieve the activation date.
				$install_date = get_site_option( $this->date_option );

				// If the time since installation exceeds the limit, display the admin notice.
				if ( ( time() - $install_date ) > $this->time_limit ) {
					add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
				}
			}
		}

		/**
		 * Displays an admin notice prompting the user to leave a review.
		 */
		public function display_admin_notice() {
			$screen = get_current_screen();

			// Ensure the notice is only shown on the plugins screen.
			if ( isset( $screen->base ) && 'plugins' === $screen->base ) {
				$no_bug_url = wp_nonce_url( admin_url( '?' . $this->nobug_option . '=true' ), 'bp-checkins-feedback-nonce' );
				$time       = $this->seconds_to_words( time() - get_site_option( $this->date_option ) );
				?>

				<style>
				/* Styling for the admin notice */
				.notice.bp-checkins-notice {
				    padding: 12px;
				    border-radius: 10px;
				    border-color: #2a32ef;
				}
				.rtl .notice.bp-checkins-notice {
					border-right-color: #008ec2 !important;
				}
				.notice.bp-checkins-notice .bp-checkins-notice-inner {
					display: table;
					width: 100%;
				}
				.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-icon,
				.notice.bp-checkins-notice .bp-checkins-notice-inner .bp-checkins-notice-content,
				.notice.bp-checkins-notice .bp-checkins-install-now {
					display: table-cell;
					vertical-align: middle;
				}
				.notice.bp-checkins-notice .bp-checkins-notice-icon {
					color: #509ed2;
					font-size: 50px;
					width: 70px;
				}
				.notice.bp-checkins-notice .bp-checkins-notice-icon img {
				    border-radius: 10px;
				    width: 75px;
				}
				.notice.bp-checkins-notice .bp-checkins-notice-content {
					padding: 0 40px 0 20px;
				}
				.notice.bp-checkins-notice p {
					padding: 0;
					margin: 0;
				}
				.notice.bp-checkins-notice h3 {
				    margin: 0 0 10px;
				}
				.notice.bp-checkins-notice .bp-checkins-install-now {
					text-align: center;
				}
				.notice.bp-checkins-notice .bp-checkins-install-now .bp-checkins-install-button {
				    padding: 10px 50px;
				    height: auto;
				    line-height: 20px;
				    background: #2a32ef;
				    border-radius: 8px;
				    font-weight: 500;
				    border-color: #2a32ef;
				}
				.notice.bp-checkins-notice a.no-thanks {
				    display: block;
				    margin-top: 5px;
				    color: #ff0000;
				    text-decoration: none;
				    font-weight: 500;
				}
				.notice.bp-checkins-notice a.no-thanks:hover {
					color:#2a32ef;
				}
				</style>

				<div class="notice updated bp-checkins-notice">
					<div class="bp-checkins-notice-inner">
						<div class="bp-checkins-notice-icon">
							<img src="<?php echo esc_url( BPCHK_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/bp_chekins.png'; ?>" alt="<?php echo esc_attr__( 'BuddyPress Check-ins', 'bp-checkins' ); ?>" />
						</div>
						<div class="bp-checkins-notice-content">
							<h3><?php echo esc_html__( 'Are you enjoying BuddyPress Check-ins?', 'bp-checkins' ); ?></h3>
							<p>
								<?php /* translators: 1. Plugin name */ ?>
								<?php printf( esc_html__( 'We hope you\'re enjoying %1$s! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?', 'bp-checkins' ), esc_html( $this->name ) ); ?>
							</p>
						</div>
						<div class="bp-checkins-install-now">
							<?php printf( '<a href="%1$s" class="button button-primary bp-checkins-install-button" target="_blank">%2$s</a>', esc_url( 'https://wordpress.org/support/plugin/bp-check-in/reviews/' ), esc_html__( 'Leave a Review', 'bp-checkins' ) ); ?>
							<a href="<?php echo esc_url( $no_bug_url ); ?>" class="no-thanks"><?php echo esc_html__( 'No thanks / I already have', 'bp-checkins' ); ?></a>
						</div>
					</div>
				</div>
				<?php
			}
		}

		/**
		 * Disables the review prompt if the user chooses not to be reminded.
		 */
		public function set_no_bug() {

			// Exit if not on the correct page or nonce validation fails.
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'bp-checkins-feedback-nonce' ) || ! is_admin() || ! isset( $_GET[ $this->nobug_option ] ) || ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Set the option to prevent further review prompts.
			add_site_option( $this->nobug_option, true );
		}
	}
endif;

/**
 * Instantiate the Bp_Checkins_Admin_Feedback class.
 * Initializes the review prompt functionality.
 */
new Bp_Checkins_Admin_Feedback(
	array(
		'slug'       => 'bp_checkins',
		'name'       => __( 'BuddyPress Check-ins', 'bp-checkins' ),
		'time_limit' => WEEK_IN_SECONDS,
	)
);
