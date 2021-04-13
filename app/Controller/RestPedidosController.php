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
App::import(
	'Vendor',
	'PagSeguroLibrary',
	array('file' => 'PagSeguroLibrary' . DS . 'PagSeguroLibrary.php')
);
App::import('Controller', 'Empresas');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Roteiros');
App::import('Controller', 'Produtos');
class RestPedidosController extends AppController
{
	public $uses = array('Pedido');
	public $helpers = array('Html', 'Form');
	public $components = array('RequestHandler', 'checkbfunc');


	/**
	 * Fun��o para gerar senhas aleat�rias
	 *
	 * @author    Thiago Belem <contato@thiagobelem.net>
	 *
	 * @param integer $tamanho Tamanho da senha a ser gerada
	 * @param boolean $maiusculas Se ter� letras mai�sculas
	 * @param boolean $numeros Se ter� n�meros
	 * @param boolean $simbolos Se ter� s�mbolos
	 *
	 * @return string A senha gerada
	 */

	public function checkToken(&$clienteId, &$token)
	{
		$this->loadModel('Cliente');
		$cliente = $this->Cliente->find('first', array('conditions' => array('AND' => array(array('Cliente.id' => $clienteId), array('Cliente.token' => $token), array('Cliente.ativo' => 1)))));

		if (!empty($cliente)) {
			$resposta = "OK";
			return $resposta;
		} else {
			$resposta = "NOK";
			return $resposta;
			$clienteUp = array('id' => $clienteId, 'ativo' => 0);
			$this->Cliente->create();
			$this->Cliente->save($clienteUp);
		}
	}
	function converteparasegundos($hora)
	{

		$horas = substr($hora, 0, -6);
		$minutos = substr($hora, -5, 2);
		$segundos = substr($hora, -2);

		return $horas * 3600 + $minutos * 60 + $segundos;
	}

	function converterparahoras($segundos)
	{

		$horas = floor($segundos / 3600);
		$segundos -= $horas * 3600;
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
			$retorno .= $caracteres[$rand - 1];
		}
		return $retorno;
	}


	/*// Gera uma senha com 10 carecteres: letras (min e mai), n�meros
$senha = geraSenha(10);
// gfUgF3e5m7

// Gera uma senha com 9 carecteres: letras (min e mai)
$senha = geraSenha(9, true, false);
// BJnCYupsN

// Gera uma senha com 6 carecteres: letras min�sculas e n�meros
$senha = geraSenha(6, false, true);
// sowz0g

// Gera uma senha com 15 carecteres de n�meros, letras e s�mbolos
$senha = geraSenha(15, true, true, true);
// fnwX@dGO7P0!iWM
	*/
	public function pgtomoip($codigo = null)
	{


		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

		//$codigo= $_GET['cd'];
		//$resultados = $this->Atendimento->find('all', array('recursive' => 1,'limit' => 5, 'order' => 'Atendimento.id DESC','conditions' => array('Atendimento.cliente_id' => $cliente)));
		$this->loadModel('Salt');
		$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));

		if ($this->request->is(array('post'))) {
			if ($this->request->data['salt'] == $salt) {
				$clt = $this->request->data['cliente_id'];
				$token = $this->request->data['token'];
				$resp = $this->checkToken($clt, $token);
				$Empresa = new EmpresasController;
				$respAux = $Empresa->empresaAtiva();

				if ($respAux == 1) {
				} else {
					$resp = 'NOK';
				}
				if ($resp == 'OK') {
					if ($this->request->data['telefone'] == '') {
						$this->request->data['telefone'] = $this->request->data['celular'];
					}

					$this->loadModel('Pgmoip');
					$codigo = "";
					$this->Pgmoip->create();
					$flag = "FALSE";
					while ($flag == "FALSE") {
						$codigo = date('Ymd');
						$numero = rand(1, 10000);
						$codigo = $codigo . $numero;
						//$codigo = geraSenha(5, false, true);
						$testeCodigo = $this->Pgmoip->find('first', array('conditions' => array('Pgmoip.idoperacao' => $codigo)));

						if (empty($testeCodigo)) {
							$flag = "TRUE";
							$dadosatendimento = array('idoperacao' => $codigo);
							if ($this->Pgmoip->save($dadosatendimento)) {
								$ultimoPgmoip = $this->Pgmoip->find('first', array('order' => array('Pgmoip.id' => 'desc'), 'recursive' => -1));
								$pgmoip_id = $ultimoPgmoip['Pgmoip']['id'];
							} else {
								//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
							}
						} else {
							$flag = "FALSE";
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

					$moip->setPayer(array(
						'name' => $this->request->data['nome'],
						'email' => $this->request->data['email'],
						'payerId' => $this->request->data['cliente_id'],
						'billingAddress' => array(
							'address' => $this->request->data['logradouro'],
							'number' => $this->request->data['numero'],
							'complement' => $this->request->data['complemento'],
							'city' => $this->request->data['cidade'],
							'neighborhood' => '',
							'state' => $this->request->data['uf'],
							'country' => 'BRA',
							'zipCode' => $this->request->data['cep'],
							'phone' => $this->request->data['telefone']
						)
					));
					$moip->validate('Identification');

					$moip->send();


					$response = $moip->getAnswer()->response;
					$error = $moip->getAnswer()->error;
					$token = $moip->getAnswer()->token;
					$payment_url = $moip->getAnswer()->payment_url;

					$resultados = array('resposta' => $response, 'erro' => $error, 'token' => $token, 'url' => $payment_url, 'pgmoip_id' => $pgmoip_id);
					//$resultados = $this->request->data;
					$this->set(array(
						'resultados' => $resultados,
						'_serialize' => array('resultados')
					));
				} else {
					return $this->redirect(array('controller' => 'users', 'action' => 'login'));
				}
			}
		}
	}
	public function calculafrete()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Filial');

		$resultados = array();
		if (isset($_GET['fp'])) {
			$Empresa = new EmpresasController;
			$resultados = $Empresa->verificaValorFrete($this->request->data['Pedido']['filial_id'], $this->checkbfunc->removeDetritos($this->request->data['Pedido']['entrega_outro_bairro']), $this->request->data['Pedido']['entrega_outro_cidade'], $this->request->data['Pedido']['entrega_outro_estado']);
		}
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}
	public function getLocalidadePedidos()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		$this->loadModel('Bairro');
		$this->loadModel('Cidad');
		$this->loadModel('Filial');
		$this->loadModel('Estado');

		$resultados = array();
		$param = $_GET['p'];
		$filialPadrao = $_GET['fp'];
		$estado = '';
		if (isset($_GET['c'])) {
			$cidade = $_GET['c'];
		}

		if (isset($_GET['e'])) {
			$estado = $_GET['e'];
		}


		switch ($param) {
			case 'b': //bairro
				$resultados = $this->Bairro->find('all', array('recursive' => -1, 'conditions'  => array('AND' => array(array('Bairro.filial_id' => $filialPadrao), array('Bairro.cidad_id' => $cidade), array('Bairro.ativo' => true)))));
				break;
			case 'c': //bairro
				$resultados = $this->Cidad->find('all', array('recursive' => -1, 'conditions'  => array('AND' => array(array('Cidad.filial_id' => $filialPadrao), array('Cidad.ativo' => true), array('Cidad.estado_id' => $estado)))));
				break;
			case 'e': //bairro
				$resultados = $this->Estado->find('all', array('recursive' => -1, 'conditions'  => array('AND' => array(array('Estado.filial_id' => $filialPadrao), array('Estado.ativo' => true)))));
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

	public function statusloja()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Filial');
		$resultados = array();
		if (isset($_GET['fp'])) {

			$resultados = $this->Filial->find('first', array('recursive' => -1, 'fields' => array('status_abertura', 'tempo_atendimento'), 'conditions' => array('Filial.id' => $_GET['fp'])));
		}
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}
	public function redirecionarpagseguro()
	{
		# code...
		$this->layout = 'ajaxaddpedido';
	}
	public function pagseguromobile()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		$this->loadModel('Assinatura');
		$hasAssinatura = $this->Assinatura->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'Assinatura.Id_pagseguro' => $_GET['ref']
			)
		));
		$this->layout = 'ajaxaddpedido';
		if (empty($hasAssinatura)) {
			$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/pre-approvals/request';
			$data['email'] = 'eduardonalves@gmail.com';
			$data['token'] = '';
			$data['currency'] = 'BRL';
			$data['reference'] = '' . $_GET['ref'] . '';
			$data['senderName'] = 'Eduardo Nascimento Alves';
			$data['senderEmail'] = 'eduardonalves@live.com';
			//$data['senderAreaCode'] = '55';
			//$data['senderPhone'] = '21 3765-3880';
			//$data['shippingAddressStreet'] = "Avenida Brasil";
			//$data['shippingAddressNumber'] = "1659";
			//$data['shippingAddressPostalCode'] = "21745-310";
			//$data['shippingAddressCity'] = "Mesquita";
			//$data['shippingAddressState'] = "RJ";
			//$data['shippingAddressCountry'] = 'BRA';
			$data['redirectURL'] = 'http://seusite.com.br/perfil?pagseguro-ok';
			$data['preApprovalCharge'] = 'auto';
			$data['preApprovalName'] = 'Assinatura mensal';
			$data['preApprovalDetails'] = 'Cobrança de valor mensal para assinatura';
			$data['preApprovalAmountPerPayment'] = 29.99;
			$data['preApprovalPeriod'] = 'MONTHLY';
			//$data['preApprovalFinalDate'] = '2020-10-17T19:20:30.45+01:00';
			//$data['preApprovalMaxTotalAmount'] = '1000000.00';
			$data['reviewURL'] = 'http://seusite.com.br.com.br/planos';


			$data = http_build_query($data);
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$xml = curl_exec($curl);

			if ($xml == 'Unauthorized') {
				echo "Unauthorized";
				exit();
			}
			curl_close($curl);
			$xml = simplexml_load_string($xml);
			if (count($xml->error) > 0) {
				echo "XML ERRO";
				exit();
			}

			//echo 'Location: https://sandbox.pagseguro.uol.com.br/v2/pre-approvals/request.html?code='.$xml->code;
			//die();
			//header('Location: https://pagseguro.uol.com.br/v2/pre-approvals/request.html?code='.$xml->code);
			$this->Assinatura->save(array(
				'tipo' => 'PAGSEGURO',
				'Id_pagseguro' => $_GET['ref'],
				'codigo_pag' => $xml->code,
				'status' => 'Novo'
			));
			$this->set(array(
				'ultimopedido' => $xml->code,
				'_serialize' => array('ultimopedido')
			));
		} else {
			$result = 'Existe';
			$this->set(array(
				'ultimopedido' => $result,
				'_serialize' => array('ultimopedido')
			));
		}



		//die();



	}
	public function addmobile($codigo = null)
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");





		if ($this->request->is('post')) {


			$this->loadModel('Salt');
			if (!is_numeric($this->request->data['Pedido']['cliente_id'])) {
				$this->request->data['Pedido']['cliente_id'] = 0;
			}
			$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
			$codigo = "entrega";
			if ($this->request->data['Pedido']['salt'] == $salt['Salt']['salt']) {


				$codigo = $this->request->data['Pedido']['a'];
				$cliente = $this->request->data['Pedido']['cliente_id'];
				$this->loadModel('Produto');
				$this->loadModel('Atendimento');
				$this->loadModel('Itensdepedido');
				$this->loadModel('Fechamento');
				$this->loadModel('Filial');

				$fechamentoObj = $this->Fechamento->find(
					'first',
					array(
						'order' => array('Fechamento.id' => 'desc'),
						'conditions' => array(
							'Fechamento.filial_id' => $this->request->data['Pedido']['filial_id'],
							'status' => 1
						)
					)
				);
				$ultimopedido = "errolojafechada";

				$minhaFilialAberta = $this->Filial->find('first', array('recursive' => -1, 'fields' => array('status_abertura', 'tempo_atendimento'), 'conditions' => array('Filial.id' => $this->request->data['Pedido']['filial_id'])));

				if ($minhaFilialAberta['Filial']['status_abertura'] != 1) {
					$ultimopedido = "errolojafechada";
				} else {
					$clt = $this->request->data['Pedido']['cliente_id'];
					$token = $this->request->data['Pedido']['token'];
					$resp = $this->checkToken($clt, $token);


					$Empresa = new EmpresasController;
					$respAux = $Empresa->empresaIdAtiva($this->request->data['Pedido']['empresa_id']);
					$respAuxFilial = $Empresa->filialIdAtiva($this->request->data['Pedido']['filial_id']);


					$this->request->data['Pedido']['data'] = date('Y-m-d');
					
					if(!isset($this->request->data['Pedido']['atendente_id'])){
						$this->request->data['Pedido']['status'] = "Em Aberto";
					}else{
						$this->request->data['Pedido']['status'] = "Confirmado";
					}
					

					$this->request->data['Pedido']['status_pagamento'] = "Pendente";
					$this->request->data['Pedido']['entrega_valor'] = $this->checkbfunc->converterMoedaToBD($this->request->data['Pedido']['entrega_valor']);
					//$userid = $this->Session->read('Auth.User.id');
					//$this->request->data['Pedido']['user_id']=$userid;

					$this->request->data['Pedido']['origem'] = "Aplicativo";
					$clt = $this->request->data['Pedido']['cliente_id'];
					if ($this->request->data['Pedido']['trocovalor'] == '') {
						unset($this->request->data['Pedido']['trocovalor']);
					}
					if ($this->request->data['Pedido']['trocoresposta'] == '') {
						$this->request->data['Pedido']['trocoresposta'] = 'Não';
					}

					//Verifico se o cliente está ativo
					$this->loadModel('Cliente');
					$this->loadModel('Atendente');
					
					if(!isset($this->request->data['Pedido']['atendente_id'])){
						$clienteAtivo = $this->Cliente->find('first', 
						array('recursive' => -1, 'conditions' =>
						 array('Cliente.id' => $this->request->data['Pedido']['cliente_id'], 
						 'Cliente.ativo' => 1)));
						$this->loadModel('Estado');
						$this->loadModel('Cidad');
						$this->loadModel('Bairro');
						if(!empty($clienteAtivo)){
							$estadoAtivo = $this->Estado->find('first', array('recursive'=> -1, 'conditions'=> array(
								'id'=> $clienteAtivo['Cliente']['uf'],
								'ativo'=> 1,
							)));

							$cidadeAtivo = $this->Cidad->find('first', array('recursive'=> -1, 'conditions'=> array(
								'id'=> $clienteAtivo['Cliente']['cidade'],
								'ativo'=> 1,
							)));

							$bairroAtivo = $this->Bairro->find('first', array('recursive'=> -1, 'conditions'=> array(
								'id'=> $clienteAtivo['Cliente']['bairro'],
								'ativo'=> 1,
							)));

							if(empty($estadoAtivo) || empty($cidadeAtivo) ||  empty($bairroAtivo) ){
								$clienteAtivo = array();
							}
						}
						 
					}else{
						$clienteAtivo = $this->Atendente->find('first', array('recursive' => -1, 
						'conditions' => array('Atendente.id' => $this->request->data['Pedido']['atendente_id'], 
						'Atendente.ativo' => 1)));
							
					}


					if ($respAux == 1) {
						$resp = 'OK';
					} else {
						$resp = 'NOK';
					}
					if ($respAuxFilial == 1) {
						$resp = 'OK';
					} else {
						$resp = 'NOK';
					}
					
					if (empty($clienteAtivo)) {
						$resp = 'NOK';
						$ultimopedido = "erroUsuarioInativo";
					}

					if ($resp == 'OK') {
						$ultimopedido = "";

						if ($codigo == "entrega") {

							$this->loadModel('Atendimento');
							$codigo = "";
							$this->Atendimento->create();
							$flag = "FALSE";
							$this->loadModel('Empresa');
							
							$empresa = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $this->request->data['Pedido']['filial_id'])));
							while ($flag == "FALSE") {
								//$codigo = date('Ymd');
								//$numero = rand(1,1000000);
								//$codigo= $codigo.$numero;
								//$codigo= "ENTR".$codigo;
								$codigo = $this->geraSenha(8, false, true);
								$testeCodigo = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));

								if (empty($testeCodigo)) {
									$flag = "TRUE";
									//fazer uma fun��o para pegar a lat e lng do estabelecimento
									$lat = $empresa['Filial']['lat'];
									$lng = $empresa['Filial']['lng'];
									if(!isset($this->request->data['Pedido']['atendente_id'])){
										$dadosatendimento = array('ativo' => 1, 'usado' => 0, 'codigo' => $codigo, 'tipo' => 'EXTERNO', 'cliente_id' => $clt, 'lat' => $lat, 'lng' => $lng, 'hora' =>  date("H:i:s"), 'data' => date('Y-m-d'), 'empresa_id' => $this->request->data['Pedido']['empresa_id'], 'filial_id' => $this->request->data['Pedido']['filial_id']);
									}else{
										$dadosatendimento = array(
											'ativo' => 1, 
											'usado' => 0, 
											'codigo' => $codigo, 
											'tipo' => 'EXTERNO-SISTEMA', 
											'cliente_id' => $clt, 
											'lat' => $lat, 'lng' => $lng, 
											'hora' =>  date("H:i:s"), 
											'data' => date('Y-m-d'), 
											'empresa_id' => $this->request->data['Pedido']['empresa_id'], 
											'filial_id' => $this->request->data['Pedido']['filial_id'],
											'mesa_id'=> $this->request->data['Pedido']['mesa_id'],
											
										);
									}
									if ($this->Atendimento->save($dadosatendimento)) {
										$ultimoAtend = $this->Atendimento->find('first', array('order' => array('Atendimento.id' => 'desc'), 'recursive' => 1));
										$codigo = $ultimoAtend['Atendimento']['codigo'];
										$atendimento = $ultimoAtend;
										$this->request->data['Pedido']['atendimento_id'] = $ultimoAtend['Atendimento']['id'];
									} else {
										//$this->Session->setFlash(__('The atendimento could not be saved. Please, try again.'), 'default', array('class' => 'error-flash'));
									}
								} else {
									$flag = "FALSE";
								}
							}
						} else {
							$atendimento = $this->Atendimento->find('first', array('conditions' => array('Atendimento.codigo' => $codigo)));
						}



						$this->Pedido->create();


						$this->request->data['Pedido']['data'] = date('Y-m-d');


						$total = 0;
						$tempoPreparo = "00:00:00";


						$itens = $this->request->data['Itensdepedido'];

						$i = 0;
						$j = 0;
						$k = 0;
						$this->loadModel('Partida');
						foreach ($itens as $key => $iten) {
							if (isset($this->request->data['Itensdepedido'][$key]['partida_id'])) {
								if ($this->request->data['Itensdepedido'][$key]['partida_id'] != '') {
									$this->Partida->create();
									$this->Partida->save(
										array(
											'id' => $this->request->data['Itensdepedido'][$key]['partida_id'],
											'resgate' => 1
										)
									);
								}
							}
							$produto = $this->Produto->find('first', array('rescusive' => -1, 'conditions' => array('Produto.id' => $iten['produto_id'])));
							if (!empty($produto)) {
								if ($produto['Produto']['composto'] == 1) {
									if (isset($this->request->data['Itensdepedido'][$key]['compostoum_id'])) {
										if ($this->request->data['Itensdepedido'][$key]['compostoum_id'] != '') {
											$produto1 = $this->Produto->find('first', array('rescusive' => -1, 'conditions' => array('Produto.id' => $this->request->data['Itensdepedido'][$key]['compostoum_id'])));
											$produto2 = $this->Produto->find('first', array('rescusive' => -1, 'conditions' => array('Produto.id' => $this->request->data['Itensdepedido'][$key]['compostodois_id'])));
											if (!empty($produto1) && !empty($produto2)) {
												if ($produto1['Produto']['preco_venda'] > $produto2['Produto']['preco_venda']) {
													$produto['Produto']['preco_venda'] = $produto1['Produto']['preco_venda'];
												} else {
													$produto['Produto']['preco_venda'] = $produto2['Produto']['preco_venda'];
												}
												$this->request->data['Itensdepedido'][$key]['composto_nomeum'] = $produto1['Produto']['nome'];
												$this->request->data['Itensdepedido'][$key]['composto_nomedois'] = $produto2['Produto']['nome'];
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


								for ($k = 0; $k <  $iten['qtde']; $k++) {
									$tempoPreparo = $this->checkbfunc->somaHora($tempoPreparo, $produto['Produto']['tempo_preparo']);
								}
							}

							$j++;
						}

						$this->request->data['Pedido']['valor'] = $total +  $this->request->data['Pedido']['entrega_valor'];



						$horaAtendimento =  date("H:i:s");
						if(isset($this->request->data['Pedido']['atendente_id'])){
							$this->request->data['Pedido']['hora_atendimento'] = $horaAtendimento;
						}
						$tempoVisualizacao = "00:01:00";
						//$tempoFila = $this->calculaFilaProdutos();
						$this->loadModel('Filial');
						$estaFilial = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $this->request->data['Pedido']['filial_id'])));
						$tempoFila = $estaFilial['Filial']['tempo_atendimento'];
						$tempoTotalFila = $tempoFila;
						if ($tempoFila == false) {
							$tempoFila = "00:00:00";
						}
						//Garanto o minimo de tempo para preparar o produto mais demorado para ficar pronto


						//Busco a dura��o do trajeto do endere�o do cliente
						if (!isset($this->request->data['Pedido']['fromAtendentePedido'])) {

							$this->loadModel('Cliente');
							$cliente = $this->Cliente->find('first', array('recursive' => -1, 'conditions' => array('Cliente.id' => $this->request->data['Pedido']['cliente_id'])));

							$duracao = $cliente['Cliente']['duracao'];

							//Trato a string recebida para o formato de horas e minutos
							$horasAux = explode('horas', $cliente['Cliente']['duracao']);

							if (isset($horasAux[1])) {
								$horas = str_replace(" ", "", $horasAux[0]);
							} else {
								$horasAux = explode('hora', $cliente['Cliente']['duracao']);
								if (isset($horasAux[1])) {
									$horas = str_replace(" ", "", $horasAux[0]);
								}
							}
							if (isset($horas)) {
								$tamanhoString = strlen($horas);
								if ($tamanhoString == 1) {
									$horas = '0' . $horas;
								}
							} else {
								$horas = '00';
							}

							$minutosAux = explode('minutos', $cliente['Cliente']['duracao']);

							if (isset($minutosAux[1])) {
								$minutos = str_replace(" ", "", $minutosAux[0]);
							}

							if (isset($minutos)) {
								$tamanhoString = strlen($minutos);
								if ($tamanhoString == 1) {
									$minutos = '0' . $minutos;
								}
							} else {
								$minutos = '00';
							}

							$segundos = '00';



							$tempoEntrega = $horas . ":" . $minutos . ":" . $segundos;

							$somaTempos1 = $this->checkbfunc->somaHora($tempoPreparo, $tempoFila);

							$somaTempos2 = $this->checkbfunc->somaHora($tempoEntrega, $tempoVisualizacao);

							$tempoEst = $this->checkbfunc->somaHora($somaTempos2, $tempoPreparo);

							$tempoTotalFila = $this->checkbfunc->somaHora($somaTempos1, $somaTempos2);




							//$tempoEstAux = $this->checkbfunc->somaHora($tempoVisualizacao, $tempoEntrega);
							//$tempoEst= $this->checkbfunc->somaHora($tempoEstAux,$tempoPreparo);
							//$tempoTotalFila = $this->checkbfunc->somaHora($tempoEst,$tempoFila);

							$this->request->data['Pedido']['hora_atendimento'] = $horaAtendimento;
							$this->request->data['Pedido']['statuspreparo'] = 1;
							$this->request->data['Pedido']['tempo_estimado'] = $tempoEst;
							$this->request->data['Pedido']['tempo_fila'] = $tempoTotalFila;

							$posicaoFila = $this->Pedido->find('count', array('recursive' => -1, 'conditions' => array('AND' => array(array('Pedido.statuspreparo' => 1), array('Pedido.filial_id' => $this->request->data['Pedido']['filial_id'])))));

							if (empty($posicaoFila)) {
								$posicaoFila = 0;
							}
							$this->request->data['Pedido']['posicao_fila'] = $posicaoFila;

							//die('aqu2i');
							//correção edu 14/01/2020
							//if(empty($pedidoExistente)){

							/*$j=0;
                foreach($this->request->data['Itensdepedido'] as $itens ){

                  $this->request->data['Itensdepedido'][$j]['pedido_id'] = $pedidoExistente['Pedido']['id'];
                  $j=$j +1;
                }*/



							//$this->request->data['Pedido']['id']= $pedidoExistente['Pedido']['id'];
							$this->Pedido->create();

							unset($this->request->data['Pedido']['a']);
							unset($this->request->data['Pedido']['salt']);
							unset($this->request->data['Pedido']['token']);
							unset($this->request->data['Pedido']['origem']);


							if ($this->Pedido->save($this->request->data['Pedido'])) {
								$ultimopedido = $this->Pedido->find('first', array('order' => array('Pedido.id' => 'desc'), 'recursive' => -1));
								foreach ($itens as $key => $iten) {
									$this->request->data['Itensdepedido'][$key]['pedido_id'] = $ultimopedido['Pedido']['id'];
								}
								if ($this->Itensdepedido->saveAll($this->request->data['Itensdepedido'])) {


									//$ultimopedido = $pedidoExistente;

									//Insiro o pedido na rota do  entregador
									$Autorizacao = new AutorizacaosController;
									$Roteiro = new RoteirosController;

									$Roteiro->inserirRota($ultimopedido['Pedido']['id']);

									$this->set(compact($ultimopedido));
									if (!$this->request->is('ajax')) {
										//$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
										//return $this->redirect( $this->referer() );
									}
								}
							} else {
								$ultimopedido = "erro";
							}



							if ($this->request->is('ajax')) {
								$this->layout = 'ajaxaddpedido';
							}
							/*}else{
					$ultimoPedido = $this->request->data;
					$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}*/
						} else {


							unset($this->request->data['Pedido']['user_id']);
							if ($this->Pedido->saveAll($this->request->data)) {

								$this->loadModel('Roteiro');

								//debug($this->request->data);

								$ultimopedido = $this->Pedido->find('first', array('order' => array('Pedido.id' => 'desc'), 'recursive' => -1));


								//Insiro o pedido na rota do  entregador
								$Autorizacao = new AutorizacaosController;
								$Roteiro = new RoteirosController;

								$Roteiro->inserirRota($ultimopedido['Pedido']['id']);

								$this->set(compact($ultimopedido));
								if (!$this->request->is('ajax')) {
									//$this->Session->setFlash(__('O pedido foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
									//return $this->redirect( $this->referer() );
								}

								if ($this->request->is('ajax')) {
									$this->layout = 'ajaxaddpedido';
								}
							} else {
								$ultimoPedido = $this->request->data;
								$this->Session->setFlash(__('Houve um erro ao salvar o pedido. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
							}
						}
					}

					//}else{

					//$this->Session->setFlash(__('Pedido Inv�lido. Please, try again.'));

					//}
				}
			}
		}

		$resultados = $ultimopedido;
		//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
		//$this->set("_serialize", array("resultados"));

		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
	}
	public function setabrirefecharlojaportempo()
	{
		$this->layout = 'ajaxaddpedido';
		$dataHoraVerificação =  date("Y-m-d H:i:s");
		$qtdItensCancelados = 0;
		$this->loadModel('Filial');

		// Abrindo lojas fechadas
		$filiais = $this->Filial->find('all', array('conditions' => array('Filial.ativo' => 1), 'recursive' => -1));

		$diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');

		$numeroDaSemanda = date('w');

		$qtdAberturaseFechamentos = 0;
		if (!empty($filiais)) {
			foreach ($filiais as $key => $value) {
				$flagHoraAbrir = false;
				$flagHoraFechar = false;
				$indexTexto = 'abre_' . $diasemana[$numeroDaSemanda];
				//echo $indexTexto;
				$horaParaFechamento='';
				$horaParaAbertura='';
				
				if(isset($value['Filial']['inicio_'.$diasemana[$numeroDaSemanda]]) ){
					if($value['Filial']['inicio_'.$diasemana[$numeroDaSemanda]] !=''){
						$horaParaAbertura = date("Y-m-d H:i:s", strtotime($value['Filial']['inicio_'.$diasemana[$numeroDaSemanda]]));
					}
					
				}
				
				if(isset($value['Filial']['fim_'.$diasemana[$numeroDaSemanda]])){
					if($value['Filial']['fim_'.$diasemana[$numeroDaSemanda]] != ''){
						$horaParaFechamento = date("Y-m-d H:i:s", strtotime($value['Filial']['fim_'.$diasemana[$numeroDaSemanda]]));
					}
				}
				
				
				$flagFecharPorErro=false;
				if($horaParaFechamento == '' || $horaParaAbertura == ''){
					$flagFecharPorErro=true;
				}
				if (($horaParaFechamento <= $horaParaAbertura) && $flagFecharPorErro== false ) {
					$horaParaFechamento = date("Y-m-d  H:i:s", strtotime('+1 day' . $value['Filial']['hora_fechamento']));
				}
				
				if ($value['Filial'][$indexTexto] == true) {


					if ($horaParaAbertura <=  $dataHoraVerificação) {

						if ($value['Filial']['status_abertura'] != 1) {
							$updateFilial = array('id' => $value['Filial']['id'], 'status_abertura' => 1);
							$flagHoraAbrir = true;
						}
					} else {
						if ($value['Filial']['status_abertura'] == 1) {
							$updateFilial = array('id' => $value['Filial']['id'], 'status_abertura' => 0);
							$flagHoraFechar = true;
						}
					}



					if ($horaParaFechamento <=  $dataHoraVerificação) {

						$updateFilial = array('id' => $value['Filial']['id'], 'status_abertura' => 0);
						$flagHoraFechar = true;
						//$this->Filial->save($updateFilial);
					}
					if ($flagHoraFechar || $flagHoraAbrir) {
						$this->Filial->save($updateFilial);
						$qtdAberturaseFechamentos = $qtdAberturaseFechamentos + 1;
					}
				} else {

					if ($horaParaFechamento <=  $dataHoraVerificação) {

						$updateFilial = array('id' => $value['Filial']['id'], 'status_abertura' => 0);
						$this->Filial->save($updateFilial);
						$qtdAberturaseFechamentos = $qtdAberturaseFechamentos + 1;
						//$this->Filial->save($updateFilial);
					}
					if($flagFecharPorErro==true){
						$updateFilial = array('id' => $value['Filial']['id'], 'status_abertura' => 0);
						$this->Filial->save($updateFilial);
						$qtdAberturaseFechamentos = $qtdAberturaseFechamentos + 1;	
					}
				}
			}
			//die;
		}



		//print_r($diasemana[$numeroDaSemanda]);
		//die;



		$this->set(array(
			'resultados' => array('resultados' => $qtdAberturaseFechamentos),
			'_serialize' => array('resultados')
		));
	}
	public function sendnotification($token = '', $title = '', $notification = '', $atendimento_id = '')
	{


		/*$payload = array(
	    'to' => $token,
	    'sound' => 'default',
	    'title'=>$title,
	    'body' => $notification, 
	    //'data'=> array('data'=> 'my date to send'),                          
	               
    );*/

		$payload = array(
			'to' => $token,
			'title' => $title,
			'body' => $notification,
			'data' => array('atendimento_id' => $atendimento_id),
			'sound' => 'default',
		);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($payload),
			CURLOPT_HTTPHEADER => array(
				"Accept: application/json",
				"Accept-Encoding: gzip, deflate",
				"Content-Type: application/json",
				"cache-control: no-cache",

			),
		));/**/

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return $err;
		} else {
			return $response;
		}
	}
	public function setpedidoscanceladosportempo()
	{
		$this->layout = 'ajaxaddpedido';
		$dataHoraVerificação =  date("Y-m-d H:i:s");
		$tempoLimite = '5';

		$prazoLimite = date("Y-m-d H:i:s", strtotime('-' . $tempoLimite . ' minute'));



		$pedidos = $this->Pedido->find(
			'all',
			array(
				'conditions' => array(
					'Pedido.status' => 'Em Aberto',
					'Pedido.created <=' => $prazoLimite
				)
			)
		);

		$qtdItensCancelados = 0;

		foreach ($pedidos as $key => $value) {

			if ($value['Pedido']['status'] != 'Cancelado') {
				/**/
				$this->Pedido->id = $value['Pedido']['id'];
				$this->Pedido->saveField('status', 'Cancelado');
				$this->Pedido->saveField('status_novo', 0);
				$this->Pedido->saveField('motivocancela', 'Cancelado automaticamente após exceder prazo de confirmação.');
				$this->loadModel('Atendimento');

				$updateStatusAtendimento = array('id' => $value['Pedido']['atendimento_id'], 'status' => 'Cancelado');
				$this->Atendimento->create();
				$this->Atendimento->save($updateStatusAtendimento);
				//$this->Pedido->create();
				$updatePedido = array('id' => $value['Pedido']['atendimento_id'], 'motivocancela' => 'Cancelado automaticamente após exceder prazo de confirmação', 'status' => 'Cancelado');
				$this->Pedido->save($updatePedido);

				$this->loadModel('Itensdepedido');
				$this->Itensdepedido->updateAll(
					array('Itensdepedido.statuspreparo' => 0),
					array('Itensdepedido.pedido_id' => $value['Pedido']['id'])
				);

				$Estoque = new ProdutosController;
				$itensACancelar = $this->Itensdepedido->find('all', array('recursive' => -1, 'conditions' => array('Itensdepedido.pedido_id' => $value['Pedido']['id'])));
				$this->loadModel('Partida');

				foreach ($itensACancelar as $iten) {

					if (isset($iten['Itensdepedido']['partida_id'])) {
						if ($iten['Itensdepedido']['partida_id'] != '') {
							$this->Partida->create();
							$this->Partida->save(
								array(
									'id' => $iten['Itensdepedido']['partida_id'],
									'resgate' => 0
								)
							);
						}
					}
					$Estoque->aumentaEstoque($iten['Itensdepedido']['produto_id'], $iten['Itensdepedido']['qtde']);
				}
				if ($value['Pedido']['ptk'] != '' && $value['Pedido']['ptk'] != null) {
					$title = 'Pedido Cancelado.';
					$notification = 'Seu pedido foi cancelado automaticamente após exceder prazo de confirmação.';
					$this->sendnotification($value['Pedido']['ptk'], $title, $notification, $value['Pedido']['atendimento_id']);
				}
				$qtdItensCancelados = $qtdItensCancelados + 1;
				//$limiteAprovacao =  $this->checkbfunc->somaHora($tempoLimite,$value['Produto']['created']);
				//$dataHoraAtendimento = date("Y-m-d H:i:s", strtotime($value['Pedido']['created']));
			}
		}






		//$resultados= array();
		$this->set(array(
			'resultados' => array('resultados' => $qtdItensCancelados),
			'_serialize' => array('resultados')
		));
	}
	public function calculaFilaProdutos()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$this->loadModel('Produto');
		$this->loadModel('Itensdepedido');
		$this->Itensdepedido->virtualFields = array('totalprod' => 'SUM(Itensdepedido.qtde)');
		$produtos = $this->Produto->find('all', array('recursive' => -1));
		$i = 0;
		$produtoFila = array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdepedido->find('all', array('recursive' => -1, 'conditions' => array('AND' => array(array('Itensdepedido.produto_id' => $produto['Produto']['id']), array('Itensdepedido.statuspreparo' => 1)))));

			if (!empty($qteFilaProduto)) {

				if (isset($qteFilaProduto[0]['Itensdepedido']['totalprod'])) {

					if ($qteFilaProduto[0]['Itensdepedido']['totalprod'] == null) {
						unset($qteFilaProduto[$i]);
					} else {
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdepedido']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo = $produto['Produto']['tempo_preparo'];
						$qtdePreparo = $produto['Produto']['qtde_preparo'];
						if ($qtdePreparo != null) {
							$qteFila = $qteFilaProduto[0]['Itensdepedido']['totalprod'];
							$tempoNescessario = '?';
							$acumuladorTempo = '00:00:00';
							for ($i = 0; $i < $qteFila; $i++) {
								$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
							}

							$segundosTotais = $this->converteparasegundos($acumuladorTempo);

							if ($qtdePreparo == null || $qtdePreparo == '') {
							} else {
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

		if (isset($tempoTotalPreparo)) {

			return $tempoTotalPreparo;
		} else {
			return false;
		}
	}

	public function calculaFilaProduto(&$id)
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$this->loadModel('Produto');
		$this->loadModel('Itensdepedido');
		$this->Itensdepedido->virtualFields = array('totalprod' => 'SUM(Itensdepedido.qtde)');
		$produtos = $this->Produto->find('all', array('recursive' => -1));
		$i = 0;
		$produtoFila = array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdepedido->find('all', array('recursive' => -1, 'conditions' => array('AND' => array(array('Itensdepedido.produto_id' => $produto['Produto']['id']), array('Itensdepedido.statuspreparo' => 1), array('Itensdepedido.pedido_id <=' => $id)))));

			if (!empty($qteFilaProduto)) {

				if (isset($qteFilaProduto[0]['Itensdepedido']['totalprod'])) {

					if ($qteFilaProduto[0]['Itensdepedido']['totalprod'] == null) {
						unset($qteFilaProduto[$i]);
					} else {
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdepedido']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo = $produto['Produto']['tempo_preparo'];
						$qtdePreparo = round($produto['Produto']['qtde_preparo']);
						if ($qtdePreparo != null) {
							$qteFila = round($qteFilaProduto[0]['Itensdepedido']['totalprod']);
							$modQtde = ($qteFila % $qtdePreparo);
							while ($modQtde != 0) {
								$qteFila++;
								$modQtde = ($qteFila % $qtdePreparo);
							}
							$tempoNescessario = '?';
							$acumuladorTempo = '00:00:00';
							for ($i = 0; $i < $qteFila; $i++) {
								$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
							}

							$segundosTotais = $this->converteparasegundos($acumuladorTempo);
							if ($qtdePreparo == null || $qtdePreparo == '') {
							} else {
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

	public function avalpedidomobile()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		if ($this->request->is('get')) {

			$this->layout = 'ajaxaddpedido';
			$id = $_GET['id'];
			$nota = $_GET['nota'];
			$clt =  $_GET['b'];
			$token = $_GET['c'];
			$resp = $this->checkToken($clt, $token);
			$Empresa = new EmpresasController;
			$respAux = $Empresa->empresaAtiva();

			if ($respAux == 1) {
			} else {
				$resp = 'NOK';
			}
			if ($resp == 'OK') {
				$updatePedido = array('id' => $id, 'avaliacao' => $nota);
				$this->Pedido->create();
				if ($this->Pedido->save($updatePedido)) {
					$resultados = "OK";
					//$this->set(compact('pagamentos','ultimopedido','codigo','resultados'));
					//$this->set("_serialize", array("resultados"));

					$this->set(array(
						'resultados' => $resultados,
						'_serialize' => array('resultados')
					));
				} else {

					$resultados = "Falha";
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
	public function getSessionPag()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		if ($this->request->is('get')) {
			$this->loadModel('Produto');
			$this->loadModel('Filial');
			$this->loadModel('Itensdepedido');
			$this->layout = 'ajaxaddpedido';
			$id = $_GET['id'];
			/*
			$clt =  $_GET['b'];
			$token = $_GET['c'];
			$resp =$this->checkToken($clt, $token);
			$Empresa = new EmpresasController;
			$respAux = $Empresa->empresaAtiva();

			if($respAux == 1){

			}else{
				$resp='NOK';
			}*/
			//if($resp =='OK'){
			/*try {

          $credentials = PagSeguroConfig::getAccountCredentials('eduardonalves@gmail.com',''); // getApplicationCredentials()
          $sessionId = PagSeguroSessionService::getSession($credentials);

        } catch (PagSeguroServiceException $e) {
          die($e->getMessage());
        }*/
			$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.id' => $id)));

			if (!empty($pedido)) {
				$filial = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $pedido['Pedido']['filial_id'])));
				if (!empty($filial)) {


					PagSeguroConfig::setEmail($filial['Filial']['email_pagseguro']);


					PagSeguroConfig::setToken($filial['Filial']['token_pagseguro']);


					PagSeguroConfig::setAppId($filial['Filial']['app_idpagseguro']);


					PagSeguroConfig::setAppKey($filial['Filial']['app_keypagseguro']);
				}
			}

			// Instantiate a new payment request
			$paymentRequest = new PagSeguroPaymentRequest();

			// Set the currency
			$paymentRequest->setCurrency("BRL");
			foreach ($pedido['Itensdepedido'] as $key => $value) {
				// Add an item for this payment request
				$produto = $this->Produto->find('first', array('recursive' => -1, 'conditions' => array('Produto.id' => $value['produto_id'])));

				$paymentRequest->addItem($produto['Produto']['id'], $produto['Produto']['nome'], $value['qtde'], $value['valor_unit']);
			}

			if ($pedido['Pedido']['entrega_valor'] != '' &&  $pedido['Pedido']['entrega_valor'] != null &&  $pedido['Pedido']['entrega_valor'] != 0) {
				$paymentRequest->addItem('001', 'tx. entrega', '1', $pedido['Pedido']['entrega_valor']);
			}

			// Add another item for this payment request
			//$paymentRequest->addItem('0002', 'Notebook rosa', 2, 560.00);

			// Set a reference code for this payment request, it is useful to identify this payment
			// in future notifications.
			$paymentRequest->setReference($pedido['Pedido']['id']);

			// Set shipping information for this payment request
			$sedexCode = PagSeguroShippingType::getCodeByType('NOT_SPECIFIED');
			$paymentRequest->setShippingType($sedexCode);
			$paymentRequest->setShippingAddress(
				$pedido['Cliente']['cep'],
				$pedido['Cliente']['logradouro'],
				$pedido['Cliente']['numero'],
				$pedido['Cliente']['complemento'],
				$pedido['Cliente']['bairro'],
				$pedido['Cliente']['cidade'],
				$pedido['Cliente']['uf'],
				'BRA'
			);
			if ($pedido['Cliente']['telefone'] != null && $pedido['Cliente']['telefone'] != '') {
				$ddd = explode('(', $pedido['Cliente']['telefone']);
				if (isset($ddd[1])) {
					$ddd = substr($ddd[1], 0, 2);
				} else {
					$ddd = '21';
				}
				$telefone = explode(')', $pedido['Cliente']['telefone']);

				if (isset($telefone[1])) {
					$telefone = str_replace('-', '', $telefone[1]);
					$telefone = str_replace(' ', '', $telefone);
				} else {
					$telefone = $pedido['Cliente']['telefone'];
					$telefone = str_replace('-', '', $telefone[1]);
					$telefone = str_replace(' ', '', $telefone[1]);
				}
			} else {
				$ddd = null;
				$telefone = null;
			}


			$paymentRequest->setSender(
				$pedido['Cliente']['nome'],
				$pedido['Cliente']['email'],
				$ddd,
				$telefone,
				'CPF',
				$pedido['Cliente']['cpf']
			);
			$paymentRequest->addParameter('notificationURL', 'http://develop.entregapp.com.br/RestPedidos/getnotifications.json?a=' . $pedido['Pedido']['id']);
			// Set the url used by PagSeguro to redirect user after checkout process ends
			//$paymentRequest->setRedirectUrl("http://www.sistema.entregapp.com.br/pagseguro");

			// Add checkout metadata information
			/*$paymentRequest->addMetadata('PASSENGER_CPF', '15600944276', 1);
       $paymentRequest->addMetadata('GAME_NAME', 'DOTA');
       $paymentRequest->addMetadata('PASSENGER_PASSPORT', '23456', 1);*/

			// Another way to set checkout parameters
			/*$paymentRequest->addParameter('notificationURL', 'http://www.sistema.entregapp.com.br/nas');
       $paymentRequest->addParameter('senderBornDate', '07/05/1981');
       $paymentRequest->addIndexedParameter('itemId', '0003', 3);
       $paymentRequest->addIndexedParameter('itemDescription', 'Notebook Preto', 3);
       $paymentRequest->addIndexedParameter('itemQuantity', '1', 3);
       $paymentRequest->addIndexedParameter('itemAmount', '200.00', 3);*/


			// Add installment limit per payment method
			$paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 1, 'MAX_INSTALLMENTS_LIMIT');

			// Add and remove a group and payment methods
			$paymentRequest->acceptPaymentMethodGroup('CREDIT_CARD', 'DEBITO_ITAU');

			try {

				/*
            * #### Credentials #####
            * Replace the parameters below with your credentials
            * You can also get your credentials from a config file. See an example:
            * $credentials = PagSeguroConfig::getAccountCredentials();
            */

				// seller authentication
				$credentials = new PagSeguroAccountCredentials(
					$filial['Filial']['email_pagseguro'],
					$filial['Filial']['token_pagseguro']
				);

				// application authentication
				//$credentials = PagSeguroConfig::getApplicationCredentials();

				//$credentials->setAuthorizationCode("E231B2C9BCC8474DA2E260B6C8CF60D3");

				// Register this payment request in PagSeguro to obtain the checkout code
				$onlyCheckoutCode = true;
				$code = $paymentRequest->register($credentials, $onlyCheckoutCode);

				//self::printPaymentUrl($code);
			} catch (PagSeguroServiceException $e) {
				die($e->getMessage());
			}

			$this->set(array(
				'resultados' => $code,
				'_serialize' => array('resultados')
			));
			//}

		}
	}

	public function cancelarpagseguro()
	{

		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");
		$this->loadModel('Assinatura');
		$assinatura = $this->Assinatura->find('first', array(
			'recursive' => 0,
			'conditions' => array(
				'Assinatura.Id_pagseguro' => $_GET['ref']
			)
		));
		//url produção https://ws.pagseguro.uol.com.br/v2/pre-approvals/cancel
		$this->layout = 'ajaxaddpedido';
		if (!empty($assinatura)) {
			$token = '';
			$email = 'eduardonalves@gmail.com';
			$transactionAssinatura = $assinatura['Assinatura']['codigo_pag'];

			$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/pre-approvals/cancel?email=' . $email . '&token=' . $token . '&transactionCode=' . $transactionAssinatura;

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$transaction = curl_exec($curl);
			curl_close($curl);
			$resposta = $transaction;
			$this->set(array(
				'ultimopedido' => $resposta,
				'_serialize' => array('ultimopedido')
			));
		}
	}
	public function getnotifications()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		$this->loadModel('Filial');
		$this->loadModel('Pedido');
		$this->loadModel('Pagamento');


		PagSeguroConfig::setEmail('eduardonalves@gmail.com');
		PagSeguroConfig::setToken('');
		PagSeguroConfig::setAppId('');
		PagSeguroConfig::setAppKey('');


		$code = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ?
			trim($_POST['notificationCode']) : null);
		$type = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ?
			trim($_POST['notificationType']) : null);

		if ($code && $type) {

			$notificationType = new PagSeguroNotificationType($type);
			$strType = $notificationType->getTypeFromValue();

			switch ($strType) {

				case 'TRANSACTION':
					$resposta = $this->transactionNotification($code);


					break;

				case 'APPLICATION_AUTHORIZATION':
					self::authorizationNotification($code);
					break;

				case 'PRE_APPROVAL':


					$preApproval = self::preApprovalNotification($code);


					$dataToSave = array(
						'status' => $preApproval->getStatus()->getTypeFromValue(),
						'codigo_pag' => $preApproval->getCode(),
						'Id_pagseguro' => $preApproval->getReference(),
						'tracker' => $preApproval->getTracker(),
						'data_pagamento' => $preApproval->getDate()
					);

					$this->loadModel('Assinatura');

					$hasPagamento  = $this->Assinatura->find('first', array(
						'recursive' => -1,
						'conditions' => array(
							'Assinatura.Id_pagseguro' => $preApproval->getReference()
						)
					));
					if (empty($hasPagamento)) {
						if ($preApproval->getCode() != null && $preApproval->getCode() != '') {
							$this->Assinatura->create();
							$this->Assinatura->save($dataToSave);
						}
					} else {
						$dataToSave['id']  = $hasPagamento['Assinatura']['id'];
						$this->Assinatura->save($dataToSave);
					}

					break;

				default:
					LogPagSeguro::error("Unknown notification type [" . $notificationType->getValue() . "]");
			}

			$count = 4;

			$log = LogPagSeguro::getHtml($count);
			$resposta = array('tipo' => $strType, 'count' => $count, 'log' => $log, 'codigo' => $code);
		} else {

			LogPagSeguro::error("Invalid notification parameters.");
			$count = 4;

			$log = LogPagSeguro::getHtml($count);
			if (!isset($strType)) {
				$strType = "vazio";
			}
			$resposta = array('tipo' => $strType, 'count' => $count, 'log' => $log, 'status' => 'ok');
		}

		// $resposta = 'Ok';
		$this->set(array(
			'resultados' => $resposta,
			'_serialize' => array('resultados')
		));
	}
	public function returnLog($strType)
	{
		$count = 4;

		echo LogPagSeguro::getHtml($count);
		$resposta = array('tipo' => $strType, 'count' => $count, 'log' => $log);
		return $resposta;
	}
	private function transactionNotification($notificationCode)
	{


		PagSeguroConfig::setEmail('eduardonalves@gmail.com');
		PagSeguroConfig::setToken('');
		PagSeguroConfig::setAppId('');
		PagSeguroConfig::setAppKey('');



		$credentials = new PagSeguroAccountCredentials("eduardonalves@gmail.com", "");


		try {
			$transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);
			// debug($transaction);
			// Do something with $transaction
			$statusPagamento =  $transaction->getStatus()->getTypeFromValue();
			switch ($statusPagamento) {
				case 'IN_ANALYSIS':
					$statusPagamento = 'Em Análise';
					break;
				case 'PAID':
					$statusPagamento = 'OK';
					break;
				case 'AVAILABLE':
					$statusPagamento = 'Disponível';
					break;
				case 'REFUNDED':
					$statusPagamento = 'Devolvida';
					break;
				case 'CANCELLED':
					$statusPagamento = 'Cancelada';
					break;
				case 'IN_DISPUTE':
					$statusPagamento = 'Em Disputa';
					break;
				case 'SELLER_CHARGEBACK':
					$statusPagamento = 'Chargeback debitado*';
					break;
				case 'CONTESTATION':
					$statusPagamento = 'Em Contestação';
					break;


				default:
					$statusPagamento = $statusPagamento;
					break;
			}
			$dataToSave = array(
				'status' => $statusPagamento,
				'Id_pagseguro' => $transaction->getReference()
			);

			$dataToSave = array(
				'status' => $statusPagamento,
				'codigo_pag' => $transaction->getCode(),
				'Id_pagseguro' => $transaction->getReference(),
				'data_pagamento' => $transaction->getDate()
			);

			$this->loadModel('Pagamento');

			$hasPagamento  = $this->Pagamento->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'Pagamento.codigo_pag' => $transaction->getCode()
				)
			));
			if (empty($hasPagamento)) {
				if ($transaction->getCode() != null && $transaction->getCode() != '') {
					$this->Pagamento->create();
					$this->Pagamento->save($dataToSave);
				}
			} else {
				if ($transaction->getCode() != null && $transaction->getCode() != '') {
					$dataToSave['id'] = $hasPagamento['Pagamento']['id'];
					$this->Pagamento->create();
				}
			}

			$this->loadModel('Pagamento');
			$this->Pagamento->save($dataToSave);

			return $dataToSave;
		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
	}

	private static function authorizationNotification($notificationCode)
	{

		PagSeguroConfig::setEmail('eduardonalves@gmail.com');
		PagSeguroConfig::setToken('');
		PagSeguroConfig::setAppId('');
		PagSeguroConfig::setAppKey('');

		try {
			$credentials = new PagSeguroAccountCredentials("eduardonalves@gmail.com", "");
			$authorization = PagSeguroNotificationService::checkAuthorization($credentials, $notificationCode);
		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
		$dataToSave =
			array(
				'status' => 'AUTORIZACAO',
				'Id_pagseguro' => $notificationCode
			);
		$this->loadModel('Pagamento');
		$this->Pagamento->create();
		$this->Pagamento->save($dataToSave);
	}

	private static function preApprovalNotification($preApprovalCode)
	{

		PagSeguroConfig::setEmail('eduardonalves@gmail.com');
		PagSeguroConfig::setToken('');
		PagSeguroConfig::setAppId('');
		PagSeguroConfig::setAppKey('');
		$credentials = new PagSeguroAccountCredentials("eduardonalves@gmail.com", "");

		try {
			$preApprovalObj = PagSeguroNotificationService::checkPreApproval($credentials, $preApprovalCode);
			return $preApprovalObj;


			// Do something with $preApproval



		} catch (PagSeguroServiceException $e) {
			die($e->getMessage());
		}
	}
}
