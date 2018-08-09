<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> <?php echo $pedidos['Entrega']['identificacao']; ?></h4>
					<input type="hidden" name="entrega_id" value="<?php echo $pedidos['Entrega']['id']; ?>">
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div class="content-pedido">
						<h3 style="text-align:center;">Pedidos</h3>

					<?php
					if(isset($pedidos['Pedidos'][0]['Filial']['dez_porcento'])){
						if ($pedidos['Pedidos'][0]['Filial']['dez_porcento'] == true) {
							$dez_porcento=true;
						}else{
							$dez_porcento=false;
						}
					}else{
						$dez_porcento=false;
					}

							$valorTotal10 = 0;
							$valorTotal =0;
							foreach ($pedidos['Pedidos'] as $entrega):

								$total =0;
					 ?>



					 	<p style="text-align:center;">
					 		Número: <?php echo $entrega['Pedido']['id']; ?> - Cliente: <?php echo $entrega['Pedido']['nomecadcliente']; ?>
					 	</p>

						<p style="text-align:center;" >
							Data <?php echo $entrega['Pedido']['data']; ?> - Obs: <?php echo $entrega['Pedido']['obs'];

							 ?>
						</p>






					 <div class="area-tabela"  id="no-more-tables">
			 			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" style="margin-bottom: 20px;">
			 				<thead class="cf">
			 					<tr>
			 						<th class="th-red">Código</th>
			 						<th class="th-red">Item </th>
									<th class="th-red">Obs </th>
			 						<th class="th-red">Valor Unitário</th>
			 						<th class="th-red">Qtd </th>
									<th class="th-red">Valor Total </th>


									<th class="th-red">Incluir no Pagamento </th>
									<th class="th-red">Ações </th>
								</tr>
			 				</thead>
						 	<tbody>

								<?php
								$totalPedido=0;
								foreach ($entrega['Itensdepedido'] as $itens):
								//	print_r($itens);
									?>
									<tr >
										<td>
											<?php echo $itens['produto_id']; ?>
										</td>
										<td>
											<?php echo $itens['produto_nome']; ?>
										</td>
										<td>
											<?php echo $itens['composto_nomeum'].' '.$itens['composto_nomedois'].' '.$itens['obs_sis']; ?>
										</td>
										<td>
											R$ <?php echo number_format($itens['valor_unit'],2,',','.'); ?>
										</td>
										<td>
											<?php echo $itens['qtde'] ; ?>
										</td>
										<td>
											R$ <?php
												echo number_format($itens['valor_total'],2,',','.');
											?>

										</td>


										<td>
											<?php
												if($itens['status_cancelado']== true)
												{
													echo $this->html->image('btncancelar.png', array('alt'=> 'Pago', 'width'=>'20px','height'=>'20px', 'class'=>'icon-pago'));

												}else{

													if($itens['status_pago'] != true ){
														?>
														 <input type="checkbox" class="checkpago" name="checkpago<?php echo $itens['id']?>" data-id="<?php echo $itens['id']?>" id="checkpago<?php echo $itens['id']?>" value="<?php echo $itens['id']?>" data-valor="<?php echo $itens['valor_total']?>" value="<?php echo $itens['id']?>">
													 <?php
												 }else{
													 echo $this->html->image('pago_ok.png', array('alt'=> 'Pago', 'width'=>'20px','height'=>'20px', 'class'=>'icon-pago'));
												 }
											 }
													?>

										</td>
										<td>
											<?php
											if($itens['status_cancelado'] != true)
											{
											?>
											<button type="button" name="button" class="btn btn-danger btn-cancelar-item" data-id="<?php echo $itens['id']?>" id="btn-cancelar-item<?php echo $itens['id']?>" >Cancelar</button>
											<?php
											}else{
											echo '-';
											}
											?>
										</td>
									</tr>

								<?php
								if($itens['status_cancelado'] != true)
								{
									$totalPedido = $totalPedido + $itens['valor_total'];
									$valorTotal = $valorTotal + $itens['valor_total'];
								}
								endforeach;
								?>
								<tr>
										<td colspan="7" style="background: yellow;">
											Total
										</td>
										<td style="background: yellow;" >
											R$ <?php

											echo number_format($totalPedido,2,',','.');
											?>
										</td>
								</tr>
						 	</tbody>
					 </table>

				 </div>

				 <?php endforeach; ?>



				</div>
				<h3 style="text-align:center;">Pagamentos</h3>
				<div class="area-tabela"  id="no-more-tables">
				 <table class="table-action col-md-12 table-bordered table-striped table-condensed cf" style="margin-bottom: 20px;">
					 <thead class="cf">
						 <tr>

							 <th class="th-red">Forma de Pagamento </th>
							 <th class="th-red">Obs </th>
							 <th class="th-red">Valor do Pagamento</th>
							 <th class="th-red">Situação</th>

							 <th class="th-red">Ações</th>
						 </tr>
					 </thead>
					 <tbody>
						<?php
							$totalPgto=0;
							foreach ($pedidos['Pgtopedido'] as $pgtos)
							{
						?>
							<tr>
								<td>
								<?php
									echo $pgtos['Pgtopedido']['pgnome'];
								 ?>
								</td>
								<td>
								<?php
									echo $pgtos['Pgtopedido']['obs'];
								 ?>
								</td>
								<td>
									R$
								<?php
									echo number_format($pgtos['Pgtopedido']['valor'],2,',','.');
								 ?>
							 </td>
								<td>
								<?php

									if($pgtos['Pgtopedido']['status']=='A'){
										echo $this->html->image('pago_ok.png', array('alt'=> 'Pago', 'width'=>'20px','height'=>'20px', 'class'=>'icon-pago'));
										$totalPgto += $pgtos['Pgtopedido']['valor'];
									}else{
										echo $this->html->image('btncancelar.png', array('alt'=> 'Pago', 'width'=>'20px','height'=>'20px', 'class'=>'icon-pago'));
									}
								 ?>
								</td>

								<td>
									<?php if($pgtos['Pgtopedido']['status']=='A'){ ?>
									<button type="button" class="btn btn-danger btn-cancelar-pg"  data-id="<?php echo $pgtos['Pgtopedido']['id'];?>" id="cancelarPagamento<?php echo $pgtos['Pgtopedido']['id'];?>" name="cancelarPagamento">Cancelar</button>
									<?php } ?>
								</td>
							</tr>


						<?php


							}

						 ?>
						 <tr>
								 <td colspan="4" style="background: yellow;">
									 Total
								 </td>
								 <td style="background: yellow;" >
									 R$ <?php

									 echo number_format($totalPgto,2,',','.');
									 ?>
								 </td>
						 </tr>
					 </tbody>
				</table>

			</div>
			<div class="area-tabela"  id="no-more-tables">
			 <table class="table-action col-md-12 table-bordered table-striped table-condensed cf" style="margin-bottom: 20px;">
					 <thead class="cf">
						 <tr>

							 <th class="th-red">Conta</th>
						 </tr>
					 </thead>
					 <tbody>
						 <tr>
						 	<td>
								<p class="textcenter">
									Valor Gasto: R$ <?php
										$totalAux =0;
										$entregaid='';
										$pagamentoid='';
									foreach ($pedidos['Pedidos'] as $pedidoAux) {
										$totalAux += $pedidoAux['Pedido']['valor'];
										$entregaid=$pedidoAux['Pedido']['entrega_id'];
										$pagamentoid=$pedidoAux['Pedido']['id'];
									}
									echo '<span class="valorTotalAtendimento" data-valor="'.$valorTotal.'" >'.number_format($valorTotal,2,",","."); ?>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td>
								<p class="textcenter">
									Taxa de Serviço: R$ <?php echo number_format($pedidos['Entrega']['taxa'],2,',','.'); ?>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td>
								<p class="textcenter">
									Desconto: R$ <span id="spandesconto"><?php echo number_format($pedidos['Entrega']['desconto'],2,',','.'); ?></span>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td>
								<p class="textcenter">
									Valor Pago: R$ <?php  echo number_format($totalPgto,2,",",".");  ?>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td style="background: yellow;">
								<p class="textcenter incluir10" >
									Total à pagar R$ <span id="spantotalapagar">
										<?php
									 $subValor =(($valorTotal + $pedidos['Entrega']['taxa'])- $pedidos['Entrega']['desconto'])-$totalPgto;

											 echo number_format($subValor,2,",",".");


										?>
									</span>
									  <input type="hidden" name="valorgasto" id="valorgasto" value="<?php  echo number_format($valorTotal,2,",",".");  ?>">
									 <input type="hidden" name="valortaxa" id="valortaxa" value="<?php echo number_format($pedidos['Entrega']['taxa'],2,',','.'); ?>">
									<input type="hidden" name="valpago" id="valpago" value="<?php  echo number_format($totalPgto,2,",",".");  ?>">
									<input type="hidden" name="subtotal" id="subtotal" value="<?php  echo number_format($subValor,2,",",".");  ?>">
								</p>
						 	</td>
						 </tr>
					 </tbody>
			 </table>
				</div>




				<!--<p class="textcenter incluir10">
					Total com 10% R$ <?php //echo  '<span class="valorTotalAtendimentoComDesconto" data-valor="'.$valorTotal10.'" >'.number_format($valorTotal10,2,",","."); ?>
				</p>-->


				<!--<div class="divdesconto">
					<form class="senddescount" action="" method="post">

						<br>
						<br>
						<button type="submit" name="button" class="btn btn-success btn-salvar-desconto">Salvar Desconto</button>
					</form>
				</div>-->

				<div class="pagamento">
					<!--<label for="">Incluir 10% <input type="checkbox" name="inputvalor10" value="1" class="inputvalor10"></label>
					<br>
					<br>-->
					<form class="formPagamento" id="formPagamento" action="" method="post">
						<label for="labelPG"> Incluir 10 %
							<input type="checkbox" name="incluirdez" id="incluirdez" value="1" >
						</label>
						<br>
						<label for="labelPG">Forma de Pagamento</label>
						<select class="formadepagamento" name="Pgtopedido[pagamento_id]">

							<option value="">selecione</option>
							<?php foreach ($meusPagamentos as $meuPagamento): ?>
									<option value="<?php echo $meuPagamento['Pagamento']['id'];?>"><?php echo $meuPagamento['Pagamento']['tipo']; ?></option>
							<?php endforeach; ?>

						</select>
						<br>
						<br>
						<label for="labelPG">Valor à Pagar</label><input type="number" name="Pgtopedido[valor]" value="" id="pedidoValor" >
						<label for="">Desconto %</label>
						<input type="number" name="desconto" value="" id="percentualDesconto">


						<label for="labelPG">Total</label><input type="text" name="Pgtopedido[valor_total_pago]" value="" class="pedidoValorTotalPago" id="pedidoValorTotalPago" >
						<input type="hidden" name="Pgtopedido[valor_dez]" value="" id="pedidoValorDez" readonly="readonly">
						<br>
						<br>
						<label for="labelPG">Obs</label><input type="text" name="Pgtopedido[obs]" value="">
						<br>
						<br>
						<input type="hidden" id="itenspg" name="Pgtopedido[itenspg]" value="">
						<input type="hidden" id="itenspgtaxa" name="Pgtopedido[taxa]" value="">
						<input type="hidden" id="pagPedido" name="Pgtopedido[pg_id]" value="">
						<input type="hidden" name="Pgtopedido[entrega_id]" value="<?php echo $entregaid;?>">
							<input type="hidden" name="Pgtopedido[pagamento_id]" value="<?php echo $pagamentoid;?>">
						<button type="submit" name="button" class="btn btn-success pagarSubmit">Inserir Pagamento</button>
					</form>

				</div>
				<div class="divTrocadeentrega">
					<h3>Troca de Entrega</h3>
					<form class="submitTrocaEntrega centralizadoForm" action="entregas/trocarentrega/" method="post" id="submitTrocaEntrega">
						<div class="">

						</div>
						<label for="EntregasParaTrocar">Trocar entrega</label>
						<select class="selectTroca" name="TrocaEntrega[entreganova_id]">
							<option value="">Selecione</option>
							<?php foreach ($minhasEntregas as $minhaEntrega): ?>

									<option value="<?php echo $minhaEntrega['Entrega']['id'];?>"><?php echo $minhaEntrega['Entrega']['identificacao'];  ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="TrocaEntrega[entrega_id]" class="entrega_antiga" value="<?php echo $entregaid;?>">
						<br>
						<br>
						<button type="submit" name="button" class="btn btn-success btn-salvar-troca">Salvar Troca</button>
					</form>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">

					<button type="button" name="button" class="btn btn-primary trocarEntrega">Trocar de  Entrega</button>

			<!--		<button type="button" name="button" class="btn btn-warning inserirdesconto">Inserir Desconto</button>-->
					<button type="button" name="button" class="btn btn-warning primary btn-voltar none">Voltar</button>
						<button type="button" name="button" class="btn btn-success pagar btn-pagarmento">Pagamento</button>
					<button type="button" name="button" class="btn btn-danger fecharEntrega">Fechar de  Entrega</button>
	      </div>
	    </div>
	  </div>
	</div>
		<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#EntregaEditForm').submit();
	});


	var urlInicio      = window.location.host;
	var urlInicio2      = window.location.host;
	urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp/Pgtopedidos/add': urlInicio);

	urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp/': urlInicio2);

	urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
	urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


	urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
	urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


	console.log(urlInicio2);
	$('body').on('click','.btn-cancelar-item', function(event){
		event.preventDefault();

		var r = confirm("Tem certeza que deseja cancelar este item?");
		if (r == true) {
		    x = "You pressed OK!";
				var dataToSend = {'id':$( this ).data('id')};

				$.ajax({
				  url: urlInicio2+'entregas/cancelaritem',
					data:dataToSend,
					type:'POST',
					format:'format',
				  success: function(data) {
						console.log(data);
						if(data=='"Sucesso"'){
							$('#modalLoaded').modal('hide');
							$("#loadDivModal").load(urlInicio2+'entregas/acoes/'+$('input[name=entrega_id]').val(), function(){
								setTimeout(function() {
										$('#modalLoaded').modal('show');
								},1000);

							});
						}else{
							//alert(2);
						}
				  },
				  beforeSend: function(){
				    //$('.loader').css({display:"block"});
				  },
				  complete: function(){
				    //$('.loader').css({display:"none"});
				  }
				});
		} else {
		    x = "You pressed Cancel!";
		}

	});
	$('.btn-cancelar-pg').on('click', function(event){
		event.preventDefault();

		var r = confirm("Tem certeza que deseja cancelar este item?");
		if (r == true) {
				x = "You pressed OK!";
				var dataToSend = {'id':$( this ).data('id')};
				var urlInicio      = window.location.host;
				var urlInicio2      = window.location.host;
				urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp/Pgtopedidos/add': urlInicio);

				urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp/': urlInicio2);

				urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
				urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


				urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
				urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


				$.ajax({
					url: urlInicio2+'entregas/cancelarpagamento',
					data:dataToSend,
					type:'POST',
					format:'format',
					success: function(data) {
						console.log(data);
						if(data=='"Sucesso"'){
							$('#modalLoaded').modal('hide');
							$("#loadDivModal").load(urlInicio2+'entregas/acoes/'+$('input[name=entrega_id]').val(), function(){
								setTimeout(function() {
										$('#modalLoaded').modal('show');
								},1000);

							});
						}else{
							//alert(2);
						}
					},
					beforeSend: function(){
						//$('.loader').css({display:"block"});
					},
					complete: function(){
						//$('.loader').css({display:"none"});
					}
				});
		} else {
				x = "You pressed Cancel!";
		}

	});
	$('.submitTrocaEntrega').submit(function(event) {
		var r = confirm("Tem certeza que deseja trocar de entrega?");
		if (r == true) {
				x = "You pressed OK!";
				event.preventDefault();
				$('#modalLoaded').modal('hide');
					if(($('.selectTroca').val() != '' &&  $('.entrega_antiga').val() != '') && ($('.selectTroca').val() != $('input[name=entrega_id]').val())){
						//alert();
						data = {entreganova_id:$('.selectTroca').val(), entrega_id:$('input[name=entrega_id]').val()};
						var urlInicio      = window.location.host;
						var urlInicio2      = window.location.host;
						urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp/Pgtopedidos/add': urlInicio);

						urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp/': urlInicio2);

						urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
						urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


						urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
						urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


						$.ajax({
							url: urlInicio2+'entregas/trocarentrega',
							data:data,
							type:'POST',
							format:'format',
							success: function(data) {
								console.log(data);
								if(data=='"Sucesso"'){
									$('#modalLoaded').modal('hide');
									alert('Troca efetuada com sucesso!');

								}else if(data=='"Vazaio"') {
									alert('Não existem pedidos nesta entrega para efetuar a troca');

								}else{
									alert('Erro ao efutuar a troca');

								}
								location.reload();
							},
							beforeSend: function(){
								//$('.loader').css({display:"block"});
							},
							complete: function(){
								//$('.loader').css({display:"none"});
							}
						});
					}else {
						alert('Selecione uma entrega, diferente');
					}
				}else{

				}


	});
	$('.pagarSubmit').on('click', function(event){

		event.preventDefault();
		if($('#pedidoValor').val() != '' && $('.formadepagamento').val() )
		{
			$("#itenspg").val('');
			idsPgtoLen = idsPgto.length;
			textoPagamento = "";
			for (i = 0; i < idsPgtoLen; i++) {
					textoPagamento +=  ','+idsPgto[i] ;
			}
			$("#itenspg").val(textoPagamento);

			$('#pagPedido').val($('.formadepagamento').val());
			$.ajax({
				url: urlInicio,
				data:$('#formPagamento').serialize(),
				type:'POST',
				format:'format',
				success: function(data) {
					console.log(data);
					if(data=='"Sucesso"'){
						$('#modalLoaded').modal('hide');
						$("#loadDivModal").load(urlInicio2+'entregas/acoes/'+$('input[name=entrega_id]').val(), function(){
							setTimeout(function() {
									$('#modalLoaded').modal('show');
							},1000);

						});
					}else{
						//alert(2);
					}
				},
				beforeSend: function(){
					//$('.loader').css({display:"block"});
				},
				complete: function(){
					//$('.loader').css({display:"none"});
				}
			});
		}else{
			alert('Insira um valor e a forma de pagamento !');
		}


	});

	$('.btn-salvar-desconto').on('click', function(event){

		event.preventDefault();
		var urlInicio      = window.location.host;
		var urlInicio2      = window.location.host;
		urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp/Pgtopedidos/add': urlInicio);

		urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp/': urlInicio2);

		urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
		urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


		urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
		urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);



		if(totalDesconto != ''){
			data = {desconto:totalDesconto, id:$('input[name=entrega_id]').val()};
			$.ajax({
				url: urlInicio2+'entregas/inserirdesconto',
				data:data,
				type:'POST',
				format:'format',
				success: function(data) {
					console.log(data);
					if(data=='"Sucesso"'){
						$('#modalLoaded').modal('hide');
						$("#loadDivModal").load(urlInicio2+'entregas/acoes/'+$('input[name=entrega_id]').val(), function(){
							setTimeout(function() {
									$('#modalLoaded').modal('show');
							},1000);

						});
					}else{
						//alert(2);
					}
				},
				beforeSend: function(){
					//$('.loader').css({display:"block"});
				},
				complete: function(){
					//$('.loader').css({display:"none"});
				}
			});
		}else{
			alert('O campo desconto não pode ser vazio!');
		}


	});



	$('body').on('click','.inputvalor10', function(event){
		event.preventDefault();


		if ($('.inputvalor10').is(':checked')) {
				$('.inputvalor10').prop('checked', true);
		}
	});
	$('body').on('change','.valorpago', function(event){
		event.preventDefault();
		var valorTotal = parseFloat($('.valorTotalAtendimento').data('valor'));
		var valorTotalComdez = parseFloat($('.valorTotalAtendimentoComDesconto').data('valor'));
		valorDesconto = parseFloat($(this).val());
		$('.valorTotalAtendimento').html(valorTotal - valorDesconto);
		$('.valorTotalAtendimentoComDesconto').html();
	//	alert(valorDesconto);
	});
	var totalDesconto='';
	$('#pedidoValor').keyup(function(){
		$("#percentualDesconto").val('');
		$('#pedidoValorTotalPago').val($(this).val());
		$('#incluirdez').attr('checked', false);
	});
	$("#percentualDesconto").keyup(function(){
				totalDesconto='';

		    $("#percentualDesconto").css("background-color", "yellow");
				var subtot = parseFloat($('#subtotal').val().replace(",","."));
				var vlgasto = parseFloat($('#pedidoValor').val().replace(",","."));
				var vlTaxa = parseFloat($('#valortaxa').val().replace(",","."));
				var vlPago = parseFloat($('#valpago').val().replace(",","."));
				var percDesc = parseFloat($('#percentualDesconto').val().replace(",","."));
				if(!isNaN(percDesc) && typeof percDesc !== 'undefined'){
					var desc = (vlgasto * percDesc) / 100;

					//var totalAux = ((vlgasto + vlTaxa) - desc) -  vlPago;

					var totalAux = (vlgasto - desc);

					totalAux = totalAux.toFixed(2);
					var totalAux2 = String(totalAux);
					var descAux =  vlgasto - desc;
					descAux  = String(descAux);
					//$('#spantotalapagar').html(totalAux2.replace(".",","));
					//totalDesconto = desc.toFixed(2);
					//var desc2 = String(desc.toFixed(2));

					//$('#spandesconto').html(desc2.replace(".",","));

					$('.pedidoValorTotalPago').val(descAux.replace(".",",")).change();
				}

	});

	$('.inserirdesconto').click('click', function(event){
		event.preventDefault();
		$('.divTrocadeentrega').hide();
		$('.trocarEntrega').hide();

			if($( ".divdesconto" ).is(':visible'))
			{

				$('.divdesconto').slideUp();

					$('.inserirdesconto').show();
				$('.btn-voltar').show();

				//$('.btn-cancelar').hide();
				//alert('show');
			}else{
				$('.divdesconto').slideDown();
				$('.inserirdesconto').hide();
				$('.pagar').hide();
				$('.btn-voltar').show();
			//	$('.btn-cancelar').show();

			}
		//$('.pagamento').slideDown();
		//$('.content-pedido').slideUp();
	});
	$('.trocarEntrega').click('click', function(event){
		event.preventDefault();
		$('.btn-voltar').show();
			if($( ".divTrocadeentrega" ).is(':visible'))
			{

				$('.divTrocadeentrega').slideUp();

				//	$('.inserirdesconto').show();
				$('.btn-voltar').show();

				//$('.btn-cancelar').hide();
				//alert('show');
			}else{
				$('.divTrocadeentrega').slideDown();
				$('.btn-pagarmento').hide();
				$('.inserirdesconto').hide();
				$('.trocarEntrega').hide();
				//$('.inserirdesconto').hide();
				//$('.pagar').hide();
			//	$('.btn-voltar').show();
			//	$('.btn-cancelar').show();

			}
		//$('.pagamento').slideDown();
		//$('.content-pedido').slideUp();
	});

	$('.pagar').click('click', function(event){
		event.preventDefault();
			$('.divTrocadeentrega').hide();
			$('.trocarEntrega').hide();
			if($( ".pagamento" ).is(':visible'))
			{



					$('.pagamento').slideUp();
					$('.btn-voltar').hide();
					$('.btn-cancelar').show();
						$('.inserirdesconto').show();
						$('.pagar').show();

			}else{

				$('.pagamento').slideDown();
				$('.btn-voltar').show();
				$('.btn-cancelar').hide();
					$('.inserirdesconto').hide();
					$('.pagar').hide();
					$('.btn-voltar').show();
			}
		//$('.pagamento').slideDown();
		//$('.content-pedido').slideUp();
	});
	$('#incluirdez').click('click', function(event){
		if($('#pedidoValor').val() != ''){
			totalPagar= 0 ;
			totalPagarComDez=0;
			idsPgto = [];
				$( ".checkpago" ).each(function( index ) {
						if($(this).is(":checked")){
								idsPgto.push($( this ).data('id'));
						}else{

						}
				});
				if($(this).is(":checked")){
					//idsPgto.push($( this ).data('id'));

					valor1 = parseFloat($( this ).data('valor'));
					totalPagar = $('#pedidoValor').val();
					totalPagar = parseFloat(totalPagar);
					totalPagarComDez = ((totalPagar * 10) / 100 ) + totalPagar ;

					$('#itenspgtaxa').val(totalPagarComDez - totalPagar);
					$('#pedidoValor').val(totalPagarComDez.toFixed(2));
					$('#pedidoValorTotalPago').val(totalPagarComDez.toFixed(2));
					$('#pedidoValorDez').val(totalPagar.toFixed(2));
					$('#percentualDesconto').val('0');


					//console.log(totalPagar);
				}else if($(this).is(":not(:checked)")){
					$('#itenspgtaxa').val();
					$('#pedidoValor').val(totalPagar.toFixed(2));
					$('#pedidoValorTotalPago').val(totalPagar.toFixed(2));
					$('#percentualDesconto').val('0');
				}
		}else{
			alert('Digite um valor a pagar!');
			$('#incluirdez').attr('checked', false);
		}




	});
			var totalPagar=0;
			var idsPgto = [];
			$('.checkpago').click('click', function(event){
				totalPagar= 0 ;
				totalPagarComDez=0;
				idsPgto = [];
				$( ".checkpago" ).each(function( index ) {
					if($(this).is(":checked")){
						idsPgto.push($( this ).data('id'));

						valor1 = parseFloat($( this ).data('valor'));

						totalPagar = parseFloat(totalPagar) +  parseFloat(valor1);
						console.log( index + ": " + $( this ).data('valor') );
						if($('#incluirdez').is(":checked"))
						{

							totalPagarComDez = totalPagar * 1.10;
							$('#pedidoValor').val(totalPagarComDez.toFixed(2));
							$('#pedidoValorTotalPago').val(totalPagarComDez.toFixed(2));
							$('#pedidoValorDez').val(totalPagar.toFixed(2));
							$('#percentualDesconto').val('0');
						}else{

							$('#pedidoValor').val(totalPagar.toFixed(2));
								$('#pedidoValorTotalPago').val(totalPagar.toFixed(2));
								$('#percentualDesconto').val('0');
						}


						//console.log(totalPagar);
					}else if($(this).is(":not(:checked)")){
					  //alert("Checkbox is unchecked.");
					}

				});
			});

		$('.btn-voltar').click('click', function(event){
			event.preventDefault();


					$('.pagamento').slideUp();
					$('.divdesconto').slideUp();
					$('.divTrocadeentrega').hide();
					$('.btn-voltar').show();
					$('.btn-cancelar').show();
					$('.btn-voltar').show();
					$('.pagar').show();
					$('.inserirdesconto').show();

					$('.trocarEntrega').show();
					$('.btn-voltar').hide();
			//$('.pagamento').slideDown();
			//$('.content-pedido').slideUp();
		});

		$('.fecharEntrega').on('click', function(event){
			event.preventDefault();

			var r = confirm("Tem certeza que deseja Fechar esta entrega?");
			if (r == true) {
					x = "You pressed OK!";
					var dataToSend = {'entrega_id':$('input[name=entrega_id]').val()};
					var urlInicio      = window.location.host;
					var urlInicio2      = window.location.host;
					urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp/Pgtopedidos/add': urlInicio);

					urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp/': urlInicio2);

					urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
					urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


					urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
					urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


					$.ajax({
						url: urlInicio2+'entregas/fecharentrega',
						data:dataToSend,
						type:'POST',
						format:'format',
						success: function(data) {
							console.log(data);
							if(data=='"Sucesso"'){
								$('#modalLoaded').modal('hide');
								$("#loadDivModal").load(urlInicio2+'entregas/acoes/'+$('input[name=entrega_id]').val(), function(){
									setTimeout(function() {
											//$('#modalLoaded').modal('show');
												location.reload();
									},1000);

								});
							}else{
								//alert(2);
							}
						},
						beforeSend: function(){
							//$('.loader').css({display:"block"});
						},
						complete: function(){
							//$('.loader').css({display:"none"});
						}
					});
			} else {
					x = "You pressed Cancel!";

			}

		});
});
	</script>
<style media="screen">
	table{
		text-align: center;
	}
	.pagamento{
		display: none;
	}
	.divdesconto{
		display: none;
	}
	.btn-voltar{
		display: none;
	}
	.divTrocadeentrega{
		display: none;

	}
	.checkpago{
		    margin-left: 50px!important;
				display: block;
	}
</style>
