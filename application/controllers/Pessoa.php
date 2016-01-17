<?php

defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('America/Sao_Paulo');

class Pessoa extends CI_Controller {

    private $tabela = 'pessoa';

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

    //Seta as regras de validações dos campos
    public function setRegrasValidacao()
    {
        $this->form_validation->set_rules('nome', '<b>Nome</b>', 'trim|required');
        $this->form_validation->set_rules('email', '<b>E-mail</b>', 'trim|required|valid_email');
        $this->form_validation->set_rules('telefone', '<b>Telefone</b>', 'trim|required');
        $this->form_validation->set_rules('endereco', '<b>Endereço</b>', 'trim|required');
        $this->form_validation->set_rules('bairro', '<b>Bairro</b>', 'trim|required');
        $this->form_validation->set_rules('cidade', '<b>Cidade</b>', 'trim|required');

        $this->form_validation->set_error_delimiters('<span>', '</span>');        
    }         
    
    private function getDados()
    {
        //preenche os dados do endereco
        $endereco = [
            'endereco' => $this->input->post('endereco'),
            'complemento' => $this->input->post('complemento'),
            'bairro' => $this->input->post('bairro'),
            'cep' => $this->input->post('cep'),
            'cidade_id' => $this->input->post('cidade'),
            'pessoa_id' => '',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ];
        
        $pessoa = [
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'telefone' => $this->input->post('telefone'),
            'celular' => $this->input->post('celular'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")        
        ];
                
        $dados = ['endereco' => $endereco, 'pessoa' => $pessoa];
        
        return $dados;
    }
    
    
    public function create()
    {
        try {
            $data = array();

             //Seta as validações
             $this->setRegrasValidacao();   

            //Testa as validações
            if ($this->form_validation->run() === false) {
                $data['msg'] = array('tipo' => 'e', 'texto' => validation_errors());
            } else {
                
                //pega todos os dados necessarios da view
                $pessoa = $this->getDados();
                
                //Condição para verificar se os dados foram gravados com exito
                $pessoa_id = $this->Crud->create($this->tabela, $pessoa['pessoa'], true);

                if($pessoa_id) {
                    
                    //adiciona o ultimo campo faltando a key do pessoa
                    $pessoa['endereco']['pessoa_id'] = $pessoa_id;

                    //grava o endereco
                    if(!$this->Crud->create('endereco', $pessoa['endereco'], false)) {
                      $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->pessoa->salvar->endereco: Erro ao salvar o endereco do pessoa');
                    }else {
                      $data['msg'] = array('tipo' => 's', 'texto' => 'Registro gravado com sucesso.');
                    }

                } else {
                    $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->pessoa->salvar: Por favor contate o Administrador: Allan, allangcruz@gmail.com');
                }
            }
            
        } catch (Exception $exc) {
            $data['msg'] = array('tipo' => 'e', 'texto' => $exc->getMessage());
        }
        echo json_encode($data);    
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
                $this->table->set_heading('Nome', 'Email', 'Telefones', 'Estado', 'Cidade', 'Ação');//Cria o cabeçalho

                //exibe a lista de pessoa           
                foreach ($resultado as $value) {

                $this->table->add_row(
                        $value->nome,
                        $value->email,
                        $value->telefone,
                        $value->estado,
                        $value->cidade,
                        '<a class="btn btn-sm btn-default" href="javascript:pesquisar(\'#form_pessoa_consulta\',\'readById/'.$value->id.'\',\'json\', function(){}, retornoDetalhar);" title="Detalhar" ><i class="glyphicon glyphicon-search"></i></a>&nbsp;'.
                        '<a class="btn btn-sm btn-default" href="javascript:pesquisar(\'#form_pessoa_consulta\',\'readById/'.$value->id.'\',\'json\', function(){}, retornoPesquisar);" title="Alterar" ><i class="glyphicon glyphicon-pencil"></i></a>&nbsp;'.
                        '<a class="btn btn-sm btn-default" href="javascript:excluir(\'#form_pessoa_consulta\', \'destroy/'.$value->id.'\',\'' . $value->nome . '\', \'json\',antesEnviar(\'#resposta_excluir\',\'#load_consulta\'),retornoExcluir);" title="Excluir" ><i class="glyphicon glyphicon-trash"></i></a>'
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
            $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro ao excluir controller: <b>pessoa.</b>' . $exc->getMessage());
        }
      echo json_encode($data);
    }
 
}