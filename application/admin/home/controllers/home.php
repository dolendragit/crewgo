<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Home extends Admin_Controller {
    
    
   public function index(  )
   {
	    
	   $this->template->set_layout('admin/default');
	   
	   $data = array();
	   $this->template->build('hmvc_view',$data);
	 
   }
}