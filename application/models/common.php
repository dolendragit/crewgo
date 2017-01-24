<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function code($code)
    {
        $codes = array(
            '0000' => 'Error occured',
            '0001' => 'Success',
            '0002' => 'Email or password is incorrect',
            '0003' => 'Un Authirized Request',
            '0004' => 'User already registered',
            '0005' => 'User not registered',
            '0006' => 'You are not logged in from this device',
            '0007' => 'User is inactive.',
            '0008' => 'Invalid Access Token',
            '0009' => 'User not registered',
            '0010' => 'A new password has been sent to your nominated email address',
            '0011' => 'Supervisor information provided is invalid',
            '0012' => 'Invalid User',
            '0013' => 'LHC associated successfully',
            '0014' => 'Email doesn\'t exists',
            '0015' => 'Could not associate LHC',
            '0016' => 'No file selected',
            '0017' => 'LHC Users not found',
            '0018' => 'Invalid file type',
            '0019' => 'Card details not found',
            '0020' => 'Job does not exist',
            '0021' => 'Cannot delete. Job has already started.',
            '0022' => 'Provided data is invalid',
            '0023' => 'Invalid Staff Selection',
            '0024' => 'No teams available in this job',
            '0025' => 'You cannot request redeem codes anymore',
            '0026' => '',
            '0027' => '',
            '0028' => '',
            '0029' => '',
            '0030' => 'No Records Found',
            '0031' => '',
            '0032' => 'Validation Errors'


        );
        return $codes[$code];
    }

    function insert_data($table_name, $data)
    {
        if (is_array($data) && !empty($data)) {
            $insrtdb = $this->load->database('default', TRUE);
            $result = $insrtdb->insert($table_name, $data);
            if ($result) {
                return $insrtdb->insert_id();
            }
        }
        return FALSE;
    }

    function insert_batch($table_name, $data)
    {
        if (is_array($data) && !empty($data)) {
            $insrtdb = $this->load->database('default', TRUE);
            $result = $insrtdb->insert_batch($table_name, $data);
        }
        return $result ? TRUE : FALSE;
    }

    /**
     * Method to handle response codes for staff user type
     * @param $code int codeId
     * @return mixed
     */
    function staff_response_code($code)
    {
        $codes = array(
            '0000' => 'Unable to process request. Please try again.',
            '0001' => 'Success',
            '0002' => 'You are not logged in from this device',
            '0003' => 'User already registered with this email. Please login.',
            '0004' => 'Invalid Google Token.',
            '0005' => 'Invalid Facebook Token.',
            '0006' => 'Invalid Email or password.',
            '0007' => 'Please verify your email address.',
            '0008' => 'User not registered',
            '0009' => 'Old password is incorrect.',
            '0010' => 'Error sending email.',
            '0011' => 'We have sent an activation link to your email address. Please activate your account and login',
            '0012' => 'No Job Found.',
            '0013' => 'Invalid Job Alert Response.',
            '0014' => 'Job Alert Status not Pending.',
            '0015' => 'You are already engaged on other job.',
            '0016' => 'Job Positions already filled.',
            '0017' => 'Job time has expired.',
            '0018' => 'Job already Checked In.',
            '0019' => 'Job time has been completed.',
            '0020' => 'No Ongoing Job Available.',
            '0021' => 'You have not checked-in for job.',
            '0022' => 'Job already completed.',
//            '0023' => 'Break already completed.',
            '0024' => 'Another Break is already on progress.',
            '0025' => 'No ongoing break available.',
            '0026' => 'Job is on a different date.',
            '0027' => 'No induction available.',
            '0028' => 'Provided Timestamps intersects with each other.',
            '0029' => 'Existing job Shift intersects with provided unavailability.',
        );
        return $codes[$code];
    }

}
