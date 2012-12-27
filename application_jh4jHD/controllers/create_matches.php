<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create_matches extends CI_Controller 
{

	public function index()
	{
	    $feedback = array();
	    $data = array();
	    
	    // first truncate to reset the match_ids.
	    $this->db->truncate('matches');
	    
	    $this->load->model('Poule');
	    
	    foreach (array(1, 2) as $pouleId)
	    {
	    	// create matches for the poule
	    	$poule = $this->Poule->init($pouleId); 
	    	$feedback[] = $poule->createMatches();
	    	
	    	// get poule overview view (for visually checking)
	    	$ovData['overview'] = $poule->getOverview();
	    	$data['pouleOverview'.$pouleId] = $this->load->view('pouleOverviewView', $ovData, true);
	    }
	    
	    $data['feedback'] = implode("\n", $feedback);
	    
	    // render html
	    $this->load->view('headerView');
		$this->load->view('createMatchesView', $data);
		$this->load->view('footerView');
	}
}

