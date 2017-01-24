<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Customer Supervisor Common Controller 
 */
class CS_Controller extends Web_Service_Controller
{

    public $_data = array();
    public $userGroup = NULL;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model('webservice_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
        if(is_null($this->userGroup)){
            throw new Exception("User Group Cannot Be Null.", 1);
            die();
        }

    }

    public function _remap($method, $params = array()) {
        $method = $method;
        if (method_exists($this, $method)) {
          
            return call_user_func_array(array($this, $method), $params);
        }
        $this->error();
    }

    public function error() {
        $this->load->view('error');
    }

    function register() {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'name', 'label' => 'Name', 'rules' => 'required|trim'),
                        array('field' => 'address', 'label' => 'Address', 'rules' => 'trim|required'),
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                        array('field' => 'phone_number', 'label' => 'Mobile Number', 'rules' => 'trim|required'),
                    )
            );
            if ($this->form_validation->run() == FALSE) {

                return $this->response('0000', array('msg' => validation_errors()));
            }

            $return = array();

            $name = trim($this->input->post('name'));
            $address = $this->input->post('address');
            $email = trim($this->input->post('email'));
            $username = $email;
            $password = $this->input->post('password');
            $phone_number = trim($this->input->post('phone_number'));
            
            $activation_code = sha1(md5(microtime()));

            $additional_data = array(
                'name' => $name,
                'full_address' => $address,
                'phone_number' => $phone_number,
                'activation_code' => $activation_code,
                'active' => 0,
                'created_on' => date('Y-m-d H:i:s')
                
            );
            $query = $this->db->query("CALL sp_checkUserByGroup(?,?)", array($email,$this->userGroup));
            $this->db->freeDBResource();
            $result = $query->row();
            
            if (!$result OR $result->status == 0) {
                return $this->response('0004');
            }
            if ($user_id = $this->ion_auth->register($activation_code, $password, $email, $additional_data, array($this->userGroup))) {

                $this->send_email($user_id, $email, $activation_code, $name);
                $this->webservice_model->updateWhere(array('id' => $user_id), array('active' => 0), 'tbl_user');
                $code = '0001';
                $return = array('user_id' => $user_id);
            } 
            else {
                $code = '0000';
                $return = array('msg' => $this->ion_auth->errors());
            }
    

            return $this->response($code, $return);
        } else {
            $this->load->view('register', array('title' => 'Registration'));
        }
    }


    function login() {
        if ($this->input->post()) {
            $return = array();
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                    array(
                        array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required'),
                        array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required'),
                        array('field' => 'device_id', 'label' => 'Device Id', 'rules' => 'trim|required'),
                        array('field' => 'device_type', 'label' => 'Device Type', 'rules' => 'required|trim'),
                    )
            );
            $this->form_validation->set_error_delimiters('', '');
            $this->ion_auth->set_error_delimiters('', '');
            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array('msg' => validation_errors()));
            }

            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $encrypted_password = $this->ion_auth->hash_password($password);

            $deviceId = $this->input->post('device_id');
            $deviceType = $this->input->post('device_type');
            $hashCode = $this->webservice_model->hashCode();

            if ($this->ion_auth->login($email, $password, FALSE, $this->userGroup) == TRUE) {
                $query = $this->db->query("CALL sp_tokenLogin(?,?,?,?,?,?)", array($email, $encrypted_password, $deviceId, $hashCode, $deviceType,$this->userGroup));
                if ($query->num_rows() == 0) {
                    return $this->response('0000');
                }
                $result = $query->row();
                $this->db->freeDBResource();
                if ($result->status == 1) {
                    $this->_lhc_options($result);
                } elseif ($result->status == 0) {
                    return $this->response('0012');
                } else {
                    return $this->response('0012');
                }
            } else {
                return $this->response('0000', array('msg' => $this->ion_auth->errors()));
            }
        } else {
            $this->load->view('login', array('title' => 'Login'));
        }
    }

 

}
