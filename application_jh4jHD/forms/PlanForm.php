<?php

require_once APPPATH.'libraries/Form.php';

class PlanForm extends Form 
{
	public 	$opponent_team,
			$load_match_planning,
			$availabilities = array(),
			$submit;
	
	protected $dates = array(),
			  $players = array();
	
	
	
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
		
		// planning consists of every day in the next 3 weeks as columns
		// and all 4 players as rows
		// Users can select yes, maybe or no by clicking on the images (copied from afspreken.nl)
		// I will use hidden input fields to store the values
		if ($this->opponent_team->isPosted())
		{
			$this->create_dates();
			$this->fetch_players($this->session->userdata('user_team_id'), $this->opponent_team->getPosted());
			
			foreach ($this->players as $player_id => $player_name)
			{
				foreach ($this->dates as $date => $date_fields)
				{
					// fill $this->availabilities
					$this->availabilities[$player_id][$date] = new HiddenInput('availability_'.$player_id.'_'.$date);
				}
			}
		}
		
		// load submit
		$this->load_match_planning = new SubmitButton('load_match_planning', 'Partij afspraak openen');
		$this->load_match_planning->setLabel('&nbsp;')->addStyle('margin-top:10px');
		
		// submit
		$this->submit = new SubmitButton('save_availability', 'Bewaren');
		$this->submit->setLabel('&nbsp;')->addStyle('margin-top:20px');
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
		
		if ($this->opponent_team->isPosted())
		{
			$data = array('players' => $this->players, 'dates' => $this->dates, 'availabilities' => $this->availabilities);
			$output .= $this->load->view('planFormView.php', $data, true);
			
			$output .= $this->submit->render().BRCLR;
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
		
		$validate = new Validate();
		
		// TODO build validations
		
		
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
	
}

