<?php
/*
  Plugin Name: WooCommerce Currency Switcher
  Plugin URI: http://currency-switcher.com/
  Description: Currency Switcher for WooCommerce
  Author: realmag777
  Version: 1.1.5.1
  Requires at least: WP 4.1.0
  Tested up to: WP 4.4.1
  Text Domain: woocommerce-currency-switcher
  Domain Path: /languages
  Forum URI: #
  Author URI: http://www.pluginus.net/
 */

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly
}

if (defined('DOING_AJAX'))
{
    if (isset($_REQUEST['action']))
    {
        //do not recalculate refund amounts when we are in order backend
        if ($_REQUEST['action'] == 'woocommerce_refund_line_items')
        {
            return;
        }

        if (isset($_REQUEST['order_id']) AND $_REQUEST['action'] == 'woocommerce_load_order_items')
        {
            return;
        }
    }
}

//block for custom support code experiments
/*
  if ($_SERVER['REMOTE_ADDR'] != 'xxx.120.xxx.208')
  {
  return;
  }
 */
//***
define('WOOCS_PATH', plugin_dir_path(__FILE__));
define('WOOCS_LINK', plugin_dir_url(__FILE__));
define('WOOCS_PLUGIN_NAME', plugin_basename(__FILE__));

//classes
include_once WOOCS_PATH . 'classes/storage.php';

//From version 2.0.9 the plugin was remade - filters moved to WOOCS php class constructor
//27-01-2015
//delete_option('woocs');
final class WOOCS
{

    //http://docs.woothemes.com/wc-apidocs/class-WC_Order.html
    public $the_plugin_version = '1.1.5.1';
    public $storage = null;
    public $settings = array();
    public $default_currency = 'USD'; //EUR -> set any existed currency here if USD is not exists in your currencies list
    public $current_currency = 'USD'; //EUR -> set any existed currency here if USD is not exists in your currencies list
    public $currency_positions = array();
    public $currency_symbols = array();
    public $is_multiple_allowed = false; //from options
    public $decimal_sep = '.';
    public $thousands_sep = ',';
    public $rate_auto_update = ''; //from options
    public $shop_is_cached = true;
    private $is_first_unique_visit = false;
    public $no_cents = array('JPY', 'TWD'); //recount price without cents always!!
    public $bones = array(
        'reset_in_multiple' => false//normal is false
    ); //just for some setting for current wp theme adapting - for support only - it is logic hack - be care!!

    public function __construct()
    {
        $this->storage = new WOOCS_STORAGE(get_option('woocs_storage', 'session'));
        $this->init_no_cents();
        if (!defined('DOING_AJAX'))
        {
            //we need it if shop uses cache plugin, in such way prices will be redraw by AJAX
            $this->shop_is_cached = get_option('woocs_shop_is_cached', 0);
        }
        //+++

        $currencies = $this->get_currencies();
        if (!empty($currencies) AND is_array($currencies))
        {
            foreach ($currencies as $key => $currency)
            {
                if ($currency['is_etalon'])
                {
                    $this->default_currency = $key;
                    break;
                }
            }
        }

        //+++

        if (!$this->storage->is_isset('woocs_first_unique_visit'))
        {
            $this->storage->set_val('woocs_first_unique_visit', 0);
        }


        $this->is_multiple_allowed = get_option('woocs_is_multiple_allowed', 0);
        $this->rate_auto_update = 'no';
        //$this->decimal_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_decimal_sep')), ENT_QUOTES);
        //$this->thousands_sep = wp_specialchars_decode(stripslashes(get_option('woocommerce_price_thousand_sep')), ENT_QUOTES);
        //+++
        $this->currency_positions = array('left', 'right', 'left_space', 'right_space');
        $this->init_currency_symbols();

        //+++
        $is_first_activation = (int) get_option('woocs_first_activation', 0);
        if (!$is_first_activation)
        {
            update_option('woocs_first_activation', 1);
            update_option('woocs_drop_down_view', 'ddslick');
            update_option('woocs_currencies_aggregator', 'yahoo');
            update_option('woocs_welcome_currency', $this->default_currency);
            update_option('woocs_is_multiple_allowed', 0);
            update_option('woocs_show_flags', 1);
            update_option('woocs_show_money_signs', 1);
            update_option('woocs_customer_signs', '');
            update_option('woocs_customer_price_format', '');
            update_option('woocs_currencies_rate_auto_update', 'no');
            update_option('woocs_use_curl', 0);
            update_option('woocs_storage', 'session');
            update_option('woocs_geo_rules', '');
            update_option('woocs_use_geo_rules', 0);
            update_option('woocs_hide_cents', '');
            update_option('woocs_price_info', 0);
            update_option('woocs_no_cents', '');
            update_option('woocs_restrike_on_checkout_page', 0);
            update_option('woocs_shop_is_cached', 0);
        }
        //+++
        //simple checkout itercept
        if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'woocommerce_checkout')
        {
            //$this->storage->set_val('woocs_first_unique_visit', 0);
            $_REQUEST['woocommerce-currency-switcher'] = $this->storage->get_val('woocs_current_currency');
            $this->current_currency = $_REQUEST['woocommerce-currency-switcher'];
            $_REQUEST['woocs_in_order_currency'] = $this->current_currency;
        }

        //paypal query itercept
        if (isset($_REQUEST['mc_currency']) AND ! empty($_REQUEST['mc_currency']))
        {
            if (array_key_exists($_REQUEST['mc_currency'], $currencies))
            {
                //$this->storage->set_val('woocs_first_unique_visit', 0);
                $_REQUEST['woocommerce-currency-switcher'] = $_REQUEST['mc_currency'];
            }
        }

        //WELCOME USER CURRENCY ACTIVATION
        if ($this->storage->get_val('woocs_first_unique_visit') == 0)
        {
            //$this->storage->set_val('woocs_first_unique_visit', 0);
            $this->is_first_unique_visit = true;
            $this->storage->set_val('woocs_current_currency', $this->get_welcome_currency());
            $file_path = WP_PLUGIN_DIR . '/woocommerce/includes/class-wc-geolocation.php';
            if (file_exists($file_path))
            {
                $wc_clean_is_defined = true;
                if (function_exists('runkit_function_remove'))
                {
                    if (!function_exists('wc_clean'))
                    {

                        $wc_clean_is_defined = false;

                        function wc_clean($var)
                        {
                            return sanitize_text_field($var);
                        }

                    }
                }
                //***
                include_once($file_path);
                $this->init_geo_currency();
                //***
                if (function_exists('runkit_function_remove') AND ! $wc_clean_is_defined)
                {
                    runkit_function_remove('wc_clean');
                }
            }
        }

        //+++
        if (isset($_REQUEST['woocommerce-currency-switcher']))
        {
            if (array_key_exists($_REQUEST['woocommerce-currency-switcher'], $currencies))
            {
                $this->storage->set_val('woocs_current_currency', $_REQUEST['woocommerce-currency-switcher']);
            } else
            {
                $this->storage->set_val('woocs_current_currency', $this->default_currency);
            }
        }
        //+++
        //*** check currency in browser address
        if (isset($_GET['currency']) AND ! empty($_GET['currency']))
        {
            if (array_key_exists(strtoupper($_GET['currency']), $currencies))
            {
                $this->storage->set_val('woocs_current_currency', strtoupper($_GET['currency']));
            }
        }
        //+++
        if ($this->storage->is_isset('woocs_current_currency'))
        {
            $this->current_currency = $this->storage->get_val('woocs_current_currency');
        } else
        {
            $this->current_currency = $this->default_currency;
        }
        $this->storage->set_val('woocs_default_currency', $this->default_currency);
        //+++
        //if we want to be paid in the basic currency - not multiple mode
        //old code for woocomerce < 2.4, left for comatibility with old versions of woocommerce
        if (isset($_REQUEST['action']) AND ! $this->is_multiple_allowed)
        {
            if ($_REQUEST['action'] == 'woocommerce_update_order_review')
            {
                $this->reset_currency();
            }
        }






        //+++ FILTERS
        add_filter('woocommerce_paypal_args', array($this, 'apply_conversion'));
        add_filter('woocommerce_paypal_supported_currencies', array($this, 'enable_custom_currency'), 9999);
        add_filter('woocommerce_currency_symbol', array($this, 'woocommerce_currency_symbol'), 9999);
        add_filter('woocommerce_currency', array($this, 'get_woocommerce_currency'), 9999);
        //add_filter('woocommerce_reports_get_order_report_data', array($this, 'woocommerce_reports_get_order_report_data'), 9999, 2);
        //main recount hook

        if ($this->is_multiple_allowed)
        {
            //add_filter('woocommerce_get_price', array($this, 'raw_woocommerce_price_memo'), 9999, 2);
            add_filter('woocommerce_get_price', array($this, 'raw_woocommerce_price'), 9999);
        } else
        {
            add_filter('raw_woocommerce_price', array($this, 'raw_woocommerce_price'), 9999);
        }


        //+++
        if ($this->is_multiple_allowed)
        {
            //wp-content\plugins\woocommerce\includes\abstracts\abstract-wc-product.php #795
            /* Alda: Had to removed the filter as it is redundant with the woocommerce_get_price hook */
            //I back it 07-01-2016 because of it is really need. 
            //Comment next 2 hooks if double recount is for sale price http://c2n.me/3sCQFkX
            add_filter('woocommerce_get_regular_price', array($this, 'raw_woocommerce_price'), 9999);
            add_filter('woocommerce_get_sale_price', array($this, 'raw_woocommerce_price'), 9999);
            //***
            add_filter('woocommerce_get_variation_regular_price', array($this, 'raw_woocommerce_price'), 9999);
            add_filter('woocommerce_get_variation_sale_price', array($this, 'raw_woocommerce_price'), 9999);
            add_filter('woocommerce_variation_prices', array($this, 'woocommerce_variation_prices'), 9999, 1);
            //***
            //add_filter('woocommerce_get_variation_price', array($this, 'raw_woocommerce_price'), 9999, 1);
            add_filter('woocommerce_variation_prices_price', array($this, 'woocommerce_variation_prices'), 9999, 1);
            add_filter('woocommerce_variation_prices_regular_price', array($this, 'woocommerce_variation_prices'), 9999, 1);
            add_filter('woocommerce_variation_prices_sale_price', array($this, 'woocommerce_variation_prices'), 9999, 1);
            add_filter('woocommerce_get_variation_prices_hash', array($this, 'woocommerce_get_variation_prices_hash'), 9999, 3);
        }
        //***


        add_filter('woocommerce_price_format', array($this, 'woocommerce_price_format'), 9999);
        add_filter('woocommerce_thankyou_order_id', array($this, 'woocommerce_thankyou_order_id'), 9999);
        add_filter('woocommerce_before_resend_order_emails', array($this, 'woocommerce_before_resend_order_emails'), 9999);
        add_filter('woocommerce_email_actions', array($this, 'woocommerce_email_actions'), 9999);
        add_action('woocommerce_order_status_completed', array($this, 'woocommerce_order_status_completed'), 1);
        //add_filter('formatted_woocommerce_price', array($this, 'formatted_woocommerce_price'), 9999);
        add_filter('woocommerce_package_rates', array($this, 'woocommerce_package_rates'), 9999);
        add_filter('woocommerce_product_is_on_sale', array($this, 'woocommerce_product_is_on_sale'), 9999, 2);

        //for shop cart
        add_filter('woocommerce_cart_totals_order_total_html', array($this, 'woocommerce_cart_totals_order_total_html'), 10, 1);
        add_filter('wc_price_args', array($this, 'wc_price_args'), 9999);


        //for refreshing mini-cart widget
        add_filter('woocommerce_before_mini_cart', array($this, 'woocommerce_before_mini_cart'), 9999);
        add_filter('woocommerce_after_mini_cart', array($this, 'woocommerce_after_mini_cart'), 9999);


        //shipping
        add_filter('woocommerce_shipping_free_shipping_is_available', array($this, 'woocommerce_shipping_free_shipping_is_available'), 99, 2);
        //add_filter('woocommerce_update_shipping_method', array($this, 'woocommerce_update_shipping_method'), 1);
        //orders view on front
        //add_filter('woocommerce_view_order', array($this, 'woocommerce_view_order'), 1);
        add_action('woocommerce_get_order_currency', array($this, 'woocommerce_get_order_currency'), 1, 2);
        //add_filter('woocommerce_get_formatted_order_total', array($this, 'woocommerce_get_formatted_order_total'), 1, 2);
        //+++
        //+++ AJAX ACTIONS
        add_action('wp_ajax_woocs_save_etalon', array($this, 'save_etalon'));
        add_action('wp_ajax_woocs_get_rate', array($this, 'get_rate'));

        add_action('wp_ajax_woocs_convert_currency', array($this, 'woocs_convert_currency'));
        add_action('wp_ajax_nopriv_woocs_convert_currency', array($this, 'woocs_convert_currency'));

        add_action('wp_ajax_woocs_rates_current_currency', array($this, 'woocs_rates_current_currency'));
        add_action('wp_ajax_nopriv_woocs_rates_current_currency', array($this, 'woocs_rates_current_currency'));

        add_action('wp_ajax_woocs_get_products_price_html', array($this, 'woocs_get_products_price_html'));
        add_action('wp_ajax_nopriv_woocs_get_products_price_html', array($this, 'woocs_get_products_price_html'));

        add_action('wp_ajax_woocs_recalculate_order_data', array($this, 'woocs_recalculate_order_data'));
        //+++

        add_action('woocommerce_settings_tabs_array', array($this, 'woocommerce_settings_tabs_array'), 9999);
        add_action('woocommerce_settings_tabs_woocs', array($this, 'print_plugin_options'), 9999);

        //+++
        add_action('widgets_init', array($this, 'widgets_init'));
        add_action('wp_head', array($this, 'wp_head'), 1);
        add_action('wp_footer', array($this, 'wp_footer'), 9999);
        add_action('body_class', array($this, 'body_class'), 9999);
        //***
        add_action('save_post', array($this, 'save_post'), 1);
        add_action('admin_head', array($this, 'admin_head'), 1);
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('admin_init', array($this, 'admin_init'), 1);
        //price formatting on front ***********
        add_action('woocommerce_price_html', array($this, 'woocommerce_price_html'), 1, 2);

        //if ($this->is_multiple_allowed)
        {
            add_action('woocommerce_variable_price_html', array($this, 'woocommerce_price_html'), 1, 2);
            add_action('woocommerce_variable_sale_price_html', array($this, 'woocommerce_price_html'), 1, 2);
            add_action('woocommerce_sale_price_html', array($this, 'woocommerce_price_html'), 1, 2);
            add_action('woocommerce_grouped_price_html', array($this, 'woocommerce_price_html'), 1, 2);
        }

        //*** additional
        //wpo_wcpdf_order_number is -> compatibility for https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/stats/
        add_action('wpo_wcpdf_order_number', array($this, 'wpo_wcpdf_order_number'), 1);
        add_action('woocs_exchange_value', array($this, 'woocs_exchange_value'), 1);
        //add_action('wcml_raw_price_amount', array($this, 'wcml_raw_price_amount'), 1);
        //for coupons
        add_filter('woocommerce_coupon_get_discount_amount', array($this, 'woocommerce_coupon_get_discount_amount'), 9999, 5);
        //*************************************
        add_shortcode('woocs', array($this, 'woocs_shortcode'));
        add_shortcode('woocs_get_sign_rate', array($this, 'get_sign_rate'));
        add_shortcode('woocs_converter', array($this, 'woocs_converter'));
        add_shortcode('woocs_rates', array($this, 'woocs_rates'));
        add_shortcode('woocs_show_current_currency', array($this, 'woocs_show_current_currency'));
        add_shortcode('woocs_show_custom_price', array($this, 'woocs_show_custom_price'));

        if ($this->is_multiple_allowed)
        {
            add_action('the_post', array($this, 'the_post'), 1);
            add_action('load-post.php', array($this, 'admin_action_post'), 1);
        }

        //+++
     
        $this->storage->set_val('woocs_first_unique_visit', 1);
    }

    public function body_class($classes)
    {
        $classes[] = 'currency-' . strtolower($this->current_currency);
        return $classes;
    }

    public function init()
    {
        if (!class_exists('WooCommerce'))
        {
            return;
        }
        //global $wp;
        wp_enqueue_script('jquery');
        //overide woocs slider js
        wp_enqueue_script('wc-price-slider', WOOCS_LINK . 'js/price-slider.js', array('jquery', 'jquery-ui-slider'));

        //+++
        load_plugin_textdomain('woocommerce-currency-switcher', false, dirname(plugin_basename(__FILE__)) . '/languages');


        //filters
        add_filter('plugin_action_links_' . WOOCS_PLUGIN_NAME, array($this, 'plugin_action_links'));
        add_filter('woocommerce_currency_symbol', array($this, 'woocommerce_currency_symbol'), 9999);

        //***
        //if we use GeoLocation
        $this->init_geo_currency();

        //set default cyrrency for wp-admin of the site
        if (is_admin() AND ! is_ajax())
        {
            $this->current_currency = $this->default_currency;
        } else
        {
            //if we are in the a product backend and loading its variations
            if (is_ajax() AND ( isset($_REQUEST['action']) AND $_REQUEST['action'] == 'woocommerce_load_variations'))
            {
                $this->current_currency = $this->default_currency;
            }
        }

        if (is_ajax())
        {
            $actions = false;
            //code for manual order adding
            if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'woocommerce_add_order_item')
            {
                $actions = true;
            }

            if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'woocommerce_save_order_items')
            {
                $actions = true;
            }

            if (isset($_REQUEST['action']) AND $_REQUEST['action'] == 'woocommerce_calc_line_taxes')
            {
                $actions = true;
            }
            //***
            if ($actions AND current_user_can('edit_shop_orders'))
            {
                $this->current_currency = $this->default_currency;
                //check if is order has installed currency
                $currency = get_post_meta($_REQUEST['order_id'], '_order_currency', TRUE);
                if (!empty($currency))
                {
                    $this->current_currency = $currency;
                }
            }
        }

        if (is_ajax() AND isset($_REQUEST['action'])
                AND $_REQUEST['action'] == 'woocommerce_mark_order_status'
                AND isset($_REQUEST['status']) AND $_REQUEST['status'] == 'completed'
                AND isset($_REQUEST['order_id'])
        )
        {
            $currency = get_post_meta($_REQUEST['order_id'], '_order_currency', true);
            if (!empty($currency))
            {
                $_REQUEST['woocs_in_order_currency'] = $currency;
                $this->current_currency = $currency;
            }
        }



        //if we want to be paid in the basic currency - not multiple mode
        if (!$this->is_multiple_allowed)
        {
            if (isset($_GET['wc-ajax']) AND $_GET['wc-ajax'] == 'update_order_review')
            {
                $this->reset_currency();
            }


            //compatibility for WC_Gateway_PayPal_Express_AngellEYE
            if (isset($_GET['wc-api']) AND isset($_GET['pp_action']) AND isset($_GET['use_paypal_credit']))
            {
                if ($_GET['pp_action'] == 'expresscheckout')
                {
                    $this->reset_currency();
                }
            }
        }
    }
    

    private function init_currency_symbols()
    {
        //includes/wc-core-functions.php #217
        $this->currency_symbols = array(
            '&#36;', '&euro;', '&yen;', '&#1088;&#1091;&#1073;.', '&#1075;&#1088;&#1085;.', '&#8361;',
            '&#84;&#76;', 'د.إ', '&#2547;', '&#82;&#36;', '&#1083;&#1074;.',
            '&#107;&#114;', '&#82;', '&#75;&#269;', '&#82;&#77;', 'kr.', '&#70;&#116;',
            'Rp', 'Rs', 'Kr.', '&#8362;', '&#8369;', '&#122;&#322;', '&#107;&#114;',
            '&#67;&#72;&#70;', '&#78;&#84;&#36;', '&#3647;', '&pound;', 'lei', '&#8363;',
            '&#8358;', 'Kn', '-----'
        );

        $this->currency_symbols = array_merge($this->currency_symbols, $this->get_customer_signs());
    }

    private function init_no_cents()
    {
        $no_cents = get_option('woocs_no_cents', array());
        $currencies = $this->get_currencies();
        $currencies = array_keys($currencies);
        $currencies = array_map('strtolower', $currencies);
        if (!empty($no_cents))
        {
            $no_cents = explode(',', $no_cents);
            if (!empty($no_cents) AND is_array($no_cents))
            {
                foreach ($no_cents as $value)
                {
                    if (in_array(strtolower($value), $currencies))
                    {
                        $this->no_cents[] = $value;
                    }
                }
            }
        }

        return $this->no_cents;
    }

    //for auto rate update sheduler
    public function rate_auto_update()
    {
        $currencies = $this->get_currencies();
        //***
        $_REQUEST['no_ajax'] = TRUE;
        $request = array();
        foreach ($currencies as $key => $currency)
        {
            if ($currency['is_etalon'] == 1)
            {
                continue;
            }
            $_REQUEST['currency_name'] = $currency['name'];
            $request[$key] = (float) $this->get_rate();
        }
        //*** checking and assigning data
        foreach ($currencies as $key => $currency)
        {
            if ($currency['is_etalon'] == 1)
            {
                continue;
            }
            if (isset($request[$key]) AND ! empty($request[$key]) AND $request[$key] > 0)
            {
                $currencies[$key]['rate'] = $request[$key];
            }
        }

        update_option('woocs', $currencies);
    }

    private function init_geo_currency()
    {
        $done = false;

        if ($this->is_use_geo_rules() AND $this->is_first_unique_visit)
        {
            $rules = $this->get_geo_rules();
            $pd = WC_Geolocation::geolocate_ip();

            if (isset($pd['country']) AND ! empty($pd['country']))
            {
                if (!empty($rules))
                {
                    foreach ($rules as $curr => $countries)
                    {
                        if (!empty($countries) AND is_array($countries))
                        {
                            foreach ($countries as $country)
                            {
                                if ($country == $pd['country'])
                                {
                                    $this->storage->set_val('woocs_current_currency', $curr);
                                    $done = true;
                                    break(2);
                                }
                            }
                        }
                    }
                }
                /*
                 * Array
                  (
                  [EUR] => Array
                  (
                  [0] => AF
                  )

                  [RUB] => Array
                  (
                  [0] => DZ
                  [1] => AZ
                  )

                  )
                 */
            }
        }

        return $done;
    }

    /**
     * Show action links on the plugin screen
     */
    public function plugin_action_links($links)
    {
        return array_merge(array(
            '<a href="' . admin_url('admin.php?page=wc-settings&tab=woocs') . '">' . __('Settings', 'woocommerce-currency-switcher') . '</a>',
            '<a target="_blank" href="' . esc_url('http://currency-switcher.com/documentation/') . '">' . __('Documentation', 'woocommerce-currency-switcher') . '</a>'
                ), $links);

        return $links;
    }

    public function widgets_init()
    {
        require_once WOOCS_PATH . 'classes/widgets/widget-woocs-selector.php';
        require_once WOOCS_PATH . 'classes/widgets/widget-currency-rates.php';
        require_once WOOCS_PATH . 'classes/widgets/widget-currency-converter.php';
        register_widget('WOOCS_SELECTOR');
        register_widget('WOOCS_RATES');
        register_widget('WOOCS_CONVERTER');
    }

    public function admin_enqueue_scripts()
    {
        if (isset($_GET['tab']) AND $_GET['tab'] == 'woocs')
        {
            wp_enqueue_style('woocommerce-currency-switcher-options', WOOCS_LINK . 'css/options.css');
        }
    }

    public function admin_head()
    {
        if (isset($_GET['woocs_reset']))
        {
            delete_option('woocs');
        }
        //wp_enqueue_scripts('jquery');
        if (isset($_GET['page']) AND isset($_GET['tab']))
        {
            if ($_GET['page'] == 'wc-settings'/* AND $_GET['tab'] == 'woocs' */)
            {
                wp_enqueue_script('woocs-admin', WOOCS_LINK . 'js/admin.js', array('jquery'));
            }
        }

        //settings page preloader
        if (isset($_GET['tab']) AND $_GET['tab'] == 'woocs')
        {
            ?>
            <script type="text/javascript">
                jQuery(window).load(function () {
                    // Animate loader off screen
                    if (jQuery(".se-pre-con").length > 0) {
                        jQuery(".se-pre-con").fadeOut("slow");
                    }
                });
            </script>
            <?php
        }
    }

    public function admin_init()
    {
        if ($this->is_multiple_allowed)
        {
            add_meta_box('woocs_order_metabox', __('WOOCS Order Info', 'woocommerce-currency-switcher'), array($this, 'woocs_order_metabox'), 'shop_order', 'side', 'default');
        }
    }

    //for orders hook
    public function save_post($order_id)
    {
        if (!empty($_POST))
        {
            global $post;
            if (is_object($post))
            {
                if (($post->post_type == 'shop_order' || $post->post_type == 'shop_subscription') AND isset($_POST['woocs_order_currency']))
                {
                    $currencies = $this->get_currencies();
                    $currencies_keys = array_keys($currencies);
                    $currency = $_POST['woocs_order_currency'];
                    if (in_array($currency, $currencies_keys))
                    {

                        //changing order currency
                        update_post_meta($order_id, '_order_currency', $currency);

                        update_post_meta($order_id, '_woocs_order_rate', $currencies[$currency]['rate']);
                        wc_add_order_item_meta($order_id, '_woocs_order_rate', $currencies[$currency]['rate'], true);

                        update_post_meta($order_id, '_woocs_order_base_currency', $this->default_currency);
                        wc_add_order_item_meta($order_id, '_woocs_order_base_currency', $this->default_currency, true);

                        update_post_meta($order_id, '_woocs_order_currency_changed_mannualy', time());
                        wc_add_order_item_meta($order_id, '_woocs_order_currency_changed_mannualy', time(), true);
                    }
                }
            }
        }
    }

    //for orders hook
    public function the_post($post)
    {
        if (is_object($post) AND $post->post_type == 'shop_order')
        {
            $currency = get_post_meta($post->ID, '_order_currency', true);
            if (!empty($currency))
            {
                $_REQUEST['woocs_in_order_currency'] = $currency;
                $this->current_currency = $currency;
            }
        }

        return $post;
    }

    //for orders hook
    public function admin_action_post()
    {
        if (isset($_GET['post']))
        {
            $post_id = $_GET['post'];
            $post = get_post($post_id);
            if (is_object($post) AND $post->post_type == 'shop_order')
            {
                $currency = get_post_meta($post->ID, '_order_currency', true);
                if (!empty($currency))
                {
                    $_REQUEST['woocs_in_order_currency'] = $currency;
                    $this->current_currency = $currency;
                }
            }
        }
    }

    public function woocs_order_metabox($post)
    {
        $data = array();
        $data['post'] = $post;
        $data['order'] = new WC_Order($post->ID);
        echo $this->render_html(WOOCS_PATH . 'views/woocs_order_metabox.php', $data);
    }

    public function wp_head()
    {
        wp_enqueue_script('jquery');
        $currencies = $this->get_currencies();
        ?>
        <script type="text/javascript">
            var woocs_drop_down_view = "<?php echo $this->get_drop_down_view(); ?>";
            var woocs_current_currency = <?php echo json_encode((isset($currencies[$this->current_currency]) ? $currencies[$this->current_currency] : $currencies[$this->default_currency])) ?>;
            var woocs_default_currency = <?php echo json_encode($currencies[$this->default_currency]) ?>;
            var woocs_array_of_get = '{}';
        <?php if (!empty($_GET)): ?>
                woocs_array_of_get = '<?php echo json_encode($_GET); ?>';
        <?php endif; ?>

            woocs_array_no_cents = '<?php echo json_encode($this->no_cents); ?>';

            var woocs_ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            var woocs_lang_loading = "<?php _e('loading', 'woocommerce-currency-switcher') ?>";
            var woocs_shop_is_cached =<?php echo (int) $this->shop_is_cached ?>;
        </script>
        <?php
        if ($this->get_drop_down_view() == 'ddslick')
        {
            wp_enqueue_script('jquery.ddslick.min', WOOCS_LINK . 'js/jquery.ddslick.min.js', array('jquery'));
        }

        if ($this->get_drop_down_view() == 'chosen' OR $this->get_drop_down_view() == 'chosen_dark')
        {
            wp_enqueue_script('chosen-drop-down', WOOCS_LINK . 'js/chosen/chosen.jquery.min.js', array('jquery'));
            wp_enqueue_style('chosen-drop-down', WOOCS_LINK . 'js/chosen/chosen.min.css');
            //dark chosen
            if ($this->get_drop_down_view() == 'chosen_dark')
            {
                wp_enqueue_style('chosen-drop-down-dark', WOOCS_LINK . 'js/chosen/chosen-dark.css');
            }
        }

        if ($this->get_drop_down_view() == 'wselect')
        {
            wp_enqueue_script('woocs_wselect', WOOCS_LINK . 'js/wselect/wSelect.min.js', array('jquery'));
            wp_enqueue_style('woocs_wselect', WOOCS_LINK . 'js/wselect/wSelect.css');
        }

        //+++
        wp_enqueue_style('woocommerce-currency-switcher', WOOCS_LINK . 'css/front.css');
        wp_enqueue_script('woocommerce-currency-switcher', WOOCS_LINK . 'js/front.js', array('jquery'));
        //+++
        //trick for refreshing header cart after currency changing - code left just for history
        if (isset($_GET['currency']))
        {
            //wp-content\plugins\woocommerce\includes\class-wc-cart.php
            //apply_filters('woocommerce_update_cart_action_cart_updated', true);
            //$this->current_currency = $_GET['currency'];
            //$_POST['update_cart'] = 1;
            //WC_Form_Handler::update_cart_action();
            //private function set_cart_cookies
            //wc_setcookie('woocommerce_items_in_cart', 0, time() - HOUR_IN_SECONDS);
            //wc_setcookie('woocommerce_cart_hash', '', time() - HOUR_IN_SECONDS);
            //do_action('woocommerce_cart_reset', WC()->cart, true);
            //do_action('woocommerce_calculate_totals', WC()->cart);
        }


        //if customer paying pending order from the front
        //checkout/order-pay/1044/?pay_for_order=true&key=order_55b764a4b7990
        if (isset($_GET['pay_for_order']) AND is_checkout_pay_page())
        {
            if ($_GET['pay_for_order'] == 'true' AND isset($_GET['key']))
            {
                $order_id = wc_get_order_id_by_order_key($_GET['key']);
                $currency = get_post_meta($order_id, '_order_currency', TRUE);
                $this->current_currency = $currency;
                $this->storage->set_val('woocs_current_currency', $currency);
            }
        }

        //+++
        //if we want to be paid in the basic currency - not multiple mode
        if (!$this->is_multiple_allowed)
        {
            if (is_checkout() OR is_checkout_pay_page())
            {
                $this->reset_currency();
            }
        }


        //logic hack for some cases when shipping for example is wrong in
        //non multiple mode but customer doesn work allow pay in user selected currency
        if ($this->is_multiple_allowed)
        {
            if ((is_checkout() OR is_checkout_pay_page()) AND $this->bones['reset_in_multiple'])
            {
                $this->reset_currency();
            }
        }
    }

    public function woocommerce_settings_tabs_array($tabs)
    {
        $tabs['woocs'] = __('Currency', 'woocommerce-currency-switcher');
        return $tabs;
    }

    public function print_plugin_options()
    {
        if (isset($_POST['woocs_name']) AND ! empty($_POST['woocs_name']))
        {
            $result = array();
            update_option('woocs_drop_down_view', $_POST['woocs_drop_down_view']);
            update_option('woocs_currencies_aggregator', $_POST['woocs_currencies_aggregator']);
            update_option('woocs_welcome_currency', $_POST['woocs_welcome_currency']);
            update_option('woocs_is_multiple_allowed', $_POST['woocs_is_multiple_allowed']);
            update_option('woocs_customer_signs', $_POST['woocs_customer_signs']);
            update_option('woocs_customer_price_format', $_POST['woocs_customer_price_format']);
            update_option('woocs_currencies_rate_auto_update', 'no');
            update_option('woocs_show_flags', $_POST['woocs_show_flags']);
            update_option('woocs_show_money_signs', $_POST['woocs_show_money_signs']);
            update_option('woocs_use_curl', $_POST['woocs_use_curl']);
            update_option('woocs_storage', $_POST['woocs_storage']);
            if (isset($_POST['woocs_geo_rules']))
            {
                update_option('woocs_geo_rules', $_POST['woocs_geo_rules']);
            } else
            {
                update_option('woocs_geo_rules', '');
            }
            update_option('woocs_use_geo_rules', $_POST['woocs_use_geo_rules']);
            update_option('woocs_hide_cents', $_POST['woocs_hide_cents']);
            update_option('woocs_price_info', $_POST['woocs_price_info']);
            update_option('woocs_no_cents', $_POST['woocs_no_cents']);
            update_option('woocs_restrike_on_checkout_page', $_POST['woocs_restrike_on_checkout_page']);
            update_option('woocs_shop_is_cached', $_POST['woocs_shop_is_cached']);
            //***
            $cc = '';
            foreach ($_POST['woocs_name'] as $key => $name)
            {
                if (!empty($name))
                {
                    $symbol = $_POST['woocs_symbol'][$key]; //md5 encoded

                    foreach ($this->currency_symbols as $s)
                    {
                        if (md5($s) == $symbol)
                        {
                            $symbol = $s;
                            break;
                        }
                    }

                    $result[strtoupper($name)] = array(
                        'name' => $name,
                        'rate' => floatval($_POST['woocs_rate'][$key]),
                        'symbol' => $symbol,
                        'position' => (in_array($_POST['woocs_position'][$key], $this->currency_positions) ? $_POST['woocs_position'][$key] : $this->currency_positions[0]),
                        'is_etalon' => (int) $_POST['woocs_is_etalon'][$key],
                        'hide_cents' => (int) @$_POST['woocs_hide_cents'][$key],
                        'description' => $_POST['woocs_description'][$key],
                        'flag' => $_POST['woocs_flag'][$key],
                    );

                    if ($_POST['woocs_rate'][$key] == 1)
                    {
                        $cc = $name;
                    }
                }
            }

            update_option('woocs', $result);
            if (!empty($cc))
            {
                //set default currency for all woocommerce system
                update_option('woocommerce_currency', $cc);
            }
            $this->init_currency_symbols();
        }
        //+++
        wp_enqueue_script('media-upload');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-tabs');
        wp_enqueue_script('woocommerce-currency-switcher-options', WOOCS_LINK . 'js/options.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'));

        $args = array();
        $args['currencies'] = $this->get_currencies();
        if ($this->is_use_geo_rules())
        {
            $args['geo_rules'] = $this->get_geo_rules();
        }
        echo $this->render_html(WOOCS_PATH . 'views/plugin_options.php', $args);
    }

    public function get_drop_down_view()
    {
        return get_option('woocs_drop_down_view', 'ddslick');
    }

    public function get_currencies()
    {
        //static $currencies = array();
        //$_POST['woocs_name'] - reinit after saving
        /*
          if (!empty($currencies) AND ! isset($_POST['woocs_name']))
          {
          return $currencies;
          }
         *
         */

        $default = array(
            'USD' => array(
                'name' => 'USD',
                'rate' => 1,
                'symbol' => '&#36;',
                'position' => 'right',
                'is_etalon' => 1,
                'description' => 'USA dollar',
                'hide_cents' => 0,
                'flag' => '',
            ),
            'EUR' => array(
                'name' => 'EUR',
                'rate' => 0.89,
                'symbol' => '&euro;',
                'position' => 'left_space',
                'is_etalon' => 0,
                'description' => 'Europian Euro',
                'hide_cents' => 0,
                'flag' => '',
            )
        );

        $currencies = get_option('woocs', $default);
        $currencies = apply_filters('woocs_currency_data_manipulation', $currencies);

        /*
          //http://currency-switcher.com/how-to-manipulate-with-currencies-rates/
          foreach ($currencies as $key => $value)
          {
          if($key == 'EUR'){
          $currencies[$key]['rate']=$currencies[$key]['rate']+0.025;
          break;
          }
          }
         */

        return $currencies;
    }

    public function get_geo_rules()
    {
        return get_option('woocs_geo_rules', array());
    }

    public function is_use_geo_rules()
    {
        $is = get_option('woocs_use_geo_rules', 0);
        $isset = class_exists('WC_Geolocation');

        return ($is && $isset);
    }

    //need for paypal currencies supporting
    public function enable_custom_currency($currency_array)
    {
        //https://developer.paypal.com/docs/classic/api/currency_codes/
        //includes\gateways\paypal\class-wc-gateway-paypal.php => woo func
        //function is_valid_for_use() =>Check if this gateway is enabled and available in the user's country
        $currency_array[] = 'usd';
        $currency_array[] = 'aud';
        $currency_array[] = 'brl';
        $currency_array[] = 'cad';
        $currency_array[] = 'czk';
        $currency_array[] = 'dkk';
        $currency_array[] = 'eur';
        $currency_array[] = 'hkd';
        $currency_array[] = 'huf';
        $currency_array[] = 'ils';
        $currency_array[] = 'jpy';
        $currency_array[] = 'myr';
        $currency_array[] = 'mxn';
        $currency_array[] = 'nok';
        $currency_array[] = 'nzd';
        $currency_array[] = 'php';
        $currency_array[] = 'pln';
        $currency_array[] = 'gbp';
        $currency_array[] = 'rub';
        $currency_array[] = 'sgd';
        $currency_array[] = 'sek';
        $currency_array[] = 'chf';
        $currency_array[] = 'twd';
        $currency_array[] = 'thb';
        $currency_array[] = 'try';
        $currency_array = array_map('strtoupper', $currency_array);
        return $currency_array;
    }

    public function woocommerce_currency_symbol($currency_symbol)
    {

        $currencies = $this->get_currencies();

        if (isset($currencies[$this->current_currency]))
        {
            $currency_symbol = $currencies[$this->current_currency]['symbol'];
        } else
        {
            $currency_symbol = 'WRONG DATA. CHECK YOUR WOOCS OPTIONS!';
            //$currency_symbol = $currencies[$this->default_currency]['symbol'];
        }



        return $currency_symbol;
    }

    public function get_woocommerce_currency()
    {
        return $this->current_currency;
    }

    public function raw_woocommerce_price($price)
    {

        if (isset($_REQUEST['woocs_block_price_hook']))
        {
            return $price;
        }

        //***

        $currencies = $this->get_currencies();

        $precision = 2;
        if (in_array($this->current_currency, $this->no_cents)/* OR $currencies[$this->current_currency]['hide_cents'] == 1 */)
        {
            $precision = 0;
        }

        /*
          if ($this->current_currency != $this->default_currency)
          {
          $price = number_format(floatval($price * $currencies[$this->current_currency]['rate']), $precision, $this->decimal_sep, '');
          }
         */

        if ($this->current_currency != $this->default_currency)
        {
            //Edited this line to set default convertion of currency
            if ($currencies[$this->current_currency] != NULL)
            {
                $price = number_format(floatval($price * $currencies[$this->current_currency]['rate']), $precision, $this->decimal_sep, '');
            } else
            {
                $price = number_format(floatval($price * $currencies[$this->default_currency]['rate']), $precision, $this->decimal_sep, '');
            }
        }

        //http://stackoverflow.com/questions/11692770/rounding-to-nearest-50-cents
        //$price = round($price * 2, 0) / 2;
        return $price;

        //return round ( $price , 0 ,PHP_ROUND_HALF_EVEN );
        //return number_format ($price, 2, $this->decimal_sep, $this->thousands_sep);
    }

    //to avoid double recount for example in mini-cart
    public function raw_woocommerce_price_memo($price, $product)
    {
        $currencies = $this->get_currencies();

        //+++

        static $products = array();
        $p_id = 0;

        //init product ID
        if (!empty($product->variation_id))
        {
            $p_id = $product->variation_id;
        } else
        {
            $p_id = $product->id;
        }
        //+++

        $precision = 2;
        if (in_array($this->current_currency, $this->no_cents))
        {
            $precision = 0;
        }

        //+++
        if ($this->current_currency != $this->default_currency)
        {
            if (!isset($products[$p_id]))
            {
                $price = number_format(floatval($price * $currencies[$this->current_currency]['rate']), $precision, $this->decimal_sep, '');
                $products[$p_id] = $price;
            } else
            {
                $price = $products[$p_id];
            }
        }


        return $price;

        //return round ( $price , 0 ,PHP_ROUND_HALF_EVEN );
        //return number_format ($price, 2, $this->decimal_sep, $this->thousands_sep);
    }

    public function get_welcome_currency()
    {
        return get_option('woocs_welcome_currency');
    }

    public function get_customer_signs()
    {
        $signs = array();
        $data = get_option('woocs_customer_signs', '');
        if (!empty($data))
        {
            $data = explode(',', $data);
            if (!empty($data) AND is_array($data))
            {
                $signs = $data;
            }
        }
        return $signs;
    }

    public function get_checkout_page_id()
    {
        return (int) get_option('woocommerce_checkout_page_id');
    }

    public function woocommerce_price_format()
    {
        $currencies = $this->get_currencies();
        $currency_pos = 'left';
        if (isset($currencies[$this->current_currency]))
        {
            $currency_pos = $currencies[$this->current_currency]['position'];
        }
        $format = '%1$s%2$s';
        switch ($currency_pos)
        {
            case 'left' :
                $format = '%1$s%2$s';
                break;
            case 'right' :
                $format = '%2$s%1$s';
                break;
            case 'left_space' :
                $format = '%1$s&nbsp;%2$s';
                break;
            case 'right_space' :
                $format = '%2$s&nbsp;%1$s';
                break;
        }

        return apply_filters('woocs_price_format', $format, $currency_pos);
    }

    //[woocs]
    public function woocs_shortcode($args)
    {
        if(empty($args)){
            $args=array();
        }
        return $this->render_html(WOOCS_PATH . 'views/shortcodes/woocs.php', $args);
    }

    //[woocs_converter exclude="GBP,AUD" precision=2]
    public function woocs_converter($args)
    {
        if(empty($args)){
            $args=array();
        }
        return $this->render_html(WOOCS_PATH . 'views/shortcodes/woocs_converter.php', $args);
    }

    //[woocs_rates exclude="GBP,AUD" precision=2]
    public function woocs_rates($args)
    {
        if(empty($args)){
            $args=array();
        }
        return $this->render_html(WOOCS_PATH . 'views/shortcodes/woocs_rates.php', $args);
    }

    //[woocs_show_current_currency text="" currency="" flag=1 code=1]
    public function woocs_show_current_currency($atts)
    {
        $currencies = $this->get_currencies();
        extract(shortcode_atts(array(
            'text' => __('Current currency is:', 'woocommerce-currency-switcher'),
            'currency' => $this->current_currency,
            'flag' => 1,
            'code' => 1,
                        ), $atts));

        $args = array();
        $args['currencies'] = $currencies;
        $args['text'] = $text;
        $args['currency'] = $currency;
        $args['flag'] = $flag;
        $args['code'] = $code;
        return $this->render_html(WOOCS_PATH . 'views/shortcodes/woocs_show_current_currency.php', $args);
    }

    //[woocs_show_custom_price value=20] -> value should be in default currency
    public function woocs_show_custom_price($atts)
    {
        extract(shortcode_atts(array('value' => 0), $atts));
        $currencies = $this->get_currencies();
        $convert = true;
        return $this->wc_price($value, $convert, array('currency' => $currencies[$this->current_currency]['name']));
    }

    //http://stackoverflow.com/questions/6918623/curlopt-followlocation-cannot-be-activated
    private function file_get_contents_curl($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    //ajax
    public function get_rate()
    {
        $is_ajax = true;
        if (isset($_REQUEST['no_ajax']))
        {
            $is_ajax = false;
        }

        //***
        //http://en.wikipedia.org/wiki/ISO_4217
        $mode = get_option('woocs_currencies_aggregator', 'yahoo');
        $request = "";
        $woocs_use_curl = (int) get_option('woocs_use_curl', 0);
        switch ($mode)
        {
            case 'yahoo':
                //http://www.idiotinside.com/2015/01/28/create-a-currency-converter-in-php-python-javascript-and-jquery-using-yahoo-currency-api/
                $yql_base_url = "http://query.yahooapis.com/v1/public/yql";
                $yql_query = 'select * from yahoo.finance.xchange where pair in ("' . $this->default_currency . $_REQUEST['currency_name'] . '")';
                $yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
                $yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
                //***
                if (function_exists('curl_init') AND $woocs_use_curl)
                {
                    $res = $this->file_get_contents_curl($yql_query_url);
                } else
                {
                    $res = file_get_contents($yql_query_url);
                }
                //***
                $yql_json = json_decode($res, true);
                $request = (float) $yql_json['query']['results']['rate']['Rate'];


                break;

            case 'google':
                $amount = urlencode(1);
                $from_Currency = urlencode($this->default_currency);
                $to_Currency = urlencode($_REQUEST['currency_name']);
                $url = "http://www.google.com/finance/converter?a=$amount&from=$from_Currency&to=$to_Currency";

                if (function_exists('curl_init') AND $woocs_use_curl)
                {
                    $html = $this->file_get_contents_curl($url);
                } else
                {
                    $html = file_get_contents($url);
                }

                preg_match_all('/<span class=bld>(.*?)<\/span>/s', $html, $matches);
                if (isset($matches[1][0]))
                {
                    $request = floatval($matches[1][0]);
                } else
                {
                    $request = sprintf(__("no data for %s", 'woocommerce-currency-switcher'), $_REQUEST['currency_name']);
                }

                break;

            case 'appspot':
                $url = 'http://rate-exchange.appspot.com/currency?from=' . $this->default_currency . '&to=' . $_REQUEST['currency_name'];

                if (function_exists('curl_init') AND $woocs_use_curl)
                {
                    $res = $this->file_get_contents_curl($url);
                } else
                {
                    $res = file_get_contents($url);
                }


                $res = json_decode($res);
                if (isset($res->rate))
                {
                    $request = floatval($res->rate);
                } else
                {
                    $request = sprintf(__("no data for %s", 'woocommerce-currency-switcher'), $_REQUEST['currency_name']);
                }
                break;

            case 'rf':
                //http://www.cbr.ru/scripts/XML_daily_eng.asp?date_req=21/08/2015
                $xml_url = 'http://www.cbr.ru/scripts/XML_daily_eng.asp?date_req='; //21/08/2015
                $date = date('d/m/Y');
                $xml_url.=$date;
                if (function_exists('curl_init') AND $woocs_use_curl)
                {
                    $res = $this->file_get_contents_curl($xml_url);
                } else
                {
                    $res = file_get_contents($xml_url);
                }
                //***
                $xml = simplexml_load_string($res) or die("Error: Cannot create object");
                $xml = $this->object2array($xml);
                $rates = array();
                $nominal = array();
                //***
                if (isset($xml['Valute']))
                {
                    if (!empty($xml['Valute']))
                    {
                        foreach ($xml['Valute'] as $value)
                        {
                            $rates[$value['CharCode']] = floatval(str_replace(',', '.', $value['Value']));
                            $nominal[$value['CharCode']] = $value['Nominal'];
                        }
                    }
                }
                //***
                if (isset($rates))
                {
                    if (isset($rates[$this->default_currency]))
                    {
                        if ($_REQUEST['currency_name'] != 'RUB')
                        {
                            if (isset($_REQUEST['currency_name']))
                            {
                                $request = floatval($rates[$this->default_currency] / ($rates[$_REQUEST['currency_name']] / $nominal[$_REQUEST['currency_name']]));
                            } else
                            {
                                $request = sprintf(__("no data for %s", 'woocommerce-currency-switcher'), $_REQUEST['currency_name']);
                            }
                        } else
                        {
                            $request = $rates[$this->default_currency];
                        }
                    } else
                    {
                        $request = sprintf(__("base currecny is %s and its absent", 'woocommerce-currency-switcher'), $this->default_currency);
                    }
                } else
                {
                    $request = sprintf(__("no data for %s", 'woocommerce-currency-switcher'), $_REQUEST['currency_name']);
                }

                break;

            default:
                break;
        }


        //***
        if ($is_ajax)
        {
            echo $request;
            exit;
        } else
        {
            return $request;
        }
    }

    private function object2array($object)
    {
        return @json_decode(@json_encode($object), 1);
    }

    //ajax
    public function save_etalon()
    {
        if (!is_ajax())
        {
            //we need it just only for ajax update
            return "";
        }

        $currencies = $this->get_currencies();
        $currency_name = $_REQUEST['currency_name'];
        foreach ($currencies as $key => $currency)
        {
            if ($currency['name'] == $currency_name)
            {
                $currencies[$key]['is_etalon'] = 1;
            } else
            {
                $currencies[$key]['is_etalon'] = 0;
            }
        }
        update_option('woocs', $currencies);
        //+++ get curr updated values back
        $request = array();
        $this->default_currency = strtoupper($_REQUEST['currency_name']);
        $_REQUEST['no_ajax'] = TRUE;
        foreach ($currencies as $key => $currency)
        {
            if ($currency_name != $currency['name'])
            {
                $_REQUEST['currency_name'] = $currency['name'];
                $request[$key] = $this->get_rate();
            } else
            {
                $request[$key] = 1;
            }
        }

        echo json_encode($request);
        exit;
    }

    //order data registration
    public function woocommerce_thankyou_order_id($order_id)
    {
        $currencies = $this->get_currencies();
        //+++
        $rate = get_post_meta($order_id, '_woocs_order_rate', true);
        //if (intval($rate) === 0)
        {
            //condition (!$rate) is lock of chaning order currency looking it using link like
            //http://dev.currency-switcher.com/checkout/order-received/1003/?key=wc_order_55a52c81ad4ef
            //this needs if back in paypal process, but looks like it is more dangerous, so commented
            //update_post_meta($order_id, '_order_currency', $this->current_currency);
        }
        //+++
        update_post_meta($order_id, '_woocs_order_rate', $currencies[$this->current_currency]['rate']);
        wc_add_order_item_meta($order_id, '_woocs_order_rate', $currencies[$this->current_currency]['rate'], true);

        update_post_meta($order_id, '_woocs_order_base_currency', $this->default_currency);
        wc_add_order_item_meta($order_id, '_woocs_order_base_currency', $this->default_currency, true);

        update_post_meta($order_id, '_woocs_order_currency_changed_mannualy', 0);
        wc_add_order_item_meta($order_id, '_woocs_order_currency_changed_mannualy', 0, true);

        return $order_id;
    }

    //when admin complete order
    public function woocommerce_order_status_completed($order_id)
    {
        if ($this->is_multiple_allowed)
        {
            $currency = get_post_meta($order_id, '_order_currency', true);
            if (!empty($currency))
            {
                $_REQUEST['woocs_in_order_currency'] = $currency;
                $this->default_currency = $currency;
            }
        }
    }

    public function woocommerce_cart_totals_order_total_html($output)
    {
        //if ($this->current_currency == $this->default_currency)
        {
            return $output;
        }
        //experimental feature. Do not use it.
        //***
        $value = "&nbsp;(";
        //***
        $currencies = $this->get_currencies();
        $amount = WC()->cart->total / $currencies[$this->current_currency]['rate'];
        //***
        $cc = $this->current_currency;
        $this->current_currency = $this->default_currency;
        $value.=__('Total in basic currency: ', 'woocommerce-currency-switcher') . $this->wc_price($amount, false, array('currency' => $this->default_currency));
        $this->current_currency = $cc;
        $value.=")";
        return $output . $value;
    }

    public function wc_price_args($default_args)
    {
        if (in_array($this->current_currency, $this->no_cents))
        {
            $default_args['decimals'] = 0;
        }
        return $default_args;
    }

    public function woocommerce_email_actions($email_actions)
    {
        $_REQUEST['woocs_order_emails_is_sending'] = 1;
        return $email_actions;
    }

    public function woocommerce_before_resend_order_emails($order)
    {
        $currency = get_post_meta($order->id, '_order_currency', true);
        if (!empty($currency))
        {
            $_REQUEST['woocs_in_order_currency'] = $currency;
            $this->current_currency = $currency;
        }
    }

    //********************************************************************************

    public function wp_footer()
    {
        //return; //return it for releases
        ?>
        <script type="text/javascript">
            try {
                jQuery(function () {
                    jQuery.cookie('woocommerce_cart_hash', null, {path: '/'});
                });
            } catch (e) {

            }
        </script>
        <?php
    }

    //********************************************************************************

    public function render_html($pagepath, $data = array())
    {
        @extract($data);
        ob_start();
        include($pagepath);
        return ob_get_clean();
    }

    public function get_sign_rate($atts)
    {
        $sign = strtoupper($atts['sign']);
        $currencies = $this->get_currencies();
        $rate = 0;
        if (isset($currencies[$sign]))
        {
            $rate = $currencies[$sign]['rate'];
        }

        return $rate;
    }

    //for hook woocommerce_paypal_args
    function apply_conversion($paypal_args)
    {
        if (in_array($this->current_currency, $this->no_cents))
        {
            $paypal_args['currency_code'] = $this->current_currency;
            foreach ($paypal_args as $key => $value)
            {
                if (strpos($key, 'amount_') !== false)
                {
                    $paypal_args[$key] = number_format($value, 0, $this->decimal_sep, '');
                } else
                {
                    if (strpos($key, 'tax_cart') !== false)
                    {
                        $paypal_args[$key] = number_format($value, 0, $this->decimal_sep, '');
                    }
                }
            }
        }

        return $paypal_args;
    }

    public function woocommerce_price_html($price_html, $product)
    {

        static $customer_price_format = -1;
        if ($customer_price_format === -1)
        {
            $customer_price_format = get_option('woocs_customer_price_format', '');
        }

        //***
        $currencies = $this->get_currencies();

        //+++
        if (!empty($customer_price_format))
        {
            $txt = '<span class="woocs_price_code" data-product-id="' . $product->id . '">' . $customer_price_format . '</span>';
            $txt = str_replace('__PRICE__', $price_html, $txt);
            $price_html = str_replace('__CODE__', $this->current_currency, $txt);
        }


        //hide cents on front as html element
        if (!in_array($this->current_currency, $this->no_cents))
        {
            if ($currencies[$this->current_currency]['hide_cents'] == 1)
            {
                $price_html = preg_replace('/\.[0-9][0-9]/', '', $price_html);
            }
        }



        //add additional info in price html
        if ((get_option('woocs_price_info', 0) AND ! is_admin()) OR isset($_REQUEST['get_product_price_by_ajax']))
        {
            $info = "<ul>";
            $current_currency = $this->current_currency;
            foreach ($currencies as $сurr)
            {
                if ($сurr['name'] == $current_currency)
                {
                    continue;
                }
                $this->current_currency = $сurr['name'];
                $value = $product->price * $currencies[$сurr['name']]['rate'];
                $value = number_format($value, 2, $this->decimal_sep, '');
                if ($product->product_type != 'variable')
                {
                    $info.= "<li><b>" . $сurr['name'] . "</b>: " . $this->wc_price($value, false, array('currency' => $сurr['name'])) . "</li>";
                } else
                {
                    //https://gist.github.com/mikejolley/1600117
                    $min_value = $product->min_variation_price * $currencies[$сurr['name']]['rate'];
                    $min_value = number_format($min_value, 2, $this->decimal_sep, '');
                    //***
                    $max_value = $product->max_variation_price * $currencies[$сurr['name']]['rate'];
                    $max_value = number_format($max_value, 2, $this->decimal_sep, '');
                    //+++
                    $var_price = $this->wc_price($min_value, array('currency' => $сurr['name']));
                    $var_price.= ' - ';
                    $var_price.= $this->wc_price($max_value, array('currency' => $сurr['name']));
                    $info.= "<li><b>" . $сurr['name'] . "</b>: " . $var_price . "</li>";
                }
            }
            $this->current_currency = $current_currency;
            $info.= "</ul>";
            $info = '<div class="woocs_price_info"><span class="woocs_price_info_icon"></span>' . $info . '</div>';
            $price_html.=$info;
        }


        return $price_html;
    }

    //work in for multiple mode only from woocommerce 2.4
    //wp-content\plugins\woocommerce\includes\class-wc-product-variable.php #303
    public function woocommerce_variation_prices($prices_array)
    {
        if ($this->current_currency != $this->default_currency)
        {
            if (!empty($prices_array) AND is_array($prices_array))
            {
                foreach ($prices_array as $key => $values)
                {
                    foreach ($values as $k => $v)
                    {
                        $prices_array[$key][$k] = $this->woocs_exchange_value(floatval($v));
                    }
                }
            }
        }

        return $prices_array;
    }
    
    public function woocommerce_get_variation_prices_hash($price_hash, $product, $display){
        //***
    }

    public function woocommerce_coupon_get_discount_amount($discount, $discounting_amount, $cart_item, $single, $coupon)
    {

        if (version_compare(WOOCOMMERCE_VERSION, '2.4', '<') OR $this->is_multiple_allowed)
        {
            if (!$coupon->is_type(array('percent_product', 'percent')))
            {
                $discount = $this->woocs_exchange_value(floatval($discount));
            }
        }

        return $discount;
    }

    //wp filter for values which is in basic currency and no possibility do it automatically
    public function woocs_exchange_value($value)
    {
        $currencies = $this->get_currencies();
        $value = $value * $currencies[$this->current_currency]['rate'];
        $value = number_format($value, 2, $this->decimal_sep, '');
        return $value;
    }

    //set it to default
    public function reset_currency()
    {
        $this->storage->set_val('woocs_current_currency', $this->default_currency);
        $this->current_currency = $this->default_currency;
    }

    public function wpo_wcpdf_order_number($order_id)
    {
        //compatibility for https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/stats/
        //set order currency instead selected on front
        $currency = get_post_meta($order_id, '_order_currency', TRUE);
        if (!empty($currency))
        {
            $this->current_currency = $currency;
        }

        return $order_id;
    }

    public function woocommerce_get_order_currency($order_currency, $order)
    {

        if (!is_ajax() AND ! is_admin() AND is_object($order))
        {

            $currency = get_post_meta($order->id, '_order_currency', TRUE);
            if (!empty($currency))
            {
                $this->current_currency = $currency;
            }
        }

        return $order_currency;
    }

    public function woocommerce_view_order($order_id)
    {

        if (!is_ajax() AND ! is_admin())
        {
            $currency = get_post_meta($order_id, '_order_currency', TRUE);
            if (!empty($currency))
            {
                $this->current_currency = $currency;
            }
        }

        return $order_id;
    }

    public function woocommerce_package_rates($rates)
    {
        if ($this->current_currency != $this->default_currency)
        {
            $currencies = $this->get_currencies();
            foreach ($rates as $rate)
            {
                $value = $rate->cost * $currencies[$this->current_currency]['rate'];
                $rate->cost = number_format(floatval($value), 2, $this->decimal_sep, '');
            }
        }

        return $rates;
    }

    public function wcml_raw_price_amount($value)
    {
        return $this->woocs_exchange_value($value);
    }

    //ajax
    public function woocs_convert_currency()
    {
        $currencies = $this->get_currencies();
        $v = $currencies[$_REQUEST['to']]['rate'] / $currencies[$_REQUEST['from']]['rate'];
        if (in_array($_REQUEST['to'], $this->no_cents))
        {
            $_REQUEST['precision'] = 0;
        }
        $value = number_format($v * $_REQUEST['amount'], intval($_REQUEST['precision']), $this->decimal_sep, '');


        wp_die($value);
    }

    //for refreshing mini-cart widget
    public function woocommerce_before_mini_cart()
    {
        $_REQUEST['woocs_woocommerce_before_mini_cart'] = 'mini_cart_refreshing';
        WC()->cart->calculate_totals();
    }

    //for refreshing mini-cart widget
    public function woocommerce_after_mini_cart()
    {
        unset($_REQUEST['woocs_woocommerce_before_mini_cart']);
    }

    //ajax
    public function woocs_rates_current_currency()
    {
        wp_die(do_shortcode('[woocs_rates exclude="' . $_REQUEST['exclude'] . '" precision="' . $_REQUEST['precision'] . '" current_currency="' . $_REQUEST['current_currency'] . '"]'));
    }

    //log test data while makes debbuging
    public function log($string)
    {
        $handle = fopen(WOOCS_PATH . 'log.txt', 'a+');
        $string.= PHP_EOL;
        fwrite($handle, $string);
        fclose($handle);
    }

    public function wc_price($price, $convert = true, $args = array())
    {
        $decimals = 2;
        extract(apply_filters('wc_price_args', wp_parse_args($args, array(
            'ex_tax_label' => false,
            'currency' => '',
            'decimal_separator' => $this->decimal_sep,
            'thousand_separator' => $this->thousands_sep,
            'decimals' => $decimals,
            'price_format' => $this->woocommerce_price_format()
        ))));

        //***
        $currencies = $this->get_currencies();
        if (isset($currencies[$currency]))
        {
            if ($currencies[$currency]['hide_cents'])
            {
                $decimals = 0;
            }
        }

        //***

        $negative = $price < 0;
        if ($convert)
        {
            //$price = apply_filters('raw_woocommerce_price', floatval($negative ? $price * -1 : $price ));
            $price = $this->raw_woocommerce_price(floatval($negative ? $price * -1 : $price ));
        }
        $price = apply_filters('formatted_woocommerce_price', number_format($price, $decimals, $decimal_separator, $thousand_separator), $price, $decimals, $decimal_separator, $thousand_separator);

        if (apply_filters('woocommerce_price_trim_zeros', false) && $decimals > 0)
        {
            $price = wc_trim_zeros($price);
        }

        $formatted_price = ( $negative ? '-' : '' ) . sprintf($price_format, get_woocommerce_currency_symbol($currency), $price);
        $return = '<span class="woocs_amount">' . $formatted_price . '</span>';

        if ($ex_tax_label && wc_tax_enabled())
        {
            $return .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
        }

        return apply_filters('wc_price', $return, $price, $args);
    }

    //woo hook
    public function woocommerce_product_is_on_sale($value, $product)
    {
        //$product->get_sale_price() !== $product->get_regular_price() && $product->get_sale_price() === $product->get_price()
        $is_sale = false;
        $sale_price = $product->sale_price;
        $regular_price = $product->regular_price;
        $price = $product->price;

        //***
        //https://www.skyverge.com/blog/get-a-list-of-woocommerce-sale-products/
        if ($product->product_type == 'variable')
        {
            /*
              $_REQUEST['woocs_block_price_hook'] = 1;
              remove_all_filters('woocommerce_product_is_on_sale');
              if ($product->is_on_sale())
              {
              $is_sale = true;
              }
              add_filter('woocommerce_product_is_on_sale', array($this, 'woocommerce_product_is_on_sale'), 9999, 2);
              unset($_REQUEST['woocs_block_price_hook']);
             */
        } else
        {
            if ($sale_price !== $regular_price AND ( $price === $sale_price))
            {
                $is_sale = true;
            }
        }


        return $is_sale;
    }

    //woo hook
    public function woocommerce_reports_get_order_report_data($query_type, $data)
    {
        //*** not called
    }

    //woo hook
    //wp-content\plugins\woocommerce\includes\shipping\free-shipping\class-wc-shipping-free-shipping.php #192
    public function woocommerce_shipping_free_shipping_is_available($is_available, $package)
    {

        $free_shipping_settings = get_option('woocommerce_free_shipping_settings');
        $min_amount = $free_shipping_settings['min_amount'];

        if ($min_amount > 0)
        {
            if ($this->current_currency != $this->default_currency)
            {
                $min_amount = $this->woocs_exchange_value($min_amount);
                if ($min_amount <= $package['contents_cost'])
                {
                    $is_available = true;
                } else
                {
                    $is_available = false;
                }
            }
        }

        return $is_available;
    }

    //ajax
    //for price redrawing on front if site using cache plugin functionality
    public function woocs_get_products_price_html()
    {
        $result = array();
        if (isset($_REQUEST['products_ids']))
        {
            $_REQUEST['get_product_price_by_ajax'] = 1;
            add_action('woocommerce_price_html', array($this, 'woocommerce_price_html'), 1, 2);
            $products_ids = $_REQUEST['products_ids'];
            //***
            if (!empty($products_ids) AND is_array($products_ids))
            {
                foreach ($products_ids as $p_id)
                {
                    $product = new WC_Product($p_id);
                    $result[$p_id] = $product->get_price_html();
                }
            }
        }
        wp_die(json_encode($result));
    }

    //count amount in basic currency from any currency
    public function back_convert($amount, $rate, $precision = 4)
    {
        return number_format((1 / $rate) * $amount, $precision, '.', '');
    }

    //recalculation order to basic currency data if order is in any another currency
    public function recalculate_order($order_id)
    {

        $order_currency = get_post_meta($order_id, '_order_currency', true);
        //lets avoid recalculation for order which is already in 
        if ($order_currency == $this->default_currency OR empty($order_currency))
        {
            return;
        }

        //***
        $currencies = $this->get_currencies();
        $_woocs_order_rate = get_post_meta($order_id, '_woocs_order_rate', true);
        if (empty($_woocs_order_rate))
        {
            $_woocs_order_rate = $currencies[$order_currency]['rate'];
        }
        //***

        update_post_meta($order_id, '_woocs_order_currency', $this->default_currency);
        update_post_meta($order_id, '_order_currency', $this->default_currency);

        update_post_meta($order_id, '_woocs_order_base_currency', $this->default_currency);
        wc_update_order_item_meta($order_id, '_woocs_order_base_currency', $this->default_currency);

        update_post_meta($order_id, '_woocs_order_rate', 1);
        wc_update_order_item_meta($order_id, '_woocs_order_rate', 1);

        update_post_meta($order_id, '_woocs_order_currency_changed_mannualy', time());
        wc_add_order_item_meta($order_id, '_woocs_order_currency_changed_mannualy', time(), true);

        //***

        $_order_shipping = get_post_meta($order_id, '_order_shipping', true);
        update_post_meta($order_id, '_order_shipping', $this->back_convert($_order_shipping, $_woocs_order_rate));

        $_order_total = get_post_meta($order_id, '_order_total', true);
        update_post_meta($order_id, '_order_total', $this->back_convert($_order_total, $_woocs_order_rate));

        $_refund_amount = get_post_meta($order_id, '_refund_amount', true);
        update_post_meta($order_id, '_refund_amount', $this->back_convert($_refund_amount, $_woocs_order_rate));

        $_cart_discount_tax = get_post_meta($order_id, '_cart_discount_tax', true);
        update_post_meta($order_id, '_cart_discount_tax', $this->back_convert($_cart_discount_tax, $_woocs_order_rate));

        $_order_tax = get_post_meta($order_id, '_order_tax', true);
        update_post_meta($order_id, '_order_tax', $this->back_convert($_order_tax, $_woocs_order_rate));

        $_order_shipping_tax = get_post_meta($order_id, '_order_shipping_tax', true);
        update_post_meta($order_id, '_order_shipping_tax', $this->back_convert($_order_shipping_tax, $_woocs_order_rate));

        $_cart_discount = get_post_meta($order_id, '_cart_discount', true);
        update_post_meta($order_id, '_cart_discount', $this->back_convert($_cart_discount, $_woocs_order_rate));

        //***

        global $wpdb;
        $get_items_sql = $wpdb->prepare("SELECT order_item_id,order_item_type FROM {$wpdb->prefix}woocommerce_order_items WHERE order_id = %d ", $order_id);
        $line_items = $wpdb->get_results($get_items_sql, ARRAY_N);
        if (!empty($line_items) AND is_array($line_items))
        {
            foreach ($line_items as $v)
            {
                $order_item_id = $v[0];
                $order_item_type = $v[1];

                switch ($order_item_type)
                {
                    case 'line_item':

                        $amount = wc_get_order_item_meta($order_item_id, '_line_subtotal', true);
                        wc_update_order_item_meta($order_item_id, '_line_subtotal', $this->back_convert($amount, $_woocs_order_rate, 2));

                        $amount = wc_get_order_item_meta($order_item_id, '_line_total', true);
                        wc_update_order_item_meta($order_item_id, '_line_total', $this->back_convert($amount, $_woocs_order_rate, 2));

                        $amount = wc_get_order_item_meta($order_item_id, '_line_subtotal_tax', true);
                        wc_update_order_item_meta($order_item_id, '_line_subtotal_tax', $this->back_convert($amount, $_woocs_order_rate, 2));

                        $amount = wc_get_order_item_meta($order_item_id, '_line_tax', true);
                        wc_update_order_item_meta($order_item_id, '_line_tax', $this->back_convert($amount, $_woocs_order_rate, 2));

                        $_line_tax_data = wc_get_order_item_meta($order_item_id, '_line_tax_data', true);
                        if (!empty($_line_tax_data) AND is_array($_line_tax_data))
                        {
                            foreach ($_line_tax_data as $key => $values)
                            {
                                if (!empty($values))
                                {
                                    if (is_array($values))
                                    {
                                        foreach ($values as $k => $value)
                                        {
                                            if (is_numeric($value))
                                            {
                                                $_line_tax_data[$key][$k] = $this->back_convert($value, $_woocs_order_rate, 2);
                                            }
                                        }
                                    } else
                                    {
                                        if (is_numeric($values))
                                        {
                                            $_line_tax_data[$key] = $this->back_convert($values, $_woocs_order_rate, 2);
                                        }
                                    }
                                }
                            }
                        }

                        wc_update_order_item_meta($order_item_id, '_line_tax_data', $_line_tax_data);

                        break;

                    case 'shipping':
                        $amount = wc_get_order_item_meta($order_item_id, 'cost', true);
                        wc_update_order_item_meta($order_item_id, 'cost', $this->back_convert($amount, $_woocs_order_rate, 2));


                        $taxes = wc_get_order_item_meta($order_item_id, 'taxes', true);

                        if (!empty($taxes) AND is_array($taxes))
                        {
                            foreach ($taxes as $key => $values)
                            {
                                if (!empty($values))
                                {
                                    if (is_array($values))
                                    {
                                        foreach ($values as $k => $value)
                                        {
                                            if (is_numeric($value))
                                            {
                                                $taxes[$key][$k] = $this->back_convert($value, $_woocs_order_rate, 2);
                                            }
                                        }
                                    } else
                                    {
                                        if (is_numeric($values))
                                        {
                                            $taxes[$key] = $this->back_convert($values, $_woocs_order_rate, 2);
                                        }
                                    }
                                }
                            }
                        }

                        wc_update_order_item_meta($order_item_id, 'taxes', $taxes);

                        break;

                    case 'tax':
                        $amount = wc_get_order_item_meta($order_item_id, 'tax_amount', true);
                        wc_update_order_item_meta($order_item_id, 'tax_amount', $this->back_convert($amount, $_woocs_order_rate, 3));

                        $amount = wc_get_order_item_meta($order_item_id, 'shipping_tax_amount', true);
                        wc_update_order_item_meta($order_item_id, 'shipping_tax_amount', $this->back_convert($amount, $_woocs_order_rate, 2));

                        break;

                    default:
                        break;
                }
            }
        }

        //***

        $order = new WC_Order($order_id);
        $refunds = $order->get_refunds();

        if (!empty($refunds))
        {
            foreach ($refunds as $refund)
            {
                $post_id = $refund->id;
                $amount = get_post_meta($post_id, '_refund_amount', true);
                update_post_meta($post_id, '_refund_amount', $this->back_convert($amount, $_woocs_order_rate, 2));
                $amount = get_post_meta($post_id, '_order_total', true);
                update_post_meta($post_id, '_order_total', $this->back_convert($amount, $_woocs_order_rate, 2));
                update_post_meta($post_id, '_order_currency', $this->default_currency);
            }
        }
    }

    //ajax
    public function woocs_recalculate_order_data()
    {
        $this->recalculate_order($_REQUEST['order_id']);
        wp_die('done');
    }

}

//+++
if (isset($_GET['P3_NOCACHE']))
{
    //stupid trick for that who believes in P3
    return;
}
//+++
$WOOCS = new WOOCS();
$GLOBALS['WOOCS'] = $WOOCS;
add_action('init', array($WOOCS, 'init'), 1);



//includes/wc-core-functions.php #156
//includes/wc-formatting-functions.php #297
//includes/admin/post-types/meta-boxes/class-wc-meta-box-order-totals.php
//wp-content\plugins\woocommerce\includes\wc-formatting-functions.php
//wp-content\plugins\woocommerce\includes\wc-cart-functions.php
//wp-content\plugins\woocommerce\includes\wc-conditional-functions.php
//wp-content\plugins\woocommerce\includes\class-wc-cart.php
//wp-content\plugins\woocommerce\includes\abstracts\abstract-wc-product.php


