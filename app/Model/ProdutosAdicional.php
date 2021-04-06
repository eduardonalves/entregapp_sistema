<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class ProdutosAdicional extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $useTable = 'produtos_adicionals';
	public $primaryKey = 'id';
	public $name = 'ProdutosAdicional';
	
	public $actsAs = array(
                    'Containable',
                );
  	
	public $belongsTo = array(
		'Produto' => array(
			'className' => 'Produto',
			'foreignKey' => 'produto_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Adicional' => array(
			'className' => 'Adicional',
			'foreignKey' => 'adicional_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
