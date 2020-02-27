<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Cidads Controller
 *
 * @property Cidad $Cidad
 * @property PaginatorComponent $Paginator
 */
class CidadsController extends AppController {

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
		$this->Cidad->recursive = 0;
		$this->set('cidads', $this->Paginator->paginate());
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
		if (!$this->Cidad->exists($id)) {
			throw new NotFoundException(__('Invalid cidad'));
		}
		$options = array('recursive' => -1,'conditions' => array('Cidad.' . $this->Cidad->primaryKey => $id));
		$cidad = $this->Cidad->find('first',array('recursive'=>-1, 'conditions'=> array('Cidad.id'=> $id)));
			$this->loadModel('Estado');


			$estados = $this->Estado->find('list', array('recursive'=> -1,'fields'=> array('Estado.id','Estado.estado') ,'conditions'=> array('Estado.filial_id'=> $cidad['Cidad']['filial_id'])));
			$this->set(compact('estados','cidads'));
		$this->set('cidad', $this->Cidad->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$this->loadModel('Estado');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		$estados = $this->Estado->find('list', array('recursive'=> -1,'fields'=> array('Estado.id','Estado.estado') ,'conditions'=> array('Estado.filial_id'=> $unicaFilial['Filial']['id'])));

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
	                'Cidad.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'estado' => array(
	                'Cidad.estado_id' => array(
	                    'operator' => '=',
	                    'select'=> $estados
	                )
	            ),
	            'empresa' => array(
	                'Cidad.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            
	        )
	    );

	    
		$conditiosAux= $this->Filter->getConditions();
			$this->loadModel('Cidad');
			$cidads = $this->Cidad->find('list', array('recursive'=> -1,'fields'=> array('Cidad.id','Cidad.cidade') ,'conditions'=> array('Cidad.filial_id'=> $unicaFilial['Filial']['id'])));
			
			$this->set(compact('estados','cidads'));
	if(empty($conditiosAux)){

		$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

		$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
	}
	$this->Paginator->settings = array(
				'Cidad' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Cidad.cidade asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Cidad->recursive = 0;
		$this->set('cidads', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Cidad']['valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Cidad']['valor']);
			$this->request->data['Cidad']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			$this->request->data['Cidad']['cidade_string'] =$this->checkbfunc->removeDetritos($this->request->data['Cidad']['cidade'] );
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Cidad->create();
			if ($this->Cidad->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de cidad foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a forma de cidad. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Cidad->exists($id)) {
			throw new NotFoundException(__('Invalid cidad'));
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
			$this->request->data['Cidad']['cidade_string'] =$this->checkbfunc->removeDetritos($this->request->data['Cidad']['cidade'] );
			$this->request->data['Cidad']['valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Cidad']['valor']);
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->request->data['Cidad']['cidade_string'] =$this->checkbfunc->removeDetritos($this->request->data['Cidad']['cidade'] );
			if ($this->Cidad->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de Cidad foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a forma de cidad. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$cidad = $this->Cidad->find('first',array('recursive'=>-1, 'conditions'=> array('Cidad.id'=> $id)));


			$cidad['Cidad']['valor']= number_format($cidad['Cidad']['valor'],2,',','.');

			$this->request->data =$cidad;
			$this->loadModel('Estado');


			$estados = $this->Estado->find('list', array('recursive'=> -1,'fields'=> array('Estado.id','Estado.estado') ,'conditions'=> array('Estado.filial_id'=> $cidad['Cidad']['filial_id'])));
			$this->set(compact('estados','cidads'));

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
		$this->Cidad->id = $id;
		if (!$this->Cidad->exists()) {
			throw new NotFoundException(__('Invalid cidad'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cidad->delete()) {
			$this->Session->setFlash(__('A forma de cidad foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a forma de cidad. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->Cidad->id = $id;
		if (!$this->Cidad->exists()) {
			throw new NotFoundException(__('Invalid cidade'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cidad->saveField('ativo', 0)) {
			$this->Session->setFlash(__('A cidade foi desativado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar a cidade. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}

}
