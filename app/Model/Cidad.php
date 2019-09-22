<?php
App::uses('AppModel', 'Model');
/**
 * Cidad Model
 *
 * @property Pedido $Pedido
 */
class Cidad extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'cidade' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
	);

}
