<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Master_setup extends Admin_Controller {
 
   public function __construct()
	{
		parent::__construct();
		 $this->template->set_layout('admin/default');
   		$this->load->model('model_master_setup');

	}
	
   public function general_view($page_action="",$id="")
   {
	   
	  $this->model_menu->checkPermission();
	   $data = array();
	   $data['page_url'] = "admin/master_setup/general_view";
	    $data['page_action'] = $page_action;
	   if($page_action == "add" || $page_action == "edit")
	   {
		  
		   $data['page_title'] = "General Form";
		   $this->session->set_flashdata('errorMessage','My error message');
	   }
	   else if($page_action == "remove" && $id!=""){
		   
	   }
	   else{
		   $this->session->set_flashdata('successMessage','My success message');
		   
		  $data['page_title'] = "General Form"; 
	   }
	   
	    $this->template->build('general_view',$data);
   }
   
   public function user_type($page_action="",$id="")
   {
	  
	   $user_groups = $this->model_menu->getUserGroup();
	   $data = array();
	   $data['page_url'] = "admin/master_setup/user_type";
	   $data['page_action'] = $page_action;
	   $data['user_groups'] = $user_groups;
	   
		$data['page_title'] = "User Group"; 
	    
	    $this->template->build('user_type',$data);
   }
   
   
   public function user_priv($group_id="")
   {
	   if($group_id=="" && !$_POST){
		   redirect(base_url('admin'));
	   }
	   
	   $user_groups = $this->model_menu->getUserGroup($group_id);
	   $modules = $this->model_menu->getModule();
	  
	   $data = array();
	   $data['group'] = $user_groups;
	   
	   if($modules)
	   {
		   $module_menu = array();
		   $module_menu_privilege = array();
		   foreach($modules as $module)
		   {
			   $get_menus = $this->model_menu->getMenu($module->id);
			   
			   if($get_menus){
				   foreach($get_menus as $menus) 
				   {
				     $module_menu[$module->id][] = $menus;
					 //get mdule menu privillege
					 $get_module_menu_priv = $this->model_menu->getMenuPrivilege($module->id,$menus->id ,$group_id);
					 
					  
					 if($get_module_menu_priv)
					 {
						
					   $module_menu_privilege[$menus->id]['add'] = $get_module_menu_priv[0]->can_add;
					   $module_menu_privilege[$menus->id]['edit'] = $get_module_menu_priv[0]->can_edit;
					   $module_menu_privilege[$menus->id]['remove'] = $get_module_menu_priv[0]->can_remove;
					   $module_menu_privilege[$menus->id]['view'] = $get_module_menu_priv[0]->can_view;
					    
					 }
				   }
			   }
			   
		   }
	   }
	    
		   $data['modules'] = $modules;
		   $data['module_menu'] = $module_menu;
		   $data['module_menu_privilege'] = $module_menu_privilege;
		    if($_POST)
			{
				if($this->input->post('group_id')!=""){
			   $this->model_menu->savePrivilege($data['module_menu']);
			   $this->session->set_flashdata('successMessage','User privilege has been saved successfully!!');
			   redirect(base_url('admin/master_setup/user_priv').'/'.$this->input->post('group_id'));
				}
	        }
		 
	    $this->template->build('user_privilege',$data);
   }

   	public function notification_settings($page_action=''){
   		$this->model_menu->checkPermission();
   		$data['page_url'] = "admin/master_setup/notification_settings";
	    $data['page_action'] = $page_action;

		if($this->input->post('submit')){
			// Update notification informations
			$update = $this->model_master_setup->update_notification();
			if($update){
				$this->session->set_flashdata('successMessage', 'Notification Information Successfully Updated. ');
			} else
				$this->session->set_flashdata('errorMessage', 'Unable to Update Notification Information.');
		}

		$data['notification'] = $this->model_master_setup->get_notification();
		$this->template->build('notification_settings', $data);
	}

	public function general_settings($page_action=''){
   		$this->model_menu->checkPermission();
		$data['page_url'] = "admin/master_setup/general_settings";
	    $data['page_action'] = $page_action;

	    //$this->$this->form_validation->set_rules($this->model_master_setup->validate_setting);
	    $this->form_validation->set_rules('quote_expiry_time', 'quote_expiry_time', 'trim|required|max_length[12]');

	    if($this->form_validation->run() ===true){
			// Update general settings information
			$update = $this->model_master_setup->update_settings();
			if($update){
				$this->session->set_flashdata('successMessage', 'General Settings Updated Successfully. ');
			} else{
				$this->session->set_flashdata('errorMessage', 'Unable to Update General Settings Information.');
			}
			redirect( base_url($data['page_url']),'refresh'); 
		}
		$data['all_countries'] = $this->model_master_setup->get_all_countries();
		$data['settings'] = $this->model_master_setup->get_admin_settings();
		$this->template->build('general_settings', $data);
	}

	public function restrict_industry(){
		$data['all_industries'] = $this->model_master_setup->get_all_industries();	
		$this->model_menu->checkPermission();
		$data['page_url'] = "admin/master_setup/restrict_industry";
	    $data['page_action'] = $page_action;

	   
		$data['all_industries'] = $this->model_master_setup->get_all_industries();
		$data['selected_industries'] = $this->model_master_setup->get_selected_industries();
		$this->template->build('restrict_inductry_lhc', $data);	
	}
	public function add_restrict_industry(){
		if(!$this->input->is_ajax_request()){
			echo 'No direct script allowed'; exit; 
		}
		if($this->session->userdata( 'email' ) && ($this->session->userdata( 'email' )!='md@crewgo.com')){ 
			$response['status'] = 'error';
			$response['message'] = 'You are not authorized to restrict industry.';
			print_r(json_encode($response));exit; 
		}
		$industry = $this->input->post('restrict_industry', true);
		if(!$industry || $industry==''){
			
			$response['status'] = 'error';
			$response['message'] = 'Please Select Industry First.';
			print_r(json_encode($response));exit; 
		}		
		$add = $this->model_master_setup->add_restrict_industries();
		if($add){
			
			$response['status'] = 'success';
			$response['message'] = 'Added to Restrict Industry List Successfully.';
			print_r(json_encode($response));exit; 


		} else{
			
			$response['status'] = 'error';
			$response['message'] = 'Unable to add to Restrict Industry list Information.';
			print_r(json_encode($response));exit; 

		}
		
	}

	public function remove_restrict_industry(){
		if(!$this->input->is_ajax_request()){
			echo 'No direct script allowed'; exit; 
		}
		if($this->session->userdata( 'email' ) && ($this->session->userdata( 'email' )!='md@crewgo.com')){ 
			$response['status'] = 'error';
			$response['message'] = 'You are not authorized to remove restricted industry.';
			print_r(json_encode($response));exit; 
		}

		$industry = $this->input->post('id', true);
		if(!$industry || $industry=='' || !is_numeric($industry)){
			
			$response['status'] = 'error';
			$response['message'] = 'Invalid Industry Selection.';
			print_r(json_encode($response));exit; 
		}		
		$add = $this->model_master_setup->remove_restrict_industry();
		if($add){
			
			$response['status'] = 'success';
			$response['message'] = 'Removed  From Restrict Industry List Successfully.';
			print_r(json_encode($response));exit; 
		} else{
			$response['status'] = 'error';
			$response['message'] = 'Unable to Remove From Restrict Industry list Information.';
			print_r(json_encode($response));exit; 

		}
		
	}

	public function available_countries(){
		$data['all_countries'] = $this->model_master_setup->get_all_countries();
		$this->model_menu->checkPermission();
		$data['page_url'] = "admin/master_setup/available_countries";
	    $data['page_action'] = $page_action;
	    $this->form_validation->set_rules('available_countries[]', 'Select Available Countries', 'trim|required');
	    if($this->form_validation->run() ===true){
			$update = $this->model_master_setup->update_available_countries();
			if($update){
				$this->session->set_flashdata('successMessage', 'Country Information Updated Successfully. ');
			} else {
				$this->session->set_flashdata('errorMessage', 'Unable to Update Country Information.');
			}
			redirect( base_url($data['page_url']),'refresh'); 
		}
		$data['all_countries'] = $this->model_master_setup->get_all_countries();
		$data['settings'] = $this->model_master_setup->get_admin_settings();
		$this->template->build('available_country', $data);	
	}

	public function check_password(){
		if(!$this->input->is_ajax_request()){
			echo 'No direct script allowed'; exit; 
		}
		$password = $this->input->post('password');
		$id = $this->session->userdata('user_id');
		if( !$id || !is_numeric($id)){
			$response['status'] = 'error';
			$response['message'] = 'Invalid user. Please Login.';
			print_r(json_encode($response));exit; 
		}
		if(!$password || $password==''){
			$response['status'] = 'error';
			$response['message'] = 'Password Reauired.';
			print_r(json_encode($response));exit; 
		}
		$this->load->model('ion_auth_model');
		$check = $this->ion_auth_model->hash_password_db($id, $password);
		if($check){
			$response['status'] = 'success';
			$response['message'] = 'Success';
		} else {
			$response['status'] = 'error';
			$response['message'] = 'Invalid Password.';
		}
		print_r(json_encode($response));exit; 
	}
}