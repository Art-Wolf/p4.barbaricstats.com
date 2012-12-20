<?php
class Chat_model extends CI_Model 
{

	function __construct()
	{
		parent::__construct();
	}

	function insert($data)
	{
		$this->db->escape($data);

		$this->db->insert('chat', $data);

		if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		} 

		return FALSE;
	}

	function game_insert($data)
        {
                $this->db->escape($data);

		$this->db->select('COUNT(*) cnt');
		$this->db->from('game');
		$this->db->join('game_state', 'game.id = game_state.game_id');
		$this->db->where('game_state.state_id < 5 AND game.id = ' . $data['chat.game_id']);

		$result = $this->db->get()->result();
		if($result['cnt'] == 0)
		{
                	$this->db->insert('chat', $data);

                	if ($this->db->affected_rows() == '1')
                	{
                	        return TRUE;
                	}
		}
                return FALSE;
        }

	function game_mafia_insert($data)
        {
                $this->db->escape($data);

                $this->db->insert('mafia_chat', $data);

                if ($this->db->affected_rows() == '1')
                {
                        return TRUE;
                }

                return FALSE;
        }

	function get_latest()
	{
		$this->db->select('chat.username, chat.timestamp, chat.message');
		$this->db->from('chat');
		$this->db->where('chat.game_id = 0');
		$this->db->limit(50);
		$this->db->order_by('chat.id', 'desc');

		return $this->db->get()->result();
	}

	function get_public_game_latest($data)
        {
		$this->db->escape($data);

                $this->db->select('chat.username, chat.timestamp, chat.message');
                $this->db->from('chat');
		$this->db->where($data);		
                $this->db->limit(50);
                $this->db->order_by('chat.id', 'desc');

                return $this->db->get()->result();
        }

	function get_mafia_game_latest($data)
        {
                $this->db->escape($data);

                $this->db->select('mafia_chat.username, mafia_chat.timestamp, mafia_chat.message');
                $this->db->from('mafia_chat');
                $this->db->where($data);
                $this->db->limit(50);
                $this->db->order_by('mafia_chat.id', 'desc');

                return $this->db->get()->result();
        }
}
?>
