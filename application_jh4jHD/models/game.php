<?php

/**
 * class to represent a game (all cards)
 * @author Bas
 *
 */
class game extends CI_Model
{

    protected $game_id,
    		  $match_id,
    		  $points_team1,
    		  $points_team2,
    		  $roem_team1,
    		  $roem_team2,
    		  $special_team1, // PIT or NAT
    		  $special_team2;
    

    public function __construct()
    {
        parent::__construct();
        
        $CI =& get_instance();
        $this->db = $CI->db;
    }
    
    
    public function init($game_id)
    {
        $query = $this->db->get_where('games', array('game_id' => $game_id));
        foreach ($query->row_array() as $attr => $value)
            $this->$attr = $value;
        
        return $this;
    }
}


