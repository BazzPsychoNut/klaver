<?php

require_once APPPATH.'libraries/Form.php';
require_once APPPATH.'models/planning_img.php';

class PlanForm extends Form 
{
	public 	$opponent_team,
			$load_match_planning,
			$availabilities = array(),
			$cancel,
			$submit;
	
	protected $dates = array(),
			  $players = array(),
			  $images = array(),
			  $best_options = array();
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		$this->session = $CI->session;
		$this->load = $CI->load;
		
		$this->name = ! empty($name) ? $name : __CLASS__;
		
		// select all possible opponents (including those already entered for now at least)
		$sql = "select t.team_id
				,      concat(t.name, ' (', min(p.name), ' en ', max(p.name), ')') name
				from   teams t
				join   players p on t.team_id = p.team_id
				where  poule_id = ?
				and    t.team_id != ?
				group by t.team_id, t.name";
		$query = $this->db->query($sql, array($CI->session->userdata('user_poule_id'), $CI->session->userdata('user_team_id')));
		$options = array();
		foreach ($query->result_array() as $row) {
			$options[$row['team_id']] = $row['name'];
		}
		$this->opponent_team = new Dropdown('opponent_team');
		$this->opponent_team->setLabel('Tegenstanders')->appendOptions($options);
		
		// load submit
		$this->load_match_planning = new SubmitButton('load_match_planning', 'Partij afspraak openen');
		$this->load_match_planning->setLabel('&nbsp;')->addStyle('margin-top:10px');
		
		// cancel
		$this->cancel = new SubmitButton('cancel', 'Annuleren');
		$this->cancel->addStyle('margin:20px')->addClass('cancel');
		
		// submit
		$this->submit = new SubmitButton('save_availability', 'Bewaren');
		$this->submit->setLabel('&nbsp;')->addStyle('margin-top:20px');
			
		// planning consists of every day in the next 3 weeks as columns
		// and all 4 players as rows
		// Users can select yes, maybe or no by clicking on the images (copied from afspreken.nl)
		// I will use hidden input fields to store the values
		if ($this->load_match_planning->isPosted() || $this->submit->isPosted())
		{
			$this->create_dates();
			$this->fetch_players($this->session->userdata('user_team_id'), $this->opponent_team->getPosted());
			
			foreach ($this->dates as $date => $date_fields)
			{
				// create $this->availabilities hidden inputs
				$this->availabilities[$date] = new HiddenInput('availability_'.$date);
				
				// create image objects
				foreach ($this->players as $player_id => $player_name)
				{
					// create the 3 images (default grey)
					foreach (array(1,2,3) as $type) 
						$this->images[$player_id][$date][$type] = new Planning_img($type, $player_id);
						
					// set one image active ( = _a ) if the hidden input field was posted
					if ($player_id == $this->session->userdata('user_id') && $this->availabilities[$date]->isPosted())
					{
						$posted_type = $this->availabilities[$date]->getPosted();
						$this->images[$player_id][$date][$posted_type]->set_active();
					}
				}
			}
		}
		
		// load match details from database
		if ($this->load_match_planning->isPosted())
			$this->set_availability($this->session->userdata('user_team_id'), $this->opponent_team->getPosted());
	}
	
	/**
	 * render the form
	 * @return html 
	 */
	public function render() 
	{
		// render the form with the input fields
		$output = '<form name="'.$this->name.'" method="'.$this->method.'" action="'.$this->action.'" enctype="'.$this->enctype.'">'."\n";
		
		$output .= $this->opponent_team->render().BRCLR;
		
		if ($this->load_match_planning->isPosted() || $this->submit->isPosted())
		{
			// TODO opponent_team dropdown should be disabled, but we need it posted
			//$this->opponent_team->setDisabled();
			
			$data = array(
					'players' 		 => $this->players, 
					'dates' 		 => $this->dates, 
					'availabilities' => $this->availabilities,
					'images' 		 => $this->images,
					);
			$output .= $this->load->view('planFormView.php', $data, true);
			
			$output .= $this->submit->render();
			$output .= $this->cancel->render().BRCLR;
		}
		else
		{
			$output .= $this->load_match_planning->render().BRCLR;
		}
		
		$output .= "</form>\n";
		
		return $output;
	}
	
	/**
	 * validate the input
	 * @return boolean
	 */
	public function validate()
	{
		if (! $this->isPosted())
			return null;
		
		//$validate = new Validate();
		
		// all dates should be filled in
		foreach ($this->availabilities as $date => $input)
		{
			if (! $input->isPosted())
				$this->invalidate($input, 'Verplicht in te vullen');
		}
		
		return $this->isValid;
	}
	
	/**
	 * Is the form posted?
	 * @return boolean
	 */
	public function isPosted()
	{
		return $this->submit->isPosted();
	}
	
	/**
	 * fetch all the players in the 2 teams
	 * @param int $team1
	 * @param int $team2
	 */
	protected function fetch_players($team1, $team2)
	{
		$sql = "select p.player_id
				,      p.name
				from   players p
				where  p.team_id in (?, ?)
				order by team_id, player_id"; // with this order the teams will always be shown together
		$query = $this->db->query($sql, array($team1, $team2));
		foreach ($query->result_array() as $row)
			$this->players[$row['player_id']] = $row['name'];
	}
	
	/**
	 * create array of dates for the next 3 weeks
	 */
	protected function create_dates()
	{
		// numeric array of days of the week that corresponds to date('w')
		$days = array('Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag');
		// numeric array of months that corresponds to date('n')
		$months = array(1 => 'januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
		
		for ($i=1; $i<=21; $i++)
		{
			$time = strtotime("+$i days");
			$this->dates[date('Ymd', $time)] = array(
					'day' => $days[(date('w', $time))],                // Zondag 
					'date' => date('d', $time).' '.$months[date('n', $time)]  // 13 januari
					); 
		}
	}
	
	/**
	 * set availability from database 
	 */
	protected function set_availability($team1, $team2)
	{
		// fetch availability 
		$sql = "select mp.player_id
				,      date_format(mp.plan_date, '%Y%m%d') plan_date
				,      mp.availability
				from   match_planning mp
				join   matches        m  on mp.match_id = m.match_id
				where  ? in (m.id_team1, m.id_team2)
				and    ? in (m.id_team1, m.id_team2)
				order by player_id, plan_date";
		$query = $this->db->query($sql, array($team1, $team2));
		foreach ($query->result_array() as $row) 
		{
			if ($row['player_id'] == $this->session->userdata('user_id'))
				$this->images[$row['player_id']][$row['plan_date']][$row['availability']]->set_active();
			else
				$this->images[$row['player_id']][$row['plan_date']][$row['availability']]->set_someone_else_active();
			
			// TODO set $best_options
			
		}
	}
	
}

