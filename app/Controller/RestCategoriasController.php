<?php
App::uses('AppController', 'Controller');
class RestCategoriasController extends AppController {
    public $uses = array('Categoria');
    public $helpers = array('Html', 'Form');
    public $components = array('RequestHandler','checkbfunc');


    public function catsmobile() {
        header("Access-Control-Allow-Origin: *");
        $categorias = $this->Categoria->find('all',array('recursive'=> -1, 'conditions'=> array('filial_id'=> $_GET['fp'], 'ativo' => 1 )));
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

	public function prodsmobile() {

		//$clt = $_GET['a'];
		//$token =$_GET['b'];
		//$resp =$this->checkToken($clt, $token);
		//if($resp =='OK'){



			$this->loadModel('Categoria');
      $this->loadModel('Filial');
      $this->loadModel('Categoria');
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
      header("Access-Control-Allow-Origin: *");
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
