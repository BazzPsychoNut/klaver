<?php

require_once 'form/Input.php';


class FileInput extends Input
{
    
    protected $size; // File Input doesn't support style="width"

    /**
     * render the hidden input element
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            $size = ! empty($this->size) ? 'size="'.$this->size.'"' : '';
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
            
            $output = '';
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label> ';
            $output .= '<input type="file" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" '.$size.' style="'.$this->style.'" '.$disabled.' title="'.$this->title.'" />'."\n";
            
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
     * return the selected (or posted) value
     */
    public function getSelected()
    {
        if (isset($_FILES[$this->name]) && ! empty($_FILES[$this->name]))
        {
            return $_FILES[$this->name];
        }
    }
	/**
     * @return the $size
     */
    public function getSize()
    {
        return $this->size;
    }

	/**
     * @param int $size
     */
    public function setSize($size)
    {
        if ($this->validate->numeric($size))
        {
            $this->size = $size;
        }
    }

    
}


?>