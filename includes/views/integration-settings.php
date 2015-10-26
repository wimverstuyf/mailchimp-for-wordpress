<?php defined( 'ABSPATH' ) or exit;
/** @var MC4WP_Integration $integration */
/** @var array $opts */
?>
<div id="mc4wp-admin" class="wrap mc4wp-settings">

	<div class="row">

		<!-- Main Content -->
		<div class="main-content col col-4 col-sm-6">

			<p>
				<a href="<?php echo remove_query_arg('integration'); ?>">&lsaquo; <?php _e( 'Back to integrations overview', 'mailchimp-for-wp' ); ?></a>
			</p>

			<h1 class="page-title">
				<?php printf( __( '%s integration', 'mailchimp-for-wp' ), $integration->name ); ?>
			</h1>

			<p>
				<?php echo $integration->description; ?>
			</p>

			<!-- Settings form -->
			<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">
				<?php settings_fields( 'mc4wp_integrations_settings' ); ?>

				<?php
				do_action( 'mc4wp_admin_before_integration_settings', $integration );
				do_action( 'mc4wp_admin_before_' . $integration->slug . '_integration_settings' );
				?>

				<table class="form-table">

					<?php if( $integration->has_ui_element( 'enabled' ) ) { ?>
					<tr valign="top">
						<th scope="row"><?php _e( 'Enabled?', 'mailchimp-for-wp' ); ?></th>
						<td class="nowrap integration-toggles-wrap">
							<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][enabled]" value="1" <?php checked( $opts['enabled'], 1 ); ?> /> <?php _e( 'Yes', 'mailchimp-for-wp' ); ?></label> &nbsp;
							<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][enabled]" value="0" <?php checked( $opts['enabled'], 0 ); ?> /> <?php _e( 'No', 'mailchimp-for-wp' ); ?></label>
							<p class="help"><?php printf( __( 'Enable the %s integration? This will add a sign-up checkbox to the form.', 'mailchimp-for-wp' ), $integration->name ); ?></p>
						</td>
					</tr>
					<?php } ?>

					<tbody class="integration-toggled-settings" <?php if( $integration->has_ui_element( 'enabled' ) && ! $opts['enabled'] ) echo 'style="opacity: 0.5;"';?>>

					<?php if( $integration->has_ui_element( 'lists' ) ) { ?>
						<tr valign="top">
							<th scope="row"><?php _e( 'MailChimp Lists', 'mailchimp-for-wp' ); ?></th>
							<?php if( ! empty( $lists ) ) {
								echo '<td>';
								foreach( $lists as $list ) {
									echo '<label>';
									echo sprintf( '<input type="checkbox" name="mc4wp_integrations[%s][lists][]" value="%s" %s> ', $integration->slug, $list->id, checked( in_array( $list->id, $opts['lists'] ), true, false ) );
									echo $list->name;
									echo '</label><br />';
								}

								echo '<p class="help">';
								_e( 'Select the list(s) to which people who check the checkbox should be subscribed.' ,'mailchimp-for-wp' );
								echo '</p>';
								echo '</td>';
							} else {
								echo '<td>' . sprintf( __( 'No lists found, <a href="%s">are you connected to MailChimp</a>?', 'mailchimp-for-wp' ), admin_url( 'admin.php?page=mailchimp-for-wp' ) ) . '</td>';
							} ?>
						</tr>
					<?php } // end if UI has lists ?>

					<?php if( $integration->has_ui_element( 'label' ) ) { ?>
						<tr valign="top">
							<th scope="row"><label for="mc4wp_checkbox_label"><?php _e( 'Checkbox label text', 'mailchimp-for-wp' ); ?></label></th>
							<td>
								<input type="text"  class="widefat" id="mc4wp_checkbox_label" name="mc4wp_integrations[<?php echo $integration->slug; ?>][label]" value="<?php echo esc_attr( $opts['label'] ); ?>" required />
								<p class="help"><?php printf( __( 'HTML tags like %s are allowed in the label text.', 'mailchimp-for-wp' ), '<code>' . esc_html( '<strong><em><a>' ) . '</code>' ); ?></p>
							</td>
						</tr>
					<?php } // end if UI label ?>


					<?php if( $integration->has_ui_element( 'precheck' ) ) { ?>
						<tr valign="top">
							<th scope="row"><?php _e( 'Pre-check the checkbox?', 'mailchimp-for-wp' ); ?></th>
							<td class="nowrap">
								<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][precheck]" value="1" <?php checked( $opts['precheck'], 1 ); ?> /> <?php _e( 'Yes', 'mailchimp-for-wp' ); ?></label> &nbsp;
								<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][precheck]" value="0" <?php checked( $opts['precheck'], 0 ); ?> /> <?php _e( 'No', 'mailchimp-for-wp' ); ?></label>
								<p class="help"><?php _e( 'Select "yes" if the checkbox should be pre-checked.', 'mailchimp-for-wp' ); ?></p>
							</td>
					<?php } // end if UI precheck ?>

					<?php if( $integration->has_ui_element( 'css' ) ) { ?>
						<tr valign="top">
							<th scope="row"><?php _e( 'Load some default CSS?', 'mailchimp-for-wp' ); ?></th>
							<td class="nowrap">
								<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][css]" value="1" <?php checked( $opts['css'], 1 ); ?> /> <?php _e( 'Yes', 'mailchimp-for-wp' ); ?></label> &nbsp;
								<label><input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][css]" value="0" <?php checked( $opts['css'], 0 ); ?> /> <?php _e( 'No', 'mailchimp-for-wp' ); ?></label>
								<p class="help"><?php _e( 'Select "yes" if the checkbox appears in a weird place.', 'mailchimp-for-wp' ); ?></p>
							</td>
						</tr>
					<?php } // end if UI css ?>

					<?php if( $integration->has_ui_element( 'double_optin' ) ) { ?>
						<tr valign="top">
							<th scope="row"><?php _e( 'Double opt-in?', 'mailchimp-for-wp' ); ?></th>
							<td class="nowrap">
								<label>
									<input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][double_optin]" value="1" <?php checked( $opts['double_optin'], 1 ); ?> />
									<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
								</label> &nbsp;
								<label>
									<input type="radio" id="mc4wp_checkbox_double_optin_0" name="mc4wp_integrations[<?php echo $integration->slug; ?>][double_optin]" value="0" <?php checked( $opts['double_optin'], 0 ); ?> />
									<?php _e( 'No', 'mailchimp-for-wp' ); ?>
								</label>
								<p class="help">
									<?php _e( 'Select "yes" if you want people to confirm their email address before being subscribed (recommended)', 'mailchimp-for-wp' ); ?>
								</p>
							</td>
						</tr>
					<?php } // end if UI double_optin ?>

					<?php if( $integration->has_ui_element( 'send_welcome' ) ) { $enabled = ! $opts['double_optin']; ?>
					<tr id="mc4wp-send-welcome"  valign="top" style="<?php if( ! $enabled ) { echo 'display:none;'; } ?>">
						<th scope="row"><?php _e( 'Send Welcome Email?', 'mailchimp-for-wp' ); ?></th>
						<td class="nowrap">
							<label for="mc4wp_checkbox_send_welcome_1">
								<input type="radio" id="mc4wp_checkbox_send_welcome_1" name="mc4wp_integrations[<?php echo $integration->slug; ?>][send_welcome]" value="1" <?php checked( $opts['send_welcome'], 1 ); ?> />
								<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
							</label> &nbsp;
							<label for="mc4wp_checkbox_send_welcome_0">
								<input type="radio" id="mc4wp_checkbox_send_welcome_0" name="mc4wp_integrations[<?php echo $integration->slug; ?>][send_welcome]" value="0" <?php checked( $opts['send_welcome'], 0 ); ?> />
								<?php _e( 'No', 'mailchimp-for-wp' ); ?>
							</label>
							<p class="help"><?php _e( 'Select "yes" if you want to send your lists Welcome Email if a subscribe succeeds (only when double opt-in is disabled).', 'mailchimp-for-wp' ); ?></p>
						</td>
					</tr>
					<?php } // end if UI send_welcome ?>

					<?php if( $integration->has_ui_element( 'update_existing' ) ) { ?>
					<tr valign="top">
						<th scope="row"><?php _e( 'Update existing subscribers?', 'mailchimp-for-wp' ); ?></th>
						<td class="nowrap">
							<label>
								<input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][update_existing]" value="1" <?php checked( $opts['update_existing'], 1 ); ?> />
								<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
							</label> &nbsp;
							<label>
								<input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][update_existing]" value="0" <?php checked( $opts['update_existing'], 0 ); ?> />
								<?php _e( 'No', 'mailchimp-for-wp' ); ?>
							</label>
							<p class="help"><?php _e( 'Select "yes" if you want to update existing subscribers with the data that is sent.', 'mailchimp-for-wp' ); ?></p>
						</td>
					</tr>
					<?php } // end if UI update_existing ?>

					<?php if( $integration->has_ui_element( 'replace_interests' ) ) { $enabled = $opts['update_existing']; ?>
						<tr valign="top" style="<?php if( ! $enabled ) { echo 'display: none;'; } ?>">
							<th scope="row"><?php _e( 'Replace interest groups?', 'mailchimp-for-wp' ); ?></th>
							<td class="nowrap">
								<label>
									<input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][replace_interests]" value="1" <?php checked( $opts['replace_interests'], 1 ); ?> />
									<?php _e( 'Yes', 'mailchimp-for-wp' ); ?>
								</label> &nbsp;
								<label>
									<input type="radio" name="mc4wp_integrations[<?php echo $integration->slug; ?>][replace_interests]" value="0" <?php checked( $opts['replace_interests'], 0 ); ?> />
									<?php _e( 'No', 'mailchimp-for-wp' ); ?>
								</label>
								<p class="help">
									<?php _e( 'Select "yes" if you want to replace the interest groups with the groups provided instead of adding the provided groups to the member\'s interest groups (only when updating a subscriber).', 'mailchimp-for-wp' ); ?>
								</p>
							</td>
						</tr>
					<?php } // end if UI replace_interests ?>

					</tbody>
				</table>

				<?php
				do_action( 'mc4wp_admin_after_integration_settings', $integration );
				do_action( 'mc4wp_admin_after_' . $integration->slug . '_integration_settings' );
				?>

				<?php submit_button(); ?>

			</form>


		</div>

		<!-- Sidebar -->
		<div class="sidebar col col-2 col-sm-6">
			<?php include dirname( __FILE__ ) . '/parts/admin-sidebar.php'; ?>
		</div>

	</div>

	<?php if( isset( $_GET['old'] ) ) { ?>



		<tr valign="top" id="woocommerce-settings" <?php if( ! $general_opts['show_at_woocommerce_checkout'] ) { ?>style="display: none;"<?php } ?>>
			<th scope="row"><?php _e( 'WooCommerce checkbox position', 'mailchimp-for-wp' ); ?></th>
			<td class="nowrap">
				<select name="mc4wp_integrations[general][woocommerce_position]">
					<option value="billing" <?php selected( $general_opts['woocommerce_position'], 'billing' ); ?>><?php _e( 'After the billing details', 'mailchimp-for-wp' ); ?></option>
					<option value="order" <?php selected( $general_opts['woocommerce_position'], 'order' ); ?>><?php _e( 'After the additional information', 'mailchimp-for-wp' ); ?></option>
				</select>
				<p class="help">
					<?php _e( 'Choose the position for the checkbox in your WooCommerce checkout form.', 'mailchimp-for-wp' ); ?>
				</p>
			</td>
		</tr>


	<?php } // end of old ?>

</div>

<script>
	(function($) {
		var $toggles = $('.integration-toggles-wrap input');
		var $settings = $('.integration-toggled-settings');
		$toggles.change(toggleSettings);

		function toggleSettings() {
			var enabled = $toggles.filter(':checked').val() > 0;
			var opacity = enabled ? '1' : '0.5';
			$settings.css( 'opacity', opacity );
		}
	})(window.jQuery);
</script>