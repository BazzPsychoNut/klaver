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
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('De aanmelding is mislukt, omdat niet alle velden goed zijn ingevuld.');
				
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
						'confirmation' => sha1($confirmation),
						);
				$this->db->insert('players', $set);
				if ($this->db->affected_rows() == 0)
					throw new Exception('Error bij opslaan nieuwe speler. '.$this->db->_error_message());
				
				$p1_id = $this->db->insert_id();
	
				// send email confirmation mail
				$email_data = array(
						'name'      => $p1['name']->getPosted(),
						'password' 	=> $p1['password']->getPosted(),
						'email' 	=> $p1['email']->getPosted(),
						'activationLink' => $this->config->base_url().'signup/confirm/'.$confirmation,
						'teamName'  => $form->team->getPosted(),
						'maatName'  => $p2['name']->getPosted(),
						);
				$email_message = $this->load->view('welcomeEmail', $email_data, true);
				if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
				{
					echo '<div style="padding:30px; border:10px solid #888;">'.$email_message.'</div>'."\n";
				}
				else
				{
					$this->email->initialize(array('mailtype' => 'html'));
					$this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
					$this->email->to($p1['email']->getPosted());
					$this->email->subject('Welkom bij de Fonteinkerk klaverjascompetitie');
					$this->email->message($email_message);
					if (! $this->email->send())
						throw new Exception('Error bij verzenden email met activatielink voor nieuwe speler '.$p1['name']->getPosted().'.');
				}
				
					
				// create player2
				if ($p2['name']->isPosted() && $p2['email']->isPosted())
				{
					$personal_salt = $form->generateSalt();
					$password = sha1('Welkom01' . $this->config->item('system_salt') . $personal_salt);
					$confirmation = $form->generateSalt(10); // string of 10 random chars that will be used as email confirmation and account activation
					$set = array(
							'name' 		=> $p2['name']->getPosted(),
							'password' 	=> $password.' '.$personal_salt,
							'email' 	=> $p2['email']->getPosted(),
							'level'		=> 0,
							'team_id'	=> $teamId,
							'confirmation' => sha1($confirmation),
							);
					if (! $this->db->insert('players', $set))
						throw new Exception('Error bij opslaan tweede speler. '.$this->db->_error_message());
					$p2_id = $this->db->insert_id();
					
					// send signup mail
					$email_data = array(
							'name'      => $p2['name']->getPosted(),
							'email' 	=> $p2['email']->getPosted(),
							'activationLink' => $this->config->base_url().'signup/confirm/'.$confirmation,
							'teamName'  => $form->team->getPosted(),
							'maatName'  => $p1['name']->getPosted(),
							);
					$email_message = $this->load->view('signupMaatjeEmail', $email_data, true);
					if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
					{
						echo '<div style="padding:30px; border:10px solid #888;">'.$email_message.'</div>'."\n";
					}
					else
					{
						$this->email->initialize(array('mailtype' => 'html'));
						$this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
						$this->email->to($p2['email']->getPosted());
						$this->email->subject('Welkom bij de Fonteinkerk klaverjascompetitie');
						$this->email->message($email_message);
						if (! $this->email->send())
							throw new Exception('Error bij verzenden email met activatielink voor 2e speler '.$p2['name']->getPosted().'.');
					}
				}
				// OR join player1 to selected maat
				elseif (! empty($form->singles) && $form->singles->isPosted())
				{
					$p2_id = $form->singles->getPosted();
					
					if (empty($teamId))
					{
						// check if single has a team (single can be only a single if he has a one man team or no team), else create a new team
						$sql = "select team_id from players where player_id = ?";
						$query = $this->db->query($sql, array($form->singles->getPosted()));
						$row = $query->row_array();
						if (! empty($row['team_id']))
						{
							$teamId = $row['team_id'];
						}
						else
						{
							// create teamName as the two firstnames
							$sql = "insert into teams (name) 
									select concat(min(name), ' en ', max(name)) as name
									from   players
									where  player_id in (?, ?)";
							$this->db->query($sql, array($p1_id, $p2_id));
							$teamId = $this->db->insert_id();
						} 
					}
					
					if (! $this->db->update('players', array('team_id' => $teamId), "player_id in ($p1_id, $p2_id)"))
						throw new Exception('Error bij opslaan van het team voor spelers met id\'s '.$p1_id.' en '.$p2_id.'. '.$this->db->_error_message());
				}
				
				$data['feedback'] = success('De aanmelding is succesvol afgerond. Een e-mail is verstuurd met een link om het account te activeren.');
				
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		
		$this->load->view('headerView');
		$this->load->view('signupView', $data);
		$this->load->view('footerView');
	}
	
	
	/**
	 * handle account activation and email confirmation link
	 */
	public function confirm()
	{
		$data['feedback'] = error('Kan het account niet activeren.');
		
		try
		{
			// voorbeeld: http://localhost/klaver/signup/confirm/7A18KU9OeZ 
			$confirmation = $this->uri->segment(3);
			if (! empty($confirmation))
			{
				$sql = "select player_id from players where confirmation = ?";
				$query = $this->db->query($sql, array(sha1($confirmation)));
				$row = $query->row_array();
				if (empty($row['player_id']))
					throw new Exception('De activatie is ongeldig. Wellicht kun je al inloggen. Neem anders contact op met de organisatie.');
					
				if (! $this->db->update('players', array('level' => 1, 'confirmation' => null), "player_id = ".$row['player_id']))
					throw new Exception('Error bij activeren account. '.$this->db->_error_message());
				else
					$data['feedback'] = success('Account succesvol geactiveerd. Je kunt nu inloggen.');
			}
		}
		catch (Exception $e)
		{
			$data['feedback'] = error($e->getMessage());
		}
		
		$this->load->view('headerView');
		$this->load->view('signupConfirmView', $data);
		$this->load->view('footerView');
	}
}

