<div class="col-md-12">
	<!-- general form elements -->
	<div class="card card-dark skin-midnight-aux-header">
		<div class="card-header skin-midnight-aux-header ">
			<h3 class="card-title">Pesquisa de Pedidos</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<?php
		echo $this->Search->create('Pedidos', array('role' => 'form', 'class' => ''));
		?>
		<div class="card-body skin-midnight-aux">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<?php
						echo $this->Search->input('codigo', array('label' => 'Código:', 'class' => 'form-control skin-midnight-aux-input-select', 'required' => 'false'));
						?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<?php
						echo $this->Search->input('nome', array('label' => 'Nome:', 'class' => 'filtroPedido form-control skin-midnight-aux-input-select'));
						?>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label>Data Início: </label>
						<?php

						echo $this->Search->input('dataPedido', array('id' => 'dataPedido', 'label' => false, 'class' => 'filtroPedido data custom-select skin-midnight-aux-input-select'));

						?>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<label>Data Fim: </label>
						<?php

						echo $this->Search->input('dataPedido-between', array('id' => 'dataPedido', 'label' => false, 'class' => 'filtroPedido data custom-select skin-midnight-aux-input-select'));

						?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<label for="">Status:</label>
						<?php
						echo $this->Search->input('status', array('label' => false, 'class' => 'filtroPedido custom-select skin-midnight-aux-input-select', 'required' => 'false'));
						?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label>Sem Status: </label>
						<?php
						echo $this->Search->input('statusnot', array('label' => false, 'class' => 'filtroPedido custom-select skin-midnight-aux-input-select', 'required' => 'false'));

						?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<?php
						echo $this->Search->input('novospedidos', array('label' => 'Novos Pedidos?', 'class' => 'filtroPedido custom-select skin-midnight-aux-input-select', 'required' => 'false'));
						?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<?php

						echo $this->Search->input('minhaslojas', array('label' => 'Loja:', 'class' => 'filtroPedido custom-select skin-midnight-aux-input-select', 'required' => 'false'));
						?>
					</div>		
				</div>
			</div>



			
			
			
			<?php
			echo $this->Search->input('empresa', array('label' => false, 'style' => 'display:none;', 'required' => 'false'));
			?>

		</div>
		<!-- /.card-body -->

		<div class="card-footer">
			
			<input type="submit" value="Pesquisar" class="btn btn-primary orange-st filtrar float-right">
			
			<?php
			if ($autorizacao['Autorizacao']['pedidos'] != 'n' && $autorizacao['Autorizacao']['pedidos'] != 'v') {
			?>
				
			<button type="button" class="btn btn-success orange-st addpedido" id="addpedido">Novo Pedido</button>
				
			<?php
			}
			?>
			<?php
			//echo $this->Search->end(__('Pesquisar', true));
			?>
		</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		var urlInicio = window.location.host;
		if (urlInicio == "localhost") {
			urlInicio = "localhost/entregapp_sistema";
		}
		$('body').on('click', '#titulo-mensagem', function(event) {
			loja = $('#filterMinhaslojas').val();
			$("#loadAtivas").load('http://' + urlInicio + '/Pedidos/mensagensativas/?loja=' + loja, function() {});
			$("#mensagens").toggle('slow');
		});

	});
</script>
<style>
	table {
		text-align: center;
	}

	#titulo-mensagem2 {
		display: block;
		width: 250px;
		height: 30px;
		border-bottom: 1px solid #ccc;
	}

	#titulo-mensagem2 .titulo {
		color: #000000;
		font-weight: bold;
	}

	#titulo-mensagem2 span {
		float: left;
	}

	form div.submit {
		margin-top: 27px;
	}
</style>




<style>
	#divLoader {
		position: absolute;
		height: 26px;
		width: 70px;
		background: rgba(0, 0, 0, 0.87);
		margin: -10% 45%;
		border-radius: 7px;
	}

	#divLoader img {
		width: 50px;
		margin: 0 auto;
		display: block;
		margin-top: 6px;
	}

	#pedidosNovos-qtd {
		color: #FFF;
		background: #FF9406;
		width: 20px;
		height: 20px;
		line-height: 20px;
		font-size: 15px;
		text-align: center;
		border-radius: 5px;
		display: block;
		margin-left: 5px;
		margin-top: -1px;
	}

	.audioPlayer {
		display: none;
	}

	.center-text {
		text-align: center;
	}

	.th-red {
		background-color: #7f7f7f !important;
	}
</style>

<div id="divLoader" style="display:none;">
	<?php echo $this->Html->image('ajax-loader.gif', array('id' => 'loaderGif', 'class' => ' loaderPedido', 'alt' => 'Loader', 'title' => 'Loader')); ?>
</div>
<section id="loadList">

</section>


<div class="row">
	<div class="col-sm-12 col-md-5">
		<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
			
			<?php
				echo $this->Paginator->counter(array(
					'format' => __('Página {:page} de {:pages}, Visualisando {:current} de {:count} resultados.')
				));
				echo "<br>";
				echo $this->Paginator->counter(array(
					'format' => __('Inicio no registro {:start}, fim no {:end}.')
				));
			?>
		</div>
	</div>
	<div class="col-sm-12 col-md-7">
		<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
			<ul class="pagination">
				<?php
					echo $this->Paginator->prev('< ' . __('Anterior'), array('class'=> 'paginate_button page-item previous disabled'), null, array('class' => 'paginate_button page-item previous disabled'));
					echo $this->Paginator->numbers(array('separator' => ''));
					echo $this->Paginator->next(__('Próxima') . ' >', array(), null, array('class' => 'paginate_button page-item next disabled'));
				?>
				<!--<li class="paginate_button page-item previous disabled" id="example1_previous"><a href="#" aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li>
				<li class="paginate_button page-item active"><a href="#" aria-controls="example1" data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
				<li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
				<li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
				<li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
				<li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="5" tabindex="0" class="page-link">5</a></li>
				<li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="6" tabindex="0" class="page-link">6</a></li>
				<li class="paginate_button page-item next" id="example1_next"><a href="#" aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
			--></ul>
		</div>
	</div>
</div>




<div id="responsiveModal2" class="modal hide fade" tabindex="-1">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3>Pedido</h3>
	</div>
	<div class="modal-body" id="modalEntregador">
		<div class="row-fluid">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn">Fechar</button>
	</div>
</div>
<div class="modal fade modal-grande" id="modalpagamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> Informe o pagamento</h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<label>Valor total:</label>
					<label>Informação de pagamento</label>
					<select>
						<option value="pago">Pago</option>
						<option value="Pendente">Pendente</option>
					</select>
					<label>Informação Forma de pagamento</label>
					<select>
						<option value="Dinheiro">Dinheiro</option>
						<option value="Cartão de Crédito Master">Cartão de Crédito Master</option>
						<option value="Cartão de Crédito Visa">Cartão de Crédito Visa</option>
						<option value="Cartão de Débito Master">Cartão de Débito Master</option>
						<option value="Cartão de Débito Visa">Cartão de Débito Visa</option>
					</select>
					<label>Valor Pago</label>
					<input type="text">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-default">Salvar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade modal-grande" id="modalCancelar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> Cancelar</h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<div id="loadCancelamento">
						<form id="formCancelar">
							<input type="hidden" name="data[Pedido][id]" id="cancelId" />
							<div class="form-group">
								<label>Motivo:</label>
								<textarea class="form-control" rows="5" name="data[Pedido][motivocancela]" id="motivoCancel"></textarea>
							</div>

						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-default" id="cancelarPedido">Salvar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-grande" id="modalHabilitaAudio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">

				<h4 class="modal-title" id="myModalLabel"> Habilitar Audio</h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<div id="loadCancelamento">
						<form id="formCancelar">
							<input type="hidden" name="data[Pedido][id]" id="cancelId" />
							<div class="form-group">
								<p>Por favor, clique no botão habilitar, para ligar o audio dos pedidos novos!</p>

							</div>

						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Habilitar</button>

			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-grande" id="modalChat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> Chat</h4>
			</div>
			<div class="modal-body" style="max-height: 400px; overflow-y: scroll;">
				<div class="row-fluid">
					<div id="modalChatcontent">

					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form id="chat">
					<input type="hidden" name="data[Mensagen][pedido_id]" id="idpedidomsg" />
					<input type="hidden" name="data[Mensagen][filial_id]" id="idfilialmsg" />
					<input type="hidden" name="data[Mensagen][cliente_id]" id="cliente_idmsg" value=<?php echo $cliente_idAux;; ?> />
					<label for="comment">Mensagem:</label>
					<textarea rows="5" id="enviarinpt" name="data[Mensagen][msg]" style="height: 50px !important;"></textarea>
					<button type="submit" class="btn" id="enviarmsg">Enviar</button>
					<button type="button" data-dismiss="modal" class="btn" id="fecharChat">Fechar</button>
				</form>
			</div>

		</div>
	</div>
</div>

<div id="loadModalPedido">
</div>
<?php if ($_SERVER['HTTP_HOST'] == 'localhost') { ?>
	<audio controls class="audioPlayer">
		<source src="/entregapp_sistema/sons/cymbalsSymphony.wav" type="audio/wav">
	</audio>
<?php } else { ?>
	<audio controls class="audioPlayer">
		<source src="/sons/cymbalsSymphony.wav" type="audio/wav">
	</audio>
<?php }  ?>
<script type="text/javascript">
	$(document).ready(function() {
		//var urlInicio      = window.location.host;
		//alert("The URL of this page is: " + window.location.href);
		//alert("The URL of this page is: " + window.location.host);
		loja = $('#filterMinhaslojas').val();
		$('#idfilialmsg').val(loja);
		var urlInicio = window.location.host;
		urlInicio = (urlInicio == 'localhost' ? urlInicio + '/entregapp_sistema' : urlInicio);

		urlToList = window.location.href;

		urlToList = urlToList.replace("index", "listarpedidos");
		//alert(urlToList);
		$("#loadList").load(urlToList, function() {});
		//alert(urlToList);

		urlAudio = 'http://' + urlInicio + '/sons/cymbalsSymphony.wav';



		var audio = new Audio(urlAudio);



		$("#pedidosNovos-qtd").load('http://' + urlInicio + '/Pedidos/countpedidosnovos/?loja=' + loja, function() {});
		setTimeout(function() {
			nPedidos = $('#pedidosNovos-qtd').text();
			nPedidos.replace(' ', '').trim();

			if (nPedidos != 0) {
				//$('.audioPlayer').trigger('play');
				//console.log('tocou 1');
				audio.play().then(function() {
					/*setTimeout(function() {
						audio.play();
					}, 2000);
					setTimeout(function() {
						audio.play();
					}, 4000);
					setTimeout(function() {
						audio.play();
					}, 6000);
					setTimeout(function() {
						audio.play();
					}, 8000);*/
				}).catch(function() {
					$('#modalHabilitaAudio').modal('show');

				});

			}
		}, 2000);
		setInterval(function() {
			//recarrega a lista de pedidos de tempos em tempos
			$("#loadList").load(urlToList, function() {});

			$("#pedidosNovos-qtd").load('http://' + urlInicio + '/Pedidos/countpedidosnovos/?loja=' + loja, function() {});
			setTimeout(function() {
				nPedidos = $('#pedidosNovos-qtd').text();
				nPedidos.replace(' ', '').trim();
				nPedidos = parseInt(nPedidos);
				if (nPedidos != 0) {
					//$('.audioPlayer').trigger('play');
					/*audio.play().then(function() {
						setTimeout(function() {
							audio.play();
						}, 2000);
						setTimeout(function() {
							audio.play();
						}, 4000);
						setTimeout(function() {
							audio.play();
						}, 6000);
						setTimeout(function() {
							audio.play();
						}, 8000);
					}).catch(function() {
						//alert('Por favor, clique aqui para habilitar o áudio do sistema de pedido');
						console.log('deu ruim 2');
					});*/

				}
			}, 2000);
			console.log('tocou 2');
		}, 20000);



		$('#titulo-mensagem2').click(function() {
			//location.reload();
			var valor = $('#pedidosNovos-qtd').text();
			valor = valor.replace(" ", "").trim();

			if (valor != '0') {
				location.reload();
			}
		});
		$('#form-filter-results').removeClass('form-inline');
	});
</script>