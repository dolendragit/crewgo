<?php

defined('BASEPATH') or die('Direct access is not allowed');

class Email_model extends CI_Model
{

    public function send_email($to = '', $from = '', $cc = '', $subject = '', $body = '', $sitename = '', $attach = null)
    {

        $this->load->library('email');
        //$config['protocol'] = 'mail';
        //$config['charset'] = 'utf-8';
        //$config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        //echo $to;exit;
        $this->email->clear(true); ////if you set the parameter to TRUE any attachments will be cleared as well
        $this->email->initialize($config);
        $this->email->from($from, $sitename);
        $this->email->to($to);
        if ($cc != '') {
            $this->email->bcc($cc);
        }

        $this->email->subject($subject);
        $this->email->message($body);

        if ($attach != null) {
            $this->email->attach($attach);
        }

        try {
            $this->email->send();
            // dd('email not send',false);
            // print_r($this->email->print_debugger());
        } catch (Exception $e) {
            // throw new Exception(e);
            return false;
        }
        return true;
    }

    public function parse_email($cond = array(), $tempid = 0)
    {
        $this->db->where("template_id", $tempid);
        $query      = $this->db->get("tbl_email_templates");
        $row        = $query->row(0);
        $subject    = $row->subject;
        $email_from = $row->email_from;
        $body       = $row->content;

        foreach ($cond as $key => $val) {
            $body = str_replace($key, $val, $body);
        }

        // echo $body;exit;

        return array($subject, $email_from, $body);
    }

    /*
     * Function for sending email
     */

    public function send_all_email($mail_for)
    {
        //Admin reset password mail
        if ($mail_for == "admin_reset_password") {
            $email_cond = array(
                '[USER_NAME]'           => $_POST['user_name'],
                '[RESET_PASSWORD_LINK]' => $_POST['reset_password_link'],
            );

            list($subject, $email_from, $body) = $this->parse_email($email_cond, 20);
            if (isset($email_from) && $email_from != '' && $email_from != null) {
                $from = $email_from;
            } else {
                $from = DEFAULT_EMAIL;
            }

            if ($this->send_email($_POST['email'], $from, '', $subject, $body, EMAIL_SITENAME)) {

                return true;
            } else {

                return false;
            }
        }
    }

    public function send_registration_mail()
    {
        $email_cond = array(
            '[USER_NAME]'       => $_POST['user_name'],
            '[ACTIVATION_LINK]' => $_POST['activation_link'],
        );

        list($subject, $email_from, $body) = $this->parse_email($email_cond, 1);
        if (isset($email_from) && $email_from != '' && $email_from != null) {
            $from = $email_from;
        } else {
            $from = DEFAULT_EMAIL;
        }

        if ($this->send_email($_POST['email'], $from, '', $subject, $body, EMAIL_SITENAME)) {

            return true;
        } else {

            return false;
        }
    }

    public function send_association_mail()
    {
        $email_cond = array(
            '[USER_NAME]'     => $_POST['user_name'],
            '[LP_NAME]'       => $_POST['lp_name'],
            '[PASSWORD_LINK]' => $_POST['link'],
        );

        list($subject, $email_from, $body) = $this->parse_email($email_cond, 21);
        if (isset($email_from) && $email_from != '' && $email_from != null) {
            $from = $email_from;
        } else {
            $from = DEFAULT_EMAIL;
        }

        if ($this->send_email($_POST['email'], $from, '', $subject, $body, EMAIL_SITENAME)) {

            return true;
        } else {

            return false;
        }
    }

    public function send_association_mailnew()
    {

        $email_cond = array(
            '[USER_NAME]' => $_POST['user_name'],
            '[LP_NAME]'   => $_POST['lp_name'],
        );

        list($subject, $email_from, $body) = $this->parse_email($email_cond, 22);
        if (isset($email_from) && $email_from != '' && $email_from != null) {
            $from = $email_from;
        } else {
            $from = DEFAULT_EMAIL;
        }

        if ($this->send_email($_POST['email'], $from, '', $subject, $body, EMAIL_SITENAME)) {

            return true;
        } else {

            return false;
        }
    }

    public function send_passwordreset_mail()
    {
        $email_cond = array(
            '[USER_NAME]'       => $_POST['user_name'],
            '[ACTIVATION_CODE]' => $_POST['activation_code'],
        );

        list($subject, $email_from, $body) = $this->parse_email($email_cond, 2);
        if (isset($email_from) && $email_from != '' && $email_from != null) {
            $from = $email_from;
        } else {
            $from = DEFAULT_EMAIL;
        }

        if ($this->send_email($_POST['email'], $from, '', $subject, $body, EMAIL_SITENAME)) {

            return true;
        } else {

            return false;
        }
    }

    public function send_supervisor_pasword_link($to="",$data="")
    {
        $email_cond = array(
            '[USER_NAME]'       => $data['user_name'],
            '[LINK]' => $data['link'],
        );

        list($subject, $email_from, $body) = $this->parse_email($email_cond, 24);
        if (isset($email_from) && $email_from != '' && $email_from != null) {
            $from = $email_from;
        } else {
            $from = DEFAULT_EMAIL;
        }

        if ($this->send_email($to, $from, '', $subject, $body, EMAIL_SITENAME)) {

            return true;
        } else {

            return false;
        }
    }

}
