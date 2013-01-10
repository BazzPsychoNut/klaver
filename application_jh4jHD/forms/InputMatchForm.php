<?php

require_once APPPATH.'libraries/Form.php';

class InputMatchForm extends Form 
{
	public 	$user_team,
			$opponent_team,
			$played_date,
			$games = array(),
			$totals = array(),
			$submit;
	
	
	
	function __construct($name = null) 
	{
		$CI =& get_instance();
		$this->db = $CI->db;
		
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
		
		// TODO rewrite DateInput to use jquery ui
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
		
		// submit
		$this->submit = new SubmitButton('input_match', 'Opslaan');
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
		
		$output .= $this->user_team->render().BRCLR;
		$output .= $this->opponent_team->render().BRCLR;
		//$output .= $this->played_date->render().BRCLR; // TODO build en uncomment
		
		// hands table of input fields
		$output .= '<label>&nbsp;</label>'."\n";
		$output .= '<table id="hands_input">'."\n";
		$output .= "<thead>\n";
		$output .= '  <tr><td></td><th colspan="2">Wij</th><td></td><th colspan="2" style="'.(! $this->isValid() ? 'text-align:left; padding-left:30px;' : '').'">Zij</th><td></td></tr>'."\n";
		$output .= '  <tr><td></td><td>punten</td><td>roem</td><td></td><td>punten</td><td>roem</td><td></td></tr>'."\n";
		$output .= "</thead>\n";
		$output .= "<tbody>\n";
		foreach ($this->games as $i => $teams)
		{
			$output .= '<tr class="'.($i % 4 == 1 && $i > 1 ? 'row_spacer' : '').'">'."\n";
			$output .= '<th>'.$i.'</th>'."\n";
			foreach ($teams as $team => $pointTypes)
			{
				foreach ($pointTypes as $type => $input)
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
		
		$output .= $this->submit->render().BRCLR;
		
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
				if (! $validate->between($roem, 0, 900))  // theoretical max: 8*100 + 100 PIT
					$this->invalidate($inputs['roem'], 'Ongeldige waarde.');
				if ($roem % 10 != 0) // roem is always a product of 10
				    $this->invalidate($inputs['roem'], 'Ongeldige waarde.');
				
				// NAT of PIT kan geen roem hebben
				if (in_array(strtolower($points), array('nat', 'pit', 'n', 'p')) && $roem != 0)
				    $this->invalidate($roem, 'Je kan geen roem hebben bij een NAT of PIT');
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
	
}

