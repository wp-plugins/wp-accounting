<?php
class wpaReports
{
	function addReport($class){
		global $wpareports;
		$wpareports[] = $class;
	}
}
function wpaReports(){
	global $wpdb, $wpareports;
	
	?>
    <div class='wrap'>
        <div id="icon-edit-pages" class="icon32"><br /></div>
        <?php
		if($_GET['report'] == ''){
		?>
            <h2>Custom Reports</h2>
            <form action="<?php echo basename($_SERVER['REQUEST_URI']);?>" method="get">
            <?php
            if(!empty($_GET)){
                foreach($_GET as $k => $v){
                    echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                }
            }
            ?>
            <p>Select a Report:
            <?php
            if(empty($wpareports)){
                echo 'You do not have any custom Wordpress Accounting reports installed.';
            }else{
                echo '<select name="report">';
                foreach($wpareports as $v){
                    $c = new $v;
                    echo '<option value="'.$v.'">'.$c->info['title'].'</option>';
                }
                echo '</select> <input type="submit" value="Run Report">';
            }
            ?>
            </p>
            </form>
        <?php
		}else{
			if(class_exists($_GET['report'])){
				$report = new $_GET['report'];
				echo '<h2>'.$report->info['title'].'</h2>';
				$select = $report->selection();
				if($select && $_POST['process'] != 1){
					echo '<form action="'.$_SERVER['REQUEST_URI'].'" method="post"><input type="hidden" name="process" value="1">';
					echo $select;
					echo '<p><input type="submit" value="Get Report"></p>';
					echo '</form>';
				}else{
					$report->output();
				}
			}else{
				echo '<p>Report not found</p>';
			}
		}
		?>
    </div>
    <?php
}
?>