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

function get_category_ancestors_ids($cid) {
    $result=array();
    while($cid>0) {
        $category = get_product_category_by_id($cid);
        $result[]=$cid;
        $cid=$category->parent;
    }
    return $result;
}

function get_product_attribute($product, $slug) {
    $options = get_option(WOO_SKROUTZ_SETTINGS_PAGE);
    if ($options === false || empty($options)) $options = get_default_options_settings();
    $result='';
    
    $terms = wc_get_product_term_ids($product->get_id(), $options[$slug]);
    if(!empty($terms)) {
        $term_names=array();
        foreach ($terms as $term) {
            $man = get_term($term);
            $term_names[]=$man->name;
        }
        $result = implode(",", $term_names);
    }
    if(empty($result)) {
        $attrs = $product->get_attributes();
        
        if(!empty($attrs)) {
            $attr_names=array();
            foreach ($attrs as $attr) {
                $attr_options = $attr->get_options();
                foreach ($attr_options as $attr_option) {
                    $attr_name = get_term($attr_option);
                    if($attr_name) {
                        $taxonomy = $attr_name->taxonomy;
                        if($attr_name->taxonomy=='pa_' . $options[$slug] || $attr_name->taxonomy==$options[$slug])
                            $attr_names[] = $attr_name->name;
                    }
                }               
            }
            $result = implode(",", $attr_names);
        }
    }
    return $result;
}

function getProducts() {
    global $wpdb;
    
    $options = get_option(WOO_SKROUTZ_SETTINGS_PAGE);
    if ($options === false || empty($options))
        $options = get_default_options_settings();
        $inStock = $options['delivery_days_in_stock'];
        $outOfStock = $options['delivery_days_out_of_stock'];
        
        $full_product_list = array();
        $loop = new WP_Query(array('post_type' => array('product', 'product_variation'), 'post_status' => 'publish', 'posts_per_page' => -1));
}


