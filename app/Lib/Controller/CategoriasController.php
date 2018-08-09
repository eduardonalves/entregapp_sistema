<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Categorias Controller
 *
 * @property Categoria $Categoria
 * @property PaginatorComponent $Paginator
 */

class CategoriasController extends AppController {

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
		$this->Categoria->recursive = 0;
		$this->set('categoria', $this->Paginator->paginate());
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
		if (!$this->Categoria->exists($id)) {
			throw new NotFoundException(__('Invalid Categoria'));
		}
		$options = array('recursive' => -1,'conditions' => array('Categoria.' . $this->Categoria->primaryKey => $id));
		$this->set('categoria', $this->Categoria->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
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
	                'Categoria.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Categoria.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'nome' => array(
	                'Categoria.nome' => array(
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
				'Categoria' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Categoria.nome asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Categoria->recursive = 0;
		$this->set('categorias', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Categoria']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Categoria->create();
			if ($this->Categoria->save($this->request->data)) {
				$this->Session->setFlash(__('A categoria foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a categoria. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Categoria->exists($id)) {
			throw new NotFoundException(__('Invalid categoria'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
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
			if ($this->Categoria->save($this->request->data)) {
				$this->Session->setFlash(__('A  categoria foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a  categoria. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('recursive' => -1, 'conditions' => array('Categoria.' . $this->Categoria->primaryKey => $id));
			$this->request->data = $this->Categoria->find('first', $options);
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
		$this->Categoria->id = $id;
		if (!$this->Categoria->exists()) {
			throw new NotFoundException(__('Invalid categoria'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Categoria->delete()) {
			$this->Session->setFlash(__('A categoria foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a categoria. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}}
