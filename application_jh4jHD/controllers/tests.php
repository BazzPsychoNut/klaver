<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends CI_Controller 
{
    
    function __construct()
    {
        parent::__construct();
        
        // load unit tests lib
        $this->load->library('unit_test');
        
        // make the comparisons strict (=== rather than ==)
        $this->unit->use_strict(true);
        
        // only use these values for reporting
        // COMMENT: Doesn's seem to work :(
        //$this->unit->set_test_items(array('test_name', 'test_datatype', 'res_datatype', 'result', 'line'));
        
        // use usable format for test report
        $str = '<tr>
			    	{rows}
			        <td>{result}</td>
				    {/rows}
		        </tr>';
        $this->unit->set_template($str);
        
    }

	public function index()
	{
	    echo '<h1>All unit tests</h1>'."\n";
	    $this->db();
	    $this->poule();
	}
	
	/**
	 * the database lib & connection tests
	 */
	public function db()
	{
	    $query = $this->db->query("select 1 nr");
	    $row = $query->row_array();
	    $this->unit->run($row, array('nr' => '1'), 'test run query and fetch row');
	    
	    $result = $query->result_array();
	    $this->unit->run($result, array(array('nr' => '1')), 'test fetch result');
	    
	    $this->report('db unit tests');
	}
	
	/**
	 * test Poule
	 */
	public function poule()
	{
	    $this->unit->run(get_class($this->poule), 'Poule', 'Poule');
	    
	    // construct
	    $poule = $this->poule->init(1);
	    $this->unit->run($poule->getPouleId(), 1, 'Poule id');
	    $this->unit->run($poule->getTeams(), 'is_array', 'Poule Teams');
	    $matches = $poule->getMatches();
	    $this->unit->run($matches, 'is_array', 'Poule Matches array');
	    $this->unit->run(! empty($matches[0]), true, 'Poule Matches exist');
	    $this->unit->run(get_class($matches[0]), 'Match', 'Poule Matches is Match');
	    
	    // create matches
	    //$poule->createMatches();
	    
	    // overview
	    $overview = $poule->getOverview();
	    $this->unit->run($overview, 'is_array', 'Poule Overview');
	    $this->unit->run(substr(key($overview), 0, 8), '1e ronde', 'Poule Overview key is round');
	    
	    $this->report('Poule unit tests');
	}
	
	/**
	 * generate the report view
	 * @param string $testName
	 */
	protected function report($testName)
	{
	    $data['testName'] = $testName;
	    $data['report'] = $this->unit->report();
	    $this->load->view('testReportView', $data);
	}
}

/* Location: ./application/controllers/home.php */