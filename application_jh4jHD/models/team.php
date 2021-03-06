<?php

/**
 * class to represent a team
 * @author Bas
 *
 */
class Team extends CI_Model
{
    
    protected $team_id,
    		  $name,
    		  $poule_id,
    		  $played,
    		  $wins,
    		  $losses,
    		  $points,
    		  $points_against;
    
    
    function __construct($team_id = null)
    {
        parent::__construct();
         
        $CI =& get_instance();
        $this->db = $CI->db;
        
        if (! empty($team_id))
        	$this->init($team_id);
    }


    public function init($team_id)
    {
        $query = $this->db->get_where('teams', array('team_id' => $team_id));
        foreach ($query->row_array() as $attr => $value)
            $this->$attr = $value;
        
        return $this;
    }
    
	/**
     * @return int $team_id
     */
    public function getTeamId()
    {
        return $this->team_id;
    }

	/**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @return int $poule_id
     */
    public function getPouleId()
    {
        return $this->poule_id;
    }

	/**
     * @return int $played
     */
    public function getPlayed()
    {
        return $this->played;
    }

	/**
     * @return int $wins
     */
    public function getWins()
    {
        return $this->wins;
    }

	/**
     * @return int $losses
     */
    public function getLosses()
    {
        return $this->losses;
    }

	/**
     * @return int $points
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @return int $points_against
     */
    public function getPointsAgainst()
    {
    	return $this->points_against;
    }
    
    
    
}


