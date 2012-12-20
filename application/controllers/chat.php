<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Chat extends CI_Controller {

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
	public function index()
	{
		$this->load->helper(array('form','url'));

		$this->load->view('header');
		$this->load->view('chat_box');
		$this->load->view('footer');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
