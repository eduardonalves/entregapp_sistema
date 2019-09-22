<?php
App::uses('AppController', 'Controller');
/**
 * Itensdepedidos Controller
 *
 * @property Itensdepedido $Itensdepedido
 * @property PaginatorComponent $Paginator
 */
class ItensdepedidosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Itensdepedido->recursive = 0;
		$this->set('itensdepedidos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Itensdepedido->exists($id)) {
			throw new NotFoundException(__('Invalid itensdepedido'));
		}
		$options = array('conditions' => array('Itensdepedido.' . $this->Itensdepedido->primaryKey => $id));
		$this->set('itensdepedido', $this->Itensdepedido->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Itensdepedido->create();
			if ($this->Itensdepedido->save($this->request->data)) {
				$this->Session->setFlash(__('The itensdepedido has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The itensdepedido could not be saved. Please, try again.'));
			}
		}
		$produtos = $this->Itensdepedido->Produto->find('list');
		$pedidos = $this->Itensdepedido->Pedido->find('list');
		$this->set(compact('produtos', 'pedidos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Itensdepedido->exists($id)) {
			throw new NotFoundException(__('Invalid itensdepedido'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Itensdepedido->save($this->request->data)) {
				$this->Session->setFlash(__('The itensdepedido has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The itensdepedido could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Itensdepedido.' . $this->Itensdepedido->primaryKey => $id));
			$this->request->data = $this->Itensdepedido->find('first', $options);
		}
		$produtos = $this->Itensdepedido->Produto->find('list');
		$pedidos = $this->Itensdepedido->Pedido->find('list');
		$this->set(compact('produtos', 'pedidos'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Itensdepedido->id = $id;
		if (!$this->Itensdepedido->exists()) {
			throw new NotFoundException(__('Invalid itensdepedido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Itensdepedido->delete()) {
			$this->Session->setFlash(__('The itensdepedido has been deleted.'));
		} else {
			$this->Session->setFlash(__('The itensdepedido could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
