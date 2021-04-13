<?php
App::uses('AppController', 'Controller');
class RestMensagensController extends AppController {
    public $uses = array('Mensagen');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

	public function checkToken(&$clienteId,&$token){
		$this->loadModel('Cliente');
		$cliente = $this->Cliente->find('first', array('conditions' => array('AND' => array(array('Cliente.id' => $clienteId), array('Cliente.token' => $token), array('Cliente.ativo' => 1)))));

		if(!empty($cliente)){
			$resposta = "OK";
			return $resposta;
		}else{
			$resposta = "NOK";
			return $resposta;
			$clienteUp= array('id'=> $clienteId, 'ativo' => 0);
			$this->Cliente->create();
			$this->Cliente->save($clienteUp);
		}

	}

	public function addmobile() {

		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

		if ($this->request->is('post')) {


			$clt =  $_GET['b'];
			$token = $_GET['c'];
			$resp =$this->checkToken($clt, $token);
			if($resp =='OK'){
				$this->Mensagen->create();
				if ($this->Mensagen->save($this->request->data)) {

					$ultimomensagen = $this->Mensagen->find('first', array('order' => array('Mensagen.id' => 'desc'), 'recursive' => 0));

				} else {
					$ultimomensagen="Erro";
				}
				$this->set(array(
						'ultimomensagen' => $ultimomensagen,
						'_serialize' => array('ultimomensagen')
					));
			}
		}

	}
	public function indexmobile() {
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$cliente_id = $_GET['clid'];

		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$pedido_id= $_GET['d'];
		$resp =$this->checkToken($clt, $token);
		if($resp =='OK'){

			$ultimomensagen = $this->Mensagen->find('all', array('conditions' => array('AND' => array( array('Mensagen.ativo' => 1), array('Mensagen.pedido_id' => $pedido_id) ) ),'order' => array('Mensagen.id' => 'asc'), 'recursive' => 0));
			$this->set(array(
						'ultimomensagen' => $ultimomensagen,
						'_serialize' => array('ultimomensagen')
					));
		}
	}

	public function viewmobile() {
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$cliente_id = $_GET['clid'];
		$ultMsg = $_GET['ult'];

		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$pedido_id= $_GET['d'];
		$resp =$this->checkToken($clt, $token);
		$ultimomensagen=array();
		if($ultMsg==""){
			$ultMsg=1;
		}
		if($resp =='OK'){

			$ultimomensagen = $this->Mensagen->find('all', array('conditions' => array('AND' => array( array('Mensagen.pedido_id' => $pedido_id), array('Mensagen.ativo' => 1),array('Mensagen.id > ' => $ultMsg)  ) ),'order' => array('Mensagen.id' => 'asc'), 'recursive' => 0));
			$this->set(array(
						'ultimomensagen' => $ultimomensagen,
						'_serialize' => array('ultimomensagen')
					));
		}
	}

	/*public function delete($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->Mensagen->id = $id;
		if (!$this->Mensagen->exists()) {
			throw new NotFoundException(__('Invalid Mensagen'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mensagen->delete()) {
			$this->Session->setFlash(__('The Mensagen has been deleted.'));
		} else {
			$this->Session->setFlash(__('The Mensagen could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}*/
}