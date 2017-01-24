<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */
class Base_Controller extends MX_Controller
{

    public function __construct()
    {

        parent::__construct();

    }


    function validateHeaderRequest($validateFields = array()) {
        $headers = apache_request_headers();
        $key = (trim(@$headers['Authentication-Key']));
        

        $myFields = array('1' => $key);
        $myFieldsName = array('1' => "Authentication Key");

        $errMessage = "";

        if (!empty($validateFields)) {
            foreach ($validateFields as $each) {
                if (isset($myFields[$each]) && $myFields[$each] != '') {
                    //continue to validate next field if found.
                } else {
                    $errMessage .= $myFieldsName[$each] . " field is required.\n <br />";
                }
            }
        }

        if ($errMessage == "")
            return TRUE;
        else
            return $errMessage;
    }

    function hashCode($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function encryptPassword($staffId, $password) {
        if (empty($password)) {
            return FALSE;
        }
        //return sha1($password);
        return $this->ion_auth->hash_password_db_send($staffId, $password);
    }


    function checkLogin() {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        $query = $this->db->query("CALL sp_checkUserLoggedIn_new('{$key}')");
        $this->db->freeDBResource($this->db->conn_id);
        
        return $query->row()->status == 1 ? $query->row() : FALSE;
    } 

    function checkSupervisorOrCustomer() {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        $query = $this->db->query("CALL sp_checkUserLoggedIn('{$key}')");
        $this->db->freeDBResource($this->db->conn_id);
        
        return $query->row()->status == 1 ? $query->row() : FALSE;
    } 
}

class Admin_Controller extends Base_Controller
{
    //--------------------------------------------------------------------

    /**
     * Class constructor setup login restriction and load various libraries
     *
     */
    public function __construct()
    {
		 
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect(base_url('admin/login'), 'refresh');
        }
       if(!$this->ion_auth->is_admin()){
            redirect(base_url('admin/login'), 'refresh');
       }

    }
}

class Public_Controller extends Base_Controller
{
    //--------------------------------------------------------------------

    /**
     * Class constructor setup login restriction and load various libraries
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect(base_url('lhc/login'), 'refresh');
        }

    }
}


class Customer_Controller extends Base_Controller
{
    //--------------------------------------------------------------------

    /**
     * Class constructor setup login restriction and load various libraries
     *
     */

    protected $data = array();

    protected $current_user = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->template->set_layout('customer/user/default');
        $this->template->title(' Customer | ');
        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect(base_url('customer/login'));
        }
        else{

            if( $this->session->userdata('current_user') == NULL )
            {
                /*$this->current_user = $this->ion_auth->user()->row();
                $this->current_user->id = (int) $this->current_user->user_id;
                $group = $this->ion_auth->get_users_groups($this->current_user->user_id)->result()[0];
                if($group->id != CUSTOMER){*/
                    $this->session->set_flashdata('message', 'Please Login With Customer Account ');
                    redirect(base_url('customer/login'));//user logged in is not a customer
               /* }
                $this->current_user->group_id = $group->id;
                $this->current_user->group_name = $group->name;
                $this->current_user->group_description = $group->description;
                $this->current_user->password = NULL;
                $this->session->set_userdata('current_user', $this->current_user);*/
            } else {
                $this->current_user = $this->session->userdata('current_user');
                if( empty($this->current_user->lhc_user_id) ){
                    $this->session->set_flashdata('message', 'Please select lp ');
                    redirect(base_url('customer/lp_select'));
                }
            }
        }

    }

    public function do_upload($des=''){
        // var_dump(getcwd());
        if( !empty($des) ){
                $config['upload_path'] = '../assets/images/profile_image';//is_dir($des) ? $des : '../assets/images/profile_image';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 1000;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;

                $this->load->library('upload', $config);
                if ( ! $this->upload->do_upload('userfile'))
                {
                        $err = array('error' => $this->upload->display_errors());
                        $err['status'] = FALSE;
                        return $err;
                        $this->load->view('upload_form', $error);
                }
                else
                {
                        $data = array('success'=>'profile updated successfully',
                                      'upload_data' => $this->upload->data()
                                     );
                        
                        $data['status'] = TRUE;
                        return $data;
                        $this->load->view('upload_success', $data);
                }
        }
    }
}
