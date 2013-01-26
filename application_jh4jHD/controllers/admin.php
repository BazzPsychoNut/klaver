<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'forms/PlaceTeamForm.php';

class Admin extends CI_Controller 
{

	public function index()
	{
	    if ($this->session->userdata('user_level') != 2)
	        redirect('home');
	    
		$data = array();
		$data['competition_is_started'] = $this->competition->init(3)->is_started(); // keep the init parameter at the current season
		
		// put teams in a poule / move teams to a poule
		$data['place_team_view'] = $this->place_team();
		
		// TODO start competition
	    
	    // TODO join players into a team
		
		$this->load->view('headerView');
		$this->load->view('adminView', $data);
		$this->load->view('footerView');
	}
	
	
	protected function place_team()
	{
	    if ($this->session->userdata('user_level') != 2)
	        return false;
	    
	    $data = array();
	    
	    // create the form
	    $form = new PlaceTeamForm();
	    	    
	    // handle post of form
	    if ($form->isPosted())
	    {
	        try
	        {
	            if (! $form->validate())
	                throw new Exception('Het team in een poule plaatsen is mislukt, omdat niet alle velden goed zijn ingevuld.');
	    
	            // TODO handle post
	    
	            $data['feedback'] = success('Je wachtwoord is nu veranderd. Er is een e-mail verzonden ter bevestiging.');
	    
	        }
	        catch (Exception $e)
	        {
	            $data['feedback'] = error($e->getMessage());
	        }
	    }
	    
	    $data['form'] = $form;
	    return $this->load->view('place_team_view', $data, true);
	}
}

