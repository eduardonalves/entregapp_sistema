<?php
/**
 * ProdutoFixture
 *
 */
class ProdutoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'nome' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'preco_custo' => array('type' => 'float', 'null' => true, 'default' => null),
		'preco_venda' => array('type' => 'float', 'null' => false, 'default' => null),
		'ativo' => array('type' => 'boolean', 'null' => false, 'default' => null),
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
			'nome' => 'Lorem ipsum dolor sit amet',
			'preco_custo' => 1,
			'preco_venda' => 1,
			'ativo' => 1
		),
	);

}
