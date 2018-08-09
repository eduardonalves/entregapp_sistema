<?php
App::uses('AppModel', 'Model');
/**
 * Filial Model
 *
 * @property Funcao $Funcao
 * @property Pedido $Pedido
 */
class Diasdepromocao extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */

  //  public $displayField = 'nome';


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
                )
            );





}
