<?php
App::uses('AppModel', 'Model');
/**
 * Produto Model
 *
 * @property Itensdepedido $Itensdepedido
 */
class Produto extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
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

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Itensdepedido' => array(
			'className' => 'Itensdepedido',
			'foreignKey' => 'produto_id',
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
		'Tamanho' => array(
			'className' => 'Tamanho',
			'foreignKey' => 'produto_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Tamanho.nome ASC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	public $belongsTo = array(
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'categoria_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Categoria.nome ASC'
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



}
