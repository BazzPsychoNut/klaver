<?php

require_once 'Input.php';


class TextAreaInput extends Input
{
    
    protected $rows;
    protected $cols;

    /**
     * render the hidden input element
     * @param boolean $echo return as string (true) or echo (false)
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            $output = $this->getLabel().'<textarea'.$this->getId().$this->getClass().$this->getName().$this->getRows().$this->getCols().$this->getStyle().$this->getDisabled().$this->getTitle().$this->getOnchange().$this->getOnclick().'>'."\n";
            $output .= ! empty($this->selected) ? $this->selected : $this->value; // can't use getValue() here, because that will return value="value"
            $output .= "</textarea>\n";
            
            if ($echo)
                echo $output;
            else
                return $output;
        }
        catch (Exception $e)
        {
            return FormError::dump($e->getMessage());
        }
    }
    
	/**
     * @return the $rows
     */
    public function getRows()
    {
        return ! empty($this->rows) ? ' rows="'.$this->rows.'"' : '';
    }

	/**
     * @return the $cols
     */
    public function getCols()
    {
        return ! empty($this->cols) ? ' cols="'.$this->cols.'"' : '';
    }

	/**
     * @param int $rows
     */
    public function setRows($rows)
    {
        if ($this->validate->numeric($rows))
        {
            $this->rows = $rows;
        }
        
        return $this;
    }

	/**
     * @param int $cols
     */
    public function setCols($cols)
    {
        if ($this->validate->numeric($cols))
        {
            $this->cols = $cols;
        }
        
        return $this;
    }

	/**
     * @param string $wrapStyle
     */
    public function setWrapStyle($wrapStyle)
    {
        $allowed = array('normal','pre','nowrap','pre-wrap','pre-line','inherit');
        if (! in_array($wrapStyle, $allowed))
            $this->validate->invalidate('Given textarea wrap-style "'.$wrapStyle.'" is invalid');
        else
        {
            // remove old width style
            foreach ($this->styles as $style)
            {
                if (preg_match('/^white-space:/', $style)) // regular expression to make sure the string starts with white-space
                	$this->removeStyle($style);
            }
            // add new width style
            $this->addStyle('white-space:'.$wrapStyle);
        }
        
        return $this;
    }

}


?>