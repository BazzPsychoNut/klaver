<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Signup extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		// create the form
		require_once APPPATH.'forms/SignupForm.php';
		$form = new SignupForm('signup_form');
		
		// handle post of form
		if ($form->isPosted() && $form->validate())
		{
			// create variables for easier usage
			$p1 = $form->player1;
			$p2 = $form->player2;
			
			// create team
			$teamId = null;
			if ($form->team->isPosted())
			{
				$this->db->insert('teams', array('name' => $form->team->getPosted()));
				$teamId = $this->db->insert_id();
			}
			
			// create player1
			// password is stored as concat of password, system_salt, personal_salt
			$personal_salt = $form->generateSalt();
			$password = sha1($p1['password']->getPosted() . $this->config->item('system_salt') . $personal_salt);
			$confirmation = $form->generateSalt(10); // string of 10 random chars that will be used as email confirmation and account activation
			$set = array(
					'name' 		=> $p1['name']->getPosted(),
					'password' 	=> $password.' '.$personal_salt,
					'email' 	=> $p1['email']->getPosted(),
					'level'		=> 0,
					'team_id'	=> $teamId,
					'confirmation' => $confirmation,
					);
			$this->db->insert('players', $set);

			// send email confirmation mail
			$email_data = array(
					'name'      => $p1['name']->getPosted(),
					'password' 	=> $password,
					'email' 	=> $p1['email']->getPosted(),
					'activationLink' => $this->config->base_url().'signup/confirm/'.$confirmation,
					'teamName'  => $form->team->getPosted(),
					'maatName'  => $p2['name']->getPosted(),
					);
			$this->email->initialize(array('mailtype' => 'html'));
			$this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
			$this->email->to($p1['email']->getPosted());
			$this->email->subject('Welkom bij de Fonteinkerk klaverjascompetitie');
			$this->email->message($this->load->view('welcomeEmail', $email_data, true));
			$this->email->send(); // TODO think of error handling/logging
			
				
			// create player2
			if ($p2['name']->isPosted() && $p2['email']->isPosted())
			{
				$personal_salt = $form->generateSalt();
				$password = sha1('Welkom01' . $this->config->item('system_salt') . $personal_salt);
				$set = array(
						'name' 		=> $p2['name']->getPosted(),
						'password' 	=> $password.' '.$personal_salt,
						'email' 	=> $p2['email']->getPosted(),
						'level'		=> 0,
						'team_id'	=> $teamId,
						);
				$this->db->insert('players', $set);
				
				// TODO send signup mail
				
			}
			
				
				
		}
		
		$data['form'] = $form;
		
		$this->load->view('headerView');
		$this->load->view('signupView', $data);
		$this->load->view('footerView');
	}
}

/* Location: ./application/controllers/home.php */