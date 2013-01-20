<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Overview extends CI_Controller 
{

	public function index()
	{
		$data = array();
		$data['competition_is_started'] = $this->competition->init(3)->is_started(); // keep the init parameter at the current season
		if ($data['competition_is_started'])
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
		}
		else
		{
			// fetch all players
			$sql = "select p.player_id
					,      p.name 
					,      ifnull(t.name, '-')  team
					from   players  p
					left join teams t on p.team_id = t.team_id
					order by p.team_id, p.player_id";
			$query = $this->db->query($sql);
			$data['players'] = $query->result_array();
		}
	    
	    $this->load->view('headerView');
		$this->load->view('overviewView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/overview.php */