<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_chat extends CI_Controller {

	 public function __construct() {
                parent::__construct();

                if (is_null($this->session->userdata('current_page'))) {
                        $this->session->set_userdata('previous_page', 'public_main');
                } else {
                        $this->session->set_userdata('previous_page', $this->session->userdata('current_page'));
                }

                $this->session->set_userdata('current_page', substr($_SERVER['REQUEST_URI'],1));
        }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($game_id)
	{
		$this->load->helper(array('url'));
		$this->load->database();
		$this->load->model('chat_model');

		$data['chat'] = $this->chat_model->get_latest();

		$this->load->model('game_model');

		$form_data = array( 'game_state.game_id' => $game_id );

                $check_state = $this->game_model->get_latest_state($form_data);

                if($check_state[0]->state_id == 4 && $check_state[0]->timestamp > 30)
                {
			$form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 3);
                } elseif ($check_state[0]->state_id == 3 && $check_state[0]->timestamp > 30)
                {
                        $form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 4);
                }

		$this->game_model->game_state($form_data);

		$this->load->view('ajax_chat_box', $data);
	}

	public function lobby()
	{
                $this->load->helper(array('url'));
                $this->load->database();
                $this->load->model('chat_model');

                $data['chat'] = $this->chat_model->get_latest();

                $this->load->view('ajax_chat_box', $data);
        }

	public function submit()
	{
		$this->load->helper(array('form','url'));
		$this->load->database();

		if($this->session->userdata('user_name')) {
			$data = array (	'chat.username' => $this->session->userdata('user_name'),
					'chat.message' => set_value('chat-message')
			);
		}
		$this->load->model('chat_model');
		$this->chat_model->insert($data);
	}

	public function night($game_id)
        {
                $this->load->database();
                $this->load->model('game_model');

                $form_data = array ('game_state.game_id' => $game_id,
                                                        'game_state.state_id' => 4);
                $this->game_model->game_state($form_data);
                $form_data = array ('chat.username' => 'Moderator',
                                                        'chat.message' => 'The sun has gone down, the towns folk reture to bed... and the mafia go to work.',
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
