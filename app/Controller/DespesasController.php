<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Autorizacaos');
App::import('Controller', 'Users');
/**
 * Despesas Controller
 *
 * @property Despesa $Despesa
 * @property PaginatorComponent $Paginator
 */
class DespesasController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'checkbfunc');

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Despesa->recursive = 0;
		$this->set('despesas', $this->Paginator->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		$this->layout = 'liso';
		if (!$this->Despesa->exists($id)) {
			throw new NotFoundException(__('Invalid despesa'));
		}
		$options = array('conditions' => array('Despesa.' . $this->Despesa->primaryKey => $id));
		$this->set('despesa', $this->Despesa->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		$this->loadModel('Filial');
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'funcoes';
		$userid = $this->Session->read('Auth.User.id');
		$userdespesa = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$lojas = $User->getSelectFiliais($userid);

		//converte a data

		if (isset($this->request->data['filter'])) {

			foreach ($this->request->data['filter'] as $key => $value) {

				$this->request->data['filter']['empresa'] = $this->Session->read('Auth.User.empresa_id');
				$data = implode("-", array_reverse(explode("/", $this->request->data['filter']['dataPedido'])));
				$data = str_replace(" ", "", $data);
				$this->request->data['filter']['dataPedido'] = $data;

				$data2 = implode("-", array_reverse(explode("/", $this->request->data['filter']['dataPedido-between'])));
				$data2 = str_replace(" ", "", $data2);
				$this->request->data['filter']['dataPedido-between'] = $data2;
			}
		}

		if (!$Autorizacao->setAutorizacao($autTipo, $userdespesa)) {
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect($this->referer());
		}
		$unicaFilial = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $minhasFiliais)));
		$this->loadModel('Categoriasdespesa');
		$categoriasdespesaAux = $this->Categoriasdespesa->find('list', array(
			'recursive' => -1,
			'conditions' => array(
				'filial_id' => $minhasFiliais,
				'ativo'=> 1
			),
			'order' => 'categoria asc'
		));
		$categoriasdespesa = $this->Categoriasdespesa->find('list', array(
			'recursive' => -1,
			'conditions' => array(
				'filial_id' => $minhasFiliais,
				'ativo'=> 1
			),
			'order' => 'categoria asc'
		));
		array_unshift($categoriasdespesa, 'Selecione');
		array_unshift($categoriasdespesaAux, array(''=>'Todas'));

		$this->Filter->addFilters(
			array(
				'despesa' => array(
					'Despesa.despesa' => array(
						'operator' => 'LIKE',
						'value' => array(
							'before' => '%', // optional
							'after'  => '%'  // optional
						)
					)
				),
				'minhaslojas' => array(
					'Despesa.filial_id' => array(
						'operator' => '=',
						'select' => $lojas
					)
				),
				'categoriasdespesa' => array(
					'Categoriasdespesa.id' => array(
						'operator' => '=',
						'select' => $categoriasdespesaAux
					)
				),
				'recorrente' => array(
					'Despesa.recorrente' => array(
						'operator' => '=',
						'select' => array(''=>'Todas','1'=> 'Sim', '0'=> 'Não')
					)
				),
				'status_pago' => array(
					'Despesa.status_pago' => array(
						'operator' => '=',
						'select' => array(''=>'Todas','1'=> 'Pago', '0'=> 'Em Aberto')
					)
				),
				'empresa' => array(
					'Despesa.empresa_id' => array(
						'operator' => '=',

					)
				),
				
				'dataPedido' => array(
					'Despesa.data_vencimento' => array(
						'operator' => 'BETWEEN',
						'between' => array(
							'text' => __(' e ', true)
						)
					)
				),
			)
		);






		$this->Paginator->settings = array(
			'Despesa' => array(
				'limit' => 20,
				'conditions' => $this->Filter->getConditions(),
				'order' => 'Despesa.data_vencimento asc'
			)
		);

		$conditiosAux = $this->Filter->getConditions();
		
		if (empty($conditiosAux)) {

			$this->request->data['filter']['minhaslojas'] = (string) $unicaFilial['Filial']['id'];

			$this->request->data['filter']['empresa'] = $this->Session->read('Auth.User.empresa_id');

			$dataIncio = date('Y-m-01');
			$dataTermino = date('Y-m-t');


			$this->request->data['filter']['dataPedido'] = $dataIncio;
			$this->request->data['filter']['dataPedido-between'] = $dataTermino;
		} else {
			$dataIncio  =  $this->request->data['filter']['dataPedido'];
			$dataTermino = $this->request->data['filter']['dataPedido-between'];
		}
		$despesas = $this->Despesa->find('all', array('conditions' => $this->Filter->getConditions(), 'recursive' => 0));

		$totalEmAberto = 0;
		$totalPago = 0;
		$totalGeral = 0;

		foreach ($despesas as $key => $value) {
			if ($value['Despesa']['status_pago']) {
				$totalPago += $value['Despesa']['valor'];
			} else {
				$totalEmAberto +=  $value['Despesa']['valor'];
			}
			$totalGeral += $value['Despesa']['valor'];
		}
		$despesas = $this->Paginator->paginate('Despesa');
		
		//debug($despesas);
		//die;
		$this->loadModel('Tiposrecorrencia');
		$tiposrecorrencia = $this->Tiposrecorrencia->find('list', array(
			'recursive' => -1,

			'order' => 'tipo asc'
		));

		
		array_unshift($tiposrecorrencia, 'Selecione');


		$despesas = $this->Paginator->paginate('Despesa');

		$this->request->data['filter']['dataPedido'] = date("d/m/Y", strtotime($dataIncio));
		$this->request->data['filter']['dataPedido-between'] = date("d/m/Y", strtotime($dataTermino));


		$this->set(compact('despesas', 'categoriasdespesa', 'tiposrecorrencia', 'totalEmAberto', 'totalPago', 'totalGeral'));
		
		if ($this->request->is('post')) {
			
			
			$this->request->data['Despesa']['empresa_id'] = $this->Session->read('Auth.User.empresa_id');
			$this->request->data['Despesa']['filial_id'] = $unicaFilial['Filial']['filial_id'];
			$autTipo = 'funcoes';
			$userid = $this->Session->read('Auth.User.id');
			$userdespesa = $this->Session->read('Auth.User.funcao_id');
			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_vencimento']);
			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_recorrente_processar']);
			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_prox_vencimento']);
			$this->checkbfunc->converterMoedaToBD($this->request->data['Despesa']['valor']);

			if($this->request->data['Despesa']['data_prox_vencimento'] != ''){
				if($this->request->data['Despesa']['data_prox_vencimento'] < $this->request->data['Despesa']['data_recorrente_processar'] ){
				
					$this->Session->setFlash(__('Data de próximo processamento inválida! A data de próxmo criação tem que ser menor que a data do próximo vencimento.'), 'default', array('class' => 'error-flash alert alert-danger'));
					return $this->redirect($this->referer());
				}
			}

			if (!$Autorizacao->setAutoIncuir($autTipo, $userdespesa)) {
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
			$this->Despesa->create();
			if ($this->Despesa->saveAll($this->request->data)) {
				$this->Session->setFlash(__('A despesa foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a despesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect(array('action' => 'add'));
			}
		} else {
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{
		$this->layout = 'liso';
		if (!$this->Despesa->exists($id)) {
			throw new NotFoundException(__('Invalid despesa'));
		}
		$Autorizacao = new AutorizacaosController;
		$autTipo = 'funcoes';
		$userid = $this->Session->read('Auth.User.id');
		$userdespesa = $this->Session->read('Auth.User.funcao_id');


		$userdespesa = $this->Session->read('Auth.User.funcao_id');
		$User = new UsersController;
		$minhasFiliais = $User->getFiliais($userid);
		$this->loadModel('Filial');
		$unicaFilial = $this->Filial->find('first', array('recursive' => -1, 'conditions' => array('Filial.id' => $minhasFiliais)));


		if (!$Autorizacao->setAutorizacao($autTipo, $userdespesa)) {
			$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
			return $this->redirect($this->referer());
		}
		if ($this->request->is(array('post', 'put'))) {
			$autTipo = 'funcoes';
			$userid = $this->Session->read('Auth.User.id');
			$userdespesa = $this->Session->read('Auth.User.funcao_id');

			if (!$Autorizacao->setAutoIncuir($autTipo, $userdespesa)) {
				$this->Session->setFlash(__('Acesso Negado!'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
			//$this->request->data['Despesa']['empresa_id'] =  $this->Session->read('Auth.User.empresa_id');

			//$this->request->data['Despesa']['filial_id'] =  $this->Session->read('Auth.User.filial_id');


			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_vencimento']);
			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_recorrente_processar']);
			$this->checkbfunc->formatDateToBD($this->request->data['Despesa']['data_prox_vencimento']);
			$this->checkbfunc->converterMoedaToBD($this->request->data['Despesa']['valor']);
			//$this->Despesa->create();
			//debug($this->request->data);
			//die;

			if ($this->Despesa->saveAll($this->request->data)) {
				$this->Session->setFlash(__('A Conta  foi salva com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
				return $this->redirect($this->referer());
			} else {
				$this->Session->setFlash(__('Houve um erro ao salvar a conta. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
				return $this->redirect($this->referer());
			}
		} else {
			$options = array('conditions' => array('Despesa.' . $this->Despesa->primaryKey => $id));
			$this->request->data = $this->Despesa->find('first', $options);

			//debug($this->request->data);

			$this->checkbfunc->formatDateToView($this->request->data['Despesa']['data_vencimento']);
			$this->checkbfunc->formatDateToView($this->request->data['Despesa']['data_recorrente_processar']);
			$this->checkbfunc->formatDateToView($this->request->data['Despesa']['data_prox_vencimento']);
			//$this->checkbfunc->converteMoedaToView($this->request->data['Despesa']['valor']);
			$this->request->data['Despesa']['valor'] = number_format($this->request->data['Despesa']['valor'], 2, ',', '');

			$this->loadModel('Categoriasdespesa');
			$categoriasdespesa = $this->Categoriasdespesa->find('list', array(
				'recursive' => -1,
				'conditions' => array(
					'filial_id' => $minhasFiliais,
					'ativo'=> 1
				),
				'order' => 'categoria asc'
			));

			$this->loadModel('Tiposrecorrencia');
			$tiposrecorrencia = $this->Tiposrecorrencia->find('list', array(
				'recursive' => -1,

				'order' => 'tipo asc'
			));

			array_unshift($categoriasdespesa, 'Selecione');
			array_unshift($tiposrecorrencia, 'Selecione');
			$this->set(compact('categoriasdespesa', 'tiposrecorrencia'));
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->Despesa->id = $id;
		if (!$this->Despesa->exists()) {
			throw new NotFoundException(__('Invalid despesa'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Despesa->delete()) {
			$this->Session->setFlash(__('A despesa foi removida com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a despesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'add'));
	}
	/**
	 * confirmarpagamento method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function confirmarpagamento($id = null)
	{
		$this->Despesa->id = $id;
		if (!$this->Despesa->exists()) {
			throw new NotFoundException(__('Invalid despesa'));
		}
		$this->request->onlyAllow('post');
		$this->Despesa->id = $id;
		
		if ($this->Despesa->saveField('status_pago', 1)) {
			$this->Session->setFlash(__('A situação da despesa foi alterada com sucesso.'), 'default', array('class' => 'success-flash alert alert-success'));
		} else {
			$this->Session->setFlash(__('Houve um erro ao remover a despesa. Por favor tente novamente'), 'default', array('class' => 'error-flash alert alert-danger'));
		}
		return $this->redirect(array('action' => 'add'));
	}
}
