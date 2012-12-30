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
    protected $name,
              $label,
              $id,
              $value,
              $values = array(),
              $labels = array(),
              $categories,
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
              $size, // File Input doesn't support style="width" and Dropdown also uses it
              $inReportForm = false,
              $required = false, // boolean value to tell if the input element is required
              $invalidations = array(); // result of form validations of this input element  
    
    
    protected $validate; // Validate object (this validates the object attributes are properly set, not the form validation!)
    
    // bitwise flags
    const INPUT_OVERRULE_POST = 1; // make the given selected value overwrite anything that is posted
    const INPUT_SELECTED_INITIALLY_ONLY = 2; // only use an initial "selected" value if nothing has been posted yet
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
        return ' id="'.$this->id.'"';
    }

    /**
     * @param  string $array '[]' or null
     * @return string $name
     */
    public function getName($array = null)
    {
        $name = $array == '[]' && strpos($this->name, '[]') === false ? $this->name.'[]' : $this->name;
        return ' name="'.$name.'"';
    }
    
    /**
     * return only the name attribute
     * @return string $name
     */
    public function name()
    {
        return $this->name;
    }

	/**
     * @return string $value
     */
    public function getValue()
    {
        $value = ! empty($this->selected) ? $this->selected : $this->value;
        return ! empty($value) ? ' value="'.$value.'"' : null;
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
    	if (empty($this->label) || $this->inReportForm)
    		return null;
    		
   		$class = $this->required ? ' class="required"' : '';
   		return '<label for="'.$this->id.'"'.$class.'>'.$this->label.'</label> ';
    }
    
    /**
     * return only the label attribute
     * @return string $label
     */
    public function label()
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
        return ! empty($this->classes) ? ' class="'.implode(' ', $this->classes).'"' : null;
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
        return ! empty($this->styles) ? ' style="'.implode('; ', $this->styles).';"' : null;
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
        return ! empty($this->disabled) ? ' '.$this->disabled.'="'.$this->disabled.'"' : null;
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
        return ! empty($this->title) ? ' title="'.$this->title.'"' : null;
    }
    
    /**
     * @return string $onclick
     */
    public function getOnclick()
    {
        return ! empty($this->onclick) ? ' onclick="'.$this->onclick.'"' : null;
    }
    
    /**
     * @return string $onchange
     */
    public function getOnchange()
    {
        return ! empty($this->onchange) ? ' onchange="'.$this->onchange.'"' : null;
    }
    
    /**
     * @return the $size
     */
    public function getSize()
    {
        return ! empty($this->size) ? ' size="'.$this->size.'"' : null;
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
     * Append individual option <option value="$value">$option</option>
     * @param string $value
     * @param string $label
     * @param optional string $category
     */
	public function appendOption($value, $label, $category = null)
    {
        $this->values[] = xsschars($value);
        $this->labels[] = xsschars($label);
        if (! empty($category))
            $this->categories[] = $category;
        
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
     * Prepend individual option <option value="$value">$option</option>
     * @param string $value
     * @param string $label
     * @param optional string $category
     */
	public function prependOption($value, $label, $category = null)
    {
        // prepend values to array (Yes, this will set all other numeric keys 1 higher)
        array_unshift($this->values, $value);
        array_unshift($this->labels, $label);
        
        if (! empty($category))
            array_unshift($this->categories, $category);
        
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
     * use $query object to populate options list
     * @param Query $query
     * @param string $valueColumn
     * @param string $labelColumn
     * @param string $categoryColumn
     */
    public function appendOptionsFromQuery(Query $query, $valueColumn, $labelColumn, $categoryColumn = null)
    {
        if ($this->validate->isQuery($query)) // validate and execute $query
        {
		    $result = $query->fetchAll();
			if ( count($result) > 0 ) 
			{
				foreach ($result as $row)
				{
					$this->appendOption( isset($row[$valueColumn]) ? $row[$valueColumn] : null,
										 isset($row[$labelColumn]) ? $row[$labelColumn] : null,
										 (! empty($categoryColumn) && isset($row[$categoryColumn])) ? $row[$categoryColumn] : null
									   );
				}
            }
        }
        
        return $this;
    }
    
    /**
     * @param array $categories
     */
    public function setCategories($categories)
    {
        if ($this->validate->isArray($categories))
        {
            $this->categories = array_values($categories); // enforce numeric array
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
            $classes = explode(' ', trim($classes));
        
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
            $styles = explode('; ', trim($styles));

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
	 * Set an initial value for the input field
     * @param string $selected
     * @param int $flag
     */
    public function setSelected($selected, $flag = 0)
    {
        if (empty($this->posted) || $this->isFlagSet($flag, self::INPUT_OVERRULE_POST))
        {
            // of flag is niet geset of wel geset maar dan moet POST empty zijn
            if (! $this->isFlagSet($flag, self::INPUT_SELECTED_INITIALLY_ONLY) || empty($_POST))
            {
                $this->selected = $selected;
            }
        }
        
        return $this;
    }
    
    /**
     * store posted values
     * @param $posted
     */
    protected function setPosted()
    {
        if (! empty($_POST[$this->name]))
        {
            $post = $_POST[$this->name];
            if (is_array($post)) 
                array_walk($post, 'xsschars');
            else
                $post = xsschars($post);
            
            $this->posted = $post;
            $this->selected = $post;  // so we can always retrieve the selected fields with getSelected()
        }
        elseif (! empty($_FILES[$this->name]))
        {
            $this->posted = $_FILES[$this->name];
            $this->selected = $_FILES[$this->name];
        }
        
        return $this;
    }
    
    /**
     * is this input element posted?
     * @return boolean
     */
    public function isPosted()
    {
    	return (! empty($_POST[$this->name]) or ! empty($_FILES[$this->name]));
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
     * @param int $size
     */
    public function setSize($size)
    {
        if ($this->validate->numeric($size))
        {
            $this->size = $size;
        }
        
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
    
	/**
	 * @return boolean $required
	 */
	public function getRequired() 
	{
		return $this->required;
	}

	/**
	 * @param bolean $required
	 */
	public function setRequired($required = true) 
	{
		if ($this->validate->isBoolean($required))
		{
			$this->required = $required;
			
			if ($required)
				$this->addClass('required');
			else
				$this->removeClass('required');
		}
	}
	
	/**
	 * @return array $invalidations
	 */
	public function getInvalidations() 
	{
		return $this->invalidations;
	}

	/**
	 * @param string $invalidation
	 */
	public function addInvalidation($invalidation) 
	{
		$this->invalidations[] = $invalidation;
		$this->addClass('invalid');
		
		return $this;
	}
	
	/**
	 * is the input valid?
	 */
	public function isValid()
	{
		return empty($this->invalidations);
	}

	/**
	 * render the invalidations
	 * @return NULL|string
	 */
	protected function renderInvalidations()
	{
		if (empty($this->invalidations))
			return null;
		
		return '<div class="invalidations">'.implode("<br/>\n", $this->invalidations)."</div>\n";
	}


    
}


