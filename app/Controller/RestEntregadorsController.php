<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
class RestEntregadorsController extends AppController {
    public $uses = array('Entregador');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');

	public function checkToken(&$entregadorId,&$token){
		$this->loadModel('Entregador');
		$entregador = $this->Entregador->find('first', array('conditions' => array('AND' => array(array('Entregador.id' => $entregadorId), array('Entregador.token' => $token), array('Entregador.ativo' => 1)))));

		if(!empty($entregador)){
			$resposta = "OK";
			return $resposta;
		}else{
			$resposta = "NOK";
			return $resposta;
			$entregadorUp= array('id'=> $entregadorId, 'ativo' => 0);
			$this->Entregador->create();
			$this->Entregador->save($entregadorUp);
		}

	}


	public function loginmobile() {
    $this->loadModel('Entregador');
		$this->loadModel('Salt');
		$this->layout ='ajaxaddpedido';
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
		$senha= $this->Auth->password($this->request->data['password']);
		$usuario =$this->request->data['username'];
		$saltenviado = $this->request->data['salt'];

		if ($this->request->is('post')) {
			if($this->request->data['salt'] == $salt['Salt']['salt']){
				$ultimopedido="inicio";

				$user=$this->Entregador->find('first', array('recursive' => -1,'conditions' => array('AND' => array( array('Entregador.password' => $senha), array('Entregador.username' => $usuario)))));

				$Empresa = new EmpresasController;
				if(!$Empresa->empresaAtiva()){
					$ultimopedido="ErroLogin";
				}else{
					if(!empty($user)){
						if($user['Entregador']['ativo'] == 1){
							$ultimopedido=$user;
							$token = $this->geraSenha(10, false, true);
							$ultimopedido['Entregador']['token']= $token;
							$this->loadModel('Empresa');
							$empresa= $this->Empresa->find('first', array('recursive' => -1,'conditions' => array('Empresa.id' => 1)));
							$uptadeToken = array('id' => $user['Entregador']['id'],'token' => $token, 'latdest' =>$empresa['Empresa']['lat'], 'lgndest'=> $empresa['Empresa']['lng'] );
							$ultimopedido['Entregador']['latdest']= $empresa['Empresa']['lat'];
							$ultimopedido['Entregador']['lngdest']= $empresa['Empresa']['lng'];
							$this->loadModel('Pagamento');
							$pagamentos = $this->Pagamento->find('all', array('recursive' => -1));

							foreach ($pagamentos as $key => $value) {
								$ultimopedido['Pagamento'][$key]= $value['Pagamento'];
							}
							$this->Entregador->create();
							$this->Entregador->save($uptadeToken);
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


	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
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
