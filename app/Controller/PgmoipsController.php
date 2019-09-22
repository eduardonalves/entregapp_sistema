<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property Pgmoip $Pgmoip
 * @property PaginatorComponent $Paginator
 */
class PgmoipsController extends AppController {

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
		$this->Pgmoip->recursive = 0;
		$this->set('pgmoips', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Pgmoip->exists($id)) {
			throw new NotFoundException(__('Invalid pgmoip'));
		}
		$options = array('conditions' => array('Pgmoip.' . $this->Pgmoip->primaryKey => $id));
		$this->set('pgmoip', $this->Pgmoip->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Pgmoip->recursive = 0;
		$this->set('pgmoips', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->Pgmoip->create();
			if ($this->Pgmoip->save($this->request->data)) {
				$this->Session->setFlash(__('The pgmoip has been saved.'), 'default', array('class' => 'success-flash'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('The pgmoip could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
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
		if (!$this->Pgmoip->exists($id)) {
			throw new NotFoundException(__('Invalid pgmoip'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Pgmoip->save($this->request->data)) {
				$this->Session->setFlash(__('The pgmoip has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pgmoip could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Pgmoip.' . $this->Pgmoip->primaryKey => $id));
			$this->request->data = $this->Pgmoip->find('first', $options);
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
		$this->Pgmoip->id = $id;
		if (!$this->Pgmoip->exists()) {
			throw new NotFoundException(__('Invalid pgmoip'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Pgmoip->delete()) {
			$this->Session->setFlash(__('The pgmoip has been deleted.'));
		} else {
			$this->Session->setFlash(__('The pgmoip could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
