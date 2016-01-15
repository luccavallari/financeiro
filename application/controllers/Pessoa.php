<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');

class Pessoa extends CI_Controller {

    private $tabela = 'dbusuario';

    function __construct() 
    {
        parent::__construct();
        $this->load->model('Crud', '', true);
        $this->load->model('Util', '', true);
        $this->load->model('PessoaDAO', '', true);
    }
    
	public function index()
	{
		$this->load->view('header', ['title'=>'Cadastro de cliente, fornecedores, funcionarios']);
		$this->load->view('menu');
		$this->load->view('pessoa', ['estados' => $this->PessoaDAO->getEstados()]);
		$this->load->view('footer');
	}

    public function setValidate()
    {
      //Seta as regras de validações dos campos
      $this->form_validation->set_rules('nome', '<b>Nome</b>', 'trim|required');
      $this->form_validation->set_rules('sexo', '<b>Sexo</b>', 'trim|required');
      $this->form_validation->set_rules('nascimento', '<b>Data de Nascimento</b>', 'trim|required');
      $this->form_validation->set_rules('email', '<b>E-mail</b>', 'trim|required|valid_email');
      $this->form_validation->set_rules('perfil', '<b>Perfil</b>', 'trim|required');
     
      if($this->input->post('perfil') == '1'){
        $this->form_validation->set_rules('crmv', '<b>CRMV</b>', 'trim|required');
      }

      $existesenha = $this->input->post('existesenha');

      //verifica se veio senha
      if($existesenha != '0'){
        $this->form_validation->set_rules('senha', '<b>Senha</b>', 'trim|required');
        $this->form_validation->set_rules('cofsenha', '<b>Confirma Senha</b>', 'trim|required');
      }

      $this->form_validation->set_rules('telefone1', '<b>Telefone 1</b>', 'trim|required');
      $this->form_validation->set_rules('endereco', '<b>Endereço</b>', 'trim|required');
      $this->form_validation->set_rules('bairro', '<b>Bairro</b>', 'trim|required');
      $this->form_validation->set_rules('cidade', '<b>Cidade</b>', 'trim|required');

      $this->form_validation->set_error_delimiters('<span>', '</span>');        
    }         
    
    public function create()
    {
    
    }
    
    public function read()
    {
        
    }
    
    public function readById()
    {
    
    }
    
    public function update()
    {
        
    } 
    
    public function destroy()
    {
    
    }
    
    public function getCidade($id = 0) 
    {
 
        try {

            $data = array();

            $cidade = $this->PessoaDAO->getCidade($id);

            if(!$cidade){
                $data['msg'] = array('tipo' => 'e', 'texto' => 'O registro com codigo <b>'.$id.'</b> não existe!');
            }else{  

                //Carrega todas as cidades 
                $data['cidades'] = '';

                foreach ($cidade as $value) {
                    $data['cidades'] .= '<option value="' . $value->id . '">' . $value->nome . ' </option>  ';
                }
            }
            
        } catch (Exception $exc) {
            $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro ao excluir controller: <b>Usuario.</b>' . $exc->getMessage());
        }
      echo json_encode($data);
    }
 
}