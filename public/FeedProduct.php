<?php

defined('ABSPATH') or die("Restricted access!");


class FeedProduct {
    public $uniqueId;
    public $title;
    public $productLink;
    public $imageLink;
    public $additionalImageLink;
    public $category;
    public $price;
    public $availability;
    public $manufacturer;
    public $mpn;
    public $size;
    public $weight;
    public $inStock;
    public $shippingCost;
    public $color;
    public $iban;
    
    public function __construct($id) {
        $this->uniqueId=$id;
    }
    
}
