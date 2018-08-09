<?php
App::uses('AppModel', 'Model');
/**
 * Pagamento Model
 *
 * @property Pedido $Pedido
 */
class Assinatura extends AppModel {

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


}
