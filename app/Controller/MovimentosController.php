<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * movimentos Controller
 *
 * @property Movimento $Movimento
 * @property PaginatorComponent $Paginator
 */
class MovimentosController extends AppController {

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
	/*public function index() {
		$this->Movimento->recursive = 0;
		$this->set('movimentos', $this->Paginator->paginate());
	}*/

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout='liso';
		if (!$this->Movimento->exists($id)) {
			throw new NotFoundException(__('Invalid Movimento'));
		}
		$this->loadModel('Pedido');
		$Movimento = $this->Movimento->find('first', array('recursive' => -1, 'conditions' => array('Movimento.id' => $id)));
		$pedidos = $this->Pedido->find('all', array('conditions' => array('AND' => array( array('Pedido.movimento_id' => $id), array('Pedido.status' => 'Em Trânsito')))));
		$this->set(compact('Movimento', 'pedidos'));
	}

/**
 * add method
 *
 * @return void
 */
/**/	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
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
	                'Movimento.nome' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'cpf' => array(
	                'Movimento.cpf' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'codigo' => array(
	                'Movimento.id' => array(
	                    'operator' => '=',
	                )
	            ),
	            'minhaslojas' => array(
	                'Movimento.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Movimento.empresa_id' => array(
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
				'Movimento' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Movimento.data asc'
				)
			);
	$this->Movimento->recursive = 0;
	$this->set('movimentos', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->request->data['Movimento']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Movimento->create();

			if($this->request->data['Movimento']['foto']['name']==''){
				unset($this->request->data['Movimento']['foto']);
			}

			if(isset($this->request->data['Movimento']['foto']['error']) && $this->request->data['Movimento']['foto']['error'] === 0) {
				$tipo = $this->request->data['Movimento']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Movimento']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Movimento']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
				$this->request->data['Movimento']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				$this->Movimento->create(); // We have a new entry
				$this->Movimento->save($this->request->data); // Save the request
				$this->Session->setFlash(__('O Movimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
            } else {

	               // Save the request
	               $this->Movimento->create(); // We have a new entry
	               unset($this->request->data['Movimento']['foto']);
	               if($this->Movimento->save($this->request->data)){
	               		$this->Session->setFlash(__('O Movimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	               		return $this->redirect($this->request->here);
	               }else{
	               		 $this->Session->setFlash(__('Erro ao salvar o Movimento . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
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
		if (!$this->Movimento->exists($id)) {
			throw new NotFoundException(__('Invalid Movimento'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
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
			if($this->request->data['Movimento']['foto']['name']==''){
				unset($this->request->data['Movimento']['foto']);
			}
			if(isset($this->request->data['Movimento']['foto']['error']) && $this->request->data['Movimento']['foto']['error'] === 0) {

	                		$tipo = $this->request->data['Movimento']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$source = $this->request->data['Movimento']['foto']['tmp_name']; // Source
				$dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				$nomedoArquivo = date('YmdHis').rand(1000,999999);
				$nomedoArquivo= $nomedoArquivo.$this->request->data['Movimento']['foto']['name'];
				$nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)

				$this->request->data['Movimento']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				$this->Movimento->create(); // We have a new entry
				$this->Movimento->save($this->request->data); // Save the request
				$this->Session->setFlash(__('O Movimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {

			       // Save the request
			       $this->Movimento->create(); // We have a new entry

			       if($this->Movimento->save($this->request->data)){
			       		$this->Session->setFlash(__('O Movimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
			       		return $this->redirect( $this->referer() );
			       }else{
			       		 $this->Session->setFlash(__('Erro ao salvar o Movimento . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			       }


			}
		} else {
			$options = array('recursive'=> 0,'conditions' => array('Movimento.' . $this->Movimento->primaryKey => $id));
			$this->loadModel('Venda');

			$this->request->data = $this->Movimento->find('first', $options);

			$vendas = $this->Venda->find('all', array('recursive'=> 1, 'conditions'=> array('Venda.movimento_id'=> $this->request->data['Movimento']['id'] )));

			$totalItens =0;
			$totalPagamento =0;
			$totalTaxa =0;
			$totalDesconto=0;
			$vendaValor=0;
			$meusPagamentos = array();
			$meusAtendentes = array();
			$meusEntregadores = array();
			$this->loadModel('Pagamento');
			$this->loadModel('Atendente');
			$this->loadModel('Entregador');
			//debug($vendas);
			//die();
			foreach ($vendas as $venda)
			{
				if($venda['Venda']['status']=='Finalizado')
				{
					$vendaValor += $venda['Venda']['valor'];
					$totalDesconto += $venda['Venda']['desconto'];
					$totalTaxa += $venda['Venda']['taxa'];
					if(isset($meusAtendentes[$venda['Venda']['atendente_id']]))
					{
						if($venda['Venda']['atendente_id'] != ''){
								 $meusAtendentes[[$venda['Venda']['atendente_id']]['taxa']] = $meusAtendentes[[$venda['Venda']['atendente_id']]['taxa']] + $venda['Venda']['taxa'];
						}
					}else
					{
						if($venda['Venda']['atendente_id'] != '')
						{
								$atendente = $this->Atendente->find('first', array('conditions'=> -1, array('conditions'=> array(
									'Atendente.id' => $venda['Venda']['atendente_id']
								))));
								$meusAtendentes[$venda['Venda']['atendente_id']]['taxa'] = $venda['Venda']['taxa'];

								 if(!empty($atendente))
								 {
									 		$meusAtendentes[$venda['Venda']['atendente_id']]['nome_atendente'] = $atendente['Atendente']['nome'];
				 				 }
						}

					}



					if(isset($meusEntregadores[$venda['Venda']['entregador_id']]))
					{
						if($venda['Venda']['entregador_id'] != ''){
								 $meusEntregadores[[$venda['Venda']['entregador_id']]['	valor_entrega']] = $meusEntregadores[[$venda['Venda']['entregador_id']]['	valor_entrega']] + $venda['Venda']['	valor_entrega'];
						}
					}else
					{
						if($venda['Venda']['entregador_id'] != '')
						{
								$entregador = $this->Entregador->find('first', array('conditions'=> -1, array('conditions'=> array(
									'Entregador.id' => $venda['Venda']['entregador_id']
								))));

								 $meusEntregadores[[$venda['Venda']['entregador_id']]['valor_entrega']] = $meusEntregadores[[$venda['Venda']['entregador_id']]['valor_entrega']] + $venda['Venda']['valor_entrega'];

								 if(!empty($entregador))
								 {
									 	$meusEntregadores[[$venda['Venda']['entregador_id']]['nome_entregador']] = $entregador['Entregador']['nome'];
				 				 }
						}

					}

				}
				foreach ($venda['Vendasiten'] as $item)
				{
					if($item['status']=='Ativo')
					{
						$totalItens += $item['valor_total'];
					}
				}
				foreach ($venda['Vendaspagamento'] as $pagamento )
				{
					if($pagamento['status']=='Ativo')
					{
						$totalPagamento	+= $pagamento['valor'];
						if(isset($meusPagamentos[$pagamento['pagamento_id']]))
						{
							 $meusPagamentos[$pagamento['pagamento_id']]['valor']  += $pagamento['valor'];
						}else{
							 $meusPagamentos[$pagamento['pagamento_id']]['valor']= $pagamento['valor'];
							 $nomePagamento = $this->Pagamento->find('first', array(
								 'recursive'=>-1,
								 'conditions' => array(
									'Pagamento.id' => $pagamento['pagamento_id']
								 ) ));
								if(!empty($nomePagamento))
								{
									$meusPagamentos[$pagamento['pagamento_id']]['nome'] =	$nomePagamento['Pagamento']['tipo'];
								}else
								{
									$meusPagamentos[$pagamento['pagamento_id']]['nome'] ='N/A';
								}
						}
					//	debug($meusPagamentos);
					}

				}
			}

			$this->set(compact('meusEntregadores','meusAtendentes','meusPagamentos','totalPagamento','totalItens','vendaValor','totalDesconto','totalTaxa'));
		}
	}
	public function fechar($id='')
	{
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}

		if($id !='')
		{
				if($this->request->is(array('post', 'put'))) {
					if($this->Movimento->save(array('id'=> $id,'status'=>'Fechado')))
					{
						$this->Session->setFlash(__('O Movimento foi fechado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					}else{
						$this->Session->setFlash(__('Erro ao fechar o Movimento . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
					}
					return $this->redirect( $this->referer() );
				}
		}
	}
}
