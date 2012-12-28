<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// create the form
		require_once APPPATH.'forms/SignupForm.php';
		$data['form'] = new SignupForm('signup_form');
		
		$this->load->view('headerView');
		$this->load->view('signupView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */