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

    //Seta as regras de validações dos campos
    public function setRegrasValidacao()
    {
        $this->form_validation->set_rules('perfil', '<b>Perfil</b>', 'trim|required');
        $this->form_validation->set_rules('categoria', '<b>Categoria</b>', 'trim|required');
        $this->form_validation->set_rules('nome', '<b>Nome</b>', 'trim|required');
        $this->form_validation->set_rules('email', '<b>E-mail</b>', 'trim|required|valid_email');
        
        $tipo = $this->input->post('tipo');
        
        if($tipo === 'F') {
            //pessoa fisica  
            $this->form_validation->set_rules('sexo', '<b>Sexo</b>', 'trim|required');
            $this->form_validation->set_rules('cpf', '<b>CPF</b>', 'trim|required');
            $this->form_validation->set_rules('nascimento', '<b>Data de Nascimento</b>', 'trim|required');
        } else {
            //pesso juridica
            //pessoa fisica  
            $this->form_validation->set_rules('razao_social', '<b>Razão Social</b>', 'trim|required');
            $this->form_validation->set_rules('cnpj', '<b>CNPJ</b>', 'trim|required');
            $this->form_validation->set_rules('inscricao_estadual', '<b>Inscricão Estadual</b>', 'trim|required');
        }
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
        
        if($this->input->post('endereco') == 'F'){
            $fisica = [
                'nascimento' => $this->Util->dataBanco($this->input->post('nascimento')),
                'sexo' => $this->input->post('sexo'),
                'cpf' => $this->input->post('cpf')
            ];
            
        } else {
            $juridica = [
                'inscricao_estadual' => $this->input->post('inscricao_estadual'),
                'cnpj' => $this->input->post('cnpj')
            ];        
        }

        
        $dados = ['endereco' => $endereco, 'pessoa' => $pessoa, 'fisica' => $fisica, 'juridica' => $juridica];
        
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
                $perfil = $this->input->post('perfil');
                $crmv = $this->input->post('crmv');

                $arrayUsuario = array(
                    'nome' => $this->input->post('nome'),
                    'nascimento' => $this->Util->dataBanco($this->input->post('nascimento')),
                    'sexo' => $this->input->post('sexo'),
                    'perfil' => $this->input->post('perfil'),
                    'email' => $this->input->post('email'),
                    'senha' => md5($this->input->post('senha')),
                    'site' => 1,
                    'confirma_email' => 1,
                    'telefone1' => $this->input->post('telefone1'),
                    'telefone2' => $this->input->post('telefone2'),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                );


                

                //verifica se já existe no Banco de dados
                $email_existe = $this->Crud->verificaExiste($this->tabela, $arrayUsuario, 'salvar','email');

                if ($email_existe) {
                    $data['msg'] = array('tipo' => 'a', 'texto' => 'O e-mail <b>' . $arrayUsuario['email'] . '</b> já existe.');
                
                }else if($crmv == '' && $perfil == '0'){

                    //não é necessario cadastrar o veterinario

                    //Condição para verificar se os dados foram gravados com exito
                    $usuario_id = $this->Crud->create($this->tabela, $arrayUsuario,true);

                    if ($usuario_id) {

                        //adiciona o ultimo campo faltando a key do usuario
                        $arrayEndereco['usuario_id']= $usuario_id; 

                        //grava o endereco
                        if(!$this->Crud->create('dbendereco', $arrayEndereco,false)){
                          $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->usuario->salvar->endereco: Erro ao salvar o endereco do usuario');
                        }else{
                          $data['msg'] = array('tipo' => 's', 'texto' => 'Registro gravado com sucesso.');
                        }

                    } else {
                        $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->usuario->salvar: Por favor contate o Administrador: Allan, allangcruz@gmail.com');
                    }

                }else{

                    //verifica se existe crmv
                    $crmv_existe = $this->Crud->verificaExiste('dbveterinario', $arrayVeterinario, 'salvar','crmv');

                    if ($crmv_existe) {
                        $data['msg'] = array('tipo' => 'a', 'texto' => 'O crmv <b>' . $arrayVeterinario['crmv'] . '</b> já existe.');
                    }else{

                        //grava o usuario administrador com crmv
                        //Condição para verificar se os dados foram gravados com exito
                        $usuario_id = $this->Crud->create($this->tabela, $arrayUsuario,true);

                        if ($usuario_id) {

                            //adiciona o ultimo campo faltando a key do usuario
                            $arrayEndereco['usuario_id']= $usuario_id; 

                            //grava o endereco
                            if(!$this->Crud->create('dbendereco', $arrayEndereco,false)){
                              $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->usuario->salvar->endereco: Erro ao salvar o endereco do usuario');
                            }else{

                                //adiciona o ultimo campo faltando a key do usuario
                                $arrayVeterinario['usuario_id']= $usuario_id; 

                                //grava o veterinario
                                if(!$this->Crud->create('dbveterinario', $arrayVeterinario,false)){
                                  $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->usuario->salvar->endereco: Erro ao salvar o endereco do usuario');
                                }else{
                                  $data['msg'] = array('tipo' => 's', 'texto' => 'Registro gravado com sucesso.');
                                }

                            }

                        } else {
                            $data['msg'] = array('tipo' => 'e', 'texto' => 'Erro->usuario->salvar: Por favor contate o Administrador: Allan, allangcruz@gmail.com');
                        }

                    }
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