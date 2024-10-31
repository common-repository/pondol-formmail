<?php
class Pondol_FormMail_Model {

	private $ctrl;
	
	function __construct($controller) {
		
		$this->ctrl = $controller;
	}
	
	
	
	function save_item(){
		//1. first check table exists or not then crete table
		//$this->is_table_exists("forms");
		global $wpdb, $user_ID;
		
		//table create and upto date
		if ( !$this->is_table_exists("forms") ) $this->create_table("forms");
		if ( !$this->is_table_exists("messages") ) $this->create_table("messages");
		$this->update_table("forms");


		$name	= esc_html($_POST["name"]);
		$id		= (int)$_POST["id"];
		
		$flag	= "forms";
		$table_name = $this->get_table_name("forms");
		
		if ( ($id > 0) && $this->is_id_exist($id, $flag) )
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					UPDATE ".$table_name."
					SET form_name=%s
					WHERE id=%d
					",
					$name,
					$id
			) );
			
			if (!$ret)
			{
				$result =  array(
						"success" => false,
						"id" => $id, 
						"message" => "Cannot update the mail in database"
					);
			}
		}
		else
		{
			$ret = $wpdb->query( $wpdb->prepare(
					"
					INSERT INTO ".$this->get_table_name("forms")." (form_name)
					VALUES (%s)
					",
					$name
			) );
			
			if (!$ret)
			{
				return array(
						"success" => false,
						"id" => -1,
						"message" => "Cannot insert the mail to database"
				);
			}
			
			$id = $wpdb->insert_id;
		}
		
		return array(
				"success" => true,
				"id" => intval($id),
				"message" => "mail published!"
		);
	}

	
	function delete_item(){
		global $wpdb;
		$id			= (int)$_POST["id"];
		$table_name	= $this->get_table_name("forms");	
		
		$ret = $wpdb->query( $wpdb->prepare(
				"
				DELETE FROM ".$table_name." WHERE id=%s
				",
				$id
		) );
		
		return array(
				"success" => true,
				"id" => intval($id),
				"message" => "mail published!"
		);
	}


	function is_table_exists($flag) {
		global $wpdb;
		$table_name = $this->get_table_name($flag);	
		return ( $wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") == $table_name );
	}
	
	function create_table($flag) {
	
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$table_name = $this->get_table_name($flag);
		$charset = '';
		if ( !empty($wpdb -> charset) )
			$charset = "DEFAULT CHARACTER SET ".$wpdb->charset;
		if ( !empty($wpdb -> collate) )
			$charset .= " COLLATE ".$wpdb->collate;
		
		
		switch($flag){
			case "forms":
				$sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`form_name` varchar(250) NOT NULL DEFAULT '',
				`form_skin` varchar(20) NOT NULL DEFAULT '',
				`from_email` varchar(250) NOT NULL DEFAULT '',
				`to_email` varchar(250) NOT NULL DEFAULT '',
				`to_name` varchar(250) NOT NULL DEFAULT '',
				`return_page` varchar(250) NOT NULL DEFAULT '',
				UNIQUE KEY `id` (`id`)
				) ".$charset;
			break;
			case "messages":
				$sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`formid` int(11) NOT NULL,
				`subject` varchar(250) NOT NULL DEFAULT '',
				`time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				`ipaddr` varchar(32) NOT NULL DEFAULT '',
				`notifyto` varchar(250) NOT NULL DEFAULT '',
				`data` text,
				`posted_data` text,
				UNIQUE KEY `id` (`id`)
				) ".$charset;
			break;
		}
		
		
		dbDelta($sql);
		
		//update 
		
	}

	function update_table($flag){
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$table_name = $this->get_table_name($flag);
		
		switch($flag){
			case "forms":
				//ver 1.0 to 1.1
				$wpdb->query ("alter table `".$table_name."` add column op_field text");
				$wpdb->query ("alter table `".$table_name."` add column form_field text");
				//dbDelta($sql_update[0]);
			break;
			case "messages":

				
				
			break;
		}
		
		echo "sql_update[0]:".$sql_update[0];
		//update
				
	}
	
	function get_table_name($flag){
		global $wpdb;
		switch($flag){
			case "forms":
				$tbname	= $wpdb->prefix . "pondol_formmail_form";
			break;
			case "messages":
				$tbname	= $wpdb->prefix . "pondol_formmail_messages";
			break;
		}
		return $tbname;
	}
	
	
	function is_id_exist($id, $flag)
	{
		global $wpdb;
		$table_name = $this->get_table_name($flag);
	
		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$table_name." WHERE id = %s", $id));
		return ($row != null);
	}
	
}