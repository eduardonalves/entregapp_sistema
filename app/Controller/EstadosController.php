<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Estados Controller
 *
 * @property Estado $Estado
 * @property PaginatorComponent $Paginator
 */
class EstadosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','checkbfunc');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Estado->recursive = 0;
		$this->set('Estados', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout ='liso';
		if (!$this->Estado->exists($id)) {
			throw new NotFoundException(__('Invalid estado'));
		}
		$estado= $this->Estado->find('first',array('recursive'=>-1, 'conditions'=> array('Estado.id'=> $id)));

		$this->set(compact('estado'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(isset($this->request->data['filter']))
		{

			foreach($this->request->data['filter'] as $key=>$value)
			{

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');



			}
		}
		$this->Filter->addFilters(
			array(

	            'minhaslojas' => array(
	                'Estado.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Estado.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'nome' => array(
	                'Estado.estado' => array(
	                    'operator' => '=',

	                )
	            ),
	        )
	    );

	    $conditiosAux= $this->Filter->getConditions();
	$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
	if(empty($conditiosAux)){

		$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

		$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
	}
	$this->Paginator->settings = array(
				'Estado' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Estado.estado asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Estado->recursive = 0;
		$this->set('estados', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Estado']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			$this->request->data['Estado']['estado_string'] =$this->checkbfunc->removeDetritos($this->request->data['Estado']['estado'] );
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Estado->create();
			if ($this->Estado->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de estado foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a forma de estado. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->layout ='liso';
		if (!$this->Estado->exists($id)) {
			throw new NotFoundException(__('Invalid estado'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if ($this->request->is(array('post', 'put'))) {
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->request->data['Estado']['estado_string'] =$this->checkbfunc->removeDetritos($this->request->data['Estado']['estado'] );
			if ($this->Estado->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de Estado foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a forma de estado. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$estado = $this->Estado->find('first',array('recursive'=> -1, array('conditions'=> array('Estado.id'=> $id))));
			$estado['Estado']['valor']= number_format($estado['Estado']['valor'],2,',','.');

			$this->request->data=$estado;
			//$options = array('recursive' => -1, 'conditions' => array('Estado.' . $this->Estado->primaryKey => $id));
			//$this->request->data = $this->Estado->find('first', $options);
			//$this->request->data['Estado']['valor']=number_format($this->request->data['Estado']['valor'],2,',','.' );
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
		$this->Estado->id = $id;
		if (!$this->Estado->exists()) {
			throw new NotFoundException(__('Invalid estado'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estado->delete()) {
			$this->Session->setFlash(__('A forma de estado foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a forma de estado. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function disable($id = null) {
		$this->Estado->id = $id;
		if (!$this->Estado->exists()) {
			throw new NotFoundException(__('Invalid Estado'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Estado->saveField('ativo', 0)) {
			$this->Session->setFlash(__('O Estado foi desativado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar o Estado. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}
}
