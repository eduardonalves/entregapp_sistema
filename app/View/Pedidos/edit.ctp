<!-- Modal -->
<div class="modal fade modal-grande modal-editPedido" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Editar Pedidos</h4>
        <?php if($pedido['Pedido']['nomecadcliente'] =='' || $pedido['Pedido']['atendente_id'] != ''):  ?>
        <h5 class="h3content" style="display: none;"><span class="h3previsao">Previsão de Entrega</span><div id="counter"></div></h5>
      <?php endif; ?>
      </div>
      <div class="modal-body">
			<?php echo $this->Form->create('Pedido',array('class' => 'form-inline centralizadoForm'));	?>

				<?php echo $this->Form->input('id',array('readonly' => 'readonly','default'=> $pedido['Pedido']['id'],'type'=>'hidden','class' => 'input-large idView','id'=>'idView','label' => false));					?>
        <?php if($pedido['Pedido']['nomecadcliente'] !=''):  ?>
          <?php echo $this->Form->input('nomecadcliente',array('default'=> $pedido['Pedido']['id'],'type'=>'text','class' => 'input-large nomecadcliente','id'=>'nomecadcliente','label' => 'Cliente:'));					?>
          <div class="form-group  form-group-lg">
            <?php echo $this->Form->input('mesa_id',array('options'=> $mesas,'default'=> $pedido['Pedido']['mesa_id'],'type'=>'select','class' => 'input-large mesaView','id'=>'mesaView','label' => 'Mesa', 'div'=> false));?>
          </div>
        <?php else: ?>
				<span class="modal-subtitulo"><h3><?php echo $pedido['Cliente']['nome'];?></h3></span>
				<span class="modal-subtitulo"><h3>(<?php echo $pedido['Cliente']['username'];?>)</h3></span>
			
    <?php endif; ?>

<!-- ########################################################################################## -->

			<?php
				$data =$pedido['Pedido']['data'];
				$data = implode("-",array_reverse(explode("/",$data)));
				$pedido['Pedido']['data']= $data;

				$dataPedido=explode('-', $pedido['Pedido']['data']) ;
				$pedido['Pedido']['data'] = $dataPedido['2'].'/'.$dataPedido['1'].'/'.$dataPedido['0'];
			?>

				<span class="modal-subtitulo"><h3>Pedido: <?php echo $pedido['Pedido']['id'];?></h3></span>
				<h5 class="h5informacoes"><?php echo 'Data: '. $pedido['Pedido']['data'] . ' / Hora:'.$pedido['Pedido']['hora_atendimento'].' / Status: <span class="statusView">'.$pedido['Pedido']['status'] .'</span>'.' / Total: <span > R$ '. number_format($pedido['Pedido']['valor'], 2, ',', '.').'</span>';?></h5>
      <?php if($pedido['Pedido']['nomecadcliente'] =='' || $pedido['Pedido']['atendente_id'] != ''):  ?>
			<div style="margin: 0 auto; width: 93%;">

					<?php
						if( $pedido['Pagamento']['tipo']!=''){
					?>
						<div class="form-group  form-group-lg">
							<label>Pagamento:</label>
							<p><?php echo $pedido['Pagamento']['tipo'];?></p>
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
							<label>Sit.Pagamento:</label>
							<?php echo $this->Form->input('status_pagamento',array('options'=> $sitpag,'default'=> $pedido['Pagamento']['status'],'type'=>'select','class' => 'input-default sitpagamentoView','id'=>'sitpagamentoView','label' => false, 'div' =>false));?>
						</div>
				<?php
					}else{
				?>
						<?php
							if( $pedido['Pedido']['status_pagamento']!=''){
						?>
							<div class="form-group  form-group-lg">
								<p><strong>Sit.Pagamento:</strong></p>
								<p><?php echo $pedido['Pedido']['status_pagamento'];?></p>
							</div>
						<?php
							}
					}
						?>


					

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
							<div class="form-group  form-group-lg" style="margin-left: 20px;">
								<p><strong>Entregador:</strong></p>
								<?php echo $this->Form->input('entregador_id',array('options'=> $entrega,'default'=> $pedido['Entregador']['nome'],'type'=>'select','class' => 'input-default entregadorView','id'=>'entregadorView','label' => false, 'div' => false));?>
							</div>
					<?php
						}else{
					?>
							<?php
								if( $pedido['Entregador']['nome']!=''){
							?>
							<div class="form-group  form-group-lg">
								<p><strong>Entregador:</strong></p>
								<p><?php echo $pedido['Entregador']['nome'];?></p>
							</div>
							<?php
								}
							?>
					<?php
						}
					?>

					<?php
						if( $pedido['User']['username']!=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Atendente:</label>
								<p><?php echo $pedido['User']['username'];?></p>
							</div>
					<?php
						}
					?>

				<!--<div class="form-group  form-group-lg">
					<label>Avaliação</label>
					<p><span id="avaliarPedido"><?php //echo h($pedido['Pedido']['avaliacao']); ?></span></p>
				</div>-->

				
				<div class="form-group  form-group-lg" style="margin-left: 20px;">
					<label>Levar Troco?</label>
					<p><span id="obsTroco">
					<?php
						echo $pedido['Pedido']['trocoresposta'];

					 ?></span></p>
				</div>
				<div class="form-group  form-group-lg col-md-4">
					<label style="width: 200px;">Local de Entrega</label>
					<p>
						<?php 
						if($pedido['Pedido']['logradouro'] != '')
						{
							echo h($pedido['Pedido']['logradouro']);
							
						} ?>
						<?php 
						if($pedido['Pedido']['numero'] != '')
						{
							echo ', ' .$pedido['Pedido']['numero'];
							
						} ?>
						<?php 
						if($pedido['Pedido']['complemento'] != '')
						{
							echo ', ' .$pedido['Pedido']['complemento'];
							
						} ?>
						<br>
						<?php 
						if($pedido['Pedido']['bairro_nome'] != '')
						{
							echo '' .$pedido['Pedido']['bairro_nome'];
							
						} ?>
						<?php 
						if($pedido['Pedido']['cidade_nome'] != '')
						{
							echo ' - ' .$pedido['Pedido']['cidade_nome'];
							
						} ?>
						<?php 
						if($pedido['Pedido']['estado_nome'] != '')
						{
							echo '- ' .$pedido['Pedido']['estado_nome'];
							
						} ?>
						<br>
						<?php 
						if($pedido['Pedido']['telefone'] != '')
						{
							echo 'Telefone: '.$pedido['Pedido']['telefone'];
							
						} ?>
						<br>
						<?php 
						if($pedido['Pedido']['ponto_referencia'] != '')
						{
							echo $pedido['Pedido']['ponto_referencia'];
							
						} ?>
						<br>
						<?php 
						if($pedido['Pedido']['entrega_valor'] != '')
						{
							echo "<b> Tx. de Entrega: R$ ". number_format($pedido['Pedido']['entrega_valor'], 2, ',', '.')."</b>";
							
						} ?>		
					</p>
				</div>
				
      <?php endif;  ?>
      			
      			<?php

      			if($pedido['Pedido']['obs'] !=''){

      			
      			?>
      			<br>
				<div class="form-group  form-group-lg">
					<label>Observações</label>
					<p><span id="obsPedido"><?php echo h($pedido['Pedido']['obs']); ?></span></p>
				</div>
				<?php
					}
				?>
			</div>
<!-- ########################################################################################## -->

				<?php echo $this->Form->input('difhora',array('readonly' => 'readonly','default'=> $pedido['Pedido']['difhora'],'type'=>'hidden','class' => 'input-large difhoraView difhora','id'=>'difhoraView','label' => false));?>
				<?php if (!empty($pedido['Itensdepedido'])): ?>
					<div class="area-tabela" id="no-more-tables">
						<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
							<tbody class="cf">
								<thead>
									<tr>
										<th><?php echo __('Código'); ?></th>
										<th><?php echo __('Produto'); ?></th>
										<th><?php echo __('Vl. Unit'); ?></th>
										<th><?php echo __('Qtd'); ?></th>
										<th><?php echo __('Vl. Total'); ?></th>
									</tr>
								</thead>
								<?php
                $k=0;
                $printItens ='';
                foreach ($pedido['Itensdepedido'] as $itensdepedido):
                ?>
								<tr>

									<td data-title="Código"><?php echo $itensdepedido['produto_id']; ?></td>
									<td data-title="Produto"><?php echo $itensdepedido['prodNome']; ?></td>
									<td data-title="Vl. Unit"> <?php echo 'R$ ' . number_format($itensdepedido['valor_unit'], 2, ',', '.'); ?></td>
									<td data-title="Qtde"><?php echo $itensdepedido['qtde']; ?></td>
									<td data-title="Valor Total"><?php echo 'R$ ' . number_format($itensdepedido['valor_total'], 2, ',', '.'); ?></td>
								</tr>
                <?php
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][id]" value="'.$itensdepedido['produto_id'].'"/>';
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][prodNome]" value="'.$itensdepedido['prodNome'].'"/>';
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][qtde]" value="'.$itensdepedido['qtde'].'"/>';
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][valor_unit]" value="R$ '.number_format($itensdepedido['valor_unit'], 2, ',', '.').'"/>';
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][valor_total]" value="R$ '.number_format($itensdepedido['valor_total'], 2, ',', '.').'"/>';
                  $printItens .='<input type="hidden" name="Pedido[Itens]['.$k.'][obs_sis]" value="'.$itensdepedido['obs_sis'].'"/>';
                  $k++;
                ?>
								<?php endforeach; ?>
								<tr id="linhaTotalPedido">
									<td colspan="4" style="text-align:left;" class="totalResponsivo" >Total</td>
									<td id="valorTotalPedido" data-title="Valor Total" ><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php endif; ?>
				<?php echo $this->Form->end();?>
                                           <div style="clear:both;"></div>
        <?php if($pedido['Pedido']['nomecadcliente'] =='' || $pedido['Pedido']['atendente_id'] != ''):  ?>
        <div class="wrap-holder">
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
        </div>

				<br />
				<br />
				<br />
				<br />
				<br />
				<?php
					if($autorizacao['Autorizacao']['mensagens'] != 'n'){
				?>
					<!--	<button type="button" class="btn btn-success" id="loadChat">Abrir Chat</button>-->
				<?php

					}
        endif;
				?>

				<?php
					if($autorizacao['Autorizacao']['pedidos'] == 'a' || $autorizacao['Autorizacao']['pedidos'] == 'g'){
				?>
        <br>

        <br>
        <br>
        <div style="clear:both;"></div>
        <?php

					}

				?>
				<?php
				 	echo $this->Form->create('Pedido', array('id' => 'statusPedidoForm'));
					echo $this->Form->input('id',array('type' => 'hidden', 'value' => $pedido['Pedido']['id'],'id' => 'idpedido'));
					echo $this->Form->input('statusView',array('type' => 'hidden', 'value' => $pedido['Pedido']['status'],'id' => 'statusView'));
					echo $this->Form->end();
				?>
				<div style="display: none;" id="salvouEntregador">
					<?php echo $pedido['Pedido']['entregador_id'];?>;
				</div>

		    </div>
     	 <div class="modal-footer">
      <button type="button" class="btn btn-default" id="cancelarPedidoModal">Cancelar Pedido</button>

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
    if(urlInicio=="localhost" ){
        urlInicio= "localhost/entregapp_sistema";   
    } 
	var scorePedido = $('#avalPedido').text();
	var idpedido= $('#idView').val();
	var showOrHide= false;

	$('body').on('click','#bt-salvarEdit', function(event){
		event.preventDefault();
		$('#PedidoEditForm').submit();
	});
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
                  alert('Seu pedido foi enviado para impressão');
              }else{
                  alert('Erro ao enviar pedido para impressao');

              }



          },error: function(data){

              alert('Erro de conexão');

          }
      });
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
		var idpedido= $('#idView').val();
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
		var idpedido= $('#idView').val();
		$( "#modalLoaded" ).modal('hide');
		$( "#modalCancelar" ).modal('show');
	});

	$('#cancelarPedido').click(function(event){
		event.preventDefault();
		var idpedido= $('#idView').val();
		cancelarpedido(idpedido);
	});

	myVar = setInterval(function(){verificaMensagem();}, 30000);
	setTimeout(function(){
						var objDiv = document.getElementById("modalChatcontent");
						objDiv.scrollTop = objDiv.scrollHeight;
					}, 1000);



	function verificaMensagem(){


		ultimaMsg= $('#ultMsg').val();
		idpedido = $('#idView').val();
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
						if(typeof mensagen.User != 'undefined'){
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
						}
						

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

		idpedido= $('#idView').val();
		confirmarentregador(idpedido);

	})
	$('#lkConfirmar').click(function(event){
		event.preventDefault();
		idpedido= $('#idView').val();
		auxStatus = $('#statusView').val();


		if(auxStatus.trim()=='Em Aberto'){

			confirmaAtendimento(idpedido);
		}else{

			alert('O pedido deve estar com o status Em Aberto.');
		}


	});

	$('#progPreparar').click(function(event){
		event.preventDefault();
		idpedido= $('#idView').val();
		auxStatus = $('#lkConfirmar').text();
		console.log(auxStatus.trim());

		if(auxStatus.trim()=='Confirmado'){
			confirmarpreparo(idpedido);
			//console.log('passou1');
		}else{
			//console.log('passou2');
			alert('O pedido deve estar com o status Confirmado.');
		}

	});

	$('#progAguardando').click(function(event){
		event.preventDefault();
		idpedido= $('#idView').val();
		auxStatus = $('#statusView').val();
		if(auxStatus=='Pronto'){

			confirmarseparacao(idpedido);
		}else{
			alert('O pedido deve estar com o status Pronto.');
		}


	});

	$('#progTransito').click(function(event){

		event.preventDefault();
		idpedido= $('#idView').val();
		auxStatus = $('#statusView').val();
		if(auxStatus.trim()=='Separado'){


			confirmarenvio(idpedido);
		}else{
			alert('O pedido deve estar com o status Separado.');
		}

	});
	/*$('#lkAguardando').click(function(event){
		event.preventDefault();
		idpedido= $('#idView').val();
		auxStatus = $('#statusView').val();
		if(auxStatus=='Separar p/ Entrega'){
			statusSeparadoEnviar(idpedido);
		}else{
			alert('O pedido deve estar com o status Separado .');
		}

	});*/


	$('#progEntregue').click(function(event){
		event.preventDefault();

		idpedido= $('#idView').val();
		auxStatus = $('#statusView').val();
		entradorNome = $('#entregadorView').val();

		if(auxStatus.trim()=='Em Trânsito'){

			if(entradorNome == ' '){
				alert('Selecione e salve um entregador antes de escolher o status Entregue.');
			}else{
				console.log(entradorNome);
				if($('#salvouEntregador').text() != ''){
					
					confirmarentrega(idpedido);
				}
				
			}

		}else{
			alert('O pedido deve estar com o status Em Trânsito.');
		}

	});
	var progresso = $('#statusView').val();





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
		$('.statusView').html('Separado');
		$('#statusView').html('Separado');
		$('#lkTransito').html('Enviar');
		$('#lkEntregue').html('Entregar');
	}
	function statusSeparadoEnviar(){
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
		$('.statusView').html('Separado');
		$('#statusView').html('Separado');
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
		$('#statusView').html('Em Trânsito');
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

						console.log(data);
						if(data !=''){

							statusConfirmado();
							$('.statusView').html(data.resultados.Pedido.status);
							$('#statusView').val(data.resultados.Pedido.status);
							$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);
							$('.statusView').html('Confirmado');
							$('#statusView').val('Confirmado');
						}

						
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

							$('.statusView').html(data.resultados.Pedido.status);
							$('#statusView').val(data.resultados.Pedido.status);
							$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);
							statusPronto();
							$('.statusView').html('Pronto');
							$('#statusView').val('Pronto');
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
							if(typeof data.resultados != 'undefined'){
								$('.statusView').html(data.resultados.Pedido.status);
								$('#statusView').val(data.resultados.Pedido.status);
								$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);

								$('.statusView').html('Separado');
								$('#statusView').val('Separado');
								statusSeparado();
							}
							
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
							$('.statusView').html(data.resultados.Pedido.status);
							$('#statusView').val(data.resultados.Pedido.status);
							$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);
							statusTransito();

							$('.statusView').html('Em Trânsito');
							$('#statusView').val('Em Trânsito');
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

							$('.statusView').html(data.resultados.Pedido.status);
							$('#statusView').val(data.resultados.Pedido.status);
							$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);




							$('.statusView').html('Entregue');
							$('#statusView').val('Entregue');
							statusEntregue();
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
							$('.statusView').html(data.resultados.Pedido.status);
							$('#statusView').val(data.resultados.Pedido.status);
							$('#linhaPdStatus'+data.resultados.Pedido.id).html(data.resultados.Pedido.status);

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


		var urlAction = "http://"+urlInicio+"/Mensagens/add.json";
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
		}else if(progresso == 'Separado'){
			statusSeparado();
		}else if(progresso == 'Separar p/ Entrega'){
			statusSeparadoEnviar();
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
