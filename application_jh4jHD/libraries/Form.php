<?php
class Form
{
	function __construct()
	{
		// require all form classes
		require_once 'form/TextInput.php';
		require_once 'form/Button.php';
		require_once 'form/Checkbox.php';
		require_once 'form/Dropdown.php';
		require_once 'form/FileInput.php';
		require_once 'form/HiddenInput.php';
		require_once 'form/ImageButton.php';
		require_once 'form/RadioInput.php';
		require_once 'form/SubmitButton.php';
		require_once 'form/TextAreaInput.php';
		require_once 'form/DateInput.php';
		require_once 'form/DateRange.php';
		require_once 'form/WeekInput.php';
		require_once 'form/WeekRange.php';
	}
}