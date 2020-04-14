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
		//	$clienteUp= array('id'=> $clienteId, 'ativo' => 0);
			//$this->Cliente->create();
			//$this->Cliente->save($clienteUp);
		}

	}

  public function prodsmobilebycat() {
    header("Access-Control-Allow-Origin: *");
    
    $produtos = $this->Produto->find('all',array('recursive'=> -1,'order' => 'Produto.nome ASC' ,'conditions'=> array('categoria_id'=> $_GET['cat'], 'filial_id'=> $_GET['fp'], 'ativo'=> 1 )));
	foreach($produtos as $key => $value){
		$produtos[$key]["Produto"]["preco_venda"] = number_format( $produtos[$key]["Produto"]["preco_venda"], 2,".", "");
    $produtos[$key]["Produto"]["id_sec"]=$key;
	}
  
    $this->set(array(
        'produtos' => $produtos,
        '_serialize' => array('produtos')
    ));
  }


  public function prodsmobilebyrec() {
    header("Access-Control-Allow-Origin: *");
    $this->loadModel('Partida');
    $produtos = array();
    if(empty($this->request->data)){
      
      $this->request->data= $_GET;
    }
    $this->loadModel('Partida');
    if ($this->request->is('post','put','get')) {

      $cliente= $this->request->data['clt'];
      $token =  $this->request->data['token'];

      $resp =$this->checkToken($cliente, $token);
      $hoje= date("Y-m-d");
     
      if($resp=='OK'){
      
        $recompensa = $this->Partida->find('all',
          array(
            'recursive'=> -1,
            'conditions'=> array(
              array('Partida.cliente_id'=> $cliente),
              array('Partida.ativo'=> 0),
              array('Partida.recompensa_escolhida_id is not null'),
              array('Partida.data_validade >= '=> $hoje),
              array('AND'=> array(
                'OR'=> array(
                  array('Partida.resgate is null'),
                  array('Partida.resgate'=> 0),
                )
              )),
            )
          )
        );
        
        foreach ($recompensa as $key2 => $value2) {
          $meuProduto = $this->Produto->find('first', array(
            'recursive'=> -1,
            'conditions'=> array(
              'Produto.id'=> $value2['Partida']['recompensa_escolhida_id']
            )
          ));

          if(!empty($meuProduto)){
            $meuProduto['Produto']['recompensa_escolhida_id']=$value2['Partida']['recompensa_escolhida_id'];
            $meuProduto['Produto']['id_sec']=$key2;
            $meuProduto['Produto']['partida_id']=$value2['Partida']['id'];
            $meuProduto['Produto']['data_validade']=date("d/m/Y", strtotime($value2['Partida']['data_validade'] ) );
             array_push($produtos, $meuProduto);   
          }
         
        }
        if(!empty($produtos)){
          foreach($produtos as $key => $value){
            $produtos[$key]["Produto"]["preco_venda"] = 0;
          }  
        }
      }

    }  
    
  
    $this->set(array(
        'produtos' => $produtos,
        '_serialize' => array('produtos')
    ));
  }
	public function prodsmobile() {

		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){



			$this->loadModel('Categoria');
      $this->loadModel('Filial');
                                    $this->loadModel('Produto');
                                     $this->loadModel('Tamanho');
			header("Access-Control-Allow-Origin: *");
                                $resultados= array();

			$resultados =  $this->Categoria->find('all', array('recursive' => 1, 'order'=> 'Categoria.destaque Desc ,Categoria.nome ASC', 'conditions' => array('AND'=> array(array('Categoria.filial_id'=> $_GET['fp']), array('Categoria.ativo'=> 1)))));


      if(isset($resultados['Filial']))
      {
        unset($resultados["Filial"]["email_pagseguro"]);
        unset($resultados["Filial"]["token_pagseguro"]);
        unset($resultados["Filial"]["app_idpagseguro"]);
        unset($resultados["Filial"]["app_keypagseguro"]);
      }
      $filiaisApp = $this->Filial->find('all',array(
        'recursive'=> -1, 'conditions'=> array(
          'empresa_id'=> $_GET['se']
        )
      ));
      foreach ($filiaisApp as $key => $value)
      {

        if(isset($value['Filial']))
        {
          unset($filiaisApp[$key]["Filial"]["email_pagseguro"]);
          unset($filiaisApp[$key]["Filial"]["token_pagseguro"]);
          unset($filiaisApp[$key]["Filial"]["app_idpagseguro"]);
          unset($filiaisApp[$key]["Filial"]["app_keypagseguro"]);
        }
      }

      $resultados[0]['filiais'] = $filiaisApp;


			$i=0;

                        foreach($resultados as $i => $categoria){
                            $j=0;
                            if(isset($resultados[0]['Produto'])){
                              foreach($categoria['Produto'] as $j=> $produto){
                                          if($produto['filial_id']==$_GET['fp'] ){

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
    public function viewMobile($value='')
    {
      header("Access-Control-Allow-Origin: *");
      $this->loadModel('Categoria');
      $this->loadModel('Tamanho');

      $produto= $this->Produto->find('first',array(
          'recursive'=> -1,
          'conditions'=> array(
            'Produto.id'=> $_GET['id']
          )
        )
      );

      $categoria = $this->Categoria->find('first',array(
          'recursive' => -1,
          'conditions' => array(
            'Categoria.id'=> $produto['Produto']['categoria_id']
          )
        )
      );

      $tamanhos = $this->Tamanho->find('all', array(
          'recursive' => -1,
          'conditions' => array(
            'AND'=> array(
              array('Tamanho.produto_id'=> $_GET['id']),
              array('Tamanho.nome != ' => null ),
              array('Tamanho.nome != ' => '' )
            )
          )
        )
      );

      if(!empty($produto))
      {
        $bebida = $this->Produto->find('all', array(
            'recursive' => -1,
            'conditions' => array(
              'AND'=> array(
                array('Produto.parte_bebida'=> 1),
                array('Produto.ativo'=> true),
                array('Produto.empresa_id'=> $produto['Produto']['empresa_id']),
                array('Produto.filial_id'=> $produto['Produto']['filial_id'])
              )
            )
          )
        );

        $ganhe = $this->Produto->find('all', array(
            'recursive' => -1,
            'conditions' => array(
              'AND'=> array(
                array('Produto.parte_compre_ganhe'=> 1),
                array('Produto.ativo'=> true),
                array('Produto.empresa_id'=> $produto['Produto']['empresa_id']),
                array('Produto.filial_id'=> $produto['Produto']['filial_id'])
              )
            )
          )
        );
      }
      $resultados = array(
        'Produto'=> (!empty($produto)) ? $produto['Produto'] : array(),
        'Categoria' => (!empty($categoria)) ? $categoria['Categoria'] : array(),
        'Tamanho' => $tamanhos,
        'Bebida' => (!empty($bebida)) ? $bebida : array(),
        'Ganhe' => (!empty($ganhe)) ? $ganhe : array()
      );
      $this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
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
