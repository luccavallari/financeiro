<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | Class->Model "Crud"
  | -------------------------------------------------------------------
  | Model que abstrai as funcionalidades basicas do banco de dados
  | url: ./application/model/Crud.php
  | @author Allan Gonçalves da cruz <allangcruz@gmail.com>
  | @version 1.0.0
  |
 */
class Crud extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /*
     | -------------------------------------------------------------------
     | Metodo "create"
     | -------------------------------------------------------------------
     | Metodo que inseri valores no banco de dados
     | @param : $table => nome da tabela.
     | @param : $array => vetor com os chave e valor
     | @param : $tipo_retorno => retorna o id ou true ou false
     | @return : retona numero de linhas afetadas
     |
    */
    public function create($table, $array,$tipo_retorno) 
    {   
        $this->db->insert($table, $array);

        if($tipo_retorno)
          return $this->db->insert_id();
        else 
          return $this->db->affected_rows();
    }     

     /*
     | -------------------------------------------------------------------
     | Metodo "createMultiple"
     | -------------------------------------------------------------------
     | Metodo que inseri valores no banco de dados
     | @param : $table => nome da tabela.
     | @param : $array => vetor com os chave e valor
     | @param : $tipo_retorno => retorna o id ou true ou false
     | @return : retona numero de linhas afetadas
     |
    */
    public function createMultiple($table, $array,$tipo_retorno) 
    {
        $this->db->insert_batch($table, $array);
        if($tipo_retorno){
          return $this->db->insert_id();
        }else {
          return $this->db->affected_rows();
        }
    }

     /*
     | -------------------------------------------------------------------
     | Metodo "update"
     | -------------------------------------------------------------------
     | Metodo que altera valores no banco de dados
     | @param : $table => nome da tabela.
     | @param : $array => vetor com os chave e valor, principalmente a primary key.
     | @return : retona true ou false
     |
    */    
    public function update($table, $array) 
    {
        $this->db->where('id', $array['id']);
        $this->db->update($table, $array);
        
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }


     /*
     | -------------------------------------------------------------------
     | Metodo "disable"
     | -------------------------------------------------------------------
     | Metodo que desabilita registro no banco de dados
     | @param : $table => nome da tabela.
     | @param : $id => primary key do registro.
     | @return : retona true ou false
     |
    */    
    public function disable($table, $id, $coluna) 
    {
        $this->db->where($coluna, $id);
        $this->db->update($table, array('deleted'=>'1'));
        
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

     /*
     | -------------------------------------------------------------------
     | Metodo "disableMultiple"
     | -------------------------------------------------------------------
     | Metodo que desabilita registro no banco de dados
     | @param : $table => nome da tabela.
     | @param : $array => lista de itens
     | @param : $id => primary key do registro.
     | @return : retona true ou false
     |
    */    
    public function disableMultiple($table,$array,$id) 
    {
        $this->db->update_batch($table, $array,$id);
        
        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }


     /*
     | -------------------------------------------------------------------
     | Metodo "delete"
     | -------------------------------------------------------------------
     | Metodo que exclui registro no banco de dados
     | @param : $table => nome da tabela.
     | @param : $id => primary key.
     | @param : $coluna_db => coluna do banco de dados utilizado para exclusao
     | @return : retona true ou false
     |
    */      
    public function delete($table, $id,$coluna_db) 
    {
        $this->db->where($coluna_db, $id);
        $this->db->delete($table);
        if($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }


     /*
     | -------------------------------------------------------------------
     | Metodo "read"
     | -------------------------------------------------------------------
     | Metodo que pesquisa apenas um registro
     | @param : $table => nome da tabela.
     | @param : $id => primary key.
     | @return : um vetor com os dados prenchidos
     |
    */     
    public function read($tabela, $id,$coluna_db) 
    {
        $this->db->where('deleted', '0');
        $this->db->where($coluna_db, $id);
        return $this->db->get($tabela)->result();
    }
    
     /*
     | -------------------------------------------------------------------
     | Metodo "consultar"
     | -------------------------------------------------------------------
     | Metodo que consulta uma lista de sala filtrando por nome
     | @param : $config=> campo que configura a ordenação da consulta enter(asc,desc)
     |
    */
    function consultAll($tabela,$coluna_db, $campo,$limite,$apartir,array $ordem) 
    {
        $this->db->where('deleted', '0');
        $this->db->like($coluna_db, $campo);
        $this->db->order_by($ordem['colunadb'],$ordem['tipo']);          

        //teste se é para pesquisar todos, ou com limite
        if($limite)
            return $this->db->get($tabela, $limite, $apartir)->result();
        else
            return $this->db->get($tabela)->result();
    }


     /*
     | -------------------------------------------------------------------
     | Metodo "countAll"
     | -------------------------------------------------------------------
     | Metodo que conta a quantidade de registro
     | @param : $table => nome da tabela.
     | @return : quantidade de registro encontrados
     |
    */  
    function countAll($table,$condicao,$coluna_db,$campo)
    {
        if($condicao){
          $this->db->where('deleted', '0');
          $this->db->where($coluna_db, $campo);
          $this->db->from($table);
          return $this->db->count_all_results();

        }else{
          $this->db->where('deleted', '0');
          $this->db->from($table);
          return $this->db->count_all_results();
        }
    }

     /*
     | -------------------------------------------------------------------
     | Metodo "verificaExiste"
     | -------------------------------------------------------------------
     | Metodo que verifica se o registro já existe no banco de dados
     | @param : $tabela => nome da tabela
     | @param : $array => vetor com o campo e o id para comparacao
     | @param : $acao => acao de salvar ou alterar
     | @param : $colunadb => coluna do banco que sera comparada
    */
    function verificaExiste($tabela,$array,$acao,$colunadb) 
    { 

        $this->db->where($colunadb,$array[$colunadb]);
        $query = $this->db->get($tabela);
        $resultado = $query->result();
        
        $valor_db = '';
        $id=0;

        foreach ($resultado as $row) {
            $valor_db = $row->$colunadb;
            $id=$row->id;
        }
        
       if($acao == 'salvar'){                                       // devido ao erro de não identificar se o  
            if (strtolower($valor_db) == strtolower($array[$colunadb])) {// nome da tela é igual ao nome do bando, 
                                                                  // transformos as os valore em letras minusculas
                                                                  // e assim faço a comparação 
              return true;//não grava o registro 
             } else {
              return false;//permite gravar o registro
            }

        }else {


             if ((strtolower($valor_db) == strtolower($array[$colunadb]))&&($id==$array['id'])) { 
                return false;//permite alterar o registro
                
             } else if((strtolower($valor_db) == strtolower($array[$colunadb]))&&($id!=$array['id'])){
                return true;//nao alterar

            }else if((strtolower($valor_db) != strtolower($array[$colunadb]))&&($id!=$array['id'])){               
                return false;//alterar
            }
        }
    }

}