<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Cupons Controller
 *
 * @property Cupon $Cupon
 * @property PaginatorComponent $Paginator
 */
class CuponsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');
	public function beforeFilter()
	{
		$this->loadModel('Filial');
		$this->loadModel('Cupon');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));

		$vencidos = $this->Cupon->find('all',array('recursive'=> -1, 'conditions' => array('AND'=> array(array('Cupon.empresa_id'=> $this->Session->read('Auth.User.empresa_id'), array('Cupon.filial_id'=> $unicaFilial['Filial']['id']), array('Cupon.status'=> 'Disponível'), array('Cupon.validade <'=> date('Y-m-d')))))));
		$k=0;
		foreach ($vencidos as $key => $value)
		{
			$updateCupon = array(
				'id' => $value['Cupon']['id'],
				'status' => 'Cancelado'
			);
			$this->Cupon->create();
			$this->Cupon->save($updateCupon);
			$k++;
		}
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Cupon->recursive = 0;
		$this->set('Cupons', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout ='liso';
		if (!$this->Cupon->exists($id)) {
			throw new NotFoundException(__('Invalid cupon'));
		}
		$options = array('recursive' => -1,'conditions' => array('Cupon.' . $this->Cupon->primaryKey => $id));
		$this->set('cupon', $this->Cupon->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');

		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		if(isset($this->request->data['filter']))
		{
			foreach($this->request->data['filter'] as $key=>$value)
			{
				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
				$data = implode("-",array_reverse(explode("/",$this->request->data['filter']['datavalidade'])));
				$data= str_replace(" ","",$data);
				$this->request->data['filter']['datavalidade'] = $data;

				$data2 = implode("-",array_reverse(explode("/",$this->request->data['filter']['datavalidade-between'])));
				$data2= str_replace(" ","",$data2);
				$this->request->data['filter']['datavalidade-between'] = $data2;
			}
		}
		$this->Filter->addFilters(
			array(

	            'minhaslojas' => array(
	                'Cupon.filial_id' => array(
	                    'operator' => '=',
	                    'select'=> $lojas
	                )
	            ),
	            'empresa' => array(
	                'Cupon.empresa_id' => array(
	                    'operator' => '=',

	                )
	            ),
	            'numero' => array(
	                'Cupon.numero' => array(
	                    'operator' => '=',

	                )
	            ),
							'status' => array(
	                'Cupon.status' => array(
	                    'operator' => '=',
											'select' => array(''=>'Selecione', 'Cancelado'=> 'Cancelado', 'Disponível'=> 'Disponível', 'Utilizado'=> 'Utilizado'),
	                )
	            ),
							'cliente' => array(
						                'Cliente.nome' => array(
						                    'operator' => 'LIKE',
						                    'value' => array(
						                        'before' => '%', // optional
						                        'after'  => '%'  // optional
						                    ),
																'type'=> 'text'
						                ),

						            ),
							'datavalidade' => array(
					            'Cupon.validade' => array(
					                'operator' => 'BETWEEN',
					                'between' => array(
					                    'text' => __(' e ', true)
					                )
					            )
					        )
	        )
	    );

	    $conditiosAux= $this->Filter->getConditions();
			$unicaFilial= $this->Filial->find('first', array('recursive'=> -1, 'conditions'=> array('Filial.id' => $minhasFiliais)));
			if(empty($conditiosAux)){

				$this->request->data['filter']['minhaslojas']=(string) $unicaFilial['Filial']['id']  ;

				$this->request->data['filter']['empresa']=$this->Session->read('Auth.User.empresa_id');
				$dataIncio = date('Y-m-d');
				$dataTermino= date('Y-m-t');
				$this->request->data['filter']['datavalidade']=$dataIncio;
				$this->request->data['filter']['datavalidade-between']=$dataTermino;
			}else{
				$dataIncio  =  $this->request->data['filter']['datavalidade'] ;
				$dataTermino = $this->request->data['filter']['datavalidade-between'];
			}
			$this->Paginator->settings = array(
					'Cupon' => array(
						'limit' => 20,
						'conditions' => $this->Filter->getConditions(),
						'order' => 'Cupon.validade asc, Cliente.nome asc'
					)
			);

			$this->request->data['filter']['datavalidade'] = date("d/m/Y", strtotime($dataIncio));
			$this->request->data['filter']['datavalidade-between'] = date("d/m/Y", strtotime($dataTermino));

			if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			$this->Cupon->recursive = 0;
			$this->set('cupons', $this->Paginator->paginate());
			if ($this->request->is('post')) {
				$this->request->data['Cupon']['numero']=$this->geraNumero();
				$this->request->data['Cupon']['status']='Disponível';
				$this->request->data['Cupon']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
				if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
					$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect( $this->referer() );
				}
				$flagOk=false;
				$this->loadModel('Cliente');
				switch ($this->request->data['Cupon']['tipo_envio'])
				{
					case 1:
							$this->Cupon->create();

							if ($this->Cupon->save($this->request->data)) {
								$this->Session->setFlash(__('O cupom foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
								return $this->redirect(array('action' => 'add'));
							} else {
								$this->Session->setFlash(__('Houve um erro ao salvar  O cupom. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
							}
					break;
					case 0:
							$allCostumers = $this->Cliente->find('all',array('order'=>'Cliente.nome asc','recursive'=> -1, 'conditions'=> array('AND'=> array('Cliente.filial_id'=> $unicaFilial['Filial']['id'], array('Cliente.empresa_id'=> $this->Session->read('Auth.User.empresa_id'))))));

							foreach ($allCostumers as $costumer)
							{
								$cupomToSave = array(
									'percentual' => $this->request->data['Cupon']['percentual'],
									'validade' => $this->request->data['Cupon']['validade']['year'].'-'.$this->request->data['Cupon']['validade']['month'].'-'.$this->request->data['Cupon']['validade']['day'],
									'descricao' => $this->request->data['Cupon']['descricao'],
									'cliente_id' => $costumer['Cliente']['id'],
									'filial_id' => $this->request->data['filter']['minhaslojas'],
									'empresa_id' => $this->Session->read('Auth.User.empresa_id'),
									'numero' => $this->geraNumero(),
									'status'=> 'Disponível',
								);
							
								$this->Cupon->create();
								if ($this->Cupon->save($cupomToSave))
								{
									$flagOk=true;
								} else
								{
									$flagOk = false;
								}
							}
					break;
					case 2:
						$aniversario = date('-m-');

						$allCostumers = $this->Cliente->find(
							'all',array(
								'order'=>'Cliente.nome asc',
								'recursive'=> -1,
								'conditions'=> array(
										'AND'=> array(
											'Cliente.filial_id'=> $unicaFilial['Filial']['id'],
											array('Cliente.empresa_id'=> $this->Session->read('Auth.User.empresa_id')),
											array('Cliente.nasc LIKE '=> '%'.$aniversario."%")
										)
									)

							)
						);

						foreach ($allCostumers as $costumer)
						{
							$cupomToSave = array(
								'percentual' => $this->request->data['Cupon']['percentual'],
								'validade' => $this->request->data['Cupon']['validade']['year'].'-'.$this->request->data['Cupon']['validade']['month'].'-'.$this->request->data['Cupon']['validade']['day'],
								'descricao' => $this->request->data['Cupon']['descricao'],
								'cliente_id' => $costumer['Cliente']['id'],
								'filial_id' => $this->request->data['filter']['minhaslojas'],
								'empresa_id' => $this->Session->read('Auth.User.empresa_id'),
								'numero' => $this->geraNumero(),
								'status' => 'Disponível'
							);
							$this->Cupon->create();
							if ($this->Cupon->save($cupomToSave))
							{
								$flagOk=true;
							} else
							{
								$flagOk = false;
							}
						}
					break;
					default:
						# code...
						break;
				}
				if ($flagOk==true) {
					$this->Session->setFlash(__('O cupom foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect(array('action' => 'add'));
				} else {
					$this->Session->setFlash(__('Houve um erro ao salvar  O cupom. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				}
			}else
			{
				$this->loadModel('Cliente');
				$clientesNew = $this->Cliente->find('list',array('order'=>'Cliente.nome asc','recursive'=> -1, 'conditions'=> array('AND'=> array('Cliente.filial_id'=> $unicaFilial['Filial']['id'], array('Cliente.empresa_id'=> $this->Session->read('Auth.User.empresa_id'))))));
				$clientes = array();
				$clientes['']='';
				foreach ($clientesNew as $key => $value) {
					$clientes[$key] =$value;
				}

				$this->set(compact('clientes'));
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
		$this->layout ='liso';
		if (!$this->Cupon->exists($id)) {
			throw new NotFoundException(__('Invalid cupon'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'produtos';
		$userid = $this->Session->read('Auth.User.id');
		$userfuncao = $this->Session->read('Auth.User.funcao_id');
		if(!$Autorizacao->setAutorizacao($autTipo,$userfuncao)){
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect( $this->referer() );
		}
		if ($this->request->is(array('post', 'put'))) {
			if(!$Autorizacao->setAutoIncuir($autTipo,$userfuncao)){
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect( $this->referer() );
			}
			if ($this->Cupon->save($this->request->data)) {
				$this->Session->setFlash(__('O cupom foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar o cupom. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			}
		} else {
			$options = array('recursive' => -1, 'conditions' => array('Cupon.' . $this->Cupon->primaryKey => $id));
			$this->request->data = $this->Cupon->find('first', $options);
		}
	}
	public function massa()
	{
		if($this->request->data['Cupon']['em_massa'] == '')
		{
			$this->Session->setFlash(__('Escolha uma opção (Desativa ou Excluir) para executar esta ação. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect(array('action' => 'add'));
		}
		switch ($this->request->data['Cupon']['em_massa']) {
			case 0:
					foreach ($this->request->data['ativo'] as $key => $value)
					{
						if($value != 0)
						{
							$this->Cupon->create();
							$this->Cupon->save(array('id'=> $value, 'status' => 'Cancelado'));
						}
					}
					$this->Session->setFlash(__('Operação efetuada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
					return $this->redirect(array('action' => 'add'));
				break;
				case 1:
						foreach ($this->request->data['ativo'] as $key => $value)
						{
							if($value != 0)
							{
								$this->Cupon->id = $value;
								$this->Cupon->delete();
							}
						}
						$this->Session->setFlash(__('Operação efetuada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
						return $this->redirect(array('action' => 'add'));
					break;
			default:
				$this->Session->setFlash(__('Houve um erro ao executar esta operação. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect(array('action' => 'add'));
				break;
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
		$this->Cupon->id = $id;
		if (!$this->Cupon->exists()) {
			throw new NotFoundException(__('Invalid cupon'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Cupon->delete()) {
			$this->Session->setFlash(__('cupon foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover o cupom. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'add'));
	}
	public function geraNumero()
	{
		$flagNumero =false;
		$numero='';
		while($flagNumero == false)
		{
				$numero = $this->geraSenha(6, true, true);
				$cupom = $this->Cupon->find('first', array('recursive'=> -1, 'conditions'=> array('Cupon.numero'=> $numero)));
				if(empty($cupom))
				{
					$flagNumero=true;
				}
		}
		return $numero;
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
		$retorno =strtoupper($retorno);
		return $retorno;
	}

}
