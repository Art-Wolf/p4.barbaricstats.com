<?php

class Users_db extends CI_Model {

	function Register($form_data) {
		$this->db->insert('users', $form_data);
		
		if ($this->db->affected_rows() == '1') {
			return TRUE;
		}
		
		return FALSE;
	}

	function Signin($form_data) {
		$this->db->escape($form_data);

		$sql = "SELECT COUNT(*) AS CNT FROM users WHERE user_name = ? AND password = ?";

		$query = $this->db->query($sql, $form_data);

		if ($query->num_rows() == 1) {
			$row = $query->row_array();

			if ($row['CNT'] > 0) {
                        	return TRUE;
			}
                }

                return FALSE;
	}

	function Log_login($form_data) {
		$this->db->insert('logins', $form_data);

		if ($this->db->affected_rows() == '1') {
                        return TRUE;
                }

                return FALSE;
	}

	function Get_list($form_data) {
		$this->db->select('users.id, users.user_name, follows.followed_id');
                $this->db->from('users');
                $this->db->join('follows', 'follows.followed_id = users.id AND follows.user_id = ' . $form_data['follows.user_id'], 'left outer');
		$this->db->where('users.id <> ' . $form_data['follows.user_id']);

                return $this->db->get()->result();

	}

	function Get_id($form_data) {
		$this->db->escape($form_data);

		$this->db->select('id');
		$this->db->from('users');
		$this->db->where($form_data);

		return $this->db->get()->row();
	}

	function Get_id_by_name($form_data) {
                $this->db->escape($form_data);

                $this->db->select('id');
                $this->db->from('users');
                $this->db->where("users.user_name = '" . $form_data['users.user_name'] . "'");

                return $this->db->get()->row();
        }

	function Start_following($form_data) {
		$this->db->insert('follows', $form_data);

		if ($this->db->affected_rows() == '1') {
                        return TRUE;
                }

                return FALSE;
	}

	function Stop_following($form_data) {
                $this->db->delete('follows', $form_data);

                if ($this->db->affected_rows() == '1') {
                        return TRUE;
                }

                return FALSE;
        }

	function Get_following_list($form_data) {

		$this->db->select('users.id, users.user_name');
                $this->db->from('users');
                $this->db->join('follows', 'follows.followed_id = users.id');
                $this->db->where($form_data);

                return $this->db->get()->result();
	}

	function Get_profile($form_data) {
		$this->db->select('users.id, users.user_name, users.email_address, users.website, users.bio, users.photo, users.location, COUNT(f2.followed_id) followed_count, MAX(login_timestamp) last_login');
		$this->db->join('follows f2', 'f2.followed_id = users.id', 'left outer');
		$this->db->join('logins', 'logins.user_id = users.id', 'left outer');
		$this->db->where($form_data);
		$this->db->group_by('users.id, users.user_name, users.website, users.bio, users.photo, users.location');

		return $this->db->get('users')->row();
	}
}
?>
