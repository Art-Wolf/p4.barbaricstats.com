<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Game extends CI_Controller {

	 public function __construct() {
                parent::__construct();

                if (is_null($this->session->userdata('current_page'))) {
                        $this->session->set_userdata('previous_page', 'public_main');
                } else {
                        $this->session->set_userdata('previous_page', $this->session->userdata('current_page'));
                }

                $this->session->set_userdata('current_page', substr($_SERVER['REQUEST_URI'],1));
        }

	public function send_vote($game_id, $target_name)
	{
		$this->load->helper(array('form','url'));
                $this->load->database();

		$this->load->model('users_db');

		$form_data = array('users.user_name' => $target_name);
		$target = $this->users_db->Get_id_by_name($form_data);

		$form_data = array ('votes.userid' => $this->session->userdata('user_id'),
                                    'votes.targetid' => $target->id,
				    'votes.gameid' => $game_id);

		$this->load->model('vote_model');

		$vote_return = $this->vote_model->insert($form_data);
	}

	public function index()
	{
                $this->load->helper(array('form','url'));
                $this->load->database();

                $this->load->view('header');

                $this->load->model('game_model');
                $data['game_list'] = $this->game_model->staging_games();
                $data['game_players'] = $this->game_model->current_game_players();

                $this->load->view('game_lobby', $data);
                $this->load->view('footer');
        }

	public function lobby()
	{
		$this->load->helper(array('form','url'));
		$this->load->database();

		$this->load->view('header');

		$this->load->model('game_model');
		$data['game_list'] = $this->game_model->staging_games();
		$data['game_players'] = $this->game_model->current_game_players();

		$this->load->view('game_lobby', $data);
		$this->load->view('footer');
	}

	public function lynch($game_id)
	{
		$this->load->database();
		$this->load->model('vote_model');

		$form_data = array('votes.gameid' => $game_id);

		$lynch_id = $this->vote_model->lynch($form_data);

		$this->load->model('users_db');

		$form_data = array('users.id' => $lynch_id);
		$lynch_name = $this->users_db->get_name_by_id($form_data);

		$form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The crowd roars and hangs ' . $lynch_name['user_name'] .'.',
                                                        'chat.game_id' => $game_id);
                $this->game_model->insert($form_data);

	}


	public function night($game_id)
	{
		$this->load->database();
                $this->load->model('game_model');

		$this->lynch($game_id);

		$form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 5);
                $this->game_model->game_state($form_data);
                $form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The sun has gone down, the towns folk reture to bed... and the mafia go to work.',
                                                        'chat.game_id' => $game_id);
                $this->game_model->insert($form_data);
	}

	public function end($game_id)
	{
		$this->load->database();
                $this->load->model('game_model');

                $form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 6);
                $this->game_model->game_state($form_data);

		$form_data = array ('game.id' => $game_id);
		$this->game_model->end_game($form_data);

                $form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The game is now over.',
                                                        'chat.game_id' => $game_id);
                $this->game_model->insert($form_data);
	}

	public function day($game_id)
        {
                $this->load->database();
                $this->load->model('game_model');

                $form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 3);
                $this->game_model->game_state($form_data);
                $form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The sun comes up and the town slowly wakes up to face the day.',
                                                        'chat.game_id' => $game_id);
                $this->game_model->insert($form_data);
        }

        public function vote($game_id)
        {
                $this->load->database();
                $this->load->model('game_model');

                $form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 4);
                $this->game_model->game_state($form_data);
                $form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The time has come vote on who will be held responsible for the murders in town!',
                                                        'chat.game_id' => $game_id);
                $this->game_model->insert($form_data);
        }

	public function checkstate($game_id, $state)
        {
                $this->load->database();
                $this->load->model('game_model');

                $form_data = array( 'game_state.game_id' => $game_id );

                $check_state = $this->game_model->get_latest_state($form_data);

                if(!isset($check_state[0]->state_id) || $check_state[0]->state_id < $state)
                {
                      	return true;
                }

		return false;
        }

	public function rand_bool()
	{
		return (rand(1,100) <= 30);
	}

	public function assignRoles($game_id)
	{
		$this->load->database();
		$this->load->model('game_model');

		$form_data = array ( 'game.id'=> $game_id );
		$data['game_info'] = $this->game_model->game_details($form_data);

		$current_count = 0;

		foreach ($data['game_info'] as $player)
                {
			if($player->role == 1)
			{
				$current_count++;				
			}
		}

		$mafia_count = 2;

		foreach ($data['game_info'] as $player)
		{
			if ($this->rand_bool() && $current_count < $mafia_count)
			{
				$form_data = array ('game.id' => $game_id,
					'game.userid' => $player->userid);
				$this->game_model->set_mob_role($form_data);
				$current_count++;
			}
		}
	}

	public function join($game_id)
	{
		$this->load->helper(array('form','url'));
                $this->load->database();

                $this->load->view('header');

                $this->load->model('game_model');

		$form_data = array ( 'game.id'=> $game_id,
				'game.userid' => $this->session->userdata('user_id'));

		if ($this->session->userdata('game_id') != $game_id)
		{
			$form_data = array ( 'game.id'=> $game_id,
                           'game.end_time' => null,
                           'game.userid' => $this->session->userdata('user_id'));
               		$this->game_model->join_game($form_data);

			$this->session->set_userdata('game_id', $game_id);
		}

		$form_data = array ( 'game.id'=> $game_id );
                $data['game_info'] = $this->game_model->game_details($form_data);

		if($this->checkState($game_id, 1))
		{
			$form_data = array ('game_state.game_id' => $game_id,
					'game_state.state_id' => 1);
			$this->game_model->game_state($form_data);			
		} 
		elseif (count($data['game_info']) == 7)
		{
			if ($this->checkState($game_id,2))
                	{
               		        $form_data = array ('game_state.game_id' => $game_id,
                	                                'game_state.state_id' => 2);
                	        $this->game_model->game_state($form_data);
				$form_data = array ('chat.username' => 'Moderator',
							'chat.message' => 'Roles are being assigned!',
							'chat.game_id' => $game_id);
				$this->game_model->insert($form_data);
				$this->assignRoles($game_id);

				$this->night($game_id);
                	}
		}

		$form_data = array ( 'game.id'=> $game_id );
                $data['game_info'] = $this->game_model->game_details($form_data);

                $this->load->view('game_center', $data);
                $this->load->view('footer');	
	}


	public function create()
	{
		$this->load->helper(array('form','url'));
                $this->load->database();

                $this->load->view('header');

                $this->load->model('game_model');

                $form_data = array (
                                'game.end_time' => null,
                                'game.userid' => $this->session->userdata('user_id'));

                $this->game_model->join_game($form_data);

		$game_id = $this->game_model->get_latest_game($form_data);

                $this->session->set_userdata('game_id', $game_id[0]->id);

                $form_data = array ( 'game.id'=> $game_id[0]->id );
                $data['game_info'] = $this->game_model->game_details($form_data);

		if($this->checkState($game_id[0]->id, 1))
                {
                        $form_data = array ('game_state.game_id' => $game_id[0]->id,
                                        'game_state.state_id' => 1);
                        $this->game_model->game_state($form_data);
                }

                $this->load->view('game_center', $data);
                $this->load->view('footer');
	}


	public function chat_message()
        {
                $this->load->helper(array('form','url'));
                $this->load->database();

                if($this->session->userdata('user_name')) {
                        $data = array ( 'chat.game_id'=> $this->session->userdata('game_id'),
					'chat.username' => $this->session->userdata('user_name'),
                                        'chat.message' => set_value('chat-message')
                        );
                }
                $this->load->model('chat_model');
                $this->chat_model->game_insert($data);
        }

	public function mafia_chat_message()
        {
                $this->load->helper(array('form','url'));
                $this->load->database();

                if($this->session->userdata('user_name')) {
                        $data = array ( 'mafia_chat.game_id'=> $this->session->userdata('game_id'),
                                        'mafia_chat.username' => $this->session->userdata('user_name'),
                                        'mafia_chat.message' => set_value('mafia-chat-message')
                        );
                }
                $this->load->model('chat_model');
                $this->chat_model->game_mafia_insert($data);
        }

	public function chat($game_id)
	{
		$this->load->helper(array('url'));
                $this->load->database();
                $this->load->model('chat_model');
		$form_data = array('chat.game_id' => $this->session->userdata('game_id'));
                $data['chat'] = $this->chat_model->get_public_game_latest($form_data);

                $this->load->model('game_model');

                $form_data = array( 'game_state.game_id' => $game_id );

		$check_state = $this->game_model->get_latest_state($form_data);
		
		if ($check_state[0]->state_id < "6")
		{
			$total_time = $this->game_model->get_total_time($form_data);
			if($total_time[0]->timestamp < -500)
			{
				$this->end($game_id);
			}
			else {
				if($check_state[0]->state_id == "3" && $check_state[0]->timestamp < -30)
	                	{
					$this->vote($game_id);
	                	} elseif($check_state[0]->state_id == "4" && $check_state[0]->timestamp < -30)
	                	{
					$this->night($game_id);
	                	} elseif ($check_state[0]->state_id == "5" && $check_state[0]->timestamp < -30)
	               	 	{
					$this->day($game_id);
	                	}
			}
		}
                $this->load->view('ajax_chat_box', $data);
	}

	public function mafia_chat()
        {
                $this->load->helper(array('url'));
                $this->load->database();
                $this->load->model('chat_model');
                $form_data = array('mafia_chat.game_id' => $this->session->userdata('game_id'));
                $data['chat'] = $this->chat_model->get_mafia_game_latest($form_data);

                $this->load->view('ajax_chat_box', $data);
        }

	public function get_player_state($game_id)
	{
		$this->load->helper(array('url'));
                $this->load->database();
                $this->load->model('game_model');

		$form_data = array ( 'game.id'=> $game_id );
                $data['game_info'] = $this->game_model->game_details($form_data);

                $this->load->view('ajax_player_state', $data);
	}

	public function get_state()
	{
		$this->load->helper(array('url'));
                $this->load->database();
                $this->load->model('game_model');

		$form_data = array('game_state.game_id' => $this->session->userdata('game_id'),
					'game_state.timestamp' => '(SELECT MAX(timestamp) FROM game_state WHERE game_id = ' . $this->session->userdata('game_id') . ')');
		$current_state = $this->game_model->get_latest_state($form_data);

		$data['game_state'] = array('state' => $current_state[0]->state_id);

		switch($current_state[0]->state_id) {
			case 1:
				$data['game_state'] = array( 'state' => 'startup.png');
				break;
			case 2:
				$data['game_state'] = array( 'state' => 'waiting.png');
				break;
			case 3:
				$data['game_state'] = array( 'state' => 'daytime.png');
				break;
			case 4:
				$data['game_state'] = array( 'state' => 'lynch.png');
                                break;
			case 5:
				$data['game_state'] = array( 'state' => 'nighttime.png');
                                break;
			case 6:
				$data['game_state'] = array( 'state' => 'mafiahit.png');
                                break;
		}

                $this->load->view('ajax_game_state', $data);
	} 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
