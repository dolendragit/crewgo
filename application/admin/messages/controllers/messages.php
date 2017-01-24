<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Messages extends Admin_Controller {

	 public function __construct()
	{
		parent::__construct();
		 $this->template->set_layout('admin/default');
   		$this->load->model('model_message');

	}
 
   public function index()
   {
	    
	   $this->template->set_layout('admin/default');
	   $data = array();
	 
   }

   public function message_log(){
      $this->model_menu->checkPermission();
      $data['page_url'] = "admin/messages/message_log";
      $data['page_action'] = '';
      $data['customers'] = $this->model_message->get_all_customers();

      $this->template->build('message_log', $data);
   }

   public function ajax_message_log(){
      $start = $this->input->post('start', true);
      $length = $this->input->post('length', true);
      $draw = $this->input->post('draw', true);
      $messages = $this->model_message->get_all_messages($length, $start);
      $data = array();
      $no = $start;
      foreach ($messages as $msg) {
         $no++;
         $row = array();
         $row[] = $no;
         $row[] = ($msg->lhc_name) ? $msg->lhc_name : 'N/A';
         $row[] = $msg->entered_date;
         $row[] = $msg->job_id;
         $row[] = $this->get_staffs($msg->msg_id);
         $row[] = '<i class="fa fa-arrow-left">';
         $row[] = $msg->customer;
         $row[] = '<select>
                  <option>confirm</option>
                  <option>started</option>
                  <option>ongoing</option>
                  <option>completed</option>
                  </select>';
         $row[] ='<p> '.$msg->message.'</p>';

         $data[] = $row;

         $reply = $this->model_message->get_message_replies($msg->msg_id);
         if($reply){
            foreach ($reply as $reply) {
               $reply_row = array();
               $reply_row[] = '';
               $reply_row[] = '';
               $reply_row[] = $reply->entered_date;
               $reply_row[] = $reply->job_id;
               $reply_row[] = $reply->staff;
               $reply_row[] = '<i class="fa fa-arrow-right">';
               $reply_row[] = $reply->customer;
               $reply_row[] = '<select>
               <option>confirm</option>
               <option>started</option>
               <option>ongoing</option>
               <option>completed</option>
            </select>';
            $reply_row[] ='<p>'.$reply->message.'</p>';
            $data[] = $reply_row;
         }
      }

      }

      $output = array(
               "draw" => $draw,
               "recordsTotal" => $this->model_message->get_total_messages(),
               "recordsFiltered" => count( $this->model_message->get_all_messages() ),
               "data" => $data,
            );
      //output to json format
      echo json_encode($output); exit;
   }


   public function get_staffs($msg_id=0){
      $staffs = $this->model_message->get_receiver_staffs($msg_id);
      $staff_str = '';
      if($staffs){ 
         foreach ($staffs as $key => $staff) {
            $staff_str .=$staff->staff_name.', '; 
         }
         return trim($staff_str, ', ');
      } else {
      return 'N/A';
   }
}

}