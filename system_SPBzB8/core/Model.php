<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Model Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/config.html
 */
class CI_Model {
    
    // Add these variable descriptors for Zend Studio
    
    /**
     * @var CI_Config
     */
    var $config;
    /**
     * @var CI_DB_active_record
     */
    var $db;
    /**
     * @var CI_Email
     */
    var $email;
    /**
     * @var CI_Form_validation
     */
    var $form_validation;
    /**
     * @var CI_Input
     */
    var $input;
    /**
     * @var CI_Loader
     */
    var $load;
    /**
     * @var CI_Router
     */
    var $router;
    /**
     * @var CI_Session
     */
    var $session;
    /**
     * @var CI_Table
     */
    var $table;
    /**
     * @var CI_Unit_test
     */
    var $unit;
    /**
     * @var CI_URI
     */
    var $uri;
    /**
     * @var CI_Pagination
     */
    var $pagination;

	/**
	 * Constructor
	 *
	 * @access public
	 */
	function __construct()
	{
		log_message('debug', "Model Class Initialized");
	}

	/**
	 * __get
	 *
	 * Allows models to access CI's loaded classes using the same
	 * syntax as controllers.
	 *
	 * @param	string
	 * @access private
	 */
	function __get($key)
	{
		$CI =& get_instance();
		return $CI->$key;
	}
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */