<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Roteiros');
App::import('Controller', 'Empresas');
/**
 * Pedidos Controller
 *
 * @property Pedido $Pedido
 * @property PaginatorComponent $Paginator
 */
class PedidosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','checkbfunc','RequestHandler');

/**
 * index method
 *
 * @return void
 */

/**
 * Atender method
 *
 * @return void
 */
	public function atender() {
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Produto');
		$pedidos2=$this->Pedido->find('all');
		$i=0;
		$pedidos= array();
		
		foreach($pedidos2 as $pedido){
			$j=0;
			foreach($pedido['Itensdepedido'] as $j => $iten){
				$produto= $this->Produto->find('first', array('recursive' => -1,'conditions' => array('Produto.id' => $iten['produto_id'])));
				
				$pedido['Itensdepedido'][$j]['produto']= $produto['Produto']['nome'];	
				
				$j++;
			}
			array_push($pedidos, $pedido);
			
			$i++;
		}
		
		
		
		$this->set(compact('pedidos'));
	}
	public function mapa() {
		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
		header('Content-Type: text/html; charset=utf-8');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'mapas';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if(!isset($_GET['mode'])){
			$mode="Pedido";
			$dtini=date("Y-m-d", strtotime( '-1 days' ) );
			$dtfim=date('Y-m-d');
			$this->loadModel('Entregador');
			$entregadores=$this->Entregador->find('all', array('recursive'=> -1));
			$this->set(compact('mode','dtini', 'dtfim','entregadores'));
			
		}	
		
		
	}
	public function mapajson() {
		date_default_timezone_set("Brazil/East");
		header('Content-Type: text/html; charset=utf-8');
		$this->loadModel('Entregador');
		$this->loadModel('Roteiro');
		$this->loadModel('Empresa');
		$this->layout ='ajaxresultadostatus';
		
		$mod =$_GET['md'];
		$modEntrega = $_GET['stausentrega'];
		$entregador= $_GET['entregador'];
		if($mod=='Pedidos'){
			
			if($modEntrega=='aentregar'){
				if($entregador=='Todos'){
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'Pedido.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Pedido.status NOT LIKE' => '%Finalizado%'
									)
								),
							)
						)
					);
				}else if($entregador=='Nenhum'){
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'Pedido.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Pedido.status NOT LIKE' => '%Finalizado%'
									),
									array(
										'OR' => array(
											array(
												'Pedido.entregador_id' => NULL
												),
											array(
												'Pedido.entregador_id' => 0
											),
										),
									),
								),
							)
						)
					);
				}else{
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'Pedido.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Pedido.status NOT LIKE' => '%Finalizado%'
									),
									array(
										'Pedido.entregador_id' => $entregador
									),
								),
							)
						)
					);
				}
				
			}else{
				if($entregador=='Todos'){
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'OR' => array(
											array(
											'Pedido.status LIKE' => '%Entregue%'
											), 
											array(
												'Pedido.status LIKE' => '%Finalizado%'
											),
										),
									),
								),
							)
						)
					);
				}else if($entregador=='Nenhum'){
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'OR' => array(
											array(
												'Pedido.entregador_id' => NULL
												),
											array(
												'Pedido.entregador_id' => 0
											),
										),
									),
									array(
										'OR' => array(
											array(
											'Pedido.status LIKE' => '%Entregue%'
											), 
											array(
												'Pedido.status LIKE' => '%Finalizado%'
											),

										),
									),
								),
							)
						)
					);
				}else{
					$resultados=$this->Pedido->find('all', 
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Pedido.data >=' => $_GET['dtini'], 'Pedido.data <=' => $_GET['dtfim']
										),
									array(
										'Pedido.status NOT LIKE' => '%Cancelado%'
									), 
									array(
										'Pedido.entregador_id' => $entregador
									),
									array(
										'OR' => array(
											array(
											'Pedido.status LIKE' => '%Entregue%'
											), 
											array(
												'Pedido.status LIKE' => '%Finalizado%'
											),

										),
									),
								),
							)
						)
					);
				}
				
			}
			
		}else if($mod=='Entregadores'){
			$resultados=$this->Entregador->find('all', array('conditions' => array('Entregador.ativo' => 1)));
		}else if($mod=='Roteiro'){
			//$resultados =$this->Roteiro->find('all', array('conditions' => array('Roteiro.status' =>'Entregar'), 'order' => array('Roteiro.ordem' => 'asc')));
			
			if($modEntrega=='aentregar'){
				$statusEntrega='Entregar';
			}else{
				$statusEntrega='Entregue';
			}

			if($entregador=='Todos'){
					$resultados=$this->Roteiro->find('all', 
						array(
							'order' => array('Roteiro.ordem' => 'asc'),
							'conditions' => array(
								'AND' => array(
									array(
										'Roteiro.data >=' => $_GET['dtini'], 'Roteiro.data <=' => $_GET['dtfim']
										),
									array(
										'Roteiro.status LIKE' => '%'.$statusEntrega.'%'
									), 
									
								),
							)
						)
					);
				}else if($entregador=='Nenhum'){

					$resultados=$this->Roteiro->find('all', 
						array(
							'order' => array('Roteiro.ordem' => 'asc'),
							'conditions' => array(
								'AND' => array(
									array(
										'Roteiro.data >=' => $_GET['dtini'], 'Roteiro.data <=' => $_GET['dtfim']
										),
									array(
										'Roteiro.status NOT LIKE' => '%Cancelado%',
									), 
									array(
										'OR' => array(
											array(
												'Pedido.entregador_id' => NULL
												),
											array(
												'Pedido.entregador_id' => 0
											),
										),
									),
									array(
										'Roteiro.status LIKE' => '%'.$statusEntrega.'%',
									),
								),
							)
						)
					);

				}else{

					$resultados=$this->Roteiro->find('all', 
						array(
							'order' => array('Roteiro.ordem' => 'asc'),
							'conditions' => array(
								'AND' => array(
									array(
										'Roteiro.data >=' => $_GET['dtini'], 'Roteiro.data <=' => $_GET['dtfim']
										),
									array(
										'Roteiro.status NOT LIKE' => '%Cancelado%',
									), 
									array(
										'Roteiro.entregador_id' => $entregador,
									),
									array(
										'Roteiro.status LIKE' => '%'.$statusEntrega.'%',
									),
								),
							)
						)
					);
				}

			
			if($resultados !='' && !empty($resultados)){
				
				$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id'=> 'asc'), 'recursive' => -1));
				$enderecoEmpresa = $empresa['Empresa']['logradouro'].' '.$empresa['Empresa']['numero'];
				if($empresa['Empresa']['complemento'] != null && $empresa['Empresa']['complemento'] != ''){
					$enderecoEmpresa = $enderecoEmpresa. " ".$empresa['Empresa']['complemento']." ".$empresa['Empresa']['cidade'] . " " .$empresa['Empresa']['uf']; 
				}else{
					$enderecoEmpresa = $enderecoEmpresa." ".$empresa['Empresa']['cidade'] . " " .$empresa['Empresa']['uf']; 
				}  



				$i=0;
				foreach ($resultados as $resultado) {
					$resultados[$i]['Roteiro']['enderecoempresa']= $enderecoEmpresa;
					if($i==0){
						$enderecoPrimeiroCliente = $resultado['Cliente']['logradouro'].' '.$resultado['Cliente']['numero'];
						if($resultado['Cliente']['complemento'] != null && $resultado['Cliente']['complemento'] != ''){
							$enderecoPrimeiroCliente = $enderecoPrimeiroCliente. " ".$resultado['Cliente']['complemento']." ".$resultado['Cliente']['bairro'] ." ".$resultado['Cliente']['cidade'] . " " .$resultado['Cliente']['uf']; 
						}else{
							$enderecoPrimeiroCliente = $enderecoPrimeiroCliente." ".$resultado['Cliente']['bairro'] ." " .$resultado['Cliente']['cidade'] . " " .$resultado['Cliente']['uf']; 
						} 

					}
					$enderecoUltimoCliente = $resultado['Cliente']['logradouro'].' '.$resultado['Cliente']['numero'];
					if($resultado['Cliente']['complemento'] != null && $resultado['Cliente']['complemento'] != ''){
						$enderecoUltimoCliente = $enderecoUltimoCliente. " ".$resultado['Cliente']['complemento']." ".$resultado['Cliente']['bairro'] ." ".$resultado['Cliente']['cidade'] . " " .$resultado['Cliente']['uf']; 
					}else{
						$enderecoUltimoCliente = $enderecoUltimoCliente." ".$resultado['Cliente']['bairro'] ." " .$resultado['Cliente']['cidade'] . " " .$resultado['Cliente']['uf']; 
					}  
					$resultados[$i]['Roteiro']['enderecofinal']= $enderecoUltimoCliente;
					$resultados[$i]['Roteiro']['enderecoinicio']=$enderecoPrimeiroCliente;
					$i++;
				}
				//unset($resultados[$i]);
				//unset($resultados[0]);
				//$resultadosAux = array_slice($resultados, -1);
				//$resultados1= $resultadosAux;
				//$resultadosAux2 = array_slice($resultados1, 1);
				//$resultados= $resultadosAux2;
			}
			
			//$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id'=> 'asc'), 'recursive' => -1));
			//array_push($resultados, $empresa);
		}
		
			
	
		
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
		
	}
	
	public function confirmarpedido($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
		if (!$this->Pedido->exists($id)) {
			throw new NotFoundException(__('Invalid pedido'));
		}
		$resultados = array();
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'confirmar';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			
		}else{
			if ($this->request->is(array('Ajax'))) {
				$pedido=$this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
				if($pedido['Pedido']['status']=='Em Aberto'){
					$this->Pedido->id= $id;
					$this->Pedido->saveField('status', 'Confirmado');
					$this->Pedido->saveField('user_id', $userid);
					
					$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
				}
				
				
			} 
			
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));

		}
		
	}
	
	public function confirmarpreparo($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'preparar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			$resultados = array();
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				
			}else{
				
				if ($this->request->is(array('Ajax'))) {
					$pedido=$this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					if($pedido['Pedido']['status']=='Confirmado'){
						$this->Pedido->id= $id;
						$this->Pedido->saveField('status', 'Pronto');
						$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
						$this->loadModel('Itensdepedido');
						$this->Itensdepedido->updateAll(
							array('Itensdepedido.statuspreparo' => 0),
						    array('Itensdepedido.pedido_id' => $id));
					}
				} 
				
				$this->set(array(
					'resultados' => $resultados,
					'_serialize' => array('resultados')
				));	
			}
			
	}
	
	public function confirmarseparacao($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}
			$resultados = array();
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'separar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				
			}else{
				if ($this->request->is(array('Ajax'))) {
					$pedido=$this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					if($pedido['Pedido']['status']=='Pronto'){
						$this->Pedido->id= $id;
						$this->Pedido->saveField('status', 'Separado p/ Entrega');
						$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					}
				} 
				
				$this->set(array(
					'resultados' => $resultados,
					'_serialize' => array('resultados')
				));
			}
	}
	
	public function confirmarenvio($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}
			$resultados = array();
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'enviar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				
			}else{
				if ($this->request->is(array('Ajax'))) {
					$pedido=$this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					if($pedido['Pedido']['status']=='Separado p/ Entrega'){
						$this->Pedido->id= $id;
						$this->Pedido->saveField('status', 'Em Trânsito');
						$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					}
				} 
				
				$this->set(array(
					'resultados' => $resultados,
					'_serialize' => array('resultados')
				));
			}
	}
	
	public function confirmarentrega($id = null) {
		date_default_timezone_set("Brazil/East");
		header('Content-Type: text/html; charset=utf-8');
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'entregar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			$resultados = array();
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				
			}else{
			if ($this->request->is(array('Ajax'))) {
					$pedido=$this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					
					if($pedido['Pedido']['status']=='Em Trânsito'){
					
						if($pedido['Pedido']['entregador_id'] !='' && $pedido['Pedido']['entregador_id'] !=0){
							$this->Pedido->id= $id;
							$this->Pedido->saveField('status', 'Entregue');
							$this->Pedido->saveField('statuspreparo', 0);
							$this->Pedido->saveField('posicao_fila', 0);
							$this->reordenafila();
							$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
							$this->loadModel('Roteiro');
							$this->Roteiro->updateAll(
							array('Roteiro.status' => 'Entregue'),
						    array('Roteiro.pedido_id' => $id));
							
							
						}
					}
			} 
			$this->set(array(
							'resultados' => $resultados,
							'_serialize' => array('resultados')
						));
				
			}
	}
	
	/*public function confirmarentregador($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'confirmar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}else{
			if ($this->request->is(array('Ajax'))) {

				$this->Pedido->id= $id;
				$this->Pedido->saveField('entregador_id', $this->request->data['Pedido']['entregador_id']);
				$resultados= $this->Pedido->find('first', array('recursive' => 0,'conditions' => array('Pedido.id' => $id)));
			
			} 
			
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		}
	}*/
	
	public function cancelarpedido($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
			if (!$this->Pedido->exists($id)) {
				throw new NotFoundException(__('Invalid pedido'));
			}
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'cancelar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				
			}else{
				if ($this->request->is(array('Ajax'))) {
					
					$this->Pedido->id= $id;
					$this->Pedido->saveField('status', 'Cancelado');
					$this->Pedido->saveField('motivocancela', $this->request->data['Pedido']['motivocancela']);
					$resultados= $this->Pedido->find('first', array('recursive' => -1,'conditions' => array('Pedido.id' => $id)));
					$this->loadModel('Itensdepedido');
					$this->Itensdepedido->updateAll(
						array('Itensdepedido.statuspreparo' => 0),
					    array('Itensdepedido.pedido_id' => $id)
					);
					$this->reordenafila();
				
				} 
				
				$this->set(array(
					'resultados' => $resultados,
					'_serialize' => array('resultados')
				));
			}
	}
	
	public function index() {
		
		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}else{
			$this->loadModel('Autorizacao');
			$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao))); 
			$this->set(compact('autorizacao'));

		}
		//converte a data
			if(isset($this->request->data['filter']))
			{

				foreach($this->request->data['filter'] as $key=>$value)
				{
					$data = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataPedido'])));
					$data= str_replace(" ","",$data);
					$this->request->data['filter']['dataPedido'] = $data;
					
					$data2 = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataPedido-between'])));
					$data2= str_replace(" ","",$data2);
					$this->request->data['filter']['dataPedido-between'] = $data2;
					
				}
			}	
		$this->Filter->addFilters(
	        array(
	            'codigo' => array(
	                'Atendimento.codigo' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
				 'nome' => array(
	                'Cliente.nome' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
				'status' => array(
	                'Pedido.status' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    ),
						 'select' => array(''=>'', 'Cancelado'=> 'Cancelado', 'Confirmado'=> 'Confirmado', 'Em Aberto'=> 'Em Aberto', 'Em Trânsito'=> 'Em Trânsito','Entregue'=> 'Entregue','Pronto'=> 'Pronto', 'Separado p/ Entrega'=>'Separado p/ Entrega'),
	                )
	            ),
				'statusnot' => array(
	                'Pedido.status' => array(
	                    'operator' => 'NOT LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    ),
	                     'select' => array(''=>'', 'Cancelado'=> 'Cancelado', 'Confirmado'=> 'Confirmado', 'Em Aberto'=> 'Em Aberto', 'Em Trânsito'=> 'Em Trânsito','Entregue'=> 'Entregue','Pronto'=> 'Pronto', 'Separado p/ Entrega'=>'Separado p/ Entrega'),
	                )
	            ),
				'dataPedido' => array(
		            'Pedido.data' => array(
		                'operator' => 'BETWEEN',
		                'between' => array(
		                    'text' => __(' e ', true)
		                )
		            )
		        ),
	        )
	    );
		$conditiosAux= $this->Filter->getConditions();
				
		if(empty($conditiosAux)){
		
			
			
			$dataIncio = date('Y-m-d',strtotime("-1 days"));
			$dataTermino= date('Y-m-d');
			$this->request->data['filter']['dataPedido']=$dataIncio;
			$this->request->data['filter']['dataPedido-between']=$dataTermino;
			
			
		}
		$this->Paginator->settings = array(
				'Pedido' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Pedido.id asc'
				)
			);	
	
	    // Define conditions
	    //$this->Filter->setPaginate('conditions', $this->Filter->getConditions());
	
	   	$this->Pedido->find('all', array('conditions'=> $this->Filter->getConditions(), 'recursive' => 0));
		$pedidos = $this->Paginator->paginate('Pedido');
		
		$contAberto=0;
		$contEntregue=0;
		foreach($pedidos as $pedido){
			if($pedido['Pedido']['status'] != 'Cancelado'){
				if($pedido['Pedido']['status'] != 'Entregue'){
					$contAberto= $contAberto +1;
				}
			}
			
			if($pedido['Pedido']['status'] == 'Entregue'){
				$contEntregue= $contEntregue +1;
			}
		}
		
		
		$this->mensagensativas();
	
		//echo $contAberto;
		//echo "<br/>";
		//echo $contEntregue;
	   $this->set(compact('pedidos','contAberto', 'contEntregue'));
	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 
 	public function mensagensativas() {
 		$this->loadModel('Mensagen');
		
		$mensagensAtivas = $this->Mensagen->find('all', array('conditions' => array('Mensagen.read' => 0), 'fields' => array('DISTINCT Mensagen.pedido_id')));
		$mensagenspedidos=array();
		$j=0;
		foreach($mensagensAtivas as $ativa){
			 
				$pedidoInfo= $this->Pedido->find('first', array('order' => array('Pedido.id' => 'desc'),'recursive'=> 0,'conditions' => array('Pedido.id' => $ativa['Mensagen']['pedido_id'])));
				$mensagenspedidos['Pedido'][$j]['pedido_id']=$ativa['Mensagen']['pedido_id'];
				$mensagenspedidos['Pedido'][$j]['codigo']= $pedidoInfo['Atendimento']['codigo'];
				$mensagenspedidos['Pedido'][$j]['username']= $pedidoInfo['Cliente']['username'];
				$idAtivos['Pedido'][$j]['id']=$ativa['Mensagen']['pedido_id'];
				$j++;
			
		}	
		$coutmsg=$j;
		$this->set(compact('mensagenspedidos','coutmsg'));
 	}
	public function view($id = null) {
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		date_default_timezone_set("Brazil/East");
		$this->layout='liso';
		if (!$this->Pedido->exists($id)) {
			throw new NotFoundException(__('Invalid pedido'));
		}

		$this->loadModel('Produto');
		$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.id' => $id)));
		
		$this->loadModel('Entregador');
		$entregadores = $this->Entregador->find('all', array('recursive' => -1));
		
		$i=0;
		foreach($pedido['Itensdepedido'] as $itens ){
			$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
			$pedido['Itensdepedido'][$i]['prodNome']= $produto['Produto']['nome'];
			$i++;	
		}

		

		$contFlag=1;
		$horaAtual = date("H:i:s");  
		$difHora="00:00:00";
		$horaAtendimento = $pedido['Pedido']['hora_atendimento'];

		$tempoTotalFila = $this->calculaFilaProduto($id);

		$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);
		

		

		if($horaAtual > $horaAtendimento){

			

			if($horaAtual < $esperaHora){

				$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
				//$difHora=date('H:i:s', $difHora);
				$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);	
				
				
			}else{
				
				if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){
					

					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
					
				}else{
					
					$pedido['Pedido']['difhora']='00:00:00';
				}

				

			}
		}else{
			
			if($horaAtual < $esperaHora){
				$horaAux1="23:59:59";
				$horazero="00:00:01";
				$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
				$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
				$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

				$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
			}else{
				$pedido['Pedido']['difhora']='00:00:00';
			}	
		}

		if($pedido['Pedido']['statuspreparo']!=1){
			$pedido['Pedido']['difhora']="00:00:00";
		}
		$posicaofila= $this->Pedido->find('count', array('recursive' => -1,'conditions' => array('AND' => array(array('Pedido.statuspreparo' => 1), array('Pedido.id <' => $id)))));
		$this->Pedido->id=$id;
		//$this->Pedido->saveField('posicao_fila', $posicaofila);
		$pedido['Pedido']['posicao_fila']= $posicaofila;
		$this->set(compact('pedido','entregadores'));
		
		
	}

/**
 * add method
 *
 * @return void
 */
 	public function reordenafila() {
 		$filas = $this->Pedido->find('all', array('recursive'=> -1, 'conditions' => array('Pedido.statuspreparo' => 1)));
 		foreach ($filas as $fila) {
 			$id=$fila['Pedido']['id'];
 			$posicaofila= $this->Pedido->find('count', array('recursive' => -1,'conditions' => array('AND' => array(array('Pedido.statuspreparo' => 1), array('Pedido.id <' => $id)))));
			$this->Pedido->id=$id;
			$this->Pedido->saveField('posicao_fila', $posicaofila);
 		}

 	}
	public function add($codigo = null) {
		


		 date_default_timezone_set("Brazil/East");
		 $this->layout='liso';

		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
		
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}else{
			$this->loadModel('Autorizacao');
			$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao))); 
			$this->set(compact('autorizacao'));

		}
		
		if ($this->request->is('post')) {


			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			if(!isset($this->request->data['Itensdepedido'])){
				$this->Session->setFlash(__('Não existem itens no pedido!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			
			$this->Pedido->create();
			
			$this->loadModel('Produto');
			$this->loadModel('Atendimento');
			
			$this->loadModel('Itensdepedido');
			
			
				$this->request->data['Pedido']['data']=date('Y-m-d');
				$this->request->data['Pedido']['status']="Confirmado";
				
				$this->request->data['Pedido']['status_pagamento']="Pendente";

				$userid = $this->Session->read('Auth.User.id');
				$this->request->data['Pedido']['user_id']=$userid;
				
				$this->request->data['Pedido']['origem']="Atendimento";
				$clt = $this->request->data['Pedido']['cliente_id'];
				
				$ultimopedido="";
				
				$this->loadModel('Atendimento');
				$codigo="";
				$this->Atendimento->create();
				$flag ="FALSE";
				$this->loadModel('Empresa');
				$empresa=$this->Empresa->find('first', array('recursive'=> -1, 'conditions' => array('Empresa.id' => 1)));
				while($flag == "FALSE"){
					//$codigo = date('Ymd');
					 //$numero = rand(1,1000000);
					 //$codigo= $codigo.$numero;
					 //$codigo= "ENTR".$codigo;
					 $codigo = $this->geraSenha(8, false, true);
					 $testeCodigo = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));
					
					 if(empty($testeCodigo)){
						$flag="TRUE";
						//fazer uma função para pegar a lat e lng do estabelecimento
						$lat = $empresa['Empresa']['lat'];
						$lng = $empresa['Empresa']['lng'];
						$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo, 'tipo' => 'EXTERNO', 'cliente_id' => $clt, 'lat' => $lat, 'lng' => $lng );
						if ($this->Atendimento->save($dadosatendimento)) {
							$ultimoAtend = $this->Atendimento->find('first', array('order' => array('Atendimento.id' => 'desc'), 'recursive' =>1));
							$codigo=$ultimoAtend['Atendimento']['codigo'];
							$atendimento=$ultimoAtend;
							$this->request->data['Pedido']['atendimento_id']=$ultimoAtend['Atendimento']['id'];

						} else {
							//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
						}
						
					 }else{
						$flag ="FALSE";
					 }
				}		 
						 
				
					
					
					
				
					
				$this->Pedido->create();
			
				$this->request->data['Pedido']['data']=date('Y-m-d');
				$i=0;
				
				$total=0;
				$tempoPreparo="00:00:00";
				

				foreach($this->request->data['Itensdepedido'] as $iten ){
					
					$produto =$this->Produto->find('first', array('conditions' => array('Produto.id' => $iten['produto_id'])));	
					$this->request->data['Itensdepedido'][$i]['valor_unit']= $produto['Produto']['preco_venda'];
					$this->request->data['Itensdepedido'][$i]['valor_total'] = $produto['Produto']['preco_venda'] * $iten['qtde'];
					$this->request->data['Itensdepedido'][$i]['produto_id'] = $iten['produto_id'];
					$this->request->data['Itensdepedido'][$i]['qtde'] = $iten['qtde'];
					$this->request->data['Itensdepedido'][$i]['statuspreparo'] = 1;
					$total += $this->request->data['Itensdepedido'][$i]['valor_total'];

					
					
					for ($i=0; $i <  $iten['qtde']; $i++) { 
						$tempoPreparo = $this->checkbfunc->somaHora($tempoPreparo,$produto['Produto']['tempo_preparo']);
					}
					

					//debug($tempoPreparo);
					
					$i = $i +1;
				}
					
				$this->request->data['Pedido']['valor']=$total;	
					
					
				
				$horaAtendimento =  date("H:i:s");  
				
				$tempoVisualizacao = "00:01:00";
				$tempoFila = $this->calculaFilaProdutos();
				if($tempoFila == false){
					$tempoFila="00:00:00";
				}
				//Garanto o minimo de tempo para preparar o produto mais demorado para ficar pronto
				
				
				//Busco a duração do trajeto do endereço do cliente
				$this->loadModel('Cliente');
				$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions' => array('Cliente.id' => $this->request->data['Pedido']['cliente_id'])));
				
				$duracao = $cliente['Cliente']['duracao'];	
			
				//Trato a string recebida para o formato de horas e minutos
				$horasAux = explode('horas', $cliente['Cliente']['duracao']);

				if(isset($horasAux[1])){
					$horas=str_replace(" ", "", $horasAux[0]);
				}else{
					$horasAux = explode('hora', $cliente['Cliente']['duracao']);
					if(isset($horasAux[1])){
						$horas=str_replace(" ", "", $horasAux[0]);	
					}
				}
				if(isset($horas)){
					$tamanhoString=strlen($horas);
					if($tamanhoString == 1){
						$horas= '0'.$horas;	
					}
				}else{
					$horas='00';
				}

				$minutosAux = explode('minutos', $cliente['Cliente']['duracao']);

				if(isset($minutosAux[1])){
					$minutos=str_replace(" ", "", $minutosAux[0]);
				}

				if(isset($minutos)){
					$tamanhoString=strlen($minutos);
					if($tamanhoString == 1){
						$minutos= '0'.$minutos;	
					}
				}else{
					$minutos='00';
				}

				$segundos='00';



				$tempoEntrega = $horas.":".$minutos.":".$segundos;
				
				$somaTempos1 = $this->checkbfunc->somaHora($tempoPreparo, $tempoFila);

				$somaTempos2 = $this->checkbfunc->somaHora($tempoEntrega,$tempoVisualizacao);

				$tempoEst = $this->checkbfunc->somaHora($somaTempos2,$tempoPreparo);

				$tempoTotalFila= $this->checkbfunc->somaHora($somaTempos1,$somaTempos2);

				
				
				
				//$tempoEstAux = $this->checkbfunc->somaHora($tempoVisualizacao, $tempoEntrega);
				//$tempoEst= $this->checkbfunc->somaHora($tempoEstAux,$tempoPreparo); 
				//$tempoTotalFila = $this->checkbfunc->somaHora($tempoEst,$tempoFila);
				
				$this->request->data['Pedido']['hora_atendimento'] = $horaAtendimento; 
				$this->request->data['Pedido']['statuspreparo'] = 1;
				$this->request->data['Pedido']['tempo_estimado'] =$tempoEst;
				$this->request->data['Pedido']['tempo_fila'] = $tempoTotalFila;
				
				$posicaoFila= $this->Pedido->find('count', array('recursive' => -1,'conditions' => array('Pedido.statuspreparo' => 1)));
				
				if(empty($posicaoFila)){
					$posicaoFila=0;
				}
				$this->request->data['Pedido']['posicao_fila']=$posicaoFila;
				
				if(!empty($pedidoExistente)){
					$j=0;
					foreach($this->request->data['Itensdepedido'] as $itens ){
						
						$this->request->data['Itensdepedido'][$j]['pedido_id'] = $pedidoExistente['Pedido']['id'];
						$j=$j +1;
					}
					
				
					
					$this->request->data['Pedido']['id']= $pedidoExistente['Pedido']['id'];
					$this->Pedido->save($this->request->data['Pedido']);
					if ($this->Itensdepedido->saveAll($this->request->data['Itensdepedido'])){
					
						
						$ultimopedido = $pedidoExistente;

						//Insiro o pedido na rota do  entregador
						$Autorizacao = new AutorizacaosController;
						$Roteiro = new RoteirosController;

				 		$Roteiro->inserirRota($ultimopedido['Pedido']['id']);

						$this->set(compact($ultimopedido));
						if(! $this->request->is('ajax'))
						{
							//$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
							//return $this->redirect( $this->referer() );
						}
						
						if( $this->request->is('ajax'))
						{
							$this->layout ='ajaxaddpedido';
						}
					}else{
						$ultimoPedido = $this->request->data;
						$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
						return $this->redirect( $this->referer() );
					}
				}else{
					if ($this->Pedido->saveAll($this->request->data)){
						
						$this->loadModel('Roteiro');

						//debug($this->request->data);
						
						$ultimopedido = $this->Pedido->find('first', array('order' => array('Pedido.id' => 'desc'), 'recursive' => -1));
						

						//Insiro o pedido na rota do  entregador
						$Autorizacao = new AutorizacaosController;
						$Roteiro = new RoteirosController;

				 		$Roteiro->inserirRota($ultimopedido['Pedido']['id']);

						$this->set(compact($ultimopedido));
						if(! $this->request->is('ajax'))
						{
							$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
							return $this->redirect( $this->referer() );
						}
						
						if( $this->request->is('ajax'))
						{
							$this->layout ='ajaxaddpedido';
						}
					}else{
						$ultimoPedido = $this->request->data;
						$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
					}
				}
				
			
		}
		
		$this->loadModel('Entregador');
		$entregadores = $this->Entregador->find('all', array('recursive'=> -1));
		$this->loadModel('Produto');
		$this->loadModel('Cliente');
		$this->loadModel('Pagamento');
		$produtos = $this->Produto->find('all', array('recursive' => -1, 'order' => 'Produto.nome asc'));
		$clientes = $this->Cliente->find('all', array('recursive' => -1, 'order' => 'Cliente.nome asc'));
		$pagamentos = $this->Pagamento->find('all', array('recursive' => -1, 'order' => 'Pagamento.tipo asc'));
		$this->set(compact('pagamentos','ultimopedido','codigo', 'produtos', 'clientes','entregadores'));
	}

	
	
	public function calculaFilaProdutos(){
		$this->loadModel('Produto');
		$this->loadModel('Itensdepedido');
		$this->Itensdepedido->virtualFields = array('totalprod' => 'SUM(Itensdepedido.qtde)');
		$produtos= $this->Produto->find('all', array('recursive' => -1));
		$i=0;
		$produtoFila= array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdepedido->find('all', array('recursive'=> -1,'conditions' => array('AND' => array(array('Itensdepedido.produto_id'=> $produto['Produto']['id']), array('Itensdepedido.statuspreparo'=> 1))) ));
			
			if(!empty($qteFilaProduto)){
				
				if(isset($qteFilaProduto[0]['Itensdepedido']['totalprod'])){
					
					if($qteFilaProduto[0]['Itensdepedido']['totalprod']== null){
						unset($qteFilaProduto[$i]);
					}else{
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdepedido']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo=$produto['Produto']['tempo_preparo'];
						$qtdePreparo=$produto['Produto']['qtde_preparo'];

						$qteFila = $qteFilaProduto[0]['Itensdepedido']['totalprod'];
						$tempoNescessario = '?';
						$acumuladorTempo='00:00:00';
						for ($i=0; $i < $qteFila; $i++) { 
							$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
						}
						
						$segundosTotais = $this->converteparasegundos($acumuladorTempo);

						$segundosTotais = $segundosTotais / $qtdePreparo;
						$tempoTotal = gmdate("H:i:s", $segundosTotais);	
						
						$tempoTotalPreparo = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoTotal);
						
						
					}

				}
				
			}
			
			$i=$i++;
		}

		if(isset($tempoTotalPreparo)){
			
			return $tempoTotalPreparo;
		}else{
			return false;
		}
		
		
	}

	public function calculaFilaProduto(&$id){
		$this->loadModel('Produto');
		$this->loadModel('Itensdepedido');
		$this->Itensdepedido->virtualFields = array('totalprod' => 'SUM(Itensdepedido.qtde)');
		$produtos= $this->Produto->find('all', array('recursive' => -1));
		$i=0;
		$produtoFila= array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdepedido->find('all', array('recursive'=> -1,'conditions' => array('AND' => array(array('Itensdepedido.produto_id'=> $produto['Produto']['id']), array('Itensdepedido.statuspreparo'=> 1), array('Itensdepedido.pedido_id <='=> $id))) ));
			
			if(!empty($qteFilaProduto)){
				
				if(isset($qteFilaProduto[0]['Itensdepedido']['totalprod'])){
					
					if($qteFilaProduto[0]['Itensdepedido']['totalprod']== null){
						unset($qteFilaProduto[$i]);
					}else{
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdepedido']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo=$produto['Produto']['tempo_preparo'];
						$qtdePreparo= round($produto['Produto']['qtde_preparo']);
						
						$qteFila = round($qteFilaProduto[0]['Itensdepedido']['totalprod']);
						$modQtde = ($qteFila % $qtdePreparo);
						while ( $modQtde != 0) {
							$qteFila++;
							$modQtde = ($qteFila % $qtdePreparo);
						}
						$tempoNescessario = '?';
						$acumuladorTempo='00:00:00';
						for ($i=0; $i < $qteFila; $i++) { 
							$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
						}
						
						$segundosTotais = $this->converteparasegundos($acumuladorTempo);

						$segundosTotais = $segundosTotais / $qtdePreparo;
						$tempoTotal = gmdate("H:i:s", $segundosTotais);	
						
						$tempoTotalPreparo = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoTotal);
						
						
					}

				}
				
			}
			
			$i=$i++;
		}

		if(isset($tempoTotalPreparo)){
				//Busco a duração do trajeto do endereço do cliente
				$this->loadModel('Cliente');
				$this->loadModel('Pedido');
				$pedidoFunc= $this->Pedido->find('first', array('recursive' => -1, 'conditions' => array('Pedido.id' => $id)));
				$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions' => array('Cliente.id' => $pedidoFunc['Pedido']['cliente_id'])));
				
				$duracao = $cliente['Cliente']['duracao'];	
			
				//Trato a string recebida para o formato de horas e minutos
				$horasAux = explode('horas', $cliente['Cliente']['duracao']);

				if(isset($horasAux[1])){
					$horas=str_replace(" ", "", $horasAux[0]);
				}else{
					$horasAux = explode('hora', $cliente['Cliente']['duracao']);
					if(isset($horasAux[1])){
						$horas=str_replace(" ", "", $horasAux[0]);	
					}
				}
				if(isset($horas)){
					$tamanhoString=strlen($horas);
					if($tamanhoString == 1){
						$horas= '0'.$horas;	
					}
				}else{
					$horas='00';
				}

				$minutosAux = explode('minutos', $cliente['Cliente']['duracao']);

				if(isset($minutosAux[1])){
					$minutos=str_replace(" ", "", $minutosAux[0]);
				}

				if(isset($minutos)){
					$tamanhoString=strlen($minutos);
					if($tamanhoString == 1){
						$minutos= '0'.$minutos;	
					}
				}else{
					$minutos='00';
				}

				$segundos='00';



				$tempoEntregaCli = $horas.":".$minutos.":".$segundos;
				
				$meusItens = $this->Itensdepedido->find('all', array('recursive' => -1, 'conditions' => array('Itensdepedido.pedido_id' => $id)));
				
			/*	$maiorTempoPreparo='00:00:00';

				foreach ($meusItens as $meuItem) {
					$meuProduto = $this->Produto->find('first', array('recursive' => -1, 'conditions' => array('Produto.id' => $meuItem['Itensdepedido']['produto_id'])));
					if($maiorTempoPreparo < $meuProduto['Produto']['tempo_preparo']){
						$maiorTempoPreparo = $meuProduto['Produto']['tempo_preparo'];

					}
					
				}
				
				if($maiorTempoPreparo > $tempoTotalPreparo){
					$tempoTotalPreparo= $maiorTempoPreparo;

				}*/
				$tempoVisualizacao="00:01:00";
				$tempoTotalPreparoAux = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoEntregaCli);
				$tempoTotalPreparoAux2 = $this->checkbfunc->somaHora($tempoTotalPreparoAux, $tempoVisualizacao);
				$tempoTotalPreparo = $tempoTotalPreparoAux2;
				
				
				

			return $tempoTotalPreparo;
		}else{
			return false;
		}
		
		
	}

	function converteparasegundos($hora) {
 
		$horas = substr($hora, 0, -6);
		$minutos = substr($hora, -5, 2);
		$segundos = substr($hora, -2);
		 
		return $horas* 3600 + $minutos * 60 + $segundos;
	}

	function converterparahoras($segundos){
 
		$horas = floor($segundos / 3600);
		$segundos -= $horas* 3600;
		$minutos = floor($segundos / 60);
		$segundos -= $minutos * 60;
		 
		return "$horas:$minutos:$segundos";
	 
	}
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
	date_default_timezone_set("Brazil/East");
		if (!$this->Pedido->exists($id)) {
			throw new NotFoundException(__('Invalid pedido'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}else{
			$this->loadModel('Autorizacao');
			$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao))); 
			$this->set(compact('autorizacao'));

		}
		if ($this->request->is(array('post', 'put'))) {

			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			
			if ($this->Pedido->save($this->request->data)) {

				//Insiro o pedido na rota do  entregador
				$Autorizacao = new AutorizacaosController;
				$Roteiro = new RoteirosController;
		 		$Roteiro->inserirRota($id);

				$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id));
			$this->request->data = $this->Pedido->find('first', $options);
			$pedido= $this->request->data;

			$i=0;
			$this->loadModel('Produto');
			foreach($pedido['Itensdepedido'] as $itens ){
				$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
				$pedido['Itensdepedido'][$i]['prodNome']= $produto['Produto']['nome'];
				$i++;	
			}
			
			
			$contFlag=1;
			$horaAtual = date("H:i:s");  
			$difHora="00:00:00";
			$horaAtendimento = $pedido['Pedido']['hora_atendimento'];

			$tempoTotalFila = $this->calculaFilaProduto($id);

			$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);
			

			

			if($horaAtual > $horaAtendimento){

				

				if($horaAtual < $esperaHora){

					$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
					//$difHora=date('H:i:s', $difHora);
					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);	
					
					
				}else{
					
					if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){
						

						$horaAux1="23:59:59";
						$horazero="00:00:01";
						$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
						$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
						$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

						$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
						
					}else{
						
						$pedido['Pedido']['difhora']='00:00:00';
					}

					

				}
			}else{
				
				if($horaAtual < $esperaHora){
					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
				}else{
					$pedido['Pedido']['difhora']='00:00:00';
				}	
			}

			if($pedido['Pedido']['statuspreparo']!=1){
				$pedido['Pedido']['difhora']="00:00:00";
			}
			$this->set(compact('pedido'));

		}
		$this->loadModel('Pagamento');
		$pagamentos = $this->Pagamento->find('all', array('recursive'=> -1));
		$this->loadModel('Entregador');
		$entregadores = $this->Entregador->find('all', array('recursive'=> -1));
		$this->loadModel('User');
		$users = $this->User->find('list');
		$this->set(compact('pagamentos', 'users','entregadores'));
	}



	public function editmapa($id = null) {
		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
	date_default_timezone_set("Brazil/East");
		if (!$this->Pedido->exists($id)) {
			throw new NotFoundException(__('Invalid pedido'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}else{
			$this->loadModel('Autorizacao');
			$autorizacao= $this->Autorizacao->find('first', array('recursive'=> -1, 'conditions'=> array('Autorizacao.funcao_id' => $userfuncao))); 
			$this->set(compact('autorizacao'));

		}
		
		if ($this->request->is(array('post', 'put'))) {
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
		
			if ($this->Pedido->save($this->request->data)) {
				$Autorizacao = new AutorizacaosController;
				$Roteiro = new RoteirosController;
		 		$Roteiro->inserirRota($id);

				$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				
				return $this->redirect( $this->referer() );
			}
			
		} else {
			$options = array('conditions' => array('Pedido.' . $this->Pedido->primaryKey => $id));
			$this->request->data = $this->Pedido->find('first', $options);
			$pedido= $this->request->data;

			$i=0;
			$this->loadModel('Produto');

			foreach($pedido['Itensdepedido'] as $itens ){
				$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
				if(!empty($produto)){
					$pedido['Itensdepedido'][$i]['prodNome']= $produto['Produto']['nome'];
					$i++;
				}else{
					$pedido['Itensdepedido'][$i]['prodNome']='';
				}	
			}
			$contFlag=1;
			$horaAtual = date("H:i:s");  
			$difHora="00:00:00";
			$horaAtendimento = $pedido['Pedido']['hora_atendimento'];

			$tempoTotalFila = $this->calculaFilaProduto($id);

			$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);
			

			

			if($horaAtual > $horaAtendimento){

				

				if($horaAtual < $esperaHora){

					$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
					//$difHora=date('H:i:s', $difHora);
					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);	
					
					
				}else{
					
					if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){
						

						$horaAux1="23:59:59";
						$horazero="00:00:01";
						$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
						$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
						$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

						$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
						
					}else{
						
						$pedido['Pedido']['difhora']='00:00:00';
					}

					

				}
			}else{
				
				if($horaAtual < $esperaHora){
					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
				}else{
					$pedido['Pedido']['difhora']='00:00:00';
				}	
			}

			if($pedido['Pedido']['statuspreparo']!=1){
				$pedido['Pedido']['difhora']="00:00:00";
			}
			$this->set(compact('pedido'));

		}
		$this->loadModel('Pagamento');
		$pagamentos = $this->Pagamento->find('all', array('recursive'=> -1));
		$this->loadModel('Entregador');
		$entregadores = $this->Entregador->find('all', array('recursive'=> -1));
		$this->loadModel('User');
		$users = $this->User->find('list');
		$this->set(compact('pagamentos', 'users','entregadores'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->Pedido->id = $id;
		if (!$this->Pedido->exists()) {
			throw new NotFoundException(__('Invalid pedido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Pedido->delete()) {
			$this->Session->setFlash(__('O pedido foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	
	}
	
		public function pgtomoip($codigo= null){
			
			
			date_default_timezone_set("Brazil/East");
			header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
			
			$codigo= $_GET['cd'];
	//$resultados = $this->Atendimento->find('all', array('recursive' => 1,'limit' => 5, 'order' => 'Atendimento.id DESC','conditions' => array('Atendimento.cliente_id' => $cliente)));
			
			$resultados ="teste";
			
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		}
		function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
		{
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';

		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		$len = strlen($caracteres);
		for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
		}
		return $retorno;
		}
}
