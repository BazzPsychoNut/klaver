<?php

/**
 * class to represent a match
 * @author Bas
 *
 */
class Match extends CI_Model
{
    protected $match_id,
    		  $round,
    		  $poule_id,
    		  $scheduled_date,
    		  $played_date,
    		  $id_team1,
    		  $id_team2,
    		  $points_team1,
    		  $points_team2;
    

    
    function __construct($match_id = null)
    {
        parent::__construct();
         
        $CI =& get_instance();
        $this->db = $CI->db;
        
        if (! empty($match_id))
            $this->init($match_id);
    }

	/**
	 * initialize the object 
	 * @param int $match_id
	 */
    public function init($match_id)
    {
        $query = $this->db->get_where('matches', array('match_id' => $match_id));
        foreach ($query->row_array() as $attr => $value)
        	$this->$attr = $value;
        
        return $this;
    }
    
	/**
     * @return int $match_id
     */
    public function getMatchId()
    {
        return $this->match_id;
    }

	/**
     * @return int $round
     */
    public function getRound()
    {
        return $this->round;
    }

	/**
     * @return int $poule_id
     */
    public function getPouleId()
    {
        return $this->poule_id;
    }

	/**
     * @return date $scheduled_date
     */
    public function getScheduledDate()
    {
        return $this->scheduled_date;
    }

	/**
     * @return date $played_date
     */
    public function getPlayedDate()
    {
        return $this->played_date;
    }

	/**
     * @return int $team1_id
     */
    public function getIdTeam1()
    {
        return $this->id_team1;
    }

	/**
     * @return int $team2_id
     */
    public function getIdTeam2()
    {
        return $this->id_team2;
    }

	/**
     * @return int $points_team1
     */
    public function getPointsTeam1()
    {
        return $this->points_team1;
    }

	/**
     * @return int $points_team2
     */
    public function getPointsTeam2()
    {
        return $this->points_team2;
    }

    
    
}


