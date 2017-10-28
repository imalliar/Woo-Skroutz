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

define('__ROOT__', dirname(__FILE__)); 

$plugin_data = get_file_data(__FILE__, 
    array(
        'name' => 'Plugin Name', 
        'version' => 'Version', 
        'text' => 'Text Domain'));

require_once plugin_dir_path(__FILE__) . '/public/common.php';

add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    // You can add other logic here too
    return $hide_empty;
}

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



if (is_admin()) {
    require_once WOO_SKROUTZ_PATH . '/admin/class-ws-admin.php';
    if (!defined('WSkroutz_Admin')) {
        $admin = new WSkroutz_Admin();
        $admin->init();
    }
} else {
    
}