<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div class="subsubsub_section">
    <div class="se-pre-con"></div>
    <br class="clear" />

    <?php
    $welcome_curr_options = array();
    if (!empty($currencies) AND is_array($currencies))
    {
        foreach ($currencies as $key => $currency)
        {
            $welcome_curr_options[$currency['name']] = $currency['name'];
        }
    }
    //+++
    $options = array(
        array(
            'name' => '',
            'type' => 'title',
            'desc' => '',
            'id' => 'woocs_general_settings'
        ),
        array(
            'name' => __('Drop-down view', 'woocommerce-currency-switcher'),
            'desc' => __('How to display currency switcher drop-down on the front of your site', 'woocommerce-currency-switcher'),
            'id' => 'woocs_drop_down_view',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                'ddslick' => __('ddslick', 'woocommerce-currency-switcher'),
                'chosen' => __('chosen', 'woocommerce-currency-switcher'),
                'chosen_dark' => __('chosen dark', 'woocommerce-currency-switcher'),
                'wselect' => __('wSelect', 'woocommerce-currency-switcher'),
                'no' => __('simple drop-down', 'woocommerce-currency-switcher'),
                'flags' => __('show as flags', 'woocommerce-currency-switcher'),
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Show flags by default', 'woocommerce-currency-switcher'),
            'desc' => __('Show/hide flags on the front drop-down', 'woocommerce-currency-switcher'),
            'id' => 'woocs_show_flags',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Show money signs', 'woocommerce-currency-switcher'),
            'desc' => __('Show/hide money signs on the front drop-down', 'woocommerce-currency-switcher'),
            'id' => 'woocs_show_money_signs',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Show price info icon', 'woocommerce-currency-switcher'),
            'desc' => __('Show info icon near the price of the product which while its under hover shows prices of products in all currencies', 'woocommerce-currency-switcher'),
            'id' => 'woocs_price_info',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Is multiple allowed', 'woocommerce-currency-switcher'),
            'desc' => __('Customer will pay with selected currency or with default currency.', 'woocommerce-currency-switcher'),
            'id' => 'woocs_is_multiple_allowed',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Welcome currency', 'woocommerce-currency-switcher'),
            'desc' => __('In wich currency show prices for first visit of your customer on your site', 'woocommerce-currency-switcher'),
            'id' => 'woocs_welcome_currency',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => $welcome_curr_options,
            'desc_tip' => true
        ),
        array(
            'name' => __('Currency aggregator', 'woocommerce-currency-switcher'),
            'desc' => __('Currency aggregators', 'woocommerce-currency-switcher'),
            'id' => 'woocs_currencies_aggregator',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                'yahoo' => __('http://finance.yahoo.com', 'woocommerce-currency-switcher'),
                'google' => __('http://google.com/finance', 'woocommerce-currency-switcher'),
                'appspot' => __('http://rate-exchange.appspot.com', 'woocommerce-currency-switcher'),
                'rf' => __('http://www.cbr.ru - russian centrobank', 'woocommerce-currency-switcher'),
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('CURL for aggregators', 'woocommerce-currency-switcher'),
            'desc' => __('You can use it if aggregators doesn works with file_get_contents because of security reasons. If all is ok leave it No!', 'woocommerce-currency-switcher'),
            'id' => 'woocs_use_curl',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Currency storage', 'woocommerce-currency-switcher'),
            'desc' => __('In some servers there is troubles with sessions, and after currency selecting its reset to welcome currency or geo ip currency. In such case use transient!', 'woocommerce-currency-switcher'),
            'id' => 'woocs_storage',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                'session' => __('session', 'woocommerce-currency-switcher'),
                //'cookie' => __('cookie', 'woocommerce-currency-switcher'),
                'transient' => __('transient', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Use GeoLocation', 'woocommerce-currency-switcher'),
            'desc' => __('Use GeoLocation rules for your currencies. This feature uses native WC_Geolocation php class! Works from woocommerce >= 2.3.0', 'woocommerce-currency-switcher'),
            'id' => 'woocs_use_geo_rules',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Rate auto update', 'woocommerce-currency-switcher'),
            'desc' => __('Currencies rate auto update by wp cron. Use it for your own risk! Premium only!', 'woocommerce-currency-switcher'),
            'id' => 'woocs_currencies_rate_auto_update',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                'no' => __('no auto update - premium only', 'woocommerce-currency-switcher')
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Hide switcher on checkout page', 'woocommerce-currency-switcher'),
            'desc' => __('Hide switcher on checkout page for any of your reason. Better restrike for users change currency on checkout page in multiple mode. Premium only', 'woocommerce-currency-switcher'),
            'id' => 'woocs_restrike_on_checkout_page',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher'),
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('I am using cache plugin on my site', 'woocommerce-currency-switcher'),
            'desc' => __('Set Yes here ONLY if you are REALLY use cache plugin for your site, for example like Super cache or Hiper cache (doesn matter). + Set "Custom price format", for example: __PRICE__ (__CODE__). After enabling this feature - clean your cache to make it works. It will allow show prices in selected currency on all pages of site. Fee for this feature - additional AJAX queries for products prices redrawing.', 'woocommerce-currency-switcher'),
            'id' => 'woocs_shop_is_cached',
            'type' => 'select',
            'class' => 'chosen_select',
            'css' => 'min-width:300px;',
            'options' => array(
                0 => __('No', 'woocommerce-currency-switcher'),
                1 => __('Yes', 'woocommerce-currency-switcher'),
            ),
            'desc_tip' => true
        ),
        array(
            'name' => __('Custom money signs', 'woocommerce-currency-switcher'),
            'desc' => __('Add your money symbols in your shop. Example: $USD,AAA,AUD$,DDD - separated by commas.', 'woocommerce-currency-switcher'),
            'id' => 'woocs_customer_signs',
            'type' => 'textarea',
            'std' => '', // WooCommerce < 2.0
            'default' => '', // WooCommerce >= 2.0
            'css' => 'min-width:500px;',
            'desc_tip' => true
        ),
        array(
            'name' => __('Custom price format on shop page', 'woocommerce-currency-switcher'),
            'desc' => __('Set your format how to display price on shop page. Use keys: __CODE__,__PRICE__. Leave it empty to use default format. Example: __PRICE__ (__CODE__)', 'woocommerce-currency-switcher'),
            'id' => 'woocs_customer_price_format',
            'type' => 'text',
            'std' => '', // WooCommerce < 2.0
            'default' => '', // WooCommerce >= 2.0
            'css' => 'min-width:500px;',
            'desc_tip' => true
        ),
        array(
            'name' => __('Prices without cents', 'woocommerce-currency-switcher'),
            'desc' => __('Recount prices without cents everywhere like in JPY and TWD which by its nature have not cents. Use comma. Example: UAH,RUB. Test it for checkout after set!', 'woocommerce-currency-switcher'),
            'id' => 'woocs_no_cents',
            'type' => 'text',
            'std' => '', // WooCommerce < 2.0
            'default' => '', // WooCommerce >= 2.0
            'css' => 'min-width:500px;',
            'desc_tip' => true
        ),
        array('type' => 'sectionend', 'id' => 'woocs_general_settings')
    );
    ?>


    <div class="section">

        <h3><?php printf(__('WooCommerce Currency Switcher v.%s', 'woocommerce-currency-switcher'), $this->the_plugin_version) ?></h3>

        <div id="tabs">

            <?php if (version_compare(WOOCOMMERCE_VERSION, '2.1', '<')): ?>

                <b style="color: red;"><?php _e("Your version of WooCommerce plugin is too obsolete. Update minimum to 2.1 version!!!", 'woocommerce-currency-switcher'); ?></b><br />

            <?php endif; ?>

            <ul>
                <li><a href="#tabs-1"><?php _e("Currencies", 'woocommerce-currency-switcher') ?></a></li>
                <li><a href="#tabs-2"><?php _e("Options", 'woocommerce-currency-switcher') ?></a></li>
                <?php if ($this->is_use_geo_rules()): ?>
                    <li><a href="#tabs-3"><?php _e("GeoIP options", 'woocommerce-currency-switcher') ?></a></li>
                <?php endif; ?>
                <li><a href="#tabs-4"><?php _e("Info", 'woocommerce-currency-switcher') ?></a></li>

            </ul>


            <div id="tabs-1">

                <div style="display: none;">
                    <div id="woocs_item_tpl"><?php
                        $empty = array(
                            'name' => '',
                            'rate' => 0,
                            'symbol' => '',
                            'position' => '',
                            'is_etalon' => 0,
                            'description' => '',
                            'hide_cents' => 0
                        );
                        woocs_print_currency($this, $empty);
                        ?>
                    </div>
                </div>

                <strong style="color: red;">In the free version of the plugin you can operate with 2 ANY currencies only. <a href="http://codecanyon.net/item/woocommerce-currency-switcher/8085217?ref=realmag777" target="_blank">Premium version of the plugin</a></strong><br />


                <ul id="woocs_list">
                    <?php
                    if (!empty($currencies) AND is_array($currencies))
                    {
                        foreach ($currencies as $key => $currency)
                        {
                            woocs_print_currency($this, $currency);
                        }
                    }
                    ?>
                </ul><br />


                <a href="http://en.wikipedia.org/wiki/ISO_4217#Active_codes" target="_blank" class="button button-primary button-large"><?php _e("Read wiki about Currency Active codes  <-  Get right currencies codes here if you are not sure about it!", 'woocommerce-currency-switcher') ?></a>

            </div>
            <div id="tabs-2">
                <?php woocommerce_admin_fields($options); ?>
            </div>

            <?php if ($this->is_use_geo_rules()): ?>
                <div id="tabs-3">

                    <?php if (version_compare(WOOCOMMERCE_VERSION, '2.3', '<')): ?>

                        <b style="color: red;"><?php _e("GeoIP works from v.2.3 of the WooCommerce plugin and no with minor versions of WooCommerce!!", 'woocommerce-currency-switcher'); ?></b><br />

                    <?php endif; ?>

                    <ul>
                        <?php
                        if (!empty($currencies) AND is_array($currencies))
                        {
                            $c = new WC_Countries();
                            foreach ($currencies as $key => $currency)
                            {
                                $rules = array();
                                if (isset($geo_rules[$key]))
                                {
                                    $rules = $geo_rules[$key];
                                }
                                ?>
                                <li>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td>
                                                <div style="width: 70px;<?php if ($currency['is_etalon']): ?>color: red;<?php endif; ?>"><strong><?php echo $key ?></strong>:</div>
                                            </td>
                                            <td style="width: 100%;">
                                                <select name="woocs_geo_rules[<?php echo $currency['name'] ?>][]" multiple="" size="1" style="max-width: 100%;" class="chosen_select">
                                                    <option value="0"></option>
                                                    <?php foreach ($c->get_countries() as $key => $value): ?>
                                                        <option <?php echo(in_array($key, $rules) ? 'selected=""' : '') ?> value="<?php echo $key ?>"><?php echo $value ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                </li>
                                <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php else: ?>
                <input type="hidden" name="woocs_geo_rules" value="" />
            <?php endif; ?>



            <div id="tabs-4">
                <ul>
                    <li><a href="http://currency-switcher.com/documentation/" target="_blank" class="button"><?php _e("Documentation", 'woocommerce-currency-switcher') ?></a></li>
                    <li><a href="http://currency-switcher.com/category/faq/" target="_blank" class="button"><?php _e("FAQ", 'woocommerce-currency-switcher') ?></a></li>

                    <li><a href="http://en.wikipedia.org/wiki/ISO_4217#Active_codes" target="_blank" class="button button-primary button-large"><?php _e("Read wiki about Currency Active codes  <-  Get right currencies codes here", 'woocommerce-currency-switcher') ?></a></li>
                    <li><a href="https://www.paypal.com/multicurrency" target="_blank" class="button"><?php _e("PayPal Multiple Currencies", 'woocommerce-currency-switcher') ?></a></li>

                    <li>
                        <a href="https://share.payoneer.com/nav/6I2wmtpBuitGE6ZnmaMXLYlP8iriJ-63OMLi3PT8SRGceUjGY1dvEhDyuAGBp91DEmf8ugfF3hkUU1XhP_C6Jg2" target="_blank"><img src="<?php echo WOOCS_LINK ?>/img/100125.png" alt=""></a>
                    </li>

                    <li>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/wUoM9EHjnYs" frameborder="0" allowfullscreen></iframe>
                    </li>

                    <li><a href="http://currency-switcher.com/i-cant-add-flags-what-to-do/" target="_blank" class="button"><?php _e("I cant add flags! What to do?", 'woocommerce-currency-switcher') ?></a></li>
                    <li><a href="http://currency-switcher.com/using-geolocation-causes-problems-doesnt-seem-to-work-for-me/" target="_blank" class="button"><?php _e("Using Geolocation causes problems, doesn’t seem to work for me", 'woocommerce-currency-switcher') ?></a></li>
                    <li><a href="http://currency-switcher.com/documentation/assets/img/screen2.png" target="_blank" class="button"><?php _e("The plugin options example screen", 'woocommerce-currency-switcher') ?></a></li>
                    <li>
                        <a href="http://codecanyon.net/item/woof-woocommerce-products-filter/11498469?ref=realmag777" target="_blank"><img src="<?php echo WOOCS_LINK ?>img/woof_banner.jpg" /></a>
                    </li>                    
                </ul>
            </div>
        </div>






    </div>
    <br />


    <b style="color:red;"><?php _e('Hint'); ?>:</b>&nbsp;<?php _e('To update all currencies rates by one click - press radio button of the default currency and then press "Save changes" button!', 'woocommerce-currency-switcher'); ?><br />





    <hr />

    <i>In the free version of the plugin you can operate with 2 ANY currencies only. If you want more currencies and features you are need make upgrade to the premium version of the plugin</i><br />

    <table style="width: 100%;">
        <tr>
            <td style="width: 50%;">
                <h3><?php _e("Get the full version of the plugin from Codecanyon", 'woocommerce-currency-switcher') ?>:</h3>
                <a href="http://codecanyon.net/item/woocommerce-currency-switcher/8085217?ref=realmag777" target="_blank"><img src="<?php echo WOOCS_LINK ?>img/woocs_banner.jpg" alt="<?php _e("full version of the plugin", 'woocommerce-currency-switcher'); ?>" /></a>
            </td>
            <td style="width: 50%;">
                <h3><?php _e("Get WooCommerce Products Filter", 'woocommerce-currency-switcher') ?>:</h3>
                <a href="http://codecanyon.net/item/woof-woocommerce-products-filter/11498469?ref=realmag777" target="_blank"><img src="<?php echo WOOCS_LINK ?>img/woof_banner.png" alt="<?php _e("WOOF", 'woocommerce-currency-switcher'); ?>" /></a>
            </td>
        </tr>
    </table>



    <div class="info_popup" style="display: none;"></div>

</div>


<style type="text/css">
    label[for=woocs_currencies_rate_auto_update]
    {
        color:red;
    }
</style>

<script type="text/javascript">
    jQuery(function () {

        jQuery.fn.life = function (types, data, fn) {
            jQuery(this.context).on(types, this.selector, data, fn);
            return this;
        };

        jQuery("#tabs").tabs();

        jQuery('body').append('<div id="woocs_buffer" style="display: none;"></div>');

        jQuery("#woocs_list").sortable();




        jQuery('.woocs_is_etalon').life('click', function () {
            jQuery('.woocs_is_etalon').next('input[type=hidden]').val(0);
            jQuery('.woocs_is_etalon').prop('checked', 0);
            jQuery(this).next('input[type=hidden]').val(1);
            jQuery(this).prop('checked', 1);
            //instant save
            var currency_name = jQuery(this).parent().find('input[name="woocs_name[]"]').val();
            if (currency_name.length) {
                woocs_show_stat_info_popup('Loading ...');
                var data = {
                    action: "woocs_save_etalon",
                    currency_name: currency_name
                };
                jQuery.post(ajaxurl, data, function (request) {
                    try {
                        request = jQuery.parseJSON(request);
                        jQuery.each(request, function (index, value) {
                            var elem = jQuery('input[name="woocs_name[]"]').filter(function () {
                                return this.value.toUpperCase() == index;
                            });

                            if (elem) {
                                jQuery(elem).parent().find('input[name="woocs_rate[]"]').val(value);
                                jQuery(elem).parent().find('input[name="woocs_rate[]"]').text(value);
                            }
                        });

                        woocs_hide_stat_info_popup();
                        woocs_show_info_popup('Save changes please!', 1999);
                    } catch (e) {
                        woocs_hide_stat_info_popup();
                        alert('Request error! Try later or another agregator!');
                    }
                });
            }

            return true;
        });


        jQuery('.woocs_flag_input').life('change', function ()
        {
            jQuery(this).next('a.woocs_flag').find('img').attr('src', jQuery(this).val());
        });

        jQuery('.woocs_flag').life('click', function ()
        {
            var input_object = jQuery(this).prev('input[type=hidden]');
            window.send_to_editor = function (html)
            {
                woocs_insert_html_in_buffer(html);
                var imgurl = jQuery('#woocs_buffer').find('a').eq(0).attr('href');
                woocs_insert_html_in_buffer("");
                jQuery(input_object).val(imgurl);
                jQuery(input_object).trigger('change');
                tb_remove();
            };
            tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true');

            return false;
        });

        jQuery('.woocs_finance_yahoo').life('click', function () {
            var currency_name = jQuery(this).parent().find('input[name="woocs_name[]"]').val();
            var _this = this;
            jQuery(_this).parent().find('input[name="woocs_rate[]"]').val('loading ...');
            var data = {
                action: "woocs_get_rate",
                currency_name: currency_name
            };
            jQuery.post(ajaxurl, data, function (value) {
                jQuery(_this).parent().find('input[name="woocs_rate[]"]').val(value);
            });

            return false;
        });

    });


    function woocs_insert_html_in_buffer(html) {
        jQuery('#woocs_buffer').html(html);
    }
    function woocs_get_html_from_buffer() {
        return jQuery('#woocs_buffer').html();
    }

    function woocs_show_info_popup(text, delay) {
        jQuery(".info_popup").text(text);
        jQuery(".info_popup").fadeTo(400, 0.9);
        window.setTimeout(function () {
            jQuery(".info_popup").fadeOut(400);
        }, delay);
    }

    function woocs_show_stat_info_popup(text) {
        jQuery(".info_popup").text(text);
        jQuery(".info_popup").fadeTo(400, 0.9);
    }


    function woocs_hide_stat_info_popup() {
        window.setTimeout(function () {
            jQuery(".info_popup").fadeOut(400);
        }, 500);
    }



</script>

<?php

function woocs_print_currency($_this, $currency)
{
    global $WOOCS;
    ?>
    <li style="width: 95%;">
        <input class="help_tip woocs_is_etalon" data-tip="<?php _e("Set etalon main currency. This should be the currency in which the price of goods exhibited!", 'woocommerce-currency-switcher') ?>" type="radio" <?php checked(1, $currency['is_etalon']) ?> />
        <input type="hidden" name="woocs_is_etalon[]" value="<?php echo $currency['is_etalon'] ?>" />
        <input type="text" value="<?php echo $currency['name'] ?>" name="woocs_name[]" class="woocs-text" placeholder="<?php _e("Exmpl.: USD,EUR", 'woocommerce-currency-switcher') ?>" />
        <select class="woocs-drop-down" name="woocs_symbol[]">
            <?php foreach ($_this->currency_symbols as $symbol) : ?>
                <option value="<?php echo md5($symbol) ?>" <?php selected(md5($currency['symbol']), md5($symbol)) ?>><?php echo $symbol; ?></option>
            <?php endforeach; ?>
        </select>
        <select class="woocs-drop-down" name="woocs_position[]" style="width: 120px;">
            <option value="0"><?php _e("Select symbol position", 'woocommerce-currency-switcher'); ?></option>
            <?php foreach ($_this->currency_positions as $position) : ?>
                <option value="<?php echo $position ?>" <?php selected($currency['position'], $position) ?>><?php echo str_replace('_', ' ', $position); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" value="<?php echo $currency['rate'] ?>" name="woocs_rate[]" class="woocs-text" placeholder="<?php _e("exchange rate", 'woocommerce-currency-switcher') ?>" />
        <button class="button woocs_finance_yahoo help_tip" data-tip="<?php _e("Press this button if you want get currency rate from the selected aggregator above!", 'woocommerce-currency-switcher') ?>"><?php _e("finance", 'woocommerce-currency-switcher'); ?>.<?php echo get_option('woocs_currencies_aggregator', 'yahoo') ?></button>
        <select name="woocs_hide_cents[]" class="woocs-drop-down" <?php if (in_array($currency['name'], $WOOCS->no_cents)): ?>disabled=""<?php endif; ?>>
            <?php
            $woocs_hide_cents = array(
                0 => __("Show cents on front", 'woocommerce-currency-switcher'),
                1 => __("Hide cents on front", 'woocommerce-currency-switcher')
            );
            if (in_array($currency['name'], $WOOCS->no_cents))
            {
                $currency['hide_cents'] = 1;
            }
            $hide_cents = 0;
            if (isset($currency['hide_cents']))
            {
                $hide_cents = $currency['hide_cents'];
            }
            ?>
            <?php foreach ($woocs_hide_cents as $v => $n): ?>
                <option <?php if ($hide_cents == $v): ?>selected=""<?php endif; ?> value="<?php echo $v ?>"><?php echo $n ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" value="<?php echo $currency['description'] ?>" name="woocs_description[]" style="width: 250px;" class="woocs-text" placeholder="<?php _e("description", 'woocommerce-currency-switcher') ?>" />
        <?php
        $flag = WOOCS_LINK . 'img/no_flag.png';
        if (isset($currency['flag']) AND ! empty($currency['flag']))
        {
            $flag = $currency['flag'];
        }
        ?>
        <input type="hidden" value="<?php echo $flag ?>" class="woocs_flag_input" name="woocs_flag[]" />
        <a href="#" class="woocs_flag help_tip" data-tip="<?php _e("Click to select the flag", 'woocommerce-currency-switcher'); ?>"><img src="<?php echo $flag ?>" style="vertical-align: middle; width: 37px;" alt="<?php _e("Flag", 'woocommerce-currency-switcher'); ?>" /></a>
        &nbsp;<a href="#" class="help_tip" data-tip="<?php _e("drag and drope", 'woocommerce-currency-switcher'); ?>"><img style="width: 22px; vertical-align: middle;" src="<?php echo WOOCS_LINK ?>img/move.png" alt="<?php _e("move", 'woocommerce-currency-switcher'); ?>" /></a>
    </li>
    <?php
}
