<?php
/**
 *
 * This file is used for rendering and saving plugin welcome settings.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}
?>
<div class="wbcom-welcome-main-wrapper">
	<div class="wbcom-welcome-head">
		<p class="wbcom-welcome-description"><?php esc_html_e( 'BuddyPress Check-ins Plugin allows to select place type for your members, like if you are foodies, you can select food-related place type and your members will be able to post food-related places on your website activity stream.', 'bp-checkins' ); ?></p>
	</div><!-- .wbcom-welcome-head -->
	<div class="wbcom-welcome-content">
		<div class="wbcom-welcome-support-info">
			<h3><?php esc_html_e( 'Help &amp; Support Resources', 'bp-checkins' ); ?></h3>
			<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'bp-checkins' ); ?></p>
			<div class="wbcom-support-info-wrap">
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
					<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'bp-checkins' ); ?></h3>
					<p><?php esc_html_e( 'We have prepared an extensive guide on BuddyPress Check-ins to learn all aspects of the plugin. You will find most of your answers here.', 'bp-checkins' ); ?></p>
					<a href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/buddypress-check-ins/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'bp-checkins' ); ?></a>
					</div>
				</div>

				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
					<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'bp-checkins' ); ?></h3>
					<p><?php esc_html_e( 'We are committed to providing top-notch customer support through our dedicated support center. You can reach out to us for assistance anytime you need help.', 'bp-checkins' ); ?></p>
					<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'bp-checkins' ); ?></a>
				</div>
				</div>
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
					<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'bp-checkins' ); ?></h3>
					<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'bp-checkins' ); ?></p>
					<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'bp-checkins' ); ?></a>
				</div>
				</div>
			</div>
		</div>
	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->
