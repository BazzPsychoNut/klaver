<?php

/**
 * class to represent a game (all cards)
 * @author Bas
 *
 */
class Planning_img extends CI_Model
{

    protected $alt,
    		  $title,
    		  $src,
    		  $class;
    
	/**
	 * create planning image
	 * @param int $type (1, 2 or 3)
	 * @param int $player_id
	 */
    public function __construct($type = null, $player_id = null)
    {
        parent::__construct();
        
        $CI =& get_instance();
        $this->session = $CI->session;
        
        if (! empty($type))
        	$this->init($type, $player_id);
    }
    
    /**
     * create planning image
     * @param int $type (1, 2 or 3)
     * @param int $player_id
     */
    public function init($type, $player_id)
    {
        switch ($type) {
        	case 1:
        		$this->alt = 'Ja';
        		$this->title = 'Ja';
        		$this->src = base_url().'img/yes_g.png';
        		$this->class = $player_id == $this->session->userdata('user_id') ? 'editable' : '';
	        	break;
        	case 2:
        		$this->alt = 'Misschien';
        		$this->title = 'Misschien';
        		$this->src = base_url().'img/maybe_g.png';
        		$this->class = $player_id == $this->session->userdata('user_id') ? 'editable' : '';
        		break;
        	case 3:
        		$this->alt = 'Nee';
        		$this->title = 'Nee';
        		$this->src = base_url().'img/no_g.png';
        		$this->class = $player_id == $this->session->userdata('user_id') ? 'editable' : '';
        		break;
        	default:
        		throw new Exception('Invalid type for creating planning image: - '.$type);
        }
        
        return $this;
    }
    
    /**
     * render the img element
     * @return html
     */
    public function render()
    {
    	return '<img alt="'.$this->alt.'" title="'.$this->title.'" src="'.$this->src.'" class="'.$this->class.'" />';
    }
    
	/**
	 * set image active (posted or selected)
	 */
    public function set_active()
    {
    	$this->src = str_replace('_g', '_a', $this->src);
    }

    /**
     * set image active (selected)
     */
    public function set_someone_else_active()
    {
    	$this->src = str_replace('_g', '_d', $this->src);
    }
    
    
    
}


