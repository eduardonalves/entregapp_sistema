<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Roteiros');
App::import('Controller', 'Produtos');
class RestCuponsController extends AppController {
    public $uses = array('Pedido');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

  /*  public function beforeFilter()
    {
      $this->loadModel('Filial');
      $this->loadModel('Cupon');

      $vencidos = $this->Cupon->find('all',array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Cupon.empresa_id'=> $_GET['lj'], array('Cupon.status'=> 'Disponível'), array('Cupon.validade <'=> date('Y-m-d')))))));
      $k=0;
      foreach ($vencidos as $key => $value)
      {
        $updateCupon = array(
          'id' => $value['Cupon']['id'],
          'status' => 'Cancelado'
        );
        $this->Cupon->create();
        $this->Cupon->save($updateCupon);
        $k++;
      }
    }*/


    public function indexmobile ()
    {
      date_default_timezone_set("Brazil/East");
  		header("Access-Control-Allow-Origin: *");
      $this->loadModel('Filial');
      $this->loadModel('Empresa');
  		$this->loadModel('Cupon');
    	$cliente= $_GET['clt'];
  		$token =  $_GET['token'];
      $empresa= $_GET['lj'];
      $filial=$_GET['fp'];
      $resultados="";

      $vencidos = $this->Cupon->find('all',array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Cupon.empresa_id'=> $_GET['lj'], array('Cupon.status'=> 'Disponível'), array('Cupon.validade <'=> date('Y-m-d')))))));
      $k=0;
      foreach ($vencidos as $key => $value)
      {
        $updateCupon = array(
          'id' => $value['Cupon']['id'],
          'status' => 'Cancelado'
        );
        $this->Cupon->create();
        $this->Cupon->save($updateCupon);
        $k++;
      }
  	/*	$Empresa = new EmpresasController;
  		$respAux = $Empresa->empresaAtiva();
      $resp='';


      if($respAux == 1)
      {
        $resp='OK';
  		}else
      {
  			$resp='NOK';
  		}
  		if($resp != 'OK')
      {
        $resp = $this->checkToken($cliente, $token);
      }
      if($resp =='OK')
      {*/
        $resultados= $this->Cupon->find(
          'all',array(
            'recursive' => -1,
            'order' => 'Cupon.id DESC',
            'conditions' => array(
              'Cupon.status'=> 'Disponivel',
              'Cupon.cliente_id'=> $cliente,
              'Cupon.empresa_id' => $empresa,
              'Cupon.filial_id' => $filial,
            )
          )
        );
        if(!empty($resultados))
        {
          foreach ($resultados as $key => $value)
          {
            if($value['Cupon']['validade'] != null && $value['Cupon']['validade'] != ''){
                $resultados[$key]['Cupon']['validade'] = $this->checkbfunc->formatDateToView($value['Cupon']['validade']);
            }

          }
        }
      //}
      $this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
    }

}
