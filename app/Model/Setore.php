<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Setore extends AppModel {
//	public	$displayField = "nome";
/**
 * Validation rules
 *
 * @var array
 */



  public $displayField = 'setor';
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
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Produto' => array(
			'className' => 'Produto',
			'foreignKey' => 'setore_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Produto.nome ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
