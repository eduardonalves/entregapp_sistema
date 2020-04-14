<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Mesas Controller
 *
 * @property Mesa $Mesa
 * @property PaginatorComponent $Paginator
 */

class MesasController extends AppController {

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
		$this->Mesa->recursive = 0;
		$this->set('mesa', $this->Paginator->paginate());
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
		if (!$this->Mesa->exists($id)) {
			throw new NotFoundException(__('Invalid Mesa'));
		}
		$options = array('recursive' => -1,'conditions' => array('Mesa.' . $this->Mesa->primaryKey => $id));
		$this->set('mesa', $this->Mesa->find('first', $options));
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
 		$this->loadModel('Mesa');
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

 		//$this->loadModel('Mesa');
 		//$mesas = $this->Mesa->find('list', array('recursive' => -1, 'order' => 'Mesa.identificacao asc', 'conditions'=> array('Mesa.filial_id'=> $unicaFilial['Filial']['id'])));
 		//$mesas['']='';
 		//asort($mesas);
 		//$mesas['']='Selecione';
 		//$this->loadModel('Setore');
 		//$setores = $this->Setore->find('list', array('recursive' => -1, 'order' => 'Setore.setor asc', 'conditions'=> array('Setore.filial_id'=> $unicaFilial['Filial']['id'])));
 		//$setores['']='';
 		//asort($setores);
 		//$setores['']='Selecione';
 		$this->Filter->addFilters(
 			array(

 							'minhaslojas' => array(
 									'Mesa.filial_id' => array(
 											'operator' => '=',
 											'select'=> $lojas
 									)
 							),
 							'empresa' => array(
 									'Mesa.empresa_id' => array(
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
 					$conditions['Mesa.ativo =']= true;
					$this->loadModel('Pedido');
 						$ultimopedido =$this->Mesa->find('all', array('recursive'=> -1,'conditions'=> $conditions,'order' => 'Mesa.identificacao asc'));

 						

						foreach ($ultimopedido as $key => $value) {

							
							$ultimopedido[$key]['Pedidos'] = $this->Pedido->find('all',array('conditions'=> array('AND'=> array(array('Pedido.mesa_id'=> $value['Mesa']['id']),array('Pedido.status_finalizado' => false)))));
							//debug($value['Mesa']['id']);
 							//die;
							$ultimopedido[$key]['Mesa']['total_pedidos']=(count($ultimopedido[$key]['Pedidos']) > 0);
							$countPedidosNaoPagos = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.mesa_id'=> $value['Mesa']['id']),array('Pedido.status_finalizado' => false), array('Pedido.status_pagamento' => 'PENDENTE')))));
							$countPedidosPagos = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.mesa_id'=> $value['Mesa']['id']),array('Pedido.status_finalizado' => false), array('Pedido.status_pagamento' => 'OK')))));
							$countTotalPedidosAberto = $this->Pedido->find('count',array('recursive'=> -1,'conditions'=> array('AND'=> array(array('Pedido.mesa_id'=> $value['Mesa']['id']),array('Pedido.status_finalizado' => false)))));

							if($countTotalPedidosAberto > 0)
							{

									if($countPedidosPagos == $countTotalPedidosAberto ){
										$ultimopedido[$key]['Mesa']['status_pedido']='F';
									}else{
										if($countPedidosPagos > 0){
												$ultimopedido[$key]['Mesa']['status_pedido']='A';
										}else{
											$ultimopedido[$key]['Mesa']['status_pedido']='O';
										}

									}
							}else{
								$ultimopedido[$key]['Mesa']['status_pedido']='L';
							}
							$total= 0;
							foreach ($ultimopedido[$key]['Pedidos'] as $key2 => $value2)
							{
								$total += $value2['Pedido']['valor'];
							}
							$ultimopedido[$key]['Mesa']['total_aberto']=number_format($total,2,',','.');

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

 				$mesas = $this->paginate('Mesa');

 				/*$i=0;
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
 				}*/
 				$this->set('mesas', $mesas);
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
		                'Mesa.filial_id' => array(
		                    'operator' => '=',
		                    'select'=> $lojas
		                )
		            ),
		            'empresa' => array(
		                'Mesa.empresa_id' => array(
		                    'operator' => '=',

		                )
		            ),
		            'identificacao' => array(
		                'Mesa.identificacao' => array(
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
					'Mesa' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'Mesa.identificacao asc'
					)
				);
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Mesa->recursive = 0;
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

	 		$this->loadModel('Atendente');
	 		$atendentes = $this->Atendente->find('list', array('recursive' => -1, 'order' => 'Atendente.nome asc', 'conditions'=> array('Atendente.filial_id'=> $unicaFilial['Filial']['id'], 'Atendente.ativo' => 1 )));
			$this->set(compact('atendentes'));
			$this->set('mesas', $this->Paginator->paginate());
			if ($this->request->is('post')) {
				$this->request->data['Mesa']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$this->Mesa->create();
				if ($this->Mesa->save($this->request->data)) {
					$this->Session->setFlash(__('A mesa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect( $this->referer() );
				} else {
					$this->Session->setFlash(__('Houve um erro ao salvar a mesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Mesa->exists($id)) {
			throw new NotFoundException(__('Invalid mesa'));
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
			if ($this->Mesa->save($this->request->data)) {
				$this->Session->setFlash(__('A  mesa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a  mesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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

			$options = array('recursive' => -1, 'conditions' => array('Mesa.' . $this->Mesa->primaryKey => $id));
			$this->request->data = $this->Mesa->find('first', $options);
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
			if (!$this->Mesa->exists($id)) {
				throw new NotFoundException(__('Invalid mesa'));
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
				if ($this->Mesa->save($this->request->data)) {
					$this->Session->setFlash(__('A  mesa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect( $this->referer() );
				} else {
					$this->Session->setFlash(__('Houve um erro ao salvar a  mesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				}
			} else {
				$options = array('recursive' => -1, 'conditions' => array('Mesa.' . $this->Mesa->primaryKey => $id));
				$pedidos = $this->Mesa->find('first', $options);

				$this->loadModel('Pedido');
				$this->loadModel('Produto');
				$this->loadModel('Pgtopedido');
				$this->loadModel('Pagamento');
				$this->loadModel('Cliente');

				$pedidos['Pedidos'] = $this->Pedido->find('all',array('conditions'=> array('AND'=> array(array('Pedido.mesa_id'=> $id),array('Pedido.status_finalizado' => false)))));
				
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
				$pedidos['Pgtopedido'] = $this->Pgtopedido->find('all',array('conditions'=> array('AND'=> array(array('Pgtopedido.mesa_id'=> $id),array('Pgtopedido.status !=' => 'D'),array('Pgtopedido.status !=' => 'F'),array('Pgtopedido.status !=' => 'C')))));
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
				$clientes = $this->Cliente->find('all', array('recursive' => -1, 'order' => 'Cliente.nome asc', 'conditions'=> array('AND'=> array(array('Cliente.ativo' => true), array('Cliente.filial_id' => $minhasFiliais)))));

				$minhasMesas = $this->Mesa->find('all', array('recursive' => -1, 'conditions'=> array('AND'=> array(array('Mesa.filial_id' => $minhasFiliais), array('Mesa.ativo' => 1)))));
				$this->loadModel('Pagamento');
				$meusPagamentos  = $this->Pagamento->find('all', array('recursive' => -1, 'conditions'=> array('Pagamento.filial_id' => $minhasFiliais)));
				//debug($meusPagamentos);
				//die();
				$this->set(compact('pedidos','minhasMesas','meusPagamentos', 'clientes'));
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
					$mesa = $this->Mesa->find('first',array('recursive'=> -1,'conditions'=> array(
						'Mesa.id' => $pagamento['Pgtopedido']['mesa_id']
					)));
					$taxaAtual = (double) $mesa['Mesa']['taxa'] - (double)  $pagamento['Pgtopedido']['taxa'];
					$descAtual = $mesa['Mesa']['desconto'];
					$desconto = (double) $descAtual - (double) $pagamento['Pgtopedido']['desconto'];
					$mesaToUpdate = array(
						'id' => $mesa['Mesa']['id'],
						'taxa' => $taxaAtual,
						'desconto' => $desconto
					);
					$this->Mesa->save($mesaToUpdate);
					$itensMesa  = $pagamento['Pgtopedido']['itenspg'];
					$itensMesa = explode(',',$itensMesa);
					$this->loadModel('Itensdepedido');
					foreach ($itensMesa as $item)
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

				if ($this->Mesa->save($dateToUpdate))
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

		public function trocarmesa()
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
	      $mesaNova = $this->Mesa->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Mesa.filial_id'=>$minhasFiliais), array('Mesa.id'=> $this->request->data['mesanova_id'])))));
				$mesaAntiga = $this->Mesa->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Mesa.filial_id'=>$minhasFiliais), array('Mesa.id'=> $this->request->data['mesa_id'])))));

				if(!empty($mesaNova)){
					$pedidos = $this->Pedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Pedido.filial_id'=>$minhasFiliais), array('Pedido.mesa_id'=> $this->request->data['mesa_id']), array('Pedido.status_finalizado'=> 0)))));
					$mesaId='';
					if(!empty($pedidos)){
						foreach ($pedidos as $pedido) {
							$dataToUpdate = array(
								'id' => $pedido['Pedido']['id'],
								'mesa_id' => $this->request->data['mesanova_id']
							);
							$mesaId = $pedido['Pedido']['mesa_id'];
							$this->Pedido->save($dataToUpdate);
						}

						$this->loadModel('Pgtopedido');
						$pagamentos = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array( array('Pgtopedido.mesa_id'=> $mesaId), array('Pgtopedido.status_finalizado'=> 0)))));
						if(!empty($pagamentos)){
							foreach ($pagamentos as $pagamento) {
								$dataToUpdate = array(
									'id' => $pagamento['Pgtopedido']['id'],
									'mesa_id' => $this->request->data['mesanova_id']
								);

								$this->Pgtopedido->save($dataToUpdate);
							}
						}
						$novaTaxa = (double) $mesaNova['Mesa']['taxa'] + (double) $mesaAntiga['Mesa']['taxa'];
						$novoDesconto = (double) $mesaNova['Mesa']['desconto'] + (double) $mesaAntiga['Mesa']['desconto'];
						$updateMesaNova = array(
							'id' => $mesaNova['Mesa']['id'],
							'taxa' => $novaTaxa,
							'desconto' => $novoDesconto
						);
						$this->Mesa->save($updateMesaNova);
						$updateMesaAntiga = array(
							'id' => $this->request->data['mesa_id'],
							'taxa' => NULL,
							'desconto'=> NULL
						);
						$this->Mesa->save($updateMesaAntiga);

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


		public function fecharmesa()
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

				//$this->request->data['mesa_id'] = 3;

				if ($this->request->is(array('post', 'put')))
				{

					$this->loadModel('Pedido');
					$this->loadModel('Venda');
					$mesa = $this->Mesa->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Mesa.filial_id'=>$minhasFiliais), array('Mesa.id'=> $this->request->data['mesa_id'])))));

					$pedidos = $this->Pedido->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Pedido.filial_id'=>$minhasFiliais), array('Pedido.mesa_id'=> $this->request->data['mesa_id']), array('Pedido.status_finalizado'=> 0)))));


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
					$meuCliente= '';
					foreach ($pedidos as $pedido_validacao)
					{

						$itens_validacao = $this->Itensdepedido->find('all', array('recursive'=> -1, 'conditions'=> array('Itensdepedido.pedido_id'=> $pedido_validacao['Pedido']['id'], 'Itensdepedido.status_cancelado is null' )));

						

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


					$pagamentosValid = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('Pgtopedido.mesa_id'=> $mesa['Mesa']['id'], 'Pgtopedido.status'=> 'A' )));

					$valorCheckPagamento =0;
					
					foreach ($pagamentosValid as $pgtoValid)
					{

						if($pgtoValid['Pgtopedido']['status'] =='A' && $pgtoValid['Pgtopedido']['status_finalizado']== false)
						{
								$valorCheckPagamento = $valorCheckPagamento + $pgtoValid['Pgtopedido']['valor'];
						}

					}

					$taxa = $mesa['Mesa']['taxa'];
					$descont= $mesa['Mesa']['desconto'];
					$valorToCheck = (((double) $valorTotalItens + (double) $taxa ) - (double) $descont )  ;

				//	$resultToCheck = (double) $valorToCheck - (double) $valorCheckPagamento;
				$checkPagamento = false;
				$valorCheckPagamento = number_format($valorCheckPagamento,2, ".",",") ;
				$valorToCheck = number_format($valorToCheck,2 ,".",",");
				if((float) $valorCheckPagamento >= (float) $valorToCheck)
				{
					$checkPagamento = true;
					
				}else{
					
					$checkPagamento = false;
				}

				if( (float) $valorCheckPagamento==0 && (float) $valorToCheck==0){
					$checkPagamento = false;
				}


				if($checkPagamento == true && $isValidItens==false ){
					foreach ($pedidos as $pedido_validacao)
					{

						$itens_validacao = $this->Itensdepedido->find('all', array('recursive'=> -1, 'conditions'=> array('Itensdepedido.pedido_id'=> $pedido_validacao['Pedido']['id'], 'Itensdepedido.status_cancelado is null' )));
						foreach ($itens_validacao as $iten_validacao)
						{
							$itemPedidoUpdate = array('id' => $iten_validacao['Itensdepedido']['id'], 'status_pago' => 1, );	
							$this->Itensdepedido->save($itemPedidoUpdate);
						}
						
					}

					$pagamentosValid = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('Pgtopedido.mesa_id'=> $mesa['Mesa']['id'], 'Pgtopedido.status'=> 'A' )));

					$valorCheckPagamento =0;
					
					foreach ($pagamentosValid as $pgtoValid)
					{

						if($pgtoValid['Pgtopedido']['status'] =='A' && $pgtoValid['Pgtopedido']['status_finalizado']== false)
						{
							
							if($pgtoValid['Pgtopedido']['cliente_id'] != ''){
								$meuCliente=  $pgtoValid['Pgtopedido']['cliente_id'];
							}
							$this->Pgtopedido->save(
								array(
									'id'=> $pgtoValid['Pgtopedido']['id'],
									'status'=> 'C',
								)
							);

						}

					}
					$isValidItens=true;
				}
				
				//Fim Validacao
				
				
					if($checkPagamento == true &&  $isValidItens==true)
					{
						$this->loadModel('Movimento');
					 $movimento = $this->Movimento->find('first', array(
							'recursive'=> -1,
							'conditions'=> array(
								'Movimento.filial_id' => $mesa['Mesa']['filial_id'],
								'status' => 'Aberto'
							)
						));
						if(empty($movimento)){
							$movimento = $this->Movimento->find('first', array(
	 							'recursive'=> -1,
	 							'conditions'=> array(
	 								'Movimento.filial_id' => $mesa['Mesa']['filial_id'],
	 								'status' => 'Fechado',
									'data'=> date('Y-m-d')
	 							)
	 						));
							$numero = count($movimento) + 1;

							$movimentoToSave = array(
									'numero' =>  $numero,
									'data' => date('Y-m-d'),
									'filial_id' => $mesa['Mesa']['filial_id'],
									'empresa_id' => $mesa['Mesa']['empresa_id'],
									'data' => date('Y-m-d'),
									'status'=> 'Aberto'
							);
							$this->Movimento->create();
							$this->Movimento->save($movimentoToSave);

							$movimento = $this->Movimento->find('first', array('order' => array('Movimento.id' => 'desc'),'recursive'=> -1,'conditions' => array('Movimento.filial_id' => $mesa['Mesa']['filial_id'],'Movimento.status'=>'Aberto')));
						}

						$vendaToSave = array(
							'desconto' => $mesa['Mesa']['desconto'],
							'taxa' => $mesa['Mesa']['taxa'],
							'user_id' => $userid,
							'data'=> date('Y-m-d'),
							'hora_atendimento' => date('H:i:s'),
							'filial_id' => $mesa['Mesa']['filial_id'],
							'empresa_id' => $mesa['Mesa']['empresa_id'],
							'status' => 'Finalizado',
							'valor'=> $valorCheckPagamento,
							'atendente_id' => $mesa['Mesa']['atendente_id'],
							'movimento_id'=> $movimento['Movimento']['id']
						);
						$this->Venda->create();
						try {
							$this->Venda->save($vendaToSave);	
						} catch (Exception $e) {
							print_r($e);
							die;
						}
						

						$venda = $this->Venda->find('first', array('recursive'=> -1 , 'order' => array('Venda.id DESC'),'conditions'=> array('Venda.filial_id'=>$minhasFiliais)));
						$itensVenda = array();
						$totalItens =0;

						foreach ($pedidos as $pedido)
						{

							$itens = $this->Itensdepedido->find('all', array('recursive'=> -1, 'conditions'=> array('Itensdepedido.pedido_id'=> $pedido['Pedido']['id'], 'Itensdepedido.status_cancelado is null' )));
							
				
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
							$pagamentos = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=> array('Pgtopedido.mesa_id'=> $mesa['Mesa']['id'], 'Pgtopedido.status'=> 'A' )));
							
							foreach ($pagamentos as $pgto)
							{
								$pgtoToSave=array(
									'venda_id' => $venda['Venda']['id'],
									'pagamento_id' => $pgto['Pgtopedido']['pagamento_id'],
									 'obs' => $pgto['Pgtopedido']['obs'],
										'valor' => $pgto['Pgtopedido']['valor'],
										'taxa'=>$pgto['Pgtopedido']['taxa'],
										'status' => 'Ativo'
								);
								
								$this->Vendaspagamento->create();
								$this->Vendaspagamento->save($pgtoToSave);
							}
							$pagamentosToUpdate = $this->Pgtopedido->find('all', array('recursive'=> -1, 'conditions'=>array(
								'Pgtopedido.mesa_id' => $mesa['Mesa']['id'], 'Pgtopedido.status_finalizado is null' 
							)));
							
							foreach ($pagamentosToUpdate as $pgtosToUp)
							{
								try {
									$this->Pgtopedido->create();
									$this->Pgtopedido->save(array('id' => (int) $pgtosToUp['Pgtopedido']['id'],
									'status_finalizado' => 1,
									'status' => 'F'
									));	

									
								} catch (Exception $e) {
									print_r($e);
									die;
								}
								
							}
							$pedidoOldToUpdate = array(
								'id' => $pedido['Pedido']['id'],
								'status_finalizado' => 1,
								'status' => 'Finalizado',
								'status_pagamento'=>'OK'
							);

							if($pedido['Pedido']['cliente_id']==0){
								
								if($meuCliente != ''){
									$pedidoOldToUpdate['cliente_id']= $meuCliente ;
								}
								
							}
							
							$this->Pedido->save($pedidoOldToUpdate);
							$this->Mesa->save(
								array('id'=> $mesa['Mesa']['id'], 'taxa'=> NULL, 'desconto'=> NULL, )
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
		$this->Mesa->id = $id;
		if (!$this->Mesa->exists()) {
			throw new NotFoundException(__('Invalid mesa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mesa->delete()) {
			$this->Session->setFlash(__('A mesa foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a mesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		$this->Mesa->id = $id;
		if (!$this->Mesa->exists()) {
			throw new NotFoundException(__('Invalid mesa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Mesa->saveField('ativo', 0)) {
			$this->Session->setFlash(__('A mesa foi desativada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao desativar a Mesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}	
}
