<?php

/**
 * class to represent a poule
 * @author Bas
 *
 */
class Poule extends CI_Model
{
	protected $poule_id,
	          $teams = array(),
	          $matches = array();  // array of Match objects
	
	
	function __construct()
	{
	    parent::__construct();
	    
	    $CI =& get_instance();
	    $this->db = $CI->db;
	    $this->load = $CI->load;
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
        
        return $this;
    }
    
    /**
     * get array with the current overview data of the poule
     * @return array
     */
    public function getOverview()
    {
        return $this->matches;
    }
    
    /**
     * fetch array of ids of all the teams in the poule
     */
    protected function fetchTeams()
    {
        $this->teams = array(); // first empty
        
        $sql = "select team_id from teams where poule_id = ?";
        $query = $this->db->query($sql, array((int) $this->poule_id));
        foreach ($query->result_array() as $row) {
            $this->teams[] = $row['team_id'];
        }
    }
    
    /**
     * fetch the Match models from database into array
     */
    protected function fetchMatches()
    {
        $sql = "select match_id from matches where poule_id = ? order by scheduled_date";
        $query = $this->db->query($sql, array((int) $this->poule_id));
        foreach ($query->result_array() as $row) {
            $this->matches[] = new Match($row['match_id']);
        }
        
        // display matches
        foreach ($this->matches as $match)
        {
            echo $match->getMatchId().': '.$match->getIdTeam1().' vs '.$match->getIdTeam2()."<br/>\n";
        }
        die;
    }
    
	/**
     * function to create the matches for the poule and fill the matches table
     * Every 3 weeks a match should be played
     */
    public function createMatches($startDate = '20121105')
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
        $matches = array();
        $count = count($this->teams);
        for ($i=0; $i<$count; $i++)
        {
        	$round = (1 + 2*$i) % $count;
            for ($j=$i+1; $j<$count; $j++)
            {
                $team1 = $this->teams[$i];
                $team2 = $this->teams[$j];
                $matches[$team1][$team2] = $round == 0 ? $count : $round;
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
        $this->db->query("delete from matches where poule_id = ?", array($this->poule_id));
        
        // now create the Matches
        // It is possible with some complicated trickery to first create the classes and then later 
        // insert them into the database, but I don't see the point.
        foreach ($matches as $team1 => $t1matches)
        {
            foreach ($t1matches as $team2 => $round)
            {
                // insert round, poule_id, scheduled_date, id_team1, id_team2
                $sql = "insert into matches (round, poule_id, scheduled_date, id_team1, id_team2) values (?, ?, ?, ?, ?)";
                $this->db->query($sql, array($round, $this->poule_id, $dates[$round], $team1, $team2));
            }
        }
        
        // fetch the created matches into the local array
        $this->fetchMatches();
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

}


