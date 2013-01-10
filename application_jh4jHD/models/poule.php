<?php

/**
 * class to represent a poule
 * @author Bas
 *
 */
class Poule extends CI_Model
{
	protected $poule_id,
	          $teams = array(),    // array of team_id => array(team_id, name, players)
	          $matches = array(),  // array of Match objects
	          $overview = array(), // overview array of all matches in the poule
	          $ranking = array();  // ranking array
	
	
	function __construct()
	{
	    parent::__construct();
	    
	    $CI =& get_instance();
	    $this->db = $CI->db;
	    $this->load = $CI->load;
	    $this->session = $CI->session;
	}
	


	/**
	 * create Poule Model
	 * @param int $poule_id
	 */
    public function init($poule_id)
    {
        $this->poule_id = $poule_id;
        $this->fetchTeams();
        $this->fetchMatches();
        $this->createOverview();
        $this->createRanking();
        
        return $this;
    }
    
    /**
     * fetch array of ids of all the teams in the poule
     */
    protected function fetchTeams()
    {
        $this->teams = array(); // first empty
        
        $sql = "select t.team_id
				,      t.name 
        		,      min(p.name) player1
        		,      max(p.name) player2
				from   teams t
				join   players p on t.team_id = p.team_id
				where  poule_id = ?
				group by t.team_id, t.name";
        $query = $this->db->query($sql, array((int) $this->poule_id));
        foreach ($query->result_array() as $row) {
            $this->teams[$row['team_id']] = $row;
        }
    }
    
    /**
     * fetch the Match models from database into array
     */
    protected function fetchMatches()
    {
    	$this->matches = array(); // empty
    	
        $sql = "select match_id from matches where poule_id = ? order by scheduled_date";
        $query = $this->db->query($sql, array((int) $this->poule_id));
        foreach ($query->result_array() as $row) {
            $this->matches[] = new Match($row['match_id']);
        }
    }
    
    /**
     * create overview array for displaying the matches in the poule
     * used by view pouleOverviewView.php
     */
    protected function createOverview()
    {
    	$this->overview = array();
    	
    	if (empty($this->matches) || empty($this->teams))
    	{
    		$this->overview = 'Cannot create poule overview: No matches or teams available.';
    		return;
    	}
    	
    	// the overview array has 2 dimensions:
    	// rows: rounds with scheduled date
    	// columns: the matches to play in that round
    	foreach ($this->matches as $match)
    	{
    		$round  = $match->getRound().'e ronde. '.format_date($match->getScheduledDate());
    		$points = $match->getPointsTeam1() > 0 && $match->getPointsTeam2() > 0 ? $match->getPointsTeam1() . ' - ' . $match->getPointsTeam2() : 'nog niet gespeeld';
    		$this->overview[$round][] = array('team1' => $this->teams[$match->getIdTeam1()],  // array(team_id, name, players)
    										  'team2' => $this->teams[$match->getIdTeam2()], 
    										  'points' => $points,
    										 );
    	}
    }
    
    /**
     * create ranking array for displaying the ranking
     * used by view pouleRankingView.php
     */
    protected function createRanking()
    {
    	$this->ranking = array();
    	
    	// the ranking looks like this:
    	// rank team G W V voor tegen
    	// Dit kan gewoon opgehaald worden als simpele fetch van teams :)
    	$sql = "select * from teams where poule_id = ? order by points, team_id";
    	$query = $this->db->query($sql, array((int) $this->poule_id));
    	$result = $query->result_array();
    	if (empty($result))
    		throw new Exception('Error fetching team data for ranking - '.$this->db->_error_message());
    	
    	$rank = 1;
    	$prevPoints = -1;
    	foreach ($result as $i => $row) 
    	{
    		// determine rank
    		$rank = $row['points'] == $prevPoints ? $rank : $i+1;
    		$prevPoints = $row['points'];
    		
    		// fill ranking array
    		$this->ranking[] = array(
    				'pos'   => $rank,
    				'team'  => $row['name'] == $this->session->userdata('user_team') ? '<span class="this_is_me">'.$row['name'].'</span>' : $row['name'],
    				'G' 	=> $row['played'],
    				'W' 	=> $row['wins'],
    				'V' 	=> $row['losses'],
    				'voor'  => $row['points'],
    				'tegen' => $row['points_against'],
    				);
    	}
    }
    
	/**
     * function to create the matches for the poule and fill the matches table
     * Every 3 weeks a match should be played
     */
    public function createMatches($startDate = '20130128')  // startdate is on a monday
    {
        /* dit algoritme creeert een matches schema zoals deze:
           rijen en kolommen zijn de teamnummers, de inhoud zijn de rondes
           
        	1	2	3	4	5	6	7	8
        1	x	1	2	3	4	5	6	7
        2		x	3	4	5	6	7	8
        3			x	5	6	7	8	1
        4				x	7	8	1	2
        5					x	1	2	3
        6						x	3	4
        7							x	5
        8								x
        */
    	
        // het is een competitie, dus elk team tegen elk team
        $teams = array_keys($this->teams);  // create numeric array of team_ids
        $matches = array();
        $count = count($teams);
        for ($i=0; $i<$count; $i++)
        {
        	$round = (1 + 2*$i) % $count;  // da magic *proud*
            for ($j=$i+1; $j<$count; $j++)
            {
                $matches[$teams[$i]][$teams[$j]] = $round == 0 ? $count : $round;
                $round = ++$round % $count;
            }
        }
        
        // bepaal de datums voor elke ronde
        $date = $startDate;
        $dates = array();
        for ($round=1; $round<=$count; $round++)
        {
        	$dates[$round] = $date;
            $date = date('Ymd', strtotime('+3 weeks', strtotime($date)));
        }
        
        // first create a backup of the database
        $this->backup->database();
        
        // then delete the matches for this poule
        $this->db->delete('matches', array('poule_id' => $this->poule_id));
        
        // now create the Matches
        // It is possible with some complicated trickery to first create the classes and then later 
        // insert them into the database, but I don't see the point.
        foreach ($matches as $team1 => $t1matches)
        {
            foreach ($t1matches as $team2 => $round)
            {
            	$set = array(
            			'round' => $round,
            			'poule_id' => $this->poule_id,
            			'scheduled_date' => $dates[$round],
            			'id_team1' => $team1,
            			'id_team2' => $team2,
            			);
            	$this->db->insert('matches', $set);
            	if ($this->db->affected_rows() == 0)
            		throw new Exception('Error bij aanmaken partijen. - '.$this->db->_error_message());
            }
        }
        
        // fetch the created matches into the local array
        $this->fetchMatches();
        
        // create the overview of the new matches
        $this->createOverview();
    }
    
    public function getPouleName()
    {
    	return 'Poule '.$this->poule_id;
    }
    
    /**
     * @return int $poule_id
     */
    public function getPouleId()
    {
        return $this->poule_id;
    }

	/**
     * @return array $teams
     */
    public function getTeams()
    {
        return $this->teams;
    }

	/**
     * @return array Match $matches
     */
    public function getMatches()
    {
        return $this->matches;
    }
    
    /**
     * get array with the current overview data of the poule
     * @return array
     */
    public function getOverview()
    {
    	return $this->overview;
    }
    
    /**
     * get array with the current ranking of teams in the poule
     * @return array
     */
    public function getRanking()
    {
    	return $this->ranking;
    }
    

}


