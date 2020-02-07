

	<h2><?php echo __('Pedidos'); ?></h2>

	<?php
		echo $this->Search->create('Pedidos', array('class'=> 'form-inline'));
	?>

	<section class="row-fluid">

		<div class="col-md-3">
			<?php
				echo $this->Search->input('codigo', array('label' => 'Código:','class'=>'filtroPedido input-default', 'required' =>'false'));
			?>
		</div>
		<div class="col-md-3">
			<?php
				echo $this->Search->input('nome', array('label' => 'Nome:','class'=>'filtroPedido input-default'));
			?>
		</div>

		<div class="col-md-3">
			<label>Status: </label>
			<br>
			<?php
				echo $this->Search->input('status', array('label' => false,'class'=>'filtroPedido input-default', 'required' =>'false'));
			?>
		</div>
		<div class="col-md-3">
			<label>Sem Status: </label>
			<br>
			<?php
				echo $this->Search->input('statusnot', array('label' => false,'class'=>'filtroPedido input-default', 'required' =>'false'));
				echo $this->Search->input('empresa', array('label' => false,'class'=>'none', 'required' =>'false'));

			?>
		</div>

	</section>

	<section class="row-fluid">
		<div class="col-md-3">
		<?php

		echo $this->Search->input('novospedidos', array('label' => 'Novos Pedidos?','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
		</div>
		<div class="col-md-3">
		<?php

		echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
		</div>

		<div class="col-md-4">
			<label>Data de até:</label>
			<br>
			<?php
				echo $this->Search->input('dataPedido', array('id'=>'dataPedido','label' => false,'class'=>'filtroPedido data'));
				echo "<span class='separador-filter'>a</span>";
			?>


		</div>
		<div class="col-md-1  pull-right" >
			<?php
				echo $this->Search->end(__('Pesquisar', true));
			?>
		</div>
		<?php
			if($autorizacao['Autorizacao']['pedidos'] != 'n' && $autorizacao['Autorizacao']['pedidos'] != 'v'){
		?>
		<div class="col-md-1" style="margin-top: 25px;">
			<button type="button" class="btn btn-success addpedido pull-right" id="addpedido">Novo</button>
		</div>
		<?php
			}
		?>
	</section>
	<div class="cb" style="clear:both">

	</div>
	<section class="row-fluid">
		
		<div>
			<h4 id="titulo-mensagem2"><span class="titulo"><?php echo $this->Html->image('bell.png',array('style'=>'float:left;margin-top:-3px;')); ?><span id="pedidosNovos-qtd">0</span>&nbsp;Novos Pedidos</span></h4>

		</div>
		<!-- MENSAGENS DE CLIENTE -->
		<?php
			if($autorizacao['Autorizacao']['mensagens'] != 'n'){
		?>
		<!--<section class="row-fluid">
			<div class="span4">
				<div id="quadro-mensagem">
					<?php
						//$qtd = $coutmsg;
					?>
					<h4 id="titulo-mensagem"><span class="titulo"><?php //echo $this->Html->image('bell.png',array('style'=>'float:left;margin-top:-3px;')); ?><span id="msg-qtd"><?php echo $qtd; ?></span>&nbsp;Mensagens</span></h4>
					<div id="mensagens">
						<div class="" id="loadAtivas">

						</div>
					</div>
				</div>
			</div>
		</section>-->
		<?php
			}
		?>
	</section>
<script>
$(document).ready(function(){
	var urlInicio  = window.location.host;
	if(urlInicio=="localhost" ){
		urlInicio= "localhost/entregapp_sistema";	
	} 
	$('body').on('click', '#titulo-mensagem', function(event){
		loja = $('#filterMinhaslojas').val();
		$("#loadAtivas").load('http://'+urlInicio+'/Pedidos/mensagensativas/?loja='+loja, function(){
		});
		$("#mensagens").toggle('slow');
	});

});
</script>
<style>
table{
	text-align: center;
}
	#titulo-mensagem2{
    display: block;
    width: 250px;
    height: 30px;
    border-bottom: 1px solid #ccc;
}
	#titulo-mensagem2 .titulo {
    color: #e32;
    font-weight: bold;
}
#titulo-mensagem2 span {
    float: left;
}
form div.submit {margin-top: 27px;}

</style>




	<style>
		#divLoader{
			position: absolute;
			height: 26px;
			width: 70px;
			background: rgba(0, 0, 0, 0.87);
			margin: -10% 45%;
			border-radius: 7px;
		}

		#divLoader img{
			width: 50px;
			margin: 0 auto;
			display: block;
			margin-top: 6px;
		}
		#pedidosNovos-qtd{
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
		.audioPlayer{
			display: none;
		}
		.center-text{
			text-align: center;
		}
		.th-red{
			background-color:#E82F00 !important;
		}
	</style>

	<div id="divLoader" style="display:none;">
		<?php echo $this->Html->image('ajax-loader.gif', array('id' => 'loaderGif', 'class' => ' loaderPedido', 'alt' => 'Loader', 'title' => 'Loader')); ?>
	</div>
	<section class="row-fluid">
		<div class="area-tabela"  id="no-more-tables">
			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
				<thead class="cf">
					<tr>
						<th class="th-red">Total dos Pedidos</th>
						<th class="th-red">Total Entregue </th>
						<th class="th-red">Total Só Pedidos</th>
						<th class="th-red">Total Só Entregas </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td  class="center-text" data-title="Total dos Pedidos" >R$ <?php echo number_format($totalPedidos, 2,',','.');?>&nbsp;</td>
						<td   class="center-text " data-title="Total Entregue " >R$ <?php echo number_format($totalPedidosEntregue, 2,',','.');?>&nbsp;</td>
						<td   class="center-text" data-title="Total Só Pedidos " >R$ <?php $soEntregas = ($totalPedidosEntregue-$totalEntrega); echo number_format($soEntregas, 2,',','.');?>&nbsp;</td>
						<td   class="center-text" data-title="Total Só Entregas " >R$ <?php echo number_format($totalEntrega, 2,',','.');?>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br />
		<br />

	</section>
	<div class="area-tabela"  id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
			<thead class="cf">
				<tr>

						<th><?php echo $this->Paginator->sort('Pedido.id','Código'); ?></th>
						<th><?php echo $this->Paginator->sort('Cliente.nome', 'Nome'); ?></th>

						<th><?php echo $this->Paginator->sort('data'); ?></th>
						<th><?php echo $this->Paginator->sort('hora_atendimento', 'Hora'); ?></th>
						<th><?php echo $this->Paginator->sort('posicao_fila', 'Pos. na Fila'); ?></th>
						<th><?php echo $this->Paginator->sort('valor'); ?></th>
						<th><?php echo $this->Paginator->sort('status_pagamento'); ?></th>
						<th><?php echo $this->Paginator->sort('Entregador.nome'); ?></th>
						<th><?php echo $this->Paginator->sort('status'); ?></th>
						<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<?php $cliente_idAux=''; ?>
			<?php foreach ($pedidos as $pedido): ?>
			<tr>

				<td data-title="Código"><?php echo h($pedido['Pedido']['id']); ?>&nbsp;</td>

				<td data-title="Nome"><?php echo ($pedido['Pedido']['cliente_id'] !=1 ? $pedido['Cliente']['nome'] : $pedido['Pedido']['nomecadcliente']); ?>&nbsp;</td>
				<?php
					$dataAntiga = $pedido['Pedido']['data'];
					$novaData = date("d/m/Y", strtotime($dataAntiga));

				?>
				<?php $cliente_idAux=$pedido['Cliente']['id'];
				?>

				<td   data-title="Data" ><?php echo  $novaData; ?>&nbsp;</td>
				<td data-title="Hora" ><?php echo h($pedido['Pedido']['hora_atendimento']); ?>&nbsp;</td>
				<td  data-title="Posicao na Fila"><?php echo h($pedido['Pedido']['posicao_fila']); ?>&nbsp;</td>
				<td data-title="Valor" ><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?>&nbsp;</td>
				<td data-title="Status Pagamento"><?php echo h($pedido['Pedido']['status_pagamento']); ?>&nbsp;</td>
				<td data-title="Entregador" <?php echo 'id="linhaEntregadorNome'.$pedido['Pedido']['id'].'"';?>><?php echo h($pedido['Entregador']['nome']); ?>&nbsp;</td>
				<td   data-title="Status" <?php echo 'id="linhaPdStatus'.$pedido['Pedido']['id'].'"';?>><?php echo h($pedido['Pedido']['status']); ?>&nbsp; <?php if($pedido['Pedido']['status_novo']==1){ echo $this->Html->image('novo.jpg', array('id' => 'novoimg', 'class' => ' novoico', 'alt' => 'Novo', 'title' => 'Novo'));}?></td>
				<td  data-title="Actions" >



					<?php
						//echo $this->html->image('tb-ver.png',array('data-idpedido'=>$pedido['Pedido']['id'],'class'=>'bt-tabela ver viewModal viewpedido','data-id'=>$pedido['Pedido']['id']));
						if($autorizacao['Autorizacao']['pedidos'] != 'n' && $autorizacao['Autorizacao']['pedidos'] != 'v'){
							echo $this->html->image('tb-edit.png',array('data-idpedido-id'=>$pedido['Pedido']['id'],'class'=>'bt-tabela editpedido ','data-id'=>$pedido['Pedido']['id']));
						}
						if($autorizacao['Autorizacao']['pedidos'] == 'a'){
							/*echo $this->Form->postLink(
								  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
								  array('controller'=>'pedidos','action' => 'delete', $pedido['Pedido']['id']), //le url
								  array('escape' => false), //le escape
								  __('Deseja Excluir o  %s?', $pedido['Atendimento']['codigo'])
							);*/
						}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
		<div class="row-fluid" style="margin-bottom: 50px;">
			<p>
			<?php
			echo $this->Paginator->counter(array(
			'format' => __('Página {:page} de {:pages}, Visualisando {:current} de {:count} resultados.')
			));
			echo "<br>";
			echo $this->Paginator->counter(array(
			'format' => __('Inicio no registro {:start}, fim no {:end}.')
			));
			?>	</p>
			<nav>
	  			<ul class="pagination">
				<?php
					echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
					echo $this->Paginator->numbers(array('separator' => ''));
					echo $this->Paginator->next(__('Próxima') . ' >', array(), null, array('class' => 'next disabled'));
				?>
				</ul>
			</nav>
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
					<form  id="formCancelar" >
						<input type="hidden" name="data[Pedido][id]" id="cancelId" />
						 <div class="form-group">
						 	<label >Motivo:</label>
							<textarea class="form-control" rows="5"  name="data[Pedido][motivocancela]"  id="motivoCancel"></textarea>
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
				<form  id="chat" >
				<input type="hidden" name="data[Mensagen][pedido_id]" id="idpedidomsg" />
				<input type="hidden" name="data[Mensagen][filial_id]" id="idfilialmsg" />
				<input type="hidden" name="data[Mensagen][cliente_id]" id="cliente_idmsg"  value=<?php echo $cliente_idAux;;?>   />
				<label for="comment">Mensagem:</label>
					<textarea  rows="5" id="enviarinpt" name="data[Mensagen][msg]" style="height: 50px !important;"></textarea>
				<button type="submit" class="btn" id="enviarmsg">Enviar</button>
				<button type="button" data-dismiss="modal" class="btn" id="fecharChat">Fechar</button>
				</form>
			</div>

	      </div>
	    </div>
	</div>

<div id="loadModalPedido">
</div>
<audio controls class="audioPlayer">
     <source src="/sons/cymbals Symphony.wav" type="audio/wav">
</audio>
<script type="text/javascript">
$(document).ready(function() {
		//var urlInicio      = window.location.host;
	loja = $('#filterMinhaslojas').val();
	$('#idfilialmsg').val(loja);
	var urlInicio      = window.location.host;
	urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp_sistema': urlInicio);

	$("#pedidosNovos-qtd").load('http://'+urlInicio+'/Pedidos/countpedidosnovos/?loja='+loja, function(){});
	setTimeout(function(){
			nPedidos = $('#pedidosNovos-qtd').text();
			nPedidos.replace(' ','');

			if( nPedidos !=0 ){
				$('.audioPlayer').trigger('play');
			}
		},2000);
	setInterval(function(){


	$("#pedidosNovos-qtd").load('http://'+urlInicio+'/Pedidos/countpedidosnovos/?loja='+loja, function(){
	});
		setTimeout(function(){
			nPedidos = $('#pedidosNovos-qtd').text();
			nPedidos.replace(' ','');
			nPedidos = parseInt(nPedidos);
			if( nPedidos !=0 ){
				$('.audioPlayer').trigger('play');
			}
		},2000);
	}, 20000);





});
</script>
