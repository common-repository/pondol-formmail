<script type="text/javascript" >
jQuery(document).ready(function($) {
	$("#btn_save_mailinfo").click(function(){
		console.log($("#form_mailinfo").serialize());
		$.post(ajaxurl, $("#form_mailinfo").serialize(), function(data){
			//console.log(data);
			eval("var obj="+data);
			if(obj.success == true){
				alert(obj.message);
			}else alert(obj.message);
			
		});
	});

	$("#pondol_paypal").click(function(){
		$("body").append('<form action="https://www.paypal.com/cgi-bin/webscr" id="pondol_paypal_submit_form" method="post" target="_blank"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"></form>');
		$("#pondol_paypal_submit_form").submit();
	});
});


</script>
<form id="form_mailinfo">
	<input type="hidden" name="action" value="pondol_formail_save_info">
	<input type="hidden" name="mainInfo_itemId" value="<?php echo $_GET["itemid"];?>">
	<table>
		<tr>
			<th><?php _e('mail form', 'pondol_formmail');?></th>
			<td> : <?php
			$dirList = PONDOLEPLUGIN_FORMMAIL_PATH."skins/";
						$open_dir = opendir($dirList);
						$skins = array();
						echo '<select name="form_skin">';
						while($opendir = readdir($open_dir)) {
							if(($opendir != ".") && ($opendir != "..") ) {
								$selected = $opendir == $data_info["form_skin"]? " selected":"";
								echo '<option value="'.$opendir.'"'.$selected.'>'.$opendir.'</option>\n';
								//array_push($skins, array("dir"=>$dirList.$opendir, "name"=>$opendir));
							}
						}
						closedir($open_dir);
						echo "</select>";
			?></td>
		</tr>
		<tr>
			<th><?php _e('receiver email address', 'pondol_formmail');?></th>
			<td> : <input name="to_email" type="text" value="<?php echo $data_info["to_email"];?>"></td>
		</tr>
		<tr>
			<th><?php _e('receiver name', 'pondol_formmail');?></th>
			<td> : <input name="to_name" type="text" value="<?php echo $data_info["to_name"];?>"></td>
		</tr>
		<tr>
			<th><?php _e('return page', 'pondol_formmail');?></th>
			<td> : <input name="return_page" type="text" value="<?php echo $data_info["return_page"];?>"></td>
		</tr>
		<tr>
			<th valign="top"><?php _e('Display copyright', 'pondol_formmail');?></th>
			<td> : 
				<input  type="checkbox" name="pondol_formmail_copyright" id="pondol_formmail_copyright" style="width:16px;"<?php echo $data_info["pondol_formmail_copyright"] == "true"? " checked":"";?>>
				<p>
					<span class="description"><?php _e( 'if you want to not showing copy right, uncheck this.', 'pondol_bbs' )?></span>
					<img id="pondol_paypal"src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="width:146px">
				</p>
			</td>
		</tr>
	</table>
	<button type="button" id="btn_save_mailinfo">save</button>
</form>