<?php
App::uses('AppModel', 'Model');
/**
 * Estado Model
 *
 * @property Pedido $Pedido
 */
class Fechamento extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */



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
		'Fechamentoiten' => array(
			'className' => 'Fechamentoiten',
			'foreignKey' => 'fechamento_id',
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
		'Venda' => array(
			'className' => 'Venda',
			'foreignKey' => 'fechamento_id',
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
