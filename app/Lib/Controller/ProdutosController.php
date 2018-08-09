<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Produtos Controller
 *
 * @property Produto $Produto
 * @property PaginatorComponent $Paginator
 */
class ProdutosController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler','checkbfunc');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Produto->recursive = 0;
		$this->set('produtos', $this->Paginator->paginate());
	}
	public function prodsmobile() {
		$this->loadModel('Categoria');
		header("Access-Control-Allow-Origin: *");

		$this->layout ='loadprodutos';

		$resultados="teste";
		$resultados =  $this->Categoria->find('all', array('recursive' => 1));
		$i=0;
		foreach($resultados as $i => $categoria){
			$j=0;

			foreach($categoria['Produto'] as $j=> $produto){
				//debug($resultados[$i]['Produto'][$j]['preco_venda']);
				$this->checkbfunc->converteMoedaToView($resultados[$i]['Produto'][$j]['preco_venda']);
				$j++;
			}
			$i++;
		}
		$this->set(compact('resultados'));


	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Produto->exists($id)) {
			throw new NotFoundException(__('Invalid produto'));
		}
		$this->layout ='liso';
		$options = array('conditions' => array('Produto.' . $this->Produto->primaryKey => $id));
		$this->set('produto', $this->Produto->find('first', $options));
		$this->loadModel('Categoria');
		$produtoAux = $this->Produto->find('first', array('recursive' => -1, 'conditions' => array('Produto.id'=> $id)));
		$categoria = $this->Categoria->find('first', array('recursive' => -1, 'conditions' => array('Categoria.id'=> $produtoAux['Produto']['categoria_id'])));

		$this->set(compact('categoria'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		if(isset($_GET['loja'])){
			$loja=$_GET['loja'];
		}else{
			$loja=0;
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}

		if(isset($this->request->data['filter']))
		{

			foreach($this->request->data['filter'] as $key=>$value)
			{

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');



			}
		}

		$this->Filter->addFilters(
	        array(
	            'nome' => array(
	                'Produto.nome' => array(
	                    'operator' => 'LIKE',
	                    'value' => array(
	                        'before' => '%', // optional
	                        'after'  => '%'  // optional
	                    )
	                )
	            ),
	            'codigo' => array(
	                'Produto.id' => array(
	                    'operator' => '=',
	                )
	            ),
	            'minhaslojas' => array(
	                'Produto.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Produto.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	        )
	    );
		$conditiosAux= $this->Filter->getConditions();
		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));



		if(empty($conditiosAux)){

			$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

			$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
		}


		$this->Paginator->settings = array(
				'Produto' => array(
					'limit' => 20,
					'conditions' => $this->Filter->getConditions(),
					'order' => 'Produto.nome asc'
				)
			);


		$this->Produto->find('all', array('conditions'=> $this->Filter->getConditions(), 'recursive' => -1));
		//$produtos = $this->Paginator->paginate('Produto');
		$this->set('produtos', $this->Paginator->paginate());

		if ($this->request->is('post')) {


			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Produto->create();

			if($this->request->data['Produto']['foto']['name']==''){
				unset($this->request->data['Produto']['foto']);
			}
			$this->request->data['Produto']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');

			if(isset($this->request->data['Produto']['foto']['error']) && $this->request->data['Produto']['foto']['error'] === 0) {
                $source = $this->request->data['Produto']['foto']['tmp_name']; // Source
                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

                $nomedoArquivo = date('YmdHis').rand(1000,999999);
                $nomedoArquivo= $nomedoArquivo.$this->request->data['Produto']['foto']['name'];
                $nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
                move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
                $this->request->data['Produto']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
                $this->Produto->create(); // We have a new entry
                $this->Produto->saveAll($this->request->data); // Save the request

             	$this->Session->setFlash(__('O produto foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
             	return $this->redirect( $this->referer() );
            } else {

               // Save the request
               $this->Produto->create(); // We have a new entry
               unset($this->request->data['Produto']['foto']);
               if($this->Produto->saveAll($this->request->data)){

               		$this->Session->setFlash(__('O produto foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
              		return $this->redirect( $this->referer() );
               }else{
               		 $this->Session->setFlash(__('Erro ao salvar o produto . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
               }


            }

		}
		//$categorias = $this->Produto->Categoria->find('list');
		$this->loadModel('Categoria');
		$categorias = $this->Categoria->find('list', array('recursive'=> -1, 'conditions'=> array('Categoria.filial_id'=> $minhasFiliais)));
		$this->set(compact('categorias'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Produto->exists($id)) {
			throw new NotFoundException(__('Invalid produto'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		$this->layout ='liso';
		if ($this->request->is(array('post', 'put'))) {



			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}

			if(isset($this->request->data['Produto']['foto']['error']) && $this->request->data['Produto']['foto']['error'] == 0) {
				$tipo = $this->request->data['Produto']['foto']['type'];
				if($tipo == 'image/jpeg' || $tipo == 'image/gif' || $tipo == 'image/png'  || $tipo == 'image/jpg' || $tipo == 'image/jpeg')
				{

				}else{
					$this->Session->setFlash(__('Formato de imagem não permitido!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				 if($this->request->data['Produto']['foto']['name']!=''){
				 	$source = $this->request->data['Produto']['foto']['tmp_name']; // Source
				                $dest = ROOT . DS . 'app' . DS . 'webroot' . DS . 'fotossistema' . DS;   // Destination

				                $nomedoArquivo = date('YmdHis').rand(1000,999999);
				                $nomedoArquivo= $nomedoArquivo.$this->request->data['Produto']['foto']['name'];
				                 $nomedoArquivo=str_ireplace(' ','', $nomedoArquivo);
				                move_uploaded_file($source, $dest.$nomedoArquivo); // Move from source to destination (you need write permissions in that dir)
				                $this->request->data['Produto']['foto'] ='http://'.$_SERVER['SERVER_NAME'].'/fotossistema/'.$nomedoArquivo; // Replace the array with a string in order to save it in the DB
				                $this->Produto->create(); // We have a new entry
				 }else{
				 	unset($this->request->data['Produto']['foto']);
				 }

		                $this->Produto->saveAll($this->request->data); // Save the request

		             	$this->Session->setFlash(__('O produto foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		             	return $this->redirect( $this->referer() );
            } else {
               if($this->request->data['Produto']['foto']['name']==''){
	               // Save the request
	               $this->Produto->create(); // We have a new entry
	               unset($this->request->data['Produto']['foto']);
	               if($this->Produto->saveAll($this->request->data)){

	               		$this->Session->setFlash(__('O produto foi salvo com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
	              		return $this->redirect( $this->referer() );
	               }else{

	               		$this->Session->setFlash(__('Erro ao salvar o produto . Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
	              		return $this->redirect( $this->referer() );
	               }
               }else{

               	 	$this->Session->setFlash(__('Erro ao salvar o produto. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
               		return $this->redirect( $this->referer() );
               }

            }
		} else {
			$options = array('conditions' => array('Produto.' . $this->Produto->primaryKey => $id));
			$this->loadModel('Categoria');

			$categorias = $this->Categoria->find('list', array('recursive'=> -1, 'conditions'=> array('Categoria.filial_id'=> $minhasFiliais)));
			$this->set(compact('categorias'));
			$this->request->data = $this->Produto->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Produto->id = $id;
		if (!$this->Produto->exists()) {
			throw new NotFoundException(__('Invalid produto'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Produto->delete()) {
			$this->Session->setFlash(__('O produto foi removido com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o produto. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect( $this->referer() );
	}

	public function listarProdutos() {


		$this->layout ='loadlista';


		$produtos =  $this->Produto->find('all', array('recursive' => -1));
		$response = array();
		$response['page'] = 1;
		$response['total'] = 5;
		$response['count'] = 5;

		$i=0;


			foreach($produtos as $i=> $produto){
				//debug($resultados[$i]['Produto'][$j]['preco_venda']);
				//$this->checkbfunc->converteMoedaToView($resultados['Produto'][$j]['preco_venda']);
				$response[$i]['nome']=$produto['Produto']['nome'];
				$response[$i]['preco_venda']=number_format($produto['Produto']['preco_venda'], '2', ',', '.');
				$response[$i]['id']=$produto['Produto']['id'];

				$i++;
			}


		$this->set(array(
			'response' => $response,
			'_serialize' => array('response')
		));


	}

	public function aumentaEstoque(&$produto_id, &$qtde){
		$produto = $this->Produto->find('first', array('recursive'=> -1,'conditions'=> array('Produto.id'=> $produto_id)));
		$qtdeEstoque = 0;
		if($qtde == '' || $qtde == null){
			$qtde=0;
		}
		if(!empty($produto)){
			$qtdeEstoque = $produto['Produto']['estoque'];
			if($qtdeEstoque == '' || $qtdeEstoque == null){
				$qtdeEstoque=0;
			}
		}
		$novoEstoque = $qtdeEstoque + $qtde;
		$updateEstoque = array('id'=> $produto_id, 'estoque'=> $novoEstoque );

		if($this->Produto->save($updateEstoque)){
			return true;
		}else{
			return false;
		}
	}

	public function diminueEstoque(&$produto_id, &$qtde){

		$produto = $this->Produto->find('first', array('recursive'=> -1,'conditions'=> array('Produto.id'=> $produto_id)));
		$qtdeEstoque = 0;
		if($qtde == '' || $qtde == null){
			$qtde=0;
		}
		if(!empty($produto)){
			$qtdeEstoque = $produto['Produto']['estoque'];
			if($qtdeEstoque == '' || $qtdeEstoque == null){
				$qtdeEstoque=0;
			}
		}
		$novoEstoque = $qtdeEstoque - $qtde;
		$updateEstoque = array('id'=> $produto_id, 'estoque'=> $novoEstoque);
		if($this->Produto->save($updateEstoque)){

			return true;

		}else{

			return false;
		}

	}
}
