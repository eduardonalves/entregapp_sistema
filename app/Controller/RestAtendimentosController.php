<?php
App::uses('AppController', 'Controller');
App::import('Controller', 'Empresas');
App::import('Controller', 'Pedidos');
class RestAtendimentosController extends AppController {
    public $uses = array('Atendimento');
    public $helpers = array('Html', 'Form');
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

	public function checkTokenEntregador(&$clienteId,&$token){
		$this->loadModel('Entregador');
		$cliente = $this->Entregador->find('first', array('conditions' => array('AND' => array(array('Entregador.id' => $clienteId), array('Entregador.token' => $token), array('Entregador.ativo' => 1)))));

		if(!empty($cliente)){
			$resposta = "OK";
			return $resposta;
		}else{
			$resposta = "NOK";
			return $resposta;
			$clienteUp= array('id'=> $clienteId, 'ativo' => 0);
			$this->Entregador->create();
			$this->Entregador->save($clienteUp);
		}

	}
	function converteparasegundos($hora) {

		$horas = substr($hora, 0, -6);
		$minutos = substr($hora, -5, 2);
		$segundos = substr($hora, -2);

		return $horas* 3600 + $minutos * 60 + $segundos;
	}

	function converterparahoras($segundos){

		$horas = floor($segundos / 3600);
		$segundos -= $horas* 3600;
		$minutos = floor($segundos / 60);
		$segundos -= $minutos * 60;

		return "$horas:$minutos:$segundos";

	}
	public function campainhamobile($id = null) {
		$this->layout='liso';
		$id = $_GET['a'];
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$resp="";
		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$origem = $_GET['entr'];
		//
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}

		if(isset($_GET['entr'])){
			if($_GET['entr']==1){
				$resp =$this->checkTokenEntregador($clt, $token);
			}else{
				$resp =$this->checkToken($clt, $token);
			}
		}else{
			$resp =$this->checkToken($clt, $token);
		}

		if($resp =='OK'){
			$atendimento = $this->Atendimento->find('first',array('recursive' => -1, 'conditions' => array('Atendimento.id' => $id)));
			if(!empty($atendimento )){
				$contadorCampainha = $atendimento['Atendimento']['campainha'] + 1;
			}else{
				$contadorCampainha=3;
			}

			if($contadorCampainha <= 3){
				if($origem==2){
					$contadorCampainha = 10;
				}else{

					if($atendimento['Atendimento']['campainha'] == 10){
						$contadorCampainha = 11;
					}else{

					}
				}

				$update = array('id' => $id, 'campainha' => $contadorCampainha);
				$this->Atendimento->create();
				$this->Atendimento->save($update);
				$resultados="OK";
			}else{

				if($atendimento['Atendimento']['campainha'] == 10){
					$atendimento = $this->Atendimento->find('first',array('recursive' => -1, 'conditions' => array('Atendimento.id' => $id)));
						$contadorCampainha = 11;
						$update = array('id' => $id, 'campainha' => $contadorCampainha);
						$this->Atendimento->create();
						$this->Atendimento->save($update);
						$resultados="OK";
				}else{
					$resultados="NOK";
				}

			}

		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));

	}

	public function getsituacaocampainha($id = null) {
		$this->layout='liso';

		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$resp="";
		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$origem = $_GET['entr'];
		//
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}

		if(isset($_GET['entr'])){
			if($_GET['entr']==1){
				$resp =$this->checkTokenEntregador($clt, $token);
			}else{
				$resp =$this->checkToken($clt, $token);
			}
		}else{
			$resp =$this->checkToken($clt, $token);
		}

		$resultados="";
		if($resp =='OK'){
			if($_GET['entr'] ==2){
				$this->loadModel('Pedido');
				$pedidos =$this->Pedido->find('all', array('recursive' => -1, 'conditions' => array('AND' => array(array('Pedido.status' => 'Em Trânsito'), array('Pedido.cliente_id' => $clt)))));
				foreach ($pedidos as  $pedido) {
					$atendimento = $this->Atendimento->find('first',array('recursive' => -1, 'conditions' => array('AND' => array(array('Atendimento.id' => $pedido['Pedido']['atendimento_id']), array('Atendimento.campainha !=' => 10), array('Atendimento.campainha !=' => 11), array('Atendimento.campainha !=' => null)) )));
					if(!empty($atendimento)){
						$resultados=$atendimento;
					}
				}
				if(empty($resultados)){
					$resultados="vazio";
				}

			}else{
				$atendimento = $this->Atendimento->find('first',array('recursive' => -1, 'conditions' =>array('AND' => array(array('Atendimento.entregador_id' => $clt), array('Atendimento.campainha' => 10), array('Atendimento.status' => 'Em Trânsito')))));
			}

			if(!empty($atendimento)){
				$resultados=$atendimento;
			}else{
				$resultados="vazio";
			}


		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));

	}

	public function confirmaentregamobile($id = null) {
		$this->layout='liso';
		$id = $_GET['a'];
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$resp="";
		$clt =  $_GET['b'];
		$token = $_GET['c'];
		//
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}

		$resp =$this->checkTokenEntregador($clt, $token);
		$resultados="";
		if($resp =='OK'){

			$atendimento = $this->Atendimento->find('first', array('recursive' => -1, 'conditions' => array('Atendimento.id' => $id)));
			if($atendimento['Atendimento']['status'] == 'Em Trânsito'){
				$updateAtendimento = array('id' => $id, 'status' => 'Entregue');
				$this->Atendimento->create();
				$this->Atendimento->save($updateAtendimento);
				$this->loadModel('Pedido');
				$pedido = $this->Pedido->find('first', array('recursive' => -1, 'conditions' => array('Pedido.atendimento_id' => $id)));
				$updatePedido = array('id' => $pedido['Pedido']['id'], 'status' => 'Entregue', 'statuspreparo' => 0, 'posicaofila' => 0);
				$Pedido = new PedidosController;
				$Pedido->reordenafila();
				$this->loadModel('Roteiro');
				$pedidoRoteiro = $this->Roteiro->find('first', array('recursive' => -1,'conditions' => array('Roteiro.pedido_id' => $pedido['Pedido']['id'])));
				$this->Roteiro->create();
				$updateRot= array('id' => $pedidoRoteiro['Roteiro']['pedido_id'], 'status' => 'Entregue');
				$this->Roteiro->save($updateRot);
				$resultados="OK";
			}else{
				$resultados="NOK";
			}



		}else{
			$resultados="NOK";

		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));

	}

	public function viewmobile($id = null) {
		$this->layout='liso';

		$id = $_GET['a'];
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$resp="";
		$clt =  $_GET['b'];
		$token = $_GET['c'];

		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}


		/*if(isset($_GET['entr'])){
			if($_GET['entr']==1){
				$resp =$this->checkTokenEntregador($clt, $token);
			}else{
				$resp =$this->checkToken($clt, $token);
			}
		}else{
			$resp =$this->checkToken($clt, $token);
		}*/

		
		$resp ='OK';
		if($resp =='OK'){
			
			$this->loadModel('Pedido');
			$this->loadModel('Itensdepedido');
			$this->loadModel('Atendimento');

			$atendimentoAux = $this->Atendimento->find('first', array('conditions' => array('Atendimento.id' => $id)));


			$id= $atendimentoAux['Atendimento']['id'];

			$codigo = $atendimentoAux['Atendimento']['codigo'];

			$pedido = $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));


			$this->loadModel('Pagamento');
			$pagamento = $this->Pagamento->find('first', array('recursive' => -1, 'conditions' => array('Pagamento.id' => $pedido['Pedido']['pagamento_id'])));

			$this->loadModel('Produto');
				$this->loadModel('Entregador');
			$entregadores = $this->Entregador->find('all', array('recursive' => -1));


			$contFlag=1;
			$horaAtual = date("H:i:s");
			$difHora="00:00:00";
			$horaAtendimento = $pedido['Pedido']['hora_atendimento'];

			//$tempoTotalFila = $this->calculaFilaProduto($pedido['Pedido']['id'] );
			$this->loadModel('Filial');
			$estaFilial= $this->Filial->find('first', array('recursive'=>-1, 'conditions'=> array('Filial.id'=> $_GET['fp'])));
			$tempoFila = $estaFilial['Filial']['tempo_atendimento'];
			$tempoTotalFila=$tempoFila;

			$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);




			if($horaAtual > $horaAtendimento){



				if($horaAtual < $esperaHora){

					$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);
					//$difHora=date('H:i:s', $difHora);
					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);


				}else{

					if($esperaHora > '00:00:00' && $esperaHora < '06:00:00'){


						$horaAux1="23:59:59";
						$horazero="00:00:01";
						$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
						$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
						$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

						$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);

					}else{

						$pedido['Pedido']['difhora']='00:00:00';
					}



				}
			}else{

				if($horaAtual < $esperaHora){
					$horaAux1="23:59:59";
					$horazero="00:00:01";
					$horaAux2=$this->checkbfunc->subtraHora($horaAux1,$horaAtual);
					$horaAux3= $this->checkbfunc->subtraHora($esperaHora,$horazero);
					$difHora= $this->checkbfunc->somaHora($horaAux2, $horaAux3);

					$pedido['Pedido']['difhora']=$this->checkbfunc->somaHora($difHora, $contadorTempo);
				}else{
					$pedido['Pedido']['difhora']='00:00:00';
				}
			}

			if($pedido['Pedido']['statuspreparo']!=1){
				$pedido['Pedido']['difhora']="00:00:00";
			}
			$posicaofila= $this->Pedido->find('count', array('recursive' => -1,'conditions' => array('AND' => array(array('Pedido.statuspreparo' => 1), array('Pedido.id <' => $pedido['Pedido']['id'] ), array('Pedido.filial_id' =>  $_GET['fp'])))));

			//$this->Pedido->saveField('posicao_fila', $posicaofila);
			$pedido['Pedido']['posicao_fila']= $posicaofila;







			$dif= array('id' => $id, 'difhora' =>$pedido['Pedido']['difhora'] );
			$this->Atendimento->create();
			$this->Atendimento->save($dif);

			if(!empty($pedido)){

				$itensdepedidos = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));

			}

			if (!$this->Atendimento->exists($id)) {
				throw new NotFoundException(__('Invalid atendimento'));
			}

			$atendimentos = $this->Atendimento->find('first', array('conditions' => array('Atendimento.id' => $id)));

			$resultados=$atendimentos;

			$resultados['Atendimento']['difhora']=$difHora;
			$resultados['Itensdepedido'] = $itensdepedidos ;
			
			
			if(isset($pagamento['Pagamento']['tipo'])){
				$resultados['Atendimento']['formadepagamento']=$pagamento['Pagamento']['tipo'];
			}
			if($resultados['Atendimento']['data'] != ''){
				$resultados['Atendimento']['data']= $this->checkbfunc->formatDateToView($resultados['Atendimento']['data']);
			}
			

		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
  	  }

  	public function posentregador($id = null) {
		$this->layout='liso';
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$resp="";
		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$lat = $_GET['lat'];
		$lng =  $_GET['lng'];
		//
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}

		$resp =$this->checkTokenEntregador($clt, $token);

		$resultados="NOK";

		if($resp =='OK'){
			$this->loadModel('Pedido');
			$this->loadModel('Entregador');
			$this->loadModel('Atendimento');
			$pedidos = $this->Pedido->find('all', array('recursive' => -1, 'conditions' => array('AND' => array(array('Pedido.entregador_id' => $clt), array('Pedido.status' => 'Em Trânsito')))));
			foreach ($pedidos as $pedido) {
				if($pedido['Pedido']['entregador_id'] !='' && $pedido['Pedido']['entregador_id'] != null){
					$updatePos = array('id' => $pedido['Pedido']['entregador_id'], 'lat' => $lat, 'lng' => $lng);
					$this->Entregador->create();
					$this->Entregador->save($updatePos);
				}

				if($pedido['Pedido']['atendimento_id'] !='' && $pedido['Pedido']['atendimento_id'] != null){
					$updatePos = array('id' => $pedido['Pedido']['atendimento_id'], 'lat' => $lat, 'lng' => $lng);
					$this->Atendimento->create();
					$this->Atendimento->save($updatePos);
				}
				$resultados="OK";
			}


		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
	}
 	 public function calculaFilaProduto(&$id){
		$this->loadModel('Produto');
		$this->loadModel('Itensdepedido');
		$this->Itensdepedido->virtualFields = array('totalprod' => 'SUM(Itensdepedido.qtde)');
		$produtos= $this->Produto->find('all', array('recursive' => -1));
		$i=0;
		$produtoFila= array();
		$tempoTotalPreparo = "00:00:00";
		foreach ($produtos as $produto) {

			$qteFilaProduto = $this->Itensdepedido->find('all', array('recursive'=> -1,'conditions' => array('AND' => array(array('Itensdepedido.produto_id'=> $produto['Produto']['id']), array('Itensdepedido.statuspreparo'=> 1), array('Itensdepedido.pedido_id <='=> $id))) ));

			if(!empty($qteFilaProduto)){

				if(isset($qteFilaProduto[0]['Itensdepedido']['totalprod'])){

					if($qteFilaProduto[0]['Itensdepedido']['totalprod']== null){
						unset($qteFilaProduto[$i]);
					}else{
						$produtoAux = array(
							'produto_id' => $produto['Produto']['id'],
							'qtde_fila' => $qteFilaProduto[0]['Itensdepedido']['totalprod'],
							'tempo_preparo' => $produto['Produto']['tempo_preparo'],
							'qtde_preparo' => $produto['Produto']['qtde_preparo'],

						);

						$tempoPreparo=$produto['Produto']['tempo_preparo'];
						$qtdePreparo= round($produto['Produto']['qtde_preparo']);
						if($qtdePreparo  != null){
							$qteFila = round($qteFilaProduto[0]['Itensdepedido']['totalprod']);
							$modQtde = ($qteFila % $qtdePreparo);
							while ( $modQtde != 0) {
								$qteFila++;
								$modQtde = ($qteFila % $qtdePreparo);
							}
							$tempoNescessario = '?';
							$acumuladorTempo='00:00:00';
							for ($i=0; $i < $qteFila; $i++) {
								$acumuladorTempo = $this->checkbfunc->somaHora($acumuladorTempo, $tempoPreparo);
							}

							$segundosTotais = $this->converteparasegundos($acumuladorTempo);

							$segundosTotais = $segundosTotais / $qtdePreparo;
							$tempoTotal = gmdate("H:i:s", $segundosTotais);

							$tempoTotalPreparo = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoTotal);
						}

					}

				}

			}

			$i++;
		}

		if(isset($tempoTotalPreparo)){
				//Busco a duração do trajeto do endereço do cliente
				$this->loadModel('Cliente');
				$this->loadModel('Pedido');
				$pedidoFunc= $this->Pedido->find('first', array('recursive' => -1, 'conditions' => array('Pedido.id' => $id)));
				$cliente = $this->Cliente->find('first', array('recursive'=> -1, 'conditions' => array('Cliente.id' => $pedidoFunc['Pedido']['cliente_id'])));

				$duracao = $cliente['Cliente']['duracao'];

				//Trato a string recebida para o formato de horas e minutos
				$horasAux = explode('horas', $cliente['Cliente']['duracao']);

				if(isset($horasAux[1])){
					$horas=str_replace(" ", "", $horasAux[0]);
				}else{
					$horasAux = explode('hora', $cliente['Cliente']['duracao']);
					if(isset($horasAux[1])){
						$horas=str_replace(" ", "", $horasAux[0]);
					}
				}
				if(isset($horas)){
					$tamanhoString=strlen($horas);
					if($tamanhoString == 1){
						$horas= '0'.$horas;
					}
				}else{
					$horas='00';
				}

				$minutosAux = explode('minutos', $cliente['Cliente']['duracao']);

				if(isset($minutosAux[1])){
					$minutos=str_replace(" ", "", $minutosAux[0]);
				}

				if(isset($minutos)){
					$tamanhoString=strlen($minutos);
					if($tamanhoString == 1){
						$minutos= '0'.$minutos;
					}
				}else{
					$minutos='00';
				}

				$segundos='00';



				$tempoEntregaCli = $horas.":".$minutos.":".$segundos;

				$meusItens = $this->Itensdepedido->find('all', array('recursive' => -1, 'conditions' => array('Itensdepedido.pedido_id' => $id)));

			/*	$maiorTempoPreparo='00:00:00';

				foreach ($meusItens as $meuItem) {
					$meuProduto = $this->Produto->find('first', array('recursive' => -1, 'conditions' => array('Produto.id' => $meuItem['Itensdepedido']['produto_id'])));
					if($maiorTempoPreparo < $meuProduto['Produto']['tempo_preparo']){
						$maiorTempoPreparo = $meuProduto['Produto']['tempo_preparo'];

					}

				}

				if($maiorTempoPreparo > $tempoTotalPreparo){
					$tempoTotalPreparo= $maiorTempoPreparo;

				}*/
				$tempoVisualizacao="00:01:00";
				$tempoTotalPreparoAux = $this->checkbfunc->somaHora($tempoTotalPreparo, $tempoEntregaCli);
				$tempoTotalPreparoAux2 = $this->checkbfunc->somaHora($tempoTotalPreparoAux, $tempoVisualizacao);
				$tempoTotalPreparo = $tempoTotalPreparoAux2;




			return $tempoTotalPreparo;
		}else{
			return false;
		}


	}
	public function indexmobile () {
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");

		$cliente= $_GET['clt'];
		$limite = $_GET['limit'];
		$token =  $_GET['token'];
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}
		$resultados="";

		if(isset($_GET['entr'])){
			if($_GET['entr']==1){

				$resp =$this->checkTokenEntregador($cliente, $token);
			}else{
				$resp =$this->checkToken($cliente, $token);
			}
		}else{
			$resp =$this->checkToken($cliente, $token);
			//$resp ='OK';
		}
		if($resp=='NOK'){
			//$resultados= "falha:19";
			//@TODO - implementar um tratamento de erro quando o token for invalido
			$resultados= "";
		}else{
			$resultados= "";
		}
		

		if($resp =='OK'){

			if(isset($_GET['entr'])){
				if( $_GET['entr']==1){
					$resultados = $this->Atendimento->find('all', array('recursive' => 1, 'order' => 'Atendimento.ordem ASC, Atendimento.status ASC','conditions' => array('AND' => array(array('Atendimento.entregador_id' => $cliente), array('Atendimento.empresa_id' => $_GET['lj'])), array('OR' => array(array('Atendimento.status' => 'Separado'), array('Atendimento.status' => 'Em Trânsito'))))));
					$this->loadModel('Cliente');
					$i =0;
					foreach ($resultados as $resultado) {
						$cliente = $this->Cliente->find('first', array('recursive' => -1, 'conditions' => array('Cliente.id' => $resultado['Atendimento']['cliente_id']) ));
						if(! empty($cliente)){
							$resultados[$i]['Atendimento']['endereco'] = $cliente['Cliente']['nome'].' - '.$cliente['Cliente']['logradouro'].' '.$cliente['Cliente']['complemento'].' '.$cliente['Cliente']['numero'].' ,'.$cliente['Cliente']['bairro'].' - '.$cliente['Cliente']['cidade'];
						}
						$i++;
					}


				}

			}else{

				$resultados = $this->Atendimento->find('all', array('recursive' => 1,'limit' => $limite, 'order' => 'Atendimento.id DESC',
					'conditions' => array(
						'Atendimento.filial_id' => $_GET['fp'],
						'Atendimento.cliente_id' => $cliente,
						'tipo'=>'EXTERNO'
						)));

			}

			$i=0;
			foreach($resultados as $i => $resultado ){
				$this->checkbfunc->formatDateToView($resultados[$i]['Atendimento']['data']);
				$i++;
			}


		}
		$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
	}

	public function itensmobile($id = null) {

		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$id= $_GET['a'];
		$this->layout ='loadprodutos';
		$this->loadModel('Pedido');
		$this->loadModel('Itensdepedido');
		$clt =  $_GET['b'];
		$token = $_GET['c'];

		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}
		if(isset($_GET['entr'])){
			if($_GET['entr']==1){
				$resp =$this->checkTokenEntregador($clt, $token);
			}else{
				$resp =$this->checkToken($clt, $token);
			}
		}else{
			$resp =$this->checkToken($clt, $token);
		}
		$resultados="";
		if($resp =='OK'){
			if (!$this->Atendimento->exists($id)) {
				throw new NotFoundException(__('Invalid atendimento'));
			}
			$atendimentoAux = $this->Atendimento->find('first', array('recursive' => 1,'conditions' => array('Atendimento.id' => $id)));

			$id= $atendimentoAux['Atendimento']['id'];

			$codigo = $atendimentoAux['Atendimento']['codigo'];

			$pedido = $this->Pedido->find('first', array('recursive' => 1,'conditions' => array('Pedido.atendimento_id' => $atendimentoAux['Atendimento']['id'])));



			if(!empty($pedido)){


				if(!empty($pedido)){
					$resultados = $this->Itensdepedido->find('all', array('conditions' => array('Itensdepedido.pedido_id' => $pedido['Pedido']['id'])));
					if(empty($resultados)){
						$resultados="vazio";
					}

				}else{
					$resultados="vazio";
				}


				$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
				));

			}else{
				$resultados="vazio";
			}

		}

		$this->set(array(
		'resultados' => $resultados,
		'_serialize' => array('resultados')
		));


	}
	public function checaatendimento($id = null) {
		date_default_timezone_set("Brazil/East");
		header("Access-Control-Allow-Origin: *"); header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
		$id= $_GET['a'];
		$resultados= array('resposta' => 'naoExiste');
		$clt =  $_GET['b'];
		$token = $_GET['c'];
		$resp =$this->checkToken($clt, $token);
		$Empresa = new EmpresasController;
		$respAux = $Empresa->empresaAtiva();

		if($respAux == 1){

		}else{
			$resp='NOK';
		}
		if($resp =='OK'){

			$atendimentoAux = $this->Atendimento->find('first', array('recursive' => -1,'conditions' => array('Atendimento.codigo' => $id, 'AND' => array('Atendimento.ativo' => 1), 'AND' => array('Atendimento.tipo' => 'INTERNO') )));

			if(!empty($atendimentoAux)){
				$resultados =  array('resposta' => 'Existe');

			}else{
				$resultados= array('resposta' => 'naoExiste');
			}
			$this->set(array(
				'resultados' => $resultados,
				'_serialize' => array('resultados')
			));
		}
	}
}