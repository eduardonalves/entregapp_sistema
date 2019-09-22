<?php
App::uses('AppController', 'Controller');

App::import('Vendor', 'autoload.inc');
App::import(
    'Vendor',
    'Moip',
    array('file' => 'lib' . DS . 'Moip.php')
);
App::import(
    'Vendor',
    'MoipClient',
    array('file' => 'lib' . DS . 'MoipClient.php')
);
App::import(
    'Vendor',
    'MoipStatus',
    array('file' => 'lib' . DS . 'MoipStatus.php')
);

App::import('Controller', 'Empresas');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Roteiros');
App::import('Controller', 'Produtos');
class RestPedidosController extends AppController {
    public $uses = array('Pedido');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


/**
* Função para gerar senhas aleatórias
*
* @author    Thiago Belem <contato@thiagobelem.net>
*
* @param integer $tamanho Tamanho da senha a ser gerada
* @param boolean $maiusculas Se terá letras maiúsculas
* @param boolean $numeros Se terá números
* @param boolean $simbolos Se terá símbolos
*
* @return string A senha gerada
*/

	public function checkToken(&$clienteId,&$token){
		$this->loadModel('Cliente');
		$cliente = $this->Cliente->find('first', array('conditions' => array('AND' => array(array('Cliente.id' => $clienteId), array('Cliente.token' => $token), array('Cliente.ativo' => 1)))));

		if(!empty($cliente)){
			$resposta = "OK";
			return $resposta;
		}else{
			$resposta = "NOK";
			return $resposta;
			$clienteUp= array('id'=> $clienteId, 'ativo' => 0);
			$this->Cliente->create();
			$this->Cliente->save($clienteUp);
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


/*// Gera uma senha com 10 carecteres: letras (min e mai), números
$senha = geraSenha(10);
// gfUgF3e5m7

// Gera uma senha com 9 carecteres: letras (min e mai)
$senha = geraSenha(9, true, false);
// BJnCYupsN

// Gera uma senha com 6 carecteres: letras minúsculas e números
$senha = geraSenha(6, false, true);
// sowz0g

// Gera uma senha com 15 carecteres de números, letras e símbolos
$senha = geraSenha(15, true, true, true);
// fnwX@dGO7P0!iWM
	*/
	public function pgtomoip($codigo= null){


		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *");

		//$codigo= $_GET['cd'];
		//$resultados = $this->Atendimento->find('all', array('recursive' => 1,'limit' => 5, 'order' => 'Atendimento.id DESC','conditions' => array('Atendimento.cliente_id' => $cliente)));
		$this->loadModel('Salt');
		$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));

		if ($this->request->is(array('post'))) {
			if($this->request->data['salt'] == $salt){
				$clt = $this->request->data['cliente_id'];
				$token =$this->request->data['token'];
				$resp =$this->checkToken($clt, $token);
				$Empresa = new EmpresasController;
				$respAux = $Empresa->empresaAtiva();

				if($respAux == 1){

				}else{
					$resp='NOK';
				}
				if($resp =='OK'){
					if($this->request->data['telefone'] ==''){
						$this->request->data['telefone'] = $this->request->data['celular'];
					}

					$this->loadModel('Pgmoip');
					$codigo="";
					$this->Pgmoip->create();
					$flag ="FALSE";
					while($flag == "FALSE"){
						$codigo = date('Ymd');
						 $numero = rand(1,10000);
						 $codigo= $codigo.$numero;
						 //$codigo = geraSenha(5, false, true);
						 $testeCodigo = $this->Pgmoip->find('first', array('conditions' => array('Pgmoip.idoperacao' => $codigo)));

						 if(empty($testeCodigo)){
							$flag="TRUE";
							$dadosatendimento = array('idoperacao' => $codigo);
							if ($this->Pgmoip->save($dadosatendimento)) {
								$ultimoPgmoip = $this->Pgmoip->find('first', array('order' => array('Pgmoip.id' => 'desc'), 'recursive' => -1));
								$pgmoip_id = $ultimoPgmoip['Pgmoip']['id'];

							} else {
								//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
							}

						 }else{
							$flag ="FALSE";
						 }

					}

					$moip = new Moip();
					$moip->setEnvironment('test');
					$moip->setCredential(array(
						'key' => 'IRDUU8TY5OWVC5RHJFJEVIYBSDPKJZOUWFS0SCYL',
						'token' => 'WZ7SMWMNCM00KTW9VO0AMOWQSJF7SHLS'
					));

					$moip->setUniqueID($codigo);
					$moip->setValue($this->request->data['cliente_id']);
					$moip->setReason('comprade lanche');

					$moip->setPayer(array('name' => $this->request->data['nome'],
						'email' => $this->request->data['email'],
						'payerId' => $this->request->data['cliente_id'],
						'billingAddress' => array('address' => $this->request->data['logradouro'],
							'number' => $this->request->data['numero'],
							'complement' => $this->request->data['complemento'],
							'city' => $this->request->data['cidade'],
							'neighborhood' => '',
							'state' => $this->request->data['uf'],
							'country' => 'BRA',
							'zipCode' => $this->request->data['cep'],
							'phone' => $this->request->data['telefone'])));
					$moip->validate('Identification');

					$moip->send();


					$response = $moip->getAnswer()->response;
					$error= $moip->getAnswer()->error;
					$token = $moip->getAnswer()->token;
					$payment_url = $moip->getAnswer()->payment_url;

					$resultados = array('resposta' => $response, 'erro' => $error, 'token' => $token, 'url' => $payment_url, 'pgmoip_id' => $pgmoip_id);
					//$resultados = $this->request->data;
					$this->set(array(
						'resultados' => $resultados,
						'_serialize' => array('resultados')
					));
				}else{
					return $this->redirect(array('controller' =>'users' ,'action' => 'login'));
				}
			}
		}
	}
	public function calculafrete(){
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Filial');

		$resultados= array();
		if(isset($_GET['fp'])){
			$Empresa = new EmpresasController;
			$resultados=$Empresa->verificaValorFrete($this->request->data['Pedido']['filial_id'],$this->checkbfunc->removeDetritos($this->request->data['Pedido']['entrega_outro_bairro']), $this->request->data['Pedido']['entrega_outro_cidade'], $this->request->data['Pedido']['entrega_outro_estado']);
		}
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}
	public function getLocalidadePedidos(){
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set("Brazil/East");

		$this->loadModel('Bairro');
		$this->loadModel('Cidad');
		$this->loadModel('Filial');

		$resultados= array();
		$param=$_GET['p'];
		$filialPadrao =$_GET['fp'];
		if(isset($_GET['c'])){
			$cidade = $_GET['c'];
		}

		switch ($param) {
			case 'b': //bairro
				$resultados = $this->Bairro->find('all', array('recursive'=> -1, 'conditions'  => array('AND' => array(array('Bairro.filial_id' => $filialPadrao),array('Bairro.cidad_id' => $cidade),array('Bairro.ativo' => true)))));
				break;
			case 'c': //bairro
				$resultados = $this->Cidad->find('all', array('recursive'=> -1, 'conditions'  => array('AND'=> array(array('Cidad.filial_id' => $filialPadrao), array('Cidad.ativo' => true)))));
				break;

			default:
				# code...
				break;
		}

		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}

	public function statusloja(){
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Filial');
		$resultados= array();
		if(isset($_GET['fp'])){

			$resultados = $this->Filial->find('first', array('recursive'=> -1 ,'fields'=> array('status_abertura','tempo_atendimento'), 'conditions'=> array('Filial.id'=> $_GET['fp'])));
		}
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}
  	public function addmobile($codigo = null) {
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set("Brazil/East");



		if ($this->request->is('post')) {


			$this->loadModel('Salt');
			$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
			$codigo="entrega";
			if($this->request->data['Pedido']['salt'] == $salt['Salt']['salt']){


				$codigo=$this->request->data['Pedido']['a'];
				$cliente = $this->request->data['Pedido']['cliente_id'];
				$this->loadModel('Produto');
				$this->loadModel('Atendimento');
				$this->loadModel('Itensdepedido');


				$clt = $this->request->data['Pedido']['cliente_id'];
				$token =$this->request->data['Pedido']['token'];
				$resp =$this->checkToken($clt, $token);


				$Empresa = new EmpresasController;
				$respAux = $Empresa->empresaIdAtiva($this->request->data['Pedido']['empresa_id']);
				$respAuxFilial =$Empresa->filialIdAtiva($this->request->data['Pedido']['filial_id']);


				$this->request->data['Pedido']['data']=date('Y-m-d');
				$this->request->data['Pedido']['status']="Em Aberto";

				$this->request->data['Pedido']['status_pagamento']="Pendente";
				$this->request->data['Pedido']['entrega_valor']=$this->checkbfunc->converterMoedaToBD($this->request->data['Pedido']['entrega_valor']);
				$userid = $this->Session->read('Auth.User.id');
				$this->request->data['Pedido']['user_id']=$userid;

				$this->request->data['Pedido']['origem']="Aplicativo";
				$clt = $this->request->data['Pedido']['cliente_id'];
				if($this->request->data['Pedido']['trocovalor']==''){
					unset($this->request->data['Pedido']['trocovalor']);
					$this->request->data['Pedido']['trocoresposta']='Não';
				}

				if($respAux == 1){
					$resp='OK';
				}else{
					$resp='NOK';
				}
				if($respAuxFilial == 1){
					$resp='OK';
				}else{
					$resp='NOK';
				}

				if($resp =='OK'){
					$ultimopedido="";

					if($codigo=="entrega"){

						$this->loadModel('Atendimento');
						$codigo="";
						$this->Atendimento->create();
						$flag ="FALSE";
						$this->loadModel('Empresa');
						$this->loadModel('Filial');
						$empresa=$this->Filial->find('first', array('recursive'=> -1, 'conditions' => array('Filial.id' => $this->request->data['Pedido']['filial_id'])));
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
								$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo, 'tipo' => 'EXTERNO', 'cliente_id' => $clt, 'lat' => $lat, 'lng' => $lng, 'hora' =>  date("H:i:s"), 'data' => date('Y-m-d'), 'empresa_id'=> $this->request->data['Pedido']['empresa_id'],'filial_id'=> $this->request->data['Pedido']['filial_id']);

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

					}else{
						$atendimento = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));

					}



					$this->Pedido->create();


					$this->request->data['Pedido']['data']=date('Y-m-d');


					$total=0;
					$tempoPreparo="00:00:00";


					$itens = $this->request->data['Itensdepedido'];

					$i=0;
					$j=0;
					$k=0;
					foreach( $itens as $key => $iten ){

						$produto =$this->Produto->find('first', array('rescusive'=> -1, 'conditions' => array('Produto.id' => $iten['produto_id'])));
						if(!empty($produto)){
							if($produto['Produto']['composto']==1){
								if(isset( $this->request->data['Itensdepedido'][$key]['compostoum_id'])){
									if($this->request->data['Itensdepedido'][$key]['compostoum_id'] != ''){
										$produto1 =$this->Produto->find('first', array('rescusive'=> -1, 'conditions' => array('Produto.id' => $this->request->data['Itensdepedido'][$key]['compostoum_id'])));
										$produto2 =$this->Produto->find('first', array('rescusive'=> -1, 'conditions' => array('Produto.id' => $this->request->data['Itensdepedido'][$key]['compostodois_id'])));
										if(!empty($produto1) && !empty($produto2) ){
											if($produto1['Produto']['preco_venda'] > $produto2['Produto']['preco_venda']){
												$produto['Produto']['preco_venda']= $produto1['Produto']['preco_venda'];
											}else{
												$produto['Produto']['preco_venda']= $produto2['Produto']['preco_venda'];
											}
											$this->request->data['Itensdepedido'][$key]['composto_nomeum']= $produto1['Produto']['nome'];
											$this->request->data['Itensdepedido'][$key]['composto_nomedois']= $produto2['Produto']['nome'];
											//$this->request->data['Pedido']['obs'] .= " Produto ".$produto['Produto']['nome']." composto de ".$produto1['Produto']['nome']." e de ".$produto2['Produto']['nome'];

										}
									}
								}


							}
							//$this->request->data['Itensdepedido'][$key]['valor_unit']= $produto['Produto']['preco_venda'];
							//$this->request->data['Itensdepedido'][$key]['valor_total'] = $produto['Produto']['preco_venda'] * $iten['qtde'];
							$this->request->data['Itensdepedido'][$key]['produto_id'] = $iten['produto_id'];
							$this->request->data['Itensdepedido'][$key]['qtde'] = $iten['qtde'];
							$this->request->data['Itensdepedido'][$key]['statuspreparo'] = 1;
							$this->request->data['Itensdepedido'][$key]['empresa_id'] = $this->request->data['Pedido']['empresa_id'];
							$this->request->data['Itensdepedido'][$key]['filial_id'] = $this->request->data['Pedido']['filial_id'];
							$total += $iten['valor_total'];
							$Estoque = new ProdutosController;
							$Estoque->diminueEstoque($iten['produto_id'], $iten['qtde']);


							for ($k=0; $k <  $iten['qtde']; $k++) {
								$tempoPreparo = $this->checkbfunc->somaHora($tempoPreparo,$produto['Produto']['tempo_preparo']);
							}

						}

						$j++;
					}

					$this->request->data['Pedido']['valor']=$total + $this->request->data['Pedido']['entrega_valor'];



					$horaAtendimento =  date("H:i:s");

					$tempoVisualizacao = "00:01:00";
					//$tempoFila = $this->calculaFilaProdutos();
					$this->loadModel('Filial');
					$estaFilial= $this->Filial->find('first', array('recursive'=>-1, 'conditions'=> array('Filial.id'=> $this->request->data['Pedido']['filial_id'])));
					$tempoFila = $estaFilial['Filial']['tempo_atendimento'];
					$tempoTotalFila=$tempoFila;
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

					$posicaoFila= $this->Pedido->find('count', array('recursive' => -1,'conditions' => array('AND'=> array(array('Pedido.statuspreparo' => 1), array('Pedido.filial_id' => $this->request->data['Pedido']['filial_id'])))));

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


						unset($this->request->data['Pedido']['user_id']);
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
						}
					}




				}

				//}else{

					//$this->Session->setFlash(__('Pedido Inválido. Please, try again.'));

				//}
			}
		}

		$resultados=$ultimopedido;
		//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
		//$this->set("_serialize", array("resultados"));

		 $this->set(array(
		            'resultados' => $resultados,
		            '_serialize' => array('resultados')
		        ));
	}


	public function calculaFilaProdutos(){
		header("Access-Control-Allow-Origin: *");
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
						if($qtdePreparo != null){
							$qteFila = $qteFilaProduto[0]['Itensdepedido']['totalprod'];
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

	public function calculaFilaProduto(&$id){
		header("Access-Control-Allow-Origin: *");
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
						if($qtdePreparo != null){
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
	}
	public function avalpedidomobile() {
		header("Access-Control-Allow-Origin: *");
		date_default_timezone_set("Brazil/East");

		if ($this->request->is('get')) {

			$this->layout ='ajaxaddpedido';
			$id = $_GET['id'];
			$nota = $_GET['nota'];
			$clt =  $_GET['b'];
			$token = $_GET['c'];
			$resp =$this->checkToken($clt, $token);
			$Empresa = new EmpresasController;
			$respAux = $Empresa->empresaAtiva();

			if($respAux == 1){

			}else{
				$resp='NOK';
			}
			if($resp =='OK'){
				$updatePedido = array('id' => $id, 'avaliacao' => $nota);
				$this->Pedido->create();
				if($this->Pedido->save($updatePedido)){
					$resultados= "OK";
					//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
					//$this->set("_serialize", array("resultados"));

					 $this->set(array(
						'resultados' => $resultados,
						'_serialize' => array('resultados')
					));
				}else{

					$resultados= "Falha";
					//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
					//$this->set("_serialize", array("resultados"));

					 $this->set(array(
						'resultados' => $resultados,
						'_serialize' => array('resultados')
					));

				}
			}

		}
	}
}