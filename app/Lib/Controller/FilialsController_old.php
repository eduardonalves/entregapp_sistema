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
class FilialsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler');


/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Filial->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('Filial.' . $this->Filial->primaryKey => $id));
		$this->set('filial', $this->Filial->find('first', $options));
	}



/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Filial->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'usuarios';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if ($this->request->is(array('post', 'put'))) {

	               // Save the request
	               $this->Filial->create(); // We have a new entry

	               if($this->Filial->save($this->request->data)){
	               	$this->Session->setFlash(__('A loja foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	              	return $this->redirect( $this->referer() );
	               }else{
	               	$this->Session->setFlash(__('Erro ao salvar a loja . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
	               }

	            }else{

			$options = array('conditions' => array('Filial.' . $this->Filial->primaryKey => $id));
			$this->request->data = $this->Filial->find('first', $options);

		}



	}
}