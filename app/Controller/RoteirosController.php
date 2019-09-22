<?php
App::uses('AppController', 'Controller');
/**
 * Roteiros Controller
 *
 * @property Roteiro $Roteiro
 * @property PaginatorComponent $Paginator
 */
class RoteirosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Roteiro->recursive = 0;
		$this->set('roteiros', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Roteiro->exists($id)) {
			throw new NotFoundException(__('Invalid Roteiro'));
		}
		$options = array('conditions' => array('Roteiro.' . $this->Roteiro->primaryKey => $id));
		$this->set('roteiro', $this->Categoria->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Roteiro->recursive = 0;
		$this->set('roteiros', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->Roteiro->create();
			if ($this->Roteiro->save($this->request->data)) {
				$this->Session->setFlash(__('O Roteiro foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Erro ao salvar o Roteiro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		}

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Roteiro->exists($id)) {
			throw new NotFoundException(__('Invalid Roteiro'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Roteiro->save($this->request->data)) {
				$this->Session->setFlash(__('O Roteiro foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o Roteiro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Roteiro.' . $this->Roteiro->primaryKey => $id));
			$this->request->data = $this->Roteiro->find('first', $options);
		}

	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Roteiro->id = $id;
		if (!$this->Roteiro->exists()) {
			throw new NotFoundException(__('Invalid Roteiro'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Roteiro->delete()) {
			$this->Session->setFlash(__('O Roteiro foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao salvar o Roteiro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function inserirRota(&$id) {
		$this->loadModel('Pedido');
		$ultimopedido = $this->Pedido->find('first', array('conditions' => array('Pedido.id' => $id), 'recursive' => -1));
		if (empty($ultimopedido)) {
			return false;
		}else{



			$this->loadModel('Empresa');
			$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1));

			$this->loadModel('Cliente');
			$cliente = $this->Cliente->find('first', array('conditions' => array('Cliente.id' => $ultimopedido['Pedido']['cliente_id']), 'recursive' => -1));

			if($ultimopedido['Pedido']['entregador_id'] != null && $ultimopedido['Pedido']['entregador_id'] != '' && $ultimopedido['Pedido']['entregador_id'] != 0){
				$ultimaRota = $this->Roteiro->find('first', array('order' => array('Roteiro.id' => 'desc'), 'recursive' => -1, 'conditions' => array('Roteiro.entregador_id' => $ultimopedido['Pedido']['entregador_id'])));
				if(!empty($ultimaRota)){
					$ordem = $ultimaRota['Roteiro']['id'] + 1;
				}else{
					$ordem = 1;
				}
				$roteiro = array(
									'pedido_id'=> $ultimopedido['Pedido']['id'],
									'entregador_id'	=> $ultimopedido['Pedido']['entregador_id'],
									'lat_origem' => $empresa['Empresa']['lat'],
									'lng_origem' => $empresa['Empresa']['lng'],
									'lat_dest' => $cliente['Cliente']['lat'],
									'lng_dest' => $cliente['Cliente']['lng'],
									'data' => date('Y-m-d'),
									'hora' => date("H:i:s"),
									'distancia' => $cliente['Cliente']['distancia'],
									'cliente_id' => $cliente['Cliente']['id'],
									'duracao' => $cliente['Cliente']['duracao'],
									'ordem' => $ordem,
									'status' => 'Entregar'
							);
				$roteiroExistente = $this->Roteiro->find('first', array('order' => array('Roteiro.id' => 'desc'), 'recursive' => -1, 'conditions' => array('Roteiro.pedido_id' => $id)));
				$this->loadModel('Atendimento');
				$this->Atendimento->create();
				$updateAtendimento = array('id' => $ultimopedido['Pedido']['atendimento_id'], 'ordem' => $ordem);
				$this->Atendimento->save($updateAtendimento);
				if(empty($roteiroExistente)){
					if($this->Roteiro->save($roteiro)){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}


			}
		}


	}

}
