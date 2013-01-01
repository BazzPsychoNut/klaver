<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'forms/ChangePasswordForm.php';
require_once APPPATH.'forms/ChangeAccountForm.php';
// require_once APPPATH.'forms/ChangeTeamForm.php';

class Account extends CI_Controller 
{

	public function index()
	{
		if ($this->session->userdata('user_logged_in') === false && $this->session->userdata('user_has_default_password') !== true)
			redirect('login');
		
		// fetch account data
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
		$data = $query->row_array();
		
		$this->load->view('headerView');
		$this->load->view('accountView', $data);  
		
		$this->change_password();
		$this->change_account();
		$this->change_team();
		
		$this->load->view('footerView');
	}
	
	
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
		$this->load->view('changePasswordView', $data);
	}
	
	
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
					// TODO add activatie stuff
					$set['']
				}
				
				if (empty($set)) // should be impossible, but doublecheck
					throw new Exception('Er is niets ingevoerd om te wijzigen.');
				
				$where = array('player_id' => $this->session->userdata('user_id'));
				if (! $this->db->update('players', $set, $where))
					throw new Exception('Error bij wijzigen account gegevens. '.$this->db->_error_message());
				
				// TODO bij wijzigen email moet nieuwe activatiemail verstuurd worden
		
				$data['feedback'] = success('Je gegevens zijn nu veranderd.'); // TODO de gegevens die getoond worden zijn nog niet ververst.
		
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		
		$data['form'] = $form;
		$this->load->view('changeAccountView', $data);
	}
	
	
	public function change_team()
	{
		// only show this if user is logged in
		if ($this->session->userdata('user_logged_in') === false)
			return;
		
		// TODO create form, view and handle post here
	}
	
}

