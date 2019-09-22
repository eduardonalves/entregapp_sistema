<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Empresas');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends AppController {

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
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->loadModel('Funcao');
		$filiais = $this->getSelectFiliais($id);
		$user= $this->User->find('first', array('recursive'=> -1, 'conditions'=> array('User.id'=>$id )));
		$funcao = $this->Funcao->find('first', array('recursive'=> -1, 'conditions'=> array('Funcao.id'=>$user['User']['funcao_id'] )));
		$this->set(compact('user','filiais','funcao'));

	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'usuarios';

		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$minhasFiliais = $this->getFiliais($userid);
		//$lojas = $this->getSelectFiliais($userid);
		$this->loadModel('Filial');
		$lojas=   $this->Filial->find('list', array('recursive'=>-1));

		if(isset($this->request->data['filter']))
		{

			foreach($this->request->data['filter'] as $key=>$value)
			{

				//$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');



			}
		}
		$this->Filter->addFilters(
				array(

		            'minhaslojas' => array(
		                '_Filial.id' => array(
		                    'operator' => '=',
		                    'select'=> $lojas
		                )
		            ),
		            'empresa' => array(
		                'User.empresa_id' => array(
		                    'operator' => '=',

		                )
		            ),
		            'nome' => array(
		                'User.username' => array(
		                    'operator' => '=',

		                )
		            ),
		        )
		    );

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}

		$conditiosAux= $this->Filter->getConditions();
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		if(empty($conditiosAux)){

			//$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

			//$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
		}
		$this->Paginator->settings = array(
					'User' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'User.username asc',
						'fields' => array('DISTINCT User.id', 'User.username', 'User.username', 'User.nome','User.funcao_id','User.foto', 'User.ativo'),
					)
				);
		//$users = $this->User->find('all',array('conditions'=>$this->Filter->getConditions(),'recursive' => 0, 'fields' => array('DISTINCT User.id', 'User.username', 'User.nome','User.funcao_id'), 'order' => 'User.username ASC'));
		$i=0;
		$this->loadModel('Funcao');
		$users =  $this->Paginator->paginate('User');
		foreach ($users as $key => $user) {
			unset($users[$i]['Pedido']);
			unset($users[$i]['Atendimento']);
			unset($users[$i]['Mensagen']);

			$funcao= $this->Funcao->find('first', array('recursive'=>-1, 'conditions' => array('Funcao.id'=> $user['User']['funcao_id'])));

			$users[$i]['User']['funcao']=$funcao['Funcao']['funcao'];
			$i++;
		}


		if ($this->request->is('post')) {

				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$this->User->create();



		 		if($this->request->data['User']['foto']['name']==''){
					unset($this->request->data['User']['foto']);
				}
				if(isset($this->request->data['User']['foto']['error']) && $this->request->data['User']['foto']['error'] === 0) {
			                $source = $this->request->data['User']['foto']['tmp_name']; // Source
			                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

			                $nomedoArquivo = date('YmdHis').rand(1000,999999);
			                $nomedoArquivo= $nomedoArquivo.$this->request->data['User']['foto']['name'];
			                move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
			                $this->request->data['User']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
			                $this->User->create(); // We have a new entry
			                $this->User->save($this->request->data); // Save the request
			                $ultimoUsuario = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.empresa_id'=> $this->Session->read('Auth.User.empresa_id')),'order' =>array('User.id' => 'DESC')));

			           		$this->atualizaFiliais($this->request->data['UsersFilial'], $ultimoUsuario['User']['id']);
			            	$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			           		return $this->redirect( $this->referer() );
			            } else {

				               // Save the request
				               $this->User->create(); // We have a new entry
			               	unset($this->request->data['User']['foto']);
				               if($this->User->save($this->request->data)){
				               		  $ultimoUsuario = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.empresa_id'=> $this->Session->read('Auth.User.empresa_id')),'order' =>array('User.id' => 'DESC')));
			           				$this->atualizaFiliais($this->request->data['UsersFilial'], $ultimoUsuario['User']['id']);
				               		$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				              		return $this->redirect( $this->referer() );
				               }else{
				               		 $this->Session->setFlash(__('Erro ao salvar o usuário . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				               }


			            }

		}
		$this->loadModel('Filial');
		$this->loadModel('Empresa');
		$empresas=   $this->Empresa->find('list', array('recursive'=>-1));
		$filiais=   $this->Filial->find('list', array('recursive'=>-1));
		$funcaos = $this->User->Funcao->find('list');
		$this->set(compact('funcaos', 'filiais','users','empresas'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'usuarios';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$this->loadModel('Filial');

		/*$selecionadas = $this->Filial->query('select f.id,f.nome FROM filials f inner JOIN
					users_filials uf ON
					f.id = uf.filial_id
					inner JOIN users u ON
					u.id =uf.user_id
					WHERE uf.user_id=3');*/


		$filiais=   $this->Filial->find('list', array('recursive'=>-1));
		$this->loadModel('Empresa');
		$empresas=   $this->Empresa->find('list', array('recursive'=>-1));

		$selecionadas = $this->getFiliais($id);



		if ($this->request->is(array('post', 'put'))) {

			if(!$Autorizacao->setAutorizacaolv($autTipo,$userfuncao)){
			//debug($Autorizacao->setAutorizacao($autTipo,$userfuncao));
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}



			if(isset($this->request->data['User']['foto']['error']) && $this->request->data['User']['foto']['error'] === 0) {
				if($this->request->data['User']['foto']['name']==''){
					unset($this->request->data['User']['foto']);
				}else{
					$source = $this->request->data['User']['foto']['tmp_name']; // Source
	                			$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

	                			$nomedoArquivo = date('YmdHis').rand(1000,999999);
	                			$nomedoArquivo= $nomedoArquivo.$this->request->data['User']['foto']['name'];
	               			 move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
	               			 $this->request->data['User']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				}

	                		$this->User->create(); // We have a new entry
	               		 $this->User->saveAll($this->request->data); // Save the request
	               		 $this->atualizaFiliais($this->request->data['UsersFilial'],$id);
	             		$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));

	           			return $this->redirect( $this->referer() );
		            } else {
		            		if(!$Autorizacao->setAutorizacaolv($autTipo,$userfuncao)){
				//debug($Autorizacao->setAutorizacao($autTipo,$userfuncao));
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
			               // Save the request

			               $this->User->create(); // We have a new entry
			              if($this->request->data['User']['foto']['name']==''){
					unset($this->request->data['User']['foto']);
				}
				  $this->atualizaFiliais($this->request->data['UsersFilial'],$id);
			               if($this->User->saveAll($this->request->data)){
			               		$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			              		return $this->redirect( $this->referer() );
			               }else{
			               		 $this->Session->setFlash(__('Erro ao salvar o usuário . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			               }


	           		 }
	} else {
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->request->data = $this->User->find('first', $options);
	}
		$funcaos = $this->User->Funcao->find('list');
		$this->set(compact('funcaos','filiais','selecionadas','empresas'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'usuarios';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		if(!$Autorizacao->setAutorizacaolv($autTipo,$userfuncao)){
		//debug($Autorizacao->setAutorizacao($autTipo,$userfuncao));
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('O usuário foi removido com suceso.'));
			return $this->redirect( $this->referer() );
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o usuário. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		//return $this->redirect(array('action' => 'index'));
	}
	public function login() {
		 $this->layout = 'login';
		 $Empresa = new EmpresasController;
		if($Empresa->empresaAtiva()){
			if ($this->Auth->login()) {

				$userId = $this->Session->read('Auth.User.id');
				$isCatalog= $this->isCatalogModeOn($userId);
				$isGraphic= $this->isGraphicModeOn($userId);

				$this->Session->write('catalogMode', $isCatalog );
				$this->Session->write('graphicMode', $isGraphic );

				$this->redirect($this->Auth->redirect());

			} elseif ((!$this->Auth->login()) && ($this->request->is('post'))) {

				$this->Session->setFlash(__('Usuario ou senha invalidos.'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		}else{
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
		}

	}
	public function loginmobile() {
		$this->layout ='ajaxaddpedido';
		$senha= $this->Auth->password($this->request->data['password']);
		$usuario =$this->request->data['username'];

		$ultimopedido="inicio";

		$user=$this->User->find('first', array('recursive' => -1,'conditions' => array('User.username' => $usuario, 'AND' => array('User.password' => $senha))));
		 $Empresa = new EmpresasController;
		if($Empresa->empresaAtiva()){
			if(!empty($user)){
				$ultimopedido=$user;
				$this->Auth->allow('*');
			}else{
				$ultimopedido="ErroLogin";
			}
		}else{
			$ultimopedido="ErroLogin";
		}

		 //$ultimopedido =$senha;
		$this->set(compact('ultimopedido'));

	}
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	public function getFiliais(&$id ){
		$this->loadModel('Filial');
		$selecionadasquery = $this->Filial->query('select Filial.id,Filial.nome FROM filials Filial inner JOIN
					users_filials uf ON
					Filial.id = uf.filial_id
					inner JOIN users u ON
					u.id =uf.user_id
					WHERE uf.user_id='.$id.'');
		$selecionadas= array();

		foreach ($selecionadasquery as $key=> $filial) {


			array_push($selecionadas, $filial['Filial']['id']);
		}
		if(empty($selecionadas)){
			$selecionadas=array(0);
		}
		return  $selecionadas;
	}

	public function getSelectFiliais(&$id ){
		$this->loadModel('Filial');
		$selecionadasquery = $this->Filial->query('select Filial.id,Filial.nome FROM filials Filial inner JOIN
					users_filials uf ON
					Filial.id = uf.filial_id
					inner JOIN users u ON
					u.id =uf.user_id
					WHERE uf.user_id='.$id.'');
		$selecionadas= array();

		foreach ($selecionadasquery as $key=> $filial) {

			$selecionadas[$filial['Filial']['id']]=$filial['Filial']['nome'];

		}

		return  $selecionadas;
	}
	private function isCatalogModeOn($id='')
	{
		$this->loadModel('Filial');
		$selecionadasquery = $this->Filial->query('select Filial.id,Filial.nome, Filial.modo_catalogo FROM filials Filial inner JOIN
					users_filials uf ON
					Filial.id = uf.filial_id
					inner JOIN users u ON
					u.id =uf.user_id
					WHERE uf.user_id='.$id.'');
		$flagCatalog=false;

		foreach ($selecionadasquery as $key=> $filial) {

			if($filial['Filial']['modo_catalogo']==true)
			{
				$flagCatalog=true;
			}

		}

		return  $flagCatalog;
	}
	private function isGraphicModeOn($id='')
	{
		$this->loadModel('Filial');
		$selecionadasquery = $this->Filial->query('select Filial.id,Filial.nome, Filial.modo_catalogo FROM filials Filial inner JOIN
					users_filials uf ON
					Filial.id = uf.filial_id
					inner JOIN users u ON
					u.id =uf.user_id
					WHERE uf.user_id='.$id.'');
		$flagGraph=false;

		foreach ($selecionadasquery as $key=> $filial) {

			/*if($filial['Filial']['modo_grafica']==true){
				$flagGraph=true;
			}*/

		}

		return  $flagGraph;
	}
	public function atualizaFiliais(&$arrayFiliais, &$userId){

		$this->loadModel('UsersFilial');
		$this->UsersFilial->deleteAll(array('UsersFilial.user_id' => $userId), false);

		foreach ($arrayFiliais as $arrayFilial) {
			if(!empty( $arrayFilial)){
				foreach ($arrayFilial as $filial) {
					$this->UsersFilial->create();
					$saveArray = array(
						'user_id' =>$userId,
						'filial_id' => $filial
					);

					$this->UsersFilial->save($saveArray);
				}
			}


		}

	}
}
