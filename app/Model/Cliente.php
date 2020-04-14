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
		'Ponto' => array(
			'className' => 'Ponto',
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
				//if($url !='10.0.2.2'){
					
					$tokenToSave = array(
					'token' => $meuToken,
					'cliente_id'=> $cliente_id,
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
				"Segue abaixo o link para recuperar sua senha http://".$url."/RestClientes/formtrocasenha?t=".$meuToken."";

				$Email = new CakeEmail();
				$Email->from(array('sistema@rudo.com.br' => 'Rudo - Aplicativo de Entregas.'));
				$Email->to($cliente['Cliente']['email']);
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

	public function sendEmailPoints($cliente_id='', $points='', $total=''){
		$cliente = $this->find('first',array('recursive'=> -1, 'conditions' => array('Cliente.id'=> $cliente_id)));

		if(!empty($cliente))
		{
			if ($cliente['Cliente']['email'] != ''  && $cliente['Cliente']['email'] != null)
			{
				$prefixoAux = explode('@', $cliente['Cliente']['email']);
				$prefixo =(isset($prefixoAux[0]) ? $prefixoAux[0] : 'ENTR');


				
				
				$mensagem =
				'<div class=""><div class="aHl"></div><div id=":1qu" tabindex="-1"></div><div id=":1r5" class="ii gt"><div id=":1r6" class="a3s aXjCH msg166628655112620723"><div class="adM">
    </div><u></u>
      <div style="background-color:#ffffff">
      <div style="background-color:#ffffff">   
      <div style="Margin:0px auto;max-width:600px">
        
        <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%">
          <tbody>
            <tr>
              <td style="direction:ltr;font-size:0px;padding:9px 0px 9px 0px;text-align:center;vertical-align:top">
                
            
      <div class="m_166628655112620723mj-column-per-100" style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%">
        
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top" width="100%">
        
            <tbody><tr>
              <td align="left" style="font-size:0px;padding:15px 15px 15px 15px;word-break:break-word">
                
      <div style="font-family:Ubuntu,Helvetica,Arial,sans-serif;font-size:11px;line-height:1.5;text-align:left;color:#000000">
        <p><span style="font-size:18px"><strong><span style="color:#f1c40f">Parabéns</span></strong>, <span style="color:#000000">você ganhou mais <span style="color:#f1c40f"><strong>'.$points.' moedas</strong></span>.&nbsp; Seu saldo total até o momento é de <span style="color:#f1c40f"><strong>'.$total.' moedas</strong>.</span> Acesse sua conta no aplicativo Geek Grill para poder trocar suas moedas por partidas de pedra, papel e tesoura (custo de 10 moedas cada) para ganhar recompensas incríveis!!!&nbsp;</span></span></p>
<p><span style="font-size:18px; color:#000000;">Programa de Fidelidade <strong>Geek Grill</strong>.</span></p>
<p><span style="font-size:18px; color:#000000;">Sabor longo e próspero.</span></p>
      </div>
    
              </td>
            </tr>
          
            <tr>
              <td align="center" style="font-size:0px;padding:0px 0px 0px 0px;word-break:break-word">
                
      <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:collapse;border-spacing:0px">
        <tbody>
          <tr>
            <td style="width:600px">
              
      <img height="auto" src="http://sistema.rudo.com.br/img/1584106300.jpg" style="border:0;display:block;outline:none;text-decoration:none;height:auto;width:100%;font-size:13px" width="600" class="CToWUd a6T" tabindex="0"><div class="a6S" dir="ltr" style="opacity: 0.01; left: 727px; top: 781px;"><div id=":1sf" class="T-I J-J5-Ji aQv T-I-ax7 L3 a5q" role="button" tabindex="0" aria-label="Fazer o download do anexo " data-tooltip-class="a1V" data-tooltip="Fazer o download"><div class="aSK J-J5-Ji aYr"></div></div></div>
    
            </td>
          </tr>
        </tbody>
      </table>
    
              </td>
            </tr>
          
      </tbody></table>
    
      </div>
    
          
              </td>
            </tr>
          </tbody>
        </table><div class="yj6qo"></div><div class="adL">
        
      </div></div><div class="adL">
    
      
      
    
    
      </div></div><div class="adL">
    
      </div></div><div class="adL"></div></div></div><div id=":1qp" class="ii gt" style="display:none"><div id=":1qq" class="a3s aXjCH undefined"></div></div><div class="hi"></div></div>';

				$Email = new CakeEmail();
				$Email->from(array('sistema@rudo.com.br' => 'Rudo - Aplicativo de Entregas.'));
				$Email->to($cliente['Cliente']['email']);
				$Email->subject('Pontos');
				$Email->emailFormat('html');

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
