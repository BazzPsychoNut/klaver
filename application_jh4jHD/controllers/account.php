<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'forms/ChangePasswordForm.php';
require_once APPPATH.'forms/ChangeAccountForm.php';
require_once APPPATH.'forms/ChangeTeamForm.php';

class Account extends CI_Controller 
{

	public function index()
	{
		// redirect user to login page if he is not logged in
		if ($this->session->userdata('user_logged_in') === false && $this->session->userdata('user_has_default_password') !== true)
			redirect('login');
		
		// first make the changes
		$data['change_password_view'] = $this->change_password();
		$data['change_account_view'] = $this->change_account();
		$data['change_team_view'] = $this->change_team();
		
		// fetch account data after possible changes
		$sql = "select p.name
				,      p.email
				,      t.name   team_name
				,      p2.name  maat_name
				from   players    p
				left join teams   t  on  p.team_id = t.team_id
				left join players p2 on  p.team_id = p2.team_id
				                     and p.player_id != p2.player_id
				where  p.player_id = ?";
		$query = $this->db->query($sql, array($this->session->userdata('user_id')));
		$data += $query->row_array();
		
		// redirect if user is no longer logged in. (logged out by changing email for example) 
		if ($this->session->userdata('user_logged_in') === false && $this->session->userdata('user_has_default_password') !== true)
			redirect('login/index/new_email');
		
		$this->load->view('headerView');
		$this->load->view('accountView', $data);
		$this->load->view('footerView');
	}
	
	/**
	 * render the change password part of the page
	 * @throws Exception
	 * @return view
	 */
	public function change_password()
	{
		$data = array();
		
		// create the form
		$form = new ChangePasswordForm('ChangePasswordForm');
		
		// handle post of form
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het wachtwoord wijzigen is mislukt, omdat niet alle velden goed zijn ingevuld.');
		
				// fetch player info 
				$sql = "select * from players where player_id = ?";
				$query = $this->db->query($sql, array($this->session->userdata('user_id')));
				$player = $query->row_array();
				
				// check old password
				list($password, $personal_salt) = explode(' ', $player['password']);
				if (sha1($form->old_password->getPosted() . $this->config->item('system_salt') . $personal_salt) != $password)
					throw new Exception('Het oude wachtwoord is incorrect.');
		
				// store new password
				$personal_salt = $form->generateSalt();
				$password = sha1($form->password->getPosted() . $this->config->item('system_salt') . $personal_salt);
				$set = array('password' => $password.' '.$personal_salt);
				$where = array('player_id' => $this->session->userdata('user_id'));
				if (! $this->db->update('players', $set, $where))
					throw new Exception('Error bij opslaan nieuwe wachtwoord. '.$this->db->_error_message());
				
				// if user had 'Welkom01' and now created his own password, reset the corresponding session variable
				if ($this->session->userdata('user_has_default_password'))
				{
					$this->session->set_userdata(array('user_logged_in' => true, 'user_has_default_password' => false));
				}
				
				// send email
				$email_data = array(
						'name'      => $player['name'],
						'password' 	=> $form->password->getPosted(),
						'email' 	=> $player['email'],
				);
				$email_message = $this->load->view('changePasswordEmail', $email_data, true);
				if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
				{
					echo '<div style="padding:30px; border:10px solid #888;">'.$email_message.'</div>'."\n";
				}
				else
				{
					$this->email->initialize(array('mailtype' => 'html'));
					$this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
					$this->email->to($player['email']);
					$this->email->subject('Wachtwoord gewijzigd voor de Fonteinkerk klaverjascompetitie');
					$this->email->message($email_message);
					if (! $this->email->send())
						throw new Exception('Error bij verzenden email met bevestiging van wachtwoord wijziging.');
				}
				
				$data['feedback'] = success('Je wachtwoord is nu veranderd. Er is een e-mail verzonden ter bevestiging.');
		
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		return $this->load->view('changePasswordView', $data, true);
	}
	
	/**
	 * render the change account part of the page
	 * @throws Exception
	 * @return view
	 */
	public function change_account()
	{
		// only show this if user is logged in
		if ($this->session->userdata('user_logged_in') === false)
			return;
		
		$data = array();
		
		// create the form
		$form = new ChangeAccountForm('ChangeAccountForm');
		
		// handle post of form
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het wijzigen van je gegevens is mislukt, omdat niet alle velden goed zijn ingevuld.');
		
				// store new data
				$set = array();
				if ($form->playername->isPosted())
					$set['name'] = $form->playername->getPosted();
				if ($form->email->isPosted())
				{
					$set['email'] = $form->email->getPosted();
					// reset account for email confirmation
					$confirmation = $form->generateSalt(10);
					$set['confirmation'] = sha1($confirmation); // string of 10 random chars that will be used as email confirmation and account activation
					$set['level'] = 0;
				}
				
				if (empty($set)) // should be impossible, but doublecheck
					throw new Exception('Er is niets ingevoerd om te wijzigen.');
				
				$where = array('player_id' => $this->session->userdata('user_id'));
				if (! $this->db->update('players', $set, $where))
					throw new Exception('Error bij wijzigen account gegevens. '.$this->db->_error_message());
				
				// log de gebruiker uit tot nieuwe email is geconfirmeerd
				$this->session->set_userdata('user_logged_in', false);
				
				// bij wijzigen email moet nieuwe activatiemail verstuurd worden
				if ($form->email->isPosted())
				{
				    $sql = "select name, email, confirmation from players where player_id = ?";
				    $query = $this->db->query($sql, array($this->session->userdata('user_id')));
				    $email_data = $query->row_array();
				    $email_data['activationLink'] = $this->config->base_url().'signup/confirm/'.$email_data['confirmation'];
				    $email_message = $this->load->view('changeEmailEmail', $email_data, true);
				    if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
				    {
				        echo '<div style="padding:30px; border:10px solid #888;">'.$email_message.'</div>'."\n";
				    }
				    else
				    {
				        $this->email->initialize(array('mailtype' => 'html'));
				        $this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
				        $this->email->to($email_data['email']);
				        $this->email->subject('Welkom bij de Fonteinkerk klaverjascompetitie');
				        $this->email->message($email_message);
				        if (! $this->email->send())
				            throw new Exception('Error bij verzenden email met activatielink voor nieuw email adres van '.$email_data['name'].'.');
				    }
				}
		
				$data['feedback'] = success('Je gegevens zijn nu veranderd.');
		
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		return $this->load->view('changeAccountView', $data, true);
	}
	
	/**
	 * render the change team name part of the page
	 * @throws Exception
	 * @return view
	 */
	public function change_team()
	{
		// only show this if user is logged in
		if ($this->session->userdata('user_logged_in') === false)
			return;
		
		$data = array();
		
		// create the form
		$form = new ChangeTeamForm('ChangeTeamForm');
		
		// handle post of form
		if ($form->isPosted())
		{
		    try
		    {
		        if (! $form->validate())
		            throw new Exception('Het wijzigen van je gegevens is mislukt, omdat niet alle velden goed zijn ingevuld.');
		
		        // store new data
		        // check if we already have a team record
		        $sql = "select t.team_id 
	        			from   teams   t
		        		join   players p on t.team_id = p.team_id
		        		where  p.player_id = ?";
		        $query = $this->db->query($sql, array($this->session->userdata('user_id')));
		        // insert
		        if ($query->num_rows() == 0)
		        {
		        	$this->db->insert('teams', array('name' => $form->team->getPosted()));
		        	if ($this->db->affected_rows() == 0)
		        		throw new Exception('Error bij opslaan teamnaam. - '.$this->db->_error_message());
		        	
		        	$data['feedback'] = success('De teamnaam is nu opgeslagen.');
		        }
		        else
		        {
			        // update
			        $sql = "update teams set name = ? where team_id = ?";
			        $binds = array($form->team->getPosted(), $this->session->userdata('user_team_id'));
			        $this->db->query($sql, $binds);
			        if ($this->db->affected_rows() == 0)
			            throw new Exception('Error bij wijzigen teamnaam. - '.$this->db->_error_message());
			        
			        $data['feedback'] = success('De teamnaam is nu veranderd.');
		        }
		
		    }
		    catch (Exception $e)
		    {
		        $data['feedback'] = error($e->getMessage());
		    }
		}
		
		$data['form'] = $form;
		return $this->load->view('changeTeamView', $data, true);
	}
	
}

