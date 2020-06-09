<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Tiposrecorrencia extends AppModel {
//	public	$displayField = "despesa";
/**
 * Validation rules
 *
 * @var array
 */



  public $displayField = 'tipo';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/**
 * belongsTo associations
 *
 * @var array
 */
	/*public $belongsTo = array(
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
	);*/

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Despesa' => array(
			'className' => 'Despesa',
			'foreignKey' => 'tiposrecorrencia_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Despesa.despesa ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
