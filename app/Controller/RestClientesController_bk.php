<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
class RestClientesController extends AppController {
    public $uses = array('Cliente');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

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


	public function loginmobile() {
        $this->loadModel('Cliente');
		$this->loadModel('Salt');
		$this->layout ='ajaxaddpedido';
		header("Access-Control-Allow-Origin: *");
		$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
		$senha= $this->Auth->password($this->request->data['password']);
		$usuario =$this->request->data['username'];
		$saltenviado = $this->request->data['salt'];

		if ($this->request->is('post')) {
			if($this->request->data['salt'] == $salt['Salt']['salt']){
				$ultimopedido="inicio";

				$user=$this->Cliente->find('first', array('recursive' => -1,'conditions' => array('AND' => array( array('Cliente.password' => $senha), array('Cliente.username' => $usuario)))));

				$Empresa = new EmpresasController;
				if(!$Empresa->empresaAtiva()){
					$ultimopedido="ErroLogin";
				}else{
					if(!empty($user)){
						if($user['Cliente']['ativo'] == 1){
							$ultimopedido=$user;
							$token = $this->geraSenha(10, false, true);
							$ultimopedido['Cliente']['token']= $token;
							$this->loadModel('Empresa');
							$empresa= $this->Empresa->find('first', array('recursive' => -1,'conditions' => array('Empresa.id' => 1)));
							$uptadeToken = array('id' => $user['Cliente']['id'],'token' => $token, 'latdest' =>$empresa['Empresa']['lat'], 'lgndest'=> $empresa['Empresa']['lng'] );
							$ultimopedido['Cliente']['latdest']= $empresa['Empresa']['lat'];
							$ultimopedido['Cliente']['lngdest']= $empresa['Empresa']['lng'];
							$this->loadModel('Pagamento');
							$pagamentos = $this->Pagamento->find('all', array('recursive' => -1));

							foreach ($pagamentos as $key => $value) {
								$ultimopedido['Pagamento'][$key]= $value['Pagamento'];
							}
							$this->Cliente->create();
							$this->Cliente->save($uptadeToken);
						//$this->Auth->allow('*');
						}else{

							$ultimopedido="ErroLogin";

						}
					}else{
						$ultimopedido="ErroLogin";
					}
					 //$ultimopedido =$senha;

				}



				$this->set(array(
					'ultimopedido' => $ultimopedido,
					'_serialize' => array('ultimopedido')
				));
			}else{

			}
		}

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

	public function addmobile() {

		header("Access-Control-Allow-Origin: *");

		if ($this->request->is('post')) {
			$this->loadModel('Salt');
			$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
			if($this->request->data['Cliente']['salt'] == $salt['Salt']['salt']){

				$this->Cliente->create();
				if($this->request->data['Cliente']['password'] ==""){
					unset($this->request->data['Cliente']['password']);
				}
				$clienteExistente = $this->Cliente->find('first', array('conditions' => array('Cliente.username' => $this->request->data['Cliente']['username'])));
				if(!empty($clienteExistente)){
					if($this->request->data['Cliente']['id'] == $clienteExistente['Cliente']['id']){

						if ($this->Cliente->save($this->request->data)) {

							$ultimocliente = $this->Cliente->find('first', array('conditions' => array('Cliente.id' => $this->request->data['Cliente']['id']), 'recursive' => -1));

						} else {
							$ultimocliente="Erro";
						}
					}else{
						$ultimocliente='ErroUsuarioDuplo';
					}
				}else{

					$this->request->data['Cliente']['ativo']= 1;
					if ($this->Cliente->save($this->request->data)) {

						$ultimocliente = $this->Cliente->find('first', array('order' => array('Cliente.id' => 'desc'), 'recursive' => -1));

					} else {
						$ultimocliente="Erro";
					}
				}

				$this->set(array(
						'ultimocliente' => $ultimocliente,
						'_serialize' => array('ultimocliente')
					));
			}
		}

	}
}