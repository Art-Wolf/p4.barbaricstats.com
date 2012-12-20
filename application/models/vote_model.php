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

	function lynch($data)
	{
		$this->db->escape($data);

		$this->db->select('votes.targetid');
		$this->db->from('votes');
		$this->db->where(' votes.gameid = ' . $data['votes.gameid'] . ' AND votes.timestamp >= NOW() - INTERVAL 40 SECOND');
		$this->db->group_by('votes.targetid');
		$this->db->order_by('COUNT(*)i', 'desc');
		$this->db->limit(0,1);

		$query = $this->db->query('SELECT votes.targetid FROM votes WHERE votes.gameid = ' . $data['votes.gameid'] . ' AND votes.timestamp >= NOW() - INTERVAL 40 SECOND GROUP BY votes.targetid ORDER BY COUNT(*) DESC LIMIT 0,1');
		$lynch = $query->row();

                $this->db->query('UPDATE game SET game.dead = 1 WHERE game.id = ' . $data['votes.gameid'] . ' AND game.userid = ' . $lynch->targetid .');

		return $lynch->targetid;
	}
}
?>
