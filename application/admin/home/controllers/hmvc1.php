<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Hmvc1 extends MX_Controller {
 
   public function index(  )
   {
	   echo "hmvc1";
	 $this->load->model('test');
     echo $this->test->testme();
     $this->load->view('hmvc_view');
   }
}
 
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
