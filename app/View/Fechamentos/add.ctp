<!-- Modal -->
	<div class="modal fade modal-grande" id="modalNovoPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="modalNovoPedidolabel"> Fazer de Pedidos</h4>
	      </div>
	      <div class="modal-body">
				<?php

					echo $this->form->create('Pedido', array('class'=> 'form-inline'));
				?>


				<section class="row">
					<section class="span4">
						<label>Cliente: </label>
						  <br>
						  <select class="selectCliente" id="combobox2">
							<option value="">Selecione um...</option>
							<?php
								foreach ($clientes as $cliente) {
									echo '<option value="'.$cliente['Cliente']['id'].'" >'.$cliente['Cliente']['nome'].' - '.$cliente['Cliente']['telefone'].'</option>';
								}
							?>
						  </select>
						  <button class="btn btn-success addCliente bts-add" id="addCliente">Selecionar</button>
					</section>

					<section class="span2">
						<label>Pagamento: </label>
					<br>
							<select name="data[Pedido][pagamento_id]" class="selectPagamento input-default" id="selectPagamento">
							<option value="0"></option>
								<?php

									foreach ($pagamentos as $pagamento) {
										echo '<option value="'.$pagamento['Pagamento']['id'].'" >'.$pagamento['Pagamento']['tipo'].'</option>';
									}
								?>
							 </select>

					</section>
					<section class="span2">
						<label>Mesa: </label>
					<br>
							<select name="data[Pedido][mesa_id]" class="selectMesa input-default" id="selectMesa">
							<option value="0"></option>
								<?php

									foreach ($mesas as $mesa) {
										echo '<option value="'.$mesa['Mesa']['id'].'" >'.$mesa['Mesa']['identificacao'].'</option>';
									}
								?>
							 </select>

					</section>
					<section class="span2">
						<label>Loja: </label>
					<br>
							<select name="data[Pedido][filial_id]" class="selectFilial input-default" id="selectFilial">
								<?php
									foreach ($lojas as $key => $loja) {
										echo '<option value="'.$key.'" >'. $loja.'</option>';
									}
								?>
							 </select>

					</section>

					<section class=" divTrocolv none">
						<section class="span1">
							<label class="divTrocolv none">Troco? </label>
							<section class="divTrocolv none">
								<select name="data[Pedido][trocoresposta]" id="trocoResposta" class="input-default">
									<option value="Não">Não</option>
									<option value="Sim">Sim</option>
								</select>
							</section>
						</section>
					</section>

					<section class="divTroco none">
						<section class="span1">
							<label class="divTroco none">Para? </label>
							<section class=" divTroco none">
								<input type="text" class="input-default" name="data[Pedido][trocovalor]" value="" id="troco">
							</section>
						</section>
					</section>
				</section>

<!--  ############################################################################################################ -->
				<div style="clear:both;"></div>
				<section class="row" style="margin-top:10px;margin-bottom:30px;">

					<?php

						$entrega = array();
						$entrega[' ']=' ';
						foreach ($entregadores as $entregador) {
							$entrega[$entregador['Entregador']['id']]	=$entregador['Entregador']['nome'];

						}
					?>
					<section class="span3">
						<label>Entregador:</label>
						<br>
						<?php

						 echo $this->Form->input('Pedido.entregador_id',array('options'=> $entrega,'type'=>'select','class' => 'input-default entregadorView','id'=>'entregadorView','label' => false,'div'=>false));?>
					</section>

					<section class="span3">
						  <label>Produto: </label>
						  <select class="selectProduto" id="combocliente">
							<option value="">Selecione um...</option>
							<?php


								foreach ($produtos as $produto) {
									$disabled ='enabled';
									$disponivelTexto ='';
									if($produto['Produto']['disponivel'] != true){$disabled ='disabled'; $disponivelTexto=' - Produto Indisponível'; }
									echo '<option value="'.$produto['Produto']['nome'].'" data-codigo="'.$produto['Produto']['id'].'" data-preco="'.$produto['Produto']['preco_venda'].'" data-nome="'.$produto['Produto']['nome'].'" data-disabled="'.$disabled .'">'.$produto['Produto']['nome'].' '. $disponivelTexto.'</option>';
								}
							?>

						  </select>
					</section>

					<section class="span1">
						<label for="inp-qtde">Qtde: </label>
						<input type="text" id="inp-qtde" class="input-default" value="1">
					</section>
					<section class="span1">
						<label for="inp-preco">Preco: </label>
						<input type="text" id="inp-preco" class="input-default">
					</section>

					<section class="span2">
						<button class="btn btn-success addProduto bts-add" id="addProduto">Adicionar Produto</button>

						<button class="btn btn-danger removeProduto bts-add" id="removeProduto">Remover Produto</button>
					</section>
				</section>

				<?php
					echo $this->form->input('a', array('value' => "Atendimento", 'type' => 'hidden'));
					echo $this->form->input('cliente_id', array('type' => 'hidden', 'value' => ''));
					?>

					<section class="span2">
						<?php
						echo $this->Form->input('Pedido.entrega_outro_local',array('class' => 'checkEndereco','label' => array('text' => 'Entrega em Outro Local? ', 'class' => 'control-label')));
						?>
					</section>
					<section class="span4 spanOutroEnd none">
						<?php
							echo $this->Form->input('Pedido.outro_endereco_entrega',array('type' => 'textarea','rows' => '3', 'cols' => '10', 'class' => 'outroEndereco','label' => array('text' => 'Endereço: ')));
						?>
					</section>
					<section class="span4">
						<?php
							echo $this->Form->input('Pedido.obs',array('type' => 'textarea','rows' => '3', 'cols' => '10', 'class' => '','label' => array('text' => 'Observações: ')));
						?>
					</section>
				<?php
					echo $this->form->end();
				?>


			<div class="row-fluid">
				<span id="label-spancliente"></span>
			</div>
			<div class="row-fluid">
				<span id="label-spantroco"></span>
			</div>
			<!-- TABELA -->
			<div class="row-fluid">
				<div class="area-tabela"  id="no-more-tables">
					<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" id="pedido" >
							<thead class="cf">
								<tr>
									<th >Cód</th>
									<th>Produto</th>
									<th class="th-header-normal">V.Und</th>
									<th class="th-header-normal">Qtde</th>
									<th class="th-header-normal">V. Total</th>
								</tr>
							</thead>
							<tbody>

								<tr id="linhaTotal">
									<td colspan="4" class="totalResponsivo">Total</td>
									<td id="totalPedido" data-title="Valor Total">00,00</td>
								</tr>

							</tbody>


						</table>
				</div>


				<div class="loaderAjax">
					 <?php echo $this->Html->image("ajaxloader.gif", array('class' => 'ajax'));?><small>Enviando Pedido</small>
				</div>
				<div class="erroqtde">
					<small>Erro: Não existem itens para enviar, adicione itens no pedido</small>
				</div>
				<div class="errotroco none">
					<small>Erro:Valor do troco inválido</small>
				</div>
			</div>
		 </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default" id="btn-salvar">Salvar</button>
	      </div>
	    </div>
	  </div>
	</div>

<script>
$(document).ready(function() {
	var itens=0;
	var urlInicio      = window.location.host;
	if(urlInicio=="localhost" ){
		urlInicio= "localhost/entregapp_sistema";	
	} 
	$.mask.definitions['~'] = '([0-9] )?';
	//$("#inp-qtde").mask("~~~");


	$('body').on('click', '#bt-imprimirEdit', function(event){
			event.preventDefault();
			var urlAction = 'http://localhost/epson/imprimir.php';
			var dadosForm = $("#printhidden").serialize();



			$.ajax({
					type: "POST",
					url: urlAction,
					data:  dadosForm,
					dataType: 'json',
					crossDomain: true,



					success: function(data){

							if(data == 'ok'){
									alert('imprimiu');
							}else{
									alert('Não Imprimiu');

							}



					},error: function(data){

							alert('Erro de conexão');

					}
			});
});


	$("#inp-qtde").keypress(function (e) {
	     //if the letter is not digit then display error and don't type anything
	     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        //display error message
	       // $("#errmsg").html("Digits Only").show().fadeOut("slow");
	               return false;
	    }
	   });
	$('input').focusin(function(){
		$('.errotroco').hide();
	});
	$('.custom-combobox-input').focusin(function(){
		$('.errotroco').hide();

	});
	$('.custom-combobox-input').focusout(function(){

	});
	$('body').on('focusout','.custom-combobox-input', function(){

		valorUnit= $(".selectProduto option:selected").attr('data-preco');

		if(typeof valorUnit !== 'undefined')
		{
			disabledProd = $(".selectProduto option:selected").attr('data-disabled');

			if(typeof disabledProd !== 'undefined'){
				if(disabledProd =='enabled'){
					$('#inp-preco').val(valorUnit);
					$('#inp-preco').priceFormat({
					    prefix: '',
					    centsSeparator: ',',
					    thousandsSeparator: '.'
					});
				}
			}

		}

	});

	$('body').on('click', '.checkEndereco', function(){
		if ($(this).is(':checked')) {
	    $('.spanOutroEnd').show();
	  } else {
	    $('.spanOutroEnd').hide();
	  }
	});
	$('body').on('click', '.ui-menu-item', function(){
		valorUnit= $(".selectProduto option:selected").attr('data-preco');

		if(typeof valorUnit !== 'undefined')
		{
			disabledProd = $(".selectProduto option:selected").attr('data-disabled');
			if(typeof disabledProd !== 'undefined'){
				if(disabledProd =='enabled'){
					$('#inp-preco').val(valorUnit);
					$('#inp-preco').priceFormat({
					    prefix: '',
					    centsSeparator: ',',
					    thousandsSeparator: '.'
					});
				}
			}
		}
	});
	$('select').change(function(){
		$('.errotroco').hide();
	});
	$('#selectPagamento').change(function(){

		resp= $('#selectPagamento').find(":selected").text();

		if(resp=='Dinheiro'){

			$('.divTrocolv').show();
			$("#trocoResposta").prop('disabled', false);
			$('.errotroco').hide();
		}else{
			$('.divTrocolv').hide();
			$('#trocoResposta').val(' ');
			$("#trocoResposta").prop('disabled', true);
			$('.divTroco').hide();
			$('#troco').val(' ');
			$("#troco").prop('disabled', true);
			$('.errotroco').hide();

		}
	});
	$('#trocoResposta').change(function(){

		resp= $(this).val();
		if(resp=='Sim'){

			$('.divTroco').show();
			$("#troco").prop('disabled', false);
			$('.errotroco').hide();
		}else{
			$('.divTroco').hide();
			$('#troco').val(' ');
			$("#troco").prop('disabled', true);
			$('.errotroco').hide();
		}
	});
	$('#btn-salvar').click(function(){
		validaFormulario();
		//$('#PedidoAddForm').submit();

	});
	function validaFormulario(){
		clienteid = $('#PedidoClienteId').val();
		selectPagamento = $('#selectPagamento').find(":selected").text();
		trocoResposta = $('#trocoResposta').val();
		itensAux = parseInt(itens);
		if(clienteid==''){
			alert('Selecione um cliente.');

		}else if(selectPagamento==''){
			alert('Selecione uma forma de pagamento.');
		}else if(itensAux == 0){
			alert('Insira pelo menos um item no pedido.');
		}else if(selectPagamento=='Dinheiro'){
			if(trocoResposta=='Sim'){
				valorTroco = $('#troco').val();
				if(valorTroco ==' '){
					alert('Insira o valor no campo troco para.');
				}else{


					$('#PedidoAddForm').submit();
				}
			}else{

				$('#PedidoAddForm').submit();
			}
		}else{

			$('#PedidoAddForm').submit();
		}



	}
	 $('#troco').focusout(function(){
	 	itensAux = parseInt(itens);
	 	if(itensAux != 0){
	 		$('.errotroco').hide();
	 		$('#label-spantroco').hide();
	 		var value = $('#totalPedido').html();

			value = value.substring(3);
			value = value.replace(".","");
			value = value.replace(",",".");

			value = parseFloat(value);

			troco= $('#troco').val();
			troco = troco.replace(".","");
			troco = troco.replace(",",".");

			troco = parseFloat(troco);


			if(troco > value){
				troco= troco -value;
				troco.toFixed(2);
				troco = toString();
				troco = troco.replace(".",",");
				$('#label-spantroco').html('Valor do troco R$ '+troco);
				$('#label-spantroco').show();

			}else{
				$('.errotroco').show();
				$('#troco').val('');
			}
	 	}else{
	 		$('.errotroco').show();
	 		$('#troco').val('');
	 	}

	 });
 $("#pedir").click(function(){
 		var sum = 0;
		$('.soma').each(function() {
			var value = $(this).text();
			value = value.substring(3);
			value = value.replace(",",".");
			value = value.replace(".","");
			value = parseFloat(value);
			if(!isNaN(value) && value.length != 0) {

	        	sum = sum + value;
	    	}


	    });


		if(sum !=0){

			$("#PedidoAddForm").submit();
			/*var urlAction = "<?php echo $this->Html->url(array("controller" => "Pedidos","action"=>"add"),true);?>";
			var dadosForm = $("#PedidoAddForm").serialize();

			$(".loaderAjax").show();
			$("#pedir").hide();

			$.ajax({
				type: "POST",
				url: urlAction,
				data:  dadosForm,
				dataType: 'json',

				success: function(data){
					$(".loaderAjax").hide();
					$("#pedir").show();
					$('.clone').remove();
					$('#totalPedido').text('00,00');
					console.debug(data);
				},error: function(data){
					console.debug(data);
					$("#pedir").show();
				}
			});*/


		}else{
			$('.erroqtde').show();
		}

 });
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
/*$("#responsive").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});*/


$('#addCliente').click(function(event){
	event.preventDefault();
	idcliente = $(".selectCliente option:selected").val();
	nomecliente = $(".selectCliente option:selected").text();
	$('#PedidoClienteId').val(idcliente);
	$('#combobox2').focus().val('');
	$('#label-spancliente').html('Nome do cliente: '+ nomecliente);
	setTimeout(function(){

		$('.ui-autocomplete-input').first().focus().val('');
	 }, 500);

});
var cont=0;
$('.removeProduto').click(function(event){
	event.preventDefault();
	$('.erroqtde').hide();
	if(itens > 0){
		cont=cont-1;
		itens=itens-1;
		$('#Itensdepedido'+cont+'ProdutoId').remove();
		$('#Itensdepedido'+cont+'Qtde').remove();
		$('#Itensdepedido'+cont+'ProdNome').remove();
		$('#Itensdepedido'+cont+'valor_unit').remove();
		$('#Itensdepedido'+cont+'valor_total').remove();
		$('#linha'+cont).remove();

	}
	var sum = 0;
	$('.soma').each(function() {
		var value = $(this).text();
		value = value.substring(3);
		value = value.replace(",",".");
		value = value.replace(".","");
		value = parseFloat(value);
		if(!isNaN(value) && value.length != 0) {

        	sum = sum + value;
        	$('#totalPedido').html(sum);
    	}


    });
	sum = sum.toString();
  	sum = sum.replace('.', ',');
    $('#totalPedido').html(sum);
    $('#totalPedido').priceFormat({
	    prefix: 'R$ ',
	    centsSeparator: ',',
	    thousandsSeparator: '.'
	});
});
$('.addProduto').click(function(event){
	event.preventDefault();


	disabledProd = $(".selectProduto option:selected").attr('data-disabled');


	if(disabledProd =='enabled'){
		$('.erroqtde').hide();

		$('#label-spantroco').html(' ');
		$('#label-spantroco').hide();
		$('#troco').val(' ');

	//	numero = $(".selectProduto option:selected").attr('data-codigo');
	//	numero = numero.substring(10);

		codigo = $(".selectProduto option:selected").attr('data-codigo');
		produto = $(".selectProduto option:selected").attr('data-nome');


		vlUnitarioAut = $("#inp-preco").val();
		vlUnitarioAut = vlUnitarioAut.replace(".","");
		vlUnitarioAut = vlUnitarioAut.replace(",",".");
		vlUnitario =  parseFloat(vlUnitarioAut);
		valor = $('#pedido tr').length;
		qtdeAux = $('#inp-qtde').val();

		qtde = parseFloat(qtdeAux);

		vlTotal=vlUnitario * qtde;
		vUProd= vlUnitario;
		vTot=vlTotal;
		vlUnitarioAux= vlUnitario.toFixed(2);
		vlUnitarioAux= vlUnitarioAux.toString();

		vlUnitario = vlUnitarioAux.replace('.', ',');


		vlTotalAux = vlTotal.toFixed(2);
		vlTotalAux = vlTotalAux.toString();
		vlTotal =vlTotalAux.replace('.',',');

		if(qtde < 0 || qtde =='' || isNaN(qtde)){
			$('.erroqtde').show();
		}else{

		itens=itens+1;

		$("#pedido").append('<tr class="clone" id="linha'+cont+'"><td  data-title="Código">'+codigo+'</td><td  data-title="Produto">'+produto+'</td><td  data-title="Vl. Unit">'+'R$ '+vlUnitario+'</td><td  data-title="Qtde">'+qtde+'</td><td  data-title="Total" class="soma" id="soma'+cont+'">'+vlTotal+'</td></tr>');
		$('#soma'+cont).priceFormat({
		    prefix: 'R$ ',
		    centsSeparator: ',',
		    thousandsSeparator: '.'
		});


		var sum = 0;
		$('.soma').each(function() {
			var value = $(this).text();
			value = value.substring(3);
			value = value.replace(",",".");
			value = value.replace(".","");
			value = parseFloat(value);
			if(!isNaN(value) && value.length != 0) {

	        	sum = sum + value;
	    	}
		  $("#linhaTotal").remove();
		  $("#linhaTotal-responsivo").remove();


	   	 });
			sum = sum.toString();
		  	sum = sum.replace('.', ',');




		  	$("#pedido").append('<tr id="linhaTotal"><td colspan="4" class="totalResponsivo">Total</td><td id="totalPedido" data-title="Valor Total">'+sum+'</td>');
			$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][produto_id]" id="Itensdepedido'+cont+'ProdutoId" value="'+codigo+'">');
			$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][qtde]" id="Itensdepedido'+cont+'Qtde" value="'+qtde+'">');
			$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][valor_unit]" id="Itensdepedido'+cont+'valor_unit" value="'+vUProd+'">');
			$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][valor_total]" id="Itensdepedido'+cont+'valor_total" value="'+vTot+'">');
			$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][prodnome]" id="Itensdepedido'+cont+'ProdNome" value="'+produto+'">');

			vUProd= 0;
			vTot=0;
			$('#totalPedido').priceFormat({
			    prefix: 'R$ ',
			    centsSeparator: ',',
			    thousandsSeparator: '.'
			});
			$('#totalPedido-responsivo').priceFormat({
			    prefix: 'R$ ',
			    centsSeparator: ',',
			    thousandsSeparator: '.'
			});
			cont= cont + 1;
		}
		$('#inp-qtde').val('1');

		$('#inp-preco').val('');

		$('#combobox').focus().val('');
		$(".selectProduto option:selected").prop("selected", false);
		setTimeout(function(){

			$('.ui-autocomplete-input').last().focus().val('');
		}, 500);
	}

});

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

  	$('body').on('keypress', '.ui-autocomplete-input', function(){
  		setTimeout(function(){
			$( ".ui-corner-all a:contains('Indisponível')" ).css( "background-color", "#E82F00;");
			$( ".ui-corner-all a:contains('Indisponível')" ).addClass( "Indisponivel");
		},300);
  	});

  	$('body').on('keyup', '.ui-autocomplete-input', function(){
  		setTimeout(function(){
			$( ".ui-corner-all a:contains('Indisponível')" ).css( "background-color", "#E82F00;");
		},300);

  	});

});




</script>
