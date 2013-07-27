<?php
function wpa_custom_post_types() {
	$labels = array(
    'name' => 'Vendors',
    'singular_name' => 'Vendor',
	'add_new' => __( 'Add Vendor' ),
	'add_new_item' => __( 'Add New Vendor' ),
	'edit_item' => __( 'Edit Vendor' ),
	'new_item' => __( 'New Vendor'),
	'view' => __( 'View Vendor'),
	'view_item' => __( 'View Vendor'),
	'search_items' => __( 'Search Vendors'),
	'not_found' => __( 'No Vendors found' ),
	'not_found_in_trash' => __( 'No Vendors found in trash' ),
	'parent' => __( 'Parent Vendor')
	);
	
    $args = array('public' => true, 
				  'show_ui' => true,
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title')
				  );
    register_post_type( 'wpa_vendors', $args );
	
	$labels = array(
    'name' => 'Expense Types',
    'singular_name' => 'Expense',
	'add_new' => __( 'Add Expense' ),
	'add_new_item' => __( 'Add New Expense' ),
	'edit_item' => __( 'Edit Expense' ),
	'new_item' => __( 'New Expense'),
	'view' => __( 'View Expense'),
	'view_item' => __( 'View Expense'),
	'search_items' => __( 'Search Expenses'),
	'not_found' => __( 'No Expenses found' ),
	'not_found_in_trash' => __( 'No Expenses found in trash' ),
	'parent' => __( 'Parent Expense')
	);
	
    $args = array('hierarchical' => true,
				  'public' => true, 
				  'show_ui' => true,
				  'capability_type' => 'page',
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title','page-attributes')
				  );
    register_post_type( 'wpa_expense_type', $args );
	
	$labels = array(
    'name' => 'Extra Income Data',
    'singular_name' => 'Extra Income Data',
	'add_new' => __( 'Add Data Field' ),
	'add_new_item' => __( 'Add New Data Field' ),
	'edit_item' => __( 'Edit Data Field' ),
	'new_item' => __( 'New Data Field'),
	'view' => __( 'View Data Field'),
	'view_item' => __( 'View Data Field'),
	'search_items' => __( 'Search Data Fields'),
	'not_found' => __( 'No Data Fields found' ),
	'not_found_in_trash' => __( 'No Data Fields found in trash' ),
	'parent' => __( 'Parent Data Field')
	);
	
    $args = array('public' => true, 
				  'show_ui' => true,
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title')
				  );
    register_post_type( 'wpa_transaction_meta', $args );
	
	$labels = array(
    'name' => 'Income Line',
    'singular_name' => 'Income Line',
	'add_new' => __( 'Add Income Line' ),
	'add_new_item' => __( 'Add New Income Line' ),
	'edit_item' => __( 'Edit Income Line' ),
	'new_item' => __( 'New Income Line'),
	'view' => __( 'View Income Line'),
	'view_item' => __( 'View Income Line'),
	'search_items' => __( 'Search Income Lines'),
	'not_found' => __( 'No Income Lines found' ),
	'not_found_in_trash' => __( 'No Income Lines found in trash' ),
	'parent' => __( 'Parent Income Line')
	);
	
    $args = array('public' => true, 
				  'show_ui' => true,
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title')
				  );
    register_post_type( 'wpa_sales_meta', $args );
	
	$labels = array(
    'name' => 'Currencies',
    'singular_name' => 'Currency',
	'add_new' => __( 'Add Currency' ),
	'add_new_item' => __( 'Add New Currency' ),
	'edit_item' => __( 'Edit Currency' ),
	'new_item' => __( 'New Currency'),
	'view' => __( 'View Currency'),
	'view_item' => __( 'View Currency'),
	'search_items' => __( 'Search Currencies'),
	'not_found' => __( 'No Currencies found' ),
	'not_found_in_trash' => __( 'No Currencies found in trash' ),
	'parent' => __( 'Parent Currency')
	);
	
    $args = array('public' => true, 
				  'show_ui' => true,
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title')
				  );
    register_post_type( 'wpa_currency', $args );
	
	$labels = array(
    'name' => 'Accounts',
    'singular_name' => 'Account',
	'add_new' => __( 'Add Account' ),
	'add_new_item' => __( 'Add New Account' ),
	'edit_item' => __( 'Edit Account' ),
	'new_item' => __( 'New Account'),
	'view' => __( 'View Account'),
	'view_item' => __( 'View Account'),
	'search_items' => __( 'Search Accounts'),
	'not_found' => __( 'No Accounts found' ),
	'not_found_in_trash' => __( 'No Accounts found in trash' ),
	'parent' => __( 'Parent Account')
	);
	
    $args = array('public' => true, 
				  'show_ui' => true,
				  'show_in_nav_menus' => false,
				  'show_in_menu' => false,
				  'labels' => $labels,
				  'supports' => array( 'title')
				  );
    register_post_type( 'wpa_accounts', $args );
}

add_action('init', 'wpa_custom_post_types');


// Add Meta Boxes
function add_wpa_metaboxes() {
	add_meta_box('wpa_currency_info', 'Currency Details', 'wpa_currency_info', 'wpa_currency', 'normal', 'default');
	add_meta_box('wpa_accounts_info', 'Account Details', 'wpa_accounts_info', 'wpa_accounts', 'normal', 'default');
}
add_action( 'add_meta_boxes', 'add_wpa_metaboxes' );

function wpa_currency_info() {
	global $post;
	
	echo '<input type="hidden" name="wpa_noncename" id="wpa_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	$dec = get_post_meta($post->ID, 'currency_decimals', true);
	echo '<p><label for="currency_default">Primary Accounting Currency: </label><input type="checkbox" id="currency_default" name="wpameta[currency_default]" value="1"'.(get_post_meta($post->ID, 'currency_default', true) == 1 ? ' CHECKED' : '').'></p>';
	echo '<p><label for="currency_decimals">Decimal Places:</label><input type="text" id="currency_decimals" name="wpameta[currency_decimals]" value="' . ($dec != '' ? $dec : 2)  . '" class="widefat" /></p>';
	echo '<p><label for="currency_symbol1">Symbol Before:</label><input type="text" id="currency_symbol1" name="wpameta[currency_symbol1]" value="' . get_post_meta($post->ID, 'currency_symbol1', true)  . '" class="widefat" /></p>';
	echo '<p><label for="currency_symbol2">Symbol After:</label><input type="text" id="currency_symbol2" name="wpameta[currency_symbol2]" value="' . get_post_meta($post->ID, 'currency_symbol2', true)  . '" class="widefat" /></p>';
	echo '<p><label for="default_account">Default Account for transaction in this currency:</label><select id="default_account" name="wpameta[default_account]" class="widefat">';
	$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => -1,'orderby' => 'post_title','post_status' => 'publish'));
	foreach($accounts as $account){
		echo '<option value="'.$account->ID.'" '.($account->ID == get_post_meta($post->ID, 'default_account',true) ? 'SELECTED' : '').'>'.$account->post_title.'</option>';
	}
	echo '</select></p>';
}

function wpa_accounts_info(){
	global $post;
	
	$account_types = array('asset' => 'Asset Account',
						   'capital' => 'Capital Account',
						   'liability' => 'Liability Account');
	
	echo '<input type="hidden" name="wpa_noncename" id="wpa_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<p><label for="account_type">Account Type:</label><select id="account_type" name="wpameta[account_type]" class="widefat">';
	foreach($account_types as $k => $v){
		echo '<option value="'.$k.'" '.($k == get_post_meta($post->ID, 'account_type', true) ? 'SELECTED' : '').'>'.$v.'</option>';
	}
	echo '</select>
	</p>';
}


// Save the Metabox Data
function wpa_meta_info_save($post_id, $post) {
	global $wpdb;
	if ( !wp_verify_nonce( $_POST['wpa_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
		
	if(!empty($_POST['wpameta'])){
		foreach($_POST['wpameta'] as $k => $v){
			update_post_meta($post->ID, $k, $v);
			if($k == 'currency_default'){
				if($currencies = get_posts(array('post_type' => 'wpa_currency','posts_per_page' => -1,'exclude' => $post->ID))){
					foreach($currencies as $c){
						update_post_meta($c->ID, $k, 0);
					}
				}
				$wpdb->query("UPDATE ".$wpdb->prefix . "wpaccounting_ledger SET currency_rate=NULL");
			}
		}
	}
}
add_action('save_post', 'wpa_meta_info_save', 1, 2);


function wpa_accounts_columns($columns) {
	unset($columns['date']);
	$columns['account_type'] = 'Account Type';
	$columns['date'] = 'Date Created';
	return $columns;
}
add_filter('manage_edit-wpa_accounts_columns', 'wpa_accounts_columns');

function manage_wpa_accounts_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {
		case 'account_type':
			echo ucwords(get_post_meta($id, 'account_type', true)). ' Account';
		break;
    } 
} 
add_action('manage_wpa_accounts_posts_custom_column', 'manage_wpa_accounts_columns', 10, 2);

function wpa_create_defaults(){
	$accounts = get_posts(array('post_type' => 'wpa_accounts','posts_per_page' => 1,'post_status' => 'publish'));
	if(empty($accounts)){
		$default_account = array(
		  'post_title'    => 'Bank Account',
		  'post_status'   => 'publish',
		  'post_type'	  => 'wpa_accounts'
		);
		$account_id = wp_insert_post( $default_account );
		update_post_meta($account_id, 'account_type','asset');
		
		/*$default_account = array(
		  'post_title'    => 'Owners Equity',
		  'post_status'   => 'publish',
		  'post_type'	  => 'wpa_accounts'
		);
		$account_id = wp_insert_post( $default_account );
		update_post_meta($account_id, 'account_type','capital');
		*/
	}
}

add_action('admin_init', 'wpa_create_defaults');
?>