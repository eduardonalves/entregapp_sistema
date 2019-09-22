<?php
App::uses('AppController', 'Controller');
class RestFilialsController extends AppController {
    public $uses = array('Filial');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

	public function indexmobile() {
		$this->layout="liso";
		$empresa = $_GET['e'];
		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){
			header("Access-Control-Allow-Origin: *");
			$resultados =  $this->Filial->find('all', array('recursive' => -1, 'conditions'=> array('Filial.empresa_id'=> $empresa)));
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		//}
    }


}