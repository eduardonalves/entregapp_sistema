<?php
App::uses('AppModel', 'Model');
/**
 * Cidade Model
 *
 * @property Pedido $Pedido
 */
class Cidade extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */	public $displayField = 'cidade';
	public $validate = array(
		'cidade' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
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
		'Estado' => array(
			'className' => 'Estado',
			'foreignKey' => 'estado_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);


}
