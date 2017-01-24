<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function customer_interface_url($string="") {
		$url = base_url().MODULE.'/'.$string;
		return $url;
	}

    function get_skills_schema(){
        $raw_test_data = '
        [
                {
                    "skill_id":1,
                    "level_id":2,
                    "quantity":2,
                    "date": "2016-10-20",
                    "start_time": "00:00",
                    "end_time": "00:00",
                },
                {
                    "skill_id":3,
                    "level_id":3,
                    "quantity":3,
                    "date": "2016-10-20",
                    "start_time": "00:00",
                    "end_time" : "00:00", 
                }
        ]
        ';

        return $raw_test_data;
    }

    function job_schema(){
        $json_schema = '{
            "description": "Here goes some notes.",
            "job_title":  "Test Title",
            "job_full_address":  "Greenwich Park",
            "meeting_full_address": "Gundary",
            "meeting_lat": "-34.440000000",
            "meeting_long": "149.960000000",
            "skills": [
                {
                    "skill_id":1,
                    "level_id":2,
                    "required_number":2,
                    "start_time": "'.strtotime(date('Y-m-d 08:00:00')).'",
                    "end_time": "'.strtotime(date('Y-m-d 16:00:00')).'",
                    "breaks": [
                        {
                            "start_time": "'.strtotime(date('Y-m-d 12:00:00')).'",
                            "end_time": "'.strtotime(date('Y-m-d 12:15:00')).'"
                        },
                        {
                            "start_time": "'.strtotime(date('Y-m-d 14:00:00')).'",
                            "end_time": "'.strtotime(date('Y-m-d 14:15:00')).'"
                        }
                    ]
                },
                {
                    "skill_id":3,
                    "level_id":3,
                    "required_number":3,
                    "start_time": "'.strtotime(date('Y-m-d 09:00:00')).'",
                    "end_time": "'.strtotime(date('Y-m-d 16:00:00')).'",
                    "breaks": [
                        {
                            "start_time": "'.strtotime(date('Y-m-d 14:00:00')).'",
                            "end_time": "'.strtotime(date('Y-m-d 14:15:00')).'"
                        }
                    ]
                }
            ],
            "attributes":"1,2"
        }';

        return $json_schema;
    }

     function get_qualification_schema(){
        $json_schema = '
        {
            "qualifications": [{
                "skill_id": 1,
                "level_id": 1,
                "qualification_ids": [1, 2, 3]
                },{
                "skill_id": 2,
                "level_id": 1,
                "qualification_ids": [3]
            }],
            "job_id":94
        }
        ';

        return $json_schema;
    }



    function get_break_times(){
        $breaks = array(
            '15' => '15 minutes',
            '30' => '30 minutes',
            '45' => '45 minutes',
        );
        return $breaks;
    }

    function get_break_time($key=""){
        switch ($key) {
            case '15':
                return '15 minutes';
                break;

            case '30':
                return '30 minutes';
                break;

            case '45':
                return '45 minutes';
                break;
            
            default:
                return '0';
                break;
        }
    }

    function get_customer_profile_image($image=""){
        if(!empty($image)){
            $url = site_url().'customer/assets/profile_image/'.$image;
            return $url;
            if(file_exists($url)){
                return $url;
            }
        }
        return "";
    }


