<?php
if ( !is_admin() )
{
    echo 'Direct access not allowed.';
    exit;
}
?>
<link href="<?php echo plugins_url('../assets/css/admin_style.css', __FILE__); ?>" type="text/css" rel="stylesheet" />   

<?php _e('Click a subject if you want to see more detail', 'pondol_formmail');?>
<form action="" method="get">
	

	<table class="wp-list-table widefat fixed">
		<col width="*" />
		<col width="200px" />
		<col width="200px" />
		<thead>
			<tr>
				<th><?php _e('subject', 'pondol_formmail');?></th>
				<th><?php _e('notify to', 'pondol_formmail');?></th>
				<th><?php _e('created', 'pondol_formmail');?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if(is_array($message_rows)) {
				foreach($message_rows as $key=>$val){
					$data = unserialize($val->data);
				?>
			<tr<?php echo $key%2 == 0?' class="alternate"':'';?>>
				<td><?php echo $val->subject ? $val->subject : __('No Subject', 'pondol_formmail');?></td>
				<td><?php echo $val->notifyto ? $val->notifyto: __('No notify to', 'pondol_formmail');?></td>
				<td><?php echo $val->time;?></td>
			</tr>
			<tr style="padding: 10px"<?php echo $key%2 == 0?' class="alternate"':'';?>>
				<td colspan="3"><?php echo $data["message"];?>
					
					<?php
						//call detail
						echo "<table>";
						if(is_array($data["post"])) foreach($data["post"] as $k => $v ){
							if($k != "ps_send_mail" && $k != "itemid"){
								echo "<tr><td>".$k."</td><td>".$v."</td></tr>";		
							}
						}
						echo "</table>";
					?>
				</td>
			</tr>
			<?php
			}
			}else{
			?>
			<tr>
				<td colspan="3"></br>no log.</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</form>

<?php
if ( $page_links ) {
		    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}
?>