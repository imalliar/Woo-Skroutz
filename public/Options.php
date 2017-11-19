<?php 

defined('ABSPATH') or die("Restricted access!");


class Options {
    protected $fields = array(
        'inStock' => 'delivery_days_in_stock',
        'outOfStock' => 'delivery_days_out_of_stock',
        'manufacturer' => 'manufacturer_slug',
        'iban' => 'iban_slug',
        'color' => 'color_slug',
        'size' => 'size_slug',
        'base_address' => 'base_address',
        'country' => 'country',
        'state' => 'state',
        'zip' => 'zip',
        'shipping' => 'shipping_method'
    );
    
    protected $defaults = array(
        'delivery_days_in_stock' => 1,
        'delivery_days_out_of_stock' => 5,
        'manufacturer_slug' => '',
        'iban_slug' => '',
        'color_slug' => '',
        'size_slug' => '',
        'base_address' => true,
        'country' => '',
        'state' => '',
        'zip' => '',
        'shipping_method' => ''
    );
    
    protected $options;
    
    public function __construct(array $defaults=[]) {
        $this->defaults = array_merge($this->defaults, $defaults);
    }
    
    public static function getInstance(array $defaults=[]) {
        return new Options(array_merge($this->defaults, $defaults));
    }
    
    public function get($name) {
        
    }
}
?>