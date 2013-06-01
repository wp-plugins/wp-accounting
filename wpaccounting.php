<?php
/*
Plugin Name: WP Accounting
Plugin URI: http://www.advancedstyle.com
Description: Simple accounting system for recording income and expenses
Version: 0.6.2
Author: David Barnes
Author URI: http://www.advancedstyle.com
License: AGPLv3.0 or later
*/
$basedir = dirname(__FILE__);
define('WPACCOUNTING_PLUGIN',__FILE__);
define('WPACCOUNTING_BASE',$basedir.'/');

include_once(WPACCOUNTING_BASE.'inc/install.php');
register_activation_hook(__FILE__,array('wpAccountingInstall','install'));

add_action('admin_menu', 'wpaccounting_menu');

define('ACCOUNTING_SLUG','wpa_entry');
define('ACCOUNTING_LEDGER','wpa_ledger');
define('ACCOUNTING_SETTINGS','wpa_settings');
define('ACCOUNTING_STATEMENT','wpa_statement');

$currency = get_option('wpaccounting_currency');
define('WPA_CUR',$currency);

include_once(WPACCOUNTING_BASE.'inc/functions/general.php');

include_once(WPACCOUNTING_BASE.'inc/posttypes.php');

include_once(WPACCOUNTING_BASE.'inc/tablecolumns.php');

include_once(WPACCOUNTING_BASE.'inc/adminmenu.php');

include_once(WPACCOUNTING_BASE.'inc/scripts.php');

include_once(WPACCOUNTING_BASE.'inc/pages/entry.php');

include_once(WPACCOUNTING_BASE.'inc/pages/ledger.php');

include_once(WPACCOUNTING_BASE.'inc/pages/settings.php');

include_once(WPACCOUNTING_BASE.'inc/pages/statement.php');
?>