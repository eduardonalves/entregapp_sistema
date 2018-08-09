<?php
	$this->response->header('Access-Control-Allow-Origin','*');
	
?>

<style>
	#modalBodyCadastrarMesa{height: 130px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}
	th{
		background-color:#E87C00;
		color:#FFFFFF; 
		
	}
table {margin-top: 10px; font-size:80%;}

.modal {
   position: absolute;
}

.table-striped tbody tr:nth-child(odd) td {
   background-color: #E8E8E8;
}

.table-striped tbody tr:nth-child(even) td {
   background-color: #F9F9F9;
}
.btn-comprar{margin-top: 15px;}
.layerslide{
	background-color: rgba(0, 0, 0, 0.8);
	color:white;
	
	
	max-height: 30%;
	overflow:hidden;
	padding: 9px;
	margin-top:15px;
	
}

.slider{
	padding: 1px;
	
}
.accordion-group {border: 2px solid #FFFFFF; 

}
#pedir{float:left;}
.loaderAjax{color:#FFFFFF; float: left; margin-left: 8px; margin-top: 7px; display:none;}
.loaderAjax small{margin-left:5px;}
.erroqtde{color:#FFFFFF; float: left; margin-left: 8px; margin-top: 7px; display:none;}
</style>


	
	
	
	
	<div class="accordion" id="accordion2">
	  <div class="accordion-group">
	    <div class="accordion-heading">
	      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
	        Grupo de itens colapsáveis #1
	      </a>
	    </div>
	    <div id="collapseOne" class="accordion-body collapse in">
	      <div class="accordion-inner">
	       <div class="row-fluid">
				<div id="owl-example" class="owl-carousel">
				  	<div class= "slider">
				  		
					  	<?php echo $this->Html->image("pizza1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="inputAdd1" type="number"  value="1">
						  	<button class="btn addProduto" type="button" data-codigo="1" data-produto="hamburger" data-vlu="15,99" id="btnAddProd1">Comprar</button>
	 						 
						</div>
				  </div>
				  <div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  </div>
				  <div class="slider">
				  		
					  	<?php echo $this->Html->image("pizza1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  </div>
				   <div class="slider">
				  		
					  	<?php echo $this->Html->image("pizza1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
							
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  </div>
				 <div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
				</div>
			</div>
	      </div>
	    </div>
	  </div>
	  <div class="accordion-group">
	    <div class="accordion-heading">
	      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
	        Grupo de itens colapsáveis #2
	      </a>
	    </div>
	    <div id="collapseTwo" class="accordion-body collapse">
	      <div class="accordion-inner">
	        	<div id="owl-example2" class="owl-carousel">
	        		<div class="slider">
				  		
					  	<?php echo $this->Html->image("pizza1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("pizza1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
					<div class="slider">
				  		
					  	<?php echo $this->Html->image("hamb1.jpg", array('class' => 'img-rounded absolute offset1'));?>
						<div class="layerslide img-rounded">
							<h4>Titulo</h4>
							 <small>
								Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Manduma pindureta quium dia nois paga. Sapien in monti palavris qui num significa nadis i pareci latim. Interessantiss quisso pudia ce receita de bolis, mais bolis eu num gostis.
							</small>
							<button class="btn btn-small" type="button">Mais</button>
						</div>
						
						<div class="input-append btn-comprar">
	  						<input class="span2 offset6" id="appendedInputButtons" type="number"  value="1">
						  	<button class="btn" type="button">Comprar</button>
	 						 
						</div>
				  	</div>
				</div>
	      </div>
	    </div>
	  </div>
	</div>
	<div class="row-fluid">
		
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" id="pedido">
					<thead>
						<tr>
							
							<th class="span1">Cód</th>
							<th>Produto</th>
							<th class="span2">V.Und</th>
							<th class="span1">Qtde</th>
							<th class="span2">V. Total</th>
						</tr>
					</thead>
					<tbody>
						
						<tr id="linhaTotal">
							<td colspan="4">Total</td>
							<td id="totalPedido">00,00</td>
						</tr>
						
					</tbody>
				</table>
				
				<button class="btn" type="button" id="pedir">Enviar Pedido</button>
				<div class="loaderAjax">
					 <?php echo $this->Html->image("ajaxloader.gif", array('class' => 'ajax'));?><small>Enviando Pedido</small>
				</div>
				<div class="erroqtde">
					<small>Erro: Não existem itens para enviar, adicione itens no pedido</small>
				</div>
				
		</div>
	</div>	
	<?php
		echo $this->form->create('Pedido');
		if(isset($codigo)){
			echo $this->form->input('a', array('value' => $codigo, 'type' => 'hidden'));
		}
		
	
		echo $this->form->end();
	?>
<script>
$(document).ready(function() {
var itens=0;
 $(".owl-carousel").owlCarousel({
 	navigation : true,
	pagination: false
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
var cont=0;
$('.addProduto').click(function(){
	$('.erroqtde').hide();
	itens=itens+1;
	numero = $(this).attr('id');
	numero = numero.substring(10);
	
	codigo = $(this).attr('data-codigo');
	produto = $(this).attr('data-produto');
	vlUnitarioAut = $(this).attr('data-vlu');
	vlUnitarioAut = vlUnitarioAut.replace(",",".");
	vlUnitario =  parseFloat(vlUnitarioAut);
	valor = $('#pedido tr').length;
	qtdeAux = $('#inputAdd'+numero).val();
	$('#inputAdd'+numero).val(1);
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
	  	  
    });
		sum = sum.toString();
	  	sum = sum.replace('.', ',');
		
		
	  	$("#pedido").append('<tr id="linhaTotal"><td colspan="4">Total</td><td id="totalPedido">'+sum+'</td></tr>');
		$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][produto_id]" id="Itensdepedido'+cont+'ProdutoId" value="'+codigo+'">');
		$("#PedidoAddForm").append('<input class="clone" type="hidden" name="data[Itensdepedido]['+cont+'][qtde]" id="Itensdepedido'+cont+'Qtde" value="'+qtde+'">');
		$('#totalPedido').priceFormat({
		    prefix: 'R$ ',
		    centsSeparator: ',',
		    thousandsSeparator: '.'
		});
		cont= cont + 1;
	}	
		
	});
});


</script>
