<?php
App::uses('AppModel', 'Model');
/**
 * Despesa Model
 *
 * @property User $User
 */
class Despesa extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $displayField = 'despesa';
	public $validate = array(
		'despesa' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'data_vencimento' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'valor' => array(
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
		'Tiposrecorrencia' => array(
			'className' => 'Tiposrecorrencia',
			'foreignKey' => 'tiposrecorrencia_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Categoriasdespesa' => array(
			'className' => 'Categoriasdespesa',
			'foreignKey' => 'categoriasdespesa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Tiposrecorrencia' => array(
			'className' => 'Tiposrecorrencia',
			'foreignKey' => 'tiposrecorrencia_id',
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
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'despesa_id',
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
		'Autorizacao' => array(
			'className' => 'Autorizacao',
			'foreignKey' => 'despesa_id',
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

	);*/

}
