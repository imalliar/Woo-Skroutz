<?php
/*
 * Woo Skroutz admin class
 */


// Exit if accessed directly
defined('ABSPATH') or die("Restricted access!");

/**
 * Plugin admin class
 * */
if (!class_exists('WSkroutz_Admin')) {

    global $current_user;
        
    class WSkroutz_Admin {

        private $prefix = WOO_SKROUTZ_PREFIX;
        private $url = WOO_SKROUTZ_URL;
        private $dir = WOO_SKROUTZ_DIR;
        private $text = WOO_SKROUTZ_TEXT;
        private $settings_page = WOO_SKROUTZ_SETTINGS_PAGE;
        private $name = WOO_SKROUTZ_NAME;
        private $fields;
        private $delivery_messages;
        private $current_country;
        private $options;
                
        function __construct() {            
            $this->delivery_messages = get_delivery_messages();
            $this->fields = Options::getFields();
        }

        public function init() {            
            add_action('init', array($this, 'add_textdomain'));
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_options_init'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_filter('plugin_action_links_' . WOO_SKROUTZ_BASE, array($this, 'add_settings_link'));
            add_filter('plugin_row_meta', array($this, 'add_plugin_row_meta'), 10, 2);
            add_filter( 'pre_update_option_country', array($this, 'update_field_country'), 10, 2 );
            add_action( 'wp_ajax_get_states', array($this, 'get_states') );
            
            $this->current_country = '';
            $this->options = new Options();
        }
        
        public function get_states()
        {
            if ( ! is_super_admin() ) {
                wp_die( 'Not allowed' );
            }
            echo json_encode(WC()->countries->get_states( $_GET['current_country'] ));
            die();
        }

        public function add_plugin_row_meta($links, $file) {
            if (strpos($file, 'woo-skroutz') !== false) {
                $new_links = array(
                    'donate' => '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=47RM5G4438SJJ&lc=GR&item_name=Woo%2dSkroutz&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> ' . __('Donate', $this->text) . '</a>'
                );
                $links = array_merge($links, $new_links);
            }
            return $links;
        }

        public function add_settings_link($links) {
            $page = '<a href="' . admin_url('options-general.php?page=' . $this->settings_page) . '">' . __('Settings', $this->text) . '</a>';
            array_unshift($links, $page);
            return $links;
        }

        public function add_textdomain() {
            load_plugin_textdomain($this->text, false, $this->dir . '/languages/');
        }
        
        public function update_field_country($new_value, $old_value) {
            $this->current_country = $new_value;
        }

        public function admin_enqueue_scripts($hook) {            
            $admin_settings_page = 'settings_page_' . $this->settings_page;
            if($hook!=$admin_settings_page) return;
            
            // Load jQuery library
            wp_enqueue_script('jquery');

            // Bootstrap library
            wp_enqueue_style($this->prefix . '-bootstrap-css', $this->url . 'assets/bootstrap/bootstrap.min.css');
            wp_enqueue_style($this->prefix . '-bootstrap-theme-css', $this->url . 'assets/bootstrap/bootstrap-theme.min.css');
            wp_enqueue_script($this->prefix . '-bootstrap-js', $this->url . 'assets/bootstrap/bootstrap.min.js');
            wp_enqueue_script($this->prefix . '-bootstrap-select-js', $this->url . 'assets/bootstrap-select/bootstrap-select.min.js');
            wp_enqueue_style($this->prefix . '-bootstrap-select-css', $this->url . 'assets/bootstrap-select/bootstrap-select.min.css');
            wp_enqueue_script($this->prefix . '-bootstrap-switch-js', $this->url . 'assets/bootstrap-switch/bootstrap-switch.min.js');
            wp_enqueue_style($this->prefix . '-bootstrap-switch-css', $this->url . 'assets/bootstrap-switch/bootstrap-switch.min.css');
            
            //Custom assets
            wp_enqueue_style($this->prefix . '-admin', $this->url . 'assets/css/admin.css');
            wp_enqueue_script($this->prefix . '-admin-js', $this->url . 'assets/js/admin.js');
            
            wp_localize_script($this->prefix . '-admin', 'woo_skroutz_script_vars', array(
                'yes' => __('Yes', $this->text),
                'no' => __('No', $this->text)
            ));
        }
        
        public function sanitize_callback($input) {
            // Create our array for storing the validated options
            $output = array();
            
            // Loop through each of the incoming options
            foreach( $input as $key => $value ) {
                
                // Check to see if the current option has a value. If so, process it.
                if( isset( $input[$key] ) ) {
                    
                    // Strip all HTML and PHP tags and properly handle quoted strings
                    $output[$key] = sanitize_text_field( $input[ $key ] );
                    
                } // end if
                
            } // end foreach
            
            // Return the array processing any additional functions filtered by this action
            return apply_filters( array($this, 'sanitize_callback'), $output, $input );
        }

        public function register_options_init() {
            $section = 'wskroutz_delivery_options_section';
            $taxonomy_section = 'wskroutz_taxonomy_options_section';
            register_setting($this->settings_page, $this->settings_page, array(  'sanitize_callback' => array($this, 'sanitize_callback'), ));         
            
            add_settings_section(
                    $section, __('Delivery settings', $this->text), array($this, 'delivery_settings_section_callback'), $this->settings_page);
            
            add_settings_field(
                    $this->fields['inStock'], __('In Stock Products', $this->text), array($this, 'in_stock_page_render'), $this->settings_page, $section
            );
            add_settings_field(
                    $this->fields['outOfStock'], __('Out Of Stock Products', $this->text), array($this, 'out_of_stock_page_render'), $this->settings_page, $section
            );

            add_settings_section(
                    $taxonomy_section, __('Taxonomy settings', $this->text), array($this, 'taxonomy_settings_section_callback'), $this->settings_page);
            
            add_settings_field(
                    $this->fields['manufacturer'], __('Manufacturer Slug', $this->text), array($this, 'manufacturer_page_render'), $this->settings_page, $taxonomy_section
            );
            
            add_settings_field(
                $this->fields['iban'], __('IBAN Slug', $this->text), array($this, 'iban_page_render'), $this->settings_page, $taxonomy_section
            );
            
            add_settings_field(
                $this->fields['color'], __('Color Slug', $this->text), array($this, 'color_page_render'), $this->settings_page, $taxonomy_section
            );
            
            add_settings_field(
                $this->fields['size'], __('Size Slug', $this->text), array($this, 'size_page_render'), $this->settings_page, $taxonomy_section
            );

            add_settings_section(
                $shipping_section, __('Shipping settings', $this->text), array($this, 'shipping_settings_section_callback'), $this->settings_page);

            add_settings_field(
                $this->fields['base_address'], __('Use Store Base Address', $this->text), array($this, 'base_address_page_render'), $this->settings_page, $shipping_section
            );
            
            add_settings_field(
                $this->fields['country'], __('Country', $this->text), array($this, 'country_page_render'), $this->settings_page, $shipping_section
            );
            
            add_settings_field(
                $this->fields['state'], __('State', $this->text), array($this, 'state_page_render'), $this->settings_page, $shipping_section
            );
            
            add_settings_field(
                $this->fields['zip'], __('Zip', $this->text), array($this, 'zip_page_render'), $this->settings_page, $shipping_section
            );
            
            add_settings_field(
                $this->fields['shipping'], __('Shipping Method', $this->text), array($this, 'shipping_page_render'), $this->settings_page, $shipping_section
            );
        }

        public function add_admin_menu() {
            $page_title = __('Woo Skroutz Settings', $this->text);
            $menu_title = __('Woo Skroutz', $this->text);
            $access_rights = 'manage_options';
            $slug = $this->settings_page;
            add_options_page($page_title, $menu_title, $access_rights, $slug, array($this, 'options_page'));
        }

        public function options_page() {
            include 'ws-options.php';
        }

        public function in_stock_page_render($args) {
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['inStock']}]"; ?>" class="form-control selectpicker">
                <option <?php selected($this->options->getInStock(), '1'); ?> value='1'><?php echo $this->delivery_messages[1]; ?></option>
                <option <?php selected($this->options->getInStock(), '2'); ?> value='2' ><?php echo $this->delivery_messages[2]; ?></option>
                <option <?php selected($this->options->getInStock(), '3'); ?> value='3' ><?php echo $this->delivery_messages[3]; ?></option>
                <option <?php selected($this->options->getInStock(), '4'); ?> value='4' ><?php echo $this->delivery_messages[4]; ?></option>
            </select>
            <p class="description"><?php _e("This product's shipping availability as used throughout your shop. 'Available in store' refers to products that are available for pick up at your outlet if there is one. 'Upon order' refers to products that are ordered upon customer request up to 30 days.", $this->text); ?></p>
            <?php
        }

        public function out_of_stock_page_render($args) {
            ?>            
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['outOfStock']}]"; ?>" class="form-control selectpicker">
                <option <?php selected($this->options->getOutOfStock(), '2'); ?> value='2' ><?php echo $this->delivery_messages[2]; ?></option>
                <option <?php selected($this->options->getOutOfStock(), '3'); ?> value='3' ><?php echo $this->delivery_messages[3]; ?></option>
                <option <?php selected($this->options->getOutOfStock(), '4'); ?> value='4' ><?php echo $this->delivery_messages[4]; ?></option>
                <option <?php selected($this->options->getOutOfStock(), '5'); ?> value='5' ><?php echo $this->delivery_messages[5]; ?></option>
            </select>
            <p class="description"><?php _e("This product's shipping availability as used throughout your shop. 'Upon order' refers to products that are ordered upon customer request up to 30 days.", $this->text); ?></p>
            <?php
        }
        
        public function manufacturer_page_render($args) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['manufacturer']}]"; ?>" class="form-control selectpicker">
            	<option <?php selected ($this->options->getManufacturer(), ''); ?> value=''><?php _e('Please select manufacturer attribute', $this->text); ?></option>
            	<?php
            	   foreach ($attribute_taxonomies as $taxonomy) {
            	?>
            		<option <?php selected($this->options->getManufacturer(), $taxonomy->attribute_name); ?> value='<?php echo $taxonomy->attribute_name ?>' ><?php echo $taxonomy->attribute_label; ?></option>
            	<?php 
            	   }
            	?>
            </select>                 
            <p class="description"><?php _e("The manufacturer attribute. That is the name of the attribute that contains the manufacturer of the product.", $this->text); ?></p>
            <?php
        }
        
        public function iban_page_render($args) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['iban']}]"; ?>" class="form-control selectpicker">
            	<option <?php selected ($this->options->getIban(), ''); ?> value=''><?php _e('Please select iban attribute', $this->text); ?></option>
            	<?php
            	   foreach ($attribute_taxonomies as $taxonomy) {
            	?>
            		<option <?php selected($this->options->getIban(), $taxonomy->attribute_name); ?> value='<?php echo $taxonomy->attribute_name ?>' ><?php echo $taxonomy->attribute_label; ?></option>
            	<?php 
            	   }
            	?>
            </select>                 
            <p class="description"><?php _e("The iban attribute. That is the name of the attribute that contains the iban of the product.", $this->text); ?></p>
            <?php
        }
        
        
        public function color_page_render($args) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['color']}]"; ?>" class="form-control selectpicker">
            	<option <?php selected ($this->options->getColor(), ''); ?> value=''><?php _e('Please select color attribute', $this->text); ?></option>
            	<?php
            	   foreach ($attribute_taxonomies as $taxonomy) {
            	?>
            		<option <?php selected($this->options->getColor(), $taxonomy->attribute_name); ?> value='<?php echo $taxonomy->attribute_name ?>' ><?php echo $taxonomy->attribute_label; ?></option>
            	<?php 
            	   }
            	?>
            </select>           
            <p class="description"><?php _e("The color attribute. That is the name of the attribute that contains the color of the product.", $this->text); ?></p>
            <?php
        }

        public function size_page_render($args) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();            
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['size']}]"; ?>" class="form-control selectpicker">
            	<option <?php selected ($this->options->getSize(), ''); ?> value=''><?php _e('Please select size attribute', $this->text); ?></option>
            	<?php
            	   foreach ($attribute_taxonomies as $taxonomy) {
            	?>
            		<option <?php selected($this->options->getSize(), $taxonomy->attribute_name); ?> value='<?php echo $taxonomy->attribute_name ?>' ><?php echo $taxonomy->attribute_label; ?></option>
            	<?php 
            	   }
            	?>
            </select>           
            <p class="description"><?php _e("The size attribute. That is the name of the attribute that contains the size of the product.", $this->text); ?></p>
            <?php
        }
        
        
        public function delivery_settings_section_callback($args) {
            ?>
            <p class="description"><?php _e("Product's shipping availability as used throughout your shop. Skroutz uses a fixed set of availability descriptions that will be crosslinked to the ones provided in your feed.", $this->text); ?></p>
            <a href="<?php echo $this->url; ?>index.php?d=1" class="button button-primary" target="_blank"><?php _e("Download XML feed", $this->text) ?></a>
            <a href="<?php echo $this->url; ?>index.php" class="button button-primary" target="_blank"><?php _e("View XML feed", $this->text) ?></a>
            <?php
        }

        public function taxonomy_settings_section_callback($args) {
            ?>
            <p class="description"><?php _e("Taxonomy settings for additional information about the shop. For instance Manufacturer.", $this->text); ?></p>
            <?php
        }
        
        public function shipping_settings_section_callback($args) {
            ?>
            <p class="description"><?php _e("Shipping settings in order to calculates the shipping price of he xml. The same settings will apply on all products.", $this->text); ?></p>
            <?php
        }
        
        public function country_page_render($args) {
            $countries_obj = new WC_Countries();
            $countries   = $countries_obj->__get('countries');
            if(empty($this->current_country)) {
                $this->current_country = empty($this->options->getCountry()) ? $countries_obj->get_base_country() : $this->options->getCountry();
            }
                        
            ?>
			<select name="<?php echo "{$this->settings_page}[" . "{$this->fields['country']}]"; ?>" class="form-control selectpicker" data-live-search="true" id='country' >
				<option value=""><?php _e( 'Select a country', $this->text ); ?></option>
				<?php
					foreach( $countries as $key => $value ) {
					    ?>
            			<option <?php selected($this->current_country, esc_attr($key)); ?> value='<?php echo esc_attr($key); ?>' ><?php echo  esc_html( $value ); ?></option>
            			<?php 
					}
				?>
			</select>
            <p class="description"><?php _e("The country that the shipping cost calculator will use as reference. This country should be the same for all products.", $this->text); ?></p>
            <?php
        }
        
        public function state_page_render($args) {
            $countries_obj   = new WC_Countries();
            $countries   = $countries_obj->__get('countries');
            $cc = empty($this->options->getCountry()) ? $countries_obj->get_base_country() : $this->options->getCountry();
           
            $states =  $countries_obj->get_states( $cc );
            
            if($states==false) $states = [];
            
            $cs = empty($this->options->getState()) ? $countries_obj->get_base_state() : $this->options->getState();
            
            ?>
			<select name="<?php echo "{$this->settings_page}[" . "{$this->fields['state']}]"; ?>" class="form-control selectpicker" id="state" data-live-search="true">
				<option value=""><?php _e( 'Select a state', $this->text ); ?></option>				
				<?php
				foreach( $states as $key => $value ) {
				?>
            		<option <?php selected($cs, esc_attr($key)); ?> value='<?php echo esc_attr($key); ?>' ><?php echo  esc_html( $value ); ?></option>
            	<?php 
				}
				?>
			</select>
            <p class="description"><?php _e("The state that the shipping cost calculator will use as reference. This state should be the same for all products.", $this->text); ?></p>
            <?php
        }
        
        public function zip_page_render($args) {
            ?>
            <input id="zip" <?php if($this->options->getBaseAddress()==true) echo 'disabled="disabled"'; ?>  type="text" class="form-control" name="<?php echo "{$this->settings_page}[" . "{$this->fields['zip']}]"; ?>" value="<?php  echo sanitize_text_field($this->options->getZip()); ?>"/>
            <p class="description"><?php _e("The zip that the shipping cost calculator will use as reference. This zip should be the same for all products.", $this->text); ?></p>
            <?php
        }
        
        public function shipping_page_render($args) {
            $methods = WC()->shipping()->load_shipping_methods()
            ?>
			<select id="shipping" name="<?php echo "{$this->settings_page}[" . "{$this->fields['shipping']}]"; ?>" class="form-control selectpicker">
				<option value=""><?php _e( 'Select a shipping method', $this->text ); ?></option>				
				<?php
					foreach( $methods as $key => $value ) {
					    ?>
            			<option <?php selected($this->options->getShipping(), esc_attr($key)); ?> value='<?php echo esc_attr($key); ?>' ><?php echo  esc_html( $value->method_title ); ?></option>
            			<?php 
					}
				?>
			</select>
            <p class="description"><?php _e("The shiiping method that the shipping cost calculator will use as reference. This method should be the same for all products.", $this->text); ?></p>
            <?php
        }
        
        public function base_address_page_render($args) {
            ?>
            <input type="checkbox" data-on-text="<?php _e("Yes", $this->text); ?>" data-off-text="<?php _e("No", $this->text); ?>" class="form-control bootswitch" name="<?php echo "{$this->settings_page}[" . "{$this->fields['base_address']}]"; ?>" <?php checked( 1 == $this->options->getBaseAddress()); ?> value="1"/>
            <p class="description"><?php _e("If true the store base address will be used as reference to the calculation of the shipping cost.", $this->text); ?></p>
            <?php            
        }

    }

}
