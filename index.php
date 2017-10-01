<?php

// Send the headers
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');

if (isset($_GET['d']) && $_GET['d'] == 1) {
    header("Content-Disposition: attachment; filename=skroutz.xml");
}

header('Expires: -1');
require_once("../../../wp-load.php");
require_once './public/common.php';
require_once './public/SimpleXMLElementExtended.php';
require_once './public/FeedProduct.php';

function createXML($feed_products) {
    $xml = new SimpleXMLElementExtended('<?xml version="1.0" encoding="utf-8"?><webstore/>');
    $now = date('Y-n-j G:i');
    $xml->addChild('created_at', "$now");
    $products = $xml->addChild('products');

    foreach ($feed_products as $feed_product) {
        $product = $products->addChild('product');
        $id = $product->addChild('id', $feed_product->$uniqueId);
    }
}


function get_woocommerce_product_list() {
    $full_product_list = array();
    $options = get_option(WOO_SKROUTZ_SETTINGS_PAGE);
    if ($options === false || empty($options)) $options = get_default_options_settings();
    $manufacturer = $options['manufacturer_slug'];

    $loop = new WP_Query(array('post_type' => array('product'), 'posts_per_page' => -1));

    while ($loop->have_posts()) :
        $loop->the_post();
        $theid = get_the_ID();
        $thetitle = get_the_title();
        $product = wc_get_product($theid);    
        
        if($product instanceof WC_Product_Variable) {
            $children = $product->get_children();
            foreach ($children as $child) {
                $variation = wc_get_product($child);
                $var_product = new FeedProduct($child);
                $var_product->title = $variation->get_title();
                $full_product_list[]=$var_product;
            }
            
            $variations = $product->get_available_variations();
            foreach ($variations as $variation) {
                if(!$variation['variation_is_active'] || !$variation['variation_is_visible']) continue;
                
                $var_product = new FeedProduct($variation['variation_id']);
                $var_product->title=$thetitle;
                $var_product->mpn=$variation['sku'];
                
                $full_product_list[]=$var_product;
            }
        } else {
            $feed_product = new FeedProduct($theid);
            // add product to array but don't add the parent of product variations
            $feed_product->uniqueId = $theid;
            $feed_product->title = $thetitle;
            $feed_product->mpn = $product->get_sku();
            $feed_product->price = $product->get_sale_price();
            $feed_product->weight=$product->get_weight();
            $imageId = $product->get_image_id();            
            if($imageId) $imagearray = wp_get_attachment_image_src($imageId, 'full');
            if(!empty($imagearray)) $feed_product->imageLink = $imagearray[0];
            $additionalImages = $product->get_gallery_image_ids();
            $feed_product->additionalImageLink=[];
            foreach ($additionalImages as $aimgid) {
                $aimg = wp_get_attachment_image_src($aimgid, 'full');
                if(!empty($aimg)) $feed_product->additionalImageLink[]=$aimg[0];
            }
            $feed_product->productLink = $product->get_permalink();
            if($product->is_in_stock()) {
                $feed_product->inStock = "Y";
                $feed_product->availability = get_delivery_messages($options[get_options_fields('inStock')]);
            } else {
                $feed_product->inStock = "N";
                $feed_product->availability = get_delivery_messages($options[get_options_fields('outOfStock')]);
            }
            
            $feed_product->manufacturer = get_product_attribute($product, 'manufacturer_slug');
            $feed_product->iban = get_product_attribute($product, 'iban_slug');
            $feed_product->color = get_product_attribute($product, 'color_slug');
            $feed_product->size = get_product_attribute($product, 'size_slug');
            
            $categories_id = $product->get_category_ids();
            if(!empty($categories_id)) {
                $categories = array();
                foreach ($categories_id as $cid) {
                    $categories[] = get_category_ancestors($cid);
                }
                $feed_product->category = implode(",", $categories);
            }
                        
            $full_product_list[] = $feed_product;
        }
        
    endwhile;
    wp_reset_query();
    // sort into alphabetical order, by title
    sort($full_product_list);

    return $full_product_list;
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

get_woocommerce_product_list();

while ($loop->have_posts()) :
    $loop->the_post();
    $theid = get_the_ID();
    if (get_post_type() == 'product_variation') {
        $parent_id = wp_get_post_parent_id($theid);
        $sku = get_post_meta($theid, '_sku', true);
        $title = get_the_title($parent_id);
        if ($sku == '') {
            if ($parent_id == 0) {
                // Remove unexpected orphaned variations.. set to auto-draft
                $false_post = array();
                $false_post['ID'] = $theid;
                $false_post['post_status'] = 'auto-draft';
                wp_update_post($false_post);
                if (function_exists(add_to_debug))
                    add_to_debug('false post_type set to auto-draft. id=' . $theid);
            } else {
                // there's no sku for this variation > copy parent sku to variation sku
                // & remove the parent sku so the parent check below triggers
                $sku = get_post_meta($parent_id, '_sku', true);
                if (function_exists(add_to_debug))
                    add_to_debug('empty sku id=' . $theid . 'parent=' . $parent_id . 'setting sku to ' . $sku);
                update_post_meta($theid, '_sku', $sku);
                update_post_meta($parent_id, '_sku', '');
            }
        }
    } else {
        $sku = get_post_meta($theid, '_sku', true);
        $title = get_the_title();
    }
    $product = $products->addChild('product');
    $product->name = NULL;
    $product->name->addCData($title);
    $product->addChild('uid', $theid);

endwhile;



ob_clean();
$dom = dom_import_simplexml($xml)->ownerDocument;
$dom->formatOutput = true;

print(trim($dom->saveXML()));
?>