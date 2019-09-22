<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Filials Controller
 *
 * @property Filial $Filial
 * @property PaginatorComponent $Paginator
 */
class FilialsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout ='liso';
		if (!$this->Filial->exists($id)) {
			throw new NotFoundException(__('Invalid Filial'));
		}
		$options = array('recursive' => -1,'conditions' => array('Filial.' . $this->Filial->primaryKey => $id));
		$this->set('Filial', $this->Filial->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$this->loadModel('User');
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
	                'Filial.id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Filial.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'nome' => array(
	                'Filial.nome' => array(
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
				'Filial' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Filial.nome asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Filial->recursive = -1;
		$this->set('filials', $this->Paginator->paginate());

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
		if (!$this->Filial->exists($id)) {
			throw new NotFoundException(__('Invalid Filial'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$empresaId = $this->Session->read('Auth.User.empresa_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if ($this->request->is(array('post', 'put'))) {
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			if ($this->Filial->saveAll($this->request->data)) {
				$this->Session->setFlash(__('A  Filial foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a  Filial. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('recursive' => -1, 'conditions' => array('Filial.' . $this->Filial->primaryKey => $id));
			$this->loadModel('Diasdepromocao');
			$diasdepromocao = $this->Diasdepromocao->find('all',array('recursive'=>-1, 'conditions' => array('Diasdepromocao.filial_id'=> $id)));
			$i=0;

			$this->request->data = $this->Filial->find('first', $options);
			foreach ($diasdepromocao as $promocao) {
				$this->request->data['Diasdepromocao'][$i]['id']=$promocao['Diasdepromocao']['id'];
				$this->request->data['Diasdepromocao'][$i]['promocao_id']=$promocao['Diasdepromocao']['promocao_id'];
				$this->request->data['Diasdepromocao'][$i]['filial_id']=$promocao['Diasdepromocao']['filial_id'];
				$this->request->data['Diasdepromocao'][$i]['segunda']=$promocao['Diasdepromocao']['segunda'];
				$this->request->data['Diasdepromocao'][$i]['terca']=$promocao['Diasdepromocao']['terca'];
				$this->request->data['Diasdepromocao'][$i]['quarta']=$promocao['Diasdepromocao']['quarta'];
				$this->request->data['Diasdepromocao'][$i]['quinta']=$promocao['Diasdepromocao']['quinta'];
				$this->request->data['Diasdepromocao'][$i]['sexta']=$promocao['Diasdepromocao']['sexta'];
				$this->request->data['Diasdepromocao'][$i]['sabado']=$promocao['Diasdepromocao']['sabado'];
				$this->request->data['Diasdepromocao'][$i]['domingo']=$promocao['Diasdepromocao']['domingo'];
				$i++;
			}

		}
	}

}
