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
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
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
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}

		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
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
             	$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
           		return $this->redirect( $this->referer() );
            } else {
               
	               // Save the request
	               $this->User->create(); // We have a new entry
	               	$this->request->data['User']['foto']='-';
	               if($this->User->save($this->request->data)){
	               		$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	              		return $this->redirect( $this->referer() );
	               }else{
	               		 $this->Session->setFlash(__('Erro ao salvar o usuário . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
	               } 
              
               
            }
			
		}
		$funcaos = $this->User->Funcao->find('list');
		$this->set(compact('funcaos'));
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
		if ($this->request->is(array('post', 'put'))) {
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			debug($Autorizacao->setAutorizacao($autTipo,$userfuncao));
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			
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
             	$this->Session->setFlash(__('O usuário foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
           		return $this->redirect( $this->referer() );
            } else {
            
	               // Save the request
	               $this->User->create(); // We have a new entry
	               	$this->request->data['User']['foto']='-';
	               if($this->User->save($this->request->data)){
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
		$this->set(compact('funcaos'));
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
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o usuário. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	public function login() {
		 $this->layout = 'login';
		 $Empresa = new EmpresasController;
		if($Empresa->empresaAtiva()){
			if ($this->Auth->login()) {
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
	
	}
