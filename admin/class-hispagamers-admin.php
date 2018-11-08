<?php 

class Hispagamers_admin{
	private $hispagamers;

	private $version;

	public function __construct($hispagamers, $version){
		$this->hispagamers = $Hispagamers;
		$this->version = $version;

	}


	public function enqueue_styles(){
		
	}

	public function admin_menu(){
		global $submenu;

		$capability = 'manage_options';
		$slug = 'hispagamers-bot';
		$hook
	}
}