<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * class to represent a player
 * @author Bas
 *
 */
class Player extends CI_Model
{

    protected $name,
    		  $emailAddress,
    		  $level,
    		  $team;
    
    function __construct()
    {
        parent::__construct();
         
        $CI =& get_instance();
        $this->db = $CI->db;
    }

    public function init($name)
    {
        // fetch Player details
        
        
        return $this;
    }
}


?>