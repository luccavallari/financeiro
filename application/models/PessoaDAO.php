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
        $this->db->from($this->tabela);
        $this->db->where('deleted', '0');
        
        switch($filtro)
        {
            case '0':
                $this->db->like('nome', $descricao);
            break;
                
            case '1':
                $this->db->like('email', $descricao);
            break;            

            case '2':
                $this->db->like('telefone1', $descricao);
            break;
                
            default:
                $this->db->like('nome', $descricao);
            break;
            
        }

        return $this->db->count_all_results();        
    }

    //retorna todos os dados necessarios
    public function listAll($filtro, $descricao, $limite, $apartir) 
    {
        $this->db->select("id, nome, email, perfil, telefone1, telefone2");
        $this->db->where('deleted', '0');
        $this->db->order_by('id desc');

         switch($filtro)
        {
            case '0':
                $this->db->like('nome', $descricao);
            break;
                
            case '1':
                $this->db->like('email', $descricao);
            break;

            case '2':
                $this->db->like('telefone1', $descricao);
            break;

            default:
                $this->db->like('nome', $descricao);
            break;
            
        }

        //teste se é para pesquisar todos, ou com limite
        if($limite) {
            return $this->db->get($this->tabela, $limite, $apartir)->result();
        } else {
            return $this->db->get($this->tabela)->result();
        }    
    }


}