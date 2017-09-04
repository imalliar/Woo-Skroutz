<?php

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
    public $barcode;
    public $size;
    public $weight;
    public $inStock;
    public $shippingCost;
    public $color;
    
    public function __construct($id) {
        $this->uniqueId=$id;
    }
    
}
