<?php
App::uses('AppController', 'Controller');
/**
 * Mensagens Controller
 *
 * @property Mensagen $Mensagen
 * @property PaginatorComponent $Paginator
 */
class MensagensController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');



	public function add() {

		if ($this->request->is('post')) {
			$this->Mensagen->create();
			$this->request->data['Mensagen']['user_id']= $this->Session->read('Auth.User.id');
			$this->request->data['Mensagen']['sender']=0;
			$this->request->data['Mensagen']['ativo']=1;
			$this->request->data['Mensagen']['read']=1;
			if ($this->Mensagen->save($this->request->data)) {

				$mensagens = $this->Mensagen->find('first', array('order' => array('Mensagen.id' => 'desc'), 'recursive' => 0));

			} else {
				$mensagens="Erro";
			}
			$this->set(array(
					'mensagens' => $mensagens,
					'_serialize' => array('mensagens')
				));

		}

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 /**
 * index method
 *
 * @return void
 */
	public function ler($id = null) {

		$mensagens=$this->Mensagen->find('all', array('order' => array('Mensagen.id' => 'asc'),'recursive'=>0, 'conditions' => array('Mensagen.pedido_id' => $id)));
		$this->set(compact('mensagens'));
	}

	public function lida($id = null) {

		$this->Mensagen->updateAll(array('Mensagen.read' => 1),array('Mensagen.pedido_id ' => $id));
		$resposta='ok';
		$this->set(array(
					'resposta' => $resposta,
					'_serialize' => array('resposta')
				));


	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */

	public function view() {



		$ultMsg = $_GET['ult'];
		$pedidoId = $_GET['idpedido'];

		if($ultMsg ==''){
			$ultMsg=0;
		}

		$mensagens=$this->Mensagen->find('all', array('order' => array('Mensagen.id' => 'asc'),'recursive'=>0, 'conditions' => array('AND' => array(array('Mensagen.pedido_id' => $pedidoId), array('Mensagen.id >' => $ultMsg)))));
		//$mensagens = $this->Mensagen->find('all', array('conditions' => array('AND' => array(array('Mensagen.cliente_id' => $cliente_id),array('Mensagen.pedido_id' => $pedidoId) ,array('Mensagen.ativo' => 1),array('Mensagen.id >' => $ultMsg)  ) ),'order' => array('Mensagen.id' => 'asc'), 'recursive' => 0));
		$this->set(array(
					'mensagens' => $mensagens,
					'_serialize' => array('mensagens')
				));
	}


	public function edit($id = null) {
		if (!$this->Mensagen->exists($id)) {
			throw new NotFoundException(__('Invalid mensagen'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Mensagen->save($this->request->data)) {
				$this->Session->setFlash(__('The mensagen has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mensagen could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Mensagen.' . $this->Mensagen->primaryKey => $id));
			$this->request->data = $this->Mensagen->find('first', $options);
		}
		$funcaos = $this->Mensagen->Funcao->find('list');
		$this->set(compact('funcaos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Mensagen->id = $id;
		if (!$this->Mensagen->exists()) {
			throw new NotFoundException(__('Invalid mensagen'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mensagen->delete()) {
			$this->Session->setFlash(__('The mensagen has been deleted.'));
		} else {
			$this->Session->setFlash(__('The mensagen could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	}
