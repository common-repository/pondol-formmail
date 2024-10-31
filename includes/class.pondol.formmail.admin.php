<?php
class Pondol_FormMail_Admin {

	private $ctrl;
	
	function __construct($controller) {
		
		$this->ctrl = $controller;
	}
	
	
	function register_menu()
	{
		$menu = add_menu_page(
				__('PondolePlugin Formmail', 'pondoleplugin_formmail'),
				__('PondolePlugin Formmail', 'pondoleplugin_formmail'),
				'manage_options',
				'pondoleplugin_formmail_overview',
				array($this, 'print_overview'),
				PONDOLEPLUGIN_FORMMAIL_URL . 'assets/images/pondol-16.png' );
		
		$menu = add_submenu_page(
				'pondoleplugin_formmail_overview',
				__('Overview', 'pondoleplugin_formmail'),
				__('Overview', 'pondoleplugin_formmail'),
				'manage_options',
				'pondoleplugin_formmail_overview',
				array($this, 'print_overview' ));
		
		$menu = add_submenu_page(
				'pondoleplugin_formmail_overview',
				__('Manage Formmail', 'pondoleplugin_formmail'),
				__('Manage Formmail', 'pondoleplugin_formmail'),
				'manage_options',
				'pondoleplugin_formmail_manage',
				array($this, 'print_manage' ));
	
		$menu = add_submenu_page(
				null,
				__('View Mail Form', 'pondoleplugin_formmail'),
				__('View Mail Form', 'pondoleplugin_formmail'),	
				'manage_options',	
				'pondolplugin_formmail_show_item',	
				array($this, 'show_item' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		
		$menu = add_submenu_page(
				null,
				__('View Mail Form', 'pondoleplugin_formmail'),
				__('View Mail Form', 'pondoleplugin_formmail'),	
				'manage_options',	
				'pondolplugin_formmail_show_log',	
				array($this, 'show_log' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
		
		$menu = add_submenu_page(
				null,
				__('View Mail Form', 'pondoleplugin_formmail'),
				__('View Mail Form', 'pondoleplugin_formmail'),	
				'manage_options',	
				'pondolplugin_formmail_edit',	
				array($this, 'editInfo' ));
		add_action( 'admin_print_styles-' . $menu, array($this, 'enqueue_admin_script') );
		
	}

	function save_item(){
		$result	= $this->ctrl->model->save_item();
		echo json_encode($result);
		die();
	}


	function delete_item(){
		$result	= $this->ctrl->model->delete_item();
		echo json_encode($result);
		die();
	}
	
	function save_mail_info(){
		global $wpdb;
		
		$form_skin							= esc_html($_POST["form_skin"]);
		$to_email							= esc_html($_POST["to_email"]);
		$to_name							= esc_html($_POST["to_name"]);
		$return_page						= esc_url($_POST["return_page"]);
		$id									= (int)($_POST["mainInfo_itemId"]);
		$op["pondol_formmail_copyright"]	= $_POST["pondol_formmail_copyright"] ? "true":"false";
		$op_field	= serialize($op);

		$ret = $wpdb->query( $wpdb->prepare(
				"
				UPDATE 
					".$this->ctrl->model->get_table_name("forms")." 
				set 
					form_skin = %s,
					to_email	= %s,
					to_name		= %s,
					return_page	= %s,
					op_field	= %s
				where 
					id	= %d
				",
				$form_skin,
				$to_email,
				$to_name,
				$return_page,
				$op_field,
				$id
				
		) );
		
		if ($ret === false)
		{
			$return =  array(
					"success" => false,
					"message" => "Cannot update the mailform to database"
			);
		}else{
			$return = array(
					"success" => true,
					"message" => "update sucess "
			);
		}
		
		echo json_encode($return);
		die();
	}
	
	
	//overview 
	function print_overview() {
		global $wpdb;
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-overview.php';
	}

	//manage 
	function print_manage(){
		global $wpdb;
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-manage.php';
	}
	
	function editInfo(){
		#get current info
		global $wpdb;
		$id = (int) $_GET["itemid"];
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$this->ctrl->model->get_table_name("forms")." WHERE id = %d", $id));
		
		//print_r($row);
		$data_info = $this->ctrl->func->get_formail_config($row);
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-top-navy.php';
		include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-mail-info.php';
	}
	
	function show_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-top-navy.php';
		echo '<div class="wrap">';
		//echo $this->ctrl->fmail->generate_short_body( $_REQUEST['itemid'], false);
		echo $this->ctrl->fmail->pre_view( $_REQUEST['itemid'], false);
		echo '</div>';

	}
	
	
	function show_log()
	{
		
		global $wpdb;
		if ( !isset( $_REQUEST['itemid'] ) )
			return;
		
		$id	= $_REQUEST['itemid'];
		
		$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
		$limit = 10; // number of rows in page
		$offset = ( $pagenum - 1 ) * $limit;

		$total = $wpdb->get_var( $wpdb->prepare(
							"SELECT COUNT(`id`) FROM ".$this->ctrl->model->get_table_name("messages") ." WHERE formid=%s"
							, $id ));
		$num_of_pages = ceil( $total / $limit );

		$page_links = paginate_links( array(
	    'base' => add_query_arg( 'pagenum', '%#%' ),
	    'format' => '',
	    'prev_text' => __( '&laquo;', 'text-domain' ),
	    'next_text' => __( '&raquo;', 'text-domain' ),
	    'total' => $num_of_pages,
	    'current' => $pagenum
		) );

		$message_rows = $wpdb->get_results( $wpdb->prepare(
						"SELECT * FROM ".$this->ctrl->model->get_table_name("messages")." 
						WHERE formid=%d order by time desc limit %d, 10", $id, $offset ));
		
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-top-navy.php';
		@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'pages/admin-mail-log.php';

	}
	
}