<?php

require_once 'form/Input.php';


class RadioInput extends Input
{
    
    protected $renderInColumns,
              $lineEnd;
    
    
    /**
     * render the radio input element
     */
	public function render($echo = false)
    {
        try
        {
            // default validity check
            if (! $this->validate->isValid())
            {
                throw new Exception($this->validate->getMessage('Error rendering '.get_class($this).' object with name '.$this->name, false));
            }
            
            $output = '';
            $disabled  = ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : '';
            if ($this->getHidden() === true)
                $this->style .= ' display:none;';
            if (! empty($this->width))
                $this->style .= ' width:'.$this->width.'px;';
            
            // if it is a collection of checkboxes the property "values" is filled, else the property "value"
            if (! empty($this->values))
            {
    		    if (! empty($this->renderInColumns))
    		    {
    		        $rowsPerColumn = ceil(count($this->values) / $this->renderInColumns);
    		    }
    		    
    		    $columns = array(); // array to collect the values per column in
		        foreach ($this->values as $i => $value)
		        {
		            $checked = $value == $this->selected ? 'checked="checked" ' : '';
		            $id = $this->id.'-'.$this->toValidHtmlId($value);
		            
    		        $radio = '<input type="radio" id="'.$id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$value.'" '.$checked.$disabled.' title="'.$this->title.'" />'."\n";
                    if (! empty($this->labels[$i]))
                    {
                        $radio .= '<label for="'.$id.'">'.$this->labels[$i].'</label>'."\n";
                    }
                    
                    if (! empty($this->renderInColumns))
                    {
                        $columns[floor($i / $rowsPerColumn)][] = $radio;
                    }
                    else
                    {
                        $output .= $radio;
                    }
                    
                    if (! empty($this->lineEnd))
                        $output .= $this->lineEnd;
		        }
		        
		        if (! empty($columns))
		        {
		            $output = '<table><tr>';
		            foreach ($columns as $col)
		            {
		                $output .= '<td valign="top">';
		                foreach ($col as $radio)
		                {
		                    $output .= $radio."<br/>\n";
		                }
		                $output .= '</td>';
		            }
		            $output .= '</tr></table>'."\n";
		        }
		        
		        if (! empty($this->label) && ! $this->inReportForm)
                {
                    $output = '<label for="'.$this->id.'">'.$this->label.'</label>'."\n".$output;
                }
		        $output = '<div id="'.$this->id.'-container" style="'.$this->style.'">'."\n".$output."</div>\n";
            }
            elseif (! empty($this->value))
            {
    		    $checked = $this->value == $this->selected ? 'checked="checked" ' : '';
			
                $output = '<input type="checkbox" id="'.$this->id.'" class="'.$this->class.'" name="'.$this->name.'" value="'.$this->value.'" '.$checked.$disabled.' />'."\n";
                if (! empty($this->label))
                {
                    $output .= '<label for="'.$this->id.'">'.$this->label.'</label>'."\n";
                }
            }
            
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
     * give amount of columns the list should be displayed in
     * @param int $int
     */
    public function setRenderInColumns($int)
    {
        if ($this->validate->numeric($int))
        {
            $this->renderInColumns = $int;
        }
    }
    
    public function getRenderInColumns()
    {
        return $this->renderInColumns;
    }
    
    /**
     * add given html code at the end of each radio line
     * @param html $html
     */
    public function setLineEnd($html)
    {
        $this->lineEnd = $html;
    }
    
    
}


