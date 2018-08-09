<?php
/**
 * PedidoFixture
 *
 */
class PedidoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'pagamento_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'codigo' => array('type' => 'integer', 'null' => false, 'default' => null),
		'data' => array('type' => 'date', 'null' => false, 'default' => null),
		'valor' => array('type' => 'float', 'null' => false, 'default' => null),
		'nota' => array('type' => 'integer', 'null' => false, 'default' => null),
		'status' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 15, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'pagamento_id' => 1,
			'user_id' => 1,
			'codigo' => 1,
			'data' => '2014-04-10',
			'valor' => 1,
			'nota' => 1,
			'status' => 'Lorem ipsum d'
		),
	);

}
