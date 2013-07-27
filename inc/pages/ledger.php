<?php
function wpaLedger(){
	global $wpdb;
	if(isset($_GET['delete'])){
		$wpdb->query("DELETE FROM ".$wpdb->prefix . "wpaccounting_ledger WHERE ledger_id='".(int)$_GET['delete']."'");
		unset($_GET);
		?>
        <div id="message" class="updated">
            <p>Entry has been deleted</p>
        </div>
        <?php
	}elseif(isset($_GET['balancereset'])){
		if($entry = $wpdb->get_row("SELECT currency FROM ".$wpdb->prefix."wpaccounting_ledger WHERE ledger_id='".(int)$_GET['balancereset']."'", ARRAY_A)){
			$wpdb->query("UPDATE ".$wpdb->prefix . "wpaccounting_ledger SET end_balance=NULL WHERE ledger_id>='".(int)$_GET['balancereset']."' AND currency='".esc_sql($entry['currency'])."'");
			wpaccountingUpdateBalances();
			unset($_GET['balancereset']);
			?>
			<div id="message" class="updated">
				<p>Balance has been recalculated</p>
			</div>
			<?php
		}
	}
	?>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div><h2>Accounting Ledger</h2>
        <?php
        include_once(dirname(__FILE__).'/../tables/ledger.php');
        $wp_list_table = new directory_ledger_table();
        $wp_list_table->prepare_items();
        $wp_list_table->display();
		?>
    </div>
    <?php
}
?>