<?php

require_once 'form/Input.php';


class TextAreaInput extends Input
{
    
    protected $rows;
    protected $cols;
    protected $wrapStyle;

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
                
            $value     = ! empty($this->selected) ? $this->selected : $this->value;
            $rows      = ! empty($this->rows) ? ' rows="'.$this->rows.'"' : '';
            $cols      = ! empty($this->cols) ? ' cols="'.$this->cols.'"' : '';
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            $this->style .= ! empty($this->wrapStyle) ? ' white-space:'.$this->wrapStyle.'; ' : '';
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
                            
            $output = '';
            if (! empty($this->label) && ! $this->inReportForm)
                $output .= '<label for="'.$this->id.'">'.$this->label.'</label>';
            $output .= '<textarea id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'"'.$rows.$cols.$disabled.' style="'.$this->style.'" title="'.$this->title.'" />'."\n";
            $output .= $value;
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
        return $this->rows;
    }

	/**
     * @return the $cols
     */
    public function getCols()
    {
        return $this->cols;
    }

	/**
     * @return the $wrapStyle
     */
    public function getWrapStyle()
    {
        return $this->wrapStyle;
    }

	/**
     * @param $rows the $rows to set
     */
    public function setRows($rows)
    {
        if ($this->validate->numeric($rows))
        {
            $this->rows = $rows;
        }
    }

	/**
     * @param $cols the $cols to set
     */
    public function setCols($cols)
    {
        if ($this->validate->numeric($cols))
        {
            $this->cols = $cols;
        }
    }

	/**
     * @param $wrapStyle the $wrapStyle to set
     */
    public function setWrapStyle($wrapStyle)
    {
        $allowed = array('normal','pre','nowrap','pre-wrap','pre-line','inherit');
        if (! in_array($wrapStyle, $allowed))
            $this->validate->invalidate('Given textarea wrap-style "'.$wrapStyle.'" is invalid');
        else
            $this->wrapStyle = $wrapStyle;
    }

}


?>