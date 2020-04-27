<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Users Controller
 *
 * @property Cliente $Cliente
 * @property PaginatorComponent $Paginator
 */
class ClientesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Cliente->recursive = 0;
		$this->set('clientes', $this->Paginator->paginate());
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
		if (!$this->Cliente->exists($id)) {
			throw new NotFoundException(__('Invalid cliente'));
		}
		$options = array('conditions' => array('Cliente.' . $this->Cliente->primaryKey => $id));

		$this->set('cliente', $this->Cliente->find('first', $options));
	}
/**
 * setAutorizacao method
 *
 *
 * @param string $acesso
 * @return void
 */


/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$userid = $this->Session->read('Auth.User.id');
		$Autorizacao = new AutorizacaosController;
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		$autTipo = 'clientes';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
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
		                'Cliente.nome' => array(
		                    'operator' => 'LIKE',
		                    'value' => array(
		                        'before' => '%', // optional
		                        'after'  => '%'  // optional
		                    )
		                )
		            ),
		            'telefone' => array(
		                'Cliente.telefone' => array(
		                    'operator' => 'LIKE',
		                    'value' => array(
		                        'before' => '%', // optional
		                        'after'  => '%'  // optional
		                    )
		                )
		            ),
		            'codigo' => array(
		                'Cliente.id' => array(
		                    'operator' => '=',
		                )
		            ),
		            'minhaslojas' => array(
		                'Cliente.filial_id' => array(
		                    'operator' => '=',
		                    'select'=> $lojas
		                )
		            ),
		            'empresa' => array(
		                'Cliente.empresa_id' => array(
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
			//debug($this->Filter->getConditions());
			//die;
			$this->Paginator->settings = array(
					'Cliente' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'Cliente.nome asc'
					)
				);

			$this->Cliente->find('all', array('conditions'=> $this->Filter->getConditions(), 'recursive' => 0));
			$clientes = $this->Paginator->paginate('Cliente');
			//debug($clientes);
			//die;
			$this->set('clientes', $this->Paginator->paginate());

			$this->loadModel('Empresa');
			$empresa = $this->Empresa->find('first', array('recursive' => -1,'conditions' => array('Empresa.id' => 1)));
			$this->set(compact('empresa'));
		if ($this->request->is('post')) {
			$this->Cliente->create();

			$autTipo = 'clientes';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}

			$dataNasc =$this->request->data['Cliente']['nasc'];
		            $dataNasc = implode("-",array_reverse(explode("/",$dataNasc)));
		            $this->request->data['Cliente']['nasc']= $dataNasc;
		            $this->request->data['Cliente']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
		             if($this->Cliente->save($this->request->data)){
	               		$this->Session->setFlash(__('O cliente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	               		return $this->redirect( $this->referer() );
		               }else{
		               		 $this->Session->setFlash(__('Erro ao salvar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		               		return $this->redirect( $this->referer() );
		               }

		           /* if($this->request->data['Cliente']['foto']['name']==''){
						unset($this->request->data['Cliente']['foto']);
			}
			if(isset($this->request->data['Cliente']['foto']['error']) && $this->request->data['Cliente']['foto']['error'] === 0) {
		                $source = $this->request->data['Cliente']['foto']['tmp_name']; // Source
		                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotoscli' . DS;   // Destination

		                $nomedoArquivo = date('YmdHis').rand(1000,999999);
		                $nomedoArquivo= $nomedoArquivo.$this->request->data['Cliente']['foto']['name'];
		                move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
		                $this->request->data['Cliente']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotoscli/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
		                $this->Cliente->create(); // We have a new entry
		                $this->Cliente->save($this->request->data); // Save the request
		             	$this->Session->setFlash(__('O cliente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		             	return $this->redirect(array('action' => 'add'));
		            } else {
		              	               // Save the request
			               $this->Cliente->create(); // We have a new entry
			               	unset($this->request->data['Cliente']['foto']);
			               if($this->Cliente->save($this->request->data)){
			               		$this->Session->setFlash(__('O cliente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			               		return $this->redirect(array('action' => 'add'));
			               }else{
			               		 $this->Session->setFlash(__('Erro ao salvar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			               }


		            }*/

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
		if (!$this->Cliente->exists($id)) {
			throw new NotFoundException(__('Invalid cliente'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'clientes';
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
			/*if($this->request->data['Cliente']['foto']['name']==''){
				unset($this->request->data['Cliente']['foto']);
			}

			if(isset($this->request->data['Cliente']['foto']['error']) && $this->request->data['Cliente']['foto']['error'] === 0) {

		                $source = $this->request->data['Cliente']['foto']['tmp_name']; // Source
		                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotoscli' . DS;   // Destination*/

		                //$nomedoArquivo = date('YmdHis').rand(1000,999999);
		                //$nomedoArquivo= $nomedoArquivo.$this->request->data['Cliente']['foto']['name'];
		                //move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
		                //$this->request->data['Cliente']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotoscli/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
		                $dataNasc =$this->request->data['Cliente']['nasc'];
		                $dataNasc = implode("-",array_reverse(explode("/",$dataNasc)));
		                $this->request->data['Cliente']['nasc']= $dataNasc;
		                unset($this->request->data['Cliente']['filial_id']);
		                unset($this->request->data['Cliente']['empresa_id']);
		                //$this->Cliente->create(); // We have a new entry
		               if($this->Cliente->save($this->request->data)){
		               		$this->Session->setFlash(__('O cliente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		               		return $this->redirect(array('action' => 'add'));
		               }else{
		               		 $this->Session->setFlash(__('Erro ao salvar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		               }
		           /* } else {

			               // Save the request
			              	$dataNasc =$this->request->data['Cliente']['nasc'];
			                $dataNasc = implode("-",array_reverse(explode("/",$dataNasc)));
			                $this->request->data['Cliente']['nasc']= $dataNasc;
			               	//unset($this->request->data['Cliente']['foto']);
			               if($this->Cliente->save($this->request->data)){
			               		$this->Session->setFlash(__('O cliente foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			               		return $this->redirect(array('action' => 'add'));
			               }else{
			               		 $this->Session->setFlash(__('Erro ao salvar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			               }


		            }*/
		} else {
			$options = array('recursive'=>-1,'conditions' => array('Cliente.' . $this->Cliente->primaryKey => $id));
			$this->request->data = $this->Cliente->find('first', $options);
			$datanasc = $this->request->data['Cliente']['nasc'];
			$nasc=explode('-', $datanasc) ;
			if(!empty($nas)){
				$datanasc = $nasc['2'].'/'.$nasc['1'].'/'.$nasc['0'];
				$this->request->data['Cliente']['nasc']=$datanasc;
			}
			
			$this->loadModel('Empresa');
			$empresa = $this->Empresa->find('first', array('recursive' => -1,'conditions' => array('Empresa.id' => 1)));
			$this->set(compact('empresa','datanasc'));
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
		$this->Cliente->id = $id;
		$this->layout ='liso';
		if (!$this->Cliente->exists()) {
			throw new NotFoundException(__('Invalid cliente'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cliente->delete()) {
			$this->Session->setFlash(__('O cliente foi desavivado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Erro ao desativar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		
		$this->Cliente->id = $id;
		if (!$this->Cliente->exists()) {
			throw new NotFoundException(__('Invalid cliente'));
		}
		$row = $this->Cliente->find('first', array(
			'recursive'=> -1,
			'conditions'=> array(
				'id' => $id
			)
		));
		$ativo = ($row['Cliente']['ativo'] == 1 ? 0: 1);  
		
		$this->request->onlyAllow('post', 'delete');
		
		if ($this->Cliente->saveField('ativo', $ativo )) {
			$this->Session->setFlash(__('O cliente foi ativado/desativado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar o cliente. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}
	public function loginmobile() {
		$this->layout ='ajaxaddpedido';
		$senha= $this->Auth->password($this->request->data['password']);
		$usuario =$this->request->data['username'];

		$ultimopedido="inicio";

		$user=$this->Cliente->find('first', array('recursive' => -1,'conditions' => array('Cliente.username' => $usuario, 'AND' => array('Cliente.password' => $senha))));

		if(!empty($user)){
			$ultimopedido=$user;
			//$this->Auth->allow('*');
		}else{
			$ultimopedido="ErroLogin";
		}
		 //$ultimopedido =$senha;
		$this->set(compact('ultimopedido'));

	}

}
