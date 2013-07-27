<?php
class wpaccounting
{
	public function insert($data){
		global $wpdb;
		if(!isset($data['amount'])){
			return false;
		}
		if(!isset($data['type_id'])){
			$data['type_id'] = 1;
		}elseif(!is_numeric($data['type_id'])){
			// Lookup expense
		}
		
		if((int)$data['account_id'] == 0){
			if($currency = get_page_by_title( $data['currency'], 'OBJECT', 'wpa_currency')){
				$data['account_id'] = get_post_meta($currency->ID, 'default_account', true);
			}
		}
		
		if(!isset($data['ledger_date'])){
			$data['ledger_date'] = $this->wpdbNOW();
		}
		return $wpdb->insert($wpdb->prefix . "wpaccounting_ledger", $data);
	}
	
	private function wpdbNOW($stamp=''){
		if($stamp == ''){
			$stamp = time();
		}
		return date("Y-m-d H:i:s",$stamp);
	}
}
?>