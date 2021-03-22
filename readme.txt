=== Amount Left for Free Shipping for WooCommerce ===
Contributors: wpcodefactory
Tags: woocommerce, left for free shipping, free shipping, shipping
Requires at least: 4.4
Tested up to: 5.7
Stable tag: 2.0.1
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Show your customers the amount left for free shipping in WooCommerce.

== Description ==

**Amount Left for Free Shipping for WooCommerce** is a lightweight plugin that lets you display the amount left for free shipping in WooCommerce.

The amount can be displayed on **cart** page or, alternatively, you can use **widget**, **shortcode** or PHP **function** to display it anywhere on your site.

= Premium Version =

[Pro version](https://wpfactory.com/item/amount-left-free-shipping-woocommerce/) allows you to use additional **position(s)** for the text:

* site-wide **store notice**,
* "add to cart" **notice**,
* **mini cart**, and/or
* **checkout** page.

Additionally, the Pro version has options to update the amount via **AJAX**.

= Feedback =

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/amount-left-free-shipping-woocommerce/).

== Frequently Asked Questions ==
= Why the amount left message doesn't seem to get displayed in the proper position? =
Some positions are located inside tables, so it's necessary to wrap the content in HTML table row tags.
Most probably, these positions will have `(in table)` appended.
In such cases, the best solution to wrap the content is by using the **Wrapper options** section.
When it happens, please try to set the **Wrap method** option as **Smart**.
Besides that, most likely the **Wrap template** option should be set as:
`
<tr><th></th><td>{content}</td></tr>
`
You can optionally add a column title for the message if you wish like this:
`
<tr><th>Free shipping</th><td>{content}</td></tr>
`

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WooCommerce > Settings > Amount Left for Free Shipping".

== Changelog ==

= 2.0.1 - 22/03/2021 =
* Dev - Create `wpml-config.xml` file with some admin text options from the plugin for a better compatibility with Polylang and WPML.
* Tested up to: 5.7.

= 2.0.0 - 15/02/2021 =
* Fix - General - Ajax prevents content wrapping.
* Dev - Cart and Checkout - Add "Wrap method" option.
* Dev - Cart and Checkout - Add "Wrap template" option.
* Dev - General - Add "Clear notices" option.
* Dev - General - Create "Cart total method" option.
* Dev - Manual min amount - Compatibility - Add compatibility option with "WooCommerce Currency Switcher" plugin made by WooCommerce author realmag777.
* Dev - Add `alg_wc_left_to_free_shipping_manual_min_amount` filter.
* Dev - Add `alg_wc_left_to_free_shipping_manual_min_amount_extra` filter.
* WC tested up to: 5.0

= 1.9.6 - 13/01/2021 =
* Dev - Store notice - Create "Default WooCommerce notice" option.
* Dev - Store notice - Default WooCommerce notice - Create "Notice type" option.
* Dev - Store notice - Default WooCommerce notice - Create "Display by function" option.
* Dev - Advanced - Add "Check cart free shipping" option.
* Dev - General - Add "Hide by category" option
* Dev - General - Hide by category - Add "check children" option
* Dev - Add `alg_wc_get_left_to_free_shipping_validation` filter.
* Tested up to: 5.6
* WC tested up to: 4.9

= 1.9.5 - 08/12/2020 =
* Fix - Replace `get_left_to_free_shipping` call using an array as parameter.

= 1.9.4 - 07/12/2020 =
* Dev - General - Add "Minimum cart amount" option allowing to only display the amount left for free shipping if there is a minimum cart amount.
* Dev - General - Add `min_cart_amount` param to `alg_wc_left_to_free_shipping` shortcode.
* WC tested up to: 4.7

= 1.9.3 - 16/11/2020 =
* Fix - Widget - Allow the Widget to save some html including `<progress>` tag.
* Add FAQ regarding HTML table row tags for table positions.
* Improve position descriptions at Cart and Checkout sections using (in table) at the end.
* Improve content description at Cart and Checkout sections regarding HTML table row tags for table positions.

= 1.9.2 - 05/11/2020 =
* Fix - PHP Notice:  Undefined index: min_free_shipping_amount in class-alg-wc-alfs-pro.php.
* Dev - Manual Min Amount - Settings section title updated.
* Dev - General - Ajax Options - Add "Added to cart event without AJAX" option fixing the Store notice not getting displayed on single product pages.

= 1.9.1 - 04/11/2020 =
* Dev - AJAX - Events - Renamed to "Additional events" and it now defaults to an empty string. I.e. default events are now always included.
* Dev - `[alg_wc_left_to_free_shipping]` - Third param in `shortcode_atts()` function now matches the shortcode name (was `alg_get_left_to_free_shipping`).
* Plugin author updated.
* WC tested up to: 4.6.

= 1.9.0 - 10/10/2020 =
* Dev - Manual Min Amount - "Currencies" options added.
* Dev - Manual Min Amount - "Shipping zones" options added.
* Dev - Manual Min Amount - "Enable section" option added (defaults to `yes`).
* Dev - "Manual Min Amount" settings section added (i.e. options moved from "General > Advanced: Manual Min Amount").

= 1.8.0 - 28/09/2020 =
* Dev - Code refactoring.
* WC tested up to: 4.5.

= 1.7.1 - 28/08/2020 =
* Fix - Functions - `alg_wc_alfs_get_min_free_shipping_amount()` - Checking if shipping method `is_available()` - "Free delivery" fixed and outputted.
* Fix - AJAX - Pass min amount - Option removed (i.e. defaults to `no`) (this caused issues with free shipping coupons).
* Fix - Store notice - `<p>` tag replaced with `<span>` (this allows to use `<p>` tags inside the content).

= 1.7.0 - 25/08/2020 =
* Fix - Store notice + empty "free delivery" issue fixed.
* Dev - Functions - `alg_wc_alfs_get_min_free_shipping_amount()` - Checking if shipping method `is_available()` now (if it is - will hide the left to free shipping content).
* Dev - AJAX - Events - `wc_cart_emptied` event added to the default value.
* Dev - General - "Message on empty cart" options added.
* Dev - General - Message on free shipping reached - Now replacing placeholders.
* Dev - Advanced - Check for virtual cart - Returning `false` on empty cart now.
* Dev - Store notice - "Position" option added. Available values: "Bottom" (default) and "Top".
* Dev - Store notice - `z-index` increased to `99999` (was `9999`).
* Dev - JS files minified.
* Dev - Admin settings restyled.
* Dev - Code refactoring.
* Tested up to: 5.5.
* WC tested up to: 4.4.

= 1.6.0 - 06/08/2020 =
* Fix - Functions - `alg_get_left_to_free_shipping()` - AJAX + "free delivery" issue fixed.
* Dev - "Store notice" section added.
* Dev - AJAX - Events - `updated_checkout` event added to the default value.
* Dev - AJAX - "Pass min amount" option added.
* Dev - Mini Cart - Position - 5 new positions added.
* Dev - Advanced - "Check for free shipping" option added.
* Dev - Advanced - "Check for virtual cart" option added.
* Dev - Functions - `alg_get_left_to_free_shipping()` - Always processing shortcodes in result now (not only on "free delivery reached").
* Dev - Admin settings split into sections: "Cart", "Min-cart", "Checkout", "Add to cart" sections added.
* Dev - Code refactoring.

= 1.5.2 - 26/06/2020 =
* Dev - AJAX - Time interval based method replaced with event based; "Interval" option removed; "Events" option added (defaults to `updated_cart_totals added_to_cart removed_from_cart wc_fragment_refresh`).

= 1.5.1 - 25/06/2020 =
* Fix - AJAX - "You have free delivery!" text fixed.

= 1.5.0 - 25/06/2020 =
* Dev - "AJAX Options" section added.

= 1.4.8 - 23/06/2020 =
* Dev - Now checking child classes of `WC_Shipping_Free_Shipping` class as well.
* Dev - Admin settings descriptions updated.
* Tested up to: 5.4.
* WC tested up to: 4.2.

= 1.4.7 - 30/03/2020 =
* Fix - "Reset settings" admin notice fixed.
* Dev - Optional `$free_delivery_text` param added to the `alg_wc_get_left_to_free_shipping()` function.
* Dev - Optional `free_delivery_text` attribute added to the `[alg_wc_left_to_free_shipping]` shortcode.
* Dev - Admin settings descriptions updated.
* WC tested up to: 4.0.

= 1.4.6 - 06/02/2020 =
* Dev - Optional `$min_free_shipping_amount` param added to the `alg_wc_get_left_to_free_shipping()` function.
* Dev - Optional `min_free_shipping_amount` attribute added to the `[alg_wc_left_to_free_shipping]` shortcode.

= 1.4.5 - 26/01/2020 =
* Dev - Advanced Options - "User roles" options added.

= 1.4.4 - 24/01/2020 =
* Dev - General Options - "Include discounts" option added.
* Dev - Code refactoring.
* WC tested up to: 3.9.

= 1.4.3 - 25/12/2019 =
* Dev - "Add to Cart Notice" options added.

= 1.4.2 - 20/12/2019 =
* Dev - Advanced Options - "Manual min amount" option added.
* Dev - Comparing floats with epsilon now.

= 1.4.1 - 04/12/2019 =
* Dev - Code refactoring.

= 1.4.0 - 13/11/2019 =
* Fix - Cart total calculation fixed.
* Dev - Code refactoring.
* WC tested up to: 3.8.
* Tested up to: 5.3.

= 1.3.1 - 19/06/2019 =
* Dev - New placeholders added for "raw" amounts: `%amount_left_for_free_shipping_raw%`, `%free_shipping_min_amount_raw%` and `%current_cart_total_raw%`.
* Tested up to: 5.2.

= 1.3.0 - 30/04/2019 =
* Dev - Checkout - "Order review: Before shipping" and "Order review: After shipping" positions added.
* Dev - `%current_cart_total%` placeholder added.
* Dev - `[alg_wc_left_to_free_shipping_translate]` shortcode added.
* Dev - Code refactoring.
* Dev - Admin settings restyled and descriptions updated.
* Dev - "Tested up to" and "WC tested up to" updated.

= 1.2.0 - 07/08/2018 =
* Dev - WooCommerce v3.2.0 compatibility - "WC_Cart->taxes is deprecated since version 3.2" notice fixed.
* Dev - Info on Checkout - New positions added.
* Dev - POT file renamed.
* Dev - Admin settings restyled.
* Dev - Plugin settings array is saved as main class property.
* Dev - Code refactored and cleaned up.
* Dev - Plugin link updated.

= 1.1.0 - 14/06/2017 =
* Dev - Autoloading plugin's options.
* Dev - `custom_textarea` instead of `textarea` in plugin settings.
* Dev - Settings descriptions updated.
* Dev - Plugin link updated from <a href="http://coder.fm">http://coder.fm</a> to <a href="https://wpcodefactory.com">https://wpcodefactory.com</a>.
* Dev - Plugin header ("Text Domain" etc.) updated.

= 1.0.0 - 16/02/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
* Initial Release.