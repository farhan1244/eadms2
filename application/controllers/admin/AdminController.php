<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class AdminController extends CI_Controller
{
    public function __construct()
    {
        //parent
        parent::__construct();
        //model
        $this->load->library('uuid');
        $this->load->model('Admin', 'admin');


    }

    public function index()
    {
        $registeredUser = 0;
        $this->isAdminLoggedIn();
        $results = $this->admin->registeredUserCount();

        for($i=0;$i<3;$i++)
            $registeredUser+= $results[$i]['count'];

        $results[3]['count'] = $registeredUser;

        $this->load->view('admin/dashboard_view', ['results' => $results]);
    }

    public function adminLogin()
    {
        $this->form_validation->set_rules('userEmail', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('userPassword', 'Password', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            $this->load->view('admin/login_view');
        }
        else
        {
            $url = base_url();
            $adminEmail     = $this->input->post('userEmail');
            $adminPassword  = $this->input->post('userPassword');

            $adminData = $this->admin->adminLoginModel($adminEmail, base64_encode($adminPassword));

            if(count($adminData) == 0)
            {
                $this->session->set_flashdata("message", "Error! Your Credentials Are Invalid");
                redirect($url."admin-login");
            }
            else
            {
                $adminData['adminSignInTime'] = date("l, d-M-y H:i:s T");
                $this->session->set_flashdata("message", "Success! Welcome Admin ".$adminData['adminName']);
                $this->session->set_userdata($adminData);
                redirect($url."admin-dashboard");
            }
        }
    }

    public function adminLogout()
    {
        $this->session->sess_destroy();
        redirect(base_url()."admin-login");
    }

    private function isAdminLoggedIn()
    {
        if(empty($this->session->adminId))
        {
            redirect(base_url()."admin-login");
        }
    }
}

?>