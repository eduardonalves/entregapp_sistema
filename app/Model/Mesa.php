<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Mesa extends AppModel {
//	public	$displayField = "nome";
/**
 * Validation rules
 *
 * @var array
 */



  public $displayField = 'identificacao';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
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
    'Atendente' => array(
			'className' => 'Atendente',
			'foreignKey' => 'atendente_id',
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
		'Pedido' => array(
			'className' => 'Pedido',
			'foreignKey' => 'mesa_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			//'order' => 'Pedido.id ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
    'Pgtopedido' => array(
			'className' => 'Pgtopedido',
			'foreignKey' => 'mesa_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			//'order' => 'Pedido.id ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);

}
