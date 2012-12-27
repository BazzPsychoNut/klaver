<?php
/**
 * this file contains global helper functions
 */

if (! function_exists('format_date'))
{
	function format_date($date)
	{
		return date('d-m-Y', strtotime($date));
	}
}