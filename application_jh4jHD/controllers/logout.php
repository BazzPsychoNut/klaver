<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// logout the user
		$this->session->set_userdata('user_logged_in', false);
		$this->session->sess_destroy();
		
		$data['feedback'] = $this->session->userdata('user_logged_in') ? error('Fout bij uitloggen.') : success('Je bent nu uitgelogd.');
		
		$this->load->view('headerView');
		$this->load->view('logoutView', $data);
		$this->load->view('footerView');
	}
	
}

