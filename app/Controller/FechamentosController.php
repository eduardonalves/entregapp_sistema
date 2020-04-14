<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Fechamentos Controller
 *
 * @property Fechamento $Fechamento
 * @property PaginatorComponent $Paginator
 */

class FechamentosController extends AppController {

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
		$this->loadModel('Fechamento');
 		$this->loadModel('User');
 		$this->loadModel('Filial');
 		$this->loadModel('Fechamento');
 		$this->loadModel('Pagamento');
 		$this->loadModel('Fechamentoiten');
		
				$this->Filter->addFilters(
			        array(
			            'codigo' => array(
			                'Fechamento.id' => array(
			                    'operator' => '='
			                )
			            ),
			            'dataFechamento' => array(
				            'Fechamento.data' => array(
				                'operator' => 'BETWEEN',
				                'between' => array(
				                    'text' => __(' e ', true)
				                )
				            )
				        ),

			           
			        )
			    );
		$this->Paginator->settings = array(
				'Fechamento' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Fechamento.id desc'
				)
			);
		$Autorizacao = new AutorizacaosController;
		$User = new UsersController;

		$userid = $this->Session->read('Auth.User.id');
		$lojas = $User->getSelectFiliais($userid);
		$minhasFiliais = $User->getFiliais($userid);
		$conditiosAux= $this->Filter->getConditions();
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
		//converte a data
		if(isset($this->request->data['filter']))
		{

			foreach($this->request->data['filter'] as $key=>$value)
			{

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
				$data = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataFechamento'])));
				$data= str_replace(" ","",$data);
				$this->request->data['filter']['dataFechamento'] = $data;

				$data2 = implode("-",array_reverse(explode("/",$this->request->data['filter']['dataFechamento-between'])));
				$data2= str_replace(" ","",$data2);
				$this->request->data['filter']['dataFechamento-between'] = $data2;


			}
		}
		if(empty($conditiosAux)){



			$dataIncio = date('Y-m-01');
			$dataTermino= date('Y-m-t');
			$this->request->data['filter']['dataFechamento']=$dataIncio;
			$this->request->data['filter']['dataFechamento-between']=$dataTermino;



		}else{

			$dataIncio  =  $this->request->data['filter']['dataFechamento'] ;
			$dataTermino=$this->request->data['filter']['dataFechamento-between'];
		}



		$this->Fechamento->find('all', array('conditions'=> array($this->Filter->getConditions()), 'recursive' => 0));
		$fechamentos = $this->Paginator->paginate('Fechamento');

		
		$this->request->data['filter']['dataFechamento'] = date("d/m/Y", strtotime($dataIncio));
		$this->request->data['filter']['dataFechamento-between'] = date("d/m/Y", strtotime($dataTermino));


		$this->set(compact('fechamentos'));
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

		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		date_default_timezone_set("Brazil/East");
		$this->layout='liso';
		if (!$this->Fechamento->exists($id)) {
			throw new NotFoundException(__('Invalid fechamento'));
		}

		$this->loadModel('Produto');
		$fechamento = $this->Fechamento->find('first', array('conditions' => array('AND'=> array(array('Fechamento.id' => $id), array('Fechamento.filial_id' => $minhasFiliais)))));

		$this->Fechamento->id=$id;


		$this->set(compact('fechamento'));


	}
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function abrirmovimento() {
		$this->loadModel('Venda');
 		$this->loadModel('User');
 		$this->loadModel('Filial');
 		$this->loadModel('Fechamento');
 		$this->loadModel('Pagamento');
 		$this->loadModel('Fechamentoiten');
 		$User = new UsersController;
 		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

		if ($this->request->is(array('post', 'put'))) 
		{
			$fechamentoObj =$this->Fechamento->find('first', array(
					'order'=> array('Fechamento.id'=> 'desc'),
					'conditions'=> array(
						'Fechamento.filial_id'=> $unicaFilial['Filial']['id'],
						'status'=> 1
					)
				)
			);

			if(!empty($fechamentoObj)){
				$this->Session->setFlash(__('Houve um erro ao abrir o caixa, já existe um caixa aberto. Por favor, feche o caixa antes de abrir outro!'), 'default', array('class' => 'error-flash alert alert-danger'));
 				return $this->redirect( $this->referer() );
			}else{
				$this->Fechamento->create();
				$dataToSave = array(
					'data'=> date('Y-m-d H:i:s'),
					'status'=>1,
					'empresa_id'=> $this->Session->read('Auth.User.empresa_id'),
					'filial_id' => $unicaFilial['Filial']['id']
				);
				
				if($this->Fechamento->save($dataToSave))
				{
					$this->Session->setFlash(__('A abertura foi executada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				}else{
					$this->Session->setFlash(__('Houve um erro ao abrir o caixa. Por favor, tente mais tarde!'), 'default', array('class' => 'error-flash alert alert-danger'));
				}
			}
		}else{
			$this->Session->setFlash(__('Houve um erro ao abrir o caixa. Por favor, tente mais tarde!'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function fecharmovimento() {
		$this->loadModel('Venda');
 		$this->loadModel('User');
 		$this->loadModel('Filial');
 		$this->loadModel('Fechamento');
 		$this->loadModel('Pagamento');
 		$this->loadModel('Fechamentoiten');
 		$User = new UsersController;
 		$userid = $this->Session->read('Auth.User.id');
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

		
		/*$this->Fechamento->create();
		$this->Fechamento->save(
			array(
				'data'=> date('Y-m-d H:i:s'),
				'status'=>1,
				'empresa_id'=> $this->Session->read('Auth.User.empresa_id'),
				'filial_id' => $unicaFilial['Filial']['id']
			)
		);*/
		$fechamentoObj =$this->Fechamento->find('first', array(
				'order'=> array('Fechamento.id'=> 'desc'),
				'conditions'=> array(
					'Fechamento.filial_id'=> $unicaFilial['Filial']['id'],
					'status'=> 1
				)
			)
		);

		if(empty($fechamentoObj)){
			$this->Session->setFlash(__('Houve um erro ao fechar o movimento, não existe caixa aberto. Por favor abra o caixa'), 'default', array('class' => 'error-flash alert alert-danger'));
 			return $this->redirect( $this->referer() );
		}

 		$vendas = $this->Venda->find('all', array('conditions'=> array('Venda.filial_id'=> $unicaFilial['Filial']['id'], 'Venda.fechamento_id is null','Venda.status'=> 'Finalizado')));

 		$pagementos = $this->Pagamento->find('all', array('conditions'=> array('filial_id'=> $unicaFilial['Filial']['id']
 		),'recursive'=> -1));


				
 		$totaisPorPagamento = array();
 		if(!empty($pagementos)){
 			foreach ($pagementos as $key => $value) {
	 				$totaisPorPagamento[$value['Pagamento']['id']] = array(
	 					'id' => $value['Pagamento']['id'],
	 					'pagamento'=> $value['Pagamento']['tipo'],
						'total'=> '',
						'taxa'=>''
	 				);
	 		}
 		}
 		$hasVendas= false;
 		if(!empty($vendas)){
 			$hasVendas=true;
	 		foreach ($vendas as $venda) {
	 			
	 			foreach ($venda['Vendaspagamento'] as $pagamento) {
	 				if(isset($totaisPorPagamento[$pagamento['pagamento_id']])){
	 					$totaisPorPagamento[$pagamento['pagamento_id']]['total'] += $pagamento['valor'];
	 					$totaisPorPagamento[$pagamento['pagamento_id']]['taxa'] += $pagamento['taxa'];
	 					//$totaisPorPagamento[$pagamento['pagamento_id']]['taxa'] += $pagamento['taxa'];
	 				}
	 				//debug($pagamento);
	 			}
	 		}
 		}else{
 				$this->Session->setFlash(__('Houve um erro ao fechar o movimento, não existe vendas no movimento. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
 			return $this->redirect( $this->referer() );
 		}
 		
 		if(!empty($totaisPorPagamento) && $hasVendas== true){
 			
 			if(!empty($fechamentoObj)){
 				foreach ($totaisPorPagamento as $totalFechamento) {
 					$this->Fechamentoiten->create();
 					$this->Fechamentoiten->save(
 						array(
							'pagamento_id'=> $totalFechamento['id'],
							'fechamento_id'=> $fechamentoObj['Fechamento']['id'],
							'pagamentonome'=> $totalFechamento['pagamento'],
							'total'=> $totalFechamento['total'],
							'total_taxa'=>  $totalFechamento['taxa'],
 						)
 					);
	 				
	 			}
	 			$this->Fechamento->save(
					array(
						'id'=> $fechamentoObj['Fechamento']['id'],
						'status'=>2,
					)
				);
	 			$this->Venda->updateAll(
	 				array('Venda.fechamento_id'=> $fechamentoObj['Fechamento']['id']),
	 				array('Venda.fechamento_id is null')
	 			);
 			}


 			
 		}else{
 			$this->Session->setFlash(__('Houve um erro ao fechar o movimento. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
 			return $this->redirect( $this->referer() );
 		}
 		$this->Session->setFlash(__('O fechamento foi executado com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));

 		return $this->redirect( $this->referer() );
 		
	}

}
