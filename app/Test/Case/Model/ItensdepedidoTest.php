<?php
App::uses('Itensdepedido', 'Model');

/**
 * Itensdepedido Test Case
 *
 */
class ItensdepedidoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.itensdepedido',
		'app.produto',
		'app.pedido'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Itensdepedido = ClassRegistry::init('Itensdepedido');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Itensdepedido);

		parent::tearDown();
	}

}
