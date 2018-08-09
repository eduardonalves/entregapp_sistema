<!-- Modal -->
<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Editar Pedidos</h4>
        <h5 class="h3content"><span class="h3previsao">Previsão de Entrega</span><div id="counter"></div></h5>
      </div>
      <div class="modal-body">
			<?php echo $this->Form->create('Pedido',array('class' => 'form-inline'));	?>
			
				<?php echo $this->Form->input('id',array('readonly' => 'readonly','default'=> $pedido['Pedido']['id'],'type'=>'hidden','class' => 'input-large idView','id'=>'idView','label' => false));					?>
			
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('Atendimento.codigo',array('readonly' => 'readonly','default'=> $pedido['Atendimento']['codigo'],'type'=>'text','class' => 'input-large codigoView','id'=>'codigoView','label' => array('text' => 'Código:')));					?>
				</div>
				<div class="form-group  form-group-lg">
					<?php

					$data =$pedido['Pedido']['data'];
			        $data = implode("-",array_reverse(explode("/",$data)));
			        $pedido['Pedido']['data']= $data;

					$dataPedido=explode('-', $pedido['Pedido']['data']) ;
					$pedido['Pedido']['data'] = $dataPedido['2'].'/'.$dataPedido['1'].'/'.$dataPedido['0'];
							

					?>
					<?php echo $this->Form->input('data',array('readonly' => 'readonly','default'=> $pedido['Pedido']['data'],'class' => 'input-large dataView','id'=>'dataView','type'=>'text','label' => array('text' => 'Data:')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('hora_atendimento',array('readonly' => 'readonly','default'=> $pedido['Pedido']['hora_atendimento'],'type'=>'text','class' => 'input-large horaView','id'=>'horaView','label' => array('text' => 'Hora:')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('cliente',array('readonly' => 'readonly','default'=> $pedido['Cliente']['nome'],'type'=>'text','class' => 'input-large nomeView','id'=>'nomeView','label' => array('text' => 'Cliente:')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('valor',array('readonly' => 'readonly','default'=> $pedido['Pedido']['valor'],'type'=>'text','class' => 'input-large valorView','id'=>'valorView','label' => array('text' => 'Valor:')));?>
				</div>
				<?php
				
					$pag = array();
					$pag[' ']=''; 
					foreach ($pagamentos as $pagamento) {
						$pag[$pagamento['Pagamento']['id']]	=$pagamento['Pagamento']['tipo'];
					
					}
				?>
				<?php
					if($pedido['Pedido']['pagamento_id'] == '' || $pedido['Pedido']['pagamento_id'] == 0  || $autorizacao['Autorizacao']['pedidos']=='a'){

					
				?>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('pagamento_id',array('options'=> $pag,'type'=>'select','class' => 'input-large pagamentoView','id'=>'pagamentoView','label' => array('text' => 'Pagamento:')));?>
					</div>
				<?php
					}else{
						?>
						<div class="form-group  form-group-lg">
							<?php echo $this->Form->input('pagamento_id',array('readonly' => 'readonly','value'=> $pedido['Pagamento']['tipo'],'type'=>'text','class' => 'input-large pagamentoView','id'=>'pagamentoView','label' => array('text' => 'Pagamento:')));?>
						</div>
						<?php
					}
				?>



				<?php
					if($pedido['Pedido']['status_pagamento'] == 'Pendente'){

				?>
						<div class="form-group  form-group-lg">
							<?php 
								$sitpag= array(
									'OK' => 'OK',
									'Pendente' => 'Pendente'
								);
							?>
							<?php echo $this->Form->input('status_pagamento',array('options'=> $sitpag,'default'=> $pedido['Pagamento']['status'],'type'=>'select','class' => 'input-large sitpagamentoView','id'=>'sitpagamentoView','label' => array('text' => 'Sit.Pagamento:')));?>
						</div>
				<?php
					}else{
						?>
						<div class="form-group  form-group-lg">
							<?php echo $this->Form->input('status_pagamento',array('readonly' => 'readonly','value'=> $pedido['Pagamento']['status'],'type'=>'text','class' => 'input-large sitpagamentoView','id'=>'sitpagamentoView','label' => array('text' => 'Sit.Pagamento:')));
							?>
						</div>
						<?php
					}
				?>
				
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('posicao_fila',array('readonly' => 'readonly','default'=> $pedido['Pedido']['posicao_fila'],'type'=>'text','class' => 'input-large sitpagamentoView','id'=>'sitpagamentoView','label' => array('text' => 'Pos.Fila:')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('status',array('readonly' => 'readonly','default'=> $pedido['Pedido']['status'],'type'=>'text','class' => 'input-large statusView','id'=>'statusView','label' => array('text' => 'Status:')));?>
				</div>
				<?php
				
					$entrega = array();
					$entrega[' ']=' ';
					foreach ($entregadores as $entregador) {
						$entrega[$entregador['Entregador']['id']]	=$entregador['Entregador']['nome'];
					
					}
				?>

				<?php
					if($pedido['Entregador']['id'] == '' || $autorizacao['Autorizacao']['pedidos']=='a'){

				?>
						<div class="form-group  form-group-lg">
							<?php echo $this->Form->input('entregador_id',array('options'=> $entrega,'default'=> $pedido['Entregador']['nome'],'type'=>'select','class' => 'input-large entregadorView','id'=>'entregadorView','label' => array('text' => 'Entregador:')));?>
						</div>
				<?php
					}else{
				?>
						<div class="form-group  form-group-lg">
							<?php echo $this->Form->input('entregador_id',array('readonly' => 'readonly','value'=> $pedido['Entregador']['nome'],'type'=>'text','class' => 'input-large entregadorView','id'=>'entregadorView','label' => array('text' => 'Entregador:')));?>
						</div>
						<?php
					}
				?>


				
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('avaliacao',array('readonly' => 'readonly','default'=> $pedido['Pedido']['avaliacao'],'type'=>'text','class' => 'input-large avalicaoView','id'=>'avalicaoView','label' => array('text' => 'Avaliação:')));?>
					<span class="none" id="avalPedido"><?php echo h($pedido['Pedido']['avaliacao']); ?></span>
				</div>
				<?php echo $this->Form->input('difhora',array('readonly' => 'readonly','default'=> $pedido['Pedido']['difhora'],'type'=>'hidden','class' => 'input-large difhoraView difhora','id'=>'difhoraView','label' => false));?>
				<?php if (!empty($pedido['Itensdepedido'])): ?>
					<div class="area-tabela">
						<table class="table-action" >
							<tbody>
								<thead>
									<tr>	
										<th><?php echo __('Código'); ?></th>
										<th><?php echo __('Produto'); ?></th>
										<th><?php echo __('Vl. Unit'); ?></th>
										<th><?php echo __('Qtd'); ?></th>
										<th><?php echo __('Vl. Total'); ?></th>
									</tr>
								</thead>
								<?php foreach ($pedido['Itensdepedido'] as $itensdepedido): ?>
								<tr>
									
									<td><?php echo $itensdepedido['produto_id']; ?></td>
									<td><?php echo $itensdepedido['prodNome']; ?></td>
									<td> <?php echo 'R$ ' . number_format($itensdepedido['valor_unit'], 2, ',', '.'); ?></td>
									<td><?php echo $itensdepedido['qtde']; ?></td>
									<td><?php echo 'R$ ' . number_format($itensdepedido['valor_total'], 2, ',', '.'); ?></td>
								</tr>
								<tr id="linhaTotalPedido">
									<td colspan="4" style="text-align:left;">Total</td>
									<td id="valorTotalPedido"><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?></td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>	
				<?php endif; ?>
				<?php echo $this->Form->end();?>
				<div class="checkout-wrap">
					  <ul class="checkout-bar">
					
					    <li class="previous first progresso" id="progConfirmar">
					      <a href="#" id="lkConfirmar">Confirmar</a>
					    </li>
					
					    <li class="progresso" id="progPreparar"><a href="#" id="lkPreparar">Preparar</a></li>
					
					    <li class="progresso" id="progAguardando"><a href="#" id="lkAguardando">Separar p/ Entrega</a></li>
					
					    <li class="progresso" id="progTransito"><a href="#" id="lkTransito">Enviar</a></li>
					
					    <li class="progresso" id="progEntregue"><a href="#" id="lkEntregue">Entregar</a></li>
					
					  </ul>
				</div>
				<br />
				<br />
				<br />
				<br />
				<?php
					if($autorizacao['Autorizacao']['mensagens'] != 'n'){
				?>
						<button type="button" class="btn btn-success" id="loadChat">Abrir Chat</button>
				<?php

					}

				?>
				<?php
					if($autorizacao['Autorizacao']['pedidos'] == 'a' || $autorizacao['Autorizacao']['pedidos'] == 'g'){
				?>
				<button type="button" class="btn btn-success" id="cancelarPedidoModal">Cancelar Pedido</button>
				<?php

					}

				?>
				<?php 
				 	echo $this->Form->create('Pedido', array('id' => 'statusPedidoForm'));
					echo $this->Form->input('id',array('type' => 'hidden', 'value' => $pedido['Pedido']['id'],'id' => 'idpedido'));	
					echo $this->Form->end();
				?>
		    </div>
     	 <div class="modal-footer">
     	<button type="submit" class="btn btn-default"  id="bt-salvarEdit" >Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        
      </div>
    </div>
  </div>
</div>			
				

			

<script>
$(document).ready(function() {

	var urlInicio = window.location.host;
	urlInicio= urlInicio;
	var scorePedido = $('#avalPedido').text();
	var idpedido= $('#idpedido').val();
	var showOrHide= false;

	$('body').on('click','#bt-salvarEdit', function(event){
		event.preventDefault();
		$('#PedidoEditForm').submit();
	});

	$('#avaliarPedido').raty({
	 starHalf   :'http://'+urlInicio+'/img/star-half.png',
	  starOff    : 'http://'+urlInicio+'/img/star-off.png',
	  starOn     : 'http://'+urlInicio+'/img/star-on.png',	
		score: scorePedido, 
	});
	
	$('#fecharChat').click(function(event){
		$( "#modalLoaded" ).modal('show');
	});
	$('#loadChat').click(function(event){
		
		event.preventDefault();
		var idpedido= $('#idpedido').val();
		$('#idpedidomsg').val(idpedido);
		$("#modalChatcontent").load('http://'+urlInicio+'/Mensagens/ler/'+idpedido+'', function(){
			$( "#modalLoaded" ).modal('hide');
			$( "#modalChat" ).modal('show');
			
			
		});	
		mensagemlida(idpedido);
	});
	
	
	
	$('#add-pagameto').click(function(event){
		event.preventDefault();
		
		$( "#modalLoaded" ).modal('hide');
		$( "#modalpagamento" ).modal('show');	
	});
	$('#cancelarPedidoModal').click(function(event){
		event.preventDefault();
		var idpedido= $('#idpedido').val();
		$( "#modalLoaded" ).modal('hide');
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
		auxStatus = $('#statusView').val();
		
		if(auxStatus=='Em Aberto'){
			
			confirmaAtendimento(idpedido);
		}else{

			alert('O pedido deve estar com o status Em Aberto.');
		}
		
		
	});
	
	$('#progPreparar').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		auxStatus = $('#statusView').val();
		if(auxStatus=='Confirmado'){
			confirmarpreparo(idpedido);
		}else{
			alert('O pedido deve estar com o status Confirmado.');
		}
		
	});
	
	$('#progAguardando').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		auxStatus = $('#statusView').val();
		if(auxStatus=='Pronto'){
			confirmarseparacao(idpedido);
		}else{
			alert('O pedido deve estar com o status Pronto.');
		}
		
		
	});
	
	$('#progTransito').click(function(event){
		event.preventDefault();
		idpedido= $('#idpedido').val();
		auxStatus = $('#statusView').val();
		if(auxStatus=='Separado p/ Entrega'){
			confirmarenvio(idpedido);
		}else{
			alert('O pedido deve estar com o status Separado p/ Entrega.');
		}
		
	});
	
	$('#progEntregue').click(function(event){
		event.preventDefault();

		idpedido= $('#idpedido').val();
		auxStatus = $('#statusView').val();
		entradorNome = $('#entregadorView').val();
		
		if(auxStatus=='Em Trânsito'){
			
			if(entradorNome == ' '){
				alert('Selecione e salve um entregador antes de escolher o status Entregue.');
			}else{

				confirmarentrega(idpedido);
			}
			
		}else{
			alert('O pedido deve estar com o status Em Trânsito.');
		}
		
	});
	var progresso = $('.statusView').val();

	



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
		$('#lkEntregue').html('Entregar');


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
		$('#lkEntregue').html('Entregar');
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
		$('#lkEntregue').html('Entregar');
		
		
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
		$('#lkEntregue').html('Entregar');
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
		$('#lkTransito').html('Em Trânsito');
		$('#lkEntregue').html('Entregar');
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
		$('#lkEntregue').html('Entregue');
	}
	function statusEntregue(){
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
		$('#lkEntregue').html('Entregue');
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
		$('#lkEntregue').html('Entregar')
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
						
						
						if(data !=''){
							
							statusConfirmado();
							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							$('.statusView').val('Confirmado');
						}
						
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
						
						if(data !=''){

							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							statusPronto();
							$('.statusView').val('Pronto');
						}
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
						
						if(data !=''){
							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							statusSeparado();
							$('.statusView').val('Separado p/ Entrega');
							console.log(data);
						}
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
						
						if(data !=''){
							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							statusTransito();
							$('.statusView').val('Em Trânsito');
						}
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
						
						if(data !=''){

							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							statusEntrega();
							
							$('.statusView').val('Entregue');
						}
						console.log(data);
					},error: function(data){
						
					 		
					}
					
				});
				
				
	}
	
	/*function confirmarentregador(idpedido){ 
		
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
				
				
	}*/
	
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
						
						if(data !=''){
							$('#statusPedido').html(data.Pedido.status);
							$('#linhaPdStatus'+idpedido).html(data.Pedido.status);
							
							$( "#modalCancelar" ).modal('hide');
							$( "#modalLoaded" ).modal('show');	
							$('#motivoCancel').val('');
						}
					},error: function(data){
						$( "#modalCancelar" ).modal('hide');
						$( "#modalLoaded" ).modal('show');	
					 	
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
	
		if(progresso == 'Em Aberto'){
			statusAberto();
		}else if(progresso == 'Confirmado'){
			statusConfirmado();
		}else if(progresso == 'Pronto'){
			statusPronto();
		}else if(progresso == 'Separado p/ Entrega'){
			statusSeparado();
		}else if(progresso == 'Em Trânsito'){
			statusTransito();
		}else if(progresso == 'Entregar'){
			statusEntrega();
		}else if(progresso == 'Cancel'){
			statusCancelado();
		
		}else if(progresso == 'Entregue'){
			statusEntregue();
		
		}

	
	
});	
					
</script>

