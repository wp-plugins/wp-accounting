<?php
//Our class extends the WP_List_Table class, so we need to make sure that it's there
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class directory_ledger_table extends WP_List_Table {

	 var $item_options;
	 var $sales_meta;
	 var $transaction_meta;
	 
    function __construct(){
        global $status, $page;
		
		$this->sales_meta = get_posts(array('post_type' => 'wpa_sales_meta','posts_per_page' => -1,'orderby' => 'meta_value','meta_key' => 'plus','order' => 'DESC','post_status' => 'publish'));
		
		$this->transaction_meta = get_posts(array('post_type' => 'wpa_transaction_meta','posts_per_page' => -1,'orderby' => 'title','post_status' => 'publish'));
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'entry',     //singular name of the listed records
            'plural'    => 'entries',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
	
    function column_default($item, $column_name){
		global $wpdb;
		$value = $item->$column_name;
		
		$ray = array_merge($this->sales_meta, $this->transaction_meta);
		if(!empty($ray)){
			foreach($ray as $v){
				if($v->post_name == $column_name){
					if($var = $wpdb->get_var("SELECT meta_value FROM ".$wpdb->prefix . "wpaccounting_meta WHERE meta_key='".$v->post_name."' AND ledger_id='".$item->ledger_id."'")){
						$value = $var;
					}
				}
			}
		}
		
		if(!empty($this->sales_meta)){
			foreach($this->sales_meta as $v){
				if($column_name == $v->post_name){
					$value = WPA_CUR.number_format($value,2);
					if(get_post_meta($v->ID,'plus',true) == 0){
						$value = '('.$value.')';
					}
				}
			}
		}
		
		switch($column_name){
			case 'amount':
				$val = WPA_CUR.number_format($value,2);
				if(stristr($item->type,'expense')){
					$val = '('.$val.')';
				}
				return $val;
			break;
			default:
				return $value;
			break;
		}
    }
    
    function get_columns(){
        $columns = array(
            'ledger_date'     => 'Date',
			'type' => 'Type',
			'amount' => 'Amount'
        );
		
		if(!empty($this->sales_meta)){
			foreach($this->sales_meta as $v){
				$columns[$v->post_name] = $v->post_title;
			}
		}
		
		if(!empty($this->transaction_meta)){
			foreach($this->transaction_meta as $v){
				$columns[$v->post_name] = $v->post_title;
			}
		}
        return $columns;
    }
    
    function get_sortable_columns() {
        $sortable_columns = array();
		$col = $this->get_columns();
		foreach($col as $k => $c){
			$sortable_columns[$k] = array($k,true);
		}
        return $sortable_columns;
    }
    
    function prepare_items() {
        global $wpdb;
		
        $perpage = 50;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns, $hidden, $sortable);
        
        
        $this->process_bulk_action();
        
		/* -- Preparing your query -- */
		$query = "SELECT l.ledger_id, l.ledger_date, l.amount, IF(l.type_id = 1, 'Income: Sales', CONCAT('Expense: ',(SELECT post_title FROM ".$wpdb->prefix."posts WHERE ID=l.type_id))) as type";
		$query .= " FROM ".$wpdb->prefix . "wpaccounting_ledger l ";
		
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : '';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';
		if($orderby == ''){
			$k = array_keys($sortable);
			$orderby = $k[0];
			$order = 'DESC';
		}
		$query .= ' ORDER BY ';
		if(!empty($orderby)){ $query.=$orderby.' '.$order; }
		
		$total_items = $wpdb->query($query); //return the total number of affected rows
		$wpdb->flush();
		
        $current_page = $this->get_pagenum();
		
		if(!empty($current_page) && !empty($perpage)){
			$offset=($current_page-1)*$perpage;
			$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
		}
		
        $this->items = $wpdb->get_results($query);
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $perpage,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$perpage)   //WE have to calculate the total number of pages
        ) );
    }
	function process_bulk_action(){
		return;
	}
}
?>