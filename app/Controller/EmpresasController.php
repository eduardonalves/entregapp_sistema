<?php
App::uses('AppController', 'Controller');
/**
 * Empresas Controller
 *
 * @property Empresa $Empresa
 * @property PaginatorComponent $Paginator
 */
class EmpresasController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','RequestHandler',);

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$options = array('conditions' => array('Empresa.' . $this->Empresa->primaryKey => $id));
		$this->set('empresa', $this->Empresa->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->Empresa->recursive = 0;
		$this->set('empresas', $this->Paginator->paginate());
		if ($this->request->is('post')) {
			$this->Empresa->create();
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('A empresa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		}
		$funcaos = $this->Empresa->Funcao->find('list');
		$this->set(compact('funcaos'));
	}

/**
 * add method
 *
 * @return bolean
 */
	public function empresaAtiva() {
		$this->loadModel('Empresa');
		 $empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1));
		 if($empresa['Empresa']['status_empresa']==0){
		 	return false;
		 }else{
		 	return true;
		 }
	}

	public function empresaIdAtiva(&$id) {
		$this->loadModel('Empresa');
		 $empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1,'conditions'=> array('Empresa.id'=> $id)));

		 if(empty($empresa)){
		 	return false;
		 }else{

		 	if($empresa['Empresa']['status_empresa']==false ){

		 		return false;
			 }else{

			 	return true;
			 }
		 }

	}

	public function filialIdAtiva(&$id) {
		$this->loadModel('Filial');
		 $empresa = $this->Filial->find('first', array('order' => array('Filial.id' => 'asc'), 'recursive' => -1,'conditions'=> array('Filial.id'=> $id)));

		 if(empty($empresa)){
		 	return false;
		 }else{
		 	if($empresa['Filial']['status_empresa']==1  && $empresa['Filial']['status_abertura']==1){
		 		return true;
			 }else{
			 	return false;
			 }
		 }

	}


	/**
 * add method
 *
 * @return bolean
 */
	public function empresaAberta() {
		$this->loadModel('Empresa');
		 $empresa = $this->Empresa->find('first', array('order' => array('Empresa.id' => 'asc'), 'recursive' => -1));
		 if($empresa['Empresa']['status_aberta']==0){
		 	return false;
		 }else{
		 	return true;
		 }
	}


	/**
 * add method
 *
 * @return bolean
 */
	public function verificaValorFrete($filial_id, $bairro, $cidade, $uf) {
		$this->loadModel('Empresa');
		$this->loadModel('Filial');
		$this->loadModel('Estado');
		$this->loadModel('Cidad');
		$this->loadModel('Bairro');
		

		$filial = $this->Filial->find('first', array('order' => array('Filial.id' => 'asc'), 'recursive' => -1,'conditions'=> array('Filial.id'=> $filial_id)));
		$minhasCidade = $this->Cidad->find('first',array('recursive'=>-1, 'conditions'=> array('and'=> array( array('Cidad.id'=> $cidade ), array('Cidad.filial_id'=> $filial_id), array('Cidad.ativo'=>true )))));
		$uf = $this->Estado->find('first',array('recursive'=>-1, 'conditions'=> array('and'=> array( array('Estado.id'=> $uf ), array('Estado.ativo'=>true )))));
		
		

		if(empty($uf)){
			return false;
			exit;
			//debug($filial_id);

		}
		if($filial['Filial']['taxa_padrao'] ==true){

			$frete=number_format($filial['Filial']['valor_padrao'],2,',','.');

			return $frete;

		}else{
			
			if(!empty($minhasCidade)){

				if($minhasCidade['Cidad']['cobertura_total']==true) {
					//$frete=number_format($minhasCidade['Cidad']['valor'],2,',','.');
					$frete=$minhasCidade['Cidad']['valor'];
					return $frete;
				}else{
					$meuBairro = $this->Bairro->find('first',array(
						'recursive'=>-1, 
						'conditions'=> array(
							'id'=> $bairro,
							'ativo'=> 1
							)
						));
					
					if(!empty($meuBairro)){
						//$frete=number_format($meuBairro['Bairro']['valor'],2,',','.');
						$frete=$meuBairro['Bairro']['valor'];
						return $frete;
					}else{
						if($filial['Filial']['locais_especificos'] !=true){
							if($filial['Filial']['valor_padrao'] !='' ){
								//$frete=number_format($filial['Filial']['valor_padrao'],2,',','.');
								$frete=$filial['Filial']['valor_padrao'];
								return $frete;
							}else{
								return false;
							}

						}else{
							return false;
						}
					}
				}
			}else{
				if($filial['Filial']['locais_especificos'] !=true){
					if($filial['Filial']['valor_padrao'] !='' ){
						//$frete=number_format($filial['Filial']['valor_padrao'],2,',','.');
						$frete=$filial['Filial']['valor_padrao'];
						return $frete;
					}else{
						return false;
					}

				}else{
					return false;
				}
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Empresa->save($this->request->data)) {
				$this->Session->setFlash(__('A empresa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'edit', $id));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {


			$this->request->data = $this->Empresa->find('first', array('recursive' => -1, 'conditions' => array('Empresa.id' => $id)));

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
		$this->Empresa->id = $id;
		if (!$this->Empresa->exists()) {
			throw new NotFoundException(__('Invalid empresa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Empresa->delete()) {
			$this->Session->setFlash(__('A empresa foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a empresa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}


	}
