<?php
App::uses('AppModel', 'Model');
/**
 * Pgtopedidos Model
 *
 * @property Pedido $Pedido
 */
class Pgtopedidos extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'tipo' => array(
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
		'Pedido' => array(
			'className' => 'Filial',
			'foreignKey' => 'pedido_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Pagamento' => array(
			'className' => 'Pagamento',
			'foreignKey' => 'pagamento_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mesa' => array(
			'className' => 'Mesa',
			'foreignKey' => 'mesa_id',
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
	/*public $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'pagamento_id',
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
	);*/

}
