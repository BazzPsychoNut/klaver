<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'forms/InputMatchForm.php';

class Input_match extends CI_Controller 
{

	public function index()
	{
	    $data = array();
	    
		$form = new InputMatchForm();
		
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het invoeren is mislukt, omdat niet alle velden goed zijn ingevuld.');
				
				// TODO eerst verzamelen in $set, dan pas bepalen of het db->insert of db->update moet worden
				// TODO Alleen degene die een partij ingevoerd heeft kan de data van de partij later aanpassen.
				dump($_POST);
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
	    
		
		$data['form'] = $form;
		
	    $this->load->view('headerView');
		$this->load->view('inputMatchView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */