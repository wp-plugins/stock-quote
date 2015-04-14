<?php
/*
Plugin Name: Stock Quote
Plugin URI: http://urosevic.net/wordpress/plugins/stock-quote/
Description: Quick and easy insert static inline stock information for specific exchange symbol by customizable shortcode.
Version: 0.1.0
Author: Aleksandar Urosevic
Author URI: http://urosevic.net
License: GNU GPL3
*/
/*
Copyright 2015 Aleksandar Urosevic (urke.kg@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
Google Finance Disclaimer <http://www.google.com/intl/en-US/googlefinance/disclaimer/#disclaimers>

Data for Stock Quote has provided by Google Finance and per their disclaimer,
it can only be used at a noncommercial level. Please also note that Google
has stated Finance API as deprecated and has no exact shutdown date.

Data is provided by financial exchanges and may be delayed as specified
by financial exchanges or our data providers. Google does not verify any
data and disclaims any obligation to do so.

Google, its data or content providers, the financial exchanges and
each of their affiliates and business partners (A) expressly disclaim
the accuracy, adequacy, or completeness of any data and (B) shall not be
liable for any errors, omissions or other defects in, delays or
interruptions in such data, or for any actions taken in reliance thereon.
Neither Google nor any of our information providers will be liable for
any damages relating to your use of the information provided herein.
As used here, “business partners” does not refer to an agency, partnership,
or joint venture relationship between Google and any such parties.

You agree not to copy, modify, reformat, download, store, reproduce,
reprocess, transmit or redistribute any data or information found herein
or use any such data or information in a commercial enterprise without
obtaining prior written consent. All data and information is provided “as is”
for personal informational purposes only, and is not intended for trading
purposes or advice. Please consult your broker or financial representative
to verify pricing before executing any trade.

Either Google or its third party data or content providers have exclusive
proprietary rights in the data and information provided.

Please find all listed exchanges and indices covered by Google along with
their respective time delays from the table on the left.

Advertisements presented on Google Finance are solely the responsibility
of the party from whom the ad originates. Neither Google nor any of its
data licensors endorses or is responsible for the content of any advertisement
or any goods or services offered therein.

 */

if(!class_exists('WPAU_STOCK_QUOTE'))
{
    class WPAU_STOCK_QUOTE
    {
        public static $wpau_stock_quote_ids = NULL;
        public static $wpau_stock_quote_css = NULL;

        /**
         * Construct the plugin object
         */
        public function __construct() {
            define('WPAU_STOCK_QUOTE_VER','0.1.0');

            // Initialize Settings
            require_once(sprintf("%s/inc/settings.php", dirname(__FILE__)));

            $WPAU_STOCK_QUOTE_SETTINGS = new WPAU_STOCK_QUOTE_SETTINGS();
        } // END public function __construct()

        /**
         * Defaults
         */
        public static function defaults() {
            $defaults = array(
                'symbol'        => 'AAPL',
                'show'          => 'name',
                'zero'          => '#454545',
                'minus'         => '#D8442F',
                'plus'          => '#009D59',
                'cache_timeout' => '180', // 3 minutes
                'error_message' => 'Unfortunately, we could not get stock quote %symbol% this time.',
                'legend'        => "AAPL;Apple Inc.\nFB;Facebook, Inc.\nCSCO;Cisco Systems, Inc.\nGOOG;Google Inc.\nINTC;Intel Corporation\nLNKD;LinkedIn Corporation\nMSFT;Microsoft Corporation\nTWTR;Twitter, Inc.\nBABA;Alibaba Group Holding Limited\nIBM;International Business Machines Corporation\n.DJI;Dow Jones Industrial Average\nEURGBP;Euro (€) ⇨ British Pound Sterling (£)",
                'style'         => ''
            );
            $options = wp_parse_args(get_option('stock_quote_defaults'), $defaults);
            return $options;
        } // END public static function defaults()

        /**
         * Activate the plugin
         */
        public static function activate() {
            // Do nothing
        } // END public static function activate()

        /**
         * Deactivate the plugin
         */
        public static function deactivate() {
            // Do nothing
        } // END public static function deactivate()

        /**
         * Ticker function for widget and shortcode
         */
        public static function stock_quote($symbol = 'AAPL', $show = 'symbol', $zero, $minus, $plus, $nolink = false, $class = '') {

            if ( !empty($symbol) )
            {

                // get fresh or from transient cache stock quote
                $sq_transient_id = "stock_quote_json_".md5($symbol);

                // get legend for company names
                $defaults = self::defaults();
                $matrix = explode("\n", $defaults['legend']);
                $msize = sizeof($matrix);
                for($m=0; $m<$msize; $m++)
                {
                    $line = explode(";", $matrix[$m]);
                    $legend[strtoupper(trim($line[0]))] = trim($line[1]);
                }
                unset($m, $msize, $matrix, $line);

                // check if cache exists
                if ( false === ( $json = get_transient( $sq_transient_id ) ) || empty($json) )
                {
                    // if does not exist, get new cache

                    // clean and prepare symbol for query
                    $exc_symbol = preg_replace('/\s+/', '', $symbol);
                    // adapt ^DIJ to .DJI
                    $exc_symbol = preg_replace('/\^/', '.', $exc_symbol);
                    // replace amp with code
                    $exc_symbol = str_replace('&', '%26', $exc_symbol);
                    // adapt currency symbol EURGBP=X to CURRENCY:EURGBP
                    $exc_symbol = preg_replace('/([a-zA-Z]*)\=X/i',"CURRENCY:$1",$exc_symbol);
                    // compose URL
                    $exc_url = "http://finance.google.com/finance/info?client=ig&q=$exc_symbol";

                    // set timeout
                    $wprga = array(
                        'timeout' => 2 // two seconds only
                    );
                    // get stock from Google
                    $response = wp_remote_get($exc_url, $wprga);
                    // get content from response
                    $data = wp_remote_retrieve_body( $response );
                    // convert a string with ISO-8859-1 characters encoded with UTF-8 to single-byte ISO-8859-1
                    $data = utf8_decode( $data );
                    // remove newlines from content
                    $data = str_replace( "\n", "", $data );
                    // remove // from content
                    $data = trim(str_replace('/', '', $data));

                    // decode data to JSON
                    $json = json_decode($data);
                    // now cache array for N minutes
                    if ( !defined('WPAU_STOCK_QUOTE_CACHE_TIMEOUT') )
                    {
                        // $defaults = WPAU_STOCK_QUOTE::defaults();
                        define('WPAU_STOCK_QUOTE_CACHE_TIMEOUT',$defaults['cache_timeout']);
                        // unset($defaults);
                    }
                    set_transient( $sq_transient_id, $json, WPAU_STOCK_QUOTE_CACHE_TIMEOUT );

                    // free some memory: destroy all vars that we temporary used here
                    unset($exc_symbol, $exc_url, $reponse);
                }

                // prepare quote
                $id = 'stock_quote_'. substr(md5(mt_rand()),0,8);
                $class = "stock_quote sqitem $class";

                // process quote
                if( ! empty($json) && ! is_null($json[0]->id) )
                {

                    // Parse results and extract data to display
                    $quote = $json[0];

                    // assign object elements to vars
                    $q_change  = $quote->c;
                    $q_price   = $quote->l;
                    $q_name    = $quote->t;
                    $q_changep = $quote->cp;
                    $q_symbol  = $quote->t;
                    $q_ltrade  = $quote->lt;
                    $q_exch    = $quote->e;

                    // Define class based on change
                    if ( $q_change < 0 ) { $class .= " minus"; }
                    else if ( $q_change > 0 ) { $class .= " plus"; }
                    else { $class .= " zero"; $q_change = "0.00"; }

                    // Get custom company name if exists
                    if ( ! empty($legend[ $q_exch.':'.$q_symbol ]) ) {
                        // first in format EXCHANGE:SYMBOL
                        $q_name = $legend[ $q_exch.':'.$q_symbol ];
                    } else if ( ! empty($legend[$q_symbol]) ) {
                        // then in format SYMBOL
                        $q_name = $legend[ $q_symbol ];
                    }

                    // What to show: Symbol or Company Name?
                    if ( $show == "name" ) {
                        $company_show = $q_name;
                    } else {
                        $company_show = $q_symbol;
                    }

                    // Do not print change, volume and change% for currencies
                    if ($q_exch == "CURRENCY") {
                        $company_show = ( $q_symbol == $q_name ) ? $q_name . '=X' : $q_name;
                        $url_query = $q_symbol;
                        $quote_title = $q_name;
                    } else {
                        $url_query = $q_exch.':'.$q_symbol;
                        $quote_title = $q_name.' ('.$q_exch.' Last trade '.$q_ltrade.')';
                    }

                    // text
                    $quote_text = "$company_show $q_price $q_change ${q_changep}%";

                    // quote w/ or w/o link
                    if ( empty($nolink) ) {
                        $out = sprintf(
                            '<a href="https://www.google.com/finance?q=%s" id="%s" class="%s" target="_blank" title="%s">%s</a>',
                            $url_query,
                            $id,
                            $class,
                            $quote_title,
                            $quote_text
                        );
                    } else {
                        $out = sprintf(
                            '<span id="%s" class="%s" title="%s">%s</span>',
                            $id,
                            $class,
                            $quote_title,
                            $quote_text
                        );
                    }

                    // prepare styles
                    $css = "#{$id}.stock_quote.sqitem.zero,#{$id}.stock_quote.zero.sqitem:hover { color: $zero; }";
                    $css .= "#{$id}.stock_quote.sqitem.minus,#{$id}.stock_quote.minus.sqitem:hover { color: $minus; }";
                    $css .= "#{$id}.stock_quote.sqitem.plus,#{$id}.stock_quote.plus.sqitem:hover { color: $plus; }";

                } else {
                    // No results were returned
                    $out = sprintf(
                        '<span id="%s" class="stock_quote sqitem error %s">%s</span>',
                        $id,
                        $class,
                        str_replace( '%symbol%', $symbol, $defaults['error_message'] )
                    );
                    $css = '';
                }

                // append customized styles
                if ( is_null(self::$wpau_stock_quote_css) )
                    self::$wpau_stock_quote_css = ( empty($defaults['style']) ) ? $css : ".stock_quote.sqitem{".$defaults['style']."}$css";
                else
                    self::$wpau_stock_quote_css .= $css;

                unset($q, $id, $css, $defaults, $legend);

                // print quote content
                return $out;

            }
        } // END public static function stock_quote()

        /**
         * Shortcode for stock quote
         */
        public static function stock_quote_shortcode($atts, $content=null) {

            $st_defaults = WPAU_STOCK_QUOTE::defaults();
            extract( shortcode_atts( array(
                'symbol' => $st_defaults['symbol'],
                'show'    => $st_defaults['show'],
                'zero'    => $st_defaults['zero'],
                'minus'   => $st_defaults['minus'],
                'plus'    => $st_defaults['plus'],
                'nolink'  => false,
                'class'   => ''
            ), $atts ) );

            if ( ! empty($symbol) ) {
                $symbol = strip_tags($symbol);
                return self::stock_quote($symbol, $show, $zero, $minus, $plus, $nolink, $class);
            }

        } // END public static function stock_quote_shortcode()

    } // END class WPAU_STOCK_QUOTE

} // END if(!class_exists('WPAU_STOCK_QUOTE'))

if(class_exists('WPAU_STOCK_QUOTE'))
{

    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('WPAU_STOCK_QUOTE', 'activate'));
    register_deactivation_hook(__FILE__, array('WPAU_STOCK_QUOTE', 'deactivate'));

    // instantiate the plugin class
    $wpau_stock_quote = new WPAU_STOCK_QUOTE();

    // Add a link to the settings page onto the plugin page
    if(isset($wpau_stock_quote))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links) {
            $settings_link = '<a href="options-general.php?page=wpau_stock_quote">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        } // eof plugin_settings_link()

        $plugin = plugin_basename(__FILE__);
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');

        /**
         * Enqueue the colour picker
         */
        function wpau_stock_quote_enqueue_colour_picker() {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        } // END function wpau_stock_quote_enqueue_colour_picker()
        add_action( 'admin_enqueue_scripts', 'wpau_stock_quote_enqueue_colour_picker' );

        // JS tool for frontend
        function wpau_stock_quote_enqueue_script() {
            wp_enqueue_style( 'stock-quote', plugin_dir_url(__FILE__) .'assets/css/stock-quote.css', array(), WPAU_STOCK_QUOTE_VER ); //'1.0.0' );
        } // END function wpau_stock_quote_enqueue_script()
        add_action( 'wp_enqueue_scripts', 'wpau_stock_quote_enqueue_script' );

        /**
         * Output prepared custom styling
         * @return string STYLE in page footer
         */
        function wpau_stock_quote_byshortcode() {

            // get class vars
            $quote_class_vars = get_class_vars('wpau_stock_quote');

            // output custom styles
            if ( !empty($quote_class_vars['wpau_stock_quote_css']) )
                echo "<style type=\"text/css\">".$quote_class_vars['wpau_stock_quote_css']."</style>";

        } // END function wpau_stock_quote_byshortcode()
        add_action( 'wp_footer', 'wpau_stock_quote_byshortcode' );

        // register stock_quote shortcode
        add_shortcode( 'stock_quote', array('WPAU_STOCK_QUOTE','stock_quote_shortcode') );

    } // END isset($wpau_stock_quote)

} // END class_exists('WPAU_STOCK_QUOTE')
