=== Stock Quote ===
Contributors: urkekg
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=Q6Q762MQ97XJ6
Tags: widget, stock, securities, quote, financial, finance, exchange, bank, market, trading, investment, stock symbols, stock quotes, forex, nasdaq, nyse, wall street
Requires at least: 3.9.0
Tested up to: 4.3
Stable tag: 0.1.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Quick and easy insert static inline stock information for specific exchange symbol by customizable shortcode.

== Description ==

A simple and easy configurable plugin that allows you to insert inline stock quotes with stock price information (data provided by Google Finance). Insertion is enabled by shortcode.

Stock Quote is simplified, static inline variation of [Stock Ticker](https://wordpress.org/plugins/stock-ticker/) plugin.

= Features =
* Configure default stock symbol that will be displayed by shortcode if no symbol provided
* Configure default presence of company as Company Name or as Stock Symbol
* Configure colours for unchanged quote, negative and positive changes
* Global settings provides easy colour picker for selecting all three colour values
* Tooltip for quote item display company name, exchange market and last trade date/time
* Define custom names for companies to be used instead symbols
* Plugin uses native WordPress function to get and cache data from Google Finance for predefined duration of time

For feature requests or help [send feedback](http://urosevic.net/wordpress/plugins/stock-quote/ "Official plugin page") or use support forum on WordPress.

= Shortcode =
Use simple shortcode `[stock_quote]` without any parameter in post or page, to display quote with default (global) settings.

You can tune single shortcode with parameters:

* `symbol` - string with single stock symbol
* `show` - string that define how will company be represent in quote; can be `name` for Company Name, or `symbol` for Stock Symbol
* `zero` - string with HEX colour value of unchanged quote
* `minus` - string with HEX colour value of negative quote change
* `plus` - string with HEX colour value of positive quote change
* `nolink` - disable links for single quotes
* `class` - custom class for quote item, if you wish some special styling

= Example =

`[stock_quote symbol="^DJI" show="symbol" zero="#000" minus="#f00" plus="#0f0"]`

== Installation ==

Easy install Stock Quote as any other ordinary WordPress plugin

1. Go to `Plugins` -> `Add New`
1. Search for `Stock Quote` plugin
1. Install and activate `Stock Quote`
1. Configure default plugin options and insert shortcode `[stock_quote]` to page or post

== Screenshots ==

1. Global plugin settings page
2. Stock Quote in action

== Frequently Asked Questions ==

= How to know which stock symbols to use? =

Visit [Google Finance Stock Screener](https://www.google.com/finance#stockscreener) and look for preferred symbols that you need/wish to display on your site.
For start you can try with AAPL (Apple)

= How to get Dow Jones Industrial Average? =

To get quote for this exchange, simply add symbol `.DJI` or `^DJI`.

= How to get currency exchange rate? =

Use Currency symbols like `EURGBP=X` to get rate of `1 Euro` = `? British Pounds`

= How to get descriptive title for currency exchange rates =

Add to `Custom Names` legend currency exchange symbol w/o `=X` part, like:

`EURGBP;Euro (€) ⇨ British Pound Sterling (£)`

= How to get proper stock price from proper stock exchange? =

Enter symbol in format `EXCHANGE:SYMBOL` like `LON:FFX`

= How to add Stock Ticker to header theme file? =

Add this to your template file (you also can add custom parameters for shortcode):

`<?php echo do_shortcode('[stock_ticker]'); ?>`

= I set to show company name but symbol is displayed instead =

Please note that Google Finance does not provide company name in retrieved feeds. You'll need to set company name to Custom Names field on plugin settings page.

== Disclaimer ==

Data for Stock Quote has provided by Google Finance and per their disclaimer, it can only be used at a noncommercial level. Please also note that Google has stated Finance API as deprecated and has no exact shutdown date.

[Google Finance Disclaimer](http://www.google.com/intl/en-US/googlefinance/disclaimer/#disclaimers)

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

== Upgrade Notice ==
= 0.1.1 =
Bugfix release

= 0.1.0 =
This is initial version of plugin.

== Changelog ==

= 0.1.3 (20150809) =
* Change: Item ID length reduced fro 8 to 4 characters
* Change: Move all core methods inside class
* Make code fully compliant to WordPress Coding Standard
* Update FAQ

= 0.1.2 (20150723) =
* Add: Option to purge cache by providing parameter `stockquote_purge_cache` in page URL
* Add: Option on plugin settings page to set fetch timeout in seconds (2 is default). Usefull for websites hosted on shared hosting.
* Change: Timeout fields to HTML5 number

= 0.1.1 (20150607) =
* Fix: Make available to work with our Stock Ticker plugin

= 0.1.0 (20150408) =
* Initial release
