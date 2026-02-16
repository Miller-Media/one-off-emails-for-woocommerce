<?php
/**
 * Plugin Name: One-Off Emails For WooCommerce
 * Plugin URI:
 * Description: Send a single email with custom content to anybody using the WooCommerce template.
 * Author: Miller Media
 * Author URI: www.millermedia.io
 * Depends:
 * Version:           1.3.0
 * WC tested up to: 9.6
 * Text Domain: one-off-emails-for-woocommerce
 * Domain Path: /languages
 */

define( 'WOOE_PLUGIN_VERSION', '1.3.0' );

include_once('classes/Plugin.php');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
	$settings_link = '<a href="' . admin_url('admin.php?page=wooe-menu') . '">' . __('Settings', 'one-off-emails-for-woocommerce') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
});

$WooOneOffEmails = new WooOneOffEmails();
