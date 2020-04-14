<?php
App::uses('AppModel', 'Model');
/**
 * Ponto Model
 *
 * @property Pedido $Pedido
 */
class Ponto extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */	public $displayField = 'ponto';
	public $validate = array(
		'ponto' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty')
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);


}
