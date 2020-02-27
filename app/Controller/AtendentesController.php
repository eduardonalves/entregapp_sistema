<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Atendentes Controller
 *
 * @property Atendente $Atendente
 * @property PaginatorComponent $Paginator
 */
class AtendentesController extends AppController {

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
		$this->Atendente->recursive = 0;
		$this->set('atendentes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout='liso';
		if (!$this->Atendente->exists($id)) {
			throw new NotFoundException(__('Invalid atendente'));
		}
		$this->loadModel('Pedido');
		$atendente = $this->Atendente->find('first', array('recursive' => -1, 'conditions' => array('Atendente.id' => $id)));
		$pedidos = $this->Pedido->find('all', array('conditions' => array('AND' => array( array('Pedido.atendente_id' => $id), array('Pedido.status' => 'Em Trânsito')))));
		$this->set(compact('atendente', 'pedidos'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'entregadores';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if(isset($this->request->data['filter']))
		{

			foreach($this->request->data['filter'] as $key=>$value)
			{

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');



			}
		}


		$this->Filter->addFilters(
			array(
	            'nome' => array(
	                'Atendente.nome' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'cpf' => array(
	                'Atendente.cpf' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'codigo' => array(
	                'Atendente.id' => array(
	                    'operator' => '=',
	                )
	            ),
	            'minhaslojas' => array(
	                'Atendente.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Atendente.empresa_id' => array(
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
				'Atendente' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Atendente.nome asc'
				)
			);
	$this->Atendente->recursive = 0;
	$this->set('atendentes', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Atendente']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Atendente->create();

			if($this->request->data['Atendente']['foto']['name']==''){
				unset($this->request->data['Atendente']['foto']);
			}

			if(isset($this->request->data['Atendente']['foto']['error']) && $this->request->data['Atendente']['foto']['error'] === 0) {
				$tipo = $this->request->data['Atendente']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Atendente']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Atendente']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
				$this->request->data['Atendente']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				//$this->Atendente->create(); // We have a new entry
				$this->Atendente->save($this->request->data); // Save the request

				$this->Session->setFlash(__('O atendente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
            } else {

	               // Save the request
	              // $this->Atendente->create(); // We have a new entry
	               unset($this->request->data['Atendente']['foto']);
	               if($this->Atendente->save($this->request->data)){

	               		$this->Session->setFlash(__('O atendente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
											//die('aqui');
											return $this->redirect( $this->referer() );
	               }else{
	               		 $this->Session->setFlash(__('Erro ao salvar o atendente . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
	               }


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
		if (!$this->Atendente->exists($id)) {
			throw new NotFoundException(__('Invalid atendente'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'entregadores';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$this->loadModel('Filial');
		
		
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		
		$this->request->data['Atendente']['empresa_id']=$this->Session->read('Auth.User.empresa_id');
		$this->request->data['Atendente']['filial_id']=$unicaFilial['Filial']['id'];
		
		
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}

		$this->layout ='liso';
		if ($this->request->is(array('post', 'put'))) {

			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			if($this->request->data['Atendente']['foto']['name']==''){
				unset($this->request->data['Atendente']['foto']);
			}
			if(isset($this->request->data['Atendente']['foto']['error']) && $this->request->data['Atendente']['foto']['error'] === 0) {

	                		$tipo = $this->request->data['Atendente']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Atendente']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Atendente']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)

				$this->request->data['Atendente']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				$this->Atendente->create(); // We have a new entry
				$this->Atendente->save($this->request->data); // Save the request
				$this->Session->setFlash(__('O atendente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {

			       // Save the request
			       $this->Atendente->create(); // We have a new entry

			       if($this->Atendente->save($this->request->data)){
			       		$this->Session->setFlash(__('O atendente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			       		return $this->redirect( $this->referer() );
			       }else{
			       		 $this->Session->setFlash(__('Erro ao salvar o atendente . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			       }


			}
		} else {
			$options = array('recursive'=> -1,'conditions' => array('Atendente.' . $this->Atendente->primaryKey => $id));
			$this->request->data = $this->Atendente->find('first', $options);
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
		$this->Atendente->id = $id;
		if (!$this->Atendente->exists()) {
			throw new NotFoundException(__('Invalid atendente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Atendente->delete()) {
			$this->Session->setFlash(__('O atendente foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o atendente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}

	public function disable($id = null) {
		$this->Atendente->id = $id;
		if (!$this->Atendente->exists()) {
			throw new NotFoundException(__('Invalid Atendente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Atendente->saveField('ativo', 0)) {
			$this->Session->setFlash(__('O Atendente foi desativado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar o atendente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}

}
