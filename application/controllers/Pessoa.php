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
    
    public function read($offset = 0)
    {
        try {
             
            $filtro = $this->input->post('filtro');
            $descricao = $this->input->post('expressao');
            
            $limite =  10;
            $config['base_url'] = site_url('pessoa/read/');
            $config['total_rows'] = $this->PessoaDAO->countAll($filtro, $descricao);
            $config['per_page'] = $limite;
            $config['show_count'] = true;
            $config['div'] = '#resposta_consulta'; 

            $this->jquery_pagination->initialize($config);
            $dados['paginacao'] = $this->jquery_pagination->create_links();

            $resultado = $this->PessoaDAO->listAll($filtro, $descricao, $limite, $offset);

            if ($resultado == null) {
                echo '<b>Nenhum registro encontrado<b/>';
            } else {
                $this->table->set_template(array('table_open'=>'<table class="table table-hover table-bordered">'));
                $this->table->set_empty('');//Se a tabela estiver vazia
                $this->table->set_heading('Nome', 'Email', 'Telefones','Ação');//Cria o cabeçalho

                //exibe a lista de usuario           
                foreach ($resultado as $value) {

                $this->table->add_row(
                        $value->nome,
                        $value->email,
                        $value->telefone,
                        '<a href="javascript:pesquisar(\'#form_pessoa_consulta\',\'readById/'.$value->id.'\',\'json\', function(){}, retornoDetalhar);" title="Detalhar" ><i class="ls-ico-search"></i></a>'.
                        '<a href="javascript:pesquisar(\'#form_pessoa_consulta\',\'readById/'.$value->id.'\',\'json\', function(){}, retornoPesquisar);" title="Alterar" ><i class="ls-ico-pencil"></i></a>'.
                        '<a href="javascript:excluir(\'#form_pessoa_consulta\', \'destroy/'.$value->id.'\',\'' . $value->nome . '\', \'json\',antesEnviar(\'#resposta_excluir\',\'#load_consulta\'),retornoExcluir);" title="Excluir" ><i class="ls-ico-remove"></i></a>'
                  );           
                }
          
                //gera a tabela e a paginação    
                echo $this->table->generate();
                echo $dados['paginacao'];
            }
        } catch (Exception $exc) {
            echo 'Erro Sala->controller->consultar: ' . $exc->getMessage();
        }        
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