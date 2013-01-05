<?php

require_once APPPATH.'libraries/Form.php';

class InputMatchForm extends Form 
{
	public 	$user_team,
			$opponent_team,
			$hands = array(),
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
		
		// create hands input fields 
		for ($i=1; $i<=16; $i++)
		{
			$this->hands[$i]['team1']['points'] = new TextInput("team1[points][$i]");
			$this->hands[$i]['team1']['roem']   = new TextInput("team1[roem][$i]");
			$this->hands[$i]['team2']['points'] = new TextInput("team2[points][$i]");
			$this->hands[$i]['team2']['roem']   = new TextInput("team2[roem][$i]");
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
		
		// hands table of input fields
		$output .= '<label>&nbsp;</label>'."\n";
		$output .= '<table id="hands_input">'."\n";
		$output .= "<thead>\n";
		$output .= '<tr><td></td><th colspan="2">Wij</th><td></td><th colspan="2">Zij</th><td></td></tr>'."\n";
		$output .= '<tr><td></td><td>punten</td><td>roem</td><td></td><td>punten</td><td>roem</td><td></td></tr>'."\n";
		$output .= "</thead>\n";
		$output .= "<tbody>\n";
		foreach ($this->hands as $i => $teams)
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
			$output .= '<td class="hand_total_check"></td>'."\n";  // TODO plaats hier de invalidatie feedback als het hand totaal niet klopt
			$output .= '</tr>'."\n";
		}
		$output .= "</tbody>\n";
		$output .= "<tfoot>\n";
		$output .= '<tr><td></td><td id="total_points_team1">0</td><td id="total_roem_team1">0</td><td></td><td id="total_points_team2">0</td><td id="total_roem_team2">0</td><td></td></tr>'."\n";
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
		
		// TODO create form validation

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

