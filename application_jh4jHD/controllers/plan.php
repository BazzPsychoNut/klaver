<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends CI_Controller 
{

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
		
				$match = $this->fetch_match_details($this->session->userdata('user_team_id'), $form->opponent_team->getPosted());
				
				$match_id = $match['match_id'];
				$user_id  = $this->session->userdata('user_id');
				
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
	 * fetch match details
	 * @param int $id_team1
	 * @param int $id_team2
	 * @throws Exception
	 */
	protected function fetch_match_details($id_team1, $id_team2)
	{
		$sql = "select m.match_id
        	    ,      m.id_team1
        	    ,      m.id_team2
        	    from   matches  m
        	    where  ? in (m.id_team1, m.id_team2)
        	    and    ? in (m.id_team1, m.id_team2)";
		$query = $this->db->query($sql, array($id_team1, $id_team2));
		$match = $query->row_array();
		if (empty($match)) // this is impossible without changing the post values
			throw new Exception('Kan geen ingeroosterde partij vinden tussen jullie en dat team.');
		 
		return $match;
	}
	
}

