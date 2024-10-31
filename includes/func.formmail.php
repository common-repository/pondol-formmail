<?php
class Pondol_Formmail_Func {

	function get_formail_config($source){
		//print_r($source);
		foreach($source as $key=>$val){
			if($val) $arg[$key]	= $val;
		}
		
		$op_field	= unserialize($source->op_field);
		foreach($op_field as $key=>$val){
			if($val) $arg[$key]	= $val;
		}

		
		$args = array(
			'form_skin'					=> 'default',
			'to_email'					=> '',
			'to_name'					=> '',
			'return_page'				=> '/',
			'pondol_formmail_copyright'	=> 'true'
		); 
		
		//print_r($args);
		$rtn = array_replace($args, $arg);
		return $rtn;
	}
	
	
	function copyright($flag){
		if($flag == "true"){
			echo '<a href="http://www.shop-wiz.com/wp/plugins/formmail" target="_blank">* copyright by pondol</a>';
		}
	}
}
