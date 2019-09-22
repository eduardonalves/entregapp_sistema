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
			<section class="row-fluid">
			
				<section class="span3">
					<label>Cliente: </label>
					<br>
					<select class="selectCliente input-default" id="combobox2">
						<option value="">Selecione um...</option>
						<?php
							foreach ($clientes as $cliente) {
								echo '<option value="'.$cliente['Cliente']['id'].'" >'.$cliente['Cliente']['nome'].' - '.$cliente['Cliente']['telefone'].'</option>';
							}
						?>						
					</select>	
					
					<button class="btn btn-success addCliente" id="addCliente">Selecionar</button> 

				</section>
				<section class="span3">
					<label>Produto: </label>
					<br>
					  <select class="selectProduto input-default" id="combobox">
						<option value="">Selecione um...</option>
						<?php
							foreach ($produtos as $produto) {
								echo '<option value="'.$produto['Produto']['nome'].'" data-codigo="'.$produto['Produto']['id'].'" data-preco="'.$produto['Produto']['preco_venda'].'" data-nome="'.$produto['Produto']['nome'].'">'.$produto['Produto']['nome'].'</option>';
							}
						?>
						
					  </select>
				</section>
				<section class="span3">
					<label for="inp-qtde">Insira a quantidade: </label>
					<input type="number" id="inp-qtde" class="input-default">	
				</section>
				
				<section class="span2">
					<label>Levar Troco? </label>
					<br>
						<select name="data[Pedido][trocoresposta]" id="trocoResposta" class="input-default">	
							<option value="Não">Não</option>
							<option value="Sim">Sim</option>
						</select>
				</section>
			</section>	
			
			<section class="row-fluid" style="margin-top:10px;margin-bottom:30px;">
				
				<section class="span3" style="display:none;" id="divTroco">
					<label>Troco para? </label>
					<input type="text" class="input-default" name="data[Pedido][trocovalor]" id="troco">	
				</section>	
				
				<section class="span3" >
					<label>Forma de Pagamento: </label>
					<br>
					  <select name="data[Pedido][pagamento_id]"class="selectProduto input-default" id="combobox">
						<option value="0"></option>
						<?php
							foreach ($pagamentos as $pagamento) {
								echo '<option value="'.$pagamento['Pagamento']['id'].'" >'.$pagamento['Pagamento']['tipo'].'</option>';
							}				
						?>						
					  </select>
				</section>	
				
				<section class="span2" >
					<button class="btn btn-success addProduto" id="addProduto" style=" margin-top: 25px;">Adicionar Produto</button>
					<?php
						echo $this->form->input('a', array('value' => "Atendimento", 'type' => 'hidden'));
						echo $this->form->input('cliente_id', array('type' => 'hidden', 'value' => ''));
					?>
				</section>
				
				<section class="span3" >
					<span id="label-spancliente"></span>
					<br>
					<span id="label-spantroco"></span>		
				</section>		
			</section>		

			<?php echo $this->form->end(); ?>
	
			<!-- TABELA -->
			<div class="row-fluid">
				<div class="area-tabela">
					<table class="table-action" id="pedido" >
							<thead>
								<tr>							
									<th >Cód</th>
									<th>Produto</th>
									<th class="th-header-normal">V.Und</th>
									<th class="th-header-normal">Qtde</th>
									<th class="th-header-normal">V. Total</th>
								</tr>
							</thead>
							<tbody>
								
								
						
							</tbody>
							
							<tfoot id="totalRodape">
								<tr id="linhaTotal">
									<th colspan="4">Total</th>
									<th id="totalPedido">00,00</th>
								</tr>
								
								<tr id="linhaTotal-responsivo">
									<th>Total</th>
									<th id="totalPedido-responsivo">00,00</th>
								</tr>				
							</tfoot>
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
		</section>
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
	
	$('#trocoResposta').change(function(){
		
		resp= $(this).val();
		if(resp=='Sim'){
			$('#divTroco').show();
			$("#troco").prop('disabled', false);
		}else{
			$('#divTroco').hide();
			$('#troco').val(' ');
			$("#troco").prop('disabled', true);
		}
	});	
	$('#btn-salvar').click(function(){
		$('#PedidoAddForm').submit();

	});
	 $('#troco').focusout(function(){
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
				$('#label-spantroco').html('Valor do troco R$ '+troco);
				$('#label-spantroco').show();

			}else{
				$('.errotroco').show();
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
$('.addProduto').click(function(event){
	event.preventDefault();
	$('.erroqtde').hide();
	itens=itens+1;
	numero = $(".selectProduto option:selected").attr('data-codigo');
	numero = numero.substring(10);
	
	codigo = $(".selectProduto option:selected").attr('data-codigo');
	produto = $(".selectProduto option:selected").attr('data-nome');
	vlUnitarioAut = $(".selectProduto option:selected").attr('data-preco');
	vlUnitarioAut = vlUnitarioAut.replace(",",".");
	vlUnitario =  parseFloat(vlUnitarioAut);
	valor = $('#pedido tr').length;
	qtdeAux = $('#inp-qtde').val();
	
	qtde = parseFloat(qtdeAux);
	vlTotal=vlUnitario * qtde;
	
	
	vlUnitarioAux= vlUnitario.toString();
	vlUnitario = vlUnitarioAux.replace('.', ',');
	
	vlTotalAux = vlTotal.toString();
	vlTotal =vlTotalAux.replace('.',',');
	
	if(qtdeAux <=0){
		$('.erroqtde').show();
	}else{
	
	$("#pedido").append('<tr class="clone" id="linha'+cont+'"><td>'+codigo+'</td><td>'+produto+'</td><td>'+'R$ '+vlUnitario+'</td><td>'+qtde+'</td><td class="soma" id="soma'+cont+'">'+vlTotal+'</td></tr>');
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
		
		
	  	$("#totalRodape").append('<tr id="linhaTotal"><td colspan="4">Total</td><td id="totalPedido">'+sum+'</td></tr>');
	  	$("#totalRodape").append('<tr id="linhaTotal-responsivo"><td>Total</td><td id="totalPedido-responsivo">'+sum+'</td></tr>');
		$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][produto_id]" id="Itensdepedido'+cont+'ProdutoId" value="'+codigo+'">');
		$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][qtde]" id="Itensdepedido'+cont+'Qtde" value="'+qtde+'">');
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
	$('#inp-qtde').val('');
		
	$('#combobox').focus().val('');
	
	setTimeout(function(){ 
		
		$('.ui-autocomplete-input').last().focus().val('');
	 }, 500);
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

    $("#combobox").combobox({ 
        select: function (event, ui) { 
            
            console.log($(this).val());
        } 
    });

    $("#combobox2").combobox({ 
        select: function (event, ui) { 
            
            console.log($(this).val());
        } 
    });
  });



});



  
</script>
