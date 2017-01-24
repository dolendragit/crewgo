<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	class Model_menu extends MY_Model 
	{
		
		public function __construct() 
		{
			parent::__construct();
			$this->table_name = 'tbl_group';
			$this->primary_key = 'id';
        
        }
		
		public function getUserGroup($group_id="")
		{
			$params['select'] = "*";
			if($group_id != "")
			{
				$params['where']['id'] = $group_id;
			}
			return $user_type_values = $this->get($params);
			 
		}
		
		public function getModule($module_id="")
		{
			$this->table_name = 'tbl_con_module';
			$this->primary_key = 'id';
			$params['select'] = "*";
			if($module_id != "")
			{
				$params['where']['id'] = $module_id;
			}
			return $module_data = $this->get($params);
			 
		}
		
		public function getMenu($module_cd= "",$menu_cd="")
		{
			$this->table_name = 'tbl_con_menu';
			$this->primary_key = 'id';
			$params['select'] = "*";
			if($menu_cd != "")
			{
				$params['where']['id'] = $menu_cd;
			}
			if($module_cd != "")
			{
				$params['where']['module_cd'] = $module_cd;
			}
			return $user_type_values = $this->get($params);
			 
		}
		/*
		*Use to get the privillege details
		*/
		public function getMenuPrivilege($module_cd= "",$menu_cd="",$group_id="")
		{
			$this->table_name = 'tbl_menu_privilege';
			$this->primary_key = 'id';
			$params['select'] = "*";
			if($menu_cd != "")
			{
				$params['where']['menu_id'] = $menu_cd;
			}
			if($module_cd != "")
			{
				$params['where']['module_id'] = $module_cd;
			}
			if($group_id != "")
			{
				$params['where']['group_id'] = $group_id;
			}
			return $group_privilege = $this->get($params);
			 
		}
		
		/*
		*This function is used save group privilege
		*/
		
		public function savePrivilege($data)
		{
			  
			 
			foreach($data as $module_id=>$val){
				 
				if(is_array($val)){
					 $group_id = $this->input->post('group_id');
					foreach($val as $menu){
						$privilege_level_add = "menu_".$menu->module_cd."_".$menu->id."_add";
						$privilege_level_edit = "menu_".$menu->module_cd."_".$menu->id."_edit";
						$privilege_level_delete = "menu_".$menu->module_cd."_".$menu->id."_delete";
						$privilege_level_view = "menu_".$menu->module_cd."_".$menu->id."_view";
						
						 $privilege_level_add_val =   $this->input->post($privilege_level_add)==""?0:1; 
						 $privilege_level_edit_val =  $this->input->post($privilege_level_edit)==""?0:1;
						 $privilege_level_delete_val =  $this->input->post($privilege_level_delete)==""?0:1;
						 $privilege_level_view_val =  $this->input->post($privilege_level_view)==""?0:1;
						 
						 //check privilege already inserted or not
						 $check_menu_privilege = $this->getMenuPrivilege($menu->module_cd,$menu->id,$group_id);
						
								 		
						 
						 if($check_menu_privilege){
							 //update
							   $sql = " UPDATE    tbl_menu_privilege set
											
											can_edit = '".$privilege_level_edit_val."',
											can_add  = '".$privilege_level_add_val."',
											can_remove  = '".$privilege_level_delete_val."',
											can_view  = '".$privilege_level_view_val."' where   
											id = '".$check_menu_privilege[0]->id."' ";
							$this->db->query($sql);
							 
						 }else{
							 //insert
							  $sql = " Insert into  tbl_menu_privilege set
											menu_id = '".$menu->id."',
											module_id = '".$menu->module_cd."',
											group_id = '".$group_id."',
											can_edit = '".$privilege_level_edit_val."',
											can_add  = '".$privilege_level_add_val."',
											can_remove  = '".$privilege_level_delete_val."',
											can_view  = '".$privilege_level_view_val."' ";
							$this->db->query($sql);
						 }
						 //if inserted then updated else insert
						 
					}
					 
				}
			}
	 
		}
	/*
	*  This function is use to verify user privilege for specified url
	*/
	public function checkPermission($action="")
	{
		
		$page_url = 'admin/'.$this->uri->segment(1);
		if($this->uri->segment(2)!=""){
			$page_url .= '/'.$this->uri->segment(2);
		}
		 if($page_url=="admin/"){ //assumes it is dashboards
			 return true;
		 }
                 
		$module_menu = array('page_url'=>$page_url,'get_row'=>'yes');
		$get_page_priv = getUserModuleMenu($module_menu);
		 
	 
		  
		 $menu_action = array('add'=>'can_add','edit'=>'can_edit','remove'=>'can_remove','view'=>'can_view');
		if($get_page_priv){
				if($action!=""){
					$page_action = $action;
				}else{
					$identify_action = $this->uri->segment(3)!=""?$this->uri->segment(3):"view";
					$page_action = $identify_action;
				}
				 
				
				if(array_key_exists($page_action,$menu_action))
				{
					
				  if($get_page_priv->$menu_action[$page_action]==1){ //authorised page action
					  return true;
				  }else{
					  return false; //unauthorised page action
				  }
				}
				return true;
				 
				 
		}else{
			
			return  false; //unauthorised page 
			
		}
		
		//assume all page will be start with controller/function
		
	}
		
		
		
		
	}
