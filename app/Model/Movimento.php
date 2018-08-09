<?php
App::uses('AppModel', 'Model');
/**
 * Movimento Model
 *
 * @property Pedido $Pedido
 */
class Movimento extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $displayField = 'numero';
	public $validate = array(
		'numero' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
			'uniqueMovimentonumerolRule' => array(
			            'rule' => 'isUnique',
			            'message' => 'Numero already registered'
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
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Venda' => array(
			'className' => 'Venda',
			'foreignKey' => 'Movimento_id',
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

}
