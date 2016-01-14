<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pessoa extends CI_Controller {

	public function index()
	{
		$this->load->view('header', ['title'=>'Cadastro de cliente, fornecedores, funcionarios']);
		$this->load->view('menu');
		$this->load->view('pessoa');
		$this->load->view('footer');
	}
}