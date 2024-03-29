<!-- Modal -->
<div class="modal fade modal-grande modal-viewPedido" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Visualizar Pedidos</h4>
        <h5 class="h3content"><span class="h3previsao">Previsão de Entrega</span><div id="counter"></div></h5>
      </div>
      <div class="modal-body">

			<?php echo $this->Form->create('Pedido',array('class' => 'form-inline  centralizadoForm'));
				echo $this->Form->input('id',array(
												'default'=> $pedido['Pedido']['id'],
												'type'=>'hidden','class' => 'idView',
												'id'=>'idView','label' => false));
			?>

				<span class="modal-subtitulo"><h3><?php echo $pedido['Cliente']['nome'];?></h3></span>
				<h5 style="  text-align: center;   margin-top: 0px;   color: #5E5E5E; font-style: italic;"><?php echo 'Telefone: '.$pedido['Cliente']['telefone'].' / Celular:'.$pedido['Cliente']['celular'] ;?></h5>


					<?php
						if($pedido['Cliente']['bairro'] !=''){
					?>
						<div class="form-group  form-group-lg">
							<label>Bairro:</label>
							<p><?php echo $pedido['Cliente']['bairro'];?></p>
						</div>
					<?php
						}
					?>





				<div class="form-group  form-group-lg">
					<label>Logradouro:</label>
					<p><?php echo $pedido['Cliente']['logradouro'];?></p>
				</div>

				<?php
					if($pedido['Cliente']['cidade'] !=''){
				?>
						<div class="form-group  form-group-lg">
							<label>Cidade:</label>
							<p><?php echo $pedido['Cliente']['cidade'];?></p>
						</div>
				<?php
					}
				?>



					<?php
						if($pedido['Cliente']['complemento'] !=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Complemento:</label>
								<p><?php echo $pedido['Cliente']['complemento'];?></p>
							</div>
					<?php
						}
					?>

					<?php
						if($pedido['Cliente']['uf'] !=''){
					?>
						<div class="form-group  form-group-lg">
							<label>UF:</label>
							<p><?php echo $pedido['Cliente']['uf'];?></p>
						</div>
					<?php
						}
					?>



					<?php
						if($pedido['Cliente']['numero'] !=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Número:</label>
								<p><?php echo $pedido['Cliente']['numero'];?></p>
							</div>
					<?php
						}
					?>
					<?php
                                                            if($pedido['Cliente']['p_referencia'] !=''){
                                                        ?>
                                                                <div class="form-group  form-group-lg">
                                                                    <label>Ponto de Referência:</label>
                                                                    <p><?php echo $pedido['Cliente']['p_referencia'];?></p>
                                                                </div>
                                                        <?php
                                                            }
                                                        ?>



<!-- ########################################################################################## -->

				<?php
						$data =$pedido['Pedido']['data'];
						$data = implode("-",array_reverse(explode("/",$data)));
						$pedido['Pedido']['data']= $data;

						$dataPedido=explode('-', $pedido['Pedido']['data']) ;
						$pedido['Pedido']['data'] = $dataPedido['2'].'/'.$dataPedido['1'].'/'.$dataPedido['0'];
					?>

				<span class="modal-subtitulo"><h3>Pedido: <?php echo $pedido['Atendimento']['codigo'];?></h3></span>
				<h5 style="  text-align: center;   margin-top: 0px;   color: #5E5E5E; font-style: italic;"><?php echo 'Data: '. $pedido['Pedido']['data'] . ' / Hora:'.$pedido['Pedido']['hora_atendimento'].' / Status: <span class="statusView">'.$pedido['Pedido']['status'].'</span>' ;?></h5>



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

					<div class="form-group  form-group-lg">
						<label>Avaliação</label>
						<p><span id="avaliarPedido"><?php echo h($pedido['Pedido']['avaliacao']); ?></span></p>
					</div>

					<div class="form-group  form-group-lg">
						<label>Local de Entrega</label>
						<p><span id="localEntrega"><?php echo h($pedido['Pedido']['outro_endereco_entrega']); ?></span></p>
					</div>
					<div class="form-group  form-group-lg">
						<label>Observações</label>
						<p><span id="obsPedido"><?php echo h($pedido['Pedido']['obs']); ?></span></p>
					</div>





					<?php
						if( $pedido['Pedido']['status_pagamento']!=''){
					?>
						<div class="form-group  form-group-lg">
							<label>Sit.Pagamento:</label>
							<p><?php echo $pedido['Pedido']['status_pagamento'];?></p>
						</div>
					<?php
						}
					?>






						<?php
						if( $pedido['Pedido']['posicao_fila']!=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Pos.Fila:</label>
								<p><?php echo $pedido['Pedido']['posicao_fila'];?></p>
							</div>
					<?php
						}
					?>







					<?php
						if( $pedido['Entregador']['nome']!=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Entregador:</label>
								<p><?php echo $pedido['Entregador']['nome'];?></p>
							</div>
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





			<?php echo $this->Form->end();?>
			<section class="row-fluid">
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

									<?php endforeach; ?>
									<tr id="linhaTotalPedido">
										<td colspan="4" style="text-align:left;">Total</td>
										<td id="valorTotalPedido"><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
			</section>
				<div class="checkout-wrap">
					  <ul class="checkout-bar" style="margin-left: 10px;">

						<li class="previous first progresso" id="progConfirmar">
						  <a href="#" id="lkConfirmar">Confirmar</a>
						</li>

						<li class="progresso" id="progPreparar"><a href="#" id="lkPreparar">Preparar</a></li>

						<li class="progresso" id="progAguardando"><a href="#" id="lkAguardando">Separar p/ Entrega</a></li>

						<li class="progresso" id="progTransito"><a href="#" id="lkTransito">Enviar</a></li>

						<li class="progresso" id="progEntregue"><a href="#" id="lkEntregue">Entregar</a></li>

					  </ul>
				</div>

			<br>
			<br>
			<br>
			<br>
			<br>

			<?php
				echo $this->Form->create('Pedido', array('id' => 'statusPedidoForm'));
				echo $this->Form->input('id',array('type' => 'hidden', 'value' => $pedido['Pedido']['id'],'id' => 'idpedido'));
				echo $this->Form->end();
			?>
		</div>

			<div class="modal-footer">
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
	var idpedido= $('#idView').text();
	var showOrHide= false;
	var progresso = $('.statusView').text();
	$('#avaliarPedido').raty({
	 starHalf   :'http://'+urlInicio+'/img/star-half.png',
	  starOff    : 'http://'+urlInicio+'/img/star-off.png',
	  starOn     : 'http://'+urlInicio+'/img/star-on.png',
	  readOnly: true,
		score: scorePedido,
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

