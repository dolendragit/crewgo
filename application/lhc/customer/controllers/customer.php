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

        if (!$this->ion_auth->logged_in()) {
            redirect('lhc/admin');
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
                if ($this->customer_model->checkExistingCustomer($this->input->post('email')) != false) {
                    //if customer exists
                    $userId  = $this->customer_model->checkExistingCustomer($this->input->post('email'));
                    $message = 'Customer with this email address exist. If you want to associate this customer with the current lhc user, Please <a href="' . base_url('lhc/customer/associate/' . $userId) . '">click here</a>';
                    $this->session->set_flashdata('info', $message);
                    redirect('lhc/customer/add');
                }

                $encrypted = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

                $data = array(
                    'name'            => $this->input->post('name'),
                    'email'           => $this->input->post('email'),
                    'phone_number'    => $this->input->post('phone_number'),
                    'full_address'    => $this->input->post('full_address'),
                    'activation_code' => hashCode(25),
                    'register_from'   => 'LHC',
                    'password'        => $encrypted,
                    'lhc_user_id'     => $this->users->id,
                );

                if (!$this->customer_model->saveCustomer($this->users->id, $data)) {
                    //insert failed
                    $this->session->set_flashdata('error', 'Customer couldnot be added');
                    redirect('lhc/customer/add');
                }
                $this->sendEmail($data);
                $this->session->set_flashdata('success', 'Customer added successfully');
                redirect('lhc/customer/add');

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
        if ($this->customer_model->checkValidityOfEmail($email)) {
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
        $data = array('lhc_user_id' => $this->users->id, 'customer_user_id' => $user, 'requested_date' => date("Y-m-d"), 'status' => '0');

        //check whether record already exists or not
        $query = $this->db->select('*')
            ->from('tbl_lhc_customer_association')
            ->where('lhc_user_id', $this->users->id)
            ->where('customer_user_id', $user)
            ->get();

        if (count($query->row()) == 0) {

            $this->db->insert('tbl_lhc_customer_association', $data);
            $this->session->set_flashdata('success', 'Customer associated successfully');
            redirect('lhc/customer/add');
        } else {
            $this->session->set_flashdata('error', 'Customer has already been associated with this lhc user');
            redirect('lhc/customer/add');
        }
    }

    //sending an email
    public function sendEmail($data)
    {

        $template = $this->db->select('*')
            ->from('tbl_email_templates')
            ->where('template_id', '21')
            ->get();

        $body = $template->row();

        $_POST['user_name'] = $data['email'];
        $_POST['password']  = 'password';
        $this->email_model->send_association_mail();
        //print_r($body);
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
            redirect('lhc/customer');

        }

        $this->template->build('edit', $data);
    }



    //deleting the detail
    public function destroy($id)
    {
        $this->db->delete('tbl_lhc_customer_association', array('customer_user_id' => $id, 'lhc_user_id' => $this->users->id));
        $this->session->set_flashdata('success', 'Customer removed successfully');
        redirect('lhc/customer');
    }

}
