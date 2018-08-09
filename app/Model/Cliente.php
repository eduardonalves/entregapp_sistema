<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('Token', 'Model');
/**
 * User Cliente
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Cliente extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
 public $displayField = 'nome';

	public $validate = array(
		'nome' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Filial' => array(
			'className' => 'Filial',
			'foreignKey' => 'filial_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'cliente_id',
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
		'Atendimento' => array(
			'className' => 'Atendimento',
			'foreignKey' => 'cliente_id',
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
			'foreignKey' => 'cliente_id',
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
		'Roteiro' => array(
			'className' => 'Roteiro',
			'foreignKey' => 'cliente_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
//The Associations below have been created with all possible keys, those that are not needed can be removed

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
	public function recoverpassword($cliente_id){
		$cliente = $this->find('first',array('recursive'=> -1, 'conditions' => array('Cliente.id'=> $cliente_id)));

		if(!empty($cliente))
		{
			if ($cliente['Cliente']['email'] != '' )
			{
				$prefixoAux = explode('@', $cliente['Cliente']['email']);
				$prefixo =(isset($prefixoAux[0]) ? $prefixoAux[0] : 'ENTR');
				$meuToken=$prefixo.date('Ymdhisa').$this->geraSenha();
				$url = $_SERVER['HTTP_HOST'];
				$tokenToSave = array(
					'token' => $meuToken,
					'cliente_id'=> $cliente_id,
					'ativo'=> true
				);
				// load the Model
				$Token = new Token();
				// use the Model
				$Token->create();
				$Token->save($tokenToSave);
				$mensagem =
				"Segue abaixo o link para recuperar sua senha http://".$url."/RestClientes/formtrocasenha?t=".$meuToken."";

				$Email = new CakeEmail();
				$Email->from(array('contato@entregapp.com.br' => 'entregapp'));
				$Email->to($cliente['Cliente']['email']);
				$Email->subject('Recuperar Senha');
				if($Email->send($mensagem))
				{
					return true;
				}else
				{
					return false;
				}
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
