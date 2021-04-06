<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Adicional extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $useTable = 'produtos';
	public $primaryKey = 'id';
	public $name = 'Adicional';
	public $displayField = 'nome';
	public $actsAs = array(
                    'Containable',
                );
  	
	
	public $validate = array(
		'nome' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'preco_venda' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'ativo' => array(
			'boolean' => array(
				'rule' => array('boolean'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'categoria_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Categoria.nome ASC'
		),
		'Setore' => array(
			'className' => 'Setore',
			'foreignKey' => 'setore_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Setore.setor ASC'
		),
		'Empresa' => array(
			'className' => 'Empresa',
			'foreignKey' => 'empresa_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Empresa.nome ASC'
		),
		'Filial' => array(
			'className' => 'Filial',
			'foreignKey' => 'filial_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Filial.nome ASC'
		),
	);

	public $hasAndBelongsToMany = array(
                'Produto' => array(
                    'className' => 'Produto',
                    'joinTable' => 'produtos_adicionals',
                    'foreignKey' => 'adicional_id',
                    'associationForeignKey' => 'produto_id',
                    'unique' => 'keepExisting',
                )
            );

	var $hasOne = array(
	  /**
	   * 'Hack' para HABTM
	   */
	  '_ProdutosAdicional' => array(
	    'className'  => 'ProdutosAdicional',
	    'foreignKey' => 'adicional_id',
	    'fields'     => 'id'
	  ),
	  '_Produto' => array(
	    'className'  => 'Produto',
	    'foreignKey' => false,
	    'conditions' => '_Produto.id = _ProdutosAdicional.produto_id',
	    'fields'	 => 'id'
	  ),

	);


}
