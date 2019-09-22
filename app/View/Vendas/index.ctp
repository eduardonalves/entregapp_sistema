

	<h2><?php echo __('Vendas'); ?></h2>

	<?php
		echo $this->Search->create('Venda', array('class'=> 'form-inline'));
	?>

	<section class="row-fluid">

		<div class="span3">
			<?php
				echo $this->Search->input('codigo', array('label' => 'Código:','class'=>'filtroVenda input-default', 'required' =>'false'));
			?>
		</div>


		<div class="span3">
			<label>Status: </label>
			<br>
			<?php
				echo $this->Search->input('status', array('label' => false,'class'=>'filtroVenda input-default', 'required' =>'false'));
			?>
		</div>
		<div class="span3">
			<label>Sem Status: </label>
			<br>
			<?php
				echo $this->Search->input('statusnot', array('label' => false,'class'=>'filtroVenda input-default', 'required' =>'false'));
				echo $this->Search->input('empresa', array('label' => false,'class'=>'none', 'required' =>'false'));

			?>
		</div>

	</section>

	<section class="row-fluid">

		<div class="span3">
		<?php

		echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroVenda input-default ', 'required' =>'false'));
		?>
		</div>
		<div class="span4">
			<label>Data de até:</label>
			<br>
			<?php
				echo $this->Search->input('dataVenda', array('id'=>'dataVenda','label' => false,'class'=>'filtroVenda data'));
				echo "<span class='separador-filter'>a</span>";
			?>


		</div>
		<div class="span2">
			<?php
				echo $this->Search->end(__('Filtrar', true));
			?>
		</div>

	</section>



	<!--<div class="form-group  form-group-lg">
		<button type="button" class="btn btn-success addvenda" id="addvenda">Nova Venda</button>
	</div>-->


	<style>
	input#dataVenda, input#filterDataVenda-between {
    width: 40%;
    height: 32px;
}
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
		#vendasNovos-qtd{
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
		<?php echo $this->Html->image('ajax-loader.gif', array('id' => 'loaderGif', 'class' => ' loaderVenda', 'alt' => 'Loader', 'title' => 'Loader')); ?>
	</div>
	<section class="row-fluid">
		<div class="area-tabela"  id="no-more-tables">
			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
				<thead class="cf">
					<tr>
						<th class="th-red">Total dos Vendas</th>
						<th class="th-red">Total Entregue </th>
						<th class="th-red">Total Só Vendas</th>
						<th class="th-red">Total Só Entregas </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td  class="center-text" data-title="Total dos Vendas" >R$ <?php echo number_format($totalVendas, 2,',','.');?>&nbsp;</td>
						<td   class="center-text " data-title="Total Entregue " >R$ <?php echo number_format($totalVendasEntregue, 2,',','.');?>&nbsp;</td>
						<td   class="center-text" data-title="Total Só Vendas " >R$ <?php $soEntregas = ($totalVendasEntregue-$totalEntrega); echo number_format($soEntregas, 2,',','.');?>&nbsp;</td>
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

						<th><?php echo $this->Paginator->sort('Venda.id','Código'); ?></th>


						<th><?php echo $this->Paginator->sort('data'); ?></th>
						<th><?php echo $this->Paginator->sort('hora_atendimento', 'Hora'); ?></th>

						<th><?php echo $this->Paginator->sort('valor'); ?></th>

						<th><?php echo $this->Paginator->sort('status'); ?></th>
						<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<?php $cliente_idAux=''; ?>
			<?php foreach ($vendas as $venda): ?>
			<tr>

				<td data-title="Código"><?php echo h($venda['Venda']['id']); ?>&nbsp;</td>


				<?php
					$dataAntiga = $venda['Venda']['data'];
					$novaData = date("d/m/Y", strtotime($dataAntiga));

				?>
				<?php $cliente_idAux=$venda['Cliente']['id'];
				?>

				<td   data-title="Data" ><?php echo  $novaData; ?>&nbsp;</td>
				<td data-title="Hora" ><?php echo h($venda['Venda']['hora_atendimento']); ?>&nbsp;</td>

				<td data-title="Valor" ><?php echo 'R$ ' . number_format($venda['Venda']['valor'], 2, ',', '.'); ?>&nbsp;</td>

				<td   data-title="Status" <?php echo 'id="linhaPdStatus'.$venda['Venda']['id'].'"';?>><?php echo h($venda['Venda']['status']); ?>&nbsp;</td>
				<td  data-title="Actions" >



					<?php
						echo $this->html->image('tb-ver.png',array('data-idvenda'=>$venda['Venda']['id'],'class'=>'bt-tabela ver viewModal viewvenda cusrorPointer','data-id'=>$venda['Venda']['id']));

						//	echo $this->html->image('tb-edit.png',array('data-idvenda-id'=>$venda['Venda']['id'],'class'=>'bt-tabela editvenda ','data-id'=>$venda['Venda']['id']));

							echo $this->Form->postLink(
								  $this->Html->image('btncancelar.png', array('class'=>'bt-tabela','alt' => __('Cancelar'))), //le image
								  array('controller'=>'vendas','action' => 'cancelarvenda', $venda['Venda']['id']), //le url
								  array('escape' => false), //le escape
								  __('Deseja Cancelar o  %s?', $venda['Venda']['id'])
							);

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
			<h3>Venda</h3>
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
						<input type="hidden" name="data[Venda][id]" id="cancelId" />
						 <div class="form-group">
						 	<label >Motivo:</label>
							<textarea class="form-control" rows="5"  name="data[Venda][motivocancela]"  id="motivoCancel"></textarea>
						 </div>
						<button type="button" class="btn" id="cancelarVenda">Enviar</button>
					</form>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default">Salvar</button>
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
				<input type="hidden" name="data[Mensagen][venda_id]" id="idvendamsg" />
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

<div id="loadModalVenda">
</div>

<script type="text/javascript">
$(document).ready(function() {
		//var urlInicio      = window.location.host;
	loja = $('#filterMinhaslojas').val();
	$('#idfilialmsg').val(loja);
	var urlInicio      = window.location.host;
	urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp_sistema': urlInicio);







});
</script>
<style media="screen">
	.cusrorPointer{
		cursor: pointer;
	}
	table{
		text-align: center;
	}
</style>
