<?php

require_once 'Input.php';


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
		            $checked = $value == $this->selected ? ' checked="checked"' : '';
		            $id = $this->id.'-'.$this->toValidHtmlId($value);
		            
		            $radio = '<input type="radio" id="'.$id.'"'.$this->getClass().$this->getName().' value="'.$value.'"'.$checked.$this->getDisabled().$this->getTitle().$this->getOnclick().' />'."\n";
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
		        
		        $output = $this->getLabel().'<div id="'.$this->id.'-container"'.$this->getStyle().'>'."\n".$output."</div>\n";
            }
            
            $output .= $this->renderInvalidations();
            
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
     * @param html $lineEnd
     */
    public function setLineEnd($lineEnd)
    {
        $this->lineEnd = $lineEnd;
    }
    
    /**
     * @return html $lineEnd
     */
    public function getLineEnd()
    {
        return $this->lineEnd;
    }
    
    
}


