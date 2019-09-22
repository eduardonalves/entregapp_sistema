<?php
App::uses('AppModel', 'Model');
/**
 * Filial Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Cupon extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
     public $validate = array(

       'percentual' => array(
         'numeric' => array(
           'rule' => array('numeric'),
           //'message' => 'Your custom message here',
           'allowEmpty' => false,
           'required' => false,
           //'last' => false, // Stop validation after this rule
           //'on' => 'create', // Limit validation to 'create' or 'update' operations
         ),
       ),
       'descricao' => array(
         'notEmpty' => array(
           'rule' => array('notEmpty'),
           //'message' => 'Your custom message here',
           //'allowEmpty' => false,
           //'required' => false,
           //'last' => false, // Stop validation after this rule
           //'on' => 'create', // Limit validation to 'create' or 'update' operations
         ),
       ),
     );
    public $displayField = 'numero';


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
                'Cliente' => array(
                    'className' => 'Cliente',
                    'foreignKey' => 'cliente_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
                ),
            );





}
