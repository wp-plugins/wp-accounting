<?php
function wpaSettings(){
	global $wp_roles;
	$settings = array('wpaccounting_currency','wpaccounting_tax','wpaccounting_tax_included','wpaccounting_multicurrency','wpaccounting_access');
	
	if(!empty($_POST['settings'])){
		foreach($settings as $s){
			update_option($s,(isset($_POST['settings'][$s]) ? $_POST['settings'][$s] : '0'));
		}
	}
	if(get_option('wpaccounting_multicurrency') == 1){
		$currencies = get_posts(array('post_type' => 'wpa_currency','posts_per_page' => -1,'orderby' => 'ID','order' => 'ASC','post_status' => 'publish'));
		if(empty($currencies)){
			$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => 1,'orderby' => 'ID','order' => 'ASC','post_status' => 'publish'));
			$default_currency = array(
			  'post_title'    => 'USD',
			  'post_status'   => 'publish',
			  'post_type'	  => 'wpa_currency'
			);
			$currency_id = wp_insert_post( $default_currency );
			update_post_meta($currency_id, 'default_account', $accounts[0]->ID);
			update_post_meta($currency_id, 'currency_default', 1);
		}
	}
	?>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div><h2>Wordpress Accounting Settings</h2>
        <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
        <table cellspacing="0" cellpadding="2" class="form-table">
        	<tr>
            	<th scope="row">Currency Symbol: </th>
                <td><input type="text" name="settings[wpaccounting_currency]" value="<?php echo get_option('wpaccounting_currency');?>"></td>
            </tr>
        	<tr>
            	<th scope="row">Sales Tax: </th>
                <td><input type="text" name="settings[wpaccounting_tax]" value="<?php echo get_option('wpaccounting_tax');?>">%</td>
            </tr>
        	<tr>
            	<th scope="row">Sales Tax included in sales: </th>
                <td><input type="checkbox" name="settings[wpaccounting_tax_included]" value="1" <?php echo (get_option('wpaccounting_tax_included') == 1 ? 'CHECKED' : '');?>></td>
            </tr>
        	<tr>
            	<th scope="row">Use Multiple Currencies: </th>
                <td><input type="checkbox" name="settings[wpaccounting_multicurrency]" value="1" <?php echo (get_option('wpaccounting_multicurrency') == 1 ? 'CHECKED' : '');?>></td>
            </tr>
        	<tr>
            	<th scope="row">Accounting Access Level: </th>
                <td>
                <?php
				$access = get_option('wpaccounting_access');
				if(!empty($access)){
					$keys = array_keys($access);
				}
				foreach($wp_roles->roles as $k => $v){
					echo '<input type="checkbox" name="settings[wpaccounting_access]['.$k.']" value="1" '.($k == 'administrator' || (!empty($access) && in_array($k,$keys)) ? 'CHECKED' : '').'> '.$v['name'].'<br>';
				}
				?>
                </td>
            </tr>
            
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>
    </div>
    <?php
}
?>