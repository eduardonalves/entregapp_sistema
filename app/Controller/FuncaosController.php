<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Funcaos Controller
 *
 * @property Funcao $Funcao
 * @property PaginatorComponent $Paginator
 */
class FuncaosController extends AppController {

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
		$this->Funcao->recursive = 0;
		$this->set('funcaos', $this->Paginator->paginate());
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
		if (!$this->Funcao->exists($id)) {
			throw new NotFoundException(__('Invalid funcao'));
		}
		$options = array('conditions' => array('Funcao.' . $this->Funcao->primaryKey => $id));
		$this->set('funcao', $this->Funcao->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'funcoes';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Filter->addFilters(
		        array(
		        	'minhaslojas' => array(
		                'Funcao.filial_id' => array(
		                    'operator' => '=',
		                    'select'=> $lojas
		                )
		            ),
		            'empresa' => array(
		                'Funcao.empresa_id' => array(
		                    'operator' => '=',

		                )
		            ),
		            'funcao' => array(
		                'Funcao.funcao' => array(
		                    'operator' => 'LIKE',
		                    'value' => array(
		                        'before' => '%', // optional
		                        'after'  => '%'  // optional
		                    )
		                )
		            )
		          )
		    );

			  $conditiosAux= $this->Filter->getConditions();
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
			if(empty($conditiosAux)){

				$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
			}
			$this->Paginator->settings = array(
					'Funcao' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'Funcao.funcao asc'
					)
				);
			$this->Funcao->find('all', array('conditions'=> $this->Filter->getConditions(), 'recursive' => 0));
			$funcaos = $this->Paginator->paginate('Funcao');
			$this->set('funcaos', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Funcao']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			$autTipo = 'funcoes';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Funcao->create();
			if ($this->Funcao->saveAll($this->request->data)) {
				$this->Session->setFlash(__('A função foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));

			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a função. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect(array('action' => 'add'));
			}
		}else{




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
		if (!$this->Funcao->exists($id)) {
			throw new NotFoundException(__('Invalid funcao'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'funcoes';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if ($this->request->is(array('post', 'put'))) {
			$autTipo = 'funcoes';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Funcao->create();
			if ($this->Funcao->saveAll($this->request->data)) {
				$this->Session->setFlash(__('A função foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a função. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
		} else {
			$options = array('conditions' => array('Funcao.' . $this->Funcao->primaryKey => $id));
			$this->request->data = $this->Funcao->find('first', $options);
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
		$this->Funcao->id = $id;
		if (!$this->Funcao->exists()) {
			throw new NotFoundException(__('Invalid funcao'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Funcao->delete()) {
			$this->Session->setFlash(__('A função foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a função. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'add'));
	}}
