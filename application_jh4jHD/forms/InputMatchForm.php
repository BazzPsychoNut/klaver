<?php

require_once APPPATH.'libraries/Form.php';

class InputMatchForm extends Form 
{
	public 	$user_team,
			$opponent_team,
			$opponent_team_hidden, // hidden input field for when we have the opponent team dropdown disabled
			$played_date,
			$games = array(),
			$totals = array(),
			$load_match,
			$cancel,
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		$this->session = $CI->session;
		
		$this->name = empty($name) ? $name : __CLASS__;
		
		$this->user_team = new TextInput('user_team', $CI->session->userdata('user_team'));
		$this->user_team->setLabel('Jouw Team')->setDisabled('disabled');
		
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
		
		$this->played_date = new DateInput('played_date', date('d-m-Y'));  
		$this->played_date->setLabel('Datum gespeeld');
		
		// create hands input fields 
		for ($i=1; $i<=16; $i++)
		{
			$this->games[$i]['wij']['points'] = new TextInput("wij_points_$i");
			$this->games[$i]['wij']['points']->setMaxLength(3);
			$this->games[$i]['wij']['roem'] = new TextInput("wij_roem_$i");
			$this->games[$i]['wij']['roem']->setMaxLength(3);
			$this->games[$i]['zij']['points'] = new TextInput("zij_points_$i");
			$this->games[$i]['zij']['points']->setMaxLength(3);
			$this->games[$i]['zij']['roem'] = new TextInput("zij_roem_$i");
			$this->games[$i]['zij']['roem']->setMaxLength(3);
		}
		
		// load submit
		$this->load_match = new SubmitButton('load_match', 'Partij openen');
		$this->load_match->setLabel('&nbsp;')->addStyle('margin-top:10px');
		
		// cancel
		$this->cancel = new SubmitButton('cancel', 'Annuleren');
		$this->cancel->addStyle('margin:20px')->addClass('cancel');
		
		// submit
		$this->submit = new SubmitButton('input_match', 'Opslaan');
		$this->submit->setLabel('&nbsp;')->addStyle('margin-top:20px');
		
		// load match details from database
		if ($this->load_match->isPosted())
		{
			$opponent_team = $this->opponent_team->isPosted() ? $this->opponent_team->getPosted() : $this->opponent_team_hidden->getPosted();
			$this->set_match_details($this->session->userdata('user_team_id'), $opponent_team);
		}
	}
	
	/**
	 * render the form
	 * @return html 
	 */
	public function render() 
	{
		// render the form with the input fields
		$output = '<form name="'.$this->name.'" method="'.$this->method.'" action="'.$this->action.'" enctype="'.$this->enctype.'">'."\n";
		
		$output .= $this->user_team->render().BRCLR;
		
		if ($this->load_match->isPosted() || $this->submit->isPosted())
		{
			// disable opponent_team dropdown but add the hidden input field with its value to make it post
			$this->opponent_team->setDisabled()->setSelected($this->opponent_team_hidden->getPosted()); // when we post as this is disabled (setSelected won't overwrite anything posted)
			$this->opponent_team_hidden->setSelected($this->opponent_team->getPosted());
			
			$output .= $this->opponent_team->render();
			$output .= $this->opponent_team_hidden->render().BRCLR;
			
			$output .= $this->played_date->render().BRCLR; 
			
			// hands table of input fields
			$output .= '<label>&nbsp;</label>'."\n";
			$output .= '<table id="hands_input">'."\n";
			$output .= "<thead>\n";
			$output .= '  <tr><td></td><th colspan="2">Wij</th><td></td><th colspan="2" style="'.(! $this->isValid() ? 'text-align:left; padding-left:30px;' : '').'">Zij</th><td></td></tr>'."\n";
			$output .= '  <tr><td></td><td>punten</td><td>roem</td><td></td><td>punten</td><td>roem</td><td></td></tr>'."\n";
			$output .= "</thead>\n";
			$output .= "<tbody>\n";
			foreach ($this->games as $i => $game)
			{
				$output .= '<tr class="'.($i % 4 == 1 && $i > 1 ? 'row_spacer' : '').'">'."\n";
				$output .= '<th>'.$i.'</th>'."\n";
				foreach ($game as $team => $inputs)
				{
					foreach ($inputs as $type => $input)
					{
						$output .= '<td>'.$input->render()."</td>\n";
					}
					$output .= '<td class="hands_input_spacer"></td>'."\n";
				}
				$output .= '</tr>'."\n";
			}
			$output .= "</tbody>\n";
			$output .= "<tfoot>\n";
			$output .= '  <tr><td></td><td id="total_points_wij">0</td><td id="total_roem_wij">0</td><td></td><td id="total_points_zij">0</td><td id="total_roem_zij">0</td><td></td></tr>'."\n";
			$output .= "</tfoot>\n";
			$output .= '</table>'."\n";
			
			$output .= $this->submit->render();
			$output .= $this->cancel->render().BRCLR;
		}
		else
		{
			$output .= $this->opponent_team->render().BRCLR;
			
			$output .= $this->load_match->render().BRCLR;
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
		
		// sum check
		for ($i=1; $i<=16; $i++)
		{
		    $points = array($this->games[$i]['wij']['points']->getPosted(), $this->games[$i]['zij']['points']->getPosted());
			$handTotal = (is_numeric($points[0]) ? $points[0] : 0) + (is_numeric($points[1]) ? $points[1] : 0);
			if ($handTotal != 162)
				$this->invalidate($this->games[$i]['zij']['roem'], 'Het totaal van deze hand is '.$handTotal.' ipv 162.');
		}
		
		// valid values check
		foreach ($this->games as $i => $teams)
		{
			foreach ($teams as $team => $inputs)
			{
			    // valid value for points
				$points = $inputs['points']->getPosted();
				if (! $validate->between($points, 0, 162))
				{
					if (! in_array(strtolower($points), array('nat', 'pit', 'n', 'p')))
						$this->invalidate($inputs['points'], 'Ongeldige waarde.');
				}
				
				// valid value for roem
				$roem = $inputs['roem']->getPosted();
				$roem = empty($roem) ? 0 : $roem;
				if ($roem > 0)
				{
					if (! $validate->between($roem, 20, 900))  // theoretical max: 8*100 + 100 PIT
						$this->invalidate($inputs['roem'], 'Ongeldige waarde.');
					if ($roem % 10 != 0) // roem is always a product of 10
					    $this->invalidate($inputs['roem'], 'Ongeldige waarde.');
				}
				
				// NAT of PIT kan geen roem hebben
				if (in_array(strtolower($points), array('nat', 'pit', 'n', 'p')) && $roem != 0)
				    $this->invalidate($roem, 'Je kan geen roem hebben bij een NAT of PIT');
				
				// if team1 has PIT, team2 needs to have at least 100 roem
				if (in_array(strtolower($points), array('p', 'pit')))
				{
					$otherteam = $team == 'wij' ? 'zij' : 'wij';
					if ($teams[$otherteam]['roem']->getPosted() < 100)
						$this->invalidate($teams[$otherteam]['roem'], 'PIT geeft 100 roem.');
				}
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
	
	
	protected function set_match_details($team1, $team2)
	{
		if (! $this->load_match->isPosted())
			return;
		
		/**
		 * fetch
		 */
		// fetch match details
		$sql = "select m.match_id
				,      m.id_team1
				,      m.id_team2
				,      m.played_date
				,      g.owner_id
				from   matches  m
				left join games g on  m.match_id = g.match_id
				                  and g.game = 16
				where  ? in (m.id_team1, m.id_team2)
				and    ? in (m.id_team1, m.id_team2)
				order by g.game";
		$query = $this->db->query($sql, array($team1, $team2));
		$match = $query->row_array();
		if (empty($match)) // this is impossible without changing the post values
			throw new Exception('Kan geen ingeroosterde partij vinden tussen jullie en dat team.');
		
		// fetch game details
		$sql = "select * from games where match_id = ? order by game";
		$query = $this->db->query($sql, array($match['match_id']));
		$games = $query->result_array();
		
		/**
		 * set
		 */
		if (empty($games))
			return;
		
		// who is team1 and who is team2?
		$team1 = $match['id_team1'] == $team1 ? 'wij' : 'zij';
		$team2 = $team1 == 'wij' ? 'zij' : 'wij';
		
		// set match details
		$this->played_date->setSelected($match['played_date']);
		
		// set game details
		foreach ($games as $game)
		{
			$this->games[$game['game']][$team1]['points']->setSelected(! empty($game['special_team1']) ? $game['special_team1'] : $game['points_team1']);
			$this->games[$game['game']][$team2]['points']->setSelected(! empty($game['special_team2']) ? $game['special_team2'] : $game['points_team2']);
			$this->games[$game['game']][$team1]['roem']->setSelected($game['roem_team1']);
			$this->games[$game['game']][$team2]['roem']->setSelected($game['roem_team2']);
		}
		
		// only owner has edit rights
		if ($match['owner_id'] != $this->session->userdata('user_id'))
		{
			$this->played_date->setDisabled();
			$this->submit->setDisabled()->setHidden();
			foreach ($this->games as $game)
			{
				$game['wij']['points']->setDisabled();
				$game['zij']['points']->setDisabled();
				$game['wij']['roem']->setDisabled();
				$game['zij']['roem']->setDisabled();
			}
		}
	}
	
	
}



