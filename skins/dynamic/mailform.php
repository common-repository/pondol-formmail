<script type="text/javascript">
jQuery(document).ready(function($) {
    	$('#pondol_form').validate({
            <?php echo $info["validation"]; ?>
            submitHandler: function (frm) {
                frm.submit();
            },
            success: function (e) { 
            //
			}
        });
});
</script>
<form enctype="multipart/form-data" id="pondol_form" method="post">
	<input type="hidden" name="ps_send_mail" value="1">
	<input type="hidden" name="itemid" value="<?php echo $id;?>">
	<table>
		<tr>
			<?php 
			foreach($info["ele_position"] as $key=>$val){
				echo '<td>';
				foreach($info["form_ele"][$key] as $k => $v){
					echo $v;
				}
				echo '</td>';
			if($key%2) echo '</tr><tr>';
			}
			?>
		</tr>
	</table>
</form>