<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customer extends Base_Controller
{

    private $users;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->form_validation->CI = &$this;
        $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
        $this->template->set_layout('lhcadmin/default');
        $this->load->library(array('ion_auth'));
        $this->load->helper(array('url', 'language'));
        $this->load->model(array('ion_auth_model', 'customer_model', 'email_model'));
        $this->load->library('session');

       if (!$this->ion_auth->logged_in() || ($this->ion_auth->logged_in() && $this->ion_auth->get_users_groups()->row()->id != "2")) {
            $this->ion_auth->logout();
            redirect('lp/admin');
        } 
        $this->users = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $data              = array();
        $data['customers'] = $this->customer_model->getAllCustomers($this->users->id);
        $this->template->build('list', $data);

    }

    public function add()
    {

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'customer name', 'required|xss_clean');
            $this->form_validation->set_rules('email', 'email', 'required|valid_email|callback_customer_email_check');
            $this->form_validation->set_rules('phone_number', 'phone', 'required|xss_clean');
            $this->form_validation->set_rules('full_address', 'address', 'required|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('add');
            } else {
                //check whether the user already exists or not
                if ($this->customer_model->checkExistingCustomer($this->input->post('email')) == true) {
                    //if customer exists
                    $userId  = $this->customer_model->checkExistingCustomer($this->input->post('email'));
                    $message = 'Customer with this email address exist. If you want to associate this customer with the current lp user, Please <a href="' . base_url('lp/customer/associate/' . $userId) . '">click here</a>';
                    $this->session->set_flashdata('info', $message);
                    redirect('lp/customer/add');
                }

                // $encrypted = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

                $data = array(
                    'name'            => $this->input->post('name'),
                    'email'           => $this->input->post('email'),
                    'phone_number'    => $this->input->post('phone_number'),
                    'full_address'    => $this->input->post('full_address'),
                    'activation_code' => hashCode(25),
                    'register_from'   => 'LHC',
                    //  'password'        => $encrypted,
                    'lhc_user_id'     => $this->users->id,
                );

                if (!$this->customer_model->saveCustomer($this->users->id, $data)) {
                    //insert failed
                    $this->session->set_flashdata('error', 'Customer couldnot be added');
                    redirect('lp/customer/add');
                }
                $this->sendEmail($data, $this->users->id);
                $this->session->set_flashdata('success', 'Customer added successfully. An instruction for creating a password has been sent to an email');
                redirect('lp/customer/add');

            }
        }

        $data = array();
        $this->template->build('add', $data);
    }

    /*
     * checking existence of the customer's email
     *
     */
    public function customer_email_check($email)
    {

        if ($this->customer_model->checkValidityOfEmail($email, $this->users->id)) {
            //email is valid
            return true;
        } else {
            $this->form_validation->set_message('customer_email_check', 'Email already taken');
            return false;
        }
    }

    /*
     * associating user with the lhc
     *
     */
    public function associate($user)
    {

        $myemail = $this->db->select('email')
            ->from('tbl_user')
            ->where('id', $user)
            ->get();

        //check whether record already exists or not
        $query = $this->db->select('*')
            ->from('tbl_lhc_customer_association')
            ->where('lhc_user_id', $this->users->id)
            ->where('customer_user_id', $user)
            ->get();

        if (count($query->row()) == 0) {
            $data = array('lhc_user_id' => $this->users->id, 'customer_user_id' => $user, 'requested_date' => date("Y-m-d"), 'status' => '1');
            $this->db->insert('tbl_lhc_customer_association', $data);
            $this->sendExistingEmail($myemail->row()->email);
            $this->session->set_flashdata('success', 'Customer associated successfully');
            redirect('lp/customer/add');
        } else {
            $this->session->set_flashdata('error', 'Customer has already been associated with this lhc user');
            redirect('lp/customer/add');
        }
    }

    //sending an email
    public function sendEmail($data, $lhc)
    {

        $template = $this->db->select('*')
            ->from('tbl_email_templates')
            ->where('template_id', '21')
            ->get();

        $body = $template->row();

        $_POST['user_name'] = $data['email'];
        $_POST['email']     = $data['email'];
        $_POST['lp_name']   = $this->users->name;
        $_POST['link']      = '<a href="' . base_url('lp/auth/setcustomerpassword') . '/' . $data['activation_code'] . '/' .$lhc  .'" title="Set Password" style="color:#f4eb38">' . base_url('lp/auth/setcustomerpassword') . '/' . $data['activation_code'] . '</a>';
        $this->email_model->send_association_mail();
        //print_r($body);
    }

    public function sendExistingEmail($email)
    {

        $template = $this->db->select('*')
            ->from('tbl_email_templates')
            ->where('template_id', '22')
            ->get();

        $body = $template->row();

        $_POST['user_name'] = $email;
        $_POST['lp_name']   = $this->users->name;
        $_POST['email']     = $email;
        $this->email_model->send_association_mailnew();
    }

    //editing customer details
    public function edit($id)
    {
        $data                    = array();
        $data['customer_detail'] = $this->customer_model->getCustomerById($id);

        if ($this->input->post()) {
            $data = array(
                'name'         => $this->input->post('name'),
                'phone_number' => $this->input->post('phone_number'),
                'full_address' => $this->input->post('full_address'),

            );

            $this->db->where('id', $this->input->post('userid'));
            $this->db->update('tbl_user', $data);
            $this->session->set_flashdata('success', 'Customer details updated successfully');
            redirect('lp/customer');

        }

        $this->template->build('edit', $data);
    }

    //deleting the detail
    public function destroy($id)
    {
        $this->db->delete('tbl_lhc_customer_association', array('customer_user_id' => $id, 'lhc_user_id' => $this->users->id));
        $this->session->set_flashdata('success', 'Customer removed successfully');
        redirect('lp/customer');
    }

}
