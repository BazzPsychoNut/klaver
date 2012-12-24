<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
{

	public function index()
	{
	    //$this->poule = new Poule(); // for editor
	     
	    $data['poules'][1] = $this->poule->init(1)->getOverview();
	    $data['poules'][2] = $this->poule->init(2)->getOverview();
	    
	    $this->load->view('headerView');
		$this->load->view('homeView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */