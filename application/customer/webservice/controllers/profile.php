<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Web_Service_Controller
{
    const MASTER_CARD = 1, VISA_CARD = 2;
    public $_data = array();
    public $_user_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('ws_helper');
        $this->load->model('webservice_model');
        $this->load->model('customer_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->ion_auth->set_error_delimiters('', '');
    }

    public function _remap($method, $params = array())
    {
        $method = $method;
        if (method_exists($this, $method)) {
            if ($this->input->post()) {
                if (!($user = $this->is_customer())) {
                    return $this->response('0006');
                }
                $this->_user_id = $user->id;
            }
            return call_user_func_array(array($this, $method), $params);
        }
        return $this->response('0000');
    }

    public function index()
    {
        redirect('developer_view');
    }

    public function detail()
    {
        if ($this->input->post()) {

            $user_id = $this->_user_id;

            $query = $this->db->query("CALL sp_getCustomerAttributes('{$user_id}')");
            $this->db->freeDBResource();

            $attributes = $query->result();

            $details = $this->customer_model->get_customer_profile($user_id);
            $default_break_time = isset($res->default_break_time) ?: 0;
            $default_break_time = get_break_time($default_break_time);
            $details->default_break_time = $default_break_time;
            //$attributes = $this->customer_model->get_customer_attributes($user_id);
            $response = array();
            $details->profile_image = get_customer_profile_image($details->profile_image);

            $response['details'] = $details;
            $response['attributes'] = $attributes;
            return $this->response('0001', $response);
        } else {
            $this->load->view('profile/detail', array('title' => 'Profile Detail'));
        }

    }

    public function edit()
    {
        if ($this->input->post()) {

            $user_id = $this->_user_id;
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email|isUnique[tbl_user.email,id.' . $user_id . ']');
            $this->form_validation->set_rules('phone', 'Phone', 'required|trim|xss_clean');
            $this->form_validation->set_rules('about_me', 'About Me', 'required|trim|xss_clean');
            $this->form_validation->set_rules('default_break_time', 'Default Break Time', 'required|trim|xss_clean');
            $this->form_validation->set_rules('profile_image', 'Profile Image', 'xss_clean');

            if ($this->form_validation->run($this) === FALSE) {
                return $this->response('0000', array("msg" => validation_errors()));
            }

            $name = $this->input->post('name');
            $address = $this->input->post('address');
            $email = $this->input->post('email');
            $phone_number = $this->input->post('phone');
            $about_me = $this->input->post('about_me');
            $default_break_time = $this->input->post('default_break_time');
            $profile_image = $this->input->post('profile_image');

            $config['upload_path'] = './assets/profile_image/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2000;
            $config['max_width'] = 5000;
            $config['max_height'] = 5000;
            if (!empty($profile_image)) {
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('profile_image')) {
                    return $this->response('0000', array("msg" => $this->upload->display_errors()));
                } else {
                    $data = $this->upload->data();
                    $profile_image = $data['file_name'];
                }
            }
            $user_data = array(
                'name' => $name,
                'full_address' => $address,
                'email' => $email,
                'phone_number' => $phone_number,
                'description' => $about_me,
            );
            if (!empty($profile_image)) {
                $user_data['profile_image'] = $profile_image;
            }
            $this->ion_auth->update($user_id, $user_data);

            $set = array(
                'customer_user_id' => $user_id,
                'default_break_time' => $default_break_time
            );
            $where = array('customer_user_id' => $user_id);
            $this->webservice_model->updateOrInsert('tbl_customer_add_info', $set, $where);
            $this->response('0001');

        } else {
            $this->load->view('profile/edit', array('title' => 'Edit Profile Detail'));
        }

    }

    function customer_attributes()
    {
        if ($this->input->post()) {

            $user_id = $this->_user_id;
            $query = $this->db->query("CALL sp_getCustomerAttributes('{$user_id}')");
            $this->db->freeDBResource();

            $attributes = $query->result();
            $response = (!empty($attributes)) ? $attributes : $this->customer_model->get_attributes($user_id);
            if(!empty($response)){
                return $this->response('0001', array('attributes' => $response ));
            }
            return $this->response('0030');
        } else {
            $this->load->view('/simple', array('title' => 'Customer Attributes'));
        }
    }

    function get_default_break_times()
    {
        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $items = get_break_times();
            $this->response('0001', array('items' => $items));
        } else {
            $this->load->view('/simple', array('title' => 'Default Breaks'));
        }
    }

    function set_attributes()
    {

        if ($this->input->post()) {
            $user_id = $this->_user_id;
            $this->form_validation->set_rules('attributes', 'Attributes', 'required|trim|xss_clean');

            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            } else {
                $attributes = $this->input->post('attributes');
                $user_id = $user->id;

                $res = $this->customer_model->set_customer_attributes($attributes, $user_id);
                if ($res) {
                    return $this->response('0001');
                } else {
                    return $this->response('0022');
                }
            };
        } else {
            $this->load->view('profile/set_attributes', array('title' => 'Set Attributes'));
        }
    }


    public function add_credit_card()
    {
        if ($this->input->post()) {
            $user_id = $this->_user_id;

            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                array(
                    array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|xss_clean'),
                    array('field' => 'number', 'label' => 'Card Number', 'rules' => 'trim|required|numeric|xss_clean'),
                    array('field' => 'card_type', 'label' => 'Card Type', 'rules' => 'trim|required|numeric|xss_clean|one_of[' . self::MASTER_CARD . ',' . self::VISA_CARD . ']'),
                    array('field' => 'month', 'label' => 'Month', 'rules' => 'trim|required|numeric|min_length[2]|max_length[2]|xss_clean'),
                    array('field' => 'year', 'label' => 'Year', 'rules' => 'trim|required|numeric|min_length[4]|max_length[4]xss_clean'),
                    array('field' => 'cvc', 'label' => 'CVC', 'rules' => 'trim|required|numeric|xss_clean'),
                )
            );
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $number = $this->input->post('number');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $cvc = $this->input->post('cvc');
            $name = $this->input->post('name');
            $card_type = $this->input->post('card_type');
            $myCard = array(
                'number' => $number,
                'exp_month' => $month,
                'exp_year' => $year,
                "cvc" => $cvc
            );
            $this->load->library('stripe');
            $_stripe_id = "";
            $_stripe_fingerprint = "";
            $already_exist = false;
            $cardInfo = $this->customer_model->get_customer_card_info($user_id);
            if (!empty($cardInfo)) {
                $tokenInfo = $this->stripe->create_token($myCard);
                if ($tokenInfo->status == 'error') {
                    return $this->response("0000", array("msg" => $tokenInfo->message));
                } elseif ($tokenInfo->status == 'success') {
                    $_stripe_id = $cardInfo->stripe_id;
                    $_stripe_fingerprint = $tokenInfo->fingerprint;
                }
                if ($cardInfo->stripe_fingerprint == $tokenInfo->fingerprint) {
                    $_stripe_id = $cardInfo->stripe_id;
                    $_stripe_fingerprint = $cardInfo->stripe_fingerprint;
                    $already_exist = true;
                }
            }
            $data = array(
                'name' => $name,
                'customer_user_id' => $user_id,
                'card_number' => $number,
                'card_type' => $card_type,
                'expiry_year' => $year,
                'expiry_month' => $month,
                'cvc' => $cvc,
                'stripe_id' => $_stripe_id,
                'stripe_fingerprint' => $_stripe_fingerprint,
                'entered_date' => date('Y-m-d H:i:s'),
            );
            if ($already_exist) {
                unset($data['stripe_id']);
                unset($data['stripe_fingerprint']);
                return $this->response('0001', array('card' => $data));
            }

            $res = $this->stripe->create_customer($myCard, $user_id);
            if ($res->status == 'error') {
                return $this->response("0000", array("msg" => $res->message));
            } elseif ($res->status == 'success') {
                $data['stripe_id'] = empty($_stripe_id) ? $res->id : $_stripe_id;
                $data['stripe_fingerprint'] = empty($_stripe_fingerprint) ? $res->fingerprint : $_stripe_fingerprint;
                $id = $this->webservice_model->updateOrInsert('tbl_customer_payment_info', $data, array('customer_user_id' => $user_id));
                //$id = $this->webservice_model->insertRow($data,'tbl_customer_payment_info');
                if ($id) {
                    unset($data['stripe_id']);
                    unset($data['stripe_fingerprint']);
                    return $this->response('0001', array('card' => $data));
                } else {
                    return $this->response("0000", array("msg" => "Please try again after some time."));
                }
            }
        } else {
            $this->load->view('add_credit_card', array('title' => 'Add Credit Card'));
        }
    }

    public function get_credit_card()
    {
        if ($this->input->post()) {

            $user_id = $this->_user_id;
            $res = $this->customer_model->get_customer_card_info($user_id);
            if (!empty($res)) {
               /* $this->load->library('stripe');
                $res = $this->stripe->get_customer_info($res->stripe_id);*/
                return $this->response('0001', array('card' => $res));
            }
            return $this->response('0001', array('msg' => 'Card details not found'));

        } else {
            $this->load->view('simple', array('title' => 'Get Credit Card'));
        }
    }


    /**
     * Get Timesheet data for Customer
     */
    public function timesheet()
    {
        $headers = apache_request_headers();
        $key = @trim($headers['Authentication-Key']);
        if ($this->input->post() || !empty($key)) {
            if (!($user = $this->_can_access())) {
                return $this->response('0006');
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules(
                array(
                    array('field' => 'job_id', 'label' => 'Job Id', 'rules' => 'trim|required|integer'),
                )
            );
            if ($this->form_validation->run($this) === false) {
                return $this->response('0000', array("msg" => validation_errors()));
            }
            $this->load->model('webservice/supervisor_model');
            $timeSheets = $this->supervisor_model->get_timesheet($user->id, $this->input->post('job_id'));
            return $this->response('0001', array('timesheet' => $timeSheets));
        } else {
            $this->load->view('profile/get_timesheet', array('title' => 'Get Timesheet'));
        }

    }


}
