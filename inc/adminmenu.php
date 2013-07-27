<?php
function wpaccounting_menu(){
	wpaccounting_roles();
	
	add_menu_page('Accounting Entry', 'Accounting', 'wpaccounting', ACCOUNTING_SLUG, 'wpaEntry'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Accounts', 'Accounts', 'wpaccounting', '/edit.php?post_type=wpa_accounts'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Ledger', 'Ledger', 'wpaccounting', ACCOUNTING_LEDGER,'wpaLedger'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Income Statement', 'Income Statement', 'wpaccounting', ACCOUNTING_STATEMENT,'wpaStatement'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Balance Sheet', 'Balance Sheet', 'wpaccounting', ACCOUNTING_BALANCE_SHEET,'wpaBalanceSheet'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Reports', 'Reports', 'wpaccounting', ACCOUNTING_CUSTOM_REPORTS,'wpaReports'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Vendors', 'Vendors', 'wpaccounting', '/edit.php?post_type=wpa_vendors'); 
	//add_submenu_page( ACCOUNTING_SLUG, 'Add Vendor', 'Add Vendor', 'wpaccounting', '/post-new.php?post_type=wpa_vendors'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Expense Types', 'Expense Types', 'wpaccounting', '/edit.php?post_type=wpa_expense_type'); 
	//add_submenu_page( ACCOUNTING_SLUG, 'Add Expense Type', 'Add Expense Type', 'wpaccounting', '/post-new.php?post_type=wpa_expense_type'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Sales Lines', 'Sales Lines', 'wpaccounting', '/edit.php?post_type=wpa_sales_meta'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Extra Sales Data', 'Extra Sales Data', 'wpaccounting', '/edit.php?post_type=wpa_transaction_meta'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Accounting Settings', 'Accounting Settings', 'wpaccounting', ACCOUNTING_SETTINGS,'wpaSettings'); 
	if(get_option('wpaccounting_multicurrency') == 1){
		add_submenu_page( ACCOUNTING_SLUG, 'Currencies', 'Currencies', 'wpaccounting', '/edit.php?post_type=wpa_currency'); 
	}
}

function wpaccounting_roles(){
	global $wp_roles;
	
	$wp_roles->add_cap('administrator','wpaccounting');
	$access_level = get_option('wpaccounting_access','administrator');
	if(!empty($access_level) && is_array($access_level)){
		foreach($access_level as $k => $v){
			$wp_roles->add_cap($k,'wpaccounting');
		}
	}
}

?>