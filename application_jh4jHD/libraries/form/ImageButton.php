<?php

require_once 'Input.php';


class ImageButton extends Input
{
    
    protected $src,
              $alt;

    /**
     * render the hidden input element
     */
    public function render($echo = false)
    {
        try
        {
            // default validity check
            $this->validate->isNotEmpty($this->src);
            if (! $this->validate->isValid())
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name));
                
            $output = $this->getLabel().'<input type="image"'.$this->getSrc().$this->getAlt().$this->getId().$this->getClass().$this->getName().$this->getValue().$this->getStyle().$this->getTitle().$this->getOnclick().' />'."\n";
            
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
     * @return $src
     */
    public function getSrc()
    {
        return ' src="'.$this->src.'"';
    }

	/**
     * @param url $src
     */
    public function setSrc($src)
    {
        if ($this->validate->isValidUrl($src))
        {
            $this->src = $src;
        }
        
        return $this;
    }
    
	/**
     * @return string $alt
     */
    public function getAlt()
    {
        return ' alt="'.$this->alt.'"';
    }

	/**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        
        return $this;
    }


    
    
}


