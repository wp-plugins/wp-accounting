<?php
function wpaEntry(){
	global $wpdb;
	
	$total_sales = 0;
	if(!empty($_POST)){
		if(!empty($_POST['sales'])){
			foreach($_POST['sales'] as $k => $v){
				$plus = get_post_meta($k,'plus',true);
				if($plus == 0){
					$total_sales -= $v;
				}else{
					$total_sales += $v;
				}
			}
		}
	}
	if($total_sales > 0){
		if($stamp = strtotime($_POST['date'])){
			$sql_array = array('ledger_date' => date("Y-m-d",$stamp),
							   'type_id' => 1,
							   'amount' => $total_sales);
			$wpdb->insert($wpdb->prefix . "wpaccounting_ledger", $sql_array);
			$ledger_id = $wpdb->insert_id;
			
			$meta = ($_POST['sales'] + $_POST['meta']);
			foreach($meta as $k => $v){
				$p = get_post($k);
				
				if($p->post_type == 'wpa_sales_meta'){
					$plus = get_post_meta($k,'plus',true);
					if($plus == 0){
						$v = ($v * -1);
					}
				}
				
				$sql_array = array('ledger_id' => $ledger_id,
								   'meta_key' => $p->post_name,
								   'meta_value' => $v);
				$wpdb->insert($wpdb->prefix . "wpaccounting_meta", $sql_array);
			}
			?>
            <div id="message" class="updated">
            	<p>Sales have been recorded</p>
            </div>
            <?php
		}else{
			$errors[] = 'Please enter a valid date';
		}
	}
	
	if(!empty($_POST['expense_amount'])){
		foreach($_POST['expense_amount'] as $k => $amount){
			if($amount > 0){
				if($stamp = strtotime($_POST['expense_date'][$k])){
					$sql_array = array('ledger_date' => date("Y-m-d",$stamp),
									   'type_id' => $_POST['expense_type'][$k],
									   'amount' => $amount,
									   'details' => $_POST['expense_details'][$k],
									   'vendor_id' => $_POST['expense_vendor'][$k]);
					$wpdb->insert($wpdb->prefix . "wpaccounting_ledger", $sql_array);
					$expense_id = $wpdb->insert_id;
				}
			}
		}
		if($expense_id > 0){
		?>
		<div id="message" class="updated">
			<p>Expenses have been recorded</p>
		</div>
		<?php
		}
	}
	
	if(!empty($errors)){
		?>
        <div id="message" class="error">
        	<?php
			foreach($errors as $e){
				echo '<p>'.$e.'</p>';
			}
			?>
        </div>
        <?php
	}
	
	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'wpa.admin', plugins_url( '/js/admin.js', WPACCOUNTING_PLUGIN) );
	wp_enqueue_style( 'jquery.ui.theme', plugins_url( '/css/smoothness/jquery-ui.css', WPACCOUNTING_PLUGIN ) );
	wp_enqueue_style( 'wpa.admin', plugins_url( '/css/admin.css', WPACCOUNTING_PLUGIN ) );
	?>
    <script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.datepicker').datepicker();
	});
    </script>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div><h2>Accounting Entry</h2>
    </div>
    <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
    <fieldset>
    	<legend>Input Sales: </legend>
        <table cellpadding="2" cellspacing="0">
        	<?php
			$sales_meta = get_posts(array('post_type' => 'wpa_sales_meta','posts_per_page' => -1,'orderby' => 'meta_value','meta_key' => 'plus','order' => 'DESC','post_status' => 'any'));
			
			foreach($sales_meta as $s){
			?>
        	<tr>
            	<td><?php 
				$plus = get_post_meta($s->ID,'plus',true);
				if($plus == 0){
					echo 'Less ';
				}
				echo $s->post_title;
				if($plus != 0){
					echo ' Total';
				}
				?>: </td>
                <td><?php echo WPA_CUR;?><input type="text" name="sales[<?php echo $s->ID;?>]" class="<?php echo ($plus == 0 ? 'less' : '');?>"></td>
            </tr>
            <?php
			}
			?>
            <tr>
                <td>Date: </td>
                <td><input type="text" name="date" class="datepicker" value="<?php echo date("m/d/Y");?>" id="wpasaledate"></td>
            </tr>
        	<?php
			$meta_options = get_posts(array('post_type' => 'wpa_transaction_meta','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'any'));
			foreach($meta_options as $m){
			?>
        	<tr>
            	<td><?php echo $m->post_title; ?>: </td>
                <td><input type="text" name="meta[<?php echo $m->ID;?>]"></td>
            </tr>
            <?php
			}
			?>
            <tr>
                <td colspan="2">
                    <input class="button-primary" type="submit" value="Input Sales">
                </td>
            </tr>
        </table>
    </fieldset>
    <fieldset>
    	<legend>Input Expenses: </legend>
        <table cellpadding="2" cellspacing="0">
        	<tr>
            	<th>Expense</th>
                <th>Amount</th>
                <th>Vendor</th>
                <th>Date</th>
                <th>Notes</th>
            </tr>
            <?php
			$vendors = get_posts(array('post_type' => 'wpa_vendors','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'any'));
			
			$expenses = get_posts(array('post_type' => 'wpa_expense_type','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'publish','post_parent' => 0));
			foreach($expenses as $k => $e){
			?>
            <tr>
            	<td><select name="expense_type[<?php echo $k;?>]">
                <?php
				foreach($expenses as $exp){
					echo '<option value="'.$exp->ID.'"'.($exp->ID == $e->ID ? ' SELECTED' : '').'>'.$exp->post_title.'</option>';
					if($sub_expenses = get_posts(array('post_type' => 'wpa_expense_type','posts_per_page' => -1,'orderby' => 'title','order' => 'ASC','post_status' => 'publish','post_parent' => $exp->ID))){
						foreach($sub_expenses as $se){
							echo '<option value="'.$se->ID.'">&nbsp;|__'.$se->post_title.'</option>';
						}
					}
				}
				?>
                </select>
                </td>
                <td><?php echo WPA_CUR;?><input type="text" name="expense_amount[<?php echo $k;?>]"></td>
                <td><select name="expense_vendor[<?php echo $k;?>]">
                	<option value="0">No Vendor Assigned</option>
                    <?php
					foreach($vendors as $v){
						echo '<option value="'.$v->ID.'">'.$v->post_title.'</option>';
					}
					?>
                </select></td>
                <td><input type="text" name="expense_date[<?php echo $k;?>]" class="datepicker" value="<?php echo date("m/d/Y");?>"></td>
                <td><input type="text" name="expense_details[<?php echo $k;?>]"></td>
            </tr>
            <?php
			}
			?>
            <tr>
                <td colspan="4">
                    <input class="button-primary" type="submit" value="Input Expenses">
                </td>
            </tr>
        </table>
    </fieldset>
    </form>
    <?php
}
?>