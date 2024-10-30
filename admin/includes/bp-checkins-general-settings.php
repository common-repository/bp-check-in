<?php
/**
 * BuddyPress Check-ins general setting tab file.
 *
 * @package Bp_Checkins
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $bp_checkins;
$saved_range = $bp_checkins->google_places_range;

$verify_btn_style = 'display: none;';
if ( ! empty( $bp_checkins->apikey ) ) {
	$verify_btn_style = '';
}

?>
<div class="wbcom-wrapper-admin">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'General Settings', 'bp-checkins' ); ?></h3>
	</div>
	<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
		<div class="form-table bpchk-admin-page-table">
				<!-- Checkin Tab Visibility  -->
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="tab-visibilty">
							<?php esc_html_e( 'Enable Check-ins Tab', 'bp-checkins' ); ?>
						</label>
					</div>
					<div class="wbcom-settings-section-options">
						<input class="regular-text" type="checkbox" value="1" name="bpchk-tab-visibilty" id="bpchk-tab-visibilty" <?php isset( $bp_checkins->tab_visibility ) ? checked( $bp_checkins->tab_visibility, 1 ) : ''; ?>>
					</div>
				</div>
				<!-- Checkin Tab Visibility  -->
				<!-- Rename Checkin Tab  -->
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="tab-name">
							<?php esc_html_e( 'Rename Checkin Tab', 'bp-checkins' ); ?>
						</label>
					</div>
					<div class="wbcom-settings-section-options">
						<input class="regular-text" type="text" value="<?php echo isset( $bp_checkins->tab_name ) ? esc_html( $bp_checkins->tab_name ) : ''; ?>" name="bpchk-tab-name" id="bpchk-tab-name" placeholder="<?php esc_html_e( 'Check-ins', 'bp-checkins' ); ?>">
					</div>
				</div>
				<!-- Rename Checkin Tab  -->
				<!-- Enable Location xProfile Field  -->
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="tab-name">
							<?php esc_html_e( 'Manage Location Profile Field Visibility', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this option to hide the Location field on the user\'s profile.', 'bp-checkins' ); ?>
					</div>
					<div class="wbcom-settings-section-options">
						<input class="regular-text" type="checkbox" value="1" name="bpchk-enable-xprofile-filed" id="bpchk-enable-xprofile-filed" <?php isset( $bp_checkins->enable_location_field ) ? checked( $bp_checkins->enable_location_field, 1 ) : ''; ?> >
					</div>
				</div>
				<!-- Enable Location xProfile Field  -->
				<!-- API Key -->
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="api-key">
							<?php esc_html_e( 'API Key', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( "Due to changes in Google Maps API it's required to use an API key for the BuddyPress Check-ins plugin to work properly.", 'bp-checkins' ); ?>
					</div>
					<div class="wbcom-settings-section-options">
						<input class="regular-text" type="text" value="<?php echo esc_attr( $bp_checkins->apikey ); ?>" name="bpchk-api-key" id="bpchk-api-key" placeholder="<?php esc_html_e( 'API Key', 'bp-checkins' ); ?>" required>
						<button type="button" class="button button-secondary" style="<?php echo esc_attr( $verify_btn_style ); ?>" id="bpchk-verify-apikey"><?php esc_html_e( 'Verify', 'bp-checkins' ); ?></button>
						<?php $gm_api_url = 'https://console.developers.google.com/henhouse/?pb=["hh-1","maps_backend",null,[],"https://developers.google.com",null,["static_maps_backend","street_view_image_backend","maps_embed_backend","places_backend","geocoding_backend","directions_backend","distance_matrix_backend","geolocation","elevation_backend","timezone_backend","maps_backend"],null]'; ?>
						</p>
						<a id="gd-api-key" onclick='window.open("<?php echo esc_attr( wp_slash( $gm_api_url ) ); ?>", "newwindow", "width=600, height=400"); return false;' href='<?php echo esc_url( $gm_api_url ); ?>' class="button-primary " name="<?php esc_attr_e( 'Generate API Key - ( MUST be logged in to your Google account )', 'bp-checkins' ); ?>" ><?php esc_html_e( 'Generate API Key', 'bp-checkins' ); ?></a>&nbsp;
						<a href="javascript:void(0);" class="gd-api-link" onClick="window.open('https://support.wbcomdesigns.com/portal/kb/articles/how-to-get-google-api-key','pagename','resizable,height=600,width=700'); return false;">
							<?php esc_html_e( '( How to Get Google API Key? )', 'bp-checkins' ); ?>
						</a>
					</div>
				</div>
				<!-- API Key -->
		</div>
<p class="submit">
	<input type="hidden" name="bpci_nonce" value="<?php echo esc_attr( wp_create_nonce( 'bpci_option_nonce' ) ); ?>">
	<?php submit_button( 'Save Changes', 'primary', 'bpchk-submit-general-settings' ); ?>
</p>
</div>
</div>
