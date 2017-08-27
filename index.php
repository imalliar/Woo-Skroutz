<?php 
// Send the headers
//header('Content-type: text/xml');
//header('Pragma: public');
//header('Cache-control: private');
//header("Content-Disposition: attachment; filename=skroutz.xml");
//header('Expires: -1');
require_once("../../../wp-load.php");

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><webstore/>');
$now = date('Y-n-j G:i');
$xml->addChild('created_at', "$now");
global $wpdb;

$query =$wpdb->prepare(  "SELECT * FROM " . $wpdb->prefix . "posts WHERE `post_type` LIKE 'product' AND `post_status` LIKE 'publish'",0);
$result = $wpdb->get_results($query);
$options = get_option(WOO_SKROUTZ_SETTINGS_PAGE); ?>
print_r($options);
die;


echo $xml->asXML();




