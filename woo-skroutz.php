<?php

/*
  Plugin Name: Woo Skroutz
  Description: A plugin which provides skroutz xml feed on the fly based on the current data of the store
  Version: 1.0.0
  Author: Jacob Malliaros
  Text Domain: woo-skroutz
  Domain Path: /languages
  License: GPL2
 */


// Exit if accessed directly
defined('ABSPATH') or die("Restricted access!");

function woo_skroutz_define_constants($constant_name, $value) {
    $constant_name = 'WOO_SKROUTZ_' . $constant_name;
    if (!defined($constant_name))
        define($constant_name, $value);
}

$plugin_data = get_file_data(__FILE__, array('name' => 'Plugin Name', 'version' => 'Version', 'text' => 'Text Domain'));

woo_skroutz_define_constants('DIR', dirname(plugin_basename(__FILE__)));
woo_skroutz_define_constants('BASE', plugin_basename(__FILE__));
woo_skroutz_define_constants('URL', plugin_dir_url(__FILE__));
woo_skroutz_define_constants('PATH', plugin_dir_path(__FILE__));
woo_skroutz_define_constants('SLUG', dirname(plugin_basename(__FILE__)));
woo_skroutz_define_constants('NAME', $plugin_data['name']);
woo_skroutz_define_constants('VERSION', $plugin_data['version']);
woo_skroutz_define_constants('TEXT', $plugin_data['text']);
woo_skroutz_define_constants('PREFIX', 'woo-skroutz');
woo_skroutz_define_constants('SETTINGS_PAGE', 'wskroutz');
woo_skroutz_define_constants('SETTINGS_NAME', 'woo_skroutz_options_settings');

$delivery_messages = array(
    1 => __('Available in store / Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
    2 => __('Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
    3 => __('Delivery 4 to 10 days', WOO_SKROUTZ_TEXT),
    4 => __('Upon order', WOO_SKROUTZ_TEXT),
    5 => __('Do not show', WOO_SKROUTZ_TEXT));

function get_default_options_settings() {
    $defaults = array(
        'delivery_days_in_stock' => 1,
        'delivery_days_out_of_stock' => 5
    );
    return $defaults;
}

if (is_admin()) {
    require_once WOO_SKROUTZ_PATH . '/admin/class-ws-admin.php';
    if (!defined('WSkroutz_Admin')) {
        $admin = new WSkroutz_Admin($delivery_messages);
        $admin->init();
    }
} else {
    
}