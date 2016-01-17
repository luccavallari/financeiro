<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Movimentacao extends CI_Controller {

    function __construct() 
    {
        parent::__construct();
    }
    
    
	public function index()
	{
		$this->load->view('header', ['title'=>'Movimentação de conta a pagar e receber']);
		$this->load->view('menu');
		$this->load->view('conta');
		$this->load->view('footer');
	}
}