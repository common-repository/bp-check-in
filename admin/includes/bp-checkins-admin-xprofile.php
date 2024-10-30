<?php
/**
 * Advanced settings area view for the plugin
 *
 * This file is used for rendering and saving plugin general settings.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Bp_Checkins_Pro
 * @subpackage Bp_Checkins_Pro/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wbcom-wrapper-admin">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Member xProfile Location/Address fields setup', 'bp-checkins' ); ?></h3>
	</div>
	<form method="post" action="options.php" class="bpchk_xprofile_setup">
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<div class="form-table">
				<div class="wbcom-admin-title-section">
					<h3>
						<?php esc_html_e( 'xProfile Fields Usage', 'bp-checkins' ); ?>
					</h3>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="tab-visibilty">
							<?php esc_html_e( 'Multiple Address Fields', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select to either use a sinlge address field as the full address, or multiple address fields. Then select the Xprofile Fields for each location field below.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select name="bpchkpro_xprofile_options[xprofile_location_field_type]" id="bpcp_settings_field_usage" disabled>
							<option selected><?php esc_html_e( 'Multiple Address Fields', 'bp-checkins' ); ?></option>
						</select>					
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'Street', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select street address xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
							<select name="bpchkpro_xprofile_options[xprofile_location_field_street]" disabled>
								<option value="none" selected><?php esc_attr_e( 'Street', 'bp-checkins' ); ?></option>
							</select>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'City', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select city xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select name="bpchkpro_xprofile_options[xprofile_location_field_city]" disabled>
							<option value="none" selected><?php esc_attr_e( 'City', 'bp-checkins' ); ?></option>
						</select>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'State', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select state xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select name="bpchkpro_xprofile_options[xprofile_location_field_state]" disabled>
							<option value="none" selected><?php esc_attr_e( 'State', 'bp-checkins' ); ?></option>
						</select>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'ZIP', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select zip xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select name="bpchkpro_xprofile_options[xprofile_location_field_zip]" disabled>
							<option value="none" selected><?php esc_attr_e( 'ZIP', 'bp-checkins' ); ?></option>
						</select>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'Country', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select country xprofile field or select none for disable it.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select name="bpchkpro_xprofile_options[xprofile_location_field_country]" disabled>
							<option value="none" selected><?php esc_attr_e( 'Country', 'bp-checkins' ); ?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
