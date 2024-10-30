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
		<h3><?php esc_html_e( 'BP Group Location/Address Fields Settings', 'bp-checkins' ); ?></h3>
	</div>
	<form class="bpchkpro-group-location" action="options.php" method="post">
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<div class="form-table">
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_tab_lable">
							<?php esc_html_e( 'Location/Address tab label', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Group location/address tab label.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="text" class="regular-text" name="groups_settings[group_tab_lable]" id="group_tab_lable" value="Location" disabled>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_tab_slug">
							<?php esc_html_e( 'Location/Address tab slug', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'You can rewite the group location/address tab slug.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input class="regular-text" type="text" name="groups_settings[group_tab_slug]" value="location" disabled>
					</div>
				</div>
				<div class="wbcom-admin-title-section">
					<h3>
						<?php esc_html_e( 'Group Location Fields', 'bp-checkins' ); ?>
					</h3>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="tab-visibilty">
							<?php esc_html_e( 'Select Group Location Fields', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Select how many fields you want to show on group. Select disable for none.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<select id="group_location_fields" name="groups_settings[group_location_field_set]" disabled>
							<option value="none" selected><?php esc_html_e( 'Multiple Fields', 'bp-checkins' ); ?></option>
						</select>				
					</div>
				</div>
				<div class="wbcom-settings-section-wrap bpchkpro-multi-field">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_location_street">
							<?php esc_html_e( 'Street', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="checkbox" name="groups_settings[group_location_street_enable]" checked disabled>
						<input type="text" id="group_location_street" class="regular-text" name="groups_settings[group_location_street]" value="Street" disabled>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap bpchkpro-multi-field">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_location_city">
							<?php esc_html_e( 'City', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="checkbox" name="groups_settings[group_location_city_enable]" checked disabled>
						<input type="text" id="group_location_city" class="regular-text" name="groups_settings[group_location_city]" value="City" disabled>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap bpchkpro-multi-field">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_location_state">
							<?php esc_html_e( 'State', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="checkbox" name="groups_settings[group_location_state_enable]" checked disabled>
						<input type="text" id="group_location_state" class="regular-text" name="groups_settings[group_location_state]" value="State" disabled>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap bpchkpro-multi-field">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_location_postalcode">
							<?php esc_html_e( 'Postal Code', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="checkbox" name="groups_settings[group_location_postalcode_enable]"checked disabled>
						<input type="text" id="group_location_postalcode" class="regular-text" name="groups_settings[group_location_postalcode]" value="Postal Code" disabled>
					</div>
				</div>
				<div class="wbcom-settings-section-wrap bpchkpro-multi-field">
					<div class="wbcom-settings-section-options-heading">
						<label for="group_location_country">
							<?php esc_html_e( 'Country', 'bp-checkins' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enable this field if you want to show in group address fields.', 'bp-checkins' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<input type="checkbox" name="groups_settings[group_location_country_enable]" checked disabled>
						<input type="text" id="group_location_country" class="regular-text" name="groups_settings[group_location_country]" value="Country" disabled>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
