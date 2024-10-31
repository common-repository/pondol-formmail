<?php
/*
Plugin Name: Pondol FormMail
Plugin URI: http://www.shop-wiz.com/wp/plugins/formmail
Description: WordPress Form to Mail Plugin
Version: 1.1
Author: Pondol
Author URI: http://www.shop-wiz.com
License: Copyright 2014 Pondol, All Rights Reserved
*/
define('PONDOL_URL', 'http://www.shop-wiz.com');
define('PONDOL_EMAIL', 'wangta69@naver.com');
define('PONDOLEPLUGIN_FORMMAIL_VERSION', '1.0');
define('PONDOLEPLUGIN_FORMMAIL_URL', plugin_dir_url( __FILE__ ));
define('PONDOLEPLUGIN_FORMMAIL_PATH', plugin_dir_path( __FILE__ ));
define('PONDOLEPLUGIN_FORMMAIL_SHORTCODE', 'CONTACT_FORM_TO_EMAIL');
require_once 'includes/class.pondol.controller.php';

class Pondole_FormMail_Plugin {
	
    function __construct() {
        $this->init();
    }
    
    public function init() {
    	
		$this->ctrl = new Pondol_FormMail_Controller($this);
          
        add_shortcode( PONDOLEPLUGIN_FORMMAIL_SHORTCODE, array($this->ctrl->view, 'shorttag_handler') );
		add_action( 'init', array($this, 'register_script'));//사용자 메일 정보 받기
        add_action(	'plugins_loaded', array($this, 'pondol_formmail_i18n_init'));
        
        if ( is_admin() )
        {
          add_action( 'admin_menu', array($this->ctrl->admin, 'register_menu') );
          add_action( 'wp_ajax_pondol_formail_save_item', array($this->ctrl->admin, 'save_item'));
		  add_action( 'wp_ajax_pondol_formail_delete_item', array($this->ctrl->admin, 'delete_item'));
		  add_action( 'wp_ajax_pondol_formail_save_info', array($this->ctrl->admin, 'save_mail_info'));
		  add_action( 'wp_ajax_pondol_dynamic_form_create_preveiw', array($this->ctrl->fmail, 'form_create_preveiw'));
		  add_action( 'wp_ajax_pondol_dynamic_form_save', array($this->ctrl->fmail, 'form_save'));
        }
    }

	function register_script(){
		if(isset($_POST["ps_send_mail"])  && $_POST["ps_send_mail"] == "1"){
			echo $this->ctrl->fmail->save_send_mail();
			die();
		}
	}
	
	// i18n
	function pondol_formmail_i18n_init() {
		load_plugin_textdomain('pondol_formmail', false, dirname(plugin_basename(__FILE__)) . '/languages/');
	}
}

$pondoleplugin_formmail = new Pondole_FormMail_Plugin();
