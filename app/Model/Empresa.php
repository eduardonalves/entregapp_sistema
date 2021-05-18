<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Empresa extends AppModel {
    public $displayField = 'nome';
/**
 * Validation rules
 *
 * @var array
 */

/**
 * belongsTo associations
 *
 * @var array
 */
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        ),
        'slug' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        ),
        're_password' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty')
            ),
        )
        
    );
    public $hasMany = array(
        'Atendimento' => array(
            'className' => 'Atendimento',
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
       'Filial' => array(
            'className' => 'Filial',
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
            'foreignKey' => 'empresa_id',
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
