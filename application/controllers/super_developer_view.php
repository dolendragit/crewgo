<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Webservice_model $webservice_model
 */
class Super_developer_view extends MX_Controller {

    public $ws_folder = 'webservice';

    public $ws_path = 'supervisor/webservice/'; // Modify as per needed.

    public function __construct() {
        parent::__construct();
        $this->load->helper('ws_helper');
        if (ENVIRONMENT == 'production' and ( $this->input->get('user_access') != 'ebpearls.ta@gmail.com' or $this->input->get('user_pass') != 'ebpearls123456')) {
                exit('Access denied');
        }
    }

    public function index() {
        $ws_path = $this->ws_path;
        $links = array(
            array('link' => 'heading', 'title' => 'Login'),
            array('link' => base_url($ws_path.'login'), 'title' => 'Login'),
            array('link' => base_url($ws_path.'register'), 'title' => 'Register'),
            array('link' => base_url($ws_path.'change_password'), 'title' => 'Change Password'),
            array('link' => base_url($ws_path.'fb_login'), 'title' => 'Facebook Login'),
            array('link' => base_url($ws_path.'fb_register'), 'title' => 'Facebook Register'),
            array('link' => base_url($ws_path.'google_login'), 'title' => 'Google Login'),
            array('link' => base_url($ws_path.'google_register'), 'title' => 'Google Register'),
            array('link' => base_url($ws_path.'forgot_password'), 'title' => 'Forgot Password Request'),
            array('link' => base_url($ws_path.'update_device_info'), 'title' => 'Update Device Info'),

            // array('link' => base_url($ws_path.'get_lhc_options'), 'title' => 'Get LHC Users'),
            // array('link' => base_url($ws_path.'set_lhc_user'), 'title' => 'Set LHC User'),
            // array('link' => base_url($ws_path.'unset_lhc_user'), 'title' => 'Unset LHC User'),

            array('link' => 'heading', 'title' => 'Job'),

            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'Job List - List View'),
            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'Job List - Calendar View'),
            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'Job Details for Ongoing Job'),
            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'Edit Job Options'),

            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'View Staff'),
            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'View Staff Documents'),
            array('link' => base_url($ws_path.'job/function_name'), 'title' => 'View Staffs Document Details'),
            array('link' => base_url($ws_path.'job/get_jobs'), 'title' => 'Job Details for Completed Jobs'),
            array('link' => base_url($ws_path.'job/get_calendar_jobs'), 'title' => 'Edit Job options for completed jobs'),           

            array('link' => 'heading', 'title' => 'Profile'),

            array('link' => base_url($ws_path.'profile/detail'), 'title' => 'Profile Detail'),
            array('link' => base_url($ws_path.'profile/edit'), 'title' => 'Edit Profile Detail'),
            array('link' => base_url($ws_path.'profile/edit'), 'title' => 'Calendar'),

            array('link' => 'heading', 'title' => 'Track Staff and Timesheet'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Track a single Staff'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Track a Staff Task Completed'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Track a Staff Timesheet Approved'),
            array('link' => base_url($ws_path.'profile/timesheet'), 'title' => 'Timesheet'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Edit a Timesheet'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Edit a Scheduled Time'),
            array('link' => base_url($ws_path.'get_team_list'), 'title' => 'Get Team by job'),
            array('link' => base_url($ws_path.'addedit_team'), 'title' => 'Create/Update Team'),

            array('link' => base_url($ws_path.'get_members_in_job'), 'title' => 'Get Members in Job'),
            array('link' => base_url($ws_path.'delete_team'), 'title' => 'Delete Team'),
            
            array('link' => base_url($ws_path.'add_team_lead'), 'title' => 'Update team lead'),
            array('link' => base_url($ws_path.'get_team_members'), 'title' => 'Get team members'),

            array('link' => base_url($ws_path.'get_sent_messages'), 'title' => 'Sent Message List'),
            array('link' => base_url($ws_path.'get_received_messages'), 'title' => 'Received Message List'),

            array('link' => base_url($ws_path.'all_teams_and_staffs/6'), 'title' => 'Get all teams and staffs'),
            array('link' => base_url($ws_path.'create_message'), 'title' => 'Send Message'),
            array('link' => base_url($ws_path.'message_details'), 'title' => 'Message Details'),
            array('link' => base_url($ws_path.'view_reply'), 'title' => 'View Reply'),
            array('link' => base_url($ws_path.'delete_message/10'), 'title' => 'Delete Message'),


            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Video Call'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Track a staff time completed time conflict resolved'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Koisk'),
            array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Rate Crew'),

            // array('link' => base_url($ws_path.'profile/function_name'), 'title' => 'Timesheet'),
            // array('link' => 'heading', 'title' => 'Payment'),            
            // array('link' => base_url($ws_path.'profile/add_credit_card'), 'title' => 'Add Credit Card'),
            // array('link' => base_url($ws_path.'profile/get_credit_card'), 'title' => 'Get Credit Card'),
            
            
               
        );
        $this->load->view('super_developer_view', array('links' => $links));
    }

    function add_job() {
        $this->load->view($this->ws_folder.'/job/add_job', array(
            'title' => 'Add Job',
            'raw_data' => job_schema(),
            'url' => base_url($this->ws_path.'job/add_job')
        ));
    }

    function get_price() {
        $this->load->view($this->ws_folder.'/job/get_price', array(
            'title' => 'Get Total',
            'raw_data' => get_skills_schema(),
            'url' => base_url($this->ws_path.'job/get_price')
        ));
    }

    function edit(){
        $this->load->view('edit');
    }

}
