<?php
/**
 * Uninstall handler for One-Off Emails for WooCommerce.
 *
 * @package One_Off_Emails_For_WooCommerce
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$delete_data = get_option( 'wooe_delete_data_on_uninstall' );

if ( ! $delete_data ) {
	return;
}

// Delete plugin options.
delete_option( 'wooe_delete_data_on_uninstall' );
