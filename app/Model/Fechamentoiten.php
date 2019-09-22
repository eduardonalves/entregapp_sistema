<?php
App::uses('AppModel', 'Model');
/**
 * Estado Model
 *
 * @property Pedido $Pedido
 */
class Fechamentoiten extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */


	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'Pagamento' => array(
			'className' => 'Empresa',
			'foreignKey' => 'pagamento_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Fechamento' => array(
			'className' => 'Fechamento',
			'foreignKey' => 'fechamento_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);


}
