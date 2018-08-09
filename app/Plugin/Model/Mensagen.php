<?php
App::uses('AppModel', 'Model');
/**
 * Mensagen Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Mensagen extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */


	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Cliente' => array(
			'className' => 'Cliente',
			'foreignKey' => 'cliente_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'pedido_id',
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
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);



}
