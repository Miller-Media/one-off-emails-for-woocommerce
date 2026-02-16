<?php

// The email fields.
$settings = array (
	__('WooCommerce One-Off Emails', 'one-off-emails-for-woocommerce') => array (
		'fields' => array (
			array (
				'name' => 'wooe_to',
				'type' => 'text',
				'title' => __('To: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => 'example001@email.com, example002@email.com',
				'required' => true,
			),
			array (
				'name' => 'wooe_reply_to_name',
				'type' => 'text',
				'title' => __('Reply To Name: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => get_option( 'woocommerce_email_from_name' ),
				'required' => false,
			),
			array (
				'name' => 'wooe_reply_to_email',
				'type' => 'email',
				'title' => __('Reply To Email: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => get_option( 'woocommerce_email_from_address' ),
				'required' => false,
			),
			array (
				'name' => 'wooe_subject',
				'type' => 'text',
				'title' => __('Subject: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => '',
				'required' => true,
			),
			array (
				'name' => 'wooe_heading',
				'type' => 'text',
				'title' => __('Heading: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => '',
				'required' => true,
			),
			array (
				'name' => 'wooe_message',
				'type' => 'textarea',
				'title' => __('Message: ', 'one-off-emails-for-woocommerce'),
				'description' => '',
				'placeholder' => '',
				'required' => true,
			),
		),
	),
);
?>
	<form name="wooe_settings" method="post" action="">
		<table>
			<tbody>
			<?php
			foreach ($settings as $section => $fields) {
				?>
				<tr class="tr-section-title">
					<td><h2><?php echo esc_html($section); ?></h2><br></td>
				</tr>
				<?php
				foreach ($fields['fields'] as $field => $data) {
					if ($data['type'] == 'textarea') {
						?>
						<tr>
							<td valign="top"><strong><label for="<?php echo esc_attr($data['name']); ?>"><?php echo esc_html($data['title']); ?><?php echo $data['required'] ? '<sup class="required">*</sup>' : ''; ?>
								</strong></label>
								<?php
								if (isset($data['description']) && $data['description']) {
									?>
									<br><?php echo esc_html($data['description']); ?>
									<?php
								}
								?>
							</td>
							<td><?php
								$content = '';
								$editor_id = $data['name'];
								wp_editor($content, $editor_id);
								?>
							</td>
						</tr>
						<?php
					} else {
						$placeholder = (isset($data['placeholder']) ? $data['placeholder'] : '');
						?>
						<tr>
							<td><strong><label for="<?php echo esc_attr($data['name']); ?>"><?php echo esc_html($data['title']); ?><?php echo $data['required'] ? '<sup class="required">*</sup>' : ''; ?>
								</strong></label>
								<?php
								if (isset($data['description']) && $data['description']) {
									?>
									<br><?php echo esc_html($data['description']); ?>
									<?php
								}
								?>
							</td>
							<td><input id="<?php echo esc_attr($data['name']); ?>" type="<?php echo esc_attr($data['type']); ?>" name="<?php echo esc_attr($data['name']); ?>" value="" placeholder="<?php echo esc_attr($placeholder); ?>"></td>
						</tr>
						<?php
					}
				}
			}
			?>
			</tbody>
		</table>
		<button id="wooe_send_email" class="button button-primary"><?php echo esc_html__('Send', 'one-off-emails-for-woocommerce'); ?></button>
		<button id="wooe_preview_email" class="button button-default"><?php echo esc_html__('Preview', 'one-off-emails-for-woocommerce'); ?></button>
		<div id="wooe_ajax_res_send_email"></div>
		<div id="wooe_preview_window"></div>
	</form>

	<hr />
	<h2><?php esc_html_e('Plugin Settings', 'one-off-emails-for-woocommerce'); ?></h2>
	<form method="post" action="options.php">
		<?php settings_fields('wooe_settings_group'); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php esc_html_e('Remove all plugin data when deleted', 'one-off-emails-for-woocommerce'); ?></th>
				<td>
					<label>
						<input type="checkbox" name="wooe_delete_data_on_uninstall" value="1" <?php checked(get_option('wooe_delete_data_on_uninstall'), '1'); ?>>
						<?php esc_html_e('Check this box if you want all plugin settings and data to be removed when the plugin is deleted.', 'one-off-emails-for-woocommerce'); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php submit_button(esc_html__('Save Settings', 'one-off-emails-for-woocommerce')); ?>
	</form>
<?php
