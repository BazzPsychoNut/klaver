<?php
// require all form classes
require_once 'form/TextInput.php';
require_once 'form/Button.php';
require_once 'form/Checkbox.php';
require_once 'form/Dropdown.php';
require_once 'form/FileInput.php';
require_once 'form/HiddenInput.php';
require_once 'form/ImageButton.php';
require_once 'form/PasswordInput.php';
require_once 'form/RadioInput.php';
require_once 'form/SubmitButton.php';
require_once 'form/TextAreaInput.php';
require_once 'form/DateInput.php';
require_once 'form/DateRange.php';
require_once 'form/WeekInput.php';
require_once 'form/WeekRange.php';

if (! defined('BRCLR'))
	define('BRCLR', '<br class="clear" />');

/**
 * abstract class Form
 * Because of how CodeIgniter works this can't be abstract, but we know it is.. ;)
 * 
 * @author Bas
 *
 */
class Form
{
	protected $method = 'post',
			  $action = '',
			  $enctype = 'application/x-www-form-urlencoded', // this is the default value anyway
			  $name,
			  $isValid = true;
	
	
	/* abstract */ public function render()
	{}
	
	/* abstract */ public function isPosted()
	{}
	
	/* abstract */ public function validate()
	{}
	
	/**
	 * Invalidate input field (and let this form know it is invalid)
	 * @param Input $input
	 * @param string $invalidation
	 */
	protected function invalidate(Input $input, $invalidation)
	{
		$this->isValid = false;
		$input->addInvalidation($invalidation);
	}
	
	/**
	 * generate salt
	 * @param int $length
	 * @return string
	 */
	public function generateSalt($length = 10)
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$salt = '';
		for ($i=0; $i<$length; $i++)
			$salt .= $chars[mt_rand(0, strlen($chars)-1)];
		
		return $salt;
	}
	
	/**
	 * is the form valid?
	 * @return boolean
	 */
	public function isValid()
	{
		return $this->isValid;
	}
	
	/**
	 * @return the $method
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * @return the $action
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @return the $enctype
	 */
	public function getEnctype() {
		return $this->enctype;
	}

	/**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param field_type $method
	 */
	public function setMethod($method) 
	{
		if (! in_array($method, array('post', 'get')))
			throw new Exception('Invalid method for Form');
		
		$this->method = $method;
	}

	/**
	 * @param field_type $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * @param field_type $enctype
	 */
	public function setEnctype($enctype) {
		$this->enctype = $enctype;
	}

	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	
	
}