<?php
function wpaSettings(){
	if(!empty($_POST['settings'])){
		foreach($_POST['settings'] as $k => $v){
			update_option($k,$v);
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
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>
    </div>
    <?php
}
?>