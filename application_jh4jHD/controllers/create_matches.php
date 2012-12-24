<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_matches extends CI_Controller 
{

	public function index()
	{
	    $feedback = array();
	    $data = array();
	    
	    $this->load->model('Poule');
	    $this->Poule = new Poule();
	    $this->Poule->init(1); // Poule A
	    $feedback[] = $this->Poule->createMatches();
	    $data['poules'][] = $this->Poule->getOverview();
	    
// 	    $this->Poule->init(2); // Poule B
// 	    $feedback[] = $this->Poule->createMatches();
// 	    $data['poules'][] = $this->Poule->getOverview();
// 	    $data['feedback'] = implode("\n", $feedback);
	    
	    // render html
	    $this->load->view('headerView');
		$this->load->view('createMatchesView', $data);
		$this->load->view('footerView');
	}
}

