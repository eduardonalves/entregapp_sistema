<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Bairros Controller
 *
 * @property Bairro $Bairro
 * @property PaginatorComponent $Paginator
 */
class BairrosController extends AppController {

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
		$this->Bairro->recursive = 0;
		$this->set('bairros', $this->Paginator->paginate());
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
		if (!$this->Bairro->exists($id)) {
			throw new NotFoundException(__('Invalid bairro'));
		}
		$options = array('recursive' => -1,'conditions' => array('Bairro.' . $this->Bairro->primaryKey => $id));
		$this->set('bairro', $this->Bairro->find('first', $options));
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
		$this->loadModel('Estado');
		$this->loadModel('Cidad');
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		$cidads = $this->Cidad->find('list', array('recursive'=> -1,'fields'=> array('Cidad.id','Cidad.cidade') ,'conditions'=> array('Cidad.filial_id'=> $unicaFilial['Filial']['id'])));
		$cidadeSelect =array(''=>'Todas');
		$cidadeSelect += $cidads;

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
	                'Bairro.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'cidades' => array(
	                'Bairro.cidad_id' => array(
	                    'operator' => '=',
	                    'select'=> $cidadeSelect
	                )
	            ),
	            'empresa' => array(
	                'Bairro.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'nome' => array(
	                'Bairro.bairro' => array(
	                    'operator' => '=',

	                )
	            ),
	        )
	    );

	    $conditiosAux= $this->Filter->getConditions();

	if(empty($conditiosAux)){

		$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

		$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
	}
	$this->Paginator->settings = array(
				'Bairro' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Bairro.bairro asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Bairro->recursive = 0;
		$this->set('bairros', $this->Paginator->paginate());

		$this->set(compact('estados','cidads'));
		if ($this->request->is('post')) {
			$this->request->data['Bairro']['valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Bairro']['valor']);
			$this->request->data['Bairro']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			$this->request->data['Bairro']['bairro_string'] =$this->checkbfunc->removeDetritos($this->request->data['Bairro']['bairro'] );
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Bairro->create();
			if ($this->Bairro->save($this->request->data)) {
				$this->Session->setFlash(__('O bairro foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o bairro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Bairro->exists($id)) {
			throw new NotFoundException(__('Invalid bairro'));
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
			$this->request->data['Bairro']['valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Bairro']['valor']);
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->request->data['Bairro']['bairro_string'] =$this->checkbfunc->removeDetritos($this->request->data['Bairro']['bairro'] );
			if ($this->Bairro->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de bairro foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o bairro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {

			$this->loadModel('Estado');
			$this->loadModel('Cidad');
			$bairro = $this->Bairro->find('first',array('recursive'=>-1, 'conditions'=> array('Bairro.id'=> $id)));
			$bairro['Bairro']['valor'] =number_format($bairro['Bairro']['valor'] ,2,',','.');
			$cidads = $this->Cidad->find('list', array('recursive'=> -1,'fields'=> array('Cidad.id','Cidad.cidade') ,'conditions'=> array('Cidad.filial_id'=> $bairro['Bairro']['filial_id'] )));
			$estados = $this->Estado->find('list', array('recursive'=> -1,'fields'=> array('Estado.id','Estado.estado') ,'conditions'=> array('Estado.filial_id'=> $bairro['Bairro']['filial_id'] )));
			$this->set(compact('estados','cidads'));
			//$options = array('recursive' => -1, 'conditions' => array('Bairro.' . $this->Bairro->primaryKey => $id));
			$this->request->data = $bairro;
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
		$this->Bairro->id = $id;
		if (!$this->Bairro->exists()) {
			throw new NotFoundException(__('Invalid bairro'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Bairro->delete()) {
			$this->Session->setFlash(__('A forma de bairro foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a forma de bairro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->Bairro->id = $id;
		if (!$this->Bairro->exists()) {
			throw new NotFoundException(__('Invalid bairro'));
		}
		$row = $this->Bairro->find('first', array(
			'recursive'=> -1,
			'conditions'=> array(
				'id' => $id
			)
		));
		$ativo = ($row['Bairro']['ativo'] == 1 ? 0: 1);

		$this->request->onlyAllow('post', 'delete');
		if ($this->Bairro->saveField('ativo', $ativo)) {
			$this->Session->setFlash(__('O bairro foi habilitado/desabilidado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar o bairro. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}
}
