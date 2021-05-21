<?php
App::uses('AppController', 'Controller');
class RestFilialsController extends AppController {
    public $uses = array('Filial');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

	public function indexmobile() {
		$this->layout="liso";
		$empresa = $_GET['e'];
		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){
			header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
			$resultados =  $this->Filial->find('all', array('recursive' => -1, 'conditions'=> array('Filial.empresa_id'=> $empresa)));
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		//}
    }
	
	public function getdatafilial() {
		$this->layout="liso";
		$empresa = $_GET['e'];
		$this->loadModel('Salt');
		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){
			header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
			$resultados =  $this->Filial->find('first', array('recursive' => -1,'fields'=> array('id','empresa_id' ), 'conditions'=> array('Filial.slug'=> $empresa)));
			$salt= $this->Salt->find('first', array('recursive'=> -1 ,'conditions'=> array('Salt.filial_id'=> $resultados['Filial']['id'])));
			
			$resultados['Filial']['idx']=$salt['Salt']['salt'];
			
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		//}
    }

	public function addfilial() {
		$this->layout="liso";
		$this->loadModel('Salt');
		
		
		$this->loadModel('Autorizacao');
		$this->loadModel('Entregador');
		
		$this->loadModel('Mesa');
		
		
		$token = $this->geraSenha(30, false, true);

		
		
		//Validações
		if ($this->request->is('post')) {
			if($this->isValid($this->request->data)){
				
				//Cadastro da empresa
				$this->loadModel('Empresa');
				$this->Empresa->save($this->request->data['Empresa']);
				$ultimaEmpresa = $this->Empresa->find('first', array('conditions' => array('Empresa.nome' => $this->request->data['Empresa']['nome']), 'order' => array('Empresa.id' => 'desc'), 'recursive' => -1));
				//Cadastro da Filial
				$this->loadModel('Filial');
				$dataFilial= array(
					'nome'=> $ultimaEmpresa['Empresa']['nome'],
					'empresa_id'=> $ultimaEmpresa['Empresa']['id'],
					'status_empresa'=> 1,
					'ativo'=> 1,
					'status_abertura' => 0,
					'locais_especificos'=>1,
					'abre_segunda'=> 0,
					'abre_terca'=> 0,
					'abre_quarta'=> 0,
					'abre_quinta'=> 0,
					'abre_sexta'=> 0,
					'abre_sabado'=> 0,
					'abre_domingo'=> 0,
					'tempo_amarelo'=>40,
					'tempo_verde'=>30,
					'tempo_vermelho'=>60,
					'slug' =>$this->request->data['Filial']['slug']
				);
				$this->Filial->save($dataFilial);
				//Falta atualizar o filial_id e o user_id da filial
				$ultimaFilial = $this->Filial->find('first', array('conditions' => array('Filial.nome' => $this->request->data['Empresa']['nome']), 'order' => array('Filial.id' => 'desc'), 'recursive' => -1));


				//Cadastro Função 
				$this->loadModel('Funcao');
				$dataFuncao= array(
					'funcao'=> 'Admin',
					'filial_id'=>$ultimaFilial['Filial']['id'],
					'empresa_id'=>$ultimaEmpresa['Empresa']['id'] 
				);
				$this->Funcao->save($dataFuncao);
				//Cadastro Usuario
				$this->loadModel('User');

				$dataUser=array(
					'username'=> $this->request->data['User']['username'],
					'nome'=> $this->request->data['User']['username'],
					'password' => $this->request->data['User']['password'],
					'ativo'=> 1,
					'empresa_id' => $ultimaEmpresa['Empresa']['id']
				);
				
			}
		}
		
		
	}
	function isValid($data = array())
	{
		$isValidCad=true;
		if($data['Empresa']['nome'] ==''){
			$isValidCad=false;
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

	/***
	 * Método que verifica se a assinatura foi paga 
	 * 
	 * ***/
	public function validaodersdepagamento()
	{
		
		$this->loadModel('Pagseguro');
		$this->loadModel('Filial');
		
		$this->layout="liso";

		$planos=$this->Pagseguro->find('all', array('recursive'=> -1));
		//Quando for o Geek não valida
		$hasPlan= array(1);
		
		foreach($planos as $key => $value){
			array_push($hasPlan,$value['Pagseguro']['filial_id']);
			
			//$url ='https://'.PAG_SEGURO_URL.'/pre-approvals/preApprovalCode/payment-orders??email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'';
			$url ='https://'.PAG_SEGURO_URL.'/pre-approvals/'.$value['Pagseguro']['code'].'/payment-orders?email='.EMAIL_PAG_SEGURO.'&token='.TOKEN_PAGSEGURO.'';
			
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
			//$dataLimite= $stop_date = date('Y-m-d', strtotime($hoje . ' +7 day'));
			if ($output === false) {
				$err = 'Curl error: ' . curl_error($cURLConnection);
				curl_close($cURLConnection);
				print $err;
			} else {
				//print_r($output);
				
				$jsonArrayResponse = json_decode($output,true);
				$minhaFilial= $this->Filial->find('first', array('recursive'=> -1,'conditions' => array(
					'id'=>$value['Pagseguro']['filial_id']
				)));
				$hoje=date('Y-m-d');
				$dataMenosSete = date('Y-m-d', strtotime(date('Y-m-d') . ' -7 day'));
				if(!empty($minhaFilial)){
					$dataMenosSete = $minhaFilial['Filial']['created'] ;
				}
				$dataLimite = date('Y-m-d', strtotime($dataMenosSete . ' +7 day'));

				
			if($dataLimite < $hoje){
				if(!empty($jsonArrayResponse['paymentOrders'])){
					$count=0;
					
					foreach ($jsonArrayResponse['paymentOrders'] as $key2 => $value2) {
						$newDateAux=explode('T', $value2['schedulingDate']);
						$newDate=$newDateAux[0];
						$thisMonth= date('m');
						$today=date('Y-m-d');
						$dateMonthAux=explode('-', $newDate);
						$dateMonth = $dateMonthAux[1];
						
						if($newDate <  $today  && $count==0){
							$count++;
							
							if(!empty($value2['transactions'])){
								
								$flagPago=false;
								foreach ($value2['transactions'] as $key5 => $value5) {
									if($value5['status']==3){
										$flagPago=true;
									}
								}
								if($flagPago==false){
									$dataLimite = date('Y-m-d', strtotime($newDate . ' +3 day'));
									
									if($dataLimite <=  $today){
										$dataToSave = array(
											'id'=>$value['Pagseguro']['id'],
											'status'=> 'CANCELLED'
										);
										
										$this->Pagseguro->save($dataToSave);
										$this->_removeautorizacoes($value['Pagseguro']['filial_id'],$value['Pagseguro']['empresa_id']);
										
										
									}
									$data_inicial = $newDate;
									$data_final = date('Y-m-d');
									$diferenca = strtotime($data_final) - strtotime($data_inicial);

									$dias = floor($diferenca / (60 * 60 * 24));
									
								}else{
									
									
									$data_inicial = $newDate;
									$data_final = date('Y-m-d');
									$diferenca = strtotime($data_final) - strtotime($data_inicial);

									$dias = floor($diferenca / (60 * 60 * 24));
									//Se o ultimo pagamento tem menos de 31 dias ativa, senão cancela 
									if($dias < 31){
										$dataToSave = array(
											'id'=>$value['Pagseguro']['id'],
											'status'=> 'ACTIVE'
										);
										$this->Pagseguro->save($dataToSave);
										$this->_concedeautorizacoes($value['Pagseguro']['filial_id'],$value['Pagseguro']['empresa_id']);
									}else{
										$dataToSave = array(
											'id'=>$value['Pagseguro']['id'],
											'status'=> 'CANCELLED'
										);
										
										$this->Pagseguro->save($dataToSave);
										$this->_removeautorizacoes($value['Pagseguro']['filial_id'],$value['Pagseguro']['empresa_id']);
									}
									
								}
							}
						}else{
							$flagPago=false;
							foreach ($value2['transactions'] as $key5 => $value5) {
								if($value5['status']==3){
									$flagPago=true;
								}
							}
							if($flagPago==true){
								$count++;
							}
						}
						
						
					}
					
				}else{
					if(date('Y-m-d') > $dataLimite ){
						$dataToSave = array(
							'id'=>$value['Pagseguro']['id'],
							'status'=> 'CANCELLED'
						);
						$this->Pagseguro->save($dataToSave);
						$this->_removeautorizacoes($value['Pagseguro']['filial_id'],$value['Pagseguro']['empresa_id']);
					}
				}
			}
				
				
				
				curl_close($cURLConnection);
				
				//print 'Operation completed without any errors';
			}
		}
		
	
	
		$filiais = $this->Filial->find('all', array('recursive'=> -1));
		foreach ($filiais as $key => $value) {
			if(!in_array($value['Filial']['id'],$hasPlan)){
				$dataLimite = date('Y-m-d', strtotime($value['Filial']['created'] . ' +7 day'));
				if(date('Y-m-d') > $dataLimite  || $value['Filial']['created']=='' ){
					$this->_removeautorizacoes($key['Filial']['id'],$value['Filial']['empresa_id']);
				}
			}
			
		}
		
		$resultados= array('resposta' =>'sucesso');
		$this->set(array(
			'resultados' => $resultados,
			'_serialize' => array('resultados')
		));
		
		
	}
	public function _removeautorizacoes($filial_id=null,  $empresa_id=null)
	{
		
		$this->loadModel('User');
		$this->loadModel('Filial');
		$this->loadModel('Empresas');
		$usuarios = $this->User->find('all', array('recursive'=> -1,'conditions' =>array(
			'empresa_id'=>$empresa_id
		)));
		foreach ($usuarios as $key => $value) {
			$userToSave= array(
				'id'=> $value['User']['id'],
				'funcao_id'=>0
			);
			$this->User->save($userToSave);
			$filiais=$this->Filial->find('all', array('recursive'=> -1, 'conditions'=> array('Filial.empresa_id'=> $value['User']['empresa_id'])));
			if(!empty($filiais)){
				foreach ($filiais as $key2 => $value2) {
					$filialToSave= array(
						'id'=> $value2['Filial']['id'],
						'status_abertura' =>0
					);
					$this->Filial->save($filialToSave);
				}
			}
			
		}
		
		
	}
	public function _concedeautorizacoes($filial_id=null,  $empresa_id=null)
	{
		$this->loadModel('User');
		$this->loadModel('Funcao');
		$funcao= $this->Funcao->find('first', array('recursive'=> -1, 'conditions' => array('Funcao.filial_id'=> $filial_id)));
		$usuarios = $this->User->find('all', array('recursive'=> -1,'conditions' =>array(
			'empresa_id'=>$empresa_id
		)));
		foreach ($usuarios as $key => $value) {
			$userToSave= array(
				'id'=> $value['User']['id'],
				'funcao_id'=> $funcao['Funcao']['id']
			);
			$this->User->save($userToSave);
		}
	}
}