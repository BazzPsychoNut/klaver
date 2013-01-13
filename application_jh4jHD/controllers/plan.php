<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plan extends CI_Controller 
{

	public function index()
	{
		$data = array();
		
		require_once APPPATH.'forms/PlanForm.php';
		$form = new PlanForm();
		
		if ($form->isPosted())
		{
			try
			{
				if (! $form->validate())
					throw new Exception('Het invoeren is mislukt, omdat niet alle velden goed zijn ingevuld.');
		
				// TODO handle post
			}
			catch (Exception $e)
			{
				$data['feedback'] = error($e->getMessage());
			}
		}
		 
		
		$data['form'] = $form;
		
		$this->load->view('headerView');
		$this->load->view('planView', $data);
		$this->load->view('footerView');
	}
	
}

