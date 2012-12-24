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
    		  $score_team1,
    		  $score_team2;
    

    
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
     * @return field_type $match_id
     */
    public function getMatchId()
    {
        return $this->match_id;
    }

	/**
     * @return field_type $round
     */
    public function getRound()
    {
        return $this->round;
    }

	/**
     * @return field_type $poule_id
     */
    public function getPouleId()
    {
        return $this->poule_id;
    }

	/**
     * @return field_type $scheduled_date
     */
    public function getScheduledDate()
    {
        return $this->scheduled_date;
    }

	/**
     * @return field_type $played_date
     */
    public function getPlayedDate()
    {
        return $this->played_date;
    }

	/**
     * @return field_type $team1_id
     */
    public function getIdTeam1()
    {
        return $this->id_team1;
    }

	/**
     * @return field_type $team2_id
     */
    public function getIdTeam2()
    {
        return $this->id_team2;
    }

	/**
     * @return field_type $score_team1
     */
    public function getScoreTeam1()
    {
        return $this->score_team1;
    }

	/**
     * @return field_type $score_team2
     */
    public function getScoreTeam2()
    {
        return $this->score_team2;
    }

    
    
}


