<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Skills extends Admin_Controller {
 
 	public function __construct(){
		parent::__construct();
		 $this->template->set_layout('admin/default');
	}

	public function index($page_action="",$id=""){

		$this->model_menu->checkPermission();
		$data = array();
		$data['page_url'] = "admin/home/skills/index/";
		$data['page_action'] = $page_action;
		if($page_action == "add" || $page_action == "edit")
		{
		  
		   $data['page_title'] = "Skills";
		   $this->session->set_flashdata('errorMessage','My error message');
		   
		  
		}
		else if($page_action == "remove" && $id!=""){
		   
		}
		else{
		   $this->session->set_flashdata('successMessage','My success message');
		   
		  $data['page_title'] = "skills"; 
		}

		$this->template->build('skills',$data);
	}

 
}