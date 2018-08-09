<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
/**
 * Users Controller
 *
 * @property Atendimento $Atendimento
 * @property PaginatorComponent $Paginator
 */
class AtendimentosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','checkbfunc','RequestHandler');
	 public $helpers = array('QrCode');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Atendimento->recursive = 0;
		$this->set('atendimentos', $this->Paginator->paginate());
	}
	
	public function indexmobile() {
	date_default_timezone_set("Brazil/East");
	header("Access-Control-Allow-Origin: *");
		$this->layout ='loadprodutos';
		$cliente= $_GET['clt'];
		$resultados = $this->Atendimento->find('all', array('recursive' => 1,'limit' => 5, 'order' => 'Atendimento.id DESC','conditions' => array('Atendimento.cliente_id' => $cliente)));
		$i=0;
		foreach($resultados as $i => $resultado ){
			$this->checkbfunc->formatDateToView($resultados[$i]['Atendimento']['data']);
			$i++;
		}
		
		$this->set(compact('resultados'));
		$this->set("_serialize", array("resultados"));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		
		$atendimentoAux = $this->Atendimento->find('first', array('conditions' => array('Atendimento.id' => $id)));
		
		$id= $atendimentoAux['Atendimento']['id'];
		
		$codigo = $atendimentoAux['Atendimento']['codigo'];
		
		$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));
		
		$esperaHora = $this->checkbfunc->somaHora($pedido['Pedido']['hora_atendimento'], $pedido['Pedido']['tempo_fila']);
		$horaAtual = date("H:i:s");  
		
		if($horaAtual < $esperaHora){
			
			$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
			$difHora=date('H:i:s', $difHora);
			
			
		}
		
		if(!empty($pedido)){
			$itensdepedidos = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
		}

		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		$options = array('conditions' => array('Atendimento.' . $this->Atendimento->primaryKey => $id));
		$this->set('atendimento', $this->Atendimento->find('first', $options));
		$this->set(compact('codigo','pedido','itensdepedidos','difHora'));
		
		
	}
	
	public function viewmobile($id = null) {
	date_default_timezone_set("Brazil/East");
		$this->layout ='loadprodutos';
		header("Access-Control-Allow-Origin: *");	
		
		
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		
		$atendimentoAux = $this->Atendimento->find('first', array('conditions' => array('Atendimento.id' => $id)));
		
		$id= $atendimentoAux['Atendimento']['id'];
		
		$codigo = $atendimentoAux['Atendimento']['codigo'];
		
		$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));
		
		$esperaHora = $this->checkbfunc->somaHora($pedido['Pedido']['hora_atendimento'], $pedido['Pedido']['tempo_fila']);
		$horaAtual = date("H:i:s");  
		$difHora="00:00:00";
		if($horaAtual < $esperaHora){
			
			$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
			//$difHora=date('H:i:s', $difHora);
			
			
		}
		
		$dif= array('id' => $id, 'difhora' => $difHora);
		$this->Atendimento->save($dif);
		if(!empty($pedido)){
			$itensdepedidos = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
		}

		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		
		$atendimentos = $this->Atendimento->find('first', array('conditions' => array('Atendimento.id' => $id)));
		
		$resultados=$atendimentos;
		
		$resultados['Atendimento']['difhora']=$difHora;
		$this->set(compact('codigo','pedido','itensdepedidos','difHora','resultados'));
		
		
		
		$this->set("_serialize", array("codigo", "pedido", "itensdepedidos", 'difHora', 'resultados'));
		
		/*$this->layout ='loadprodutos';
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		$atendimentoAux = $this->Atendimento->find('first', array('recursive' => 0,'conditions' => array('Atendimento.id' => $id)));
		
		$id= $atendimentoAux['Atendimento']['id'];
		
		$codigo = $atendimentoAux['Atendimento']['codigo'];
		
		$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));
		
		if(!empty($pedido)){
			$esperaHora = $this->checkbfunc->somaHora($pedido['Pedido']['hora_atendimento'], $pedido['Pedido']['tempo_fila']);
			$horaAtual = date("H:i:s");  
			
			if($horaAtual < $esperaHora){
				
				$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
				$difHora=date('H:i:s', $difHora);
				
				
			}
			
			if(!empty($pedido)){
				$itensdepedidos = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
			}

			
			$options = array('conditions' => array('Atendimento.' . $this->Atendimento->primaryKey => $id));
			$this->set('atendimento', $this->Atendimento->find('first', $options));
			$resultados= $atendimentoAux;
			
			if(isset($resultados['Atendimento']['difHora'])){
				$resultados['Atendimento']['difHora']=$difHora;
			}else{
				$difHora='00:00:00';	
				$resultados['Atendimento']['difHora']=$difHora;
			}
			
			
			$this->set(compact('codigo','pedido','itensdepedidos','difHora','resultados'));		
			
			$this->set("_serialize", array("codigo", "pedido", "itensdepedidos", 'difHora', 'resultados'));
			
		}else{
			$resultados=$atendimentoAux;
			$this->set(compact('resultados'));
			$this->set("_serialize", array("resultados"));
		}
		
		
		*/
	}
	

	
	public function itensmobile($id = null) {
	date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *");	
		$this->layout ='loadprodutos';
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		$atendimentoAux = $this->Atendimento->find('first', array('recursive' => 1,'conditions' => array('Atendimento.id' => $id)));
		
		$id= $atendimentoAux['Atendimento']['id'];
		
		$codigo = $atendimentoAux['Atendimento']['codigo'];
		
		$pedido = $this->Pedido->find('first', array('recursive' => 1,'conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));
		
		
	
		if(!empty($pedido)){
			
				
			if(!empty($pedido)){
				$resultados = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
				if(empty($resultados)){
					$resultados="vazio";
				}
				
			}else{	
				$resultados="vazio";
			}

			
			$this->set(compact('resultados'));		
			
			$this->set("_serialize", array("resultados",));
			
		}else{
			$resultados="vazio";
			$this->set(compact('resultados'));
			$this->set("_serialize", array("resultados"));
		}
		
		
		
	}	
	
	public function checaatendimento($id = null) {
	date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *");	
		$this->layout ='loadprodutos';
		$resultados= array('resposta' => 'naoExiste');
		
		
		$atendimentoAux = $this->Atendimento->find('first', array('recursive' => -1,'conditions' => array('Atendimento.codigo' => $id, 'AND' => array('Atendimento.ativo' => 1), 'AND' => array('Atendimento.tipo' => 'INTERNO') )));
	
		if(!empty($atendimentoAux)){
			$resultados =  array('resposta' => 'Existe');
			
		}else{
			$resultados= array('resposta' => 'naoExiste');
		}
		$this->set(compact('resultados'));
		$this->set("_serialize", array("resultados"));
		
	}	
/**
 * atender method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function atender($id = null) {
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		
		$atendimentoAux = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $id)));
		
		$id= $atendimentoAux['Atendimento']['id'];
		
		$codigo = $atendimentoAux['Atendimento']['codigo'];
		
		$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));
		
		$esperaHora = $this->checkbfunc->somaHora($pedido['Pedido']['hora_atendimento'], $pedido['Pedido']['tempo_fila']);
		$horaAtual = date("H:i:s");  
		if($horaAtual < $esperaHora){
			
			$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
			$difHora=date('H:i:s', $difHora);
			
			
		}
		
		if(!empty($pedido)){
			$itensdepedidos = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
		}

		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		$options = array('conditions' => array('Atendimento.' . $this->Atendimento->primaryKey => $id));
		$this->set('atendimento', $this->Atendimento->find('first', $options));
		$this->set(compact('codigo','pedido','itensdepedidos','difHora'));
		
		
	}
/**
 * add method
 *
 * @return void
 */
	public function add() {
	date_default_timezone_set("Brazil/East");
		$this->Atendimento->recursive = 0;
		$this->set('atendimentos', $this->Paginator->paginate());
		
		
		 $codigo="";
		
		if ($this->request->is('post')) {
			$this->Atendimento->create();
			$flag ="FALSE";
			while($flag == "FALSE"){
				$codigo = date('Ymd');
				 $numero = rand(1,1000000);
				 $codigo= $codigo.$numero;
				 $testeCodigo = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));
				
				 if(empty($testeCodigo)){
				 	$flag="TRUE";
					$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo,'tipo' => 'INTERNO');
					if ($this->Atendimento->save($dadosatendimento)) {
						$this->Session->setFlash(__('O atendimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
						
					} else {
						$this->Session->setFlash(__('Houve um erro ao salvar o atendimento. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
					}
					
				 }else{
				 	$flag ="FALSE";
				 }
			}
			
		}
		$this->set(compact('codigo'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		date_default_timezone_set("Brazil/East");
		if (!$this->Atendimento->exists($id)) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Atendimento->save($this->request->data)) {
				$this->Session->setFlash(__('O atendimento foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o atendimento. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Atendimento.' . $this->Atendimento->primaryKey => $id));
			$this->request->data = $this->Atendimento->find('first', $options);
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
	date_default_timezone_set("Brazil/East");
		$this->Atendimento->id = $id;
		if (!$this->Atendimento->exists()) {
			throw new NotFoundException(__('Invalid atendimento'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Atendimento->delete()) {
			$this->Session->setFlash(__('O atendimento foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o atendimento. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
