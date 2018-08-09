<?php
App::uses('AppModel', 'Model');
/**
 * Filial Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Filial extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
    public $actsAs = array(
                    'Containable',
                );
  		public $displayField = 'nome';
	public $validate = array(

		/*'funcao_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
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
		),*/
	);


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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Atendimento' => array(
			'className' => 'Atendimento',
			'foreignKey' => 'filial_id',
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
		'Autorizacao' => array(
			'className' => 'Autorizacao',
			'foreignKey' => 'filial_id',
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
		'Categoria' => array(
			'className' => 'Categoria',
			'foreignKey' => 'filial_id',
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
                        'Cliente' => array(
                            'className' => 'Cliente',
                            'foreignKey' => 'filial_id',
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
                        'Entregador' => array(
                            'className' => 'Entregador',
                            'foreignKey' => 'filial_id',
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
                         'Funcao' => array(
                            'className' => 'Funcao',
                            'foreignKey' => 'filial_id',
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
                         'Mesa' => array(
                            'className' => 'Funcao',
                            'foreignKey' => 'filial_id',
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
                         'Pagamento' => array(
                            'className' => 'Pagamento',
                            'foreignKey' => 'filial_id',
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
                          'Pedido' => array(
                            'className' => 'Pedido',
                            'foreignKey' => 'filial_id',
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
                          'Produto' => array(
                            'className' => 'Produto',
                            'foreignKey' => 'filial_id',
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
                          'Itensdepedido' => array(
                            'className' => 'Itensdepedido',
                            'foreignKey' => 'filial_id',
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
                          'Diasdepromocao' => array(
                            'className' => 'Diasdepromocao',
                            'foreignKey' => 'filial_id',
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
                           'Mensagen' => array(
                            'className' => 'Mensagen',
                            'foreignKey' => 'filial_id',
                            'dependent' => false,
                            'conditions' => '',
                            'fields' => '',
                            'order' => '',
                            'limit' => '',
                            'offset' => '',
                            'exclusive' => '',
                            'finderQuery' => '',
                            'counterQuery' => ''
                        )

	);
            public $hasAndBelongsToMany = array(
                'User' => array(
                    'className' => 'User',
                    'joinTable' => 'users_filials',
                    'foreignKey' => 'filial_id',
                    'associationForeignKey' => 'user_id',
                    'unique' => 'keepExisting',
                )
            );

            var $hasOne = array(
              /**
               * 'Hack' para HABTM
               */
              '_UsersFilial' => array(
                'className'  => 'UsersFilial',
                'foreignKey' => 'filial_id',
                'fields'     => 'id'
              ),
              '_User' => array(
                'className'  => 'User',
                'foreignKey' => false,
                'conditions' => '_User.id = _UsersFilial.user_id',
                'fields'     => 'id'
              ),

            );

}
