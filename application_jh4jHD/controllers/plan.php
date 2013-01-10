<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// show afspraak plannen form
		
		
		
		
		$this->load->view('headerView');
		$this->load->view('planView', $data);
		$this->load->view('footerView');
	}
	
}

