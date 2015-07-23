<?php
if(!class_exists('WPAU_STOCK_QUOTE_SETTINGS'))
{
    class WPAU_STOCK_QUOTE_SETTINGS
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions
            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
        } // END public function __construct

        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            // get default values
            $defaults = WPAU_STOCK_QUOTE::defaults();

            // register plugin's settings
            register_setting('wpausq_default_settings', "stock_quote_defaults");
            register_setting('wpausq_advanced_settings', "stock_quote_defaults");

            // add general settings section
            add_settings_section(
                'wpausq_default_settings',
                __('Default Settings','wpausq'),
                array(&$this, 'settings_default_section_description'),
                'wpau_stock_quote'
            );

            // add setting's fields
            add_settings_field(
                'wpau_stock_quote-symbol',
                __('Stock Symbols','wpausq'),
                array(&$this, 'settings_field_input_text'),
                'wpau_stock_quote',
                'wpausq_default_settings',
                array(
                    'field'       => "stock_quote_defaults[symbol]",
                    'description' => __('Enter default stock symbol','wpausq'),
                    'class'       => 'regular-text',
                    'value'       => $defaults['symbol'],
                )
            );
            add_settings_field(
                'wpau_stock_quote-show',
                __('Show Company as','wpausq'),
                array(&$this, 'settings_field_select'),
                'wpau_stock_quote',
                'wpausq_default_settings',
                array(
                    'field'       => "stock_quote_defaults[show]",
                    'description' => __('What to show as Company identifier by default','wpausq'),
                    'items'       => array(
                        "name"   => __("Company Name",'wpausq'),
                        "symbol" => __("Stock Symbol",'wpausq')
                    ),
                    'value' => $defaults['show'],
                )
            );
            // Color pickers
            add_settings_field( // unchanged
                'wpau_stock_quote-quote_zero',
                __('Unchanged Quote','wpausq'),
                array(&$this, 'settings_field_colour_picker'),
                'wpau_stock_quote',
                'wpausq_default_settings',
                array(
                    'field'       => "stock_quote_defaults[zero]",
                    'description' => __('Set colour for unchanged quote','wpausq'),
                    'value'       => $defaults['zero'],
                )
            );
            add_settings_field( // minus
                'wpau_stock_quote-quote_minus',
                __('Netagive Change','wpausq'),
                array(&$this, 'settings_field_colour_picker'),
                'wpau_stock_quote',
                'wpausq_default_settings',
                array(
                    'field'       => "stock_quote_defaults[minus]",
                    'description' => __('Set colour for negative change','wpausq'),
                    'value'       => $defaults['minus'],
                )
            );
            add_settings_field( // plus
                'wpau_stock_quote-quote_plus',
                __('Positive Change','wpausq'),
                array(&$this, 'settings_field_colour_picker'),
                'wpau_stock_quote',
                'wpausq_default_settings',
                array(
                    'field'       => "stock_quote_defaults[plus]",
                    'description' => __('Set colour for positive change','wpausq'),
                    'value'       => $defaults['plus'],
                )
            );

            // add advanced settings section
            add_settings_section(
                'wpausq_advanced_settings',
                __('Advanced Settings','wpausq'),
                array(&$this, 'settings_advanced_section_description'),
                'wpau_stock_quote'
            );
            // add setting's fields
            // custom name
            add_settings_field(
                'wpau_stock_quote-legend',
                __('Custom Names','wpausq'),
                array(&$this, 'settings_field_textarea'),
                'wpau_stock_quote',
                'wpausq_advanced_settings',
                array(
                    'field'       => "stock_quote_defaults[legend]",
                    'class'       => 'widefat',
                    'value'       => $defaults['legend'],
                    'description' => __('Define custom names for symbols. Single symbol per row in format EXCHANGE:SYMBOL;CUSTOM_NAME','wpausq')
                )
            );
            // caching timeout field
            add_settings_field(
                'wpau_stock_quote-cache_timeout',
                __('Cache Timeout','wpausq'),
                array(&$this, 'settings_field_input_number'),
                'wpau_stock_quote',
                'wpausq_advanced_settings',
                array(
                    'field'       => "stock_quote_defaults[cache_timeout]",
                    'description' => __('Define cache timeout for single quote set, in seconds','wpausq'),
                    'class'       => 'num',
                    'value'       => $defaults['cache_timeout'],
                    'min'         => 0,
                    'max'         => DAY_IN_SECONDS,
                    'step'        => 1
                )
            );
            // fetching timeout field
            add_settings_field(
                'wpau_stock_quote-timeout',
                __('Fetch Timeout','wpausq'),
                array(&$this, 'settings_field_input_number'),
                'wpau_stock_quote',
                'wpausq_advanced_settings',
                array(
                    'field'       => "stock_quote_defaults[timeout]",
                    'description' => __('Define timeout to fetch quote feed before give up and display error message, in seconds (default is 2)','wpausq'),
                    'class'       => 'num',
                    'value'       => $defaults['timeout'],
                    'min'         => 1,
                    'max'         => 60,
                    'step'        => 1
                )
            );
            // default error message
            add_settings_field(
                'wpau_stock_quote-error_message',
                __('Error Message','wpausq'),
                array(&$this, 'settings_field_input_text'),
                'wpau_stock_quote',
                'wpausq_advanced_settings',
                array(
                    'field'       => "stock_quote_defaults[error_message]",
                    'description' => __('When Stock Quote fail to grab quote set from Google Finance, display this mesage instead. Use macro <em>%symbol%</em> to insert requested symbol.','wpausq'),
                    'class'       => 'widefat',
                    'value'       => $defaults['error_message'],
                )
            );

            // default styling
            add_settings_field(
                'wpau_stock_quote-style',
                __('Custom Style','wpausq'),
                array(&$this, 'settings_field_textarea'),
                'wpau_stock_quote',
                'wpausq_advanced_settings',
                array(
                    'field'       => "stock_quote_defaults[style]",
                    'class'       => 'widefat',
                    'rows'        => 1,
                    'value'       => $defaults['style'],
                    'description' => __('Define custom CSS style for quote item (font family, size, weight)','wpausq')
                )
            );
            // Possibly do additional admin_init tasks
        } // END public static function admin_init()

        public function settings_default_section_description()
        {
            // Think of this as help text for the section.
            echo __('Predefine default settings for Stock Quote. Here you can set stock symbols and how you wish to present companies in page.','wpausq');
        }
        public function settings_advanced_section_description()
        {
            // Think of this as help text for the section.
            echo __('Set advanced options important for caching quote feeds.','wpausq');
        }

        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            extract( $args );
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" class="%s" /><p class="description">%s</p>', $field, $field, $value, $class, $description);
        } // END public function settings_field_input_text($args)

        /**
         * This function provides number inputs for settings fields
         */
        public function settings_field_input_number($args)
        {
            extract( $args );
            printf(
                '<input type="number" name="%1$s" id="%2$s" value="%3$s" class="%4$s" min="%5$s" max="%6$s" step="%7$s" /><p class="description">%8$s</p>',
                $field, // name
                $field, // id
                $value, // value
                $class, // class
                $min, // min
                $max, // max
                $step, // step
                $description // description
            );
        } // END public function settings_field_input_number($args)

        /**
         * This function provides checkbox for settings fields
         */
        public function settings_field_checkbox($args)
        {
            extract( $args );
            $checked = ( !empty($args['value']) ) ? 'checked="checked"' : '';
            echo sprintf('<label for="%s"><input type="checkbox" name="%s" id="%s" value="1" class="%s" %s />%s</label>', $field, $field, $field, $class, $checked, $description);
        } // END public function settings_field_checkbox($args)

        /**
         * This function provides textarea for settings fields
         */
        public function settings_field_textarea($args)
        {
            extract( $args );
            if (empty($rows)) $rows = 7;
            echo sprintf('<textarea name="%s" id="%s" rows="%s" class="%s">%s</textarea><p class="description">%s</p>', $field, $field, $rows, $class, $value, $description);
        } // END public function settings_field_textarea($args)

        /**
         * This function provides select for settings fields
         */
        public function settings_field_select($args)
        {
            extract( $args );
            $html = sprintf('<select id="%s" name="%s">',$field,$field);
            foreach ($items as $key=>$val)
            {
                $selected = ($value==$key) ? 'selected="selected"' : '';
                $html .= sprintf('<option %s value="%s">%s</option>',$selected,$key,$val);
            }
            $html .= sprintf('</select><p class="description">%s</p>',$description);
            echo $html;
        } // END public function settings_field_select($args)

        public function settings_field_colour_picker($args)
        {
            extract( $args );
            $html = sprintf('<input type="text" name="%s" id="%s" value="%s" class="wpau-color-field" />',$field, $field, $value);
            $html .= (!empty($description)) ? ' <p class="description">'.$description.'</p>' : '';
            echo $html;
        } // END public function settings_field_colour_picker($args)

        /**
         * add a menu
         */
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
            add_options_page(
                __('Stock Quote Settings','wpausq'),
                __('Stock Quote','wpausq'),
                'manage_options',
                'wpau_stock_quote',
                array(&$this, 'plugin_settings_page')
            );
        } // END public function add_menu()

        /**
         * Menu Callback
         */
        public function plugin_settings_page()
        {
            if(!current_user_can('manage_options'))
            {
                wp_die(__('You do not have sufficient permissions to access this page.'));
            }

            // Render the settings template
            include(sprintf("%s/../templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WPAU_STOCK_QUOTE_SETTINGS
} // END if(!class_exists('WPAU_STOCK_QUOTE_SETTINGS'))
