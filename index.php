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
require_once './woo-skroutz.php';
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
    if ($options === false || empty($options))
        $options = get_default_options_settings();
    $manufacturer = $options['manufacturer_slag'];

    $loop = new WP_Query(array('post_type' => array('product'), 'posts_per_page' => -1));

    while ($loop->have_posts()) :
        $loop->the_post();
        $theid = get_the_ID();
        $thetitle = get_the_title();
        $product = wc_get_product($theid);    
        
        print_r($product);
        if($product instanceof WC_Product_Variable) {
            $children = $product->get_children();
            foreach ($children as $child) {
                $variation = wc_get_product($child);
                $var_product = new FeedProduct($child);
                $var_product->title = $variation->get_title();
                $full_product_list[]=$var_product;
            }
            
            $variations = $product->get_available_variations();
            print_r($variations);
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
            $feed_product->inStock = $product->is_in_stock();
            if($manufacturer) {
                $terms =  wp_get_post_terms($product->get_id(), $manufacturer);
                
            }
            print_r(wc_get_product_term_ids($product->get_id(), 'pwb-brand'));
            if(!empty($terms)) {
                
            }
            
            
            $full_product_list[] = $feed_product;
        }
        
    endwhile;
    wp_reset_query();
    // sort into alphabetical order, by title
    sort($full_product_list);
    print_r($full_product_list);
    die;
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

/*
  $query =$wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "posts WHERE `post_type` LIKE %s AND `post_status` LIKE %s", 'product', 'publish');
  $result = $wpdb->get_results($query);


  foreach ($result as $index => $prod) {

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $prod->ID, '_stock_status');
  $stockstatus = $wpdb->get_results($sql);
  if ((strcmp($stockstatus[0]->meta_value, "outofstock") == 0)& ($outOfStock==5) ){
  continue;
  }

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $prod->ID, 'hidden');
  $hidden =$wpdb->get_results($sql);
  if (strcmp($hidden[0]->meta_value, "true") == 0) {
  continue;
  }

  $sql = $wpdb->prepare(  "SELECT * 	FROM " . $wpdb->prefix . "postmeta WHERE post_id = %d AND meta_key LIKE %s;", $prod->ID, '_price');
  $meta = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT `meta_value` FROM " . $wpdb->prefix . "postmeta	WHERE `post_id` = %d AND meta_key LIKE %s ", $prod->ID, '_thumbnail_id');
  $imagelinks = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT `guid` FROM " . $wpdb->prefix . "posts WHERE `id` = %d AND post_type LIKE %s AND `post_mime_type` LIKE %s ;", $imagelinks[0]->meta_value, 'attachment', 'image/%%');
  $images = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $prod->ID, '_sku');
  $skus = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "term_relationships as tr , " . $wpdb->prefix . "term_taxonomy  as tt WHERE `object_id` = %d and tr.term_taxonomy_id =tt.term_taxonomy_id and tt.taxonomy like %s ", $prod->ID, 'product_cat');
  $categories = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $prod->ID, 'mpn');
  $mpn = $wpdb->get_results($sql);

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s ;", $prod->ID, '_product_attributes');
  $attr = $wpdb->get_results($sql);

  if (strcmp($attr[0]->meta_value, "a:0:{}")) {
  $sizestring = "";
  $childs = $wpdb->prepare(  "SELECT `id`	FROM " . $wpdb->prefix . "posts	WHERE `post_parent` = %d AND post_type LIKE %s ;", $prod->ID, 'product_variation');

  $childs = $wpdb->get_results($childs);
  foreach ($childs as $child) {
  $sstock = $wpdb->prepare(  "SELECT *  FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $child->id, '_stock');
  $sstock = $wpdb->get_results($sstock);
  if ($sstock[0]->meta_value > 0 || $sstock[0]->meta_value==NULL ) {
  $sizes = $wpdb->prepare(  "SELECT *  FROM " . $wpdb->prefix . "postmeta WHERE `post_id` = %d AND `meta_key` LIKE %s;", $child->id, 'attribute_pa_size');
  $attrsize = $wpdb->get_results($sizes);
  $sizename = $wpdb->prepare(  "SELECT *  FROM " . $wpdb->prefix . "terms WHERE `slug` LIKE %s ;", $attrsize[0]->meta_value);
  $sizename = $wpdb->get_results($sizename);
  $sizestring = $sizename[0]->name . ", " . $sizestring;
  }
  }
  }

  $man=null;

  if (strcmp($attr[0]->meta_value, "a:0:{}")) {

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "term_relationships as tr, " . $wpdb->prefix . "term_taxonomy as tt , " . $wpdb->prefix . "terms as t WHERE tr.object_id = %d and tt.term_taxonomy_id= tr.term_taxonomy_id and tt.term_id=t.term_id and tt.taxonomy like %s;", $prod->ID, 'pa_manufacturer');
  $man = $wpdb->get_results($sql);
  }

  if(count($man) == 0) {
  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "term_relationships as tr, " . $wpdb->prefix . "term_taxonomy as tt , " . $wpdb->prefix . "terms as t WHERE tr.object_id = %d and tt.term_taxonomy_id= tr.term_taxonomy_id and tt.term_id=t.term_id and tt.taxonomy like %s;", $prod->ID, 'product_brand');
  $man = $wpdb->get_results($sql);
  }

  $sql = $wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "term_relationships as tr, " . $wpdb->prefix . "term_taxonomy as tt , " . $wpdb->prefix . "terms as t WHERE tr.object_id = %d and tt.term_taxonomy_id= tr.term_taxonomy_id and tt.term_id=t.term_id and tt.taxonomy like %s;", $prod->ID, 'pa_color');
  $color = $wpdb->get_results($sql);

  $last_key = end(array_keys($categories));

  foreach ($categories as $index2 => $cat) {

  }

  $product = $products->addChild('product');
  $product->name = NULL;
  //$product->name->addCData($title);
  $product->addChild('uid', $prod->ID);
  }
 */

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