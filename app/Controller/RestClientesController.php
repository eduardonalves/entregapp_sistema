<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
class RestClientesController extends AppController {
    public $uses = array('Cliente');
    public $helpers = array('Html', 'Form','Session');
    public $components = array('RequestHandler','checkbfunc');

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

  public function indexmobile($value='')
  {
    $this->loadModel('Pedido');
		$this->loadModel('Salt');
		$this->layout ='ajaxaddpedido';
		header("Access-Control-Allow-Origin: *");
    $ultimopedido = array();
    $salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));

  //  if ($this->request->is('post')) {

      //if($this->request->data['salt'] == $salt['Salt']['salt']){

        $this->loadModel('Pedido');
        $pedidos = $this->Pedido->find('all', array('fields' => 'Pedido.nomecadcliente','group'=> 'Pedido.nomecadcliente','recursive' => -1, 'conditions'=>array('Pedido.mesa_id'=> $this->request->data['mesa_id'],'Pedido.status_finalizado !=' => 1 )));
        

        foreach ($pedidos as $key5 => $value5) {

          $ultimopedido['Pedido'][$key5]= $value5['Pedido'];
        }

      //}
  //  }
  $this->set(array(
    'ultimopedido' => $ultimopedido,
    '_serialize' => array('ultimopedido')
  ));
  }
	public function loginmobile() {
    $this->loadModel('Cliente');
		//header('Content-Type: application/json; Charset="UTF-8"');
		$this->loadModel('Salt');
		$this->layout ='ajaxaddpedido';
		header("Access-Control-Allow-Origin: *");

		$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
		$senha= $this->Auth->password($this->request->data['password']);
		$usuario =$this->request->data['username'];
		$saltenviado = $this->request->data['salt'];
        $ultimopedido='';

		if ($this->request->is('post')) {

			if($this->request->data['salt'] == $salt['Salt']['salt']){


				$ultimopedido="inicio";
        $isAtendente = false;
        if(isset($this->request->data['atendente_id']))
        {
          if($this->request->data['atendente_id'] == true){
            $isAtendente = true;
          }
        }

        if($isAtendente == false){
            $user=$this->Cliente->find('first', array('recursive' => -1,'conditions' => array('AND' => array( array('Cliente.password' => $senha), array('Cliente.username' => $usuario)))));
        }else{
          $this->loadModel('Atendente');
          $user=$this->Atendente->find('first', array('recursive' => -1,'conditions' => array('AND' => array( array('Atendente.password' => $senha), array('Atendente.username' => $usuario)))));
        }


				$Empresa = new EmpresasController;
				if($Empresa->empresaIdAtiva($this->request->data['empresa']) ==false && $isAtendente == false){
                   
					$ultimopedido="EmpresaInativa";
				}else{

          $ultimopedido=$user;
					if(!empty($user)){
            if($isAtendente == true){
                

              $user['Cliente']['email'] = $user['Atendente']['email'];
              $user['Cliente']['ativo'] = $user['Atendente']['ativo'];
              $user['Cliente']['nome'] = $user['Atendente']['nome'];
              $user['Cliente']['id'] = $user['Atendente']['id'];
              $user['Cliente']['username'] = $user['Atendente']['username'];
              $user['Cliente']['filial_id'] = $user['Atendente']['filial_id'];
              $user['Cliente']['empresa_id'] = $user['Atendente']['empresa_id'];
              $user['Cliente']['ativo'] = $user['Atendente']['ativo'];
              $user['Cliente']['token'] = $user['Atendente']['token'];

              //Inicio minhasMesas
              $this->loadModel('Mesa');
							$mesas = $this->Mesa->find('all', array('recursive' => -1, 'conditions'=>
                                    array('Mesa.filial_id'=>  $user['Atendente']['filial_id'])
                                )
                        );

							foreach ($mesas as $key5 => $value5) {
								$ultimopedido['Mesa'][$key5]= $value5['Mesa'];
							}
            //  print_r($ultimopedido['Mesa']);
            //  die('aqui');
              //Fim mesas


            }
						if($user['Cliente']['ativo'] == 1){

							$token = $this->geraSenha(10, false, true);
							$ultimopedido['Cliente']['token']= $token;
							$this->loadModel('Empresa');
							$this->loadModel('Produto');
              if($isAtendente == true){
                $this->request->data['empresa'] =$user['Atendente']['empresa_id'];
                $this->request->data['filial'] =$user['Atendente']['filial_id'];
              }
							$empresa= $this->Empresa->find('first', array('recursive' => -1,'conditions' => array('Empresa.id' => $this->request->data['empresa'])));
              if($isAtendente != true){

							         $uptadeToken = array('id' => $user['Cliente']['id'],'token' => $token, 'latdest' =>$empresa['Empresa']['lat'], 'lgndest'=> $empresa['Empresa']['lng'] );
                                     
              }else{
                $uptadeToken = array('id' => $user['Atendente']['id'],'token' => $token );
                
  				$this->Atendente->save($uptadeToken);
                
              }
              if($isAtendente != true){
                  $ultimopedido['Cliente']['latdest']= $empresa['Empresa']['lat'];
							    $ultimopedido['Cliente']['lngdest']= $empresa['Empresa']['lng'];
              }
              if($isAtendente != true){
							  $ultimopedido['Cliente']['frete_cadastro']=$Empresa->verificaValorFrete($this->request->data['filial'],$this->checkbfunc->removeDetritos($ultimopedido['Cliente']['bairro']), $this->checkbfunc->removeDetritos($ultimopedido['Cliente']['cidade']), $ultimopedido['Cliente']['uf']);
              }else{
                $ultimopedido['Cliente']['frete_cadastro'] =0;
              }
              $this->loadModel('Pagamento');
							$pagamentos = $this->Pagamento->find('all', array('recursive' => -1, 'conditions'=>array('Pagamento.filial_id'=> $this->request->data['filial'])));

							foreach ($pagamentos as $key => $value) {
								$ultimopedido['Pagamento'][$key]= $value['Pagamento'];
							}





							$promodia =false;
							$promodia = $this->confirmaPromoDia($this->request->data['filial'], 1);

							if($promodia==true){
								$bebidas = $this->Produto->find('all',array('recursive'=> -1, 'conditions'=> array('AND' => array(array('Produto.filial_id'=>$this->request->data['filial']), array('Produto.ativo'=>1), array('Produto.disponivel'=>1), array('Produto.parte_bebida'=> 1)) )));

								foreach ($bebidas as $key => $value) {
									$ultimopedido['Bebidas'][$key]= $value['Produto'];
								}

							}
							$promodia = $this->confirmaPromoDia($this->request->data['filial'], 2);

							if($promodia==true){
								$pagueGanhe = $this->Produto->find('all',array('recursive'=> -1, 'conditions'=> array('AND' => array(array('Produto.filial_id'=>$this->request->data['filial']), array('Produto.ativo'=>1), array('Produto.disponivel'=>1), array('Produto.parte_compre_ganhe'=> 1)) )));

								foreach ($pagueGanhe as $key => $value) {
									$ultimopedido['PagueGanhe'][$key]= $value['Produto'];
								}
							}
							$this->Cliente->create();
							$this->Cliente->save($uptadeToken);
						//$this->Auth->allow('*');
						}else{

							$ultimopedido="ErroLogin";

						}
					}else{
						$ultimopedido="ErroLogin";
					}
					 //$ultimopedido =$senha;

				}



				/*$this->set(array(
					'ultimopedido' => $ultimopedido,
					'_serialize' => array('ultimopedido')
				));*/
			}else{

			}
		}
		//print_r($ultimopedido);
		
		
            $this->set(array(
                    'ultimopedido' => $ultimopedido,
                    '_serialize' => array('ultimopedido')
                ));
    }
    	   public function getPromoDia(){
    	   	header("Access-Control-Allow-Origin: *");
    	   	$filial_id = $_GET['fp'];
    	   	$ultimopedido=array();

    	   	$promodia =false;
    	   	$this->loadModel('Produto');
		$promodia = $this->confirmaPromoDia($filial_id, 1);

		if($promodia==true){
			$bebidas = $this->Produto->find('all',array('recursive'=> -1, 'conditions'=> array('AND' => array(array('Produto.filial_id'=>$filial_id,), array('Produto.ativo'=>1), array('Produto.disponivel'=>1), array('Produto.parte_bebida'=> 1)) )));

			foreach ($bebidas as $key => $value) {
				$ultimopedido['Bebidas'][$key]= $value['Produto'];
			}

		}
		$promodia = $this->confirmaPromoDia($filial_id, 2);

		if($promodia==true){
			$pagueGanhe = $this->Produto->find('all',array('recursive'=> -1, 'conditions'=> array('AND' => array(array('Produto.filial_id'=>$filial_id,), array('Produto.ativo'=>1), array('Produto.disponivel'=>1), array('Produto.parte_compre_ganhe'=> 1)) )));

			foreach ($pagueGanhe as $key => $value) {
				$ultimopedido['PagueGanhe'][$key]= $value['Produto'];
			}
		}

    	   	$this->set(array(
					'ultimopedido' => $ultimopedido,
					'_serialize' => array('ultimopedido')
				));
    	   }
	    public function confirmaPromoDia($filial_id,$promocao_id){
	    	$dia = date('w');
	    	$this->loadModel('Diasdepromocao');
		$diasdepromocao = $this->Diasdepromocao->find('first',array('recursive'=>-1, 'conditions' => array('AND' => array(array('Diasdepromocao.filial_id'=> $filial_id), 'Diasdepromocao.promocao_id'=> $promocao_id))));
    if(!empty($diasdepromocao))
    {
      $dia = (float) $dia;

  	    	switch ($dia) {
  		    case 0:
  			       $dia="domingo";
  			       if($diasdepromocao['Diasdepromocao']['domingo']==true){
  			       	return true;
  			       }else{
  			       	return false;
  			       }

  		        break;
  		    case 1:
  			        $dia="segunda";
  			       if($diasdepromocao['Diasdepromocao']['segunda']==true){
  			       	return true;
  			       }else{
  			       	return false;
  			       }
  		    	break;
  		    case 2:
  			        $dia="terca";
  			       if($diasdepromocao['Diasdepromocao']['terca']==true){
  			       	return true;
  			       }else{
  			       	return false;
  			       }
  		        break;
  		    case 3:
  			        $dia="quarta";
  			       if($diasdepromocao['Diasdepromocao']['quarta']==true){
  			       	return true;

  			       }else{
  			       	return false;
  			       }
  		        break;
  		    case 4:
  			        $dia="quinta";

  			       if($diasdepromocao['Diasdepromocao']['quinta']==true){

  			       	return true;
  			       }else{
  			       	return false;
  			       };
  		        break;
  		    case 5:
  			        $dia="sexta";
  			       if($diasdepromocao['Diasdepromocao']['sexta']==true){
  			       	return true;
  			       }else{
  			       	return false;
  			       }
  		        break;
  		    case 6:

  			        $dia="sabado";
  			       if($diasdepromocao['Diasdepromocao']['sabado']==true){
  			       	return true;
  			       }else{
  			       	return false;
  			       }
  		       break;
  		    default:
  		       return false;
  		}
    }else {
      return false;
    }


	    }
	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
	{
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';

	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
	$rand = mt_rand(1, $len);
	$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
	}
	public function formtrocasenha()
	{
		header("Access-Control-Allow-Origin: *");
		 $this->layout = 'login';
		 if ($this->request->is('post')) {

		 	if(($this->request->data['Changesenha']['password'] == '' || $this->request->data['Changesenha']['confirmpassword'] == '') || ($this->request->data['Changesenha']['password'] != $this->request->data['Changesenha']['confirmpassword']))
		 	{

		 		//$this->Session->setFlash(__('Your message here.', true));
		 		$this->Session->setFlash(__('Erro: As senhas digitadas não podem ser vazias e devem ser iguais.'), 'default', array('class' => 'error-flash alert alert-danger'));
				$_GET['t']= $this->request->data['Changesenha']['tk'];
				//return $this->redirect( $this->referer() );
				//die('aqui');
		 	}else{
		 		$this->loadModel('Token');
			 	$token = $this->Token->find('first',array('recursive'=> -1, 'conditions'=> array('AND'=> array(array('Token.ativo'=> true), array('Token.token'=> $this->request->data['Changesenha']['tk'])))));
			 	$_GET['t']= $this->request->data['Changesenha']['tk'];
			 	if(!empty($token)){
			 		$dataToken = date("Y-m-d", strtotime($token['Token']['created']));
			 		$today = date('Y-m-d');
			 		if($dataToken== $today)
			 		{
			 			$dataToSaveToken = array(
			 				'id' => $token['Token']['id'],
			 				'ativo'=> false,
			 			);
			 			$this->Token->save($dataToSaveToken);
			 			$dataToSaveCliente = array(
			 				'id' => $token['Token']['cliente_id'],
			 				'password'=> $this->request->data['Changesenha']['password']
			 			);
			 			$dataToSaveToken = array(
			 				'ativo'=> false,
			 				'cliente_id' => $token['Token']['cliente_id'],
			 			);
			 			$this->Token->updateAll($dataToSaveToken);
						if($this->Cliente->save($dataToSaveCliente)){
							$this->Session->setFlash(__('Sua senha foi redefinida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
							//return $this->redirect( $this->referer() );
						}else{
							$this->Session->setFlash(__('Erro não foi possível redefinir sua senha'), 'default', array('class' => 'error-flash alert alert-danger'));
							//return $this->redirect( $this->referer() );
						}
			 		}
			 	}else{
			 		$this->Session->setFlash(__('Erro este link não é mais válido, entre no aplicativo solicite novamente uma redefinição de senha.'), 'default', array('class' => 'error-flash alert alert-danger'));
					//return $this->redirect( $this->referer() );
			 	}
		 	}




		 }
	}
	public function recuperarsenha()
	{
		header("Access-Control-Allow-Origin: *");
		$cliente = $this->Cliente->find('first',array('recursive'=> -1,'conditions'=> array('Cliente.username'=> $this->request->data['clt'])));
		if(!empty($cliente))
		{
			if($this->Cliente->recoverpassword($cliente['Cliente']['id']))
			{
				$ultimocliente ='ok';
			}else
			{
				$ultimocliente ='sem email';
			}

		}else
		{
			$ultimocliente ='sem email';
		}

		$this->set(array(
						'ultimocliente' => $ultimocliente,
						'_serialize' => array('ultimocliente')
					));
	}
	public function addmobile() {

		header("Access-Control-Allow-Origin: *");

		if ($this->request->is('post')) {
			$this->loadModel('Salt');
			$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
			$this->layout='liso';

			if($this->request->data['Cliente']['salt'] == $salt['Salt']['salt']){

				$this->Cliente->create();
				if(isset($this->request->data['Cliente']['password'])){
					if($this->request->data['Cliente']['password'] ==""){
						unset($this->request->data['Cliente']['password']);
					}
				}
				
				
				$clienteExistente = $this->Cliente->find('first', array('conditions' => array('Cliente.username' => $this->request->data['Cliente']['username'])));
				$Empresa = new EmpresasController;
				if(!empty($clienteExistente)){
					if($this->request->data['Cliente']['id'] == $clienteExistente['Cliente']['id']){

						if ($this->Cliente->save($this->request->data)) {

							$ultimocliente = $this->Cliente->find('first', array('conditions' => array('Cliente.id' => $this->request->data['Cliente']['id']), 'recursive' => -1));

							$ultimocliente['Cliente']['frete_cadastro']=$Empresa->verificaValorFrete($this->request->data['Cliente']['filial_id'],$this->checkbfunc->removeDetritos($ultimocliente['Cliente']['bairro']), $this->checkbfunc->removeDetritos($ultimocliente['Cliente']['cidade']), $ultimocliente['Cliente']['uf']);

						} else {
							$ultimocliente="Erro";
						}
					}else{
						$ultimocliente='ErroUsuarioDuplo';
					}
				}else{

					$this->request->data['Cliente']['ativo']= 1;
					if ($this->Cliente->save($this->request->data)) {

						$ultimocliente = $this->Cliente->find('first', array('order' => array('Cliente.id' => 'desc'), 'recursive' => -1));
						$ultimocliente['Cliente']['frete_cadastro']=$Empresa->verificaValorFrete($this->request->data['Cliente']['filial_id'],$this->checkbfunc->removeDetritos($ultimocliente['Cliente']['bairro']), $this->checkbfunc->removeDetritos($ultimocliente['Cliente']['cidade']), $ultimocliente['Cliente']['uf']);

					} else {
						$ultimocliente="Erro";
					}
				}
				
				$this->set(array(
						'ultimocliente' => $ultimocliente,
						'_serialize' => array('ultimocliente')
					));
			}
		}

	}

	function image_fix_orientation($path){
		//read EXIF header from uploaded file
		$exif = exif_read_data($path);

		//fix the Orientation if EXIF data exist
		if(!empty($exif['Orientation'])) {
		    switch($exif['Orientation']) {
		        case 8:
		            $createdImage = imagerotate($image,90,0);
		            break;
		        case 3:
		            $createdImage = imagerotate($image,180,0);
		            break;
		        case 6:
		            $createdImage = imagerotate($image,-90,0);
		            break;
		    }
		}
	}

	// Quality is a number between 0 (best compression) and 100 (best quality)
	function base64_to_jpeg($base64_string, $output_file) {
	    $ifp = fopen($output_file, "wb");

	    $data = explode(',', $base64_string);

	    fwrite($ifp, base64_decode($data[1]));
	    fclose($ifp);

	    return $output_file;
	}


	function resample($jpgFile, $thumbFile, $width, $orientation) {
    	// Get new dimensions
	    list($width_orig, $height_orig) = getimagesize($jpgFile);
	    $height = (int) (($width / $width_orig) * $height_orig);
	    // Resample
	    $image_p = imagecreatetruecolor($width, $height);
	    $image   = imagecreatefromjpeg($jpgFile);
	    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	    // Fix Orientation
	    switch($orientation) {
	        case 3:
	            $image_p = imagerotate($image_p, 180, 0);
	            break;
	        case 6:
	            $image_p = imagerotate($image_p, -90, 0);
	            break;
	        case 8:
	            $image_p = imagerotate($image_p, 90, 0);
	            break;
	    }
	    // Output
	    imagejpeg($image_p, $thumbFile, 90);
	}
	public function addFotosmobile() {

		header("Access-Control-Allow-Origin: *");

		if ($this->request->is('post','put')) {
			$this->loadModel('Salt');
			$salt = $this->Salt->find('first', array('conditions' => array('Salt.id' => 1)));
			$clienteId = $this->request->data['Cliente']['id'];
			$ultimocliente="";

			if($this->request->data['Cliente']['salt'] == $salt['Salt']['salt']){

				if(isset($this->request->data['Cliente']['foto']['error']) && $this->request->data['Cliente']['foto']['error'] == 0) {


			                $source = $this->request->data['Cliente']['foto']['tmp_name']; // Source
			                //$this->resample($source, $source, 400, );
			                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotoscli' . DS;   // Destination

			                $nomedoArquivo = date('YmdHis').rand(1000,999999);
			                $nomedoArquivo= $nomedoArquivo.$this->request->data['Cliente']['foto']['name'];
			                $nomedoArquivo = str_replace("%", "", $nomedoArquivo);
			                $nomedoArquivo = str_replace(" ", "", $nomedoArquivo);
			                $teste = explode("fotoscli", $nomedoArquivo);
			                if(isset($teste[1])){
			                	$testeAux = explode(".", $teste[1]);
					if(isset($testeAux[1])){
						$this->request->data['Cliente']['fotoexif'] =0;

					}else{
						$this->request->data['Cliente']['fotoexif'] =1;
					}
			                }

			                move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
			                $this->request->data['Cliente']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotoscli/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
			                $this->Cliente->create(); // We have a new entry

				           if($this->Cliente->save($this->request->data)){

			               		$ultimocliente = $this->Cliente->find('first', array('order' => array('Cliente.id' => 'desc'), 'recursive' => -1, 'conditions' => array('Cliente.id' => $clienteId)));
				           }else{

			               		$ultimocliente ="ErroGravar";
				           }

			            } else {
		               		unset($this->request->data['Cliente']['foto']);

				           if($this->Cliente->save($this->request->data)){

			               		$ultimocliente = $this->Cliente->find('first', array('order' => array('Cliente.id' => 'desc'), 'recursive' => -1, 'conditions'=> array('Cliente.id'  => $clienteId)));
				           }else{

			               		$ultimocliente ="ErroGravar";
				           }
	           			}


			}
			$this->set(array(
						'ultimocliente' => $ultimocliente,
						'_serialize' => array('ultimocliente')
					));
		}

	}
}
