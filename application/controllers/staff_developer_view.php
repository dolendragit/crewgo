<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Webservice_model $webservice_model
 */
class Staff_developer_view extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT == 'production' and ($this->input->get('user_access') != 'ebpearls.ta@gmail.com' or $this->input->get('user_pass') != 'ebpearls123456')) {
            exit('Access denied');
        }
    }

    public function index()
    {
        $links = array(
            array('link' => 'heading', 'title' => 'Profile'),
            array('link' => base_url('staff/staff_webservice/login'), 'title' => 'Login'),
            array('link' => base_url('staff/staff_webservice/logout'), 'title' => 'Logout'),
            array('link' => base_url('staff/staff_webservice/register'), 'title' => 'Register'),
            array('link' => base_url('staff/staff_webservice/change_password'), 'title' => 'Change Password'),
            array('link' => base_url('staff/staff_webservice/forgot_password'), 'title' => 'Forgot password'),
            array('link' => base_url('staff/staff_webservice/get_profile'), 'title' => 'Get Profile Details'),
            array('link' => base_url('staff/staff_webservice/get_skills'), 'title' => 'Get Skills'),
            array('link' => base_url('staff/staff_webservice/get_suburbs'), 'title' => 'Get Suburbs'),

            array('link' => 'heading', 'title' => 'Job'),
            array('link' => base_url('staff/staff_webservice/get_job_alerts'), 'title' => 'Get Job Alerts'),
            array('link' => base_url('staff/staff_webservice/get_job_alert_detail'), 'title' => 'Get Job Alert Detail'),
            array('link' => base_url('staff/staff_webservice/respond_job_alert'), 'title' => 'Respond to Job Alert'),
            array('link' => base_url('staff/staff_webservice/get_accepted_jobs'), 'title' => 'Get Accepted Job List'),
            array('link' => base_url('staff/staff_webservice/get_job_detail'), 'title' => 'Get Accepted Job Detail'),
            array('link' => base_url('staff/staff_webservice/get_ongoing_job'), 'title' => 'Get Ongoing Job'),
            array('link' => base_url('staff/staff_webservice/set_job_site'), 'title' => 'Set Job Site'),
            array('link' => base_url('staff/staff_webservice/staff_job_delete'), 'title' => 'Delete Staff Job'),
            array('link' => base_url('staff/staff_webservice/job_check_in'), 'title' => 'Job Check In'),
            array('link' => base_url('staff/staff_webservice/checked_in_job_detail'), 'title' => 'CheckedIn Job Detail'),
            array('link' => base_url('staff/staff_webservice/complete_job'), 'title' => 'Complete Job'),
            array('link' => base_url('staff/staff_webservice/post_staff_location'), 'title' => 'Post Staff Location'),
            array('link' => base_url('staff/staff_webservice/post_start_job_break'), 'title' => 'Start Break'),
            array('link' => base_url('staff/staff_webservice/post_stop_job_break'), 'title' => 'End Break'),
            array('link' => base_url('staff/staff_webservice/get_job_induction'), 'title' => 'Job Induction'),
            array('link' => base_url('staff/staff_webservice/get_job_calendar'), 'title' => 'Get Job Calendar'),
            array('link' => base_url('staff/staff_webservice/get_staff_unavailability'), 'title' => 'Get Staff unavailability'),
            array('link' => base_url('staff/staff_webservice/post_staff_unavailability'), 'title' => 'Post Staff unavailability'),

            array('link' => 'heading', 'title' => 'Timesheet'),
            array('link' => base_url('staff/staff_webservice/timesheet'), 'title' => 'Timesheet'),

            array('link' => 'heading', 'title' => 'Message'),
            array('link' => base_url('staff/staff_webservice/get_sent_messages'), 'title' => 'Send Message List'),
            array('link' => base_url('staff/staff_webservice/get_received_messages'), 'title' => 'Received Message List'),
            array('link' => base_url('staff/staff_webservice/message_details'), 'title' => 'Message Details'),
            array('link' => base_url('staff/staff_webservice/view_reply'), 'title' => 'View Reply Details'),
            array('link' => base_url('staff/staff_webservice/reply_message'), 'title' => 'Send Reply'),

        );
        $this->load->view('developer_view', array('links' => $links));
    }
}