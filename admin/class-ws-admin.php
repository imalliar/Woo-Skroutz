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

    class WSkroutz_Admin {

        private $prefix = WOO_SKROUTZ_PREFIX;
        private $url = WOO_SKROUTZ_URL;
        private $dir = WOO_SKROUTZ_DIR;
        private $text = WOO_SKROUTZ_TEXT;
        private $settings_page = WOO_SKROUTZ_SETTINGS_PAGE;
        private $settings_name = WOO_SKROUTZ_SETTINGS_NAME;
        private $name = WOO_SKROUTZ_NAME;
        private $fields = array('inStock' => 'delivery_days_in_stock', 'outOfStock' => 'delivery_days_out_of_stock');
        private $delivery_messages;

        public function __construct($dm) {
            $this->delivery_messages = $dm;
        }

        public function init() {
            add_action('init', array($this, 'add_textdomain'));
            add_action('admin_menu', array($this, 'add_admin_menu'));
            add_action('admin_init', array($this, 'register_options_init'));
            add_action('admin_init', array($this, 'save_registered_setting'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            add_filter('plugin_action_links_' . WOO_SKROUTZ_BASE, array($this, 'add_settings_link'));
            add_filter('plugin_row_meta', array($this, 'add_plugin_row_meta'), 10, 2);
        }

        function add_plugin_row_meta($links, $file) {
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
            load_plugin_textdomain(WOO_SKROUTZ_TEXT, false, $this->dir . '/languages/');
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

            //Custom assets
            wp_enqueue_style($this->prefix . '-admin', $this->url . 'assets/css/admin.css');
            wp_enqueue_script($this->prefix . '-admin-js', $this->url . 'assets/js/admin.js');
        }

        public function register_options_init() {
            $section = 'wskroutz_delivery_options_section';
            register_setting($this->settings_page, $this->settings_page);         
            
            add_settings_section(
                    $section, __('Delivery settings', $this->text), array($this, 'delivery_settings_section_callback'), $this->settings_page);
            add_settings_field(
                    $this->fields['inStock'], __('In Stock Products', $this->text), array($this, 'in_stock_page_render'), $this->settings_page, $section
            );

            add_settings_field(
                    $this->fields['outOfStock'], __('Out Of Stock Products', $this->text), array($this, 'out_of_stock_page_render'), $this->settings_page, $section
            );
            
            $options = get_option ($this->settings_page);
            if (false === $options) {
                //Get defaults
                $defaults = get_default_options_settings();
                update_option ( $this->settings_page, $defaults );
            }               

        }

        public function save_registered_setting() {
            //$options = get_option($this->settings_page);
            //update_option( $this->settings_page, $options );
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
            $options = get_option($this->settings_page);
            if($options===false || empty($options)) $options = get_default_options_settings();
            ?>
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['inStock']}]"; ?>" class="form-control">
                <option <?php selected($options[$this->fields['inStock']], '1'); ?> value='1'><?php echo $this->delivery_messages[1]; ?></option>
                <option <?php selected($options[$this->fields['inStock']], '2'); ?> value='2' ><?php echo $this->delivery_messages[2]; ?></option>
                <option <?php selected($options[$this->fields['inStock']], '3'); ?> value='3' ><?php echo $this->delivery_messages[3]; ?></option>
                <option <?php selected($options[$this->fields['inStock']], '4'); ?> value='4' ><?php echo $this->delivery_messages[4]; ?></option>
            </select>
            <p class="description"><?php _e("This product's shipping availability as used throughout your shop. 'Available in store' refers to products that are available for pick up at your outlet if there is one. 'Upon order' refers to products that are ordered upon customer request up to 30 days.", $this->text); ?></p>
            <?php
        }

        public function out_of_stock_page_render($args) {
            $options = get_option($this->settings_page);
            if($options===false || empty($options)) $options = get_default_options_settings();
            ?>
            
            <select  name="<?php echo "{$this->settings_page}[" . "{$this->fields['outOfStock']}]"; ?>" class="form-control">
                <option <?php selected($options[$this->fields['outOfStock']], '2'); ?> value='2' ><?php echo $this->delivery_messages[2]; ?></option>
                <option <?php selected($options[$this->fields['outOfStock']], '3'); ?> value='3' ><?php echo $this->delivery_messages[3]; ?></option>
                <option <?php selected($options[$this->fields['outOfStock']], '4'); ?> value='4' ><?php echo $this->delivery_messages[4]; ?></option>
                <option <?php selected($options[$this->fields['outOfStock']], '5'); ?> value='5' ><?php echo $this->delivery_messages[5]; ?></option>
            </select>
            <p class="description"><?php _e("This product's shipping availability as used throughout your shop. 'Upon order' refers to products that are ordered upon customer request up to 30 days.", $this->text); ?></p>
            <?php
        }

        public function delivery_settings_section_callback($args) {
            ?>
            <p class="description"><?php _e("Product's shipping availability as used throughout your shop. Skroutz uses a fixed set of availability descriptions that will be crosslinked to the ones provided in your feed.", $this->text); ?></p>
            <a href="<?php echo $this->url."/"; ?>index.php?d=1" class="button button-primary" target="_blank"><?php _e("Download XML feed", $this->text) ?></a>
            <?php
        }



    }

}
