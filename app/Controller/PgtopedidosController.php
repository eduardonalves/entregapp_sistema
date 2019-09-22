<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Pgtopedidos Controller
 *
 * @property Pgtopedido $Pgtopedido
 * @property PaginatorComponent $Paginator
 */
class PgtopedidosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','checkbfunc');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Pgtopedido->recursive = 0;
		$this->set('pgtopedidos', $this->Paginator->paginate());
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
		if (!$this->Pgtopedido->exists($id)) {
			throw new NotFoundException(__('Invalid pgtopedido'));
		}
		$options = array('recursive' => -1,'conditions' => array('Pgtopedido.' . $this->Pgtopedido->primaryKey => $id));
		$this->set('pgtopedido', $this->Pgtopedido->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {

		$this->request->data['Pgtopedido']['pagamento_id'] =$this->request->data['Pgtopedido']['pg_id'];

		$this->loadModel('Filial');
		$this->layout ='ajaxaddpedido';
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		$this->loadModel('Estado');
		$this->loadModel('Cidad');
		$this->loadModel('Itensdepedido');
		$this->loadModel('Mesa');
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

		$this->Pgtopedido->create();
		$ultimopedido='Erro';
		$this->request->data['Pgtopedido']['valor_total_pago'] = str_replace(',','.',$this->request->data['Pgtopedido']['valor_total_pago']);



			if ($this->request->is(array('post', 'put'))) {

				
				$this->request->data['Pgtopedido']['status']='A';
				$vlComDez = $this->request->data['Pgtopedido']['valor_dez'];
				$vlPgto = $this->request->data['Pgtopedido']['valor'];
				$desc =   $this->request->data['Pgtopedido']['desconto'] ;
				$taxa = $this->request->data['Pgtopedido']['taxa'] ;

				//Faz o cast dos tipos
				$this->request->data['Pgtopedido']['valor_dez']= (float) $vlComDez;
				$this->request->data['Pgtopedido']['valor'] = (float) $vlPgto;
				$this->request->data['Pgtopedido']['desconto']= (float) $desc ;
				$this->request->data['Pgtopedido']['taxa'] =(float) $taxa ;




				$this->request->data['Pgtopedido']['valor_total_pago'] = ($vlPgto-$desc) + $taxa;

				if( $vlPgto !='')
				{
					$requestAux = $this->request->data;
					$requestAux['Pgtopedido']['valor'] = ($vlPgto+ $taxa ) - $desc;
					

					if ($this->Pgtopedido->save($requestAux)) {
						$itenspgAux = $this->request->data['Pgtopedido']['itenspg'];
						$itensPgArray = explode(',', $itenspgAux);
						$mesa = $this->Mesa->find('first', array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Mesa.filial_id'=>$minhasFiliais), array('Mesa.id'=> $this->request->data['Pgtopedido']['mesa_id'])))));
						if(isset($this->request->data['incluirdez']))
						{
							if($this->request->data['incluirdez']== 1){
								//$vlPgto  = ($this->request->data['Pgtopedido']['valor_total_pago'] != '' ? $this->request->data['Pgtopedido']['valor_total_pago']: $this->request->data['Pgtopedido']['valor']);


								$taxaAtual = (float) $mesa['Mesa']['taxa'] + (float) $taxa;

								$descAtual = $mesa['Mesa']['desconto'];
								$desconto = (float) $descAtual + (float) $desc;
								if($taxaAtual > 0)
								{
									if(!empty($mesa)){
										$this->Mesa->save(array(
											'id' => $mesa['Mesa']['id'],
											'taxa' => $taxaAtual,
											'desconto' => $desconto,
										));
									}

								}
							}
						}else{
							$descAtual = $mesa['Mesa']['desconto'];
							$desconto = (float) $descAtual + (float) $desc;
							if(!empty($mesa)){
								$this->Mesa->save(array(
									'id' => $mesa['Mesa']['id'],
									'desconto' => $desconto,
								));
							}
						}
						foreach ($itensPgArray as $key => $value)
						{
							if($value !='')
							{
								$itemPedidoUpdate = array('id' => $value, 'status_pago' => 1, 'desconto' => $desc,'taxa' => $taxa);
								$this->Itensdepedido->save($itemPedidoUpdate);
							}
						}
						//$this->Session->setFlash(__('O pgtopedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
						//return $this->redirect( $this->referer() );

						$ultimopedido='Sucesso';
					} else {
						//$this->Session->setFlash(__('Houve um erro ao salvar o pgtopedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
						$ultimopedido='Erro';
					}
				}else
				{
						$ultimopedido='Erro';
				}


			}
			$this->set(array(
				'ultimopedido' => $ultimopedido,
				'_serialize' => array('ultimopedido')
			));

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
		if (!$this->Pgtopedido->exists($id)) {
			throw new NotFoundException(__('Invalid pgtopedido'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'formas_de_pagamento';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if ($this->request->is(array('post', 'put'))) {
			$this->request->data['Pgtopedido']['valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Pgtopedido']['valor']);
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->request->data['Pgtopedido']['pgtopedido_string'] =$this->checkbfunc->removeDetritos($this->request->data['Pgtopedido']['pgtopedido'] );
			if ($this->Pgtopedido->save($this->request->data)) {
				$this->Session->setFlash(__('A forma de pgtopedido foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect( $this->referer() );
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a forma de pgtopedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {

			$this->loadModel('Estado');
			$this->loadModel('Cidad');
			$pgtopedido = $this->Pgtopedido->find('first',array('recursive'=>-1, 'conditions'=> array('Pgtopedido.id'=> $id)));
			$pgtopedido['Pgtopedido']['valor'] =number_format($pgtopedido['Pgtopedido']['valor'] ,2,',','.');
			$cidads = $this->Cidad->find('list', array('recursive'=> -1,'fields'=> array('Cidad.id','Cidad.cidade') ,'conditions'=> array('Cidad.filial_id'=> $pgtopedido['Pgtopedido']['filial_id'] )));
			$estados = $this->Estado->find('list', array('recursive'=> -1,'fields'=> array('Estado.id','Estado.estado') ,'conditions'=> array('Estado.filial_id'=> $pgtopedido['Pgtopedido']['filial_id'] )));
			$this->set(compact('estados','cidads'));
			//$options = array('recursive' => -1, 'conditions' => array('Pgtopedido.' . $this->Pgtopedido->primaryKey => $id));
			$this->request->data = $pgtopedido;
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
		$this->Pgtopedido->id = $id;
		if (!$this->Pgtopedido->exists()) {
			throw new NotFoundException(__('Invalid pgtopedido'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Pgtopedido->delete()) {
			$this->Session->setFlash(__('A forma de pgtopedido foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a forma de pgtopedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}}
