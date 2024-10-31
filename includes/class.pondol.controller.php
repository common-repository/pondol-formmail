<?php 
require_once 'class.pondol.formmail.admin.php';
require_once 'class.pondol.formmail.php';
require_once 'class.pondol.formmail.model.php';
require_once 'class.pondol.formmail.view.php';
require_once 'func.formmail.php';

class Pondol_FormMail_Controller {
	public $view, $model, $fmail, $admin;

	function __construct() {
		$this->fmail	= new Pondol_FormMail($this);	
		$this->model	= new Pondol_FormMail_Model($this);	
		$this->view		= new Pondol_FormMail_View($this);
		$this->admin	= new Pondol_FormMail_Admin($this);
		$this->func		= new Pondol_Formmail_Func();
	}
}