<?php
App::uses('AppModel', 'Model');
/**
 * Mesa Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Pgmoip extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'idoperacao' => array(
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
	/**public $belongsTo = array(
		'Funcao' => array(
			'className' => 'Funcao',
			'foreignKey' => 'funcao_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'pgmoip_id',
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

}
