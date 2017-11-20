<?php 

defined('ABSPATH') or die("Restricted access!");

class Options {
        
    public static $fields = array(
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
        'country' => '',
        'state' => '',
        'zip' => '',
        'base_address' => true,
        'shipping_method' => 'flat_rate'
    );
    
    protected $options;
    
    function __construct(array $defaults=[]) {
        $this->defaults = array_merge($this->defaults, $defaults);
        $options = get_option(WOO_SKROUTZ_SETTINGS_PAGE);
        
        if ( $options === false ) {
            $this->options = $this->defaults;
            $this->save();
        } else {
            $this->options = (array)$options;
        }
        
        $this->options = array_merge( $this->defaults, $this->options );
    }
    
    public static function getFields() {
        return Options::$fields;
    }
    
    public function save() {
        $this->options= apply_filters(WOO_SKROUTZ_SAVE_OPIONS, $this->options);
        return update_option(WOO_SKROUTZ_SETTINGS_PAGE, $this->options);
    }
        
    public function get($name) {
        if($this->exists($name)) {
            return apply_filters( WOO_SKROUTZ_GET_OPTION, $this->options[ $name ] );            
        }
        throw new \ErrorException('Invalid option in ' . __METHOD__ );
    }
       
    public function exists($name) {
        return apply_filters( WOO_SKROUTZ_OPTION_EXISTS, isset( $this->options[ $name ] ) );
    }
    
    public function set($name, $value) {
        if ( $this->exists( $name ) ) {
            $this->options[ $name ] = $value;
            
            return $this->save();
        }
        throw new \ErrorException( 'Invalid option in ' . __METHOD__ );
    }
    
    public function getInStock() {
        return $this->get(Options::$fields['inStock']);
    }
    
    public function setInStock($value) {
        $this->set(Options::$fields['inStock'], $value); 
    }
    
    public function getOutOfStock() {
        return $this->get(Options::$fields['outOfStock']);
    }
    
    public function setOutOfStock($value) {
        $this->set(Options::$fields['outOfStock'], $value);
    }
    
    public function getManufacturer() {
        return $this->get(Options::$fields['manufacturer']);
    }
    
    public function setManufacturer($value) {
        $this->set(Options::$fields['manufacturer'], $value);
    }
    
    public function getIban() {
        return $this->get(Options::$fields['iban']);
    }
    
    public function setIban($value) {
        $this->set(Options::$fields['iban'], $value);
    }
    
    public function getColor() {
        return $this->get(Options::$fields['color']);
    }
    
    public function setColor($value) {
        $this->set(Options::$fields['color'], $value);
    }
    
    public function getSize() {
        return $this->get(Options::$fields['size']);
    }
    
    public function setSize($value) {
        $this->set(Options::$fields['size'], $value);
    }
    
    public function getBaseAddress() {
        return $this->get(Options::$fields['base_address']);
    }
    
    public function setBaseAddress($value) {
        $this->set(Options::$fields['base_address'], $value);
    }
    
    public function getCountry() {
        return $this->get(Options::$fields['country']);
    }
    
    public function setCountry($value) {
        $this->set(Options::$fields['country'], $value);
    }
    
    public function getState() {
        return $this->get(Options::$fields['state']);
    }
    
    public function setState($value) {
        $this->set(Options::$fields['state'], $value);
    }

    public function getShipping() {
        return $this->get(Options::$fields['shipping']);
    }
    
    public function setShipping($value) {
        $this->set(Options::$fields['shipping'], $value);
    }
    
    public function getZip() {
        return $this->get(Options::$fields['zip']);
    }
    
    public function setZip($value) {
        $this->set(Options::$fields['zip'], $value);
    }
    
}
?>