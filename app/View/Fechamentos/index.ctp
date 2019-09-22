

	<h2><?php echo __('Caixas'); ?></h2>

	<?php
		echo $this->Search->create('Fechamento', array('class'=> 'form-inline'));
	?>

	<section class="row-fluid">

		<div class="span3">
			<?php
				echo $this->Search->input('codigo', array('label' => 'Código:','class'=>'filtroFechamento input-default', 'required' =>'false'));
			?>
		</div>

		<div class="span4">
			<label>Data de até:</label>
			<br>
			<?php
				echo $this->Search->input('dataFechamento', array('id'=>'dataFechamento','label' => false,'class'=>'filtroFechamento data'));
				echo "<span class='separador-filter'>a</span>";
			?>


		</div>
		<div class="span2">
			<?php
				echo $this->Search->end(__('Filtrar', true));
			?>
		</div>

	</section>

	<section class="row-fluid">

		

	</section>



	<!--<div class="form-group  form-group-lg">
		<button type="button" class="btn btn-success addfechamento" id="addfechamento">Nova Fechamento</button>
	</div>-->


	<style>
	input#dataFechamento, input#filterDataFechamento-between {
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
		#fechamentosNovos-qtd{
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
		<?php echo $this->Html->image('ajax-loader.gif', array('id' => 'loaderGif', 'class' => ' loaderFechamento', 'alt' => 'Loader', 'title' => 'Loader')); ?>
	</div>
	<div class="area-tabela"  id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
			<thead class="cf">
				<tr>

						<th><?php echo $this->Paginator->sort('Fechamento.id','Movimento'); ?></th>


						<th><?php echo $this->Paginator->sort('data'); ?></th>

						<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<?php $cliente_idAux=''; ?>
			<?php foreach ($fechamentos as $fechamento): ?>
			<tr>

				<td data-title="Código"><?php echo h($fechamento['Fechamento']['id']); ?>&nbsp;</td>


				<?php
					$dataAntiga = $fechamento['Fechamento']['data'];
					$novaData = date("d/m/Y", strtotime($dataAntiga));

				?>
				

				<td   data-title="Data" ><?php echo  $novaData; ?>&nbsp;</td>
				
				<td  data-title="Actions" >



					<?php
						echo $this->html->image('tb-ver.png',array('data-idfechamento'=>$fechamento['Fechamento']['id'],'class'=>'bt-tabela ver viewModal viewfechamento cusrorPointer','data-id'=>$fechamento['Fechamento']['id']));

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
			<h3>Fechamento</h3>
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
						<input type="hidden" name="data[Fechamento][id]" id="cancelId" />
						 <div class="form-group">
						 	<label >Motivo:</label>
							<textarea class="form-control" rows="5"  name="data[Fechamento][motivocancela]"  id="motivoCancel"></textarea>
						 </div>
						<button type="button" class="btn" id="cancelarFechamento">Enviar</button>
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
				<input type="hidden" name="data[Mensagen][fechamento_id]" id="idfechamentomsg" />
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

<div id="loadModalFechamento">
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
