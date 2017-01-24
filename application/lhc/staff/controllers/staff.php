<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staff extends Base_Controller
{

    protected $users;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->template->set_layout('lhcadmin/default');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->load->model(array('ion_auth_model', 'staff_model', 'induction/induction_model'));
        $this->load->library('session');
        $this->load->helper('my_helper');

        if (!$this->ion_auth->logged_in()) {
            redirect('lhc/admin');
        }
        $this->users = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $data          = array();
        $data['staff'] = $this->staff_model->getAllUserDetail($this->users->id);

        $this->template->build('list', $data);
    }

/*
 * implementing validation for all staff add form
 * checking whether validation passes or not.
 *
 *
 *
 */
    public function add()
    {
        $specificIndustry = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);
        $allSuburbs       = $this->staff_model->allSuburb();

        $data              = array();
        $data['industry']  = $specificIndustry;
        $data['suburb']    = $allSuburbs;
        $data['induction'] = $this->induction_model->getAll($this->users->id);

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'staff name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email|callback_staff_email_check');
            $this->form_validation->set_rules('mobileno', 'mobile number', 'required|trim|xss_clean');
            $this->form_validation->set_rules('address', 'address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('from', 'hours from', 'required|trim|xss_clean');
            $this->form_validation->set_rules('to', 'hours to', 'required|trim|xss_clean');
            $this->form_validation->set_rules('workhours', 'work hours', 'required|trim|xss_clean');
            $this->form_validation->set_rules('days[]', 'days', 'required|trim|xss_clean');
            $this->form_validation->set_rules('contactable_from', 'contactable from', 'required|trim|xss_clean');
            $this->form_validation->set_rules('contactable_to', 'contactable to', 'required|trim|xss_clean');
            $this->form_validation->set_rules('maincategory[]', 'main category', 'required|trim|xss_clean');
            $this->form_validation->set_rules('subskill[]', 'sub skill', 'required|trim|xss_clean');
            $this->form_validation->set_rules('transportation', 'transportation', 'required|trim|xss_clean');
            $this->form_validation->set_rules('areas[]', 'areas', 'required|trim|xss_clean');
            $this->form_validation->set_rules('driving_expiry', 'driving expiry', 'required|trim|xss_clean');
            $this->form_validation->set_rules('induction', 'induction', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('add');
            } else {
                $this->saveUserDetail($this->input->post());
            }

            //$this->saveUserDetail($this->input->post());
        }

        $this->template->build('add', $data);
    }

    /*
     * callback function to check valid email
     *
     *
     */

    public function staff_email_check($email)
    {
        $staffUsers            = $this->ion_auth->users(4)->result();
        $emailToBeCheckAgainst = array();
        foreach ($staffUsers as $val) {
            $emailToBeCheckAgainst[] = $val->email;
        }

        if (in_array($email, $emailToBeCheckAgainst)) {
            $this->form_validation->set_message('staff_email_check', 'Email already taken');
            return false;
        } else {
            return true;
        }
    }

    /*
     * saving staff user detail
     *
     *
     */

    public function saveUserDetail($datas, $id = null)
    {

        $user_detail = array(
            'name'          => $datas['name'],
            'email'         => $datas['email'],
            'phone_number'  => $datas['mobileno'],
            'full_address'  => $datas['address'],
            'register_from' => 'LHC',
            'lhc_user_id'   => $this->users->id,
        );

        $insertedUserId = $this->staff_model->saveUserDetail($user_detail, $id);

        $this->saveStaffInfo($insertedUserId, $datas);
        $this->saveStaffAvailability($insertedUserId, $datas);
        if ($datas['userid'] == "") {
            $this->saveStaffSkill($insertedUserId, $datas);
            $this->saveStaffQualification($insertedUserId, $datas);
            $this->saveStaffTraining($insertedUserId, $datas);
        }
        $this->saveArea($insertedUserId, $datas);
        $this->saveInduction($insertedUserId, $datas);

        if ($id != "") {
            $this->session->set_flashdata('success', 'Staff details edited successfully');
            redirect('lhc/staff/edit/' . $id);
        }

        $this->session->set_flashdata('success', 'New Staff added successfully');
        redirect('lhc/staff/add');

    }

    /*
     * saving staff information
     *
     *
     */

    public function saveStaffInfo($id, $infos)
    {

        if ($infos['userid'] != "") {
            $currentEdit = $infos['userid'];
        } else {
            $currentEdit = 0;
        }

        $addition_info = array(
            'staff_user_id'         => $id,
            'full_address'          => '',
            'transport'             => $infos['transportation'],
            'driving_passport'      => $infos['document_type'],
            'diving_passport_file'  => '',
            'expiry_date'           => $infos['driving_expiry'],
            'sociable_hour_from'    => $infos['from'],
            'sociable_hour_to'      => $infos['to'],
            'work_hour'             => $infos['workhours'],
            'conatctable_hour_from' => $infos['contactable_from'],
            'conatctable_hour_to'   => $infos['contactable_to'],
        );

        $config['upload_path']   = IMAGEPATH . 'uploads/staff_documents/';
        $config['file_name']     = date('his') . hashCode(4);
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 1000;
        $config['max_width']     = 1024;
        $config['max_height']    = 768;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('driving')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data                                  = $this->upload->data();
            $addition_info['diving_passport_file'] = $data['file_name'];
        }

        $this->staff_model->saveStaffInformation($id, $addition_info, $currentEdit);
    }

    /*
     * saving staff availability
     *
     *
     */

    public function saveStaffAvailability($insertedUserId, $datas)
    {

        if ($datas['userid'] != "") {
            $currentEdit = $datas['userid'];
        } else {
            $currentEdit = 0;
        }

        $daysAndAvailability = array();
        foreach ($datas['days'] as $val) {
            switch ($val) {
                case '0':
                    $daysAndAvailability['day_0'] = 1;
                    break;

                case '1':
                    $daysAndAvailability['day_1'] = 1;
                    break;

                case '2':
                    $daysAndAvailability['day_2'] = 1;
                    break;

                case '3':
                    $daysAndAvailability['day_3'] = 1;
                    break;

                case '4':
                    $daysAndAvailability['day_4'] = 1;
                    break;

                case '5':
                    $daysAndAvailability['day_5'] = 1;
                    break;

                case '6':
                    $daysAndAvailability['day_6'] = 1;
                    break;

                default:
                    return;
                    break;
            }
        }
        $daysAndAvailability['staff_user_id'] = $insertedUserId;
        $this->staff_model->staff_availability_store($daysAndAvailability, $currentEdit);

    }

    /*
     * saving staff skills
     *
     *
     */

    public function saveStaffSkill($id, $datas)
    {
        $this->staff_model->staff_skill_store($id, $datas);
    }

    /*
     * saving staff qualification
     *
     *
     */

    public function saveStaffQualification($id, $datas)
    {
        $title  = $datas['title'];
        $expiry = $datas['expiry'];

        $names_array = array();
        $this->load->library('upload');
        $file_no = count($_FILES['documents']['name']);

        for ($i = 0; $i < $file_no; $i++) {

            if (!empty($_FILES['documents']['name'][$i])) {

                $_FILES['userfile']['name']     = $_FILES['documents']['name'][$i];
                $_FILES['userfile']['type']     = $_FILES['documents']['type'][$i];
                $_FILES['userfile']['tmp_name'] = $_FILES['documents']['tmp_name'][$i];
                $_FILES['userfile']['error']    = $_FILES['documents']['error'][$i];
                $_FILES['userfile']['size']     = $_FILES['documents']['size'][$i];

                $config = array(
                    'file_name'     => date('his') . hashCode(3),
                    'allowed_types' => '*',
                    'max_size'      => 3000,
                    'overwrite'     => false,

                    /* real path to upload folder ALWAYS */
                    'upload_path'   => IMAGEPATH . 'uploads/qualifications/',
                );

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    echo $this->upload->display_errors();
                } else {
                    //success
                    $final         = $this->upload->data();
                    $names_array[] = $final['file_name'];
                }
            }
        }

        $this->staff_model->saveQualification($title, $expiry, $names_array, $id);
    }

    /*
     * saving staff training
     *
     *
     */

    public function saveStaffTraining($id, $datas)
    {
        $training_title  = $datas['training_title'];
        $training_expiry = $datas['training_expiry'];

        $names_array_new = array();
        $this->load->library('upload');
        $file_no = count($_FILES['training_document']['name']);

        for ($i = 0; $i < $file_no; $i++) {

            if (!empty($_FILES['documents']['name'][$i])) {

                $_FILES['userfile1']['name']     = $_FILES['documents']['name'][$i];
                $_FILES['userfile1']['type']     = $_FILES['documents']['type'][$i];
                $_FILES['userfile1']['tmp_name'] = $_FILES['documents']['tmp_name'][$i];
                $_FILES['userfile1']['error']    = $_FILES['documents']['error'][$i];
                $_FILES['userfile1']['size']     = $_FILES['documents']['size'][$i];

                $config = array(
                    'file_name'     => date('his') . hashCode(3),
                    'allowed_types' => '*',
                    'max_size'      => 3000,
                    'overwrite'     => false,

                    /* real path to upload folder ALWAYS */
                    'upload_path'   => IMAGEPATH . 'uploads/trainings/',
                );

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    echo $this->upload->display_errors();
                } else {
                    //success
                    $final_new         = $this->upload->data();
                    $names_array_new[] = $final_new['file_name'];
                }
            }
        }

        $this->staff_model->saveTraining($training_title, $training_expiry, $names_array_new, $id);
    }

    /*
     * saving staff preferred area
     *
     */

    public function saveArea($id, $datas)
    {
        if ($datas['userid'] != "") {
            $currentEdit = $datas['userid'];
        } else {
            $currentEdit = 0;
        }
        $this->staff_model->saveUserArea($id, $datas, $currentEdit);
    }

    /*
     * saving induction
     *
     */
    public function saveInduction($id, $datas)
    {
        if ($id == $datas['userid']) {
            $currentEdit = $id;
        } else {
            $currentEdit = 0;
        }
        $this->staff_model->createInduction($id, $datas, $currentEdit);
    }

    /*
     * editing staff details
     *
     */
    public function edit($id)
    {
        $data                      = array();
        $data['user']              = $this->staff_model->getUserById($id);
        $data['info']              = $this->staff_model->getInfoById($id);
        $data['available']         = $this->staff_model->getAvailabilityById($id);
        $data['skill']             = $this->staff_model->getSkillById($id);
        $data['industry']          = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);
        $selectedSub               = $this->staff_model->getSuburbById($id);
        $data['induction']         = $this->induction_model->getAll($this->users->id);
        $data['selectedInduction'] = $this->staff_model->getSelectedInduction($id);
        $data['qualification']     = $this->staff_model->getQualificationById($id);
        $data['training']          = $this->staff_model->getTrainingById($id);

        $formatted = array();
        foreach ($selectedSub as $key => $val) {
            $formatted[] = $val->postcode_id;
        }
        $data['selectedArray'] = $formatted;
        $data['suburb']        = $this->staff_model->allSuburb();

        $this->template->build('edit', $data);
    }

    /*
     * editing skills
     *
     */
    public function personaldetailupdate()
    {
        $this->form_validation->set_rules('name', 'staff name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'required|trim|valid_email|callback_staff_email_check_current');
        $this->form_validation->set_rules('mobileno', 'mobile number', 'required|trim|xss_clean');
        $this->form_validation->set_rules('address', 'address', 'required|trim|xss_clean');
        $this->form_validation->set_rules('from', 'hours from', 'required|trim|xss_clean');
        $this->form_validation->set_rules('to', 'hours to', 'required|trim|xss_clean');
        $this->form_validation->set_rules('workhours', 'work hours', 'required|trim|xss_clean');
        $this->form_validation->set_rules('days[]', 'days', 'required|trim|xss_clean');
        $this->form_validation->set_rules('contactable_from', 'contactable from', 'required|trim|xss_clean');
        $this->form_validation->set_rules('contactable_to', 'contactable to', 'required|trim|xss_clean');
        $this->form_validation->set_rules('transportation', 'transportation', 'required|trim|xss_clean');
        $this->form_validation->set_rules('areas[]', 'areas', 'required|trim|xss_clean');
        $this->form_validation->set_rules('driving_expiry', 'driving expiry', 'required|trim|xss_clean');
        $this->form_validation->set_rules('induction', 'induction', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->edit($this->input->post('userid'));
        } else {
            $this->saveUserDetail($this->input->post(), $this->input->post('userid'));
            //validation success
        }
    }

    public function staff_email_check_current($email)
    {

        $currentUser           = $this->staff_model->getUserById($this->input->post('userid'));
        $staffUsers            = $this->ion_auth->users(4)->result();
        $emailToBeCheckAgainst = array();
        foreach ($staffUsers as $val) {
            $emailToBeCheckAgainst[] = $val->email;
        }

        if ($currentUser->email == $email) {
            return true;
        } else {
            if (in_array($email, $emailToBeCheckAgainst)) {
                $this->form_validation->set_message('staff_email_check_current', 'Email already taken');
                return false;
            } else {
                return true;
            }
        }
    }

    /*
     *
     * adding new staff skill
     *
     */
    public function addskill()
    {
        if ($this->input->post()) {
            //save data to database
            $data = array(
                'staff_user_id' => $this->input->post('myuserid'),
                'skill_id'      => $this->input->post('skill'),
                'level_id'      => $this->input->post('level'),
            );

            if ($this->staff_model->addNewSkillViaEdit($data)) {
                $this->session->set_flashdata('success', 'Skills updated successfully');
                redirect('lhc/staff/edit/' . $data['staff_user_id'] . '/#profile');
            } else {
                $this->session->set_flashdata('error', 'Skills already exists');
                redirect('lhc/staff/edit/' . $data['staff_user_id'] . '/#profile');
            }
        }
    }

    /*
     * editing skills
     *
     */
    public function editskills($rowId = null)
    {
        $data                     = array();
        $data['industry']         = $this->staff_model->specifcIndustryForLhcStaff($this->users->id);
        $data['selectedIndustry'] = $this->staff_model->getSelectedSkillDetail($rowId);

        if ($this->input->post()) {
            $data = array(
                'skill_id' => $this->input->post('skill'),
                'level_id' => $this->input->post('level'),
            );

            if ($this->staff_model->updateSkillViaEdit($data, $this->input->post('userid'), $this->input->post('skillid'))) {
                //success
                $this->session->set_flashdata('success', 'Skills updated successfully');
                redirect('lhc/staff/edit/' . $this->input->post('userid') . '/#profile');
            } else {
                $this->session->set_flashdata('error', 'Skills could not be updated');
                redirect('lhc/staff/edit/' . $this->input->post('userid') . '/#profile');
            }
        }

        $this->template->build('skilledit', $data);
    }

    /*
     * deleting skill
     *
     */
    public function deleteskill($id, $user)
    {
        $query = $this->db->delete('tbl_staff_skill', array('id' => $id));
        $this->session->set_flashdata('success', 'Skills removed successfully');
        redirect('lhc/staff/edit/' . $user . '/#profile');
    }

    /*
     * adding new qualifiction
     *
     */
    public function addqualification()
    {
        if ($this->input->post()) {
            $datas = array(
                'name'          => $this->input->post('title'),
                'expiry_date'   => $this->input->post('expiry_date'),
                'staff_user_id' => $this->input->post('myuserid'),
            );

            $config['upload_path']   = IMAGEPATH . 'uploads/qualifications/';
            $config['file_name']     = date('his') . hashCode(4);
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 1000;
            $config['max_width']     = 1024;
            $config['max_height']    = 768;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('document')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data                   = $this->upload->data();
                $datas['document_name'] = $data['file_name'];
            }

            $this->staff_model->addNewQualification($datas);
            $this->session->set_flashdata('success', 'Qualification added successfully');
            redirect('lhc/staff/edit/' . $this->input->post('myuserid') . '/#profile');

        }
    }

    /*
     * editing qualification
     *
     */
    public function editqualification($id)
    {
        $data                  = array();
        $data['qualification'] = $this->staff_model->getSingleQualificationById($id);

        if ($this->input->post()) {
            $datas = array(
                'name'        => $this->input->post('title'),
                'expiry_date' => $this->input->post('expiry_date'),
            );

            if ($this->input->post('document') != "") {
                $config['upload_path']   = IMAGEPATH . 'uploads/qualifications/';
                $config['file_name']     = date('his') . hashCode(4);
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 1000;
                $config['max_width']     = 1024;
                $config['max_height']    = 768;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('document')) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $data                   = $this->upload->data();
                    $datas['document_name'] = $data['file_name'];
                }
            }
            $this->staff_model->updateStaffQualification($datas, $this->input->post('rowid'));
            $this->session->set_flashdata('success', 'Qualification updated successfully');
            redirect('lhc/staff/edit/' . $this->input->post('userid') . '/#profile');

        }

        $this->template->build('qualificationedit', $data);
    }

    /*
     * deleting qualification
     *
     */
    public function deletequalification($id, $userid)
    {
        $query = $this->db->delete('tbl_staff_qualification', array('id' => $id));
        $this->session->set_flashdata('success', 'Qualification removed successfully');
        redirect('lhc/staff/edit/' . $userid . '/#profile');
    }

    /*
     * adding new training
     *
     */

    /*
     * adding new qualifiction
     *
     */
    public function addtraining()
    {
        if ($this->input->post()) {
            $datas = array(
                'name'          => $this->input->post('title'),
                'expiry_date'   => $this->input->post('expiry_date'),
                'staff_user_id' => $this->input->post('myuserid'),
            );

            $config['upload_path']   = IMAGEPATH . 'uploads/trainings/';
            $config['file_name']     = date('his') . hashCode(4);
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 1000;
            $config['max_width']     = 1024;
            $config['max_height']    = 768;
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('document')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data                   = $this->upload->data();
                $datas['document_name'] = $data['file_name'];
            }

            $this->staff_model->addNewTraining($datas);
            $this->session->set_flashdata('success', 'Training added successfully');
            redirect('lhc/staff/edit/' . $this->input->post('myuserid') . '/#profile');

        }
    }

    /*
     * deleting training
     *
     */
    public function deletetraining($id, $userid)
    {
        $query = $this->db->delete('tbl_staff_training', array('id' => $id));
        $this->session->set_flashdata('success', 'Training removed successfully');
        redirect('lhc/staff/edit/' . $userid . '/#profile');
    }

    /*
     * editing training
     *
     */
    public function edittraining($id)
    {
        $data             = array();
        $data['training'] = $this->staff_model->getSingleTrainingById($id);

        if ($this->input->post()) {
            $datas = array(
                'name'        => $this->input->post('title'),
                'expiry_date' => $this->input->post('expiry_date'),
            );

            if ($this->input->post('document') != "") {
                $config['upload_path']   = IMAGEPATH . 'uploads/trainings/';
                $config['file_name']     = date('his') . hashCode(4);
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 1000;
                $config['max_width']     = 1024;
                $config['max_height']    = 768;
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('document')) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $data                   = $this->upload->data();
                    $datas['document_name'] = $data['file_name'];
                }
            }
            $this->staff_model->updateStaffTraining($datas, $this->input->post('rowid'));
            $this->session->set_flashdata('success', 'Training updated successfully');
            redirect('lhc/staff/edit/' . $this->input->post('userid') . '/#profile');

        }

        $this->template->build('trainingedit', $data);
    }

    /*
     * ajax function
     *
     */

    public function industrySkill()
    {
        $value    = $this->input->post('myvalue');
        $subSkill = $this->staff_model->subSkills($value);
        echo json_encode($subSkill);
    }

}
