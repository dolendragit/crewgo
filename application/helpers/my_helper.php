<?php

/* This function is used to display page title*/

function getPageActionTitle($page_url, $page_title, $page_action = "")
{
    //page url : url for the page

    if ($page_action == "add" || $page_action == "edit"):
        echo '<h3>' . $page_title . ' &nbsp;||&nbsp; <a href="' . base_url($page_url) . '">List'
            . ' <span class="fa fa-list-ol" style="font-size:16px;vertical-align:middle;"></span></a> </h3>';
    else:

        echo '<h3>' . $page_title . ' &nbsp;||&nbsp; <a href="' . base_url($page_url) . '/add">'
            . ' Add <span class="fa fa-plus" style="font-size:16px;vertical-align:middle;"></span></a> </h3> ';
    endif;
}

/*This function is used to display page messages*/

function displayMessages()
{
    $ci = &get_instance();
    $msg = '<!--notification start-->';

    if ($ci->session->flashdata('errorMessage')) {
        $msg .= '
          <div class="alert alert-block alert-danger fade in">
              <button data-dismiss="alert" class="close close-sm" type="button">
                  <i class="fa fa-times"></i>
              </button>
              ' . $ci->session->flashdata('errorMessage') . '
          </div>';
    }

    if ($ci->session->flashdata('successMessage')) {
        $msg .= '
          <div class="alert alert-success fade in">
                      <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="fa fa-times"></i>
                      </button>
                      ' . $ci->session->flashdata('successMessage') . '
                  </div>';
    }

    if ($ci->session->flashdata('bluebarMessage')) {
        $msg .= '
          <div class="alert alert-info fade in">
                      <button data-dismiss="alert" class="close close-sm" type="button">
                          <i class="fa fa-times"></i>
                      </button>
                      ' . $ci->session->flashdata('bluebarMessage') . '
                  </div>';
    }

    if ($ci->session->flashdata('awareMessage')) {
        $msg .= '
          <div class="alert alert-warning fade in">
              <button data-dismiss="alert" class="close close-sm" type="button">
                  <i class="fa fa-times"></i>
              </button>
              ' . $ci->session->flashdata('awareMessage') . '
          </div>';
    }

    $msg .= '<!--notification end-->';
    echo $msg;
}

/*This function use to fetch user module and menu privilege
 * module_menu = array(menu=>array(module_id=>'',page_url=>''))
 * if(menu[module_id]==) then fetch module else fetch menu for the module
 */
function getUserModuleMenu($module_menu = array()) //if module is false then get menus

{
    $ci = &get_instance();
    $user_details = $ci->ion_auth->user()->row(); //get logged user details

    $user_group_id = $ci->ion_auth->get_users_groups($user_details->id)->row()->id;

    $group_id = $user_group_id;
    $sub_query = "";
    $privilege_where = '(can_add=1 OR can_edit=1 OR can_remove=1 OR can_view=1 )';
    $query = $ci->db->select('module.name AS module_name,module.id AS module_id,
            menu.id AS menu_id, menu.name AS menu_name, menu.page_url,mp.can_add,mp.can_edit,mp.can_remove,mp.can_view,menu.is_secondary,
            module.icon_class')->from('tbl_menu_privilege mp')
        ->join('tbl_con_module as module', 'mp.module_id = module.id')
        ->join('tbl_con_menu menu', ' mp.menu_id = menu.id')
        ->where('mp.group_id', $group_id)
        ->where($privilege_where);

    if (isset($module_menu['menu_id'])) {
        $query->where('menu_id', $module_menu['menu_id']);
    }

    if (isset($module_menu['page_url'])) {
        $query->like('menu.page_url', $module_menu['page_url']);
    }

    if ($module_menu['module_id'] != "") {

        //fetch all menu of module
        $query = $query->where('module_cd', $module_menu['module_id'])->group_by('mp.menu_id')->order_by('menu.order_by', 'ASC');

    } else {
        //fetch all modules of provide  groups

        $query = $query->group_by('mp.module_id')->order_by('module.order_by', 'ASC');
    }

    if (isset($module_menu['get_row'])) {
        return $query_result = $query->get()->row();
    } else {

        return $query_result = $query->get()->result();
    }

}

function hashCode($length = 32)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function getSkills($id, $level)
{
    $ci = &get_instance();

    $query = $ci->db->select('*')
        ->from('tbl_con_skill')
        ->where('id', $id)
        ->get();

    $query1 = $ci->db->select('*')
        ->from('tbl_con_level')
        ->where('id', $level)
        ->get();

    if ($query->num_rows() > 0) {

        return $query->row()->name . '>' . $query1->row()->name;
    } else {
        return 'N/A';
    }

}

function getStatusData($tbl_id, $current_status, $master_data)
{
    if ($master_data == 'lhc_manage') {
        $status_data = '<input type="hidden" name="status" id="status" value="' . $current_status . '">';
        if ($current_status == 1) {
            $status_data .= '<a href="javascript:" onclick="changeMasterDataStatus(\'' . base_url('admin/master_data/changeMasterDataStatus') . '\',\'' . $master_data . '\',\'' . $tbl_id . '\')" title="change status"><span class="label label-success">Active</span></a>';
        } else {
            $status_data .= '<a href="javascript:" onclick="changeMasterDataStatus(\'' . base_url('admin/master_data/changeMasterDataStatus') . '\',\'' . $master_data . '\',\'' . $tbl_id . '\')" title="change status"><span class="label label-default">Passive</span></a>';
        }

        return $status_data;
    } else {

        $status_data = '<input type="hidden" name="status" id="status" value="' . $current_status . '">';
        if ($current_status == 1) {
            $status_data .= '<a href="javascript:" onclick="changeMasterDataStatus(\'' . base_url('admin/master_data/changeMasterDataStatus') . '\',\'' . $master_data . '\',\'' . $tbl_id . '\')" title="change status"><span class="label label-success">Show</span></a>';
        } else {
            $status_data .= '<a href="javascript:" onclick="changeMasterDataStatus(\'' . base_url('admin/master_data/changeMasterDataStatus') . '\',\'' . $master_data . '\',\'' . $tbl_id . '\')" title="change status"><span class="label label-default">Hide</span></a>';
        }

        return $status_data;
    }
}


function checkJobStatus($job_id)
{
    $ci = &get_instance();


    $query = $ci->db->select('*')
        ->from('tbl_job_manual_alert_setting')
        ->where('job_detail_id', $job_id)
        ->get();

    return $query->num_rows();
}

function get_date_time($time = 'yes')
{
    if ($time == 'no') {
        return date('Y-m-d H:i:s');
    }
    return date('Y-m-d H:i:s');
}

function thumb($fullName)
{
    $CI = &get_instance();
    $dir = './assets/uploads/';
    $url = base_url() . 'assets/uploads/';
    $width = 25;
    $height = 20;

    $extension = pathinfo($fullName, PATHINFO_EXTENSION);
    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png') {
        $filename = pathinfo($fullName, PATHINFO_FILENAME);

        $image_org = $dir . $filename . "." . $extension;

        $image_thumb = $dir . $filename . "." . $extension;
        $image_returned = $url . $filename . "." . $extension;

        if (!file_exists($image_thumb)) {
            $CI->load->library('image_lib');
            $configThumb['source_image'] = $image_org;
            $configThumb['new_image'] = $image_thumb;
            $configThumb['maintain_ratio'] = TRUE;
            $configThumb['width'] = $width;
            $configThumb['height'] = $height;
            $CI->image_lib->initialize($configThumb);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
        }
        return $image_returned;
    } else {
        return null;
    }
}

/**
 * Get Timezone based on provided location
 * @param $location
 * @return bool|string
 */
function getTimezone($location)
{
    $location = urlencode($location);
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$location}&sensor=false";
    $data = file_get_contents($url);


    // Get the lat/lng out of the data
    $data = json_decode($data);
    if (!$data) return false;
    if (!is_array($data->results)) return false;
    if (!isset($data->results[0])) return false;
    if (!is_object($data->results[0])) return false;
    if (!is_object($data->results[0]->geometry)) return false;
    if (!is_object($data->results[0]->geometry->location)) return false;
    if (!is_numeric($data->results[0]->geometry->location->lat)) return false;
    if (!is_numeric($data->results[0]->geometry->location->lng)) return false;
    $lat = $data->results[0]->geometry->location->lat;
    $lng = $data->results[0]->geometry->location->lng;

    // get the API response for the timezone
    $timestamp = time();
    $timezoneAPI = "https://maps.googleapis.com/maps/api/timezone/json?location={$lat},{$lng}&sensor=false&timestamp={$timestamp}";
    $response = file_get_contents($timezoneAPI);
    if (!$response) return false;
    $response = json_decode($response);
    if (!$response) return false;
    if (!is_object($response)) return false;
    if (!is_string($response->timeZoneId)) return false;

    return $response->timeZoneId;
}
