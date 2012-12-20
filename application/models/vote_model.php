<?php
class Vote_model extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->escape($data);

		$this->db->insert('votes', $data);

		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		} 

		return FALSE;
	}
}
?>
