<?php

function get_delivery_messages() {
    $delivery_messages = array(
        1 => __('Available in store / Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
        2 => __('Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
        3 => __('Delivery 4 to 10 days', WOO_SKROUTZ_TEXT),
        4 => __('Upon order', WOO_SKROUTZ_TEXT),
        5 => __('Do not show', WOO_SKROUTZ_TEXT));
    
    return $delivery_messages;
}

function get_default_options_settings() {
    $defaults = array(
        'delivery_days_in_stock' => 1,
        'delivery_days_out_of_stock' => 5,
        'manufacturer_slag' => ''
    );
    return $defaults;
}

function woo_skroutz_define_constants($constant_name, $value) {
    $constant_name = 'WOO_SKROUTZ_' . $constant_name;
    if (!defined($constant_name))
        define($constant_name, $value);
}

function get_product_category_by_id($cat_id) 
{
    $category = get_term_by('id', $cat_id, 'product_cat');
    return $category; 
}

function get_category_ancestors($cid, $seperator=' > '){
    $result = array();    
    while ($cid>0) {
        $category = get_product_category_by_id($cid);
        if($category && !empty($category->name)) {
            $result[] = $category->name;
        }
        $cid = $category->parent;
    } 
    
    return implode($seperator, array_reverse($result));
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
