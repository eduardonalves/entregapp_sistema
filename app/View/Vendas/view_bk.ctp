<div class="pedidos view">
	<h4 class="h3content"><span class="h3previsao">Previsão de Entrega</spam><div id="counter"></div></h4>
	<dl>
		<dt><?php echo __('Código: '); ?></dt>
		<dd>
			<?php echo h($pedido['Atendimento']['codigo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Data: '); ?></dt>
		<dd>
			<?php
					$dataAntiga = $pedido['Pedido']['data'];
					$novaData = date("d/m/Y", strtotime($dataAntiga));
					
				?>
			<?php echo h($novaData); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Hora: '); ?></dt>
		<dd>
			<?php echo h($pedido['Pedido']['hora_atendimento']); ?>
			
			&nbsp;
		</dd>
		<dt><?php echo __('Cliente: '); ?></dt>
		<dd>
			<?php echo h($pedido['Cliente']['nome']); ?>
			&nbsp;
		</dd>
		
		<dt><?php echo __('Valor: '); ?></dt>
		<dd>
			<?php
			
				echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); 	
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Forma de Pagamento: '); ?></dt>
		<dd>
			<?php echo h($pedido['Pagamento']['tipo']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Situação do Pagamento: '); ?></dt>
		<dd>
			<?php echo h($pedido['Pagamento']['status']); ?>
			<button type="button" class="btn btn-small" id="add-pagameto">Informar Pagamento</button>
			&nbsp;
		</dd>
		
		<dt><?php echo __('Posição na Fila: '); ?></dt>
		<dd>
			<?php echo h($pedido['Pedido']['posicao_fila']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status: '); ?></dt>
		<dd id="statusPedido">
			<?php echo h($pedido['Pedido']['status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Entregador: '); ?> <i class="icon-plus-sign" id="mostraEntregador"></i></dt>
		<dd id="entregadorLinha">
			
			<span id="nomeEntregador"><?php echo h($pedido['Entregador']['nome']); ?></span>
			<div id="diventregador">
				<form class="form-inline" id="entregador" >
				
					<select class="input-small" name="data[Pedido][entregador_id]">
						<option value=""></option>
						<?php
						foreach($entregadores as $entregador){
							echo '<option value="'.$entregador['Entregador']['id'].'">'.$entregador['Entregador']['nome'].'</option>';
							
						}
						
						?>
					</select>
					
					<button type="button" class="btn btn-small" id="add-entregador">Salvar</button>
				</form>
			</div>
			
			&nbsp;
		</dd>
		<dt><?php echo __('Avaliação: '); ?></dt>
		<dd id="avaliarPedido">
			<span class="none" id="avalPedido"><?php echo h($pedido['Pedido']['avaliacao']); ?></span>
			&nbsp;
		</dd>
	</dl>
	<?php if (!empty($pedido['Itensdepedido'])): ?>
		<table  class="">
			<tbody>
				<tr>
					
					<th><?php echo __('Código'); ?></th>
					<th><?php echo __('Produto'); ?></th>
					<th><?php echo __('Vl. Unit'); ?></th>
					<th><?php echo __('Qtd'); ?></th>
					<th><?php echo __('Vl. Total'); ?></th>
					
					
				</tr>
				<?php foreach ($pedido['Itensdepedido'] as $itensdepedido): ?>
					<tr>
						
						<td><?php echo $itensdepedido['produto_id']; ?></td>
						<td><?php echo $itensdepedido['prodNome']; ?></td>
						<td> <?php echo 'R$ ' . number_format($itensdepedido['valor_unit'], 2, ',', '.'); ?></td>
						<td><?php echo $itensdepedido['qtde']; ?></td>
						<td><?php echo 'R$ ' . number_format($itensdepedido['valor_total'], 2, ',', '.'); ?></td>
						
						
					</tr>
					<tr id="linhaTotalPedido">
					<td colspan="4">Total</td>
					<td id="valorTotalPedido"><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	
	

	
	
	
</div>
<div class="checkout-wrap">
	  <ul class="checkout-bar">
	
	    <li class="visited first progresso" id="progConfirmar">
	      <a href="#" id="lkConfirmar">Confirmar</a>
	    </li>
	
	    <li class="previous visited progresso" id="progPreparar"><a href="#" id="lkPreparar">Preparar</a></li>
	
	    <li class="active progresso" id="progAguardando"><a href="#" id="lkAguardando">Separar p/ Entrega</a></li>
	
	    <li class="next progresso" id="progTransito"><a href="#" id="lkTransito">Enviar</a></li>
	
	    <li class="progresso" id="progEntregue"><a href="#" id="lkEntregue">Entregar</a></li>
	
	  </ul>
</div>
<br />
<br />
<br />
<br />

<button type="button" class="btn" id="loadChat">Abrir Chat</button>
<button type="button" class="btn" id="cancelarPedidoModal">Cancelar Pedido</button>
	<div id="responsiveModal2" class="modal hide fade" tabindex="-1">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3>Pedido</h3>
		</div>
	  	<div class="modal-body" id="modalEntregador">
			<div class="row-fluid">
				<div id="loadModalPedido">
					
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn">Fechar</button>
			
			
		 </div>
	</div>
	
	
<?php 
 	echo $this->Form->create('Pedido', array('id' => 'statusPedidoForm'));
	echo $this->Form->input('id',array('type' => 'hidden', 'value' => $pedido['Pedido']['id'],'id' => 'idpedido'));	
	echo $this->Form->end();
?>
<script>

	var urlInicio = window.location.host;
	urlInicio= urlInicio;
	if(urlInicio=="localhost" ){
		urlInicio= "localhost/entregapp_sistema";	
	} 
	var scorePedido = $('#avalPedido').text();
	var idpedido= $('#idpedido').val();
	var showOrHide= false;
	$('#avaliarPedido').raty({
	 starHalf   :'http://'+urlInicio+'/img/star-half.png',
	  starOff    : 'http://'+urlInicio+'/img/star-off.png',
	  starOn     : 'http://'+urlInicio+'/img/star-on.png',	
		score: scorePedido, 
	});
	
	$('#fecharChat').click(function(event){
		$( "#responsiveModal" ).modal('show');
	});
	$('#loadChat').click(function(event){
		
		event.preventDefault();
		var idpedido= $('#idpedido').val();
		$('#idpedidomsg').val(idpedido);
		$("#modalChatcontent").load('http://'+urlInicio+'/Mensagens/ler/'+idpedido+'', function(){
			$( "#responsiveModal" ).modal('hide');
			$( "#modalChat" ).modal('show');
			
			
		});	
		mensagemlida(idpedido);
	});
	
	
	
	$('#add-pagameto').click(function(event){
		event.preventDefault();
		
		$( "#responsiveModal" ).modal('hide');
		$( "#modalpagamento" ).modal('show');	
	});
	$('#cancelarPedidoModal').click(function(event){
		event.preventDefault();
		var idpedido= $('#idpedido').val();
		$( "#responsiveModal" ).modal('hide');
		$( "#modalCancelar" ).modal('show');	
	});
	
	$('#cancelarPedido').click(function(event){
		event.preventDefault();
		var idpedido= $('#idpedido').val();
		cancelarpedido(idpedido);
	});
	
	myVar = setInterval(function(){verificaMensagem();}, 5000);
	setTimeout(function(){
						var objDiv = document.getElementById("modalChatcontent");
						objDiv.scrollTop = objDiv.scrollHeight;
					}, 1000);
					
					
					
	function verificaMensagem(){
		
		
		ultimaMsg= $('#ultMsg').val();
		idpedido = $('#idpedido').val();
		var url='http://'+urlInicio+'/Mensagens/view?&ult='+ultimaMsg+'&idpedido='+idpedido;
		
		 $.ajax({
				type: "GET",
				url: url,
				dataType: 'json',
				
			
			
			
			success: function(data){
				
				contadorScroll=0;
				acumuladorTexto="";
				$.each(data, function(i, resultados){
		     		console.log(data);
					$.each(data, function(z, mensagen){
						
						senderuser = mensagen.User.id;
						sendercliente = mensagen.Cliente.id;
						sender = mensagen.Mensagen.sender;
						nomeMsg ="";
						classemsg="";
						if(sender == 0){
							nomeMsg = mensagen.User.username;
							classemsg ="triangle-isosceles";
							classUser="spanAtendente";
						}else{
							nomeMsg = mensagen.Cliente.username;
							classemsg ="triangle-isosceles top";
							classUser="spanUsuario";
						}
						
						acumuladorTexto= acumuladorTexto+'<p class="'+classemsg+'" data-msgid="'+mensagen.Mensagen.id+'" ><span class="'+classUser+'">'+nomeMsg+'</span> '+mensagen.Mensagen.msg+'</p>';
						
			
						//$("#chatZone").scrollTop($("#chatZone").prop("scrollHeight"));
						
						//$("html, body").animate({ scrollTop: $(document).height() }, "slow");
						//$("html, body").animate({ scrollTop: $(document).height() }, "slow");
						//$("#chatZone").getNiceScroll().resize();
						//$("#chatZone").niceScroll({cursorcolor:"#FF5C0A" });
						
						$('#ultMsg').val(mensagen.Mensagen.id);
						
					});
				});
				if(acumuladorTexto !=''){
					$('#modalChatcontent').append(acumuladorTexto);	
					
					setTimeout(function(){
						
						
						var objDiv = document.getElementById("modalChatcontent");
						objDiv.scrollTop = objDiv.scrollHeight;
					}, 500);
				}
				
						
					
			},error: function(data){
				//criar tratatmento de erros
				//$(".erroconexao").popup( "open" );	
				
			}
			});
	}

	$('#mostraEntregador').click(function(event){
		event.preventDefault();
		
		
		
		
		
		if ( showOrHide == true ) {
		  $( "#diventregador" ).hide();
		  showOrHide = false;
		  $('#mostraEntregador').removeClass('icon-minus-sign');
		  $('#mostraEntregador').addClass('icon-plus-sign');
		  
		} else if ( showOrHide == false ) {
		  $( "#diventregador" ).show();
		  showOrHide = true;
		  $('#mostraEntregador').removeClass('icon-plus-sign');
		  $('#mostraEntregador').addClass('icon-minus-sign');
		 
		}
	});
	$('#add-entregador').click(function(event){
		event.preventDefault();
		
		idpedido= $('#idpedido').val();
		confirmarentregador(idpedido);
	})
	$('#lkConfirmar').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		confirmaAtendimento(idpedido);
	});
	
	$('#progPreparar').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		confirmarpreparo(idpedido);
	});
	
	$('#progAguardando').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		confirmarseparacao(idpedido);
		
	});
	
	$('#progTransito').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		confirmarenvio(idpedido);
	});
	
	$('#progEntregue').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		confirmarentrega(idpedido);
	});
	var progresso = $('#statusPedido').text();
	progresso = progresso.substring(4, 10);
	$('#chat').submit(function(event){
		event.preventDefault();
		
		contmsg= $('#enviarinpt').val();
		if(contmsg !=''){
			enviaMensagem();
			$('#enviarinpt').val('');
		}
		
	});
	function statusAberto(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('active');
		
		$('#lkConfirmar').html('Confirmar');
		$('#lkPreparar').html('Preparar');
		$('#lkAguardando').html('Separar p/ Entrega');
		$('#lkTransito').html('Enviar');
		$('#progEntregue').html('Entregar')
	}
	
	
	function statusConfirmado(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('visited');
		$('#progConfirmar').addClass('first');		
		$('#progPreparar').addClass('active');
		$('#progAguardando').addClass('next');
		
		$('#lkConfirmar').html('Confirmado');
		$('#lkPreparar').html('Preparar');
		$('#lkAguardando').html('Separar p/ Entrega');
		$('#lkTransito').html('Enviar');
		$('#progEntregue').html('Entregar');
	}
	function statusPronto(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('visited');
		$('#progConfirmar').addClass('first');		
		$('#progPreparar').addClass('visited');
		$('#progAguardando').addClass('active');
		
		$('#lkConfirmar').html('Confirmado');
		$('#lkPreparar').html('Pronto');
		$('#lkAguardando').html('Separar p/ Entrega');
		$('#lkTransito').html('Enviar');
		$('#progEntregue').html('Entregar');
		
		
	}
	
	function statusSeparado(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('visited');
		$('#progConfirmar').addClass('first');		
		$('#progPreparar').addClass('visited');
		$('#progAguardando').addClass('visited');
		$('#progTransito').addClass('active');
		
		$('#lkConfirmar').html('Confirmado');
		$('#lkPreparar').html('Pronto');
		$('#lkAguardando').html('Separado');
		$('#lkTransito').html('Enviar');
		$('#progEntregue').html('Entregar');
	}
	
	function statusTransito(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('visited');
		$('#progConfirmar').addClass('first');		
		$('#progPreparar').addClass('visited');
		$('#progAguardando').addClass('visited');
		$('#progTransito').addClass('visited');
		$('#progEntregue').addClass('active');
		
		$('#lkConfirmar').html('Confirmado');
		$('#lkPreparar').html('Pronto');
		$('#lkAguardando').html('Separado');
		$('#lkTransito').html('Enviado');
		$('#progEntregue').html('Entregar');
	}
	
	function statusEntrega(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		$('#progConfirmar').addClass('visited');
		$('#progConfirmar').addClass('first');		
		$('#progPreparar').addClass('visited');
		$('#progAguardando').addClass('visited');
		$('#progTransito').addClass('visited');
		$('#progEntregue').addClass('visited');
		
		$('#lkConfirmar').html('Confirmado');
		$('#lkPreparar').html('Pronto');
		$('#lkAguardando').html('Separado');
		$('#lkTransito').html('Enviado');
		$('#progEntregue').html('Entregue');
	}
	
	function statusCancelado(){
		$('.progresso').removeClass('visited');
		$('.progresso').removeClass('first');
		$('.progresso').removeClass('previous');
		$('.progresso').removeClass('next');
		$('.progresso').removeClass('active');
		
		$('#lkConfirmar').html('Confirmar');
		$('#lkPreparar').html('Preparar');
		$('#lkAguardando').html('Separar p/ Entrega');
		$('#lkTransito').html('Enviar');
		$('#progEntregue').html('Entregar')
	}



	function confirmaAtendimento(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarpedido/'+idpedido;
			var dadosFormulario = $("#LoteIndexForm").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						
						statusConfirmado();
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}	
	
	function confirmarpreparo(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarpreparo/'+idpedido;
			var dadosFormulario = $("#LoteIndexForm").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						statusPronto();
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	function confirmarseparacao(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarseparacao/'+idpedido;
			var dadosFormulario = $("#LoteIndexForm").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						statusSeparado();
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	function confirmarenvio(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarenvio/'+idpedido;
			var dadosFormulario = $("#LoteIndexForm").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						statusTransito();
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	function confirmarentrega(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarentrega/'+idpedido;
			var dadosFormulario = $("#LoteIndexForm").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						statusEntrega();
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	function confirmarentregador(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/confirmarentregador/'+idpedido;
			var dadosFormulario = $("#entregador").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#nomeEntregador').html(data.Entregador.nome);
						$('#linhaEntregadorNome'+idpedido).html(data.Entregador.nome);
						 $('#mostraEntregador').removeClass('icon-plus-sign');
		 				 $('#mostraEntregador').addClass('icon-minus-sign');
						 showOrHide = false;
						 $( "#diventregador" ).hide();
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	function cancelarpedido(idpedido){ 
		
			var url='http://'+urlInicio+'/Pedidos/cancelarpedido/'+idpedido;
			var dadosFormulario = $("#formCancelar").serialize();
			 $.ajax({
					type: "POST",
					url: url,
					data:  dadosFormulario,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						
						$('#statusPedido').html(data.Pedido.status);
						$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
						
						$( "#modalCancelar" ).modal('hide');
						$( "#responsiveModal" ).modal('show');	
						$('#motivoCancel').val('');
					},error: function(data){
						$( "#modalCancelar" ).modal('hide');
						$( "#responsiveModal" ).modal('show');	
					 	
					}
					
				});
				
				
	}
	
	function mensagemlida(idpedido){ 
		
			var url='http://'+urlInicio+'/Mensagens/lida/'+idpedido;
			
			 $.ajax({
					type: "POST",
					url: url,
					dataType: 'json',
					type: "POST",		
					success: function(data){
						
						$("#loadAtivas").load('http://'+urlInicio+'/Pedidos/mensagensativas/', function(){});
						
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}


	function enviaMensagem(){
		
	
		var urlAction = "http://appedido.lojacriandoart.com.br/Mensagens/add.json";
		var dadosForm = $("#chat").serialize();

		
		
		$.ajax({
			type: "POST",
			url: urlAction,
			data:  dadosForm,
			dataType: 'json',
			crossDomain: true,
			
			
			
			success: function(data){
				//verificaMensagem();
				//ultimaMsg =data.ultimomensagen.Mensagen.id; 
				 /*$('#chatZone').append('<div class="chatmsg" data-msgid="'+data.ultimomensagen.Mensagen.id+'"><b>'+data.ultimomensagen.Cliente.username+'</b>: '+data.ultimomensagen.Mensagen.msg+'<br/></div>');
			
				
				
				
				//$("#chatZone").scrollTop($("#chatZone").prop("scrollHeight"));
				
				$('#chatZone').animate(
				{
					scrollTop: $('#chatZone').prop("scrollHeight"),
					
				}, 500);
				
				$("#chatZone").getNiceScroll().resize();
				$("#chatZone").niceScroll({cursorcolor:"#CCC" }); */
				
			},error: function(data){
				//criar tratatmento de erros
				
				
			}
			});
	
		
		
		
	}
	if(progresso == 'Em Abe'){
		statusAberto();
		
	}else if(progresso == 'Confir'){
		statusConfirmado();
	}else if(progresso == 'Pronto'){
		statusPronto();
	}else if(progresso == 'Separa'){
		statusSeparado();
	}else if(progresso == 'Em Trâ'){
		statusTransito();
	}else if(progresso == 'Entreg'){
		statusEntrega();
	}else if(progresso == 'Cancel'){
		statusCancelado();
	
	}
	
	
					
</script>

