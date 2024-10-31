<?php 

class Pondol_FormMail {
	
	private $ctrl;
	
	function __construct($controller) {
		
		$this->ctrl = $controller;
	}
	
	
	
	function pre_view($id, $has_wrapper) {
		global $wpdb;

		$row = $wpdb->get_row("SELECT * FROM ".$this->ctrl->model->get_table_name("forms")." WHERE id = ".$id);
		
		$data_info = $this->ctrl->func->get_formail_config($row);
//print_r($data_info);
		wp_enqueue_script( 'pondolmailform-validate-script', plugins_url('../assets/js/jquery.validate.min.js', __FILE__ ), false, '1.12.0', true);
		
		if($data_info["form_skin"] == "dynamic" && is_admin()){
			$form_field = unserialize($data_info["form_field"]);
			$call_obj	=  $this->js_array($form_field["obj"]);
			
			$form_ele; 
			if(is_array($form_field["ele_position"])) 
				foreach($form_field["ele_position"] as $key =>$val){
					for($i = 0; $i < $val; $i++){
						$ele_type_str				= array_shift($form_field["ele_type"]);
						$obj_ele					= array_shift($form_field["obj"]);
						$arg						= $this->getVal($obj_ele);
						$form_field["form_ele"][$key][$i]	= $ele_type_str;
					}
				}//foreach($form_field["ele_position"] as $key =>$val){
			$attrbutes = array("label", "text", "password", "file", "check", "radio", "select", "textarea", "button");
			@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'skins/'.$data_info["form_skin"].'/form_generator.php';
			
			
		}else @include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'skins/'.$data_info["form_skin"].'/mailform.php';
		
		
		//$this->ctrl->func->copyright($data_info["pondol_formmail_copyright"]);
	}
	
	function generate_short_body($id, $has_wrapper) {
		global $wpdb;

		$row = $wpdb->get_row("SELECT * FROM ".$this->ctrl->model->get_table_name("forms")." WHERE id = ".$id);
		
		if ($row != null)
		{
			$data_info = $this->ctrl->func->get_formail_config($row);
//print_r($data_info);
			wp_enqueue_script( 'pondolmailform-validate-script', plugins_url('../assets/js/jquery.validate.min.js', __FILE__ ), false, '1.12.0', true);
			
			if($data_info["form_skin"] == "dynamic"){
				$this->form_create_preveiw($data_info);
			}else{
				@include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'skins/'.$data_info["form_skin"].'/mailform.php';
			}
			$this->ctrl->func->copyright($data_info["pondol_formmail_copyright"]);
		}
		else
		{
			echo '<p>The specified mail id does not exist.</p>';
		}		
	}
	
	private function js_str($s)
	{
	    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
	}
	private function js_array($array)
	{
	    $temp = array_map(array($this, 'js_str'), $array);
	    return '[' . implode(',', $temp) . ']';
	}
	
	function form_create_preveiw($objArr=null){
		//
		if($objArr){
			$form_field			= unserialize($objArr["form_field"]);
			$obj				=  $form_field["obj"];
			$info["row_cnt"]	= $form_field["row_cnt"];
			$ele_position		= $form_field["ele_position"];
			$ele_type			= $form_field["ele_type"];
			$id				= $objArr["id"];
		}else{
			$obj				= $_POST["obj"];
			$info["row_cnt"]	= $_POST["row_cnt"];
			$ele_position		= $_POST["ele_position"];
			$ele_type			= $_POST["ele_type"];
			$id					= $_POST["id"];
		}
		
		$form_ele; 
		if(is_array($ele_position)) foreach($ele_position as $key =>$val){
			for($i = 0; $i < $val; $i++){
				
				$ele_type_str	= array_shift($ele_type);
				$obj_ele		= array_shift($obj);
				$arg = $this->getVal($obj_ele);
				//echo "ele_type_str:".$obj_ele["input_label"]."</br>";
				switch($ele_type_str){
					case "label":
						$ele = '<span>'.urldecode($arg["input_label"]).'</span>';
					break; 
					case "text":
						if($arg["sel_validation"] != "none"){
							$validation_class = ' class="required"';
							$this->set_validate_script(array("id"=>$arg["element_id"], "rule"=>"", "msg"=>$arg["validate_text_message"]));
							
						}
						$ele = '<input type="text" name="'.$arg["element_id"].'" id="'.$arg["element_id"].'" '.$validation_class.'>';
						
					break; 
					case "password":
						$ele = '<input type="password">';
					break; 
					case "file":
						$ele = '<input type="file" name="attached[]">';
					break; 
					case "check":
						$ele = '<input type="checkbox">';
					break; 
					case "radio":
						$ele = '<input type="radio">';
					break; 
					case "select":
						$ele = '<select>';
						$els .= '<option value=""></option>';
						$els .= '<select>';
					break; 
					case "button":
						$ele = '<button type="'.$arg["input_button_type"].'">'.urldecode($arg["input_button_text"]).'</button>';
					break; 
					case "textarea":
						$ele = '<textarea name="'.$arg["element_id"].'" id="'.$arg["element_id"].'" ></textarea>';
					break; 
						
					
				}
				$info["form_ele"][$key][$i] = $ele;
			}
		}
		
		
		##본문 출력
		$info["validation"]	= $this->get_validate_script();
		
		$info["arg"]	= $form_ele[$key][$i];
		$info["ele_position"]	= $ele_position;

		$data_info["form_skin"] = "dynamic";
		include_once PONDOLEPLUGIN_FORMMAIL_PATH . 'skins/'.$data_info["form_skin"].'/mailform.php';
		if(!$objArr) die();
	}
	
	private $validate_rules		= array();
	private function set_validate_script($arr){
		array_push($this->validate_rules, $arr);
	}
	
	
	private function get_validate_script(){
		//$this->set_validate_script(array("id"=>$arg["element_id"], "rule"=>"", "msg"=>$arg["validate_text_message"]));
		
		if(is_array($this->validate_rules)){
			$script_rules = "rules:{";
			$script_messages = "messages:{";
			foreach($this->validate_rules as $key=>$val){
				$script_rules .= $val["id"].':{ required: true},';
				$script_messages .= $val["id"].':{ required: "'.urldecode($val["msg"]).'"},';
			}
			
			$script_rules .= "}, ";
			$script_messages .= "}, ";
		} 
           
        $script = $script_rules."\n\r".$script_messages;
        
        //echo "validate script:".$script;
    	return $script;
	}

	private function getVal($str){
		
		//echo "str:".$str;
		if($str && gettype($str) == "string"){
			//print_r($str);
			//echo "str:".$str;
			$elements = explode("&", $str); 
			
			foreach($elements as $key => $val){
				$temp = explode("=", $val); 
				//echo "temp:  ";
				//print_r($temp);
				if(!$temp[1]) $temp[1] = "{}";
				$keyValues[$temp[0]]	= $temp[1];
			}
			
		}
		
		return $keyValues;
	}
	
	
	
	function form_save(){
		global $wpdb;
		
		$data["obj"]			= $_POST["obj"];
		$data["row_cnt"]		= (int)$_POST["row_cnt"];
		$data["ele_position"]	= $_POST["ele_position"];
		$data["ele_type"]		= $_POST["ele_type"];
		$id						= (int)$_POST["id"];
		
		$form_field				= serialize($data);

		$table_name = $this->ctrl->model->get_table_name("forms");

		$ret = $wpdb->query( $wpdb->prepare(
				"
				UPDATE ".$table_name."
				SET form_field=%s
				WHERE id=%d
				",
				$form_field,
				$id
		) );
	}

	function save_send_mail(){
		global $wpdb;
		$itemid		= (int)$_POST["itemid"];

		//first check if what skin this use, if this use dynamic skin put all message
		
		$notifyto	= esc_html($_POST["email"]);
		$name		= $_POST["name"] ? esc_html($_POST["name"]) : "anonymous";
		$subject	= esc_html($_POST["subject"]);
		$message	= esc_textarea($_POST["message"]);
		$params		= array("message"=>$message, "post"=>$_POST);

		$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$this->ctrl->model->get_table_name("forms")." WHERE id = %d", $itemid));

		## step 1 save to database
        $rows_affected = $wpdb->insert( 
        								$this->ctrl->model->get_table_name("messages"), 
        								array( 'formid' => $row->id,
                                               'time' => current_time('mysql'),
                                               'ipaddr' => $_SERVER['REMOTE_ADDR'],
                                               'notifyto' => $notifyto,
                                               'subject' => $subject,
                                               'data' =>serialize($params)
                                        ),
                                        array(
	                                        '%s', 
											'%s',
											'%s',
											'%s',
											'%s',
											'%s',
										)
							);
        if (!$rows_affected)
        {
            $rtn["message"]	= 'Error saving data! Please try again. <br /><br />Error debug information: '.mysql_error();
			$rtn["result"]	= false;
			echo json_encode($rtn);
            exit;
        }
		
		
		## FILEDATA
		if (isset($_FILES['attached'])) {
			$fileData = $_FILES['attached'];
		} else {
			$fileData = '';
		}
		$attachmentIds = array();
		if ($fileData !== '') {
			for ($i = 0; $i < count($fileData['name']); $i++) {
				$imageInfo = @getimagesize($fileData['tmp_name'][$i]);

				$key = "public-submission-attachment-{$i}";
	
				$_FILES[$key] = array();
				$_FILES[$key]['name']     = $fileData['name'][$i];
				$_FILES[$key]['tmp_name'] = $fileData['tmp_name'][$i];
				$_FILES[$key]['type']     = $fileData['type'][$i];
				$_FILES[$key]['error']    = $fileData['error'][$i];
				$_FILES[$key]['size']     = $fileData['size'][$i];
	
				$attachmentId = media_handle_upload($key);
		
				if (!is_wp_error($attachmentId)) {// && wp_attachment_is_image($attachmentId) 모든 파일을 첨부
					$attachmentIds[] = $attachmentId;
					//add_post_meta($POST_ID, PONDOL_BBS_IMAGES, wp_get_attachment_url($attachmentId));
				} else {
					wp_delete_attachment($attachmentId);
				}
			}
		}
				
				
		## step 2 send email
		//send to admin
		//if ('html' == $this->get_option('fp_emailformat', CP_CFEMAIL_DEFAULT_email_format)) $content_type = "Content-Type: text/html; charset=utf-8\n"; else
		$attachments = array();
		if(is_array($attachmentIds)) foreach($attachmentIds as $key=>$val){
			//array_push($attachments, wp_get_attachment_url($val)); 
			array_push($attachments, get_attached_file($val)); 
		}
		

		$content_type	= "Content-Type: text/plain; charset=utf-8\n";//
		//$content_type	= "Content-Type: text/html; charset=utf-8\n";//
		$header			= "From: ".$name." <".$email.">\r\n".$content_type."X-Mailer: PHP/". phpversion();

		wp_mail(trim($row->to_email), $subject, $message,$header, $attachments);
		wp_redirect( stripslashes($row->return_page) ); exit;
	}


}