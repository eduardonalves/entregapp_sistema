<?php
App::uses('AppController', 'Controller');
class RestMesasController extends AppController {
    public $uses = array('Mesa');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function mesasmobile() {
        header("Access-Control-Allow-Origin: *");
        $mesas = $this->Mesa->find('all',array('recursive'=> -1, 'conditions'=> array('filial_id'=> $_GET['fp'] )));
        $this->set(array(
            'mesas' => $mesas,
            '_serialize' => array('mesas')
        ));
    }
	
}
