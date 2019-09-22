<?php
App::uses('AppController', 'Controller');
class RestProdutosController extends AppController {
    public $uses = array('Produto');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function index() {
        $produtos = $this->Produto->find('all');
        $this->set(array(
            'produtos' => $phones,
            '_serialize' => array('produtos')
        ));
    }
	public function checkToken(&$clienteId,&$token){
		$this->loadModel('Cliente');

		$cliente = $this->Cliente->find('first', array('conditions' => array('AND' => array(array('Cliente.id' => $clienteId), array('Cliente.token' => $token), array('Cliente.ativo' => 1)))));

		if(!empty($cliente)){
			$resposta = "OK";
			return $resposta;
		}else{
			$resposta = "NOK";
			return $resposta;
			$clienteUp= array('id'=> $clienteId, 'ativo' => 0);
			$this->Cliente->create();
			$this->Cliente->save($clienteUp);
		}

	}

	public function prodsmobile() {

		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){



			$this->loadModel('Categoria');
                                    $this->loadModel('Produto');
                                     $this->loadModel('Tamanho');
			header("Access-Control-Allow-Origin: *");
                                $resultados= array();

			$resultados =  $this->Categoria->find('all', array('recursive' => 1, 'order'=> 'Categoria.nome ASC', 'conditions' => array('Categoria.filial_id'=> $_GET['fp'])));

			$i=0;
                        foreach($resultados as $i => $categoria){
                            $j=0;

                            foreach($categoria['Produto'] as $j=> $produto){
                                        if($produto['filial_id']==$_GET['fp']){

                                            $this->checkbfunc->converteMoedaToView($resultados[$i]['Produto'][$j]['preco_venda']);
                                            $tamanhosProdutos = $this->Tamanho->find('all', array('recursive'=> -1, 'order'=> 'Tamanho.nome ASC','conditions'=> array('Tamanho.produto_id'=> $produto['id'])));
                                            $resultados[$i]['Produto'][$j]['tamanhos'] = array($tamanhosProdutos);

                                               if($produto['composto'] ==1) {
                                                        $resultados[$i]['Produto'][$j]['produtoscomposicao'] = array();
                                                        if($produto['parte_composto']==1){
                                                            $produtosCompostos = $this->Produto->find('all', array('recursive'=> -1 ,'order'=>'Produto.nome ASC', 'conditions' => array('AND'=> array(array('Produto.disponivel' => 1),array('Produto.ativo' => 1),array('Produto.filial_id' => $_GET['fp']), array('OR'=> array(array('Produto.tipo_composto'=> 1 ),array('Produto.tipo_composto'=> 3)))))));
                                                             $L=0;
                                                            foreach ($produtosCompostos as $L => $prodComposto) {


                                                                $tamanhosProdutosCompostos = $this->Tamanho->find('all', array('recursive'=> -1, 'conditions'=> array('Tamanho.produto_id'=> $prodComposto['Produto']['id'])));

                                                                $tamanhosProdutosCompostos[$L]['Produto']['tamanhos'] = array();

                                                                $produtosCompostos[$L]['Produto']['tamanhos']= array($tamanhosProdutosCompostos);
                                                                $L++;
                                                            }
                                                             array_push($resultados[$i]['Produto'][$j]['produtoscomposicao'] , $produtosCompostos);
                                                        }



                                                        if($produto['parte_composto']==2){

                                                            $produtosCompostos = $this->Produto->find('all', array('recursive'=> -1 , 'order'=>'Produto.nome ASC', 'conditions' => array('AND'=> array(array('Produto.disponivel' => 1),array('Produto.ativo' => 1),array('Produto.filial_id' => $_GET['fp']), array('OR'=> array(array('Produto.tipo_composto'=> 2 ),array('Produto.tipo_composto'=> 3)))))));
                                                             $L=0;
                                                            foreach ($produtosCompostos as $L => $prodComposto) {


                                                                $tamanhosProdutosCompostos = $this->Tamanho->find('all', array('recursive'=> -1, 'conditions'=> array('Tamanho.produto_id'=> $prodComposto['Produto']['id'])));

                                                                $tamanhosProdutosCompostos[$L]['Produto']['tamanhos'] = array();

                                                                $produtosCompostos[$L]['Produto']['tamanhos']= array($tamanhosProdutosCompostos);
                                                                $L++;
                                                            }
                                                             array_push($resultados[$i]['Produto'][$j]['produtoscomposicao'] , $produtosCompostos);
                                                        }


                                                        if($produto['parte_composto']==3){
                                                            $produtosCompostos = $this->Produto->find('all', array('recursive'=> -1 , 'order'=>'Produto.nome ASC','conditions' => array('AND'=> array(array('Produto.disponivel' => 1),array('Produto.ativo' => 1),array('Produto.filial_id' => $_GET['fp']), array('OR'=> array(array('Produto.tipo_composto'=> 1 ),array('Produto.tipo_composto'=> 2),array('Produto.tipo_composto'=> 3)))))));
                                                             array_push($resultados[$i]['Produto'][$j]['produtoscomposicao'] , $produtosCompostos);
                                                        }

                                               }

                                        }else{
                                            unset($resultados[$i]['Produto'][$j]);
                                        }
                            	//debug($resultados[$i]['Produto'][$j]['preco_venda']);

                            	$j++;
                            }
                        $i++;
                        }


			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));

		//}else{

			//return $this->redirect(array('controller' =>'users' ,'action' => 'login'));

		//}

    }

    public function add() {
        $this->Phone->create();
        if ($this->Phone->save($this->request->data)) {
             $message = 'Created';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }

    public function view($id) {
        $phone = $this->Phone->findById($id);
        $this->set(array(
            'phone' => $phone,
            '_serialize' => array('phone')
        ));
    }


    public function edit($id) {
        $this->Phone->id = $id;
        if ($this->Phone->save($this->request->data)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }

    public function delete($id) {
        if ($this->Phone->delete($id)) {
            $message = 'Deleted';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }
}