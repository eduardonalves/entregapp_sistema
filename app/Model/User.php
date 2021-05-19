<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('Token', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $actsAs = array(
                    'Containable',
                );
  	public $displayField = 'username';
	public $validate = array(

		'funcao_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'ativo' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Funcao' => array(
			'className' => 'Funcao',
			'foreignKey' => 'funcao_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Atendimento' => array(
			'className' => 'Atendimento',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Mensagen' => array(
			'className' => 'Mensagen',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	public $hasAndBelongsToMany = array(
                'Filial' => array(
                    'className' => 'Filial',
                    'joinTable' => 'users_filials',
                    'foreignKey' => 'user_id',
                    'associationForeignKey' => 'filial_id',
                    'unique' => 'keepExisting',
                )
            );

	var $hasOne = array(
	  /**
	   * 'Hack' para HABTM
	   */
	  '_UsersFilial' => array(
	    'className'  => 'UsersFilial',
	    'foreignKey' => 'user_id',
	    'fields'     => 'id'
	  ),
	  '_Filial' => array(
	    'className'  => 'Filial',
	    'foreignKey' => false,
	    'conditions' => '_Filial.id = _UsersFilial.filial_id',
	    'fields'	 => 'id'
	  ),

	);

	public function recoverpassword($user_id){
		$user = $this->find('first',array('recursive'=> -1, 'conditions' => array('User.id'=> $user_id)));

		if(!empty($user))
		{
			if ($user['User']['username'] != '' )
			{
				$prefixoAux = explode('@', $user['User']['username']);
				$prefixo =(isset($prefixoAux[0]) ? $prefixoAux[0] : 'ENTR');


				$meuToken=$prefixo.date('Ymdhisa').$this->geraSenha();
				$url = $_SERVER['HTTP_HOST'];
				//if($url !='10.0.2.2'){
					
					$tokenToSave = array(
					'token' => $meuToken,
					'user_id'=> $user_id,
					'ativo'=> true
				);
				// load the Model

				$Token = new Token();
				try {
					$Token->create();
					$Token->save($tokenToSave);
				} catch (Exception $e) {
					print_r($e);
					die;
					return false;
				}
				// use the Model
				
				$mensagem =
				"Segue abaixo o link para recuperar sua senha http://".$url."/users/formtrocasenha?t=".$meuToken."";

				$Email = new CakeEmail();
				$Email->from(array('sistema@rudo.com.br' => 'Rudo - Aplicativo de Entregas.'));
				$Email->to($user['User']['username']);
				$Email->subject('Recuperar Senha');

				try {
				 	if($Email->send($mensagem))
					{
						return true;
					}else
					{
						return false;
					}
				 } catch (Exception $e) {
				 	print_r($e);
					die;
				 } 
				//}else{
					//return true;
				//}
				
				
			}else
			{
				return false;
			}
		}


	}


	public function enviaemaildeativacao($user_id){
		$user = $this->find('first',array('recursive'=> -1, 'conditions' => array('User.id'=> $user_id)));

		if(!empty($user))
		{
			if ($user['User']['username'] != '' )
			{
				$prefixoAux = explode('@', $user['User']['username']);
				$prefixo =(isset($prefixoAux[0]) ? $prefixoAux[0] : 'ENTR');


				$meuToken=$prefixo.date('Ymdhisa').$this->geraSenha();
				$url = $_SERVER['HTTP_HOST'];
				//if($url !='10.0.2.2'){
					
					$tokenToSave = array(
					'token' => $meuToken,
					'user_id'=> $user_id,
					'ativo'=> true
				);
				// load the Model

				$Token = new Token();
				try {
					$Token->create();
					$Token->save($tokenToSave);
				} catch (Exception $e) {
					print_r($e);
					die;
					return false;
				}
				// use the Model
				
				$mensagem =
				"Segue abaixo o link para ativar sua conta na plataforma Rudo http://".$url."/users/ativacadastro?t=".$meuToken."";

				$Email = new CakeEmail();
				$Email->from(array('sistema@rudo.com.br' => 'Rudo - Plataform de entregas.'));
				$Email->to($user['User']['username']);
				$Email->subject('Recuperar Senha');

				try {
				 	if($Email->send($mensagem))
					{
						return true;
					}else
					{
						return false;
					}
				 } catch (Exception $e) {
				 	print_r($e);
					die;
				 } 
				//}else{
					//return true;
				//}
				
				
			}else
			{
				return false;
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
}
