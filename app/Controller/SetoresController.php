<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Setorees Controller
 *
 * @property Setore $Setore
 * @property PaginatorComponent $Paginator
 */

class SetoresController extends AppController {

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
		$this->Setore->recursive = 0;
		$this->set('setore', $this->Paginator->paginate());
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
		if (!$this->Setore->exists($id)) {
			throw new NotFoundException(__('Invalid Setore'));
		}
		$options = array('recursive' => -1,'conditions' => array('Setore.' . $this->Setore->primaryKey => $id));
		$this->set('setore', $this->Setore->find('first', $options));
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
	                'Setore.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Setore.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'setor' => array(
	                'Setore.setor' => array(
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
				'Setore' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Setore.setor asc'
				)
			);
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->Setore->recursive = 0;
		$this->set('setores', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Setore']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Setore->create();
			if ($this->Setore->save($this->request->data)) {
				$this->Session->setFlash(__('A setor foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a setor. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		}
	}
	public function monitorar()
	{
			/*if ($this->request->is(array('post', 'put'))) {

				$this->loadModel('Itensdepedido');
				$dataToSave = array('id' => $this->request->data['id'], 'statuspreparo'=> false );
				$this->layout ='ajaxaddpedido';

				if($this->Itensdepedido->save($dataToSave)){
					$ultimopedido ="ok";
				}else{
					$ultimopedido ="nok";
				}
				$this->set(compact('ultimopedido'));

			}else{
*/
			if ($this->request->is(array('post', 'put')))
			{
				if(isset($this->request->data['id'])){
						$this->loadModel('Itensdepedido');
						$dataToSave = array('id' => $this->request->data['id'], 'statuspreparo'=> 0 );
						$this->layout ='ajaxaddpedido';

						if($this->Itensdepedido->save($dataToSave)){
							$ultimopedido ="ok".$this->request->data['id'];
						}else{
							$ultimopedido ="nok";
						}
						$this->set(compact('ultimopedido'));
				}

			}
			$this->loadModel('Filial');
			$this->loadModel('Itensdepedido');
			$this->loadModel('Produto');
			$this->loadModel('Mesa');
			$this->loadModel('Cliente');
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
					$this->request->data['filter']['preparo']=true;
					$this->request->data['filter']['status']='Confirmado';


				}
			}
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));


			$this->loadModel('Mesa');
			$mesas = $this->Mesa->find('list', array('recursive' => -1, 'order' => 'Mesa.identificacao asc', 'conditions'=> array('Mesa.filial_id'=> $unicaFilial['Filial']['id'])));
			$mesas['']='';
			asort($mesas);
			$mesas['']='Selecione';
			$this->loadModel('Setore');
			$setores = $this->Setore->find('list', array('recursive' => -1, 'order' => 'Setore.setor asc', 'conditions'=> array('Setore.filial_id'=> $unicaFilial['Filial']['id'])));
			$setores['']='';
			asort($setores);
			$setores['']='Selecione';
			$this->Filter->addFilters(
				array(

								'minhaslojas' => array(
										'Itensdepedido.filial_id' => array(
												'operator' => '=',
												'select'=> $lojas
										)
								),
								'status' => array(
										'Pedido.status' => array(
												'operator' => '=',

										)
								),
								'empresa' => array(
										'Pedido.empresa_id' => array(
												'operator' => '=',

										)
								),
								'mesa' => array(
										'Pedido.mesa_id' => array(
												'operator' => '=',
												'select'=> $mesas
										)
								),
								'setor' => array(
										'Produto.setore_id' => array(
												'operator' => '=',
												'select'=> $setores
										)
								),
								'preparo' => array(
										'Itensdepedido.statuspreparo' => array(
												'operator' => '='
											)
								),
						)
				);

				$conditiosAux= $this->Filter->getConditions();
		$this->request->data['filter']['preparo']=true;
		$this->request->data['filter']['status']='Confirmado';
		if(empty($conditiosAux)){

			$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

			$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
			$this->request->data['filter']['status']='Confirmado';
		}

			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}




			if(isset($_GET['json']))
			{
					if($_GET['json']== true)
					{
							$this->layout ='ajaxaddpedido';
							$this->request->data['filter']['preparo']=true  ;
							$this->request->data['filter']['status']='Confirmado'  ;
							//debug($unicaFilial['Filial']['id']);
						//	die();
						$conditions = $this->Filter->getConditions();
						$conditions['Itensdepedido.statuspreparo =']= true;
						$ultimopedido =$this->Itensdepedido->find('all', array('conditions'=> $conditions,'order' => 'Itensdepedido.id asc'));
						$this->loadModel('Mesa');
						foreach ($ultimopedido as $key => $value)
						{
							 $minhaMesa= $this->Mesa->find('first',array(
								'recursive' => -1,
								'conditions' => array(
									'id' => $ultimopedido[$key]['Pedido']['mesa_id'])));
								if(!empty($minhaMesa)){
										if($ultimopedido[$key]['Pedido']['nomecadcliente'] != ''){
											$ultimopedido[$key]['Pedido']['mesa'] = $minhaMesa['Mesa']['identificacao'].' / cliente: '. $ultimopedido[$key]['Pedido']['nomecadcliente'];
										}else{
											$ultimopedido[$key]['Pedido']['mesa'] = $minhaMesa['Mesa']['identificacao'];
										}
								}else{
									$ultimopedido[$key]['Pedido']['mesa'] = 'Sem Mesa';
									$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions'=> array(
										'Cliente.id'=> $ultimopedido[$key]['Pedido']['cliente_id']
									)));
									if(!empty($cliente)){
										$ultimopedido[$key]['Pedido']['mesa'] = $cliente['Cliente']['nome'].' / '. $cliente['Cliente']['username'] ." \n". $ultimopedido[$key]['Pedido']['logradouro'] . ' '. $ultimopedido[$key]['Pedido']['numero'] . ' '. $ultimopedido[$key]['Pedido']['bairro_nome']. ' - '. $ultimopedido[$key]['Pedido']['cidade_nome']. ' - '. $ultimopedido[$key]['Pedido']['estado_nome'];
									}
								}
								$meuSetor = $this->Setore->find('first', array(
									'recursive' => -1,
									'conditions' => array(
										'Setore.id' => $ultimopedido[$key]['Produto']['setore_id']
									)
								));
								if(!empty($meuSetor)){
									$ultimopedido[$key]['Produto']['setor'] = $meuSetor['Setore']['setor'];
								}else{
									$ultimopedido[$key]['Produto']['setor'] = 'Sem Setor';
								}
							//debug($ultimopedido);
						}
						//	debug($ultimopedido);
						//	array('Itensdepedido.filial_id' => $unicaFilial['Filial']['id'],'Itensdepedido.statuspreparo'=> true)
							// var_dump($conditions);
						//	 die();
						//array('Itensdepedido.filial_id' => $unicaFilial['Filial']['id'],'Itensdepedido.statuspreparo'=> true)
							$this->set(compact('ultimopedido'));
					}

			}else{
				$options = array(
					'conditions' => $this->Filter->getConditions(),
				//	'order' => array('Noticia.created' => 'DESC'),
					'limit' => 10
				);
				$this->paginate = $options;

					$pedidos = $this->paginate('Itensdepedido');
					$i=0;
					foreach($pedidos as $itens )
					{
						$setor = $this->Setore->find('first', array('conditions' => array('Setore.id' => $itens['Produto']['setore_id'])));
						if(!empty($setor)){
							$pedidos[$i]['Produto']['setor']= $setor['Setore']['setor'];
						}else{
							$pedidos[$i]['Produto']['setor']='';
						}

						$mesa = $this->Mesa->find('first', array('conditions' => array('Mesa.id' => $itens['Pedido']['mesa_id'])));
						if(!empty($mesa)){
							$pedidos[$i]['Pedido']['mesa']= $mesa['Mesa']['identificacao'];
						}else{
							$pedidos[$i]['Pedido']['mesa']='';
						}


						$i++;
					}
				
					$this->set('pedidos', $pedidos);
			}
		//}
	}

	public function prontos()
	{

			if ($this->request->is(array('post', 'put')))
			{
				if(isset($this->request->data['id'])){
						$this->loadModel('Itensdepedido');
						$dataToSave = array('id' => $this->request->data['id'], 'statusentregarmesa'=> 1 );
						$this->layout ='ajaxaddpedido';

						if($this->Itensdepedido->save($dataToSave)){
							$ultimopedido ="ok".$this->request->data['id'];
						}else{
							$ultimopedido ="nok";
						}
						$this->set(compact('ultimopedido'));
				}

			}
			$this->loadModel('Filial');
			$this->loadModel('Itensdepedido');
			$this->loadModel('Produto');
			$this->loadModel('Mesa');
			$this->loadModel('Cliente');
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
					$this->request->data['filter']['preparo']=false;
					$this->request->data['filter']['statusentregarmesa']=false;


				}
			}
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

			$this->loadModel('Mesa');
			$mesas = $this->Mesa->find('list', array('recursive' => -1, 'order' => 'Mesa.identificacao asc', 'conditions'=> array('Mesa.filial_id'=> $unicaFilial['Filial']['id'])));
			$mesas['']='';
			asort($mesas);
			$mesas['']='Selecione';
			$this->loadModel('Setore');
			$setores = $this->Setore->find('list', array('recursive' => -1, 'order' => 'Setore.setor asc', 'conditions'=> array('Setore.filial_id'=> $unicaFilial['Filial']['id'])));
			$setores['']='';
			asort($setores);
			$setores['']='Selecione';
			$this->Filter->addFilters(
				array(

								'minhaslojas' => array(
										'Itensdepedido.filial_id' => array(
												'operator' => '=',
												'select'=> $lojas
										)
								),
								'empresa' => array(
										'Pedido.empresa_id' => array(
												'operator' => '=',

										)
								),
								'mesa' => array(
										'Pedido.mesa_id' => array(
												'operator' => '=',
												'select'=> $mesas
										)
								),
								'setor' => array(
										'Produto.setore_id' => array(
												'operator' => '=',
												'select'=> $setores
										)
								),
								'preparo' => array(
										'Itensdepedido.statuspreparo' => array(
												'operator' => '='
											)
								),
								'statusentregarmesa' => array(
										'Itensdepedido.statusentregarmesa' => array(
												'operator' => '='
											)
								),

						)
				);

				$conditiosAux= $this->Filter->getConditions();
		$this->request->data['filter']['preparo']=false;
		$this->request->data['filter']['statusentregarmesa']=false;
		if(empty($conditiosAux)){

			$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

			$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
		}

			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}



			if(isset($_GET['json']))
			{
					if($_GET['json']== true)
					{
							$this->layout ='ajaxaddpedido';
							$this->request->data['filter']['preparo']=false  ;
							//debug($unicaFilial['Filial']['id']);
						//	die();
						$conditions = $this->Filter->getConditions();
						$conditions['Itensdepedido.statuspreparo =']= false;
						$conditions['Itensdepedido.statusentregarmesa =']= false;

							$ultimopedido =$this->Itensdepedido->find('all', array('conditions'=> $conditions,'order' => 'Itensdepedido.id asc'));

							$this->loadModel('Mesa');
							foreach ($ultimopedido as $key => $value)
							{
								 $minhaMesa= $this->Mesa->find('first',array(
									'recursive' => -1,
									'conditions' => array(
										'id' => $ultimopedido[$key]['Pedido']['mesa_id'])));
									if(!empty($minhaMesa)){
											if($ultimopedido[$key]['Pedido']['nomecadcliente'] != ''){
												$ultimopedido[$key]['Pedido']['mesa'] = $minhaMesa['Mesa']['identificacao'].' / cliente: '. $ultimopedido[$key]['Pedido']['nomecadcliente'];
											}else{
												$ultimopedido[$key]['Pedido']['mesa'] = $minhaMesa['Mesa']['identificacao'];
											}
											
									}else{
										$ultimopedido[$key]['Pedido']['mesa'] = 'Sem Mesa';

										$ultimopedido[$key]['Pedido']['mesa'] = 'Sem Mesa';
										$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions'=> array(
											'Cliente.id'=> $ultimopedido[$key]['Pedido']['cliente_id']
										)));
										if(!empty($cliente)){
											$ultimopedido[$key]['Pedido']['mesa'] = $cliente['Cliente']['nome'].' / '. $cliente['Cliente']['username'];
										}
									}
									$meuSetor = $this->Setore->find('first', array(
										'recursive' => -1,
										'conditions' => array(
											'Setore.id' => $ultimopedido[$key]['Produto']['setore_id']
										)
									));
									if(!empty($meuSetor)){
										$ultimopedido[$key]['Produto']['setor'] = $meuSetor['Setore']['setor'];
									}else{
										$ultimopedido[$key]['Produto']['setor'] = 'Sem Setor';
									}
								//debug($ultimopedido);
							}
						//	array('Itensdepedido.filial_id' => $unicaFilial['Filial']['id'],'Itensdepedido.statuspreparo'=> true)
							// var_dump($conditions);
						//	 die();
						//array('Itensdepedido.filial_id' => $unicaFilial['Filial']['id'],'Itensdepedido.statuspreparo'=> true)
							$this->set(compact('ultimopedido'));
					}

			}else{
				$options = array(
					'conditions' => $this->Filter->getConditions(),
				//	'order' => array('Noticia.created' => 'DESC'),
					'limit' => 10
				);
				$this->paginate = $options;

					$pedidos = $this->paginate('Itensdepedido');
					$i=0;
					foreach($pedidos as $itens )
					{
						$setor = $this->Setore->find('first', array('conditions' => array('Setore.id' => $itens['Produto']['setore_id'])));
						if(!empty($setor)){
							$pedidos[$i]['Produto']['setor']= $setor['Setore']['setor'];
						}else{
							$pedidos[$i]['Produto']['setor']='';
						}

						$mesa = $this->Mesa->find('first', array('conditions' => array('Mesa.id' => $itens['Pedido']['mesa_id'])));
						if(!empty($mesa)){
							$pedidos[$i]['Pedido']['mesa']= $mesa['Mesa']['identificacao'];
						}else{
							$pedidos[$i]['Pedido']['mesa']='';
						}


						$i++;
					}
					$this->set('pedidos', $pedidos);
			}

	}

	public function confirmar()
	{
		$this->loadModel('Itensdepedido');
		$dataToSave = array('id' => $this->request->data['id'], 'statuspreparo'=> false );
		$this->layout ='ajaxaddpedido';

		if($this->Itensdepedido->save($dataToSave)){
			$ultimopedido ="ok";
		}else{
			$ultimopedido ="nok";
		}
		$this->set(compact('ultimopedido'));
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
		if (!$this->Setore->exists($id)) {
			throw new NotFoundException(__('Invalid setor'));
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
			if ($this->Setore->save($this->request->data)) {
				$this->Session->setFlash(__('A  setor foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a  setor. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('recursive' => -1, 'conditions' => array('Setore.' . $this->Setore->primaryKey => $id));
			$this->request->data = $this->Setore->find('first', $options);
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
		$this->Setore->id = $id;
		if (!$this->Setore->exists()) {
			throw new NotFoundException(__('Invalid setor'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Setore->delete()) {
			$this->Session->setFlash(__('A setor foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a setor. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->Setore->id = $id;
		if (!$this->Setore->exists()) {
			throw new NotFoundException(__('Invalid setor'));
		}
		$row = $this->Setore->find('first', array(
			'recursive'=> -1,
			'conditions'=> array(
				'id' => $id
			)
		));
		$ativo = ($row['Setore']['ativo'] == 1 ? 0: 1);

		$this->request->onlyAllow('post', 'delete');
		if ($this->Setore->saveField('ativo', $ativo)) {

			$this->Session->setFlash(__('O setor foi habilitado/desabilitado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao habilitar/desabilitador o setor. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}
}
