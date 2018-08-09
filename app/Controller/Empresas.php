<?php
App::uses('AppController', 'Controller');
/**
 * Empresas Controller
 *
 * @property Empresa $Empresa
 * @property PaginatorComponent $Paginator
 */
class EmpresasController extends AppController {

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
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
		$this->set('empresa', $this->Empresa->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->Empresa->create();
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('The empresa has been saved.'), 'default', array('class' => 'success-flash'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('The empresa could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
			}
		}
		$funcaos = $this->Empresa->Funcao->find('list');
		$this->set(compact('funcaos'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('The empresa has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The empresa could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
			$this->request->data = $this->Empresa->find('first', $options);
		}
		$funcaos = $this->Empresa->Funcao->find('list');
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
		$this->Empresa->id = $id;
		if (!$this->Empresa->exists()) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Empresa->delete()) {
			$this->Session->setFlash(__('The empresa has been deleted.'));
		} else {
			$this->Session->setFlash(__('The empresa could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	
	}
