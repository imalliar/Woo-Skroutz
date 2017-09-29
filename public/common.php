<?php

function get_delivery_messages($msg='') {
    $delivery_messages = array(
        1 => __('Available in store / Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
        2 => __('Delivery 1 to 3 days', WOO_SKROUTZ_TEXT),
        3 => __('Delivery 4 to 10 days', WOO_SKROUTZ_TEXT),
        4 => __('Upon order', WOO_SKROUTZ_TEXT),
        5 => __('Do not show', WOO_SKROUTZ_TEXT));
    
    return empty($msg) ? $delivery_messages : $delivery_messages[$msg];
}

function get_options_fields($field='') {
    $fields = array(
        'inStock' => 'delivery_days_in_stock', 
        'outOfStock' => 'delivery_days_out_of_stock', 
        'manufacturer' => 'manufacturer_slag',
        'iban' => 'iban_slag'
    );    
    
    return empty($field) ? $fields : $fields[$field];
}

function get_default_options_settings() {
    $defaults = array(
        'delivery_days_in_stock' => 1,
        'delivery_days_out_of_stock' => 5,
        'manufacturer_slag' => '',
        'iban_slag' => ''
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


