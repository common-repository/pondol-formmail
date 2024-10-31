<?php
class Pondol_FormMail_View {

	private $ctrl;
	
	function __construct($controller) {
		
		$this->ctrl = $controller;
	}
	
	function shorttag_handler($atts) {		
		if ( !isset($atts['id']) )
			return __('Please specify a email id', 'pondolplugin_email');

		$this->ctrl->fmail->generate_short_body( $atts['id'], false);
	}
}