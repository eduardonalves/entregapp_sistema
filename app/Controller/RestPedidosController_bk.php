<?php
App::uses('AppController', 'Controller');
class RestPedidosController extends AppController {
    public $uses = array('Pedido');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');
 
  	public function addmobile($codigo = null) {
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");
		
			
		
		if ($this->request->is('post')) {
			$codigo=$this->request->data['Pedido']['a'];
			$cliente = $this->request->data['Pedido']['cliente_id'];
			$this->loadModel('Produto');
			$this->loadModel('Atendimento');
			$this->loadModel('Itensdepedido');
			
			$ultimopedido="";
			if($codigo=="entrega"){
				$this->loadModel('Atendimento');
				$codigo="";
				$this->Atendimento->create();
				$flag ="FALSE";
				while($flag == "FALSE"){
					$codigo = date('Ymd');
					 $numero = rand(1,1000000);
					 $codigo= $codigo.$numero;
					 $codigo= "ENTR".$codigo;
					 $testeCodigo = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));
					
					 if(empty($testeCodigo)){
						$flag="TRUE";
						$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo, 'tipo' => 'EXTERNO', 'cliente_id' => $cliente);
						if ($this->Atendimento->save($dadosatendimento)) {
							$ultimoAtend = $this->Atendimento->find('first', array('order' => array('Atendimento.id' => 'desc'), 'recursive' =>1));
							$codigo=$ultimoAtend['Atendimento']['codigo'];
							$atendimento=$ultimoAtend;
						} else {
							//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
						}
						
					 }else{
						$flag ="FALSE";
					 }
					 
				}
			
			}else{
				$atendimento = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));
				
			}
				
			
				
				$this->Pedido->create();
				
				
				
				
				
				//if($this->request->is('ajax')){
					
					
					
					if(!empty($atendimento) && $atendimento['Atendimento']['usado'] != 1 ){
						
						$updateAtend = array('id' => $atendimento['Atendimento']['id'], 'cliente_id' => $cliente);
						$this->Atendimento->create();
						$this->Atendimento->save($updateAtend);
						
						$this->request->data['Pedido']['data']=date('Y-m-d');
						$this->request->data['Pedido']['status']="Em Aberto";
						$i=0;
						
						$total=0;
						$tempoPreparo="00:00:00";
						foreach($this->request->data['Itensdepedido'] as $iten ){
							
							$produto =$this->Produto->find('first', array('conditions' => array('Produto.id' => $iten['produto_id'])));	
							$this->request->data['Itensdepedido'][$i]['valor_unit']= $produto['Produto']['preco_venda'];
							$this->request->data['Itensdepedido'][$i]['valor_total'] = $produto['Produto']['preco_venda'] * $iten['qtde'];
							$this->request->data['Itensdepedido'][$i]['produto_id'] = $iten['produto_id'];
							$this->request->data['Itensdepedido'][$i]['qtde'] = $iten['qtde'];
							
							$total += $this->request->data['Itensdepedido'][$i]['valor_total'];
							$tempoPreparo = $this->checkbfunc->somaHora($tempoPreparo, $produto['Produto']['tempo_preparo']);
							
							
							//debug($tempoPreparo);
							
							$i = $i +1;
						}
						
						
						
						
						$pedidoExistente= $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimento['Atendimento']['id'])));
						$this->request->data['Pedido']['atendimento_id'] = $atendimento['Atendimento']['id'];
						
						
						$tempoEstimado="00:00:00";
						if(!empty($pedidoExistente)){
							if($pedidoExistente['Pedido']['statuspreparo']==1){
								$tempoEstimado = $this->checkbfunc->somaHora($tempoEstimado, $pedidoExistente['Pedido']['tempo_estimado']);  
							}else{
								$tempoEstimado = "00:00:00";
							}
							$this->request->data['Pedido']['valor'] = $total + $pedidoExistente['Pedido']['valor'];	
							
						}else{
							$this->request->data['Pedido']['valor'] = $total;
							$tempoEstimado = "00:00:00";
						}
						
						
						
						$pedidosAguardandos= $this->Pedido->find('all', array('recursive' => -1,'conditions' => array('Pedido.statuspreparo' => 1)));
						$tempoFila="00:00:00";
						if(!empty($pedidosAguardandos)){
							foreach($pedidosAguardandos as $pedidosAguardando){
								
									$tempoFila =  $this->checkbfunc->somaHora($tempoFila, $pedidosAguardando['Pedido']['tempo_estimado']);
								
								
							}
						}
						$horaAtendimento =  date("H:i:s");  
						
						$tempoVisualizacao = "00:00:50";
						
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
						
						
						$tempoEstAux = $this->checkbfunc->somaHora($tempoVisualizacao, $tempoEntrega);
						$tempoEst= $this->checkbfunc->somaHora($tempoEstAux,$tempoPreparo); 
						$tempoTotalFila = $this->checkbfunc->somaHora($tempoEst,$tempoFila);
						
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
							
							//debug($this->request->data);
							
							$this->request->data['Pedido']['id']= $pedidoExistente['Pedido']['id'];
							$this->Pedido->save($this->request->data['Pedido']);
							if ($this->Itensdepedido->saveAll($this->request->data['Itensdepedido'])){
							
								//debug($this->request->data);
								$ultimopedido = $pedidoExistente;
								//$this->set(compact('ultimopedido'));
								if(! $this->request->is('ajax'))
								{
									
									//return $this->redirect(array('controller' => 'atendimentos','action' => 'view',$atendimento['Atendimento']['codigo']));
								}
								
								if( $this->request->is('ajax'))
								{
									$this->layout ='ajaxaddpedido';
								}
							}else{
								$ultimoPedido = $this->request->data;
								//$this->Session->setFlash(__('The pedido could not be saved. Please, try again.'));
							}
						}else{
							if ($this->Pedido->saveAll($this->request->data)){
							
								//debug($this->request->data);
								$ultimopedido = $this->Pedido->find('first', array('order' => array('Pedido.id' => 'desc'), 'recursive' => 1));
								//$this->set(compact('ultimopedido'));
								if(! $this->request->is('ajax'))
								{
									//return $this->redirect(array('controller' => 'atendimentos','action' => 'view',$atendimento['Atendimento']['codigo']));
								}
								
								if( $this->request->is('ajax'))
								{
									$this->layout ='ajaxaddpedido';
								}
							}else{
								$ultimoPedido = $this->request->data;
								//$this->Session->setFlash(__('The pedido could not be saved. Please, try again.'));
							}
						}
					}
				//}else{
					
					//$this->Session->setFlash(__('Pedido Inválido. Please, try again.'));
					
				//}
		}
		
		
		$resultados=$ultimopedido;
		//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
		//$this->set("_serialize", array("resultados"));
		
		 $this->set(array(
            'resultados' => $resultados,
            '_serialize' => array('resultados')
        ));
	}
} 