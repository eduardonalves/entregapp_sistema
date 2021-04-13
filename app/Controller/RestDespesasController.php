<?php
App::uses('AppController', 'Controller');
class RestDespesasController extends AppController {
    public $uses = array('Despesa');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function processadespesa() {
        header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        //$categorias = $this->Categoria->find('all',array('recursive'=> -1,'order'=> 'Categoria.destaque Desc ,Categoria.nome ASC', 'conditions'=> array('filial_id'=> $_GET['fp'], 'ativo' => 1 )));
        $despesas = $this->Despesa->find('all', array('recursive'=>-1,'conditions'=> array(
          'recorrente'=> 1,
          'data_recorrente_processar'=> date('Y-m-d'),
          'tiposrecorrencia_id <>'=> 0
        )));
        $countProcessamento =0;
        foreach ($despesas as $key => $value) {
          $novaDataVencimento='';
          if($value['Despesa']['data_prox_vencimento'] !='' && $value['Despesa']['data_prox_vencimento'] >= date('Y-m-d')){
            $novaDataVencimento= $value['Despesa']['data_prox_vencimento'];
          }else{
            $novaDataVencimento = $this->checkbfunc->somaDataAUmPeriodo($value['Despesa']['data_vencimento'], $value['Despesa']['tiposrecorrencia_id']);
          }
          
          $value['Despesa']['data_vencimento'] = $novaDataVencimento;
          $value['Despesa']['data_prox_vencimento']= $this->checkbfunc->somaDataAUmPeriodo($novaDataVencimento, $value['Despesa']['tiposrecorrencia_id']);  
          $value['Despesa']['data_recorrente_processar']= $this->checkbfunc->somaDataAUmPeriodo($value['Despesa']['data_recorrente_processar'], $value['Despesa']['tiposrecorrencia_id']);
          
          $this->Despesa->save(array(
            'id'=> $value['Despesa']['id'],
            'recorrente' => 0
          ));
          unset($value['Despesa']['id']);
          $this->Despesa->create();
          $this->Despesa->save($value);
          
          $countProcessamento ++;
        }
        
        
        $this->set(array(
            'resultados' => $countProcessamento,
            '_serialize' => array('resultados')
        ));
    }

}
