<?php

function get_default_options_settings() {
    $defaults = array(
        'delivery_days_in_stock' => 1,
        'delivery_days_out_of_stock' => 5,
        'manufacturer_slag' => ''
    );
    return $defaults;
}

function woo_skroutz_define_constants($constant_name, $value) {
    $constant_name = 'WOO_SKROUTZ_' . $constant_name;
    if (!defined($constant_name))
        define($constant_name, $value);
}

