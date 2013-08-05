<?php
function wpaStatement(){
	global $wpdb;
	
	wp_enqueue_style( 'wpa.admin', plugins_url( '/css/admin.css', WPACCOUNTING_PLUGIN ) );
	
	if(!isset($_GET['date']) || $_GET['date'] == ''){
		$_GET['date'] = date("Y-m");
	}
	$d = $_GET['date'];
	if(strlen($d) == 4){
		$start = $d.'-01-01';
		$end = $d.'-12-31';
	}else{
		$start = $d.'-01';
		$end = $d.'-31';
	}
	$start .= ' 00:00:00';
	$end .= ' 23:59:59';
	?>
    <script type="text/javascript">
	jQuery(function($){
		$(document).ready(function(){
			$('.expenses').css('min-height',$('.income').height());
		});
	});
	</script>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div><h2>Income Statement</h2>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
        <?php
		foreach($_GET as $k => $v){
			if($k != 'date'){
				echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
			}
		}
		?>
        <p><label for="date">Report Period: </label>
        	<select name="date" id="date" onChange="this.form.submit();">
            <?php
			for($i=0;$i<24;$i++){
				$x = strtotime("-".$i." Months");
				$d = date("Y-m",$x);
				echo '<option value="'.$d.'" '.($d == $_GET['date'] ? 'SELECTED' : '').'>'.date("F Y",$x).'</option>';
			}
			for($i=date("Y");$i>=(date("Y")-10);$i--){
				echo '<option value="'.$i.'" '.(strlen($_GET['date']) == 4 && $_GET['date'] == (int)$i ? 'SELECTED' : '').'>Annual '.$i.'</option>';
			}
			?>
            </select>
        </p>
        </form>
        <div class="statement">
        <div class="income">
        	<h3>Income</h3>
            <?php
			$total_sales = 0;
			$total_expense = 0;
			$subsales = array();
			$sales_meta = get_posts(array('post_type' => 'wpa_sales_meta','posts_per_page' => -1,'orderby' => 'meta_value','meta_key' => 'plus','order' => 'DESC','post_status' => 'any'));
			$currencies = $wpdb->get_results("SELECT DISTINCT currency FROM ".$wpdb->prefix . "wpaccounting_ledger",'ARRAY_A');
			
			$default_currency = wpaDefaultCurrency();
			
			foreach($currencies as $currency){
				$total_currency_sales = 0;
				if($sales = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "wpaccounting_ledger WHERE type_id=1 AND currency='".esc_sql($currency['currency'])."' AND ledger_date BETWEEN '".$start."' AND '".$end."'")){
					echo '<dl>';
					foreach($sales as $s){
						if(count($currencies) > 1){
							if($s->currency_rate == 0){
								if($currency['currency'] == $default_currency){
									$s->currency_rate = 1;
								}else{
									$s->currency_rate = wpaGetCurrencyRate($currency['currency'],$s->ledger_date, $default_currency);
								}
								$wpdb->query("UPDATE ".$wpdb->prefix . "wpaccounting_ledger SET currency_rate='".esc_sql($s->currency_rate)."' WHERE ledger_id='".$s->ledger_id."'");
							}
							$total_sales += ($s->amount / $s->currency_rate);
						}else{
							$total_sales += $s->amount;
						}
						
						$total_currency_sales += $s->amount;
						if($meta = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "wpaccounting_meta WHERE ledger_id='".$s->ledger_id."'")){
							foreach($meta as $m){
								$subsales[$m->meta_key] += $m->meta_value;
							}
						}
					}
					
					if(!empty($subsales)){
						arsort($subsales);
						$sales_keys = array();
						if(!empty($sales_meta)){
							foreach($sales_meta as $s){
								$sales_keys[$s->post_name] = $s->post_title;
							}
						}
						
						foreach($subsales as $k => $v){
							if(isset($sales_keys[$k])){
								echo '<dt>'.$sales_keys[$k].':</dt><dd> '.wpa_moneyFormat($v, $currency['currency']).'</dd>';
							}
						}
					}else{
						echo '<dt>'.(count($currencies) > 1 ? $currency['currency'] : 'Total').' Income:</dt><dd>'.wpa_moneyFormat($total_currency_sales, $currency['currency']).'</dd>';
					}
					echo '</dl>';
					
				}elseif(count($currencies) == 1){
					echo '<p>No Sales Recorded</p>';
				}
			}
			if(count($currencies) > 1){
				echo '<dl><dt>Total Combined Income: </dt><dd>'.wpa_moneyFormat($total_sales,$default_currency).'</dd></dt>';
			}
			?>
            <br class="clear">
        </div>
        <div class="expenses">
        	<h3>Expenses</h3>
            <?php
			if($expenses = get_posts(array('post_type' => 'wpa_expense_type','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'publish','post_parent' => 0))){
				echo '<dl>';
				foreach($expenses as $k => $e){
					$x = 0;
					foreach($currencies as $currency){
						$et = 0;
						if($sum = $wpdb->get_results("SELECT ledger_id, amount, currency_rate FROM ".$wpdb->prefix . "wpaccounting_ledger WHERE type_id='".$e->ID."' AND ledger_date BETWEEN '".$start."' AND '".$end."' AND currency='".esc_sql($currency['currency'])."'")){
							foreach($sum as $s){
								if($s->currency_rate == 0){
									if($currency['currency'] == $default_currency){
										$s->currency_rate = 1;
									}else{
										$s->currency_rate = wpaGetCurrencyRate($currency['currency'],$s->ledger_date, $default_currency);
									}
									$wpdb->query("UPDATE ".$wpdb->prefix . "wpaccounting_ledger SET currency_rate='".esc_sql($s->currency_rate)."' WHERE ledger_id='".$s->ledger_id."'");
								}
								$et += $s->amount;
								$total_expense += ($s->amount / $s->currency_rate);
							}
							echo '<dt>'.($x == 0 ? $e->post_title : '&nbsp;').'</dt>';
							$x++;
							echo '<dd>'.wpa_moneyFormat($et, $currency['currency']).'</dd>';
						}
					}
					if($subexpenses = get_posts(array('post_type' => 'wpa_expense_type','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'publish','post_parent' => $e->ID))){
						foreach($subexpenses as $se){
							$x = 0;
							foreach($currencies as $currency){
								$et = 0;
								if($sum = $wpdb->get_results("SELECT ledger_id, amount, currency_rate FROM ".$wpdb->prefix . "wpaccounting_ledger WHERE type_id='".$se->ID."' AND ledger_date BETWEEN '".$start."' AND '".$end."' AND currency='".esc_sql($currency['currency'])."'")){
									foreach($sum as $s){
										if($s->currency_rate == 0){
											if($currency['currency'] == $default_currency){
												$s->currency_rate = 1;
											}else{
												$s->currency_rate = wpaGetCurrencyRate($currency['currency'],$s->ledger_date, $default_currency);
											}
											$wpdb->query("UPDATE ".$wpdb->prefix . "wpaccounting_ledger SET currency_rate='".esc_sql($s->currency_rate)."' WHERE ledger_id='".$s->ledger_id."'");
										}
										$et += $s->amount;
										$total_expense += ($s->amount / $s->currency_rate);
									}
									echo '<dt class="sub">'.($x == 0 ? $se->post_title : '&nbsp;').'</dt>';
									$x++;
									echo '<dd>'.wpa_moneyFormat($et, $currency['currency']).'</dd>';
								}
							}
						}
					}
				}
				echo '</dl>';
			}
			?>
            <br class="clear">
        </div>
        <div class="totals">
            <h3>Totals</h3>
        	<dl>
            	<dt>Gross Income:</dt>
                	<dd><?php echo wpa_moneyFormat($total_sales, $default_currency);?></dd>
                <?php
				$tax_percent = get_option('wpaccounting_tax');
				$calc_tax = 0;
				if(is_numeric($tax_percent) && $tax_percent > 0){
					if(get_option('wpaccounting_tax_included') == 1){
						$pre_tax = $total_sales / (1 + ($tax_percent/100));
						$calc_tax = $total_sales - $pre_tax;
					}else{
						$calc_tax = $total_sales * ($tax_percent/100);
					}
				?>
            	<dt>Sales Tax (<?php echo $tax_percent;?>%):</dt>
                	<dd>- <?php echo wpa_moneyFormat($calc_tax, $default_currency);?></dd>
                <?php
				}
				?>
            	<dt>Gross Expenses:</dt>
                	<dd>- <?php echo wpa_moneyFormat($total_expense, $default_currency);?></dd>    
            	<dt>Net Income (Profit):</dt>
                	<dd><strong><?php echo wpa_moneyFormat($total_sales - $calc_tax - $total_expense, $default_currency);?></strong></dd>
            </dl>
            <br class="clear">
        </div>
        </div>
    </div>
    <?php
}
?>