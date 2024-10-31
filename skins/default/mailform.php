<script type="text/javascript">
jQuery(document).ready(function($) {
    	$('#sendMailForm_<?php echo $id;?>').validate({
            rules: {
                email_<?php echo $id;?>: { required: true},
                name_<?php echo $id;?>: { required: true },
                subject_<?php echo $id;?>: { required: true },
                message_<?php echo $id;?>: { required: true }
            },
            messages: {
                email_<?php echo $id;?>: { required: "Pls. input your email" },
                name_<?php echo $id;?>: { required: "Pls. input your name" },
                subject_<?php echo $id;?>: { required: "Pls. input subject" },
                message_<?php echo $id;?>: { required: "Pls. input message" },
            },
            submitHandler: function (frm) {
                frm.submit();
            },
            success: function (e) { 
            //
			}
        });
});
</script>
<div class="pondol_formail_div">
	<form method="post" id="sendMailForm_<?php echo $id;?>" enctype="multipart/form-data">
		<input type="hidden" name="ps_send_mail" value="1">
		<input type="hidden" name="itemid" value="<?php echo $id;?>">
		<h2><?php echo $data_info["form_name"] ;?></h2>
		<table>
			<tr>
				<td><?php _e('Mail Address', 'pondol_formmail');?></td>
				<td><input id="email_<?php echo $id;?>" name="email" class="required" type="text" value=""></td>
			</tr>
			<tr>
				<td><?php _e('Your Name', 'pondol_formmail');?></td>
				<td><input id="name_<?php echo $id;?>" name="name" class="required" type="text" value=""></td>
			</tr>
			<tr>
				<td><?php _e('Subject', 'pondol_formmail');?></td>
				<td><input id="subject_<?php echo $id;?>" name="subject" class="required" type="text" value=""></td>
			</tr>
			<tr>
				<td><?php _e('Content', 'pondol_formmail');?></td>
				<td><textarea id="message_<?php echo $id;?>" name="message" class="required"></textarea></td>
			</tr>
			<tr>
				<td><?php _e('File', 'pondol_formmail');?></td>
				<td><input name="attached[]" type="file" size="25"></td>
			</tr>
		</table>
		<button type="submit" id="btn_sendmail_<?php echo $id;?>"><?php _e('Send Mail', 'pondol_formmail');?></button>
	</form>
</div>