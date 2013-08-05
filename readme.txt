=== Plugin Name ===
Contributors: dave111223
Donate link: http://www.advancedstyle.com/
Tags: accounting, ledger, sales, expenses, financial
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 0.7.2
License: AGPLv3.0 or later
License URI: http://opensource.org/licenses/AGPL-3.0

Wordpress Accounting provides a simple way for businesses to record income and expenses, and generate financial reports

== Description ==

Wordpress Accounting (or "WPA" for short) allows users to input income and expenses.  These entries are recorded in a ledger format, and are arranged into monthly income statements.

This plugin was initally designed for a small restaurant to input their daily receipts and outgoing expenses, but can be used for any kind of business to keep track of your income and expenses.

== Installation ==

1. Unzip/Extract the plugin zip to your computer
2. Upload `\wpaccounting\` folder to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Visit the new "Accounting" menu in your admin area.

== Frequently Asked Questions ==

= Does Wordpress Accounting integrate with any shopping cart plugins? =

No, Wordpress Accounting is a stand-alone system that requires uses to manually input their sales and expenses.  It does not automatically link into any shopping carts, or get any data by itself.

= How to do I make WP Accounting show up in the front end of my site, such as within certain pages =

You can't; WP Accounting is an admin area only tool.  You can only access WP Accounting system via the Wordpress admin.

== Screenshots ==

1. screenshot_accountinginput.jpg

2. screenshot_statement.jpg

3. screenshot_ledger.jpg

== Changelog ==
= 0.7.2 =
* Added API (see "Other Notes" section for API functions)
* Added multi-currency support
* Changed "Sales" to "Income" to allow for broader usage
* Added Accounts (bank accounts, loan accounts etc..)
* Added Balance Sheet
* Added options to allow different user access levels
* Added pluggable reports system (see "Other Notes" section)

= 0.6.2 =
* Fixed minor PHP strict notices

= 0.6.1 =
* Fixed number_format bug

= 0.6 =
* Updates:
- Added "Tax included in Sales" option and tax calculation
- Added Filters to the ledger
- Added option to delete entries from the ledger
- Added sales categories to the sales items in ledger

= 0.5.1 =
* Minor wordpress compliance updates

= 0.5 =
* Initial Wordpress Accounting Release

== Upgrade Notice ==

= 0.5 =
* Initial Wordpress Accounting Release

== WP Accounting API ==

The API allows you to directly interact with the account ledger on a code level without having to use the user interface.

==Example Useage:==

'if(class_exists('wpaccounting')){
    $wpaccounting = new wpaccounting;
    $data = array("amount" => 15.29,
    			  "currency" => "USD");
    $wpaccounting->insert($data);
}'

== WP Accounting Pluggable Report ==

WP Accounting has the ability to create report plugins, so that developers can create custom reports that can easily be installed ontop of WP accounting without modifying core code.

Plugins should be created in the same manner as a standard Wordpress plugins.

==Example Useage:==

-Create a folder /wp-content/plugins/wp-accounting-report-example/

-Create a file /wp-content/plugins/wp-accounting-report-example/wp-accounting-report-example.php

-Paste the below code into your PHP file and modify it as needed to create your own custom reports.