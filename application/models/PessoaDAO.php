<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  | Class->Model "PessoaDAO"
  | -------------------------------------------------------------------
  | Model que implemnta as funcionalidade da entidade pessoa
  | url: ./application/model/Pessoadao.php
  | @author Allan gonçalves da cruz <allangcruz@gmail.com>  
  | @version 1.0.0
  |
 */
class PessoaDAO extends CI_Model {

    protected $tabela = 'pessoa';

    //Consulta todos os estados existe
    public function getEstados(){
        $this->db->order_by('id','asc');          
        $result =  $this->db->get('estado')->result();

        $estados = '';

        foreach ($result as $value) {
            $estados .= '<option value="' . $value->id . '">' . $value->nome . ' </option>  ';
        }

        return $estados;
    }

    //Consulta todos os estados existe
    public function getCidade($id){
        $this->db->where('estado', $id);
        $this->db->order_by('id','asc');  
        
        return $this->db->get('cidade')->result();
    }

    //conta para gerar a paginacao
    public function countAll($filtro, $descricao)
    {
        $this->db->from($this->tabela.' t1');
        $this->db->join('endereco t2', 't1.id = t2.pessoa_id', 'INNER');
        $this->db->join('cidade t3', 't3.id = t2.cidade_id', 'INNER');
        $this->db->join('estado t4', 't4.id = t3.estado', 'INNER');
        $this->db->where('t1.deleted_at', '0');
        
        switch($filtro)
        {
            case '0':
                $this->db->like('t1.nome', $descricao);
            break;
                
            case '1':
                $this->db->like('t1.email', $descricao);
            break;            

            case '2':
                $this->db->like('t1.telefone', $descricao);
            break;
                
            default:
                $this->db->like('t1.nome', $descricao);
            break;
            
        }

        return $this->db->count_all_results();        
    }

    //retorna todos os dados necessarios
    public function listAll($filtro, $descricao, $limite, $apartir) 
    {
        $this->db->select("t1.id, 
                           t1.nome, 
                           t1.email, 
                           t1.telefone,
                           t3.nome cidade,
                           t4.nome estado");
        $this->db->join('endereco t2', 't1.id = t2.pessoa_id', 'INNER');
        $this->db->join('cidade t3', 't3.id = t2.cidade_id', 'INNER');
        $this->db->join('estado t4', 't4.id = t3.estado', 'INNER');
        $this->db->where('t1.deleted_at', '0');
        $this->db->order_by('id desc');

         switch($filtro)
        {
            case '0':
                $this->db->like('t1.nome', $descricao);
            break;
                
            case '1':
                $this->db->like('t1.email', $descricao);
            break;

            case '2':
                $this->db->like('t1.telefone', $descricao);
            break;

            default:
                $this->db->like('t1.nome', $descricao);
            break;
            
        }

        //teste se é para pesquisar todos, ou com limite
        if($limite) {
            return $this->db->get($this->tabela.' t1', $limite, $apartir)->result();
        } else {
            return $this->db->get($this->tabela.' t1')->result();
        }    
    }

    //retorna apenas um registro para edicao
    public function readById($id)
    {
        $this->db->select("t1.id, 
                           t1.nome, 
                           t1.email, 
                           t1.telefone,
                           t2.cidade_id cidade,
                           t2.endereco,
                           t2.complemento,
                           t2.bairro,
                           t2.cep,
                           t3.estado");
        $this->db->join('endereco t2', 't1.id = t2.pessoa_id', 'INNER');
        $this->db->join('cidade t3', 't3.id = t2.cidade_id', 'INNER');
        $this->db->where('t1.deleted_at', '0');
        $this->db->where('t1.id', $id);
        $this->db->limit(1); 

        return $this->db->get($this->tabela.' t1')->row();     
    }
}