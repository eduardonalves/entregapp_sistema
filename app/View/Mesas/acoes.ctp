<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> <?php echo $pedidos['Mesa']['identificacao']; ?></h4>
					<input type="hidden" name="mesa_id" value="<?php echo $pedidos['Mesa']['id']; ?>">
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
							foreach ($pedidos['Pedidos'] as $mesa):

								$total =0;
					 ?>



					 	<p style="text-align:center;">
					 		Número: <?php echo $mesa['Pedido']['id']; ?> - Cliente: <?php echo $mesa['Pedido']['nomecadcliente']; ?>
					 	</p>

						<p style="text-align:center;" >
							Data <?php echo $mesa['Pedido']['data']; ?> - Obs: <?php echo $mesa['Pedido']['obs'];

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
								foreach ($mesa['Itensdepedido'] as $itens):
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
											<button type="button" name="button" class="btn btn-default btn-cancelar-item" data-id="<?php echo $itens['id']?>" id="btn-cancelar-item<?php echo $itens['id']?>" >Cancelar</button>
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
									<button type="button" class="btn btn-default btn-cancelar-pg"  data-id="<?php echo $pgtos['Pgtopedido']['id'];?>" id="cancelarPagamento<?php echo $pgtos['Pgtopedido']['id'];?>" name="cancelarPagamento">Cancelar</button>
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
										$mesaid='';
										$pagamentoid='';
									foreach ($pedidos['Pedidos'] as $pedidoAux) {
										$totalAux += $pedidoAux['Pedido']['valor'];
										$mesaid=$pedidoAux['Pedido']['mesa_id'];
										$pagamentoid=$pedidoAux['Pedido']['id'];
									}
									echo '<span class="valorTotalAtendimento" data-valor="'.$valorTotal.'" >'.number_format($valorTotal,2,",","."); ?>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td>
								<p class="textcenter">
									Taxa de Serviço: R$ <?php echo number_format($pedidos['Mesa']['taxa'],2,',','.'); ?>
								</p>
						 	</td>
						 </tr>
						 <tr>
						 	<td>
								<p class="textcenter">
									Desconto: R$ <span id="spandesconto"><?php echo number_format($pedidos['Mesa']['desconto'],2,',','.'); ?></span>
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
									 $subValor =(($valorTotal + $pedidos['Mesa']['taxa'])- $pedidos['Mesa']['desconto'])-$totalPgto;

											 echo number_format($subValor,2,",",".");


										?>
									</span>
									  <input type="hidden" name="valorgasto" id="valorgasto" value="<?php  echo number_format($valorTotal,2,",",".");  ?>">
									 <input type="hidden" name="valortaxa" id="valortaxa" value="<?php echo number_format($pedidos['Mesa']['taxa'],2,',','.'); ?>">
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


						<label for="">Serviço</label>
						<input type="number" id="itenspgtaxa" name="Pgtopedido[taxa]" value="">

						<label for="">Desconto</label>
						<input type="number" name="Pgtopedido[desconto]" value="" id="percentualDesconto">


						<label for="labelPG">Total</label><input type="text" name="Pgtopedido[valor_total_pago]" value="" readonly="readonly" class="pedidoValorTotalPago" id="pedidoValorTotalPago" >
						<input type="hidden" name="Pgtopedido[valor_dez]" value="" id="pedidoValorDez" readonly="readonly">
						<br>
						<br>
						<label for="labelPG">Obs</label><input type="text" name="Pgtopedido[obs]" value="">
						<br>
						<br>
						<input type="hidden" id="itenspg" name="Pgtopedido[itenspg]" value="">
						
						<input type="hidden" id="pagPedido" name="Pgtopedido[pg_id]" value="">
						<input type="hidden" name="Pgtopedido[mesa_id]" value="<?php echo $mesaid;?>">
							<input type="hidden" name="Pgtopedido[pagamento_id]" value="<?php echo $pagamentoid;?>">

						
						<label>Cliente: </label>
						  <br>
						  <select class="selectCliente" id="combobox2" name="Pgtopedido[cliente_id]">
							<option value="">Selecione um...</option>
								<?php
									foreach ($clientes as $cliente) {
										echo '<option value="'.$cliente['Cliente']['id'].'" >'.$cliente['Cliente']['nome'].' - '.$cliente['Cliente']['telefone'].'</option>';
									}
								?>
							  </select>
							<br>
							<br>
							<button type="submit" name="button" class="btn btn-default pagarSubmit">Inserir Pagamento</button>
					</form>

				</div>
				<div class="divTrocademesa">
					<h3>Troca de Mesa</h3>
					<form class="submitTrocaMesa centralizadoForm" action="mesas/trocarmesa/" method="post" id="submitTrocaMesa">
						<div class="">

						</div>
						<label for="MesasParaTrocar">Trocar mesa</label>
						<select class="selectTroca" name="TrocaMesa[mesanova_id]">
							<option value="">Selecione</option>
							<?php foreach ($minhasMesas as $minhaMesa): ?>

									<option value="<?php echo $minhaMesa['Mesa']['id'];?>"><?php echo $minhaMesa['Mesa']['identificacao'];  ?></option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="TrocaMesa[mesa_id]" class="mesa_antiga" value="<?php echo $mesaid;?>">
						<br>
						<br>
						<button type="submit" name="button" class="btn btn-defaults btn-salvar-troca">Salvar Troca</button>
					</form>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">

					<button type="button" name="button" class="btn btn-default trocarMesa">Trocar de  Mesa</button>

			<!--		<button type="button" name="button" class="btn btn-warning inserirdesconto">Inserir Desconto</button>-->
					<button type="button" name="button" class="btn btn-default primary btn-voltar none">Voltar</button>
						<button type="button" name="button" class="btn btn-default pagar btn-pagarmento">Pagamento</button>
					<button type="button" name="button" class="btn btn-default fecharMesa">Fechar de  Mesa</button>
	      </div>
	    </div>
	  </div>
	</div>
		<script type="text/javascript">
$(document).ready(function() {

  	$('#combobox').trigger("chosen:updated");

(function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );

        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },

      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";

        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });

        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },

          autocompletechange: "_removeIfInvalid"
        });
      },

      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;

        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();

            // Close if already visible
            if ( wasOpen ) {
              return;
            }

            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },

      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },

      _removeIfInvalid: function( event, ui ) {

        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }

        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });

        // Found a match, nothing to do
        if ( valid ) {
          return;
        }

        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },

      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );

  $(function() {
    $( "#toggle" ).click(function() {
      $( "#combobox" ).toggle();
    });
    setTimeout(function(){
	     $("#combocliente").combobox({
	        select: function (event, ui) {

	            $('.errotroco').hide();
	        },
	         open: function(event,ui){

	         }
	    });
	 },500);
    $("#combobox2").combobox({
        select: function (event, ui) {

           $('.errotroco').hide();
        },
         open: function(event,ui){

         }
    });
  });

	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#MesaEditForm').submit();
	});


	var urlInicio      = window.location.host;
	var urlInicio2      = window.location.host;
	urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp_sistema/Pgtopedidos/add': urlInicio);

	urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp_sistema/': urlInicio2);

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
				  url: urlInicio2+'mesas/cancelaritem',
					data:dataToSend,
					type:'POST',
					format:'format',
				  success: function(data) {
						console.log(data);
						if(data=='"Sucesso"'){
							$('#modalLoaded').modal('hide');
							$("#loadDivModal").load(urlInicio2+'mesas/acoes/'+$('input[name=mesa_id]').val(), function(){
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
				urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp_sistema/Pgtopedidos/add': urlInicio);

				urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp_sistema/': urlInicio2);

				urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
				urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


				urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
				urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


				$.ajax({
					url: urlInicio2+'mesas/cancelarpagamento',
					data:dataToSend,
					type:'POST',
					format:'format',
					success: function(data) {
						console.log(data);
						if(data=='"Sucesso"'){
							$('#modalLoaded').modal('hide');
							$("#loadDivModal").load(urlInicio2+'mesas/acoes/'+$('input[name=mesa_id]').val(), function(){
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
	$('.submitTrocaMesa').submit(function(event) {
		var r = confirm("Tem certeza que deseja trocar de mesa?");
		if (r == true) {
				x = "You pressed OK!";
				event.preventDefault();
				$('#modalLoaded').modal('hide');
					if(($('.selectTroca').val() != '' &&  $('.mesa_antiga').val() != '') && ($('.selectTroca').val() != $('input[name=mesa_id]').val())){
						//alert();
						data = {mesanova_id:$('.selectTroca').val(), mesa_id:$('input[name=mesa_id]').val()};
						var urlInicio      = window.location.host;
						var urlInicio2      = window.location.host;
						urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp_sistema/Pgtopedidos/add': urlInicio);

						urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp_sistema/': urlInicio2);

						urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
						urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


						urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
						urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


						$.ajax({
							url: urlInicio2+'mesas/trocarmesa',
							data:data,
							type:'POST',
							format:'format',
							success: function(data) {
								console.log(data);
								if(data=='"Sucesso"'){
									$('#modalLoaded').modal('hide');
									alert('Troca efetuada com sucesso!');

								}else if(data=='"Vazaio"') {
									alert('Não existem pedidos nesta mesa para efetuar a troca');

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
						alert('Selecione uma mesa, diferente');
					}
				}else{

				}


	});
	$('.pagarSubmit').on('click', function(event){

		event.preventDefault();
		$(this).prop("disabled", true);
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
						$("#loadDivModal").load(urlInicio2+'mesas/acoes/'+$('input[name=mesa_id]').val(), function(){
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

		$(this).prop("disabled", false);
	});

	$('.btn-salvar-desconto').on('click', function(event){

		event.preventDefault();
		var urlInicio      = window.location.host;
		var urlInicio2      = window.location.host;
		urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp_sistema/Pgtopedidos/add': urlInicio);

		urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp_sistema/': urlInicio2);

		urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
		urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


		urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
		urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);



		if(totalDesconto != ''){
			data = {desconto:totalDesconto, id:$('input[name=mesa_id]').val()};
			$.ajax({
				url: urlInicio2+'mesas/inserirdesconto',
				data:data,
				type:'POST',
				format:'format',
				success: function(data) {
					console.log(data);
					if(data=='"Sucesso"'){
						$('#modalLoaded').modal('hide');
						$("#loadDivModal").load(urlInicio2+'mesas/acoes/'+$('input[name=mesa_id]').val(), function(){
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
		    	var subtot = ($('#subtotal').val() =='' ? 0 :$('#subtotal').val().replace(",","."));
				subtot = parseFloat(subtot);

				var vlgasto = ($('#pedidoValor').val() =='' ? 0 :$('#pedidoValor').val().replace(",","."));
				vlgasto = parseFloat(vlgasto);

				var vlTaxa = ($('#itenspgtaxa').val() =='' ? 0 : $('#itenspgtaxa').val().replace(",","."));
				vlTaxa = parseFloat(vlTaxa);


				var vlPago = ($('#valpago').val() =='' ? 0 :$('#valpago').val().replace(",","."));
				var vlPago = parseFloat(vlPago);

				var percDesc = ($('#percentualDesconto').val() =='' ? 0 :$('#percentualDesconto').val().replace(",","."));
				var percDesc = parseFloat(percDesc);

				

				if(!isNaN(percDesc) && typeof percDesc !== 'undefined'){
					//var desc = (vlgasto * percDesc) / 100;

					//var totalAux = ((vlgasto + vlTaxa) - desc) -  vlPago;
					percDesc=percDesc.toFixed(2);
					vlPago=vlPago.toFixed(2);
					vlgasto=vlgasto.toFixed(2);
					subtot=subtot.toFixed(2);

					var totalAux = (vlgasto - percDesc);

					totalAux = totalAux.toFixed(2);
					var totalAux2 = String(totalAux);
					var descAux =  (vlgasto - percDesc) + vlTaxa;
					descAux = descAux.toFixed(2);
					
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
		$('.divTrocademesa').hide();
		$('.trocarMesa').hide();

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
	$('.trocarMesa').click('click', function(event){
		event.preventDefault();
		$('.btn-voltar').show();
			if($( ".divTrocademesa" ).is(':visible'))
			{

				$('.divTrocademesa').slideUp();

				//	$('.inserirdesconto').show();
				$('.btn-voltar').show();

				//$('.btn-cancelar').hide();
				//alert('show');
			}else{
				$('.divTrocademesa').slideDown();
				$('.btn-pagarmento').hide();
				$('.inserirdesconto').hide();
				$('.trocarMesa').hide();
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
			$('.divTrocademesa').hide();
			$('.trocarMesa').hide();
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
					//$('#pedidoValor').val(totalPagarComDez.toFixed(2));
					$('#pedidoValorTotalPago').val(totalPagarComDez.toFixed(2));
					$('#pedidoValorDez').val(totalPagar.toFixed(2));
					$('#percentualDesconto').val('0');


					//console.log(totalPagar);
				}else if($(this).is(":not(:checked)")){
					$('#itenspgtaxa').val();
					//$('#pedidoValor').val(totalPagar.toFixed(2));
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
					$('.divTrocademesa').hide();
					$('.btn-voltar').show();
					$('.btn-cancelar').show();
					$('.btn-voltar').show();
					$('.pagar').show();
					$('.inserirdesconto').show();

					$('.trocarMesa').show();
					$('.btn-voltar').hide();
			//$('.pagamento').slideDown();
			//$('.content-pedido').slideUp();
		});

		$('.fecharMesa').on('click', function(event){
			event.preventDefault();

			var r = confirm("Tem certeza que deseja Fechar esta mesa?");
			if (r == true) {
					x = "You pressed OK!";
					var dataToSend = {'mesa_id':$('input[name=mesa_id]').val()};
					var urlInicio      = window.location.host;
					var urlInicio2      = window.location.host;
					urlInicio = (urlInicio=='localhost' ? 'http://localhost/entregapp_sistema/Pgtopedidos/add': urlInicio);

					urlInicio2 = (urlInicio2=='localhost' ? 'http://localhost/entregapp_sistema/': urlInicio2);

					urlInicio = (urlInicio=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/Pgtopedidos/add': urlInicio);
					urlInicio2 = (urlInicio2=='develop.entregapp.com.br' ? 'http://develop.entregapp.com.br/': urlInicio2);


					urlInicio = (urlInicio=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/Pgtopedidos/add': urlInicio);
					urlInicio2 = (urlInicio2=='sistema.entregapp.com.br' ? 'http://sistema.entregapp.com.br/': urlInicio2);


					$.ajax({
						url: urlInicio2+'mesas/fecharmesa',
						data:dataToSend,
						type:'POST',
						format:'format',
						success: function(data) {
							console.log(data);
							if(data=='"Sucesso"'){
								$('#modalLoaded').modal('hide');
								$("#loadDivModal").load(urlInicio2+'mesas/acoes/'+$('input[name=mesa_id]').val(), function(){
									setTimeout(function() {
											//$('#modalLoaded').modal('show');
												location.reload();
									},1000);

								});
							}else{
								alert("Houve um erro, a operação não foi realizada.");
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
	.divTrocademesa{
		display: none;

	}
	.checkpago{
		    margin-left: 50px!important;
				display: block;
	}
</style>
