<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Solution: https://stackoverflow.com/questions/6260224/how-to-write-cdata-using-simplexmlelement
class SimpleXMLElementExtended extends SimpleXMLElement {
    public function addCData($cdata_text) {
        $node = dom_import_simplexml($this); 
        $no   = $node->ownerDocument; 
        $node->appendChild($no->createCDATASection($cdata_text)); 
    }   
}

