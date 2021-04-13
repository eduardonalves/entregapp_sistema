<?php
App::uses('AppController', 'Controller');
class RestCategoriasController extends AppController {
    public $uses = array('Categoria');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function catsmobile() {
        header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
        if(isset($_GET['apg'])){
          $categorias = $this->Categoria->find('all',array('recursive'=> -1,'order'=> 'Categoria.destaque Desc ,Categoria.nome ASC', 'conditions'=> array(
            'filial_id'=> $_GET['fp'], 
            'ativo' => 1,
            'show_store'=> 1 
          )));
        }else{
          $categorias = $this->Categoria->find('all',array('recursive'=> -1,'order'=> 'Categoria.destaque Desc ,Categoria.nome ASC', 'conditions'=> array(
            'filial_id'=> $_GET['fp'], 
            'ativo' => 1,
            'show_app'=> 1 
          )));
        }
        
        $this->set(array(
            'categorias' => $categorias,
            '_serialize' => array('categorias')
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
  public function validajogos()
  {
    header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header('Content-Type: application/json');

    $this->layout='liso';
    $cliente= $_GET['clt'];
    $token =  $_GET['token'];

    $resultado="";

    $resp =$this->checkToken($cliente, $token);

    if($resp=='OK'){
      $this->loadModel('Ponto');
      $this->loadModel('Produto');
      $this->loadModel('Partida');
      $valorFicha = 10;
      $pontos = $this->Ponto->find(
        'first',
        array(
          'recursive'=> -1,
          'conditions'=> array(
            'Ponto.cliente_id'=> $cliente 
          )
        )
      );
      $saldo = (int) $pontos['Ponto']['pontos_ganhos'] - (int) $pontos['Ponto']['pontos_gastos'];

      

      $temPartidaAberta = $this->Partida->find('all', array(
        'recursive'=> -1,
        'conditions'=> array(
          'Partida.cliente_id'=> $cliente,
          'Partida.ativo'=> 1
        )
      ));
      if(empty($temPartidaAberta)){
      
        $produtosConsolacao = $this->Produto->find('all',
          array(
            'recursive'=> -1,
            'conditions'=> array(
              'Produto.recompensa'=> 1,
              'Produto.recompensa_tipo'=> 2,
            )
          )
        ); 

        $p_consolacao = array();

        foreach ($produtosConsolacao as $key => $value) {
          array_push($p_consolacao, $value['Produto']['id']);
        }
        $tamanho = count($p_consolacao);
        $numeroAleatorio = rand(1,$tamanho);
        $numeroAleatorio = $numeroAleatorio -1;
        $premioConsolacao= '';

        if(isset($p_consolacao[$numeroAleatorio])){
          $premioConsolacao= $p_consolacao[$numeroAleatorio];
        }

        $produtosComuns = $this->Produto->find('all',
          array(
            'recursive'=> -1,
            'conditions'=> array(
              'Produto.recompensa'=> 1,
              'Produto.recompensa_tipo'=> 1,
            )
          )
        ); 

        $premios = array();

        $countPremioComum = count($produtosComuns);
        $vezesParaContar = $countPremioComum  * 10; 
        foreach ($produtosComuns as $key2 => $value2) {
          for ($i=0; $i < $vezesParaContar; $i++) { 
            array_push($premios, $value2['Produto']['id']);
          }
          
        }

        

        $produtosRaros = $this->Produto->find('all',
          array(
            'recursive'=> -1,
            'conditions'=> array(
              'Produto.recompensa'=> 1,
              'Produto.recompensa_tipo'=> 3,
            )
          )
        ); 

        $premiosRaros = array();

        $countPremiosRaros= count($produtosRaros);
        $vezesParaContar = $countPremiosRaros  * 3; 
        foreach ($produtosRaros as $key3 => $value3) {
          for ($i=0; $i < $vezesParaContar; $i++) { 
            array_push($premios, $value3['Produto']['id']);
          }
          
        }

        $tamanho = count($premios);
        

        $numeroAleatorio = rand(1,$tamanho);
        $numeroAleatorio = $numeroAleatorio -1;
        $premio= '';
        
        if(isset($premios[$numeroAleatorio])){
          $premio= $premios[$numeroAleatorio];
        }



        if($saldo >= $valorFicha){
          $moedas = $saldo - $valorFicha;
          $resultado=array(
            'resultado'=> 'OK',
            'premio_consolacao'=> $premioConsolacao,
            'premio'=> $premio,
            'moedas'=> $moedas 
          );
          $pontos_gastos = $pontos['Ponto']['pontos_gastos'] + $valorFicha;
          $pontoParaSalvar = array(
            'id'=>  $pontos['Ponto']['id'],
            'pontos_gastos'=>  $pontos_gastos
          );
          $this->Ponto->save($pontoParaSalvar);

          $this->Partida->create();
          $this->Partida->save(
            array(
              'cliente_id'=> $cliente,
              'recompensa_um_id'=> $premioConsolacao,
              'recompensa_dois_id'=> $premio,
              'ativo'=> 1
            )
            
          );
           
        }else{
          $resultado='NOKSS';
          $resultado=array(
            'resultado'=> 'NOKSS',
            
          );
        }
      }else{
        $resultado=array(
            'resultado'=> 'NOKPA',
            
        );
      }
    }

    

    $this->set(array(
            'users' => $resultado ,
            '_serialize' => array('users')
          ));
  }
	public function prodsmobile() {

		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){



			$this->loadModel('Categoria');
      $this->loadModel('Filial');
      $this->loadModel('Categoria');
      $this->loadModel('Tamanho');
			header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
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
                            if(isset($resultados[0]['Categoria'])){
                              foreach($categoria['Categoria'] as $j=> $categoria){
                                          if($categoria['filial_id']==$_GET['fp']){

                                              $this->checkbfunc->converteMoedaToView($resultados[$i]['Categoria'][$j]['preco_venda']);
                                              $tamanhosCategorias = $this->Tamanho->find('all', array('recursive'=> -1, 'order'=> 'Tamanho.nome ASC','conditions'=> array('Tamanho.categoria_id'=> $categoria['id'])));

                                              $resultados[$i]['Categoria'][$j]['tamanhos'] = array($tamanhosCategorias);

                                                 if($categoria['composto'] ==1) {
                                                          $resultados[$i]['Categoria'][$j]['categoriascomposicao'] = array();
                                                          if($categoria['parte_composto']==1){
                                                              $categoriasCompostos = $this->Categoria->find('all', array('recursive'=> -1 ,'order'=>'Categoria.nome ASC', 'conditions' => array('AND'=> array(array('Categoria.disponivel' => 1),array('Categoria.ativo' => 1),array('Categoria.filial_id' => $_GET['fp']), array('OR'=> array(array('Categoria.tipo_composto'=> 1 ),array('Categoria.tipo_composto'=> 3)))))));
                                                               $L=0;
                                                              foreach ($categoriasCompostos as $L => $prodComposto) {


                                                                  $tamanhosCategoriasCompostos = $this->Tamanho->find('all', array('recursive'=> -1, 'conditions'=> array('Tamanho.categoria_id'=> $prodComposto['Categoria']['id'])));

                                                                  $tamanhosCategoriasCompostos[$L]['Categoria']['tamanhos'] = array();

                                                                  $categoriasCompostos[$L]['Categoria']['tamanhos']= array($tamanhosCategoriasCompostos);
                                                                  $L++;
                                                              }
                                                               array_push($resultados[$i]['Categoria'][$j]['categoriascomposicao'] , $categoriasCompostos);
                                                          }



                                                          if($categoria['parte_composto']==2){

                                                              $categoriasCompostos = $this->Categoria->find('all', array('recursive'=> -1 , 'order'=>'Categoria.nome ASC', 'conditions' => array('AND'=> array(array('Categoria.disponivel' => 1),array('Categoria.ativo' => 1),array('Categoria.filial_id' => $_GET['fp']), array('OR'=> array(array('Categoria.tipo_composto'=> 2 ),array('Categoria.tipo_composto'=> 3)))))));
                                                               $L=0;
                                                              foreach ($categoriasCompostos as $L => $prodComposto) {


                                                                  $tamanhosCategoriasCompostos = $this->Tamanho->find('all', array('recursive'=> -1, 'conditions'=> array('Tamanho.categoria_id'=> $prodComposto['Categoria']['id'])));

                                                                  $tamanhosCategoriasCompostos[$L]['Categoria']['tamanhos'] = array();

                                                                  $categoriasCompostos[$L]['Categoria']['tamanhos']= array($tamanhosCategoriasCompostos);
                                                                  $L++;
                                                              }
                                                               array_push($resultados[$i]['Categoria'][$j]['categoriascomposicao'] , $categoriasCompostos);
                                                          }


                                                          if($categoria['parte_composto']==3){
                                                              $categoriasCompostos = $this->Categoria->find('all', array('recursive'=> -1 , 'order'=>'Categoria.nome ASC','conditions' => array('AND'=> array(array('Categoria.disponivel' => 1),array('Categoria.ativo' => 1),array('Categoria.filial_id' => $_GET['fp']), array('OR'=> array(array('Categoria.tipo_composto'=> 1 ),array('Categoria.tipo_composto'=> 2),array('Categoria.tipo_composto'=> 3)))))));
                                                               array_push($resultados[$i]['Categoria'][$j]['categoriascomposicao'] , $categoriasCompostos);
                                                          }

                                                 }

                                          }else{
                                              unset($resultados[$i]['Categoria'][$j]);
                                          }
                                //debug($resultados[$i]['Categoria'][$j]['preco_venda']);

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
      header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
      $this->loadModel('Categoria');
      $this->loadModel('Tamanho');

      $categoria= $this->Categoria->find('first',array(
          'recursive'=> -1,
          'conditions'=> array(
            'Categoria.id'=> $_GET['id']
          )
        )
      );

      $categoria = $this->Categoria->find('first',array(
          'recursive' => -1,
          'conditions' => array(
            'Categoria.id'=> $categoria['Categoria']['categoria_id']
          )
        )
      );

      $tamanhos = $this->Tamanho->find('all', array(
          'recursive' => -1,
          'conditions' => array(
            'AND'=> array(
              array('Tamanho.categoria_id'=> $_GET['id']),
              array('Tamanho.nome != ' => null ),
              array('Tamanho.nome != ' => '' )
            )
          )
        )
      );

      if(!empty($categoria))
      {
        $bebida = $this->Categoria->find('all', array(
            'recursive' => -1,
            'conditions' => array(
              'AND'=> array(
                array('Categoria.parte_bebida'=> 1),
                array('Categoria.ativo'=> true),
                array('Categoria.empresa_id'=> $categoria['Categoria']['empresa_id']),
                array('Categoria.filial_id'=> $categoria['Categoria']['filial_id'])
              )
            )
          )
        );

        $ganhe = $this->Categoria->find('all', array(
            'recursive' => -1,
            'conditions' => array(
              'AND'=> array(
                array('Categoria.parte_compre_ganhe'=> 1),
                array('Categoria.ativo'=> true),
                array('Categoria.empresa_id'=> $categoria['Categoria']['empresa_id']),
                array('Categoria.filial_id'=> $categoria['Categoria']['filial_id'])
              )
            )
          )
        );
      }
      $resultados = array(
        'Categoria'=> (!empty($categoria)) ? $categoria['Categoria'] : array(),
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
