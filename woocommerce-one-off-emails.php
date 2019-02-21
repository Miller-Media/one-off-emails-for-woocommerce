<?php
/**
 * Plugin Name: WooCommerce One-Off Emails
 * Plugin URI:
 * Description: Send an email with custom content to anybody using the WooCommerce template.
 * Author: Miller Media ( Michael Robinson )
 * Author URI: www.millermedia.io
 * Depends:
 * Version: 1.0.2
 * WC tested up to: 3.2.6
 */

include_once('classes/Plugin.php');
include_once('classes/Settings.php');

$WooOneOffEmails = new WooOneOffEmails();