<?php
// Update the ending balances
function wpaccountingUpdateBalances(){
	global $wpdb;
	$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => -1,'orderby' => 'post_title','post_status' => 'publish'));
	$currencies = $wpdb->get_results("SELECT DISTINCT currency FROM ". $wpdb->prefix . "wpaccounting_ledger", ARRAY_A);
	if(empty($currencies)){
		$currency = array(array('currency' => ''));
	}
	foreach($accounts as $account){
		if(!empty($currencies)){
			foreach($currencies as $currency){
				if($results = $wpdb->get_row("SELECT ledger_id, amount, end_balance FROM ". $wpdb->prefix . "wpaccounting_ledger WHERE currency='".esc_sql($currency['currency'])."' AND account_id='".(int)$account->ID."' ORDER BY ledger_id DESC LIMIT 1", ARRAY_A)){
					if(!is_numeric($results['end_balance'])){
						// Find the last entry with an ending balance
						$last_balance = $wpdb->get_row("SELECT ledger_id, end_balance FROM ". $wpdb->prefix . "wpaccounting_ledger WHERE currency='".esc_sql($currency['currency'])."' AND account_id='".(int)$account->ID."' AND end_balance IS NOT NULL ORDER BY ledger_id DESC LIMIT 1", ARRAY_A);
						
						$balance = $last_balance['end_balance'];
						if($entries = $wpdb->get_results("SELECT ledger_id, amount, type_id FROM ". $wpdb->prefix . "wpaccounting_ledger WHERE ledger_id >'".(int)$last_balance['ledger_id']."' AND currency='".esc_sql($currency['currency'])."' AND account_id='".(int)$account->ID."' AND end_balance IS NULL ORDER BY ledger_id ASC", ARRAY_A)){
							foreach($entries as $entry){
								if($entry['type_id'] > 1){
									$balance -= $entry['amount'];
								}else{
									$balance += $entry['amount'];
								}
								$wpdb->query("UPDATE ". $wpdb->prefix . "wpaccounting_ledger SET end_balance='".$balance."' WHERE ledger_id='".$entry['ledger_id']."'");
							}
						}
					}
				}
			}
		}
	}
}

add_action('admin_init', 'wpaccountingUpdateBalances'); 
?>