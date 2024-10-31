    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("#btn_add").click(function() {
                var data = {
                    action : 'pondol_formail_save_item',
                    name : $("#itemname").val()
                };
                    $.post(ajaxurl, data, function(response) {
                   //console.log(response);
                    location.reload();
                });
            });
            
            $(".btn_update").click(function(){           	
            	var data = {
                    action : 'pondol_formail_save_item',
                    id	: $(this).parents("tr").attr("user-data-id"),
                    name : $(this).parents("tr").find("td > .name").val()
                    
                };
                $.post(ajaxurl, data, function(response) {
                    location.reload();
                });
            });
            
            $(".btn_report").click(function(){           	
            	var data = {
                    action : 'pondol_formail_save_item',
                    id	: $(this).parents("tr").attr("user-data-id"),
                    name : $(this).parents("tr").find("td > .name").val()
                };
                $.post(ajaxurl, data, function(response) {
                    location.reload();
                });
            });
            

            $(".btn_delete").click(function(){
            	var data = {
                    action : 'pondol_formail_delete_item',
                    id	: $(this).parents("tr").attr("user-data-id")
                };
                $.post(ajaxurl, data, function(response) {
                	//console.log(response);
                    location.reload();
                });
            });
        });

    </script>
    
<div class="wrap">
    <h2><?php _e('plugin name', 'pondol_formmail');?></h2>
    <div id="normal-sortables" class="meta-box-sortables">

        <div id="metabox_basic_settings" class="postbox" >
            <h3 class='hndle' style="padding:5px;"><span><?php _e('New Form', 'pondol_formmail');?></span></h3>
            <div class="inside">

                <form name="additem">
                    <?php _e('Item Name', 'pondol_formmail');?>:
                    <br />
                    <input type="text" name="itemname" id="itemname"  value="" />
                    <input type="button" id="btn_add" value="Add" />
                    <br />
                    <br />
                </form>
				<div>  <?php _e('Afeter create a form, you shoud set a form attributes as you click "info" button below', 'pondol_formmail');?></div>
            </div>
        </div>
        <div id="metabox_basic_settings" class="postbox" >
            <h3 class='hndle' style="padding:5px;"><span><?php _e('Forms List', 'pondol_formmail');?> / <?php _e('Items List', 'pondol_formmail');?></span></h3>
            <div class="inside">

                <table cellspacing="10">
                    <tr>
                        <th align="left"><?php _e('ID', 'pondol_formmail');?></th>
                        <th align="left"><?php _e('Form Name', 'pondol_formmail');?></th>
                        <th align="left"><?php _e('Options', 'pondol_formmail');?></th>
                        <th align="left"><?php _e('Shortcode', 'pondol_formmail');?></th>
                        <th align="left"><?php _e('PHP code', 'pondol_formmail');?></th>
                    </tr>
                    <?php
$myrows = $wpdb->get_results( "SELECT * FROM ".$this->ctrl->model->get_table_name("forms") );
foreach ($myrows as $item)
{
                    ?>
                    <tr user-data-id="<?php echo $item -> id; ?>">
                    <td nowrap><?php echo $item -> id; ?></td>
                    <td nowrap><input type="text" class="name" value="<?php echo esc_attr($item -> form_name); ?>" /></td>

                    <td nowrap>&nbsp; &nbsp;
						<button type="button" class="btn_update" /><?php _e('Update Form Name', 'Update');?></button> &nbsp;
						<a href="<?php echo admin_url('admin.php?page=pondolplugin_formmail_show_log').'&itemid=' . $item->id; ?>"><button type="button"/><?php _e('Mail log', 'pondol_formmail');?></button></a> &nbsp;
						<a href="<?php echo admin_url('admin.php?page=pondolplugin_formmail_show_item') . '&itemid=' . $item->id; ?>"><button type="button"/><?php _e('Pre view', 'pondol_formmail');?></button></a> &nbsp;
						<a href="<?php echo admin_url('admin.php?page=pondolplugin_formmail_edit') . '&itemid=' . $item->id; ?>"><button type="button" /><?php _e('Set config', 'pondol_formmail');?></button></a> &nbsp;
	                    <button type="button" class="btn_delete" /><?php _e('Delete', 'Update');?></button>
                    </td>
                    <td nowrap>[<?php echo PONDOLEPLUGIN_FORMMAIL_SHORTCODE; ?>
                    id="<?php echo $item -> id; ?>"]
                    </td>
                    <td nowrap>&lt;?php echo do_shortcode('[<?php echo PONDOLEPLUGIN_FORMMAIL_SHORTCODE; ?> id="<?php echo $item -> id; ?>"]'); ?&gt;
                    </td>
                    </tr>

                    <?php
					}
                    ?>
                </table>

            </div>
        </div>

</div>