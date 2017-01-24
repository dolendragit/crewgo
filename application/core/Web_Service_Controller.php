<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Webservice_model $webservice_model
 */
class Web_Service_Controller extends Base_Controller
{

    protected $data = array();

    public $geo_code_url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&key=AIzaSyAvsWUKu3FJ-OyyGUr9Ks7C7JKYsvsl1h0&";
    //public $geo_code_url = "https://maps.googleapis.com/maps/api/geocode/json?sensor=false&components=country:AU&key=AIzaSyAvsWUKu3FJ-OyyGUr9Ks7C7JKYsvsl1h0&";

    public function __construct()
    {
        parent::__construct();

        $this->load->model('webservice_model');
        $this->load->model('common');

    }

    public function response($code, $response = array(), $userType = CUSTOMER)
    {
        if ($userType == CUSTOMER) {
            $returnArr = array('code' => $code, 'msg' => $this->common->code($code));
        } elseif ($userType == STAFF) {
            $returnArr = array('code' => $code, 'msg' => $this->common->staff_response_code($code));
        }
        $returnArr = array_merge($returnArr, $response);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($returnArr));
    }

    public function is_logged_in()
    {
        $key = $this->input->get_post('key');

        if ($user = $this->webservice_model->checkLogin($key)) {
            return $user;
        }

        return FALSE;
    }

      /** Returns details for pagination in api
     * @param int $total
     * @param int $offset 
     * @return next_offset,is_last_offset
     */
    public function _paginate($total = 0, $offset = 0)
    {
        $perpage = 10;
        $pagination = array();
        $pagination['total'] = $total;
        $pagination['next_offset'] = 0;
        $pagination['is_last_offset'] = true;
        $offset = $offset + $perpage;
        if ($total > $offset) {
            $pagination['is_last_offset'] = false;
            $pagination['next_offset'] = $offset;
        }

        return $pagination;
    }

    public function is_customer()
    {
        if ($user = $this->webservice_model->checkSupervisorOrCustomer()) {
            if ($user->group_id == CUSTOMER) {
                return $user;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function _can_access()
    {
        if ($user = $this->webservice_model->checkSupervisorOrCustomer()) {
            if ($user->group_id == CUSTOMER || $user->group_id == SUPERVISOR) {
                return $user;
            }
            return FALSE;
        }
        return FALSE;
    }

    public function is_job_assigned($job_id = "")
    {
        if (!($user = $this->_can_access())) {
            return FALSE;
        }
        $user_id = $user->id;
        $group_id = $user->group_id;
        if ($res = $this->webservice_model->checkJob($user_id, $group_id, $job_id)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * [get_admin_setting description]
     * @param  array $select [fiels names (* if all)]
     * @return [obj]         [returns the obj with selected fields]
     * implementation: $this->get_admin_setting(array('quote_expiry_time', 'quote_cleanup_time'));
     */
    public function get_admin_setting($select = array('*'))
    {
        if (!($user = $this->_can_access()) || !($user = $this->is_logged_in())) {
            return FALSE;
        }
        return $this->webservice_model->get_admin_settings($select);
    }

      /** Returns details for pagination in api
     * @param int $total
     * @param int $offset 
     * @return next_offset,is_last_offset
     */

    public function is_supervisor()
    {
        if ($user = $this->webservice_model->checkSupervisorOrCustomer()) {
            if ($user->group_id == SUPERVISOR) {
                return $user;
            }
            return FALSE;
        }
        return FALSE;
    }


}
