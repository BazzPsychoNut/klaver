<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller 
{

	public function index()
	{
	    //$this->poule = new Poule(); // for editor
	    
		// fetch the pouleOverviewViews as string in $data to pass on to homeView
		$poules = $this->session->userdata('user_poule_id') == 1 ? array(1,2) : array(2,1); // start with own poule
	    foreach ($poules as $pouleId)
	    {
	    	$poule = $this->poule->init($pouleId);
		    $ovData['overview'] = $poule->getOverview();
		    $ovData['poulename'] = $poule->getPouleName();
		    $data['pouleOverview'][$pouleId] = $this->load->view('pouleOverviewView', $ovData, true);
	    }
	    
	    $this->load->view('headerView');
		$this->load->view('overviewView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */