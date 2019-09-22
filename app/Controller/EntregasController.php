<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Entregas Controller
 *
 * @property Entrega $Entrega
 * @property PaginatorComponent $Paginator
 */

class EntregasController extends AppController {

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
		$this->Entrega->recursive = 0;
		$this->set('entrega', $this->Paginator->paginate());
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
		if (!$this->Entrega->exists($id)) {
			throw new NotFoundException(__('Invalid Entrega'));
		}
		$options = array('recursive' => -1,'conditions' => array('Entrega.' . $this->Entrega->primaryKey => $id));
		$this->set('entrega', $this->Entrega->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
 public function salao()
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
 					//$this->loadModel('M');
 					//$dataToSave = array('id' => $this->request->data['id'], 'ativo'=> 1 );
 					//$this->layout ='ajaxaddpedido';

 					//if($this->Itensdepedido->save($dataToSave)){
 					//	$ultimopedido ="ok".$this->request->data['id'];
 					//}else{
 						//$ultimopedido ="nok";
 					//}
 					//$this->set(compact('ultimopedido'));
 			}

 		}
 		$this->loadModel('Filial');
 		$this->loadModel('Itensdepedido');
 		$this->loadModel('Produto');
 		$this->loadModel('Entrega');
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


 			}
 		}
 		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

 		//$this->loadModel('Entrega');
 		//$entregas = $this->Entrega->find('list', array('recursive' => -1, 'order' => 'Entrega.identificacao asc', 'conditions'=> array('Entrega.filial_id'=> $unicaFilial['Filial']['id'])));
 		//$entregas['']='';
 		//asort($entregas);
 		//$entregas['']='Selecione';
 		//$this->loadModel('Setore');
 		//$setores = $this->Setore->find('list', array('recursive' => -1, 'order' => 'Setore.setor asc', 'conditions'=> array('Setore.filial_id'=> $unicaFilial['Filial']['id'])));
 		//$setores['']='';
 		//asort($setores);
 		//$setores['']='Selecione';
 		$this->Filter->addFilters(
 			array(

 							'minhaslojas' => array(
 									'Entrega.filial_id' => array(
 											'operator' => '=',
 											'select'=> $lojas
 									)
 							),
 							'empresa' => array(
 									'Entrega.empresa_id' => array(
 											'operator' => '=',

 									)
 							),

 					)
 			);

 			$conditiosAux= $this->Filter->getConditions();
 	$this->request->data['filter']['ativo']=true;
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
 						$this->request->data['filter']['preparo']=true  ;
 						//debug($unicaFilial['Filial']['id']);
 					//	die();
 					$conditions = $this->Filter->getConditions();
 					$conditions['Entrega.ativo =']= true;
					$this->loadModel('Pedido');
 					$ultimopedido =$this->Entrega->find('all', array('recursive'=> -1,'conditions'=> $conditions,'order' => 'Entrega.id asc'));
					
						foreach ($ultimopedido as $key => $value) {
							$ultimopedido[$key]['Pedidos'] = $this->Pedido->find('all',array('conditions'=> array('AND'=> array(array('Pedido.entrega_id'=> $value['Entrega']['id']),array('Pedido.status_finalizado' => false)))));
							$ultimopedido[$key]['Entrega']['total_pedidos']=(count($ultimopedido[$key]['Pedidos']) > 0);
							$countPedidosNaoPagos = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.entrega_id'=> $value['Entrega']['id']),array('Pedido.status_finalizado' => false), array('Pedido.status_pagamento' => 'PENDENTE')))));
							$countPedidosPagos = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.entrega_id'=> $value['Entrega']['id']),array('Pedido.status_finalizado' => false), array('Pedido.status_pagamento' => 'OK')))));
							$countTotalPedidosAberto = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.entrega_id'=> $value['Entrega']['id']),array('Pedido.status_finalizado' => false)))));

							if($countTotalPedidosAberto > 0)
							{

									if($countPedidosPagos == $countTotalPedidosAberto ){
										$ultimopedido[$key]['Entrega']['status_pedido']='F';
									}else{
										if($countPedidosPagos > 0){
												$ultimopedido[$key]['Entrega']['status_pedido']='A';
										}else{
											$ultimopedido[$key]['Entrega']['status_pedido']='O';
										}

									}
							}else{
								$ultimopedido[$key]['Entrega']['status_pedido']='L';
							}
							$total= 0;
							foreach ($ultimopedido[$key]['Pedidos'] as $key2 => $value2)
							{
								$total += $value2['Pedido']['valor'];
							}
							$ultimopedido[$key]['Entrega']['total_aberto']=number_format($total,2,',','.');

						}
						//debug($ultimopedido);
						//die();
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

 				$entregas = $this->paginate('Entrega');

 				/*$i=0;
 				foreach($pedidos as $itens )
 				{
 					$setor = $this->Setore->find('first', array('conditions' => array('Setore.id' => $itens['Produto']['setore_id'])));
 					if(!empty($setor)){
 						$pedidos[$i]['Produto']['setor']= $setor['Setore']['setor'];
 					}else{
 						$pedidos[$i]['Produto']['setor']='';
 					}

 					$entrega = $this->Entrega->find('first', array('conditions' => array('Entrega.id' => $itens['Pedido']['entrega_id'])));
 					if(!empty($entrega)){
 						$pedidos[$i]['Pedido']['entrega']= $entrega['Entrega']['identificacao'];
 					}else{
 						$pedidos[$i]['Pedido']['entrega']='';
 					}


 					$i++;
 				}*/
 				$this->set('entregas', $entregas);
 		}
 	//}
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
		                'Entrega.filial_id' => array(
		                    'operator' => '=',
		                    'select'=> $lojas
		                )
		            ),
		            'empresa' => array(
		                'Entrega.empresa_id' => array(
		                    'operator' => '=',

		                )
		            ),
		            'identificacao' => array(
		                'Entrega.identificacao' => array(
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
					'Entrega' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'Entrega.identificacao asc'
					)
				);
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Entrega->recursive = 0;
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

	 		$this->loadModel('Atendente');
	 		$atendentes = $this->Atendente->find('list', array('recursive' => -1, 'order' => 'Atendente.nome asc', 'conditions'=> array('Atendente.filial_id'=> $unicaFilial['Filial']['id'], 'Atendente.ativo' => 1 )));
			$this->set(compact('atendentes'));
			$this->set('entregas', $this->Paginator->paginate());
			if ($this->request->is('post')) {
				$this->request->data['Entrega']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$this->Entrega->create();
				if ($this->Entrega->save($this->request->data)) {
					$this->Session->setFlash(__('A entrega foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect( $this->referer() );
				} else {
					$this->Session->setFlash(__('Houve um erro ao salvar a entrega. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Entrega->exists($id)) {
			throw new NotFoundException(__('Invalid entrega'));
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
			if ($this->Entrega->save($this->request->data)) {
				$this->Session->setFlash(__('A  entrega foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a  entrega. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$User = new UsersController;
			$userid = $this->Session->read('Auth.User.id');
			$minhasFiliais = $User->getFiliais($userid);
			$lojas = $User->getSelectFiliais($userid);
			$this->loadModel('Filial');
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

			$this->loadModel('Atendente');
			$atendentes = $this->Atendente->find('list', array('recursive' => -1, 'order' => 'Atendente.nome asc', 'conditions'=> array('Atendente.filial_id'=> $unicaFilial['Filial']['id'], 'Atendente.ativo' => 1 )));
			$this->set(compact('atendentes'));

			$options = array('recursive' => -1, 'conditions' => array('Entrega.' . $this->Entrega->primaryKey => $id));
			$this->request->data = $this->Entrega->find('first', $options);
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
		public function acoes($id = null) {
			$this->layout ='liso';
			if (!$this->Entrega->exists($id)) {
				throw new NotFoundException(__('Invalid entrega'));
			}
			$User = new UsersController;
			$userid = $this->Session->read('Auth.User.id');
			$minhasFiliais = $User->getFiliais($userid);
			$lojas = $User->getSelectFiliais($userid);

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
				if ($this->Entrega->save($this->request->data)) {
					$this->Session->setFlash(__('A  entrega foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect( $this->referer() );
				} else {
					$this->Session->setFlash(__('Houve um erro ao salvar a  entrega. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				}
			} else {
				$options = array('recursive' => -1, 'conditions' => array('Entrega.' . $this->Entrega->primaryKey => $id));
				$pedidos = $this->Entrega->find('first', $options);
				$this->loadModel('Pedido');
				$this->loadModel('Produto');
				$this->loadModel('Pgtopedido');
				$this->loadModel('Pagamento');
				$pedidos['Pedidos'] = $this->Pedido->find('all',array('conditions'=> array('AND'=> array(array('Pedido.entrega_id'=> $id),array('Pedido.status_finalizado' => false)))));
				foreach ($pedidos['Pedidos'] as $key => $value)
				{
						foreach ($value['Itensdepedido'] as $key2 => $value2)
						{
							$produtoNome =$this->Produto->find('first',array(
								'recursive'=>-1,
								'conditions'=> array(
									'Produto.id'=> $pedidos['Pedidos'][$key]['Itensdepedido'][$key2]['produto_id']
								)
							));
							$pedidos['Pedidos'][$key]['Itensdepedido'][$key2]['produto_nome'] = $produtoNome['Produto']['nome'];

						}

				}
				//debug($pedidos['Pedidos']);

				//die();
				$pedidos['Pgtopedido'] = $this->Pgtopedido->find('all',array('conditions'=> array('AND'=> array(array('Pgtopedido.entrega_id'=> $id),array('Pgtopedido.status !=' => 'D'),array('Pgtopedido.status !=' => 'F'),array('Pgtopedido.status !=' => 'C')))));
				foreach ($pedidos['Pgtopedido'] as $key3 => $value3)
				{

					$pagamentoNome =$this->Pagamento->find('first',array(
						'recursive'=>-1,
						'conditions'=> array(
							'Pagamento.id'=> $pedidos['Pgtopedido'][$key3]['Pgtopedido']['pagamento_id']
						)
					));
					//debug($pedidos['Pgtopedido'][$key3]['Pgtopedido']['pagamento_id']);
					//die();
					if($pedidos['Pgtopedido'][$key3]['Pgtopedido']['pagamento_id'] != null){
							if(!empty($pagamentoNome)){
									$pedidos['Pgtopedido'][$key3]['Pgtopedido']['pgnome'] = $pagamentoNome['Pagamento']['tipo'];
							}else{
									$pedidos['Pgtopedido'][$key3]['Pgtopedido']['pgnome']  = 'N/D';
							}

					}else{
						$pedidos['Pgtopedido'][$key3]['Pgtopedido']['pgnome']  = 'N/D';
					}

				}
				$minhasEntregas = $this->Entrega->find('all', array('recursive' => -1, 'conditions'=> array('AND'=> array(array('Entrega.filial_id' => $minhasFiliais), array('Entrega.ativo' => 1)))));
				$this->loadModel('Pagamento');
				$meusPagamentos  = $this->Pagamento->find('all', array('recursive' => -1, 'conditions'=> array('Pagamento.filial_id' => $minhasFiliais)));
				//debug($meusPagamentos);
				//die();
				$this->set(compact('pedidos','minhasEntregas','meusPagamentos'));
			}
		}
		public function cancelaritem()
		{

			$this->layout ='ajaxaddpedido';

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'produtos';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$ultimopedido='';
			if ($this->request->is(array('post', 'put'))) {
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$this->loadModel('Itensdepedido');

				$dateToUpdate = array('id' => $this->request->data['id'],'status_cancelado' => 1 );

				if ($this->Itensdepedido->save($dateToUpdate))
				{
					$ultimopedido='Sucesso';
				}else{
					$ultimopedido='Erro';
				}
			}
			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));
		}

		public function cancelarpagamento()
		{

			$this->layout ='ajaxaddpedido';

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'produtos';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$ultimopedido='';
			if ($this->request->is(array('post', 'put'))) {
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$this->loadModel('Pgtopedido');
				$pagamento= $this->Pgtopedido->find('first', array('recursive'=> -1,'conditions'=> array('Pgtopedido.id'=> $this->request->data['id'])));

				$dateToUpdate = array('id' => $this->request->data['id'],'status' => 'C' );

				if ($this->Pgtopedido->save($dateToUpdate))
				{
					$entrega = $this->Entrega->find('first',array('recursive'=> -1,'conditions'=> array(
						'Entrega.id' => $pagamento['Pgtopedido']['entrega_id']
					)));
					$taxaAtual = (double) $entrega['Entrega']['taxa'] - (double)  $pagamento['Pgtopedido']['taxa'];
					$descAtual = $entrega['Entrega']['desconto'];
					$desconto = (double) $descAtual - (double) $pagamento['Pgtopedido']['desconto'];
					$entregaToUpdate = array(
						'id' => $entrega['Entrega']['id'],
						'taxa' => $taxaAtual,
						'desconto' => $desconto
					);
					$this->Entrega->save($entregaToUpdate);
					$itensEntrega  = $pagamento['Pgtopedido']['itenspg'];
					$itensEntrega = explode(',',$itensEntrega);
					$this->loadModel('Itensdepedido');
					foreach ($itensEntrega as $item)
					{
						if($item != '')
						{
							$itemToUpdate = array(
								'id' => $item,
								'status_pago' => NULL
							);

							$this->Itensdepedido->save($itemToUpdate);
						}
					}
					$ultimopedido='Sucesso';
				}else{
					$ultimopedido='Erro';
				}
			}
			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));
		}
		public function inserirdesconto()
		{

			$this->layout ='ajaxaddpedido';

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'produtos';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$ultimopedido='';
			if ($this->request->is(array('post', 'put'))) {
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}


				$dateToUpdate = array('id' => $this->request->data['id'],'desconto' => $this->request->data['desconto'] );

				if ($this->Entrega->save($dateToUpdate))
				{
					$ultimopedido='Sucesso';
				}else{
					$ultimopedido='Erro';
				}
			}
			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));
		}

		public function trocarentrega()
		{

			$this->layout ='ajaxaddpedido';

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'produtos';

			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');

			$User = new UsersController;
			$userid = $this->Session->read('Auth.User.id');
			$minhasFiliais = $User->getFiliais($userid);
			$lojas = $User->getSelectFiliais($userid);
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$ultimopedido='';


			if ($this->request->is(array('post', 'put'))) {
				$this->loadModel('Pedido');
	      $entregaNova = $this->Entrega->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Entrega.filial_id'=>$minhasFiliais), array('Entrega.id'=> $this->request->data['entreganova_id'])))));
				$entregaAntiga = $this->Entrega->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Entrega.filial_id'=>$minhasFiliais), array('Entrega.id'=> $this->request->data['entrega_id'])))));

				if(!empty($entregaNova)){
					$pedidos = $this->Pedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Pedido.filial_id'=>$minhasFiliais), array('Pedido.entrega_id'=> $this->request->data['entrega_id']), array('Pedido.status_finalizado'=> 0)))));
					$entregaId='';
					if(!empty($pedidos)){
						foreach ($pedidos as $pedido) {
							$dataToUpdate = array(
								'id' => $pedido['Pedido']['id'],
								'entrega_id' => $this->request->data['entreganova_id']
							);
							$entregaId = $pedido['Pedido']['entrega_id'];
							$this->Pedido->save($dataToUpdate);
						}

						$this->loadModel('Pgtopedido');
						$pagamentos = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array( array('Pgtopedido.entrega_id'=> $entregaId), array('Pgtopedido.status_finalizado'=> 0)))));
						if(!empty($pagamentos)){
							foreach ($pagamentos as $pagamento) {
								$dataToUpdate = array(
									'id' => $pagamento['Pgtopedido']['id'],
									'entrega_id' => $this->request->data['entreganova_id']
								);

								$this->Pgtopedido->save($dataToUpdate);
							}
						}
						$novaTaxa = (double) $entregaNova['Entrega']['taxa'] + (double) $entregaAntiga['Entrega']['taxa'];
						$novoDesconto = (double) $entregaNova['Entrega']['desconto'] + (double) $entregaAntiga['Entrega']['desconto'];
						$updateEntregaNova = array(
							'id' => $entregaNova['Entrega']['id'],
							'taxa' => $novaTaxa,
							'desconto' => $novoDesconto
						);
						$this->Entrega->save($updateEntregaNova);
						$updateEntregaAntiga = array(
							'id' => $this->request->data['entrega_id'],
							'taxa' => NULL,
							'desconto'=> NULL
						);
						$this->Entrega->save($updateEntregaAntiga);

						$ultimopedido='Sucesso';
					}else{
						$ultimopedido='Vazio';
					}


				}else{
					$ultimopedido="Erro";
				}
			}
			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));
		}


		public function fecharentrega()
		{

				$this->layout ='ajaxaddpedido';

				$Autorizacao = new AutorizacaosController;
				$autTipo = 'produtos';

				$userid = $this->Session->read('Auth.User.id');
				$userfuncao = $this->Session->read('Auth.User.funcao_id');

				$User = new UsersController;
				$userid = $this->Session->read('Auth.User.id');
				$minhasFiliais = $User->getFiliais($userid);
				$lojas = $User->getSelectFiliais($userid);
				if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao))
				{
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$ultimopedido='';

				//$this->request->data['entrega_id'] = 3;
				if ($this->request->is(array('post', 'put')))
				{

					$this->loadModel('Pedido');
					$this->loadModel('Venda');
					$entrega = $this->Entrega->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Entrega.filial_id'=>$minhasFiliais), array('Entrega.id'=> $this->request->data['entrega_id'])))));

					$pedidos = $this->Pedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Pedido.filial_id'=>$minhasFiliais), array('Pedido.entrega_id'=> $this->request->data['entrega_id']), array('Pedido.status_finalizado'=> 0)))));
					$this->loadModel('Itensdepedido');
					$this->loadModel('Vendasiten');
					$this->loadModel('Vendaspagamento');
					$this->loadModel('Pgtopedido');
					//Inicio validacao
					$isValidItens= false;
					$countPago = 0;
					$countCancelado = 0;
					$contTotalItens=0;
					$valorTotalItens=0;
					foreach ($pedidos as $pedido_validacao)
					{

						$itens_validacao = $this->Itensdepedido->find('all', array('recursive'=> -1, 'conditions'=> array('Itensdepedido.pedido_id'=> $pedido_validacao['Pedido']['id'], 'Itensdepedido.status_cancelado'=> NULL )));

						foreach ($itens_validacao as $iten_validacao)
						{

							if($iten_validacao['Itensdepedido']['status_pago']==1 && $iten_validacao['Itensdepedido']['status_cancelado'] != 1)
							{
								$valorTotalItens =$valorTotalItens + 	$iten_validacao['Itensdepedido']['valor_total'];
								$countPago++;
							}
							if($iten_validacao['Itensdepedido']['status_cancelado']==1)
							{
								$countCancelado++;
							}
							$contTotalItens++;
						}

					}

					$testCount = $countPago + $countCancelado;

					if($testCount == $contTotalItens)
					{
						$isValidItens = true;
					}else{
						$isValidItens = false;
					}


					$pagamentosValid = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('Pgtopedido.entrega_id'=> $entrega['Entrega']['id'], 'Pgtopedido.status'=> 'A' )));

					$valorCheckPagamento =0;

					foreach ($pagamentosValid as $pgtoValid)
					{

						if($pgtoValid['Pgtopedido']['status'] =='A' && $pgtoValid['Pgtopedido']['status_finalizado']== false)
						{
								$valorCheckPagamento = $valorCheckPagamento + $pgtoValid['Pgtopedido']['valor'];
						}

					}

					$taxa = $entrega['Entrega']['taxa'];
					$descont= $entrega['Entrega']['desconto'];
					$valorToCheck = (((double) $valorTotalItens + (double) $taxa ) - (double) $descont )  ;

				//	$resultToCheck = (double) $valorToCheck - (double) $valorCheckPagamento;
				$checkPagamento = false;
				if((float) $valorCheckPagamento >= $valorToCheck)
				{
					$checkPagamento = true;
				}else{
					$checkPagamento = false;
				}

					//Fim Validacao

					if($checkPagamento == true &&  $isValidItens==true)
					{
						$vendaToSave = array(
							'desconto' => $entrega['Entrega']['desconto'],
							'taxa' => $entrega['Entrega']['taxa'],
							'user_id' => $userid,
							'data'=> date('Y-m-d'),
							'hora_atendimento' => date('H:i:s'),
							'filial_id' => $entrega['Entrega']['filial_id'],
							'empresa_id' => $entrega['Entrega']['empresa_id'],
							'status' => 'Finalizado',
							'valor'=> $valorCheckPagamento,
							'atendente_id' => $entrega['Entrega']['atendente_id']
						);
						$this->Venda->create();
						$this->Venda->save($vendaToSave);

						$venda = $this->Venda->find('first', array('recursive'=> -1 , 'order' => array('Venda.id DESC'),'conditions'=> array('Venda.filial_id'=>$minhasFiliais)));
						$itensVenda = array();
						$totalItens =0;
						foreach ($pedidos as $pedido)
						{

							$itens = $this->Itensdepedido->find('all', array('recursive'=> -1, 'conditions'=> array('Itensdepedido.pedido_id'=> $pedido['Pedido']['id'], 'Itensdepedido.status_cancelado'=> NULL )));
							foreach ($itens as $iten)
							 {
								$itensToSave=array(
									'venda_id' => $venda['Venda']['id'],
									'produto_id' => $iten['Itensdepedido']['produto_id'],
									 'valor_unit' => $iten['Itensdepedido']['valor_unit'],
										'valor_total' => $iten['Itensdepedido']['valor_total'],
										'qtde' => $iten['Itensdepedido']['qtde'],
										'filial_id' => $iten['Itensdepedido']['filial_id'],
										'status' => 'Ativo'
								);
								$this->Vendasiten->create();
								$this->Vendasiten->save($itensToSave);
							}
							$pagamentos = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('Pgtopedido.entrega_id'=> $entrega['Entrega']['id'], 'Pgtopedido.status'=> 'A' )));

							foreach ($pagamentos as $pgto)
							{
								$pgtoToSave=array(
									'venda_id' => $venda['Venda']['id'],
									'pagamento_id' => $pgto['Pgtopedido']['pagamento_id'],
									 'obs' => $pgto['Pgtopedido']['obs'],
										'valor' => $pgto['Pgtopedido']['valor'],
										'status' => 'Ativo'
								);
								$this->Vendaspagamento->create();
								$this->Vendaspagamento->save($pgtoToSave);
							}
							$pagamentosToUpdate = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=>array(
								'Pgtopedido.entrega_id' => $entrega['Entrega']['id'], 'Pgtopedido.status_finalizado' => 0
							)));

							foreach ($pagamentosToUpdate as $pgtosToUp)
							{
								$this->Pgtopedido->save(array('id' => $pgtosToUp['Pgtopedido']['id'],
								'status_finalizado' => 1,
								'status' => 'F'
								));
							}
							$pedidoOldToUpdate = array(
								'id' => $pedido['Pedido']['id'],
								'status_finalizado' => 1,
								'status' => 'Finalizado'
							);
							$this->Pedido->save($pedidoOldToUpdate);
							$this->Entrega->save(
								array('id'=> $entrega['Entrega']['id'], 'taxa'=> NULL, 'desconto'=> NULL, )
							);
								$ultimopedido='Sucesso';
						}

					}else{
						$ultimopedido='Erro';
					}



					}else{
						$ultimopedido='Vazio';
					}





			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));
		}



/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Entrega->id = $id;
		if (!$this->Entrega->exists()) {
			throw new NotFoundException(__('Invalid entrega'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Entrega->delete()) {
			$this->Session->setFlash(__('A entrega foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a entrega. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}}
