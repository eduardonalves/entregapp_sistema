<?php
App::import('Vendor', 'autoload.inc');
App::import(
	'Vendor',
	'PagSeguroLibrary',
	array('file' => 'PagSeguroLibrary' . DS . 'PagSeguroLibrary.php')
);
App::uses('AppController', 'Controller');
App::import('Controller', 'Users');
/**
 * Empresas Controller
 *
 * @property Empresa $Empresa
 * @property PaginatorComponent $Paginator
 */


class EmpresasController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'RequestHandler',);

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
		$this->set('empresa', $this->Empresa->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->Empresa->create();
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('A empresa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		}
		$funcaos = $this->Empresa->Funcao->find('list');
		$this->set(compact('funcaos'));
	}

	/**
	 * add method
	 *
	 * @return bolean
	 */
	public function empresaAtiva()
	{
		$this->loadModel('Empresa');
		$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1));
		if ($empresa['Empresa']['status_empresa'] == 0) {
			return false;
		} else {
			return true;
		}
	}

	public function empresaIdAtiva(&$id)
	{
		$this->loadModel('Empresa');
		$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1, 'conditions' => array('Empresa.id' => $id)));

		if (empty($empresa)) {
			return false;
		} else {

			if ($empresa['Empresa']['status_empresa'] == false) {

				return false;
			} else {

				return true;
			}
		}
	}

	public function filialIdAtiva(&$id)
	{
		$this->loadModel('Filial');
		$empresa = $this->Filial->find('first', array('order' => array('Filial.id' => 'asc'), 'recursive' => -1, 'conditions' => array('Filial.id' => $id)));

		if (empty($empresa)) {
			return false;
		} else {
			if ($empresa['Filial']['status_empresa'] == 1  && $empresa['Filial']['status_abertura'] == 1) {
				return true;
			} else {
				return false;
			}
		}
	}


	/**
	 * add method
	 *
	 * @return bolean
	 */
	public function empresaAberta()
	{
		$this->loadModel('Empresa');
		$empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1));
		if ($empresa['Empresa']['status_aberta'] == 0) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * add method
	 *
	 * @return bolean
	 */
	public function verificaValorFrete($filial_id, $bairro, $cidade, $uf)
	{
		$this->loadModel('Empresa');
		$this->loadModel('Filial');
		$this->loadModel('Estado');
		$this->loadModel('Cidad');
		$this->loadModel('Bairro');


		$filial = $this->Filial->find('first', array('order' => array('Filial.id' => 'asc'), 'recursive' => -1, 'conditions' => array('Filial.id' => $filial_id)));
		$minhasCidade = $this->Cidad->find('first', array('recursive' => -1, 'conditions' => array('and' => array(array('Cidad.id' => $cidade), array('Cidad.filial_id' => $filial_id), array('Cidad.ativo' => true)))));
		$uf = $this->Estado->find('first', array('recursive' => -1, 'conditions' => array('and' => array(array('Estado.id' => $uf), array('Estado.ativo' => true)))));



		if (empty($uf)) {
			return false;
			exit;
			//debug($filial_id);

		}
		if ($filial['Filial']['taxa_padrao'] == true) {

			$frete = number_format($filial['Filial']['valor_padrao'], 2, ',', '.');

			return $frete;
		} else {

			if (!empty($minhasCidade)) {

				if ($minhasCidade['Cidad']['cobertura_total'] == true) {
					//$frete=number_format($minhasCidade['Cidad']['valor'],2,',','.');
					$frete = $minhasCidade['Cidad']['valor'];
					return $frete;
				} else {
					$meuBairro = $this->Bairro->find('first', array(
						'recursive' => -1,
						'conditions' => array(
							'id' => $bairro,
							'ativo' => 1
						)
					));

					if (!empty($meuBairro)) {
						//$frete=number_format($meuBairro['Bairro']['valor'],2,',','.');
						$frete = $meuBairro['Bairro']['valor'];
						return $frete;
					} else {
						if ($filial['Filial']['locais_especificos'] != true) {
							if ($filial['Filial']['valor_padrao'] != '') {
								//$frete=number_format($filial['Filial']['valor_padrao'],2,',','.');
								$frete = $filial['Filial']['valor_padrao'];
								return $frete;
							} else {
								return false;
							}
						} else {
							return false;
						}
					}
				}
			} else {
				if ($filial['Filial']['locais_especificos'] != true) {
					if ($filial['Filial']['valor_padrao'] != '') {
						//$frete=number_format($filial['Filial']['valor_padrao'],2,',','.');
						$frete = $filial['Filial']['valor_padrao'];
						return $frete;
					} else {
						return false;
					}
				} else {
					return false;
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
	public function edit($id = null)
	{
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('A empresa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {


			$this->request->data = $this->Empresa->find('first', array('recursive' => -1, 'conditions' => array('Empresa.id' => $id)));
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->Empresa->id = $id;
		if (!$this->Empresa->exists()) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Empresa->delete()) {
			$this->Session->setFlash(__('A empresa foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function meucadastro()
	{
		$this->layout = "login";
		$this->loadModel('Salt');


		$this->loadModel('Autorizacao');
		$this->loadModel('Entregador');

		$this->loadModel('Mesa');


		$token = $this->geraSenha(30, false, true);



		//Validações
		if ($this->request->is('post')) {
			$this->loadModel('User');
			$hasUser = $this->User->find('first', array('recursive' => -1, 'conditions' => array(
				'username' => $this->request->data['Empresa']['email']
			)));
			if (!empty($hasUser)) {
				$this->Session->setFlash(__('Já existe um cadastro com este email! Por favor, escolha outro email ou recupere sua senha na tela de login!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
			if ($this->request->data['Empresa']['nome'] == '') {
				$this->Session->setFlash(__('O campo nome do estabelecimento é obrigatório'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
			if ($this->request->data['Empresa']['email'] == '') {
				$this->Session->setFlash(__('O campo email é obrigatório'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}

			if ($this->request->data['Empresa']['password'] == '') {
				$this->Session->setFlash(__('O campo senha é obrigatório'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}

			if ($this->request->data['Empresa']['password'] != $this->request->data['Empresa']['re_password']) {
				$this->Session->setFlash(__('O campo senha deve ser igual ao confirme a sua senha.'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
			$mySlug= $this->geraSlug($this->request->data['Empresa']['slug']);
			if ($mySlug==false) {
				$this->Session->setFlash(__('O endrereço menu.rudo.com.br/'.$this->request->data['Empresa']['slug'].' já está sendo utilizado'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}

			//Cadastro da empresa
			$this->loadModel('Empresa');
			$dataEmpresa = array(
				'nome' => $this->request->data['Empresa']['nome'],
				'status_empresa' => true,
			);
			$this->Empresa->save($dataEmpresa);
			$ultimaEmpresa = $this->Empresa->find('first', array('conditions' => array('Empresa.nome' => $this->request->data['Empresa']['nome']), 'order' => array('Empresa.id' => 'desc'), 'recursive' => -1));
			//Cadastro da Filial
			$this->loadModel('Filial');
			$dataFilial = array(
				'nome' => $ultimaEmpresa['Empresa']['nome'],
				'empresa_id' => $ultimaEmpresa['Empresa']['id'],
				'status_empresa' => 1,
				'ativo' => 1,
				'status_abertura' => 0,
				'locais_especificos' => 1,
				'abre_segunda' => 0,
				'abre_terca' => 0,
				'abre_quarta' => 0,
				'abre_quinta' => 0,
				'abre_sexta' => 0,
				'abre_sabado' => 0,
				'abre_domingo' => 0,
				'tempo_amarelo' => 40,
				'tempo_verde' => 30,
				'tempo_vermelho' => 60,
				'slug' => $mySlug
			);
			$this->Filial->save($dataFilial);
			//Falta atualizar o filial_id e o user_id da filial
			$ultimaFilial = $this->Filial->find('first', array('conditions' => array('Filial.nome' => $this->request->data['Empresa']['nome']), 'order' => array('Filial.id' => 'desc'), 'recursive' => -1));


			//Cadastro Função 
			$this->loadModel('Funcao');
			$dataFuncao = array(
				'funcao' => 'Admin',
				'filial_id' => $ultimaFilial['Filial']['id'],
				'empresa_id' => $ultimaEmpresa['Empresa']['id']
			);
			$this->Funcao->save($dataFuncao);

			$ultimaFuncao = $this->Funcao->find('first', array('conditions' => array('Funcao.funcao' => 'Admin'), 'order' => array('Funcao.id' => 'desc'), 'recursive' => -1));

			//Cadastro do Salt
			$dataSalt = array(
				'salt' => $this->geraSenha(30, true, true, true),
				'empresa_id' => $ultimaEmpresa['Empresa']['id'],
				'filial_id' => $ultimaFilial['Filial']['id']
			);

			$this->Salt->save($dataSalt);
			//Cadastro Usuario


			$dataUser = array(
				'username' => $this->request->data['Empresa']['email'],
				'nome' => $this->request->data['Empresa']['nome'],
				'password' => $this->request->data['Empresa']['password'],
				'ativo' => 1,
				'empresa_id' => $ultimaEmpresa['Empresa']['id'],
				'funcao_id' => $ultimaFuncao['Funcao']['id']
			);
			$this->User->save($dataUser);
			$ultimoUser = $this->User->find('first', array('conditions' => array('User.username' => $this->request->data['Empresa']['email']), 'order' => array('User.id' => 'desc'), 'recursive' => -1));



			$dataAutorizacao = array(
				'nome' => 'admin',
				'funcao_id' => $ultimaFuncao['Funcao']['id'],
				'filial_id' => $ultimaFilial['Filial']['id'],
				'empresa_id' => $ultimaEmpresa['Empresa']['id'],
				'pedidos' => 'a',
				'mensagens' => 'a',
				'mapas' => 'a',
				'clientes' => 'a',
				'produtos' => 'a',
				'entregadores' => 'a',
				'formas_de_pagamento' => 'a',
				'usuarios' => 'a',
				'funcoes' => 'a',
				'confirmar' => 'm',
				'preparar' => 'm',
				'separar' => 'm',
				'enviar' => 'm',
				'entregar' => 'm',
				'cancelar' => 'm',
				'relatorios' => 'm',
				'ativo' => 1
			);


			$this->Autorizacao->save($dataAutorizacao);
			$updateFilial = array(
				'id' => $ultimaFilial['Filial']['id'],
				'user_id' => $ultimoUser['User']['id'],
				'filial_id' => $ultimaFilial['Filial']['id']
			);

			$this->loadModel('UsersFilial');
			$dataUsersFilial = array(
				'user_id' => $ultimoUser['User']['id'],
				'filial_id' => $ultimaFilial['Filial']['id']
			);
			$this->UsersFilial->save($dataUsersFilial);
			if ($this->Filial->save($updateFilial)) {
				$this->Session->setFlash(__('Seu registro foi salvo com sucesso. Faça seu login com seu email e senha'), 'default', array('class' => 'success-flash alert alert-success'));

				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}
	}
	public function pagseguromobile()
	{
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		date_default_timezone_set("Brazil/East");

		
		$this->layout = 'ajaxaddpedido';
		
		$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/pre-approvals/request';
		$data['email'] = EMAIL_PAG_SEGURO;
		$data['token'] = TOKEN_PAGSEGURO;
		$data['currency'] = 'BRL';
		$data['reference'] = 'Referencia_1';
		$data['senderName'] = 'Comprador de testes';
		$data['senderEmail'] = 'c20735891930399985331@sandbox.pagseguro.com.br';
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
		/*$this->Assinatura->save(array(
			'tipo' => 'PAGSEGURO',
			'Id_pagseguro' => $_GET['ref'],
			'codigo_pag' => $xml->code,
			'status' => 'Novo'
		));
		$this->set(array(
			'ultimopedido' => $xml->code,
			'_serialize' => array('ultimopedido')
		));*/
		
		print_r($xml->code);

		die();



	}
	
	public function minhaassinatura()
	{
		error_reporting(0);
		ini_set('display_errors', 0);
		$this->loadModel('Pagseguro');
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		$hasPagseguro= $this->Pagseguro->find('first', array('recursive'=> -1, 'conditions'=> array(
			'filial_id'=>$minhasFiliais
		)));
		

		if(empty($hasPagseguro)){
			$hasPagseguro= $this->_gerabotaoPagseguro();
		}
		
		$this->set(compact('hasPagseguro'));
		
	}

	
	public function _gerabotaoPagseguro()
	{
		error_reporting(0);
		ini_set('display_errors', 0);
		$this->loadModel('Pagseguro');
		$this->loadModel('Filial');
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		$unicaFilial = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $minhasFiliais)));

		$paymentRequest = new PagSeguroPreApprovalRequest();  
		$paymentRequest->setCurrency("BRL");  
		
		
		$paymentRequest->setPreApprovalCharge('AUTO');
		$paymentRequest->setPreApprovalName("Plano Padrão");
		$paymentRequest->setPreApprovalDetails("Assinatura mensal para o aplataforma Rudo no valor de R$ 29,90.");
		$paymentRequest->setPreApprovalAmountPerPayment('29.90');
		$paymentRequest->setSenderName($unicaFilial['Filial']['nome']);
		$paymentRequest->setSenderEmail($unicaFilial['Filial']['email']);
		//$paymentRequest->setPreApprovalMaxAmountPerPeriod('200.00');
		$paymentRequest->setPreApprovalPeriod('Monthly');
		//$paymentRequest->setPreApprovalMaxTotalAmount('2400.00');
		//$paymentRequest->setPreApprovalInitialDate($data['inicial']);
		//$paymentRequest->setPreApprovalFinalDate($data['final']);
		
		//$paymentRequest->setReviewURL("http://www.lojateste.com.br/review");
		
		// Referenciando a transação do PagSeguro em seu sistema  
		$referecia=$this->geraSenha(7, false, true, false);
		$paymentRequest->setReference("".$minhasFiliais[0].$referecia."");  
		
		// URL para onde o comprador será redirecionado (GET) após o fluxo de pagamento  
		$paymentRequest->setRedirectUrl("http://sistema.rudo.com.br/");
		
		// URL para onde serão enviadas notificações (POST) indicando alterações no status da transação  
		//$paymentRequest->addParameter('notificationURL', 'https://tutoriaiseinformatica.com/sdkpagseguro/response.php');
		
		try {

		
			
			// seller authentication
			$credentials = new PagSeguroAccountCredentials(
				EMAIL_PAG_SEGURO,
				TOKEN_PAGSEGURO
			);
			
			$sessionId = PagSeguroSessionService::getSession($credentials);
			
			// application authentication
			//$credentials = PagSeguroConfig::getApplicationCredentials();

			//$credentials->setAuthorizationCode("E231B2C9BCC8474DA2E260B6C8CF60D3");

			// Register this payment request in PagSeguro to obtain the checkout code
			$onlyCheckoutCode = false;
			
			$code = $paymentRequest->register($credentials);
			//$this->Session->read('Auth.User.funcao_id');
			
			$PagseguroToSave=array(
				'filial_id' =>$minhasFiliais[0],
				'empresa_id'=> $this->Session->read('Auth.User.empresa_id'),
				'code'=>$code['code'],
				'cancelurl'=>$code['cancelUrl'],
				'checkouturl'=> $code['checkoutUrl'],
				'user_id'=>$this->Session->read('Auth.User.id'),
				'reference'=>$minhasFiliais[0].$referecia,
				'status'=>'Link Gerado'
			);
			/*$this->Pagseguro->deleteAll(
				array(
					'Pagseguro.empresa_id'=>$this->Session->read('Auth.User.empresa_id'),
					'Pagseguro.filial_id'=>$minhasFiliais[0]
				)
			);*/
			$this->Pagseguro->save($PagseguroToSave);


			$hasPagseguro = $this->Pagseguro->find('first', array('conditions' => array('empresa_id' => $this->Session->read('Auth.User.empresa_id')), 'order' => array('id' => 'desc'), 'recursive' => -1));
			
			//die;
			return $hasPagseguro;
			//self::printPaymentUrl($code);
		} catch (PagSeguroServiceException $e) {
			//die($e->getMessage());
			return array();
		}
	}
	
	
	
	public function verificastatusdasassinaturas()
	{
		//$url = 'https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'';
		$hoje=date("Y-m-d");
		$agora=date("H:i:s");
		$ontem= $stop_date = date('Y-m-d', strtotime($hoje . ' -30 day'));

		
		$url ='https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'&initialDate='.$ontem.'T00:00&finalDate='.$hoje.'T'.$agora.'&page=1';
		
		
		$cURLConnection = curl_init();

		curl_setopt($cURLConnection, CURLOPT_URL, $url);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
		));

		$output = curl_exec($cURLConnection);
		if ($output === false) {
			$err = 'Curl error: ' . curl_error($cURLConnection);
			curl_close($cURLConnection);
			print $err;
		} else {
			//print_r($output);
			$jsonArrayResponse = json_decode($output,true);
			$results= $this->_getresultAssinatura($jsonArrayResponse);
			curl_close($cURLConnection);
			//print_r($results);
			
			
			//print 'Operation completed without any errors';
		}
	
		
		die('aqui linha - 828');
		
		
		
		
	}
	public function _getresultAssinatura($assinaturas){
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);
		
		$newResult=array();
		
		if($assinaturas['totalPages']==1){
			
			foreach ($assinaturas['preApprovalList'] as $key => $value) {
				
				array_push($newResult,$value);
			}
			
		}else{
			for ($i=1; $i <= $assinaturas['totalPages'] ; $i++) { 
				
				
				$hoje=date("Y-m-d");
				$agora=date("H:i:s");
				$ontem= $stop_date = date('Y-m-d', strtotime($hoje . ' -30 day'));

				
				$url ='https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'&initialDate='.$ontem.'T00:00&finalDate='.$hoje.'T'.$agora.'&page='.$i.'';
				
				
				$cURLConnection = curl_init();

				curl_setopt($cURLConnection, CURLOPT_URL, $url);
				curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
				));

				$output = curl_exec($cURLConnection);
				if ($output === false) {
					$err = 'Curl error: ' . curl_error($cURLConnection);
					curl_close($cURLConnection);
					print $err;
				} else {
					//print_r($output);
					$jsonArrayResponse = json_decode($output,true);

					foreach ($jsonArrayResponse['preApprovalList'] as $key => $value) {
						array_push($newResult,$value);
					}
					
					curl_close($cURLConnection);
					
					//print 'Operation completed without any errors';
				}
			}
		}
		$this->loadModel('Pagseguro');
		$isInArray=array();
		
		
		$allAccounts= $hasPagseguro= $this->Pagseguro->find('all', array('recursive' => -1));
		$hasPagseguro= array();
		foreach ($newResult as $key4 => $value4){
			$hasPagseguro[$value4['reference']]=array();
			
		}

		foreach ($newResult as $key5 => $value5){
			array_push($hasPagseguro[$value5['reference']], $value5);
			
		}
		echo '<pre>';
			print_r( $newResult);
			die;
		foreach ($allAccounts as $key3 => $value3) {
			# code...
			print_r($value3);
		die;
		}
		
		/*foreach ($newResult as $key2 => $value2){
			if(!isset($isInArray[$value2['reference']])){
				$hasPagseguro= $this->Pagseguro->find('first', array('recursive'=> -1, 'conditions'=> array(
					'reference'=>$value2['reference']
				)));
				//echo '<pre>';
				//print_r($hasPagseguro);
				if(!empty($hasPagseguro)){
					//echo '<pre>';
					//print_r($value2);
	
					$auxDate=explode('T', $value2['date']);
					$date=$auxDate[0];
					$dataToSave= array(
						'event_date'=>$date,
						'tracker'=>$value2['tracker'],
						'last_tran_code'=>$value2['code'],
						'status'=>$value2['status'],	
						'id'=>$hasPagseguro['Pagseguro']['id']
					);
					$this->Pagseguro->save($dataToSave);
					$isInArray[$value2['reference']]=$value2['status'];
				}
			}			
		}*/
		
		//die;

		
	}
	public function criarplanoteste()
	{
		

		$soap_request='<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>
		<preApprovalRequest>
		<preApproval>
		<name>Plano - Teste</name>
		<reference>TESTEREF</reference>
		<charge>AUTO</charge>
		<period>MONTHLY</period> //
		<amountPerPayment>29.90</amountPerPayment>
		<cancelURL>http://sitedocliente.com</cancelURL>
		</preApproval>
		</preApprovalRequest>';
		$header = array(
			"Content-type:application/xml;charset=ISO-8859-1",
			"Accept: application/vnd.pagseguro.com.br.v3+xml;charset=ISO-8859-1",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: \"run\"",
			"Content-length: " . strlen($soap_request),
		);

		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL, "https://ws.sandbox.pagseguro.uol.com.br/pre-approvals/request/?email=".EMAIL_PAG_SEGURO."&token=".TOKEN_PAGSEGURO."");
		curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($soap_do, CURLOPT_TIMEOUT,        10);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($soap_do, CURLOPT_POST,           true);
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,     $soap_request);
		curl_setopt($soap_do, CURLOPT_HTTPHEADER,     $header);
		$output = curl_exec($soap_do);
		if ($output === false) {
			$err = 'Curl error: ' . curl_error($soap_do);
			curl_close($soap_do);
			print $err;
		} else {
			print_r($output);
			curl_close($soap_do);
			
			print 'Operation completed without any errors';
		}
		die;
	}
	public function criarautorizacaoteste()
	{
		$url =   "https://ws.sandbox.pagseguro.uol.com.br/sessions/sessions?email=".EMAIL_PAG_SEGURO."&token=".TOKEN_PAGSEGURO."";

		//Utilizar o CURL para realizar a requisição ao PagSeguro
		//http://br2.php.net/manual/pt_BR/function.curl-setopt.php
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8"));
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$retorno = curl_exec($curl);
		print_r($retorno);
		die;
		
		curl_close($curl);
		
		$xml = simplexml_load_string($retorno);
		echo json_encode($xml);
	}
	public function criarassunaturateste()
	{
		//$url = 'https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'';
		$url ='https://ws.sandbox.pagseguro.uol.com.br/pre-approvals?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'';
		//$data = array("first_name" => "First name","last_name" => "last name","email"=>"email@gmail.com","addresses" => array ("address1" => "some address" ,"city" => "city","country" => "CA", "first_name" =>  "Mother","last_name" =>  "Lastnameson","phone" => "555-1212", "province" => "ON", "zip" => "123 ABC" ) );
		
		$credentials = new PagSeguroAccountCredentials(
			EMAIL_PAG_SEGURO,
			TOKEN_PAGSEGURO
		);
		
		$sessionId = PagSeguroSessionService::getSession($credentials);

		
		$str='{
			"plan":"659AB301DFDFE684448C1FB8B86F28F8",
			"reference":"ID-CND",
			"sender":{
				"name":"Comprador testes",
				"email":"adesao@sandbox.pagseguro.com.br",
				"ip":"192.168.0.1",
				"hash":"hash",
				"phone":{
				"areaCode":"11",
				"number":"999999999"
			},
			"address":{
				"street":"Av. Brigadeira Faria Lima",
				"number":"1384",
			   "complement":"3 andar",
				"district":"Jd. Paulistano",
				"city":"São Paulo",
				"state":"SP",
				"country":"BRA",
				"postalCode":"01452002"
			},
			"documents":[
				{
					"type":"CPF",
					"value":"00000000191"
				}
			]
			},
			"paymentMethod":{
				"type":"CREDITCARD",
				"creditCard":{
				"token":"'.$sessionId.'",
				"holder":{
					"name":"Nome Comprador",
					"birthDate":"11/01/1984",
					"documents":[
					{
						"type":"CPF",
						"value":"00000000191"
					}
				],
				"billingAddress":{
					"street":"Av. Brigadeiro Faria Lima",
					"number":"1384",
					"complement":"3 andar",
					"district":"Jd. Paulistano",
					"city":"São Paulo",
					"state":"SP",
					"country":"BRA",
					"postalCode":"01452002"
				},
				"phone":{
					"areaCode":"11",
					"number":"988881234"
				}
			}
			}
			}
		}';
		$data=json_decode($str,true);
		$postdata = json_encode($data);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Accept: application/vnd.pagseguro.com.br.v3+json;charset=ISO-8859-1'
		));
		

		$output = curl_exec($ch);
		if ($output === false) {
			$err = 'Curl error: ' . curl_error($ch);
			curl_close($ch);
			print $err;
		} else {
			print_r($output);
			$jsonArrayResponse = json_decode($output,true);
			echo "<pre>";
			print_r($jsonArrayResponse);
			curl_close($ch);
			
			//print 'Operation completed without any errors';
		}
		die;
	}
	function geraSlug($nome = '')
	{
		$slug = str_replace(' ', '-', trim($nome));
		$slug = str_replace('--', '-', $nome);
		$slug = str_replace('--', '-', $nome);
		$slug = str_replace('@', '', $nome);
		$slug = str_replace('/', '', $nome);
		$slug = str_replace('#', '', $nome);
		$slug = str_replace('´', '', $nome);
		$slug = str_replace('`', '', $nome);
		$slug = strtolower($slug);
		$slug = $this->sanitizeString($slug); 
		$this->loadModel('Filial');
		
		$hasSlug = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.slug' => $slug)));

		if(empty($hasSlug)){
			return $slug;
		}else{
			return false;
		}
		
	}

	function sanitizeString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
		//$str = preg_replace('/[^a-z0-9]/i', '_', $str);
		$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
		return $str;
	}
	function isValid($data = array())
	{
		$isValidCad = true;
		if ($data['Empresa']['nome'] == '') {
			$isValidCad = false;
		}
		if ($data['Empresa']['nome'] == '') {
			$isValidCad = false;
		}
		return $isValidCad;
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
}
