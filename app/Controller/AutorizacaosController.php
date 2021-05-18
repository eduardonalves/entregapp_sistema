<?php
App::uses('AppController', 'Controller');
/**
 * Autorizacaos Controller
 *
 * @property Autorizacao $Autorizacao
 * @property PaginatorComponent $Paginator
 */
class AutorizacaosController extends AppController {

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
		$this->Autorizacao->recursive = 0;
		$this->set('autorizacaos', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Autorizacao->exists($id)) {
			throw new NotFoundException(__('Invalid Autorizacao'));
		}
		$options = array('conditions' => array('Autorizacao.' . $this->Autorizacao->primaryKey => $id));
		$this->set('autorizacao', $this->Autorizacao->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Autorizacao->create();
			if ($this->Autorizacao->save($this->request->data)) {
				$this->Session->setFlash(__('A autorizacão foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a autorizacão. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Autorizacao->exists($id)) {
			throw new NotFoundException(__('Invalid autorizacao'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Autorizacao->save($this->request->data)) {
				$this->Session->setFlash(__('A autorizacão foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a autorizacão. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Autorizacao.' . $this->Autorizacao->primaryKey => $id));
			$this->request->data = $this->Autorizacao->find('first', $options);
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
		$this->Autorizacao->id = $id;
		if (!$this->Autorizacao->exists()) {
			throw new NotFoundException(__('Invalid Autorizacao'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Autorizacao->delete()) {
			$this->Session->setFlash(__('A autorizacão foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a autorizacão. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * setAutorizacao method
	 *
	 *
	 * @param string $acesso
	 * @return $autorizacao
	 */
	public function setAutorizacao(&$acesso,&$userfuncao ){

		$this->loadModel('Autorizacao');
		$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao)));
		
		if($autorizacao['Autorizacao'][$acesso]=='n'){
			return false;
		}else{
			if($userfuncao != false){
				return $autorizacao;
				$this->set(compact('autorizacao'));
			}else{
				return false;
			}
			
		}
	}

	public function setAutorizacaolv(&$acesso,&$userfuncao ){

		$this->loadModel('Autorizacao');
		$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao)));


		if($autorizacao['Autorizacao'][$acesso]=='m' || $autorizacao['Autorizacao'][$acesso]=='g' || $autorizacao['Autorizacao'][$acesso]=='a'){

			return true;
		}else{
			return false;
		}
	}
	public function setAutoIncuir(&$acesso,&$userfuncao ){

		$this->loadModel('Autorizacao');
		$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao)));

		if($autorizacao['Autorizacao'][$acesso]=='m' || $autorizacao['Autorizacao'][$acesso]=='g' || $autorizacao['Autorizacao'][$acesso]=='a'){

			return true;
		}else{
			return false;
		}
	}

	public function setAutodelete(&$acesso,&$userfuncao ){

		$this->loadModel('Autorizacao');
		$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao)));

		if($autorizacao['Autorizacao'][$acesso]=='m' || $autorizacao['Autorizacao'][$acesso]=='g' || $autorizacao['Autorizacao'][$acesso]=='a'){

			return true;
		}else{
			return false;
		}
	}

	public function setConfirmarPedido(&$acesso,&$userfuncao ){

		$this->loadModel('Autorizacao');
		$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao)));

		if($autorizacao['Autorizacao'][$acesso]=='m'){

			return true;
		}else{
			return false;
		}
	}

}
