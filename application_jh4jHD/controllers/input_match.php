<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'forms/InputMatchForm.php';

class Input_match extends CI_Controller 
{

	public function index()
	{
	    // redirect user to login page if he is not logged in
	    if ($this->session->userdata('user_logged_in') === false)
	        redirect('login');
	    
	    $data = array();
	    
		$form = new InputMatchForm();
		
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het invoeren is mislukt, omdat niet alle velden goed zijn ingevuld.');
				
				// get details of played match
				$match = $this->fetch_match_details($this->session->userdata('user_team_id'), $form->opponent_team->getPosted());

				// update games
				$this->update_games($match, $form);
				
				// update matches TODO remove hardcoded 01-01
				$this->update_matches($match['match_id'], '20130101' /*dateYmd($form->played_date->getPosted())*/ );
				
				// update teams score
				$this->update_teams($this->session->userdata('user_team_id'));
				$this->update_teams($form->opponent_team->getPosted());
				
				$data['feedback'] = success('Partij succesvol opgeslagen.<br/> Bedankt voor het invoeren en succes met de volgende partij!');
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
	    
		
		$data['form'] = $form;
		
	    $this->load->view('headerView');
		$this->load->view('inputMatchView', $data);
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
        	    ,      case when g.game is not null then 'update' else 'insert' end statement_type
        	    ,      g.owner_id
        	    from   matches  m
        	    left join games g on  m.match_id = g.match_id
        	    and g.game = 16
        	    where  ? in (m.id_team1, m.id_team2)
        	    and    ? in (m.id_team1, m.id_team2)";
	    $query = $this->db->query($sql, array($id_team1, $id_team2));
	    $match = $query->row_array();
	    if (empty($match)) // this is impossible without changing the post values
	        throw new Exception('Kan geen ingeroosterde partij vinden tussen jullie en dat team.');
	    
	    return $match;
	}
	
	
	/**
	 * update games
	 * @param array $match
	 * @param Form $form
	 * @throws Exception
	 */
	protected function update_games($match, Form $form)
	{
	    $matchId = $match['match_id'];  // I use this a lot
	    
	    // who is team1 and who is team2?
	    $team1 = $match['id_team1'] == $this->session->userdata('user_team_id') ? 'wij' : 'zij';
	    $team2 = $team1 == 'wij' ? 'zij' : 'wij';
	    
	    // collect data to set
	    $set = array();
	    foreach ($form->games as $game => $teams)
	    {
	        $points_team1 = $teams[$team1]['points']->getPosted();
	        $points_team2 = $teams[$team2]['points']->getPosted();
	        $set[] = array(
	                    'game'          => $game,
	                    'match_id'      => $matchId,
	                    'id_team1'      => $match['id_team1'],
	                    'id_team2'      => $match['id_team2'],
	                    'points_team1'  => is_numeric($points_team1) ? $points_team1 : 0,
	                    'points_team2'  => is_numeric($points_team2) ? $points_team2 : 0,
	                    'roem_team1'    => (int) $teams[$team1]['roem']->getPosted(),
	                    'roem_team2'    => (int) $teams[$team2]['roem']->getPosted(),
	                    'special_team1' => in_array(strtoupper($points_team1), array('N','P','NAT','PIT')) ? strtoupper($points_team1) : null,
	                    'special_team2' => in_array(strtoupper($points_team2), array('N','P','NAT','PIT')) ? strtoupper($points_team2) : null,
	                    'owner_id'      => $this->session->userdata('user_id'),
	        );
	    }
	    
	    if ($match['statement_type'] == 'update')
	    {
	        // check if user has update rights (only the one who originally inserted can update the scores)
	        if ($match['owner_id'] != $this->session->userdata('user_id'))
	            throw new Exception('Alleen degene die de partij oorspronkelijk heeft ingevoerd kan deze aanpassen.');
	    
	        // We dont really update. We delete then insert, because it's easier.
	        $this->db->delete('games', array('match_id' => $matchId));
	        if ($this->db->affected_rows() == 0)
	            throw new Exception('Error bij verwijderen oude partijgegevens. - '.$this->db->_error_message());
	    }
	     
	    // insert
	    $this->db->insert_batch('games', $set);
	    if ($this->db->affected_rows() != 16)
	        throw new Exception('Probleem bij invoeren van de partij. - '.$this->db->_error_message());
	}
	
	
	/**
	 * update the points fields of a match
	 * @param int $match_id
	 * @param date $played_date
	 * @throws Exception
	 */
	protected function update_matches($match_id, $played_date)
	{
	    $sql = "update matches m
        	    set    m.points_team1 = (select sum(g.points_team1) from games g where g.match_id = m.match_id)
        	    ,      m.points_team2 = (select sum(g.points_team2) from games g where g.match_id = m.match_id)
        	    ,      m.played_date = ?
        	    where  m.match_id = ?";
	    $this->db->query($sql, array($played_date, $match_id) );
	    if ($this->db->affected_rows() == 0)
	        throw new Exception('Error bij updaten totaalscore van deze partij - '.$this->db->_error_message());
	}
	
	
	/**
	 * update the score fields of a team
	 * @param int $team_id
	 * @throws Exception
	 */
	protected function update_teams($team_id)
	{
	    $sql = "update teams t
        	    join
        	    (
        	    select team_id
        	    ,      sum(played)          played
        	    ,      sum(wins)            wins
        	    ,      sum(losses)          losses
        	    ,      sum(points)          points
        	    ,      sum(points_against)  points_against
        	    from
        	    (
        	    select m.id_team1 team_id
        	    ,      sum(case when m.points_team1 > 0 then 1 else 0 end) played
        	    ,      sum(case when m.points_team1 > m.points_team2 then 1 else 0 end) wins
        	    ,      sum(case when m.points_team2 > m.points_team1 then 1 else 0 end) losses
        	    ,      sum(m.points_team1) points
        	    ,      sum(m.points_team2) points_against
        	    from   matches m
        	    where  m.id_team1 = ?
        	    union all
        	    select m.id_team2 team_id
        	    ,      sum(case when m.points_team2 > 0 then 1 else 0 end) played
        	    ,      sum(case when m.points_team2 > m.points_team1 then 1 else 0 end) wins
        	    ,      sum(case when m.points_team1 > m.points_team2 then 1 else 0 end) losses
        	    ,      sum(m.points_team2) points
        	    ,      sum(m.points_team1) points_against
        	    from   matches m
        	    where  m.id_team2 = ?
        	    ) s
        	    group by team_id
        	    ) a on t.team_id = a.team_id
        	    set    t.played = a.played
        	    ,      t.wins = a.wins
        	    ,      t.losses = a.losses
        	    ,      t.points = a.points
        	    ,      t.points_against = a.points_against";
	    $this->db->query($sql, array($team_id, $team_id));
	    if ($this->db->affected_rows() == 0)
	        throw new Exception('Error bij updaten totaalscore van team '.$team_id.' - '.$this->db->_error_message());
	}
}

/* Location: ./application/controllers/home.php */