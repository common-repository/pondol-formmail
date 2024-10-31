<?php
$formmail_admin_menu	= array("pondoleplugin_formmail_manage"=>"Manage", "pondolplugin_formmail_show_log"=>"Mail Log", "pondolplugin_formmail_show_item"=>"View", "pondolplugin_formmail_edit"=>"info");
?>
<p>
<!-- <ul class="subsubsub"> -->
<ul class="pondol_visitor_navy">
	<?php foreach($formmail_admin_menu as $key=>$val){
		$selected	= $key == $_GET["page"] ? ' class="current"':'';
		echo '<li><a href="?page='.$key.'&itemid='.$_GET["itemid"].'"'.$selected.'>'.$val.'</a> |</li>';
	}
	?>
</ul>
</p>
