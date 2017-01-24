<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Mickey extends MX_Controller {
 
   public function index(  )
   {
      $this->load->view('mouse');
   }
   public function mypage(  )
   {
      echo "My page";
   }
   
}
 
/* End of file hmvc.php */
/* Location: ./application/widgets/hmvc/controllers/hmvc.php */
