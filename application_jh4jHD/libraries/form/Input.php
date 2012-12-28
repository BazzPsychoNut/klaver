<?php

require_once 'functions.php';
require_once 'FormError.php';
require_once 'Validate.php';


/**
 * Abstract class for input classes
 * Only render() _has_ to be different for each concrete class. The rest can be inherited.
 * @author b-deruiter
 *
 */
abstract class Input
{
    protected $id,
              $name,
              $value,
              $values = array(),
              $label,
              $labels = array(),
              $width,
              $classes = array(),
              $title,
              $styles = array(),
              $selected,
              $posted,
              $disabled,
              $hidden,
              $onclick,
              $onchange,
              $inReportForm = false;
    
    protected $validate; // Validate object
    
    // bitwise flags
    const INPUT_OVERRULE_POST = 1; // make the given selected value overwrite anything that is posted
    // next const should be 2, then 4, then 8 etc. to have the nth bit set to 1

    /**
     * construct Input
     * @param string $name
     * @param mixed optional $value
     */
    function __construct($name, $value = null)
    {
        $this->validate = new Validate();
        
        $this->setName($name);
        $this->setId($name); // normally you want the id to be the same as the name
        
        if (! empty($value))
        {
            if (is_array($value))
                $this->setValues($value);
            else
                $this->setValue($value);
        }
        
        // store posted as selected
        $this->setPosted();
    }
    
    /**
     * render html
     * @return html
     */
    abstract public function render();
    
    /**
     * @return attributes in readable format
     */
    public function toString()
    {
        return "<pre>\n".print_r($this, true)."</pre>\n";
    }
    
    /**
     * convert all characters in $value that are not allowed in a html string to $replace (default a dash -)
     * @param string $value
     */
    protected function toValidHtmlId($value, $replace='-')
    {
        $invalidCharacters = " \r\n\t;,./&|[]{}+=`~!@#$%^*()";
        return str_replace(str_split($invalidCharacters), $replace, $value);
    }
    
    /**
     * bitwise check if given flag number contains the wanted value
     * 
     * @param int $flag
     * @param int $value
     * @return boolean
     */
    protected function isFlagSet($flag, $value)
    {
        return (($flag & $value) == $value);
    }
    
    
	/**
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

	/**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

	/**
     * @return string $value
     */
    public function getValue()
    {
        return $this->value;
    }

	/**
     * @return array $values
     */
    public function getValues()
    {
        return $this->values;
    }

	/**
     * @return string $label
     */
    public function getLabel()
    {
        return $this->label;
    }

	/**
     * @return array $labels
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return int $width
     */
    public function getWidth()
    {
        return $this->width;
    }
    
    /**
     * @return string $class
     */
    public function getClass()
    {
        return implode(' ', $this->classes);
    }
    
    /**
     * @return array $classes
     */
    public function getClasses()
    {
        return $this->classes;
    }
    
    /**
     * @return css-string style
     */
    public function getStyle()
    {
        return implode('; ', $this->styles).';';
    }
    
    /**
     * 
     * @return array $styles
     */
    public function getStyles()
    {
        return array_values($this->styles);
    }
    
    /**
     * @return mixed $selected
     */
    public function getSelected()
    {
        return $this->selected;
    }
    
    /**
     * @return string $posted
     */
    public function getPosted()
    {
        return $this->posted;
    }

    /**
     * @return string $disabled
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
    
    /**
     * @return boolean $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }
    
    /**
     * @return boolean $inReportForm
     */
    public function getInReportForm()
    {
        return $this->inReportForm;
    }
    
    /**
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * @return string $onclick
     */
    public function getOnclick()
    {
        return $this->onclick;
    }
    
    /**
     * @return string $onchange
     */
    public function getOnchange()
    {
        return $this->onchange;
    }
    
    

    /**
     * @param element id $id
     */
    public function setId($id)
    {
        // a name can end with [] but the id not
        if (substr($id, -2) == '[]')
            $id = substr($id, 0, -2);
            
        if ($this->validate->htmlId($id))
        {
            $this->id = $id;
        }
        
        return $this;
    }

	/**
     * @param element name $name
     */
    protected function setName($name)
    {
        // a name can end with '[]'
        $testName = substr($name, -2) == '[]' ? substr($name, 0, -2) : $name;
        
        if ($this->validate->htmlId($testName))
        {
            $this->name = $name;
        }
        
        return $this;
    }

	/**
     * @param string $value
     */
    public function setValue($value)
    {
        if ($this->validate->isNotArray($value))
        {
            $this->value = xsschars($value);
        }
        
        return $this;
    }

	/**
     * @param array $values
     */
    public function setValues($values)
    {
        if ($this->validate->isArray($values))
        {
            $values = array_values($values); // enforce numeric array
            array_walk($values, 'xsschars');
            
            $this->values = $values;
        }
        
        return $this;
    }

	/**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }
    
	/**
     * @param array $labels
     */
    public function setLabels($labels)
    {
        if ($this->validate->isArray($labels))
        {
            $labels = array_values($labels); // enforce numeric array
            array_walk($labels, 'xsschars');
            $this->labels = $labels;
        }
        
        return $this;
    }
    
	/**
     * append single option
     * @param string $value
     * @param string $label
     */
    public function appendOption($value, $label)
    {
        $this->values[] = xsschars($value);
        $this->labels[] = xsschars($label);
        
        return $this;
    }
    
    /**
     * append array of options ( $value => $label )
     * @param $options
     */
    public function appendOptions($options)
    {
        if ($this->validate->isArray($options))
        {
            // loop over the options array and add all values to values and labels
            // array_merge or the + operator won't do, because they will remove duplicate keys
            // (and we will have a lot of duplicate keys in 2 numeric arrays)
            foreach ($options as $value => $label)
            {
                $this->values[] = xsschars($value);
                $this->labels[] = xsschars($label);
            }
        }
        
        return $this;
    }
    
    /**
     * prepend single option
     * @param string $value
     * @param string $label
     */
    public function prependOption($value, $label)
    {
        array_unshift($this->values, xsschars($value));
        array_unshift($this->labels, xsschars($label));
        
        return $this;
    }
    
    /**
     * prepend array of options ( $value => $label )
     * @param $options
     */
    public function prependOptions($options)
    {
        if ($this->validate->isArray($options))
        {
            // loop over the options array and add all values to values and labels
            // array_merge or the + operator won't do, because they will remove duplicate keys
            // (and we will have a lot of duplicate keys in 2 numeric arrays)
            $options = array_reverse($options); // reverse the array to keep the order when we unshift :)
            foreach ($options as $value => $label)
            {
                array_unshift($this->values, xsschars($value));
                array_unshift($this->labels, xsschars($label));
            }
        }
        
        return $this;
    }
    
    /**
     * use $query object to populate values list
     * @param Query $query
     */
    public function appendOptionsFromQuery(Query $query, $valueColumn='VALUE', $labelColumn='LABEL')
    {
        if ($this->validate->isQuery($query)) // validate and execute $query
        {
            // empty the arrays we are going to fill
            foreach ($query->fetchAll() as $row)
            {
                $this->values[] = isset($row[strtoupper($valueColumn)]) ? $row[strtoupper($valueColumn)] : null;
                $this->labels[] = isset($row[strtoupper($labelColumn)]) ? $row[strtoupper($labelColumn)] : null;
            }
        }
        
        return $this;
    }

	/**
     * @param int $width (in pixels)
     */
    public function setWidth($width)
    {
        if ($this->validate->numeric($width))
        {
            $this->width = $width;
            
            // remove old width style
            foreach ($this->styles as $style)
            {
                if (preg_match('/^width:/', $style)) // regular expression to make sure the string starts with width
                	$this->removeStyle($style);
            }
            // add new width style
            $this->addStyle('width:'.$this->width.'px');
        }
        
        return $this;
    }

	/**
     * @param array $class
     */
    public function setClass($classes)
    {
        if (! is_array($classes))
            $classes = (array) $classes;
        
        foreach ($classes as $class)
        {
	        if ($this->validate->htmlId($class))
	        {
	            $this->classes[] = $class;
	        }
        }
        
        return $this;
    }
    
    /**
     * @param add a classname
     */
    public function addClass($class)
    {
        if ($this->validate->htmlId($class))
        {
            $this->classes[] = $class;
        }
        
        return $this;
    }
    
    /**
     * remove a classname from the class
     * @param string $class
     */
    public function removeClass($class)
    {
        foreach ($this->classes as $key => $val)
        {
            if ($val == $class)
                unset($this->classes[$key]);
        }
        
        return $this;
    }
    
    /**
     * @param array $styles
     */
    public function setStyle($styles)
    {
        if (! is_array($styles))
            $styles = (array) $styles;

        // use addStyle to keep the logic in one function
        $this->styles = array();
        foreach ($styles as $style)
            $this->addStyle($style);
        
        return $this;
    }
    
    /**
     * @param css-string $style
     */
    public function addStyle($style)
    {
        $this->styles[] = trim($style, ' ;');
        
        return $this;
    }
    
    /**
     * @param css-string $style
     */
    public function removeStyle($style)
    {
        foreach ($this->styles as $key => $val)
        {
            if ($val == trim($style, ' ;'))
                unset($this->styles[$key]);
        }
        
        return $this;
    }
    
	/**
     * @param $selected the $selected to set
     */
    public function setSelected($selected, $flag = 0)
    {
        if (empty($this->posted) || $this->isFlagSet($flag, self::INPUT_OVERRULE_POST))
        {
            $this->selected = $selected;
        }
        
        return $this;
    }
    
    /**
     * store posted values
     * @param $posted
     */
    protected function setPosted()
    {
        if (isset($_POST[$this->name]) && ! empty($_POST[$this->name]))
        {
            $this->posted = xsschars($_POST[$this->name]);
            $this->selected = xsschars($_POST[$this->name]);  // so we can always retrieve the selected fields with getSelected()
        }
        
        return $this;
    }
    
    /**
     * set $disabled
     * @param value or false $disabled
     */
    public function setDisabled($disabled)
    {
        if ($this->validate->inArray($disabled, array('disabled','readonly')))
        {
            $this->disabled = $disabled;
        }
        
        return $this;
    }
    
	/**
     * @param boolean $hidden
     */
    public function setHidden($hidden)
    {
        if ($this->validate->isBoolean($hidden))
        {
            $this->hidden = $hidden;
            
            if ($this->hidden === true)
                $this->addStyle('display:none');
            else
                $this->removeStyle('display:none');
        }
        
        return $this;
    }
    
	/**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
        
        return $this;
    }
    
	/**
	 * This function is just here to help rebuilding old reports.
	 * Please use jQuery's $('#id').click(function(){}); instead 
     * @param js-string $onclick
     */
    public function setOnclick($onclick)
    {
        $this->onclick = $onclick;
        
        return $this;
    }

	/**
	 * This function is just here to help rebuilding old reports.
	 * Please use jQuery's $('#id').change(function(){}); instead 
     * @param js-string $onchange
     */
    public function setOnchange($onchange)
    {
        $this->onchange = $onchange;
        
        return $this;
    }
    
	/**
	 * indicator to let Input know if it's in the ReportForm class or not (needed to display labels or not)
     * @param bool $bool
     */
    public function setInReportForm($bool)
    {
        if ($this->validate->isBoolean($bool))
        {
            $this->inReportForm = $bool;
        }
        
        return $this;
    }


    
}


