<?php
function wpaLedger(){
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