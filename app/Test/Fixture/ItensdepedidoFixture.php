<?php
/**
 * ItensdepedidoFixture
 *
 */
class ItensdepedidoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'produto_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'pedido_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'qtd' => array('type' => 'integer', 'null' => false, 'default' => null),
		'valor' => array('type' => 'float', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'produto_id' => 1,
			'pedido_id' => 1,
			'qtd' => 1,
			'valor' => 1
		),
	);

}
