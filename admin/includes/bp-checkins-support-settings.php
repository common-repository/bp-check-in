<?php
/**
 * BuddyPress Check-ins support tab file.
 *
 * @package Bp_Checkins
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-faq-adming-setting">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Have some questions?', 'bp-checkins' ); ?></h3>
	</div>
	<div class="wbcom-faq-admin-settings-block">
		<div id="wbcom-faq-settings-section" class="wbcom-faq-table">
			<div class="wbcom-faq-section-row">
					<button class="wbcom-faq-accordion">
						<?php esc_html_e( 'Does this plugin work with BuddyPress and BuddyBoss?', 'bp-checkins' ); ?>
					</button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Yes, this plugin works with both BuddyPress and BuddyBoss platforms.', 'bp-checkins' ); ?></p>
					</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'What is the API key used for?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'The API key enables Google Places autocomplete functionality, allowing users to check in to locations and display them on a map.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'Does this plugin require location services?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Yes, users need to enable location services in their browser to use the check-in functionality.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'How do users check-in with this plugin?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Users can check in by selecting a location using the autocomplete feature on the BuddyPress or BuddyBoss activity post page. A map will display the selected location.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'Where can I view all check-ins?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Check-ins can be viewed by selecting the "Check-ins" filter from the BuddyPress or BuddyBoss activity stream dropdown menu.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'Does the plugin support profile location fields?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'Yes, the plugin offers an xProfile field to add location data to user profiles in BuddyPress or BuddyBoss.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
			<div class="wbcom-faq-section-row">
				<div>
					<button class="wbcom-faq-accordion"><?php esc_html_e( 'Can I customize the plugin?', 'bp-checkins' ); ?></button>
					<div class="wbcom-faq-panel">
						<p><?php esc_html_e( 'If you need any custom modifications, feel free to contact us at <a href="https://wbcomdesigns.com/contact/" target="_blank" title="Wbcom Designs">Wbcom Designs</a>.', 'bp-checkins' ); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
