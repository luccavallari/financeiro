<?php

defined('BASEPATH') OR exit('No direct script access allowed');

 /*
  | -------------------------------------------------------------------
  | Class->model "util"
  | -------------------------------------------------------------------
  | Classe que formata data e moeda
  | url: ./application/models/Util.php
  |
 */
class Util extends CI_Model{


    /*
     |Função dataBanco
     |----------------------------------------------------------
     |Converte data para o formato para o banco
     |@param : $val => data da string 
     |@return : retorna o valor da data convertida para o banco
     |
    */
    public static function dataBanco($data) {
        $novaData = implode('-', array_reverse(explode('/', $data)));
        return $novaData;
    }

    /*
     |Função dataBanco
     |----------------------------------------------------------
     |Converte data para o formato para o usuario
     |@param : $val => data da string 
     |@return : retorna o valor da data convertida para o usuario
     |
    */
    public static function dataTela($data) {
        $novaData = implode('/', array_reverse(explode('-', $data)));
        return $novaData;
    }

    /*
    | -------------------------------------------------------------------
    | Metodo "trocar_pasta"
    | -------------------------------------------------------------------
    | Responsavel por enviar a imagem da pasta tmp para o seu destino
   */
    public static function trocar_pasta($origem,$destino)
    {
        //copia a imagem da pasta TMP para a pasta banner
        if(copy($origem, $destino))
          unlink($origem);
        else
          echo 'Erro ao copiar a imagem';
    }


        /*
     | -------------------------------------------------------------------
     | Metodo "removeAcentos"
     | -------------------------------------------------------------------
     | Função para remover acentos de uma string
     |
     | @autor Thiago Belem <contato@thiagobelem.net>
    */
    public static function removeAcentos($string, $slug = false) {
        $string = utf8_decode(strtolower($string));

        // Código ASCII das vogais
        $ascii['a'] = range(224, 230);
        $ascii['e'] = range(232, 235);
        $ascii['i'] = range(236, 239);
        $ascii['o'] = array_merge(range(242, 246), array(240, 248));
        $ascii['u'] = range(249, 252);

        // Código ASCII dos outros caracteres
        $ascii['b'] = array(223);
        $ascii['c'] = array(231);
        $ascii['d'] = array(208);
        $ascii['n'] = array(241);
        $ascii['y'] = array(253, 255);

        foreach ($ascii as $key=>$item) {
            $acentos = '';
            foreach ($item AS $codigo) $acentos .= chr($codigo);
                $troca[$key] = '/['.$acentos.']/i';
        }

        $string = preg_replace(array_values($troca), array_keys($troca), $string);

        // Slug?
        if ($slug) {
        // Troca tudo que não for letra ou número por um caractere ($slug)
        $string = preg_replace('/[^a-z0-9]/i', $slug, $string);

        // Tira os caracteres ($slug) repetidos
        $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
        $string = trim($string, $slug);
        }

        return $string;
    }
}
