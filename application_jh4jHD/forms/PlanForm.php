<?php

require_once APPPATH.'libraries/Form.php';
require_once APPPATH.'models/planning_img.php';

class PlanForm extends Form 
{
	public 	$opponent_team,
			$opponent_team_hidden, // hidden input field for when we have the opponent team dropdown disabled
			$load_match_planning,
			$availabilities = array(),
			$picked_date,
			$cancel,
			$submit;
	
	protected $match_id,
			  $dates = array(),
			  $players = array(),
			  $images = array(),
			  $best_options = array(),
			  $date_is_pickable = false,
			  $previously_picked_date;
	
	
	
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
		
		$this->opponent_team_hidden = new HiddenInput('opponent_team_hidden');
		
		$this->picked_date = new HiddenInput('picked_date');
		
		// load submit
		$this->load_match_planning = new SubmitButton('load_match_planning', 'Partij afspraak openen');
		$this->load_match_planning->setLabel('&nbsp;')->addStyle('margin-top:10px');
		
		// cancel
		$this->cancel = new SubmitButton('cancel', 'Annuleren');
		$this->cancel->addStyle('margin:20px')->addClass('cancel');
		
		// submit
		$this->submit = new SubmitButton('save_availability', 'Bewaren');
		$this->submit->setLabel('&nbsp;')->addStyle('margin-top:20px');
			
		// planning consists of every day in the next 3 weeks as rows
		// and all 4 players as columns
		// Users can select yes, maybe or no by clicking on the images (copied from afspreken.nl)
		// I will use hidden input fields to store the values
		if ($this->load_match_planning->isPosted() || $this->submit->isPosted())
		{
			$this->create_dates();
			$opponent_team = $this->opponent_team->isPosted() ? $this->opponent_team->getPosted() : $this->opponent_team_hidden->getPosted();
			$this->fetch_players($this->session->userdata('user_team_id'), $opponent_team);
			
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
		
			// set match_id of this match
			$this->fetch_match_id($this->session->userdata('user_team_id'), $opponent_team);
			
			// fetch the best options (= the dates with the most yes'es (or maybes))
			$this->fetch_best_options();
			
		}
	}
	
	/**
	 * render the form
	 * @return html 
	 */
	public function render() 
	{
		/**
		 * do stuff that fetches data from database and that needs to wait untill posted stuff has been saved there.
		 */
		// The 2 method calls are placed here and not in constructor, so a user input will have been saved to database.
		// load match details from database
		if ($this->load_match_planning->isPosted() || $this->picked_date->isPosted())
			$this->set_availability();
		
		// determine if the date is pickable. 
		$this->set_date_is_pickable();
		
		/**
		 * start actual rendering
		 */
		// render the form with the input fields
		$output = '<form name="'.$this->name.'" method="'.$this->method.'" action="'.$this->action.'" enctype="'.$this->enctype.'">'."\n";
		
		if ($this->load_match_planning->isPosted() || $this->submit->isPosted())
		{
			// disable opponent_team dropdown but add the hidden input field with its value to make it post
			$this->opponent_team->setDisabled()->setSelected($this->opponent_team_hidden->getPosted()); // when we post as this is disabled
			$this->opponent_team_hidden->setSelected($this->opponent_team->getPosted());
			
			$output .= $this->opponent_team->render();
			$output .= $this->opponent_team_hidden->render().BRCLR;
			
			$data = array(
					'players' 		 => $this->players, 
					'dates' 		 => $this->dates, 
					'availabilities' => $this->availabilities,
					'images' 		 => $this->images,
					'best_options'   => $this->best_options,
					'date_is_pickable' => $this->date_is_pickable,
					'previously_picked_date' => $this->previously_picked_date,
					);
			$output .= $this->load->view('planFormView.php', $data, true);
			
			$output .= $this->picked_date->render();
			
			$output .= $this->submit->render();
			$output .= $this->cancel->render().BRCLR;
		}
		else
		{
			$output .= $this->opponent_team->render().BRCLR;
			
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
		
		if (! $this->picked_date->isPosted())
		{
			// all dates should be filled in
			foreach ($this->availabilities as $date => $input)
			{
				if (! $input->isPosted())
					$this->invalidate($input, 'Verplicht in te vullen');
			}
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
		
		for ($i=0; $i<21; $i++)
		{
			$time = strtotime("+$i days");
			$this->dates[date('Ymd', $time)] = array(
					'day' => $days[(date('w', $time))],                // Zondag 
					'date' => date('d', $time).' '.$months[date('n', $time)]  // 13 januari
					); 
		}
	}
	
	/**
	 * fetch the match_id of this match
	 * @param int $team1
	 * @param int $team2
	 */
	protected function fetch_match_id($team1, $team2)
	{
		$sql = "select match_id
				from   matches  m 
				where  ? in (m.id_team1, m.id_team2)
				and    ? in (m.id_team1, m.id_team2)";
		$query = $this->db->query($sql, array($team1, $team2));
		$row = $query->row_array();
		$this->match_id = $row['match_id'];
	}
	
	
	protected function fetch_best_options()
	{
		foreach ($this->dates as $date => $date_fields)
		{
			$img = new Planning_img(1);
			$this->best_options[$date] = $img->set_half_active()->add_class('hidden');
		}
		
		// fetch the dates that have the most availability
		$sql = "select date_format(mp.plan_date, '%Y%m%d') plan_date
				,      sum(mp.availability) total_availability
				from   match_planning mp
				where  mp.match_id = ?
				group by date_format(mp.plan_date, '%Y%m%d')
				order by total_availability";
		$query = $this->db->query($sql, array($this->match_id));
		$total_availability = null;
		foreach ($query->result_array() as $row)
		{
			if (empty($total_availability))
				$total_availability = $row['total_availability'];
				
			if ($row['total_availability'] > $total_availability)
				break;

			if (! empty($this->best_options[$row['plan_date']]))
				$this->best_options[$row['plan_date']]->remove_class('hidden')->add_class('keep_alive'); 
		}
	}
	
	/**
	 * set availability from database 
	 */
	protected function set_availability()
	{
		// fetch picked date if a date has been picked
		$sql = "select date_format(picked_date, '%Y%m%d') picked_date from matches where match_id = ?";
		$query = $this->db->query($sql, array($this->match_id));
		$row = $query->row_array();
		$this->previously_picked_date = $row['picked_date'];
		
		// set the picked image
		if (! empty($this->best_options[$this->previously_picked_date]))
			$this->best_options[$this->previously_picked_date]->set_active()->add_class('picked');
		
		// fetch availability 
		$sql = "select mp.player_id
				,      date_format(mp.plan_date, '%Y%m%d') plan_date
				,      mp.availability
				from   match_planning mp
				where  mp.match_id = ?
				and    mp.plan_date >= curdate()
				order by player_id, plan_date";
		$query = $this->db->query($sql, array($this->match_id));
		$result = $query->result_array();

		// set the availability images
		foreach ($result as $row) 
		{
			// if the dates are filled previously, there will be dates in the database that are not shown and vice versa.
			if (isset($this->images[$row['player_id']][$row['plan_date']][$row['availability']]))
			{
				$img = $this->images[$row['player_id']][$row['plan_date']][$row['availability']];
				
				if (! empty($this->previously_picked_date) && $this->previously_picked_date != $row['plan_date'])
					$img->set_half_active();
				elseif ($row['player_id'] == $this->session->userdata('user_id'))
					$img->set_active();
				else
					$img->set_half_active();
			}
			
			// set availability hidden input
			if ($row['player_id'] == $this->session->userdata('user_id'))
				$this->availabilities[$row['plan_date']]->setValue($row['availability']);
		}
	}
	
	/**
	 * determine if a date can be picked by the user
	 */
	protected function set_date_is_pickable()
	{
		// a date is pickable if the user has filled in his availability and if at least 1 person per team has filled it in.
		$sql = "select count(distinct p.team_id) teams
				,      max(case when mp.player_id = ? then 1 else 0 end) user_has_filled_in
				from   match_planning mp
				join   players        p  on mp.player_id = p.player_id
				where  mp.match_id = ?";
		$query = $this->db->query($sql, array($this->session->userdata('user_id'), $this->match_id));
		$row = $query->row_array();
		$this->date_is_pickable = ($row['teams'] == 2 && $row['user_has_filled_in'] = 1);
	}
	
}

