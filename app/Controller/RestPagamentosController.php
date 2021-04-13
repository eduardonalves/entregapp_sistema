<?php
App::uses('AppController', 'Controller');
class RestPagamentosController extends AppController {
    public $uses = array('Pagamento');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function pagamentosmobile() {
        header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        $pagamentos = $this->Pagamento->find('all',array('recursive'=> -1, 'conditions'=> array('filial_id'=> $_GET['fp'] )));
        $this->set(array(
            'pagamentos' => $pagamentos,
            '_serialize' => array('pagamentos')
        ));
    }
	
}
