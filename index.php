<?php 
// Send the headers
header('Content-type: text/xml');
header('Pragma: public');
header('Cache-control: private');
//header("Content-Disposition: attachment; filename=skroutz.xml");
header('Expires: -1');
require_once("../../../wp-load.php");
require_once './woo-skroutz.php';

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><webstore/>');
$now = date('Y-n-j G:i');
$xml->addChild('created_at', "$now");
global $wpdb;
$options = get_option(WOO_SKROUTZ_SETTINGS_PAGE);
if($options===false || empty($options)) $options = get_default_options_settings();
$inStock = $options['delivery_days_in_stock'];
$outOfStock = $options['delivery_days_out_of_stock'];

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
    
    
    
    
}


echo $xml->asXML();




