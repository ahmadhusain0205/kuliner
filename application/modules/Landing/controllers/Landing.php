<?php
class Landing extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function index()
    {
        $data['judul'] = 'Landing Page';
        $this->load->view('Templates/Header_landing', $data);
        $this->load->view('V_landing', $data);
        $this->load->view('Templates/Footer_landing');
    }
}
