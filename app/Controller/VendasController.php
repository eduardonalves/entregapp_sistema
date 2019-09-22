<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Roteiros');
App::import('Controller', 'Empresas');
App::import('Controller', 'Users');
App::import('Controller', 'Produtos');
App::import(
    'Vendor',
    'Escpos',
    array('file' => 'lib' . DS . 'Escpos.php')
);
App::import(
    'Vendor',
    'PagSeguroLibrary',
    array('file' => 'PagSeguroLibrary' . DS . 'PagSeguroLibrary.php')
);
/**
 * Vendas Controller
 *
 * @property Venda $Venda
 * @property PaginatorComponent $Paginator
 */
class VendasController extends AppController {

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
		$vendas2=$this->Venda->find('all');
		$i=0;
		$vendas= array();

		foreach($vendas2 as $venda){
			$j=0;
			foreach($venda['Itensdevenda'] as $j => $iten){
				$produto= $this->Produto->find('first', array('recursive' => -1,'conditions' => array('Produto.id' => $iten['produto_id'])));

				$venda['Itensdevenda'][$j]['produto']= $produto['Produto']['nome'];

				$j++;
			}
			array_push($vendas, $venda);

			$i++;
		}



		$this->set(compact('vendas'));
	}
  public function imprimirVenda()
  {

   /**
   * Install the printer using USB printing support, and the "Generic / Text Only" driver,
   * then share it (you can use a firewall so that it can only be seen locally).
   *
   * Use a WindowsPrintConnector with the share name to print.
   *
   * Troubleshooting: Fire up a command prompt, and ensure that (if your printer is shared as
   * "Receipt Printer), the following commands work:
   *
   * 	echo "Hello World" > testfile
   * 	copy testfile "\\%COMPUTERNAME%\Receipt Printer"
   * 	del testfile
   */

   $this->layout ='liso';
    try {
    	// Enter the share name for your USB printer here
			$connector = null;
			$connector = new WindowsPrintConnector("smb://eduardo-pc/Receipt Printer");
			/* Print a "Hello world" receipt" */
			$printer = new Escpos($connector);
			$printer -> text("Hello World!\n");
			$printer -> cut();

			/* Close printer */
			$printer -> close();
    } catch(Exception $e) {
    	echo "Couldn't print to this printer: " . $e -> getMessage() . "\n";
    }

  }
	public function mapa() {
		$Empresa = new EmpresasController;
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
		header('Content-Type: text/html; charset=utf-8');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'mapas';

		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}


		if(!isset($_GET['mode'])){
			$mode="Venda";
			$dtini=date("Y-m-d", strtotime( '-1 days' ) );
			$dtfim=date('Y-m-d');
			$this->loadModel('Entregador');
			$entregadores=$this->Entregador->find('all', array('recursive'=> -1, 'conditions'=> array('Entregador.empresa_id' => $minhasFiliais)));
			$this->set(compact('mode','dtini', 'dtfim','entregadores','lojas'));

		}
		$this->set(compact('lojas'));


	}
	public function mapajson() {
		date_default_timezone_set("Brazil/East");
		header('Content-Type: text/html; charset=utf-8');
		$this->loadModel('Entregador');
		$this->loadModel('Roteiro');
		$this->loadModel('Empresa');
		$this->layout ='ajaxresultadostatus';
		$User = new UsersController;
		$userId = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userId);
		$mod =$_GET['md'];
		$loja= $_GET['loja'];
		if(in_array($loja, $minhasFiliais )){
			$loja=$_GET['loja'];
		}else{
			$loja=0;
		}

		if(isset($_GET['stausentrega'])){
			$modEntrega = $_GET['stausentrega'];

		}
		if(isset($_GET['entregador'])){
			$entregador= $_GET['entregador'];
		}

		$this->checkbfunc->formatDateToBD($_GET['dtini']);
		$this->checkbfunc->formatDateToBD($_GET['dtfim']);
		if($mod=='Vendas'){

			if($modEntrega=='aentregar'){
				if($entregador=='Todos'){
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'Venda.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Venda.status NOT LIKE' => '%Finalizado%'
									),
									array(
										'Venda.filial_id' => $loja
									),

								),
							)
						)
					);
				}else if($entregador=='Nenhum'){
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'Venda.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Venda.status NOT LIKE' => '%Finalizado%'
									),
									array(
										'Venda.filial_id' => $loja
									),
									array(
										'OR' => array(
											array(
												'Venda.entregador_id' => NULL
												),
											array(
												'Venda.entregador_id' => 0
											),
										),
									),
								),
							)
						)
					);
				}else{
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'Venda.status NOT LIKE' => '%Entregue%'
									),
									array(
										'Venda.status NOT LIKE' => '%Finalizado%'
									),
									array(
										'Venda.entregador_id' => $entregador
									),
									array(
										'Venda.filial_id' => $loja
									),
								),
							)
						)
					);
				}

			}else{
				if($entregador=='Todos'){
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'OR' => array(
											array(
											'Venda.status LIKE' => '%Entregue%'
											),
											array(
												'Venda.status LIKE' => '%Finalizado%'
											),
										),
									),
									array(
										'Venda.filial_id' => $loja
									),
								),
							)
						)
					);
				}else if($entregador=='Nenhum'){
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'Venda.filial_id' => $loja
									),

									array(
										'OR' => array(
											array(
												'Venda.entregador_id' => NULL
												),
											array(
												'Venda.entregador_id' => 0
											),
										),
									),
									array(
										'OR' => array(
											array(
											'Venda.status LIKE' => '%Entregue%'
											),
											array(
												'Venda.status LIKE' => '%Finalizado%'
											),

										),
									),
								),
							)
						)
					);
				}else{
					$resultados=$this->Venda->find('all',
						array(
							'conditions' => array(
								'AND' => array(
									array(
										'Venda.data >=' => $_GET['dtini'], 'Venda.data <=' => $_GET['dtfim']
										),
									array(
										'Venda.status NOT LIKE' => '%Cancelado%'
									),
									array(
										'Venda.entregador_id' => $entregador
									),
									array(
										'Venda.filial_id' => $loja
									),
									array(
										'OR' => array(
											array(
											'Venda.status LIKE' => '%Entregue%'
											),
											array(
												'Venda.status LIKE' => '%Finalizado%'
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
			$resultados=$this->Entregador->find('all', array('recursive' => -1,'conditions' =>  array('AND'=> array(array('Entregador.filial_id' => $loja
									), array('Entregador.ativo' => 1)))));
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
									array(
										'Roteiro.filial_id' => $loja
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
										'Roteiro.filial_id' =>$minhasFiliais
									),
									array(
										'OR' => array(
											array(
												'Venda.entregador_id' => NULL
												),
											array(
												'Venda.entregador_id' => 0
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
									array(
										'Roteiro.filial_id' =>$loja
										),
								),
							)
						)
					);
				}


			if($resultados !='' && !empty($resultados)){
				$this->loadModel('Filial');
				$empresa = $this->Filial->find('first', array('conditions' => array('Filial.id'=> $minhasFiliais), 'recursive' => -1));
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

	public function confirmarvenda($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->layout ='ajaxresultadostatus';
		if (!$this->Venda->exists($id)) {
			throw new NotFoundException(__('Invalid venda'));
		}
		$resultados = array();
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'confirmar';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

		}else{
			if ($this->request->is(array('Ajax'))) {
				$venda=$this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
				if($venda['Venda']['status']=='Em Aberto'){
					$this->Venda->id= $id;
					$this->Venda->saveField('status', 'Confirmado');
					$this->Venda->saveField('user_id', $userid);
					$this->loadModel('Atendimento');
					$this->Atendimento->create();
					$updateStatusAtendimento= array('id' => $venda['Venda']['atendimento_id'], 'status' => 'Confirmado');
					$this->Atendimento->save($updateStatusAtendimento);

					$resultados= $this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
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
			if (!$this->Venda->exists($id)) {
				throw new NotFoundException(__('Invalid venda'));
			}
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'preparar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			$resultados = array();
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

			}else{

				if ($this->request->is(array('Ajax'))) {
					$venda=$this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
					if($venda['Venda']['status']=='Confirmado'){
						$this->Venda->id= $id;
						$this->Venda->saveField('status', 'Pronto');
						$this->loadModel('Atendimento');
						$this->Atendimento->create();
						$updateStatusAtendimento= array('id' => $venda['Venda']['atendimento_id'], 'status' => 'Pronto');
						$this->Atendimento->save($updateStatusAtendimento);
						$resultados= $this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
						$this->loadModel('Itensdevenda');
						$this->Itensdevenda->updateAll(
							array('Itensdevenda.statuspreparo' => 0),
						    array('Itensdevenda.venda_id' => $id));
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
			if (!$this->Venda->exists($id)) {
				throw new NotFoundException(__('Invalid venda'));
			}
			$resultados = array();
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'separar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

			}else{
				if ($this->request->is(array('Ajax'))) {
					$venda=$this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
					if($venda['Venda']['status']=='Pronto'){
						$this->Venda->id= $id;
						$this->Venda->saveField('status', 'Separado');
						$this->loadModel('Atendimento');
						$this->Atendimento->create();
						$updateStatusAtendimento= array('id' => $venda['Venda']['atendimento_id'], 'status' => 'Separado');
						$this->Atendimento->save($updateStatusAtendimento);
						$resultados= $this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
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
			if (!$this->Venda->exists($id)) {
				throw new NotFoundException(__('Invalid venda'));
			}
			$resultados = array();
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'enviar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

			}else{
				if ($this->request->is(array('Ajax'))) {
					$venda=$this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
					if($venda['Venda']['status']=='Separado'){
						$this->Venda->id= $id;
						$this->Venda->saveField('status', 'Em Trânsito');
						$this->loadModel('Atendimento');
						$this->Atendimento->create();
						$updateStatusAtendimento= array('id' => $venda['Venda']['atendimento_id'], 'status' => 'Em Trânsito');
						$this->Atendimento->save($updateStatusAtendimento);
						$resultados= $this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
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
			if (!$this->Venda->exists($id)) {
				throw new NotFoundException(__('Invalid venda'));
			}

			$Autorizacao = new AutorizacaosController;
			$autTipo = 'entregar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			$resultados = array();
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

			}else{
				if ($this->request->is(array('Ajax'))) {
					$venda=$this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));

					//if($venda['Venda']['status']=='Em Trânsito'){

						if($venda['Venda']['entregador_id'] !='' && $venda['Venda']['entregador_id'] !=0){
							$this->Venda->id= $id;
							$this->Venda->saveField('status', 'Entregue');
							$this->Venda->saveField('statuspreparo', 0);
							$this->Venda->saveField('posicao_fila', 0);
							$this->reordenafila();
							$resultados= $this->Venda->find('first', array('recursive' => -1,'conditions' => array('Venda.id' => $id)));
							$this->loadModel('Roteiro');
							$vendaRoteiro = $this->Roteiro->find('first', array('recursive' => -1,'conditions' => array('Roteiro.venda_id' => $id)));
							$this->Roteiro->create();
							$updateRot= array('id' => $vendaRoteiro['Roteiro']['venda_id'], 'status' => 'Entregue');
							$this->Roteiro->save($updateRot);
							$this->loadModel('Atendimento');
							$this->Atendimento->create();
							$updateStatusAtendimento= array('id' => $venda['Venda']['atendimento_id'], 'status' => 'Entregue');
							$this->Atendimento->save($updateStatusAtendimento);


						}
					//}

					$this->set(array(
							'resultados' => $resultados,
							'_serialize' => array('resultados')
						));

				}
			}
	}



	public function cancelarvenda($id = null) {
		date_default_timezone_set("Brazil/East");
	//	$this->layout ='ajaxresultadostatus';
			if (!$this->Venda->exists($id)) {
				throw new NotFoundException(__('Invalid venda'));
			}
			$Autorizacao = new AutorizacaosController;
			$autTipo = 'cancelar';
			$userid = $this->Session->read('Auth.User.id');
			$userfuncao = $this->Session->read('Auth.User.funcao_id');
			$resultados = $this->Venda->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Venda.id'=> $id),array('Venda.filial_id'=> $this->Session->read('Auth.User._Filial.id'))))));
      
			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){

			}else{
				if ($this->request->is(array('Post'))) {

					if(!empty($resultados)){
						if($resultados ['Venda']['status'] != 'Cancelado'){
							$this->Venda->id= $id;


              if (	$this->Venda->saveField('status', 'Cancelado')) {
          			$this->Session->setFlash(__('A venda foi cancelada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
          		} else {
          			$this->Session->setFlash(__('Houve um erro ao cancelar a venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
          		}

							//$this->loadModel('Atendimento');

							/*$updateStatusAtendimento= array('id' => $resultados['Venda']['atendimento_id'], 'status' => 'Cancelado');
							$this->Atendimento->create();
							$this->Atendimento->save($updateStatusAtendimento);
							$this->Venda->create();
							$updateVenda = array('id'=> $id, 'motivocancela'=>$this->request->data['Venda']['motivocancela'], 'status'=>'Cancelado');
							$this->Venda->save($updateVenda);

							$this->loadModel('Itensdevenda');
							$this->Itensdevenda->updateAll(
								array('Itensdevenda.statuspreparo' => 0),
							    array('Itensdevenda.venda_id' => $id)
							);
							$this->reordenafila();
							$Estoque = new ProdutosController;
							$itensACancelar = $this->Itensdevenda->find('all', array('recursive'=>-1, 'conditions'=> array('Itensdevenda.venda_id' => $id)));

							foreach ($itensACancelar as $iten) {

								$Estoque->aumentaEstoque($iten['Itensdevenda']['produto_id'], $iten['Itensdevenda']['qtde']);
							}*/
						}
						//$resultados = $this->Venda->find('first', array('recursive'=> -1, 'conditions'=> array('Venda.id'=>$id)));
					}
				}

				/*$this->set(array(
					'resultados' => $resultados,
					'_serialize' => array('resultados')
				));*/
			}
      	return $this->redirect( $this->referer() );
	}

	public function index() {

		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}

		$Autorizacao = new AutorizacaosController;
		$User = new UsersController;


		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$lojas = $User->getSelectFiliais($userid);
		$minhasFiliais = $User->getFiliais($userid);

		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$this->loadModel('Filial');
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

					$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
					$data = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataVenda'])));
					$data= str_replace(" ","",$data);
					$this->request->data['filter']['dataVenda'] = $data;

					$data2 = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataVenda-between'])));
					$data2= str_replace(" ","",$data2);
					$this->request->data['filter']['dataVenda-between'] = $data2;


				}
			}
		$this->Filter->addFilters(
	        array(
	            'codigo' => array(
	                'Venda.id' => array(
	                    'operator' => '='
	                )
	            ),
	           'minhaslojas' => array(
	                'Venda.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Venda.empresa_id' => array(
	                    'operator' => '=',

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
	                'Venda.status' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    ),
						 'select' => array(''=>'', 'Cancelado'=> 'Cancelado', 'Confirmado'=> 'Confirmado', 'Em Aberto'=> 'Em Aberto', 'Em Trânsito'=> 'Em Trânsito','Entregue'=> 'Entregue','Pronto'=> 'Pronto', 'Separado p/ Entrega'=>'Separado p/ Entrega'),
	                )
	            ),
	            'statusnot' => array(
	                'Venda.status' => array(
	                    'operator' => 'NOT LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    ),
	                     'select' => array(''=>'', 'Cancelado'=> 'Cancelado', 'Confirmado'=> 'Confirmado', 'Em Aberto'=> 'Em Aberto', 'Em Trânsito'=> 'Em Trânsito','Entregue'=> 'Entregue','Pronto'=> 'Pronto', 'Separado p/ Entrega'=>'Separado p/ Entrega'),
	                )
	            ),
		'novosvendas' => array(
	                'Venda.status_novo' => array(
	                    'operator' => '=',

	                     'select' => array(''=>'Novos e Antigos', '1'=> 'Apenas Novos', '0'=> 'Apenas Antigos'),
	                )
	            ),
				'dataVenda' => array(
		            'Venda.data' => array(
		                'operator' => 'BETWEEN',
		                'between' => array(
		                    'text' => __(' e ', true)
		                )
		            )
		        ),
	        )
	    );

		$conditiosAux= $this->Filter->getConditions();
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

		if(empty($conditiosAux)){



			$dataIncio = date('Y-m-d');
			$dataTermino= date('Y-m-d');
			$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;
			$this->request->data['filter']['dataVenda']=$dataIncio;
			$this->request->data['filter']['dataVenda-between']=$dataTermino;
			$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');



		}else{

			$dataIncio  =  $this->request->data['filter']['dataVenda'] ;
			$dataTermino=$this->request->data['filter']['dataVenda-between'];
		}

		$this->Paginator->settings = array(
				'Venda' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Venda.id asc'
				)
			);

		$this->request->data['filter']['dataVenda'] = date("d/m/Y", strtotime($dataIncio));
		$this->request->data['filter']['dataVenda-between'] = date("d/m/Y", strtotime($dataTermino));



	    // Define conditions
	    //$this->Filter->setPaginate('conditions', $this->Filter->getConditions());

	   	$this->Venda->find('all', array('conditions'=> array($this->Filter->getConditions()), 'recursive' => 0));
		$vendas = $this->Paginator->paginate('Venda');

		$contAberto=0;
		$contEntregue=0;
		$totalVendasEntregue = 0;
		$totalVendas = 0;
		$totalEntrega = 0;
		foreach($vendas as $venda){

			if($venda['Venda']['status'] != 'Cancelado'){
				if($venda['Venda']['status'] != 'Entregue'){
					$contAberto= $contAberto +1;
				}
				$totalVendas +=$venda['Venda']['valor'];
			}

			if($venda['Venda']['status'] == 'Entregue'){
				$totalVendasEntregue +=$venda['Venda']['valor'];
				$totalEntrega +=$venda['Venda']['entrega_valor'];
				$contEntregue= $contEntregue +1;

			}
		}


		$this->mensagensativas();

		//echo $contAberto;
		//echo "<br/>";
		//echo $contEntregue;
	   $this->set(compact('vendas','contAberto', 'contEntregue','lojas','totalVendas','totalVendasEntregue','totalEntrega'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function countvendasnovos(){
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		$this->layout='liso';
 		if(isset($_GET['loja'])){
 			$loja=$_GET['loja'];
 			$novosVendas = $this->Venda->find('all', array('recursive'=>-1,'conditions' => array('AND' => array(array('Venda.filial_id'=>$loja),array('Venda.status_novo' => 1), array('Venda.filial_id' => $minhasFiliais)))));
 			$resultados=count($novosVendas);
 		}else{
 			$resultados=0;
 			//$loja=$this->request->data['filter']['minhaslojas'];
 		}


 		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
	}
 	public function mensagensativas() {
 		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);

 		$this->loadModel('Mensagen');
 		$j=0;
      $coutmsg=0;
 		if(isset($_GET['loja'])){
 			$loja = $_GET['loja'];
 			$mensagensAtivas = $this->Mensagen->find('all', array('conditions' => array('AND' => array(array('Mensagen.filial_id'=>$loja),array('Mensagen.read' => 0), array('Mensagen.filial_id' => $minhasFiliais))), 'fields' => array('DISTINCT Mensagen.venda_id')));
			$mensagensvendas=array();

			foreach($mensagensAtivas as $ativa){

					$vendaInfo = $this->Venda->find('first', array('order' => array('Venda.id' => 'desc'),'recursive'=> 0,'conditions' => array('Venda.id' => $ativa['Mensagen']['venda_id'])));
					if(!empty($vendaInfo)){
						$mensagensvendas['Venda'][$j]['venda_id']=$ativa['Mensagen']['venda_id'];
						$mensagensvendas['Venda'][$j]['codigo']= $vendaInfo['Atendimento']['codigo'];
						$mensagensvendas['Venda'][$j]['username']= $vendaInfo['Cliente']['username'];
						$idAtivos['Venda'][$j]['id'] = $ativaproduto_id['Mensagen']['venda_id'];
            $coutmsg++;
					}

					$j++;

			}
 		}else{
 			$mensagensvendas=array();
 			$coutmsg=0;
 			//$loja = $this->request->data['filter']['minhaslojas'];
 		}


		$coutmsg = $j;
		$this->set(compact('mensagensvendas','coutmsg'));
 	}

	public function view($id = null) {

		if(isset($_GET['loja'])){
			$loja=$_GET['loja'];
		}else{
			$loja=0;
		}

		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);

		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		date_default_timezone_set("Brazil/East");
		$this->layout='liso';
		if (!$this->Venda->exists($id)) {
			throw new NotFoundException(__('Invalid venda'));
		}

		$this->loadModel('Produto');
		$venda = $this->Venda->find('first', array('conditions' => array('AND'=> array(array('Venda.id' => $id), array('Venda.filial_id' => $minhasFiliais)))));



		$i=0;
		foreach($venda['Vendasiten'] as $itens ){
			$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
			$venda['Vendasiten'][$i]['prodNome']= $produto['Produto']['nome'];
			$i++;
		}

    $j=0;
    $this->loadModel('Pagamento');
		foreach($venda['Vendaspagamento'] as $itens ){
			$produto = $this->Pagamento->find('first', array('conditions' => array('Pagamento.id' => $itens['pagamento_id'])));
			$venda['Vendaspagamento'][$j]['pagNome']= $produto['Pagamento']['tipo'];
			$j++;
		}
		$estaFilial= $this->Filial->find('first', array('recursive'=>-1, 'conditions'=> array('Filial.id'=> $loja)));

		$this->Venda->id=$id;


		$this->set(compact('venda'));


	}

/**
 * add method
 *
 * @return void
 */
 	public function reordenafila() {
 		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
 		$filas = $this->Venda->find('all', array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Venda.statuspreparo' => 1), array('Venda.filial_id'=> $minhasFiliais), array('Venda.status <>' => 'Cancelado')))));
 		foreach ($filas as $fila) {
 			$id=$fila['Venda']['id'];
 			$posicaofila= $this->Venda->find('count', array('recursive' => -1,'conditions' => array('AND' => array(array('Venda.filial_id'=> $minhasFiliais),array('Venda.statuspreparo' => 1), array('Venda.status <>' => 'Cancelado'), array('Venda.id <' => $id)))));
			$this->Venda->id=$id;
			$this->Venda->saveField('posicao_fila', $posicaofila);
 		}

 	}
	public function add($codigo = null) {
		$this->loadModel('Filial');
		if(isset($_GET['loja'])){
			$loja=$_GET['loja'];
		}else{
			$loja=0;
		}
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);

		
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
			if(!isset($this->request->data['Itensdevenda'])){
				$this->Session->setFlash(__('Não existem itens no venda!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}

			$this->Venda->create();

			$this->loadModel('Produto');
			$this->loadModel('Atendimento');

			$this->loadModel('Itensdevenda');

			
				$this->request->data['Venda']['data']=date('Y-m-d');
				$this->request->data['Venda']['user_id']=$this->Session->read('Auth.User.id');
				$this->request->data['Venda']['empresa_id']=$this->Session->read('Auth.User.empresa_id');
				$this->request->data['Venda']['status']="Confirmado";

				$this->request->data['Venda']['status_pagamento']="Pendente";

				$userid = $this->Session->read('Auth.User.id');
				$this->request->data['Venda']['user_id']=$userid;

				$this->request->data['Venda']['origem']="Atendimento";
				$clt = $this->request->data['Venda']['cliente_id'];
				$entregadorID="";
				if(isset($this->request->data['Venda']['entregador_id'])){
					$entregadorID= $this->request->data['Venda']['entregador_id'];
				}

				$ultimovenda="";

				$this->loadModel('Atendimento');
				$codigo="";
				$this->Atendimento->create();
				$flag ="FALSE";
				$this->loadModel('Empresa');

				$empresa=$this->Filial->find('first', array('recursive'=> -1, 'conditions' => array('Filial.id' => $this->request->data['Venda']['filial_id'])));

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
						$lat = $empresa['Filial']['lat'];
						$lng = $empresa['Filial']['lng'];
						$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo, 'tipo' => 'EXTERNO', 'cliente_id' => (int)  $clt, 'lat' => $lat, 'lng' => $lng, 'entregador_id' => (int)  $entregadorID, 'filial_id'=> (int) $this->request->data['Venda']['filial_id'], 'empresa_id'=>$this->Session->read('Auth.User.empresa_id'));
						
						if ($this->Atendimento->save($dadosatendimento)) {

							$ultimoAtend = $this->Atendimento->find('first', array('conditions' => array('Atendimento.filial_id'=> $this->request->data['Venda']['filial_id']),'order' => array('Atendimento.id' => 'desc'), 'recursive' =>1));

							$codigo=$ultimoAtend['Atendimento']['codigo'];
							$atendimento=$ultimoAtend;
							$this->request->data['Venda']['atendimento_id']=$ultimoAtend['Atendimento']['id'];

						} else {
							//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
						}

					 }else{
						$flag ="FALSE";
					 }
				}







				$this->Venda->create();

				$this->request->data['Venda']['data']=date('Y-m-d');
				$i=0;

				$total=0;
				$tempoPreparo="00:00:00";


				foreach($this->request->data['Itensdevenda'] as $iten ){
					if($iten['qtde']=='' || $iten['qtde'] =='___' || $iten['qtde']==0){
						$this->Session->setFlash(__('Ocorreu um errro ao adicionar os produtos, por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
						return $this->redirect( $this->referer() );
					}
					$produto =$this->Produto->find('first', array('conditions' => array('Produto.id' => $iten['produto_id'])));
				//	$this->request->data['Itensdevenda'][$i]['valor_unit']= $produto['Produto']['preco_venda'];
				//	$this->request->data['Itensdevenda'][$i]['valor_total'] = $produto['Produto']['preco_venda'] * $iten['qtde'];
					//$this->request->data['Itensdevenda'][$i]['produto_id'] = $iten['produto_id'];
				//	$this->request->data['Itensdevenda'][$i]['qtde'] = $iten['qtde'];
					$this->request->data['Itensdevenda'][$i]['statuspreparo'] = 1;
					$this->request->data['Itensdevenda'][$i]['filial_id'] = $this->request->data['Venda']['filial_id'];
					$total += $this->request->data['Itensdevenda'][$i]['valor_total'];
          $obsSisArgs= explode('tamanho: ', $this->request->data['Itensdevenda'][$i]['prodnome']);
          if(isset($obsSisArgs[1]))
          {
            $this->request->data['Itensdevenda'][$i]['obs_sis']='<strong><i>Tamanho:</i></strong> '.$obsSisArgs[1];
          }


					for ($k=0; $k <  $iten['qtde']; $k++) {
						$tempoPreparo = $this->checkbfunc->somaHora($tempoPreparo,$produto['Produto']['tempo_preparo']);
					}
					$Estoque = new ProdutosController;
					$Estoque->diminueEstoque($iten['produto_id'], $iten['qtde']);

					//debug($tempoPreparo);

					$i = $i +1;
				}

				$this->request->data['Venda']['valor']=$total;



				$horaAtendimento =  date("H:i:s");

				$tempoVisualizacao = "00:01:00";
				//$tempoFila = $this->calculaFilaProdutos($this->request->data['Venda']['filial_id']);
				$estaFilial= $this->Filial->find('first', array('recursive'=>-1, 'conditions'=> array('Filial.id'=> $this->request->data['Venda']['filial_id'])));
				$tempoFila = $estaFilial['Filial']['tempo_atendimento'];
				if($tempoFila == false || empty($tempoFila)){
					$tempoFila="00:00:00";
				}
				//Garanto o minimo de tempo para preparar o produto mais demorado para ficar pronto


				//Busco a duração do trajeto do endereço do cliente
				$this->loadModel('Cliente');
				$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions' => array('Cliente.id' => $this->request->data['Venda']['cliente_id'])));

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

				$this->request->data['Venda']['hora_atendimento'] = $horaAtendimento;
				$this->request->data['Venda']['statuspreparo'] = 1;
				$this->request->data['Venda']['tempo_estimado'] =$tempoEst;
				$this->request->data['Venda']['tempo_fila'] = $tempoTotalFila;

				$posicaoFila= $this->Venda->find('count', array('recursive' => -1,'conditions' => array('AND' => array(array('Venda.statuspreparo' => 1), array('Venda.filial_id'=>$this->request->data['Venda']['filial_id'])))));

				if(empty($posicaoFila)){
					$posicaoFila=0;
				}
				$this->request->data['Venda']['posicao_fila']=$posicaoFila;

				if(!empty($vendaExistente)){
					$j=0;
					foreach($this->request->data['Itensdevenda'] as $itens ){

						$this->request->data['Itensdevenda'][$j]['venda_id'] = $vendaExistente['Venda']['id'];
						$this->request->data['Itensdevenda'][$j]['filial_id']= $this->request->data['Venda']['filial_id'];
						$j=$j +1;
					}



					$this->request->data['Venda']['id']= $vendaExistente['Venda']['id'];
					$this->Venda->save($this->request->data['Venda']);
					if ($this->Itensdevenda->saveAll($this->request->data['Itensdevenda'])){


						$ultimovenda = $vendaExistente;

						//Insiro o venda na rota do  entregador
						$Autorizacao = new AutorizacaosController;
						$Roteiro = new RoteirosController;

				 		$Roteiro->inserirRota($ultimovenda['Venda']['id']);

						$this->set(compact($ultimovenda));
						if(! $this->request->is('ajax'))
						{
							//$this->Session->setFlash(__('O venda foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
							//return $this->redirect( $this->referer() );
						}

						if( $this->request->is('ajax'))
						{
							$this->layout ='ajaxaddvenda';
						}
					}else{
						$ultimoVenda = $this->request->data;
						$this->Session->setFlash(__('Houve um erro ao salvar o venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
						return $this->redirect( $this->referer() );
					}
				}else{
					if ($this->Venda->saveAll($this->request->data)){

						$this->loadModel('Roteiro');

						//debug($this->request->data);

						$ultimovenda = $this->Venda->find('first', array('conditions'=> array('Venda.filial_id' => $this->request->data['Venda']['filial_id']),'order' => array('Venda.id' => 'desc'), 'recursive' => -1));


						//Insiro o venda na rota do  entregador
						$Autorizacao = new AutorizacaosController;
						$Roteiro = new RoteirosController;

				 		$Roteiro->inserirRota($ultimovenda['Venda']['id']);

						$this->set(compact($ultimovenda));
						if(! $this->request->is('ajax'))
						{
							$this->Session->setFlash(__('O venda foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
							return $this->redirect( $this->referer() );
						}

						if( $this->request->is('ajax'))
						{
							$this->layout ='ajaxaddvenda';
						}
					}else{
						$ultimoVenda = $this->request->data;
						$this->Session->setFlash(__('Houve um erro ao salvar o venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
					}
				}


		}
		$this->loadModel('Produto');
		$this->loadModel('Cliente');
		$this->loadModel('Pagamento');
		$this->loadModel('Entregador');
		$filiasArray = $User->getFiliais($userid);
		if(in_array($loja, $filiasArray )){
			$entregadores = $this->Entregador->find('all', array('recursive'=> -1, 'conditions' => array('AND' => array(array('Entregador.ativo'=> true), array('Entregador.filial_id'=> $loja)))));
			$produtos = $this->Produto->find('all', array('recursive' => -1, 'order' => 'Produto.nome asc', 'conditions' => array('AND' => array(array('Produto.ativo' => true), array('Produto.filial_id'=> $loja)))));

      $this->loadModel('Tamanho');
      $produtosTratados=array();
      foreach ($produtos as $key => $value) {
        $tamanhos= $this->Tamanho->find('all', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Tamanho.produto_id'=> $value['Produto']['id']), array('Tamanho.ativo'=> true)) )));
        if(!empty($tamanhos))
        {
        foreach ($tamanhos as $tamanho) {
            $newProduto = array(
              'Produto' => array(
                'id'=> $value['Produto']['id'],
                'nome'=>$value['Produto']['nome'].' tamanho: '.$tamanho['Tamanho']['nome'],
                'preco_venda' => number_format($tamanho['Tamanho']['preco'],2,'.',''),
                'acompanha_bebida'=> $tamanho['Tamanho']['acompanha_bebida'],
                'promo_compre_ganhe'=>  $tamanho['Tamanho']['promo_compre_ganhe'],
                'disponivel' => $value['Produto']['disponivel'],
                'ativo'=> $value['Produto']['disponivel'],
              )
            );
            array_push($produtosTratados, $newProduto);
          }

        }else
        {
          $newProduto = array(
            'Produto' => array(
              'id'=> $value['Produto']['id'],
              'nome'=>$value['Produto']['nome'],
              'preco_venda' =>  number_format($value['Produto']['preco_venda'],2,'.',''),
              'acompanha_bebida'=> $value['Produto']['acompanha_bebida'],
              'promo_compre_ganhe'=>  $value['Produto']['promo_compre_ganhe'],
              'disponivel' => $value['Produto']['disponivel'],
              'ativo'=> $value['Produto']['disponivel'],
            )
          );
          array_push($produtosTratados, $newProduto);
          //$produtosTratados[]=$newProduto;
        }
      //  $produtos[$key]['Produto']['tamanho']= $tamnahos;
      }
      $produtos = $produtosTratados;
      unset($produtosTratados);
      unset($tamanhos);
      $this->loadModel('Mesa');
      $mesas = $this->Mesa->find('all', array('recursive' => -1, 'order' => 'Mesa.identificacao asc', 'conditions'=> array('Mesa.filial_id'=> $loja)));

      $clientes = $this->Cliente->find('all', array('recursive' => -1, 'order' => 'Cliente.nome asc', 'conditions'=> array('AND'=> array(array('Cliente.ativo' => true), array('Cliente.filial_id' => $filiasArray)))));
			$pagamentos = $this->Pagamento->find('all', array('recursive' => -1, 'order' => 'Pagamento.tipo asc', 'conditions'=> array('Pagamento.filial_id'=> $loja)));


			$lojas = $this->Filial->find('list', array('recursive'=>-1, 'conditions'=> array(array('Filial.id'=> $loja))));
			$this->set(compact('mesas','pagamentos','ultimovenda','codigo', 'produtos', 'clientes','entregadores','lojas'));
		}else{
			$entregadores = array();
			$produtos = array();
			$clientes = array();
			$pagamentos = array();
			$lojas = array();
      $mesas = array();
			$this->set(compact('mesas','pagamentos','ultimovenda','codigo', 'produtos', 'clientes','entregadores','lojas'));
		}
	}



	public function calculaFilaProdutos(&$filial_id){
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		$this->loadModel('Produto');
		$this->loadModel('Itensdevenda');
		$this->Itensdevenda->virtualFields = array('totalprod' => 'SUM(Itensdevenda.qtde)');
		$produtos= $this->Produto->find('all', array('recursive' => -1, 'conditions' => array('Produto.filial_id'=> $filial_id)));
		$i=0;
		$produtoFila= array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdevenda->find('all', array('recursive'=> -1,'conditions' => array('AND' => array(array('Itensdevenda.filial_id' => $filial_id),array('Itensdevenda.produto_id'=> $produto['Produto']['id']), array('Itensdevenda.statuspreparo'=> 1))) ));

			if(!empty($qteFilaProduto)){

				if(isset($qteFilaProduto[0]['Itensdevenda']['totalprod'])){

					if($qteFilaProduto[0]['Itensdevenda']['totalprod']== null){
						unset($qteFilaProduto[$i]);
					}else{
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdevenda']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo=$produto['Produto']['tempo_preparo'];
						$qtdePreparo=$produto['Produto']['qtde_preparo'];
						if($qtdePreparo  != null){
							$qteFila = $qteFilaProduto[0]['Itensdevenda']['totalprod'];
							$tempoNescessario = '?';
							$acumuladorTempo='00:00:00';
							for ($i=0; $i < $qteFila; $i++) {
								$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
							}

							$segundosTotais = $this->converteparasegundos($acumuladorTempo);

							if($qtdePreparo==null || $qtdePreparo==''){

							}else{
								$segundosTotais = $segundosTotais / $qtdePreparo;
								$tempoTotal = gmdate("H:i:s", $segundosTotais);

								$tempoTotalPreparo = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoTotal);
							}
						}




					}

				}

			}

			$i++;
		}

		if(isset($tempoTotalPreparo)){

			return $tempoTotalPreparo;
		}else{
			return false;
		}


	}

	public function calculaFilaProduto(&$id, $filial_id){
		$this->loadModel('Produto');
		$this->loadModel('Itensdevenda');
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');

		$this->Itensdevenda->virtualFields = array('totalprod' => 'SUM(Itensdevenda.qtde)');
		$produtos= $this->Produto->find('all', array('recursive' => -1, 'conditions'=> array('Produto.filial_id'=> $filial_id)));
		$i=0;
		$produtoFila= array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdevenda->find('all', array('recursive'=> -1,'conditions' => array('AND' => array(array('Itensdevenda.filial_id'=>$filial_id),array('Itensdevenda.produto_id'=> $produto['Produto']['id']), array('Itensdevenda.statuspreparo'=> 1), array('Itensdevenda.venda_id <='=> $id))) ));

			if(!empty($qteFilaProduto)){

				if(isset($qteFilaProduto[0]['Itensdevenda']['totalprod'])){

					if($qteFilaProduto[0]['Itensdevenda']['totalprod']== null){
						unset($qteFilaProduto[$i]);
					}else{
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdevenda']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo=$produto['Produto']['tempo_preparo'];
						if($produto['Produto']['qtde_preparo'] != null){
							$qtdePreparo= round($produto['Produto']['qtde_preparo']);

							$qteFila = round($qteFilaProduto[0]['Itensdevenda']['totalprod']);
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
							if($qtdePreparo==null || $qtdePreparo==''){

							}else{
								$segundosTotais = $segundosTotais / $qtdePreparo;
								$tempoTotal = gmdate("H:i:s", $segundosTotais);

								$tempoTotalPreparo = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoTotal);
							}
						}


					}

				}

			}

			$i++;
		}

		if(isset($tempoTotalPreparo)){
				//Busco a duração do trajeto do endereço do cliente
				$this->loadModel('Cliente');
				$this->loadModel('Venda');
				$vendaFunc= $this->Venda->find('first', array('recursive' => -1, 'conditions' => array('Venda.id' => $id)));
				$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions' => array('Cliente.id' => $vendaFunc['Venda']['cliente_id'])));

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

				$meusItens = $this->Itensdevenda->find('all', array('recursive' => -1, 'conditions' => array('and' => array(array('Itensdevenda.venda_id' => $id), array('Itensdevenda.filial_id' => $filial_id)))));

			/*	$maiorTempoPreparo='00:00:00';

				foreach ($meusItens as $meuItem) {
					$meuProduto = $this->Produto->find('first', array('recursive' => -1, 'conditions' => array('Produto.id' => $meuItem['Itensdevenda']['produto_id'])));
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
		$this->loadModel('Produto');
		$this->loadModel('Filial');
		$this->loadModel('Itensdevenda');
		$User = new UsersController;
		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
		date_default_timezone_set("Brazil/East");
		if (!$this->Venda->exists($id)) {
			throw new NotFoundException(__('Invalid venda'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		//$userid = $this->Session->read('Auth.User.id');
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

			$entregadorID= $this->request->data['Venda']['entregador_id'];
			if ($this->Venda->save($this->request->data)) {

				if(isset($this->request->data['Venda']['entregador_id'])){

					$meuVenda = $this->Venda->find('first', array('recursive' => -1, 'conditions' => array('Venda.id' => $id)));
					$atendEntrega = array('id' => $meuVenda['Venda']['atendimento_id'], 'entregador_id' => $entregadorID);
					$this->loadModel('Atendimento');
					$this->Atendimento->create();
					$this->Atendimento->save($atendEntrega);

				}
				//Insiro o venda na rota do  entregador
				$Autorizacao = new AutorizacaosController;
				$Roteiro = new RoteirosController;
		 		$Roteiro->inserirRota($id);

				$this->Session->setFlash(__('O venda foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Venda.' . $this->Venda->primaryKey => $id));
			$this->request->data = $this->Venda->find('first', $options);
			$venda= $this->request->data;
			if($venda['Venda']['status_novo']==1){
				$updateNovo = array(
					'id'=>$venda['Venda']['id'],
					'status_novo'=>0
				);
				$this->Venda->create();
				$this->Venda->save($updateNovo);
				$venda['Venda']['status_novo']=0;
			}
			$i=0;
			$this->loadModel('Produto');
			foreach($venda['Itensdevenda'] as $itens ){
				$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
				if(!empty($produto)){
					$venda['Itensdevenda'][$i]['prodNome']= $produto['Produto']['nome'];
					$venda['Itensdevenda'][$i]['prodNome']=$venda['Itensdevenda'][$i]['prodNome'].' '.$itens['obs_sis'];
					$i++;
				}

			}
			if($venda['Venda']['entrega_outro_local'] !=1){
				$venda['Venda']['outro_endereco_entrega']="No endereço de cadastro";
			}

			$contFlag=1;
			$horaAtual = date("H:i:s");
			$difHora="00:00:00";
			$horaAtendimento = $venda['Venda']['hora_atendimento'];

			//$tempoTotalFila = $this->calculaFilaProduto($id, $venda['Venda']['filial_id']);
			$estaFilial= $this->Filial->find('first', array('recursive'=>-1, 'conditions'=> array('Filial.id'=> $this->request->data['Venda']['filial_id'])));
			$tempoFila = $estaFilial['Filial']['tempo_atendimento'];
			$tempoTotalFila = $tempoFila;
			$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);




			if($horaAtual > $horaAtendimento){



				if($horaAtual < $esperaHora){

					$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
					//$difHora=date('H:i:s', $difHora);
					$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);


				}else{

					if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){


						$horaAux1="23:59:59";
						$horazero="00:00:01";
						$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
						$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
						$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

						$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);

					}else{

						$venda['Venda']['difhora']='00:00:00';
					}



				}
			}else{

				if($horaAtual < $esperaHora){
					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
				}else{
					$venda['Venda']['difhora']='00:00:00';
				}
			}

			if($venda['Venda']['statuspreparo']!=1){
				$venda['Venda']['difhora']="00:00:00";
			}
			$this->set(compact('venda'));

		}


		$this->loadModel('Pagamento');
		$pagamentos = $this->Pagamento->find('all', array('recursive'=> -1, 'conditions'=> array('Pagamento.filial_id'=> $minhasFiliais)));
		$this->loadModel('Entregador');
		$entregadores = $this->Entregador->find('all', array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Entregador.ativo'=> true), array('Entregador.filial_id'=> $minhasFiliais)))));
		$this->loadModel('User');
		$users = $this->User->find('list', array('conditions' => array('and'=> array('User.id' => $this->Session->read('Auth.User.empresa_id'), array('User.ativo'=> 1)))));
		$this->set(compact('pagamentos', 'users','entregadores'));
	}



	public function editmapa($id = null) {
		$Empresa = new EmpresasController;
		if(!$Empresa->empresaAtiva()){
			$this->Session->setFlash(__('O sistema está temporáriamente indisponível, entre em contato com o suporte técnico.'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('controller' =>'users','action' => 'logout'));
		}
	date_default_timezone_set("Brazil/East");
		if (!$this->Venda->exists($id)) {
			throw new NotFoundException(__('Invalid venda'));
		}

		$Autorizacao = new AutorizacaosController;
		$autTipo = 'pedidos';
		$userid = $this->Session->read('Auth.User.id');
                        $User = new UsersController;
                        $minhasFiliais = $User->getFiliais($userid);
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

			if ($this->Venda->save($this->request->data)) {
				$Autorizacao = new AutorizacaosController;
				$Roteiro = new RoteirosController;
		 		$Roteiro->inserirRota($id);



				if(isset($this->request->data['Venda']['entregador_id'])){
					$entregadorID= $this->request->data['Venda']['entregador_id'];
					$meuVenda = $this->Venda->find('first', array('recursive' => -1, 'conditions' => array('Venda.id' => $id)));
					$atendEntrega = array('id' => $meuVenda['Venda']['atendimento_id'], 'entregador_id' => $entregadorID);
					$this->loadModel('Atendimento');
					$this->Atendimento->create();
					$this->Atendimento->save($atendEntrega);

				}
				//debug();
				//$this->Session->setFlash(__('O venda foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));

				return $this->redirect( $this->referer() );
			} else {
				//$this->Session->setFlash(__('Houve um erro ao salvar o venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));

				return $this->redirect( $this->referer() );
			}

		} else {
			$options = array('conditions' => array('Venda.' . $this->Venda->primaryKey => $id));
			$this->request->data = $this->Venda->find('first', $options);
			$venda= $this->request->data;

			$i=0;
			$this->loadModel('Produto');
			if($venda['Venda']['status_novo']==1){
				$updateNovo = array(
					'id'=>$venda['Venda']['id'],
					'status_novo'=>0
				);
				$this->Venda->create();
				$this->Venda->save($updateNovo);
				$venda['Venda']['status_novo']=0;
			}
			foreach($venda['Itensdevenda'] as $itens ){
				$produto = $this->Produto->find('first', array('conditions' => array('Produto.id' => $itens['produto_id'])));
				if(!empty($produto)){
					$venda['Itensdevenda'][$i]['prodNome']= $produto['Produto']['nome'];
					$i++;
				}else{
					$venda['Itensdevenda'][$i]['prodNome']='';
				}
			}
			$contFlag=1;
			$horaAtual = date("H:i:s");
			$difHora="00:00:00";
			$horaAtendimento = $venda['Venda']['hora_atendimento'];

			$tempoTotalFila = $this->calculaFilaProduto($id, $venda['Venda']['filial_id']);

			$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);




			if($horaAtual > $horaAtendimento){



				if($horaAtual < $esperaHora){

					$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
					//$difHora=date('H:i:s', $difHora);
					$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);


				}else{

					if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){


						$horaAux1="23:59:59";
						$horazero="00:00:01";
						$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
						$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
						$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

						$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);

					}else{

						$venda['Venda']['difhora']='00:00:00';
					}



				}
			}else{

				if($horaAtual < $esperaHora){
					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$venda['Venda']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
				}else{
					$venda['Venda']['difhora']='00:00:00';
				}
			}

			if($venda['Venda']['statuspreparo']!=1){
				$venda['Venda']['difhora']="00:00:00";
			}
			$this->set(compact('venda'));

		}


		$this->loadModel('Pagamento');
                        $pagamentos = $this->Pagamento->find('all', array('recursive'=> -1, 'conditions'=> array('Pagamento.filial_id'=> $minhasFiliais)));
                         $this->loadModel('Entregador');
                         $entregadores = $this->Entregador->find('all', array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Entregador.ativo'=> true), array('Entregador.filial_id'=> $minhasFiliais)))));
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
		$this->Venda->id = $id;
		if (!$this->Venda->exists()) {
			throw new NotFoundException(__('Invalid venda'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Venda->delete()) {
			$this->Session->setFlash(__('O venda foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o venda. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );

	}

		public function pgtomoip($codigo= null){


			date_default_timezone_set("Brazil/East");
			header("Access-Control-Allow-Origin: *");

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
