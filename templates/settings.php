<div class="wrap" id="stock_quote_settings">
	<h2><?php _e( 'Stock Quote Settings', 'wpausq' ); ?></h2>
	<form method="post" action="options.php">
		<?php @settings_fields( 'wpausq_default_settings' ); ?>
		<?php @settings_fields( 'wpausq_advanced_settings' ); ?>

		<?php @do_settings_sections( 'wpau_stock_quote' ); ?>

		<?php @submit_button(); ?>
	</form>
	<h2><?php _e( 'Help', 'wpausq' ); ?></h2>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" style="float:right">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="98DNTKSUMAM5Q">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
	<p><?php printf( __( 'You also can use shortcode <code>%s</code> where:', 'wpausq' ), '[stock_quote symbol="" show="" zero="" minus="" plus="" nolink="" class=""]' ); ?>
		<ul>
			<li><code>symbol</code> <?php _e( 'represent single stock symbol (default from this settings page used if no custom set by shortcode)', 'wpausq' ); ?></li>
			<li><code>show</code> <?php printf( __( 'can be <code>%s</code> to represent company with Company Name (default), or <code>%s</code> to represent company with Stock Symbol', 'wpausq' ), 'name', 'symbol' ); ?></li>
			<li><code>zero</code> <?php _e( 'is HEX or RGBA colour for unchanged quote', 'wpausq' ); ?></li>
			<li><code>minus</code> <?php _e( 'is HEX or RGBA colour for negative change of quote', 'wpausq' ); ?></li>
			<li><code>plus</code> <?php _e( 'is HEX or RGBA colour for positive change of quote', 'wpausq' ); ?></li>
			<li><code>nolink</code> <?php _e( 'to disable link of quotes to Google Finance page set to <code>1</code> or <code>true</code>', 'wpausq' ); ?></li>
			<li><code>class</code> <?php _e( 'custom class name for quote item', 'wpausq' ); ?></li>
		</ul>
	</p>

	<p>If you experience error message after update (WordPress or plugin), try to increase <strong>Fetch Timeout</strong> parameter in settings (from default 2 to 3 seconds), and then append to page URL parameter <em>?stockquote_purge_cache=1</em> to re-fetch quote feed.</p>
	<h2><?php _e( 'Disclaimer', 'wpausq' ); ?></h2>
	<p class="description">Data for Stock Quote has provided by Google Finance and per their disclaimer,
it can only be used at a noncommercial level. Please also note that Google has stated
Finance API as deprecated and has no exact shutdown date.<br />
<br />
<a href="http://www.google.com/intl/en-US/googlefinance/disclaimer/#disclaimers">Google Finance Disclaimer</a><br />
<br />
Data is provided by financial exchanges and may be delayed as specified
by financial exchanges or our data providers. Google does not verify any
data and disclaims any obligation to do so.
<br />
Google, its data or content providers, the financial exchanges and
each of their affiliates and business partners (A) expressly disclaim
the accuracy, adequacy, or completeness of any data and (B) shall not be
liable for any errors, omissions or other defects in, delays or
interruptions in such data, or for any actions taken in reliance thereon.
Neither Google nor any of our information providers will be liable for
any damages relating to your use of the information provided herein.
As used here, “business partners” does not refer to an agency, partnership,
or joint venture relationship between Google and any such parties.
<br />
You agree not to copy, modify, reformat, download, store, reproduce,
reprocess, transmit or redistribute any data or information found herein
or use any such data or information in a commercial enterprise without
obtaining prior written consent. All data and information is provided “as is”
for personal informational purposes only, and is not intended for trading
purposes or advice. Please consult your broker or financial representative
to verify pricing before executing any trade.
<br />
Either Google or its third party data or content providers have exclusive
proprietary rights in the data and information provided.
<br />
Please find all listed exchanges and indices covered by Google along with
their respective time delays from the table on the left.
<br />
Advertisements presented on Google Finance are solely the responsibility
of the party from whom the ad originates. Neither Google nor any of its
data licensors endorses or is responsible for the content of any advertisement
or any goods or services offered therein.</p>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
	$('.wpau-color-field').wpColorPicker();
});
</script>
