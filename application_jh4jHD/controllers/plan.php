<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends CI_Controller 
{
    
    protected $teams = array(),
              $match_details = array(),
              $players = array(),
              $comments = array();
    

	public function index()
	{
		$data = array();
		
		require_once APPPATH.'forms/PlanForm.php';
		$form = new PlanForm();
		
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het invoeren is mislukt, omdat niet alle velden goed zijn ingevuld.');
		
				// fetch teams details
				$opponent_team = $form->opponent_team->isPosted() ? $form->opponent_team->getPosted() : $form->opponent_team_hidden->getPosted();
				$this->fetch_teams($this->session->userdata('user_team_id'), $opponent_team);
				
				// fetch players details
				$this->fetch_players();
				
				// fetch match details
				$this->fetch_match();
				
				// often used variables
				$match_id = $this->match_details['match_id'];
				$user_id  = $this->session->userdata('user_id');
				
				$this->fetch_comments($match_id);
				
				// as a business rule you can only pick a date when you already filled in your availability
				if ($this->is_new_picked_date_posted($form->picked_date, $match_id)) 
				{
					// store the posted picked date.
					$picked_date = $form->picked_date->getPosted();
					$result = $this->db->update('matches', array('picked_date' => $picked_date), array('match_id' => $match_id));
					if (! $result)
						throw new Exception('Error bij vastleggen van gekozen datum. - '.$this->db->_error_message());
					
					// create pleasant date format by using numeric array of days of the week that corresponds to date('w')
					$days = array('zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag');
					$picked_date_formatted = $days[date('w', strtotime($picked_date))].' '.date('d-m-Y', strtotime($picked_date));
					
					/**
					 * send e-mail to all 4 players
					 */
					$email_data = array(
					            'pick_user_name' 	=> $this->session->userdata('user_name'),
					            'picked_date'       => $picked_date_formatted,
					            'teams'             => $this->teams,
					            'comments'          => $this->comments,
					            );
					if ($_SERVER['SERVER_NAME'] == 'localhost') // working locally
					{
					    $email_data['name'] = 'Bas de Ruiter';
					    $email_message = $this->load->view('match_picked_email', $email_data, true);
					    
					    echo '<div style="padding:30px; border:10px solid #888;">'.$email_message.'</div>'."\n";
					}
					else
					{
					    foreach ($this->players as $player)
					    {
					        $email_data['name'] = $player['name'];
					        $email_message = $this->load->view('match_picked_email', $email_data, true);
					        
    					    $this->email->initialize(array('mailtype' => 'html'));
    					    $this->email->from('klaverjascompetitie@fonteinkerkhaarlem.nl', 'Klaverjascompetitie');
    					    $this->email->to($player['email']);
    					    $this->email->subject('Datum geprikt voor klaverjaspartij: '.$picked_date_formatted);
    					    $this->email->message($email_message);
    					    if (! $this->email->send())
    					        throw new Exception('Error bij verzenden email geprikte datum bevestiging naar '.$player['name'].'.');
					    }
					}
					
					$data['feedback'] = success('De afspraak is vastgelegd op '.$picked_date_formatted.'. Een e-mail is verstuurd naar alle deelnemers.');
				}
				else
				{
					/**
					 * save user availability
					 */
					// delete old availability
					$where = array('match_id' => $match_id, 'player_id' => $user_id);
					$this->db->delete('match_planning', $where);
					
					// insert posted availability
					$set = array();
					foreach ($form->availabilities as $date => $input)
					{
						$set[] = array(
								'match_id' => $match_id,
								'player_id' => $user_id, // don't trust posted player_id. Always take the session's user.
								'plan_date' => $date,
								'availability' => $input->getPosted(),
								);
					}
					$this->db->insert_batch('match_planning', $set);
					if ($this->db->affected_rows() != 21)
						throw new Exception('Error bij opslaan van beschikbaarheid. - '.$this->db->_error_message());
					
					$data['feedback'] = success('Bedankt! De beschikbaarheid is opgeslagen.');
				}
				
				$data['match_id'] = $match_id;
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		 
		
		$data['form'] = $form;
		
		$this->load->view('headerView');
		$this->load->view('planView', $data);
		$this->load->view('footerView');
	}
	
	/**
	 * this page is used by an ajax script on /plan to show the comments at the bottom of that page
	 */
	public function comment()
	{
	    $data = array();
	    
	    require_once APPPATH.'forms/CommentsForm.php';
	    $form = new CommentsForm();
	    
	    if ($form->isPosted())
	    {
	        try
	        {
	            if (! $form->validate())
	                throw new Exception('Het invoeren is mislukt, omdat niet alle velden goed zijn ingevuld.');
	            
	            // TODO handle post
            }
            catch (Exception $e)
            {
                $data['feedback'] = error($e->getMessage());
            }
        }
        
        $match_id = $this->uri->segment(3);
        
        // fetch comments
        $this->fetch_comments($match_id);
	    
	    $data['form'] = $form;
	    $data['comments'] = $this->comments;
	    
	    $this->load->view('commentsView', $data);
	}
	
	
	/**
	 * fetch team details
	 * @param int $id_team1
	 * @param int $id_team2
	 * @throws Exception
	 */
	protected function fetch_teams($id_team1, $id_team2)
	{
	    // fetch id, name, wins, losses
	    $sql = "select team_id, name, wins, losses from teams where team_id in (?, ?) order by team_id";
	    $query = $this->db->query($sql, array($id_team1, $id_team2));
	    $teams = $query->result_array();
	    if (empty($teams))
	        throw new Exception('Error bij ophalen team gegevens. - '.$this->db->_error_message());
	    
	    foreach ($teams as $i => $row)
	        $this->teams[$i+1] = $row;
	}
	
	/**
	 * fetch match details
	 * @param int $id_team1
	 * @param int $id_team2
	 * @throws Exception
	 */
	protected function fetch_match()
	{
	    if (empty($this->teams))
	        return;
	    
		$sql = "select m.match_id
        	    ,      m.id_team1
        	    ,      m.id_team2
        	    from   matches  m
        	    where  ? in (m.id_team1, m.id_team2)
        	    and    ? in (m.id_team1, m.id_team2)";
		$query = $this->db->query($sql, array($this->teams[1]['team_id'], $this->teams[2]['team_id']));
		$match = $query->row_array();
		if (empty($match)) // this is impossible without changing the post values
			throw new Exception('Kan geen ingeroosterde partij vinden tussen jullie en dat team. - '.$this->db->_error_message());
		 
		$this->match_details = $match;
	}
	
	/**
	 * fetch all the players in the 2 teams
	 * The player names will also be added to $this->teams
	 * @param int $team1
	 * @param int $team2
	 * @throws Exception
	 */
	protected function fetch_players()
	{
	    if (empty($this->teams))
	        return;
	    
	    $sql = "select p.player_id
        	    ,      p.name
        	    ,      p.email
        	    ,      p.team_id
        	    from   players p
        	    where  p.team_id in (?, ?)
        	    order by team_id, player_id"; 
	    $query = $this->db->query($sql, array($this->teams[1]['team_id'], $this->teams[2]['team_id']));
	    $players = $query->result_array();
	    if (empty($players))
	        throw new Exception('Error bij ophalen spelers gegevens. - '.$this->db->_error_message());
	    
	    // add players to the teams array
	    foreach ($players as $row)
	    {
	        if ($this->teams[1]['team_id'] == $row['team_id'])
	            $this->teams[1]['players'][] = $row['name'];
	        else
	            $this->teams[2]['players'][] = $row['name'];
	    }
	         
	    $this->players = $players;
	}
	
	/**
	 * fetch comments belonging to given match
	 * @param int $match_id
	 * @throws Exception
	 */
	protected function fetch_comments($match_id)
	{
	    $sql = "select p.name
                ,      mpc.comment
                ,      date_format(mpc.create_date, '%d-%m-%Y %H:%i') as create_date
                from   match_planning_comments mpc
                join   players p on mpc.player_id = p.player_id
                where  mpc.match_id = ? 
                order by mpc.create_date desc";
	    $query = $this->db->query($sql, array($match_id));
	    
	    // no need to check if result is not empty, because it will be empty in most cases
	    $this->comments = $query->result_array();
	}
	
	/**
	 * check if a posted picked date is in fact a new one, or that the user just changed his availability
	 * @param Input $picked_date
	 * @throws Exception
	 * @return boolean
	 */
	protected function is_new_picked_date_posted(Input $picked_date, $match_id)
	{
		if (! $picked_date->isPosted())
			return false;
		
		$sql = "select count(*) amount from matches where picked_date = ? and match_id = ?";
		$query = $this->db->query($sql, array($picked_date->getPosted(), $match_id));
		$row = $query->row_array();
		
		// affected rows will be zero if same date is picked
		return $row['amount'] == 0;
	}
	
}

