<?php
/**
 * Plugin Name: One-Off Emails For WooCommerce
 * Plugin URI:
 * Description: Send a single email with custom content to anybody using the WooCommerce template.
 * Author: Miller Media
 * Author URI: https://mattmiller.ai
 * Depends:
 * Version:           1.3.1
 * WC tested up to: 9.6
 * Requires PHP: 7.4
 * Text Domain: one-off-emails-for-woocommerce
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WOOE_PLUGIN_VERSION', '1.3.1' );

include_once('classes/Plugin.php');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
	$settings_link = '<a href="' . admin_url('admin.php?page=wooe-menu') . '">' . __('Settings', 'one-off-emails-for-woocommerce') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
});

$WooOneOffEmails = new WooOneOffEmails();
