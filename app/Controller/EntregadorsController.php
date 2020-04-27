<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Entregadors Controller
 *
 * @property Entregador $Entregador
 * @property PaginatorComponent $Paginator
 */
class EntregadorsController extends AppController {

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
		$this->Entregador->recursive = 0;
		$this->set('entregadors', $this->Paginator->paginate());
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
		if (!$this->Entregador->exists($id)) {
			throw new NotFoundException(__('Invalid entregador'));
		}
		$this->loadModel('Pedido');
		$entregador = $this->Entregador->find('first', array('recursive' => -1, 'conditions' => array('Entregador.id' => $id)));
		$pedidos = $this->Pedido->find('all', array('conditions' => array('AND' => array( array('Pedido.entregador_id' => $id), array('Pedido.status' => 'Em Trânsito')))));
		$this->set(compact('entregador', 'pedidos'));
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
	                'Entregador.nome' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'cpf' => array(
	                'Entregador.cpf' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'codigo' => array(
	                'Entregador.id' => array(
	                    'operator' => '=',
	                )
	            ),
	            'minhaslojas' => array(
	                'Entregador.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Entregador.empresa_id' => array(
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
				'Entregador' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Entregador.nome asc'
				)
			);
	$this->Entregador->recursive = 0;
	$this->set('entregadors', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Entregador']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Entregador->create();

			if($this->request->data['Entregador']['foto']['name']==''){
				unset($this->request->data['Entregador']['foto']);
			}

			if(isset($this->request->data['Entregador']['foto']['error']) && $this->request->data['Entregador']['foto']['error'] === 0) {
				$tipo = $this->request->data['Entregador']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Entregador']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Entregador']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
				$this->request->data['Entregador']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				$this->Entregador->create(); // We have a new entry
				$this->Entregador->save($this->request->data); // Save the request
				$this->Session->setFlash(__('O entregador foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
            } else {

	               // Save the request
	               $this->Entregador->create(); // We have a new entry
	               unset($this->request->data['Entregador']['foto']);
	               if($this->Entregador->save($this->request->data)){
	               		$this->Session->setFlash(__('O entregador foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	               		return $this->redirect($this->request->here);
	               }else{
	               		 $this->Session->setFlash(__('Erro ao salvar o entregador . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Entregador->exists($id)) {
			throw new NotFoundException(__('Invalid entregador'));
		}
		$this->loadModel('Filial');
		
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		$this->request->data['Entregador']['filial_id']=(string) $unicaFilial['Filial']['id']  ;

		$this->request->data['Entregador']['empresa_id']=$this->Session->read('Auth.User.empresa_id');

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
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
			if($this->request->data['Entregador']['foto']['name']==''){
				unset($this->request->data['Entregador']['foto']);
			}
			
			if(isset($this->request->data['Entregador']['foto']['error']) && $this->request->data['Entregador']['foto']['error'] === 0) {

	                		$tipo = $this->request->data['Entregador']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Entregador']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Entregador']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)

				$this->request->data['Entregador']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				$this->Entregador->create(); // We have a new entry
				$this->Entregador->save($this->request->data); // Save the request
				$this->Session->setFlash(__('O entregador foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {

			       // Save the request
			       $this->Entregador->create(); // We have a new entry

			       if($this->Entregador->save($this->request->data)){
			       		$this->Session->setFlash(__('O entregador foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			       		return $this->redirect( $this->referer() );
			       }else{
			       		 $this->Session->setFlash(__('Erro ao salvar o entregador . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			       }


			}
		} else {
			$options = array('recursive'=> -1,'conditions' => array('Entregador.' . $this->Entregador->primaryKey => $id));
			$this->request->data = $this->Entregador->find('first', $options);
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
		$this->Entregador->id = $id;
		if (!$this->Entregador->exists()) {
			throw new NotFoundException(__('Invalid entregador'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Entregador->delete()) {
			$this->Session->setFlash(__('O entregador foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o entregador. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->Entregador->id = $id;
		if (!$this->Entregador->exists()) {
			throw new NotFoundException(__('Invalid entregador'));
		}
		$row = $this->Entregador->find('first', array(
			'recursive'=> -1,
			'conditions'=> array(
				'id' => $id
			)
		));
		$ativo = ($row['Entregador']['ativo'] == 1 ? 0: 1);

		$this->request->onlyAllow('post', 'delete');
		if ($this->Entregador->saveField('ativo', $ativo)) {
			$this->Session->setFlash(__('O entregador foi habilitado/desabilitado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao habilitar/desabilidar o entregador. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}	
}
