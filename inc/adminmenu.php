<?php
function wpaccounting_menu(){
	add_menu_page('Accounting Entry', 'Accounting', 'administrator', ACCOUNTING_SLUG, 'wpaEntry'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Income Statement', 'Income Statement', 'administrator', ACCOUNTING_STATEMENT,'wpaStatement'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Ledger', 'Ledger', 'administrator', ACCOUNTING_LEDGER,'wpaLedger'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Vendors', 'Vendors', 'administrator', '/edit.php?post_type=wpa_vendors'); 
	//add_submenu_page( ACCOUNTING_SLUG, 'Add Vendor', 'Add Vendor', 'administrator', '/post-new.php?post_type=wpa_vendors'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Expense Types', 'Expense Types', 'administrator', '/edit.php?post_type=wpa_expense_type'); 
	//add_submenu_page( ACCOUNTING_SLUG, 'Add Expense Type', 'Add Expense Type', 'administrator', '/post-new.php?post_type=wpa_expense_type'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Sales Lines', 'Sales Lines', 'administrator', '/edit.php?post_type=wpa_sales_meta'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Extra Sales Data', 'Extra Sales Data', 'administrator', '/edit.php?post_type=wpa_transaction_meta'); 
	add_submenu_page( ACCOUNTING_SLUG, 'Accounting Settings', 'Accounting Settings', 'administrator', ACCOUNTING_SETTINGS,'wpaSettings'); 
}

?>