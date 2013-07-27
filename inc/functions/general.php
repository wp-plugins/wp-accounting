<?php

function wpa_moneyFormat($str, $currency=''){
	if($currency != ''){
		if($p = get_page_by_title($currency, 'OBJECT', 'wpa_currency')){
			return get_post_meta($p->ID, 'currency_symbol1', true).number_format($str,get_post_meta($p->ID, 'currency_decimals', true)).get_post_meta($p->ID, 'currency_symbol2', true);
		}
	}
	if(!is_numeric($str)){
		return WPA_CUR.$str;
	}
	return WPA_CUR.number_format($str,2);
}

function wpaDefaultCapitalAccount(){
	$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => 1,'orderby' => 'ID','post_status' => 'publish',
								'meta_query' => array(
									array(
										'key' => 'account_type',
										'value' => 'capital',
									)
								)));
	return $accounts[0]->ID;
}

function wpaDefaultCurrency($type='code'){
		if($default_currency = get_posts(array('post_type' => 'wpa_currency','posts_per_page' => -1,
									'meta_query' => array(
										array(
											'key' => 'currency_default',
											'value' => '1',
										)
							)))){
			
			return ($type == 'code' ? $default_currency[0]->post_title : $default_currency[0]->ID);
		}
		return false;
}

function wpaGetCurrencyRate($currency, $date='', $default_currency=''){
		if($default_currency == ''){
			$default_currency = wpaDefaultCurrency();
		}
		if($date == ''){
			$date = date("m/d/y");
		}else{
			$date = date("m/d/y", strtotime($date));
		}
		$url = 'http://www.oanda.com/convert/fxhistory?date_fmt=us&date='.$date.'&date1='.$date.'&exch='.$default_currency.'&expr='.$currency.'&lang=en&margin_fixed=0&format=HTML&redirected=1';
		
		$data = wp_remote_get($url);
		$html = $data['body'];
		
		preg_match('#<TD ALIGN=center WIDTH=80><font face=Verdana size=2>(.*)</font></TD></TR>#',$html,$matches);
		return $matches[1];
}
?>