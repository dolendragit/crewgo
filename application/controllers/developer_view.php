<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Webservice_model $webservice_model
 */
class Developer_view extends MX_Controller
{

    public $ws_folder = 'webservice';

    public $ws_path = 'customer/webservice/'; // Modify as per needed.

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('ws_helper');
        if (ENVIRONMENT == 'production' and ($this->input->get('user_access') != 'ebpearls.ta@gmail.com' or $this->input->get('user_pass') != 'ebpearls123456')) {
            exit('Access denied');
        }
    }

    public function index()
    {
        $ws_path = $this->ws_path;
        $links = array(
            array('link' => 'heading', 'title' => 'Login'),

            array('link' => base_url($ws_path . 'login'), 'title' => 'Login'),
            array('link' => base_url($ws_path . 'register'), 'title' => 'Register'),
            array('link' => base_url($ws_path . 'change_password'), 'title' => 'Change Password'),
            array('link' => base_url($ws_path . 'fb_login'), 'title' => 'Facebook Login'),
            array('link' => base_url($ws_path . 'fb_register'), 'title' => 'Facebook Register'),
            array('link' => base_url($ws_path . 'google_login'), 'title' => 'Google Login'),
            array('link' => base_url($ws_path . 'google_register'), 'title' => 'Google Register'),
            array('link' => base_url($ws_path . 'forgot_password'), 'title' => 'Forgot Password Request'),
            array('link' => base_url($ws_path . 'update_device_info'), 'title' => 'Update Device Info'),
            array('link' => base_url($ws_path . 'get_lhc_options'), 'title' => 'Get LHC Users'),
            array('link' => base_url($ws_path . 'set_lhc_user'), 'title' => 'Set LHC User'),
            array('link' => base_url($ws_path . 'unset_lhc_user'), 'title' => 'Unset LHC User'),


            /*   array('link' => base_url($ws_path.'add_credit_card'), 'title' => 'Add Credit Card'),
               array('link' => base_url($ws_path.'make_payment'), 'title' => 'Make Payment'),
               array('link' => base_url($ws_path.'payment_history'), 'title' => 'Payment History'),
               array('link' => base_url($ws_path.'forgot_password'), 'title' => 'Forgot Password Request'),
               array('link' => base_url($ws_path.'change_password'), 'title' => 'Change Password'),*/

            array('link' => 'heading', 'title' => 'Job'),

            array('link' => base_url($ws_path . 'job/get_industries'), 'title' => 'Get Industry'),
            array('link' => base_url($ws_path . 'job/get_skills'), 'title' => 'Get Skills'),
            array('link' => base_url($ws_path . 'job/get_levels'), 'title' => 'Get Subskill'),

            // array('link' => base_url($ws_path.'add_skills'), 'title' => 'Add Skills'),

            array('link' => base_url($ws_path . 'job/get_meeting_place'), 'title' => 'Get Meeting Place'),
            array('link' => base_url('index.php/developer_view/add_job'), 'title' => 'Add Job'),
            array('link' => base_url($ws_path . 'job/add_job_test'), 'title' => 'Add Dummy Job'),
            /*  array('link' => base_url('index.php/developer_view/get_price'), 'title' => 'Get Total Price'),*/
            //array('link' => base_url($ws_path . 'job/wage'), 'title' => 'Get Wage'),
            //array('link' => base_url($ws_path . 'job/generate_quote'), 'title' => 'Generate Quote'),
            array('link' => base_url($ws_path . 'job/get_jobs'), 'title' => 'Get Jobs'),
            array('link' => base_url($ws_path . 'job/get_calendar_jobs'), 'title' => 'Get Calendar Jobs'),
            array('link' => base_url($ws_path . 'job/get_job_detail'), 'title' => 'Get Job Detail'),

            array('link' => base_url($ws_path . 'job/add_supervisor'), 'title' => 'Add Supervisor'),
            array('link' => base_url($ws_path . 'job/update_supervisor'), 'title' => 'Update Supervisor'),
            array('link' => base_url($ws_path . 'job/get_supervisors'), 'title' => 'Get Supervisors'),
            array('link' => base_url($ws_path . 'job/delete_supervisor'), 'title' => 'Delete Supervisor'),
            array('link' => base_url($ws_path . 'job/get_supervisor_detail'), 'title' => 'Get Supervisor Detail'),
            array('link' => base_url($ws_path . 'job/add_job_supervisor'), 'title' => 'Add Job Supervisor'),
            array('link' => base_url($ws_path . 'job/book_job'), 'title' => 'Book Job'),
            array('link' => base_url($ws_path . 'job/set_job_induction'), 'title' => 'Set Job Induction'),
            array('link' => base_url($ws_path . 'job/get_job_induction'), 'title' => 'Get Job Induction'),
            array('link' => base_url($ws_path . 'job/get_qualifications'), 'title' => 'Get Qualifications'),
            array('link' => base_url('index.php/developer_view/set_job_qualification'), 'title' => 'Set Job Qualifications'),
            array('link' => base_url($ws_path . 'job/get_job_qualification'), 'title' => 'Get Job Qualifications'),
            
            //array('link' => base_url($ws_path.'add_new_qualification'), 'title' => 'Add New Qualifications'),
            array('link' => base_url($ws_path . 'job/get_job_attributes'), 'title' => 'Get Job Attributes'),
            array('link' => base_url($ws_path . 'job/set_job_attributes'), 'title' => 'Set Job Attributes'),
            array('link' => base_url($ws_path . 'job/additional_information'), 'title' => 'Additional Information'),
            array('link' => base_url($ws_path . 'job/repeat_job'), 'title' => 'Repeat Job'),
            array('link' => base_url($ws_path . 'job/get_peak_price'), 'title' => 'Get Peak Price'),
            array('link' => base_url($ws_path . 'job/view_book_screen'), 'title' => 'View Book Screen'),
            array('link' => base_url($ws_path . 'job/delete_job'), 'title' => 'Delete job'),

            array('link' => base_url($ws_path . 'staff/get_job_staffs'), 'title' => 'View Job Staffs'),
            array('link' => base_url($ws_path . 'staff/get_staff_detail'), 'title' => 'View Staff Detail'),
            array('link' => base_url($ws_path . 'staff/track_job_staffs'), 'title' => 'Track Job Staff'),
            array('link' => base_url($ws_path . 'staff/track_single_staff'), 'title' => 'Track Single Job Staff'),


            array('link' => 'heading', 'title' => 'Profile'),

            array('link' => base_url($ws_path . 'profile/detail'), 'title' => 'Profile Detail'),
            array('link' => base_url($ws_path . 'profile/edit'), 'title' => 'Edit Profile Detail'),
            array('link' => base_url($ws_path . 'profile/get_default_break_times'), 'title' => 'Default Break times'),
            array('link' => base_url($ws_path . 'profile/customer_attributes'), 'title' => 'Customer Attributes'),

            array('link' => base_url($ws_path . 'profile/set_attributes'), 'title' => 'Set Customer Attributes'),

            array('link' => 'heading', 'title' => 'Payment'),

            array('link' => base_url($ws_path . 'profile/add_credit_card'), 'title' => 'Add Credit Card'),
            array('link' => base_url($ws_path . 'profile/get_credit_card'), 'title' => 'Get Credit Card'),

            array('link' => 'heading', 'title' => 'Timesheet'),

            array('link' => base_url($ws_path . 'profile/timesheet'), 'title' => 'Timesheet'),
            array('link' => base_url($ws_path . 'profile/edit_timesheet'), 'title' => 'Edit Timesheet'),


        );
        $this->load->view('developer_view', array('links' => $links));
    }

    function add_job()
    {
        $this->load->view($this->ws_folder . '/job/add_job', array(
            'title' => 'Add Job',
            'raw_data' => job_schema(),
            'url' => base_url($this->ws_path . 'job/add_job')
        ));
    }

    function add_job_qualification()
    {
        $this->load->view($this->ws_folder . '/job/add_job_qualification', array(
            'title' => 'Add Job Qualification',
            'json_schema' => get_qualification_schema(),
            'url' => base_url($this->ws_path . 'job/add_job_qualification')
        ));
    }

    function edit()
    {
        $this->load->view('edit');
    }


    /*    public function index() {
        $links = array(
            array('link' => base_url('webservice/customer/login'), 'title' => 'Login'),
            array('link' => base_url('webservice/customer/register'), 'title' => 'Register'),
            array('link' => base_url('webservice/customer/change_password'), 'title' => 'Change Password'),
            array('link' => base_url('webservice/customer/fb_login'), 'title' => 'Facebook Login'),
            array('link' => base_url('webservice/customer/google_login'), 'title' => 'Google Login'),
            array('link' => base_url('webservice/customer/add_credit_card'), 'title' => 'Add Credit Card'),
            array('link' => base_url('webservice/customer/make_payment'), 'title' => 'Make Payment'),
            array('link' => base_url('webservice/customer/payment_history'), 'title' => 'Payment History'),
            array('link' => base_url('webservice/customer/forgot_password'), 'title' => 'Forgot Password Request'),
            array('link' => base_url('webservice/customer/change_password'), 'title' => 'Change Password'),


            array('link' => base_url('webservice/customer/get_skills'), 'title' => 'Skills'),
            array('link' => base_url('webservice/customer/get_levels'), 'title' => 'Levels'),
            array('link' => base_url('webservice/customer/add_skills'), 'title' => 'Add Skills'),
            array('link' => base_url('webservice/customer/get_meeting_place'), 'title' => 'Meeting Place'),
            array('link' => base_url('index.php/developer_view/add_job'), 'title' => 'Add Job'),
            array('link' => base_url('webservice/customer/get_jobs'), 'title' => 'Get Job'),
            array('link' => base_url('webservice/customer/get_job_detail'), 'title' => 'Get Job Detail'),

            array('link' => base_url('webservice/customer/add_supervisor'), 'title' => 'Add Supervisor'),
            array('link' => base_url('webservice/customer/update_supervisor'), 'title' => 'Update Supervisor'),
            array('link' => base_url('webservice/customer/get_supervisors'), 'title' => 'Get Supervisor'), 
            array('link' => base_url('webservice/customer/add_job_supervisor'), 'title' => 'Add Job Supervisor'), 
            array('link' => base_url('webservice/customer/book_job'), 'title' => 'Book Job'), 
            
               
        );
        $this->load->view('developer_view', array('links' => $links));
    }

    function add_job() {
        $this->load->view('customer/job/add_job', array(
            'title' => 'Add Job',
            'raw_data' => $this->_job_schema(),
            'url' => base_url('webservice/customer/add_job')
        ));
    }*/


}
