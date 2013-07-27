<?php
function wpaBalanceSheet(){
	global $wpdb;
	
	wp_enqueue_style( 'wpa.admin', plugins_url( '/css/admin.css', WPACCOUNTING_PLUGIN ) );
	?>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div><h2>Balance Sheet</h2>
        <div class="balancesheet">
        	<?php
			$account_types = array('asset' => 'Assets',
								   'liability' => 'Liabilities',
								   'capital' => 'Equity'
								   );
			$equity = array();
			foreach($account_types as $account_key => $account_type){
			?>
        	<div class="<?php echo $account_key;?>">
            	<h3><?php echo $account_type;?></h3>
                <div>
                <?php
				$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'publish',
											'meta_query' => array(
												array(
													'key' => 'account_type',
													'value' => $account_key,
												)
									)));
				if(!empty($accounts) || $account_key == 'capital'){
					echo '<dl>';
					foreach($accounts as $account){
						echo '<dt>'.$account->post_title.'</dt>';
						$currencies = $wpdb->get_results("SELECT DISTINCT currency FROM ". $wpdb->prefix . "wpaccounting_ledger WHERE account_id='".(int)$account->ID."'", ARRAY_A);
						if(!empty($currencies)){
							foreach($currencies as $currency){
								$balances = $wpdb->get_results("SELECT end_balance FROM ". $wpdb->prefix . "wpaccounting_ledger WHERE account_id='".(int)$account->ID."' AND currency='".esc_sql($currency['currency'])."' ORDER BY ledger_id DESC LIMIT 1", ARRAY_A);
								if(!empty($balances)){
									foreach($balances as $balance){
										if($account_key == 'asset'){
											$equity[$currency['currency']] += $balance['end_balance'];
										}else{
											$equity[$currency['currency']] -= $balance['end_balance'];
										}
										echo '<dd>'.wpa_moneyFormat($balance['end_balance'], $currency['currency']).'</dd>';
									}
								}
							}
						}else{
							echo '<dd>Emtpy</dd>';
						}
					}
					if($account_key == 'capital'){
						echo '<dt>Owners Equity</dt>';
						if(!empty($equity)){
							foreach($equity as $k => $v){
								echo '<dd>'.wpa_moneyFormat($v, $k).'</dd>';
							}
						}
					}
					echo '</dl>';
				}else{
					echo '<p>None</p>';
				}
				?>
                </div>
            </div>
            <?php
			}
			?>
            <br class="clear">
        </div>
    </div>
    <?php
}
?>