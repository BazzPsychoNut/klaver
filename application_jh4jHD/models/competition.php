<?php

/**
 * class to start/stop the competition and provide competition info
 * @author Bas
 *
 */
class Competition extends CI_Model
{

    protected $season,
    		  $start_date,
    		  $end_date,
    		  $end_results;
    

    public function __construct($season = null)
    {
        parent::__construct();
        
        $CI =& get_instance();
        $this->db = $CI->db;
        
        if (! empty($season))
        	$this->init($season);
    }
    
    public function init($season)
    {
        $query = $this->db->get_where('competition', array('season' => $season));
        foreach ($query->row_array() as $attr => $value)
            $this->$attr = $value;
        
        return $this;
    }
    
    /**
     * is the competition started?
     * @return boolean
     */
    public function is_started()
    {
    	return ! empty($this->start_date);
    }
}


