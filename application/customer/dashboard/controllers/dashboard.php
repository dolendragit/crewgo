<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends Customer_Controller
{
    public function __construct()
    {
		  
        parent::__construct();
        $this->load->database();
        $this->load->helper('ws_helper');
        $this->data['action_title'] = "Customer Website";

    }

    public function index()
    {
        $this->template->build("test", $this->data );
    }

}
