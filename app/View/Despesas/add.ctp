<style>
	#modalBodyPagamento {
		height: 130px;
		overflow: hidden;
		padding-top: 73px;
	}

	.close {
		color: #fff;
	}

	th {
		background-color: #E87C00;
		color: #FFFFFF;

	}

	table {
		margin-top: 10px;
		font-size: 80%;
	}



	.table-striped tbody tr:nth-child(odd) td {
		background-color: #E8E8E8;
	}

	.table-striped tbody tr:nth-child(even) td {
		background-color: #F9F9F9;
	}
</style>

<h2><?php echo __('Controle de Despesas'); ?></h2>
<!-- Modal -->
<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> Cadastrar Contas a Pagar</h4>
			</div>
			<div class="modal-body">
				<div class="row-fluid">

					<?php echo $this->Form->create('Despesa', array('class' => ''));
					?>
					<div class="form-group col-md-4">
						<?php
						echo $this->Form->input('despesa', array('type' => 'text', 'class' => 'input-default ', 'label' => array('text' => 'Conta')));
						?>
					</div>
					<div class="form-group  col-md-4">
						<?php
						echo $this->Form->input('valor', array('type' => 'text', 'class' => 'input-default ', 'label' => array('text' => 'Valor')));
						?>
					</div>
					<div class="form-group  col-md-4">
						<?php
						echo $this->Form->input('data_vencimento', array('type' => 'text', 'class' => 'input-default', 'label' => array('text' => 'Vencimento')));
						?>
					</div>
					<div class="form-group col-md-12">
						<?php
						echo $this->Form->input('obs', array('type' => 'textarea', 'rows' => '3', 'cols' => '10', 'label' => array('text' => 'Observações')));
						?>
					</div>
					<div class="form-group col-md-2">
						<?php
						echo $this->Form->input('recorrente', array('label' => array('text' => 'Recorrente')));
						?>
					</div>
					<div class="form-group col-md-3 nonerecorrente">
						<?php
						echo $this->Form->input('tiposrecorrencia_id', array('class' => 'input-default', 'options' => $tiposrecorrencia, 'label' => array('text' => 'Periodicidade')));
						?>
					</div>
					<div class="form-group  col-md-4 nonerecorrente">
						<?php
						echo $this->Form->input('data_prox_vencimento', array('type' => 'text', 'label' => array('text' => 'Próx. Vencimento', 'class' => 'label-large')));
						?>
					</div>
					<div class="form-group  col-md-4 nonerecorrente">
						<?php
						echo $this->Form->input('data_recorrente_processar', array('type' => 'text', 'label' => array('text' => 'Próx. data Criação', 'class' => 'label-large')));
						?>
					</div>
					<div class="form-group col-md-3">
						<?php
						echo $this->Form->input('categoriasdespesa_id', array('options' => $categoriasdespesa, 'class' => 'input-default', 'label' => array('text' => 'Categoria')));
						?>

					</div>
					<div class="form-group col-md-2">
						<?php
						echo $this->Form->input('status_pago', array('label' => array('text' => 'Pago')));
						?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-default">Salvar</button>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>



	<div class="row-fluid">
		<?php
		echo $this->Search->create('Despesa', array('class' => 'form-inline'));
		?>

		<div class="col-md-2">
			<?php
			

			echo $this->Search->input('minhaslojas', array('label' => 'Loja', 'class' => 'filtroPedido input-default ', 'required' => 'false'));
			
			?>
			
		</div>
		
		<div class="col-md-4">
				<label>Data de até:</label>
				<br/>
				<?php	
					echo $this->Search->input('dataPedido', array('id'=>'dataPedido','label' => false,'class'=>'filtroPedido data'));
					echo "<span class='separador-filter'>a</span>";

					
				?>


		</div>
		<div class="col-md-2">
			<?php	
			echo $this->Search->input('recorrente', array('label' => 'Recorrente', 'class' => 'filtroPedido input-default ', 'required' => 'false'));
			?>
		</div>
		<div class="col-md-2">
			<?php	
			echo $this->Search->input('status_pago', array('label' => 'Situação', 'class' => 'filtroPedido input-default ', 'required' => 'false'));
			?>
		</div>
		<div class="col-md-2">
			<?php	
			echo $this->Search->input('categoriasdespesa', array('label' => 'Categoria', 'class' => 'filtroPedido input-default ', 'required' => 'false'));
			?>
		</div>
		<div class="col-md-2">
			<?php	
			echo $this->Search->input('despesa', array('type'=>'text','label' => 'Despesa', 'class' => 'input-default ', 'required' => 'false'));
			?>
		</div>
		<div class="col-md-2 " style="margin-top: 15px;">
			<?php
			echo $this->Search->end(__('Pesquisar', true));
			?>
		</div>
	</div>
	
	<div class="row-fluid">
	<div class="col-md-2 " style=" margin-top: 13px;">
		<button type="button" class="btn btn-success" id="clickmodal">Nova</button>
	</div>
	<br>
	<br>
	<div class="area-tabela" id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf">

			<thead class="cf">
				<tr>
					
					<th>Total</th>
					<th>Pago</th>
					<th>Em aberto </th>
					
				</tr>
			</thead>
			<tbody>
				
					<tr>
						<td><?php echo 'R$ '. number_format($totalGeral, 2, ',', ''); ?></td>
						<td><?php echo 'R$ '. number_format($totalPago, 2, ',', ''); ?></td>
						<td><?php echo 'R$ '. number_format($totalEmAberto, 2, ',', ''); ?></td>
					</tr>
				
			</tbody>
		</table>

	</div>

	<div class="area-tabela" id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf">

			<thead class="cf">
				<tr>
					<th><?php echo $this->Paginator->sort('id', 'Código'); ?></th>
					<th><?php echo $this->Paginator->sort('despesa', 'Despesa'); ?></th>
					<th><?php echo $this->Paginator->sort('data_vencimento', 'Vencimento'); ?></th>
					<th><?php echo $this->Paginator->sort('valor', 'Valor'); ?></th>
					
					<th><?php echo $this->Paginator->sort('Categoriasdespesa.categoria', 'Categoria'); ?></th>
					<th><?php echo $this->Paginator->sort('recorrente', 'Recorrente'); ?></th>
					<th><?php echo $this->Paginator->sort('status_pago', 'Situação'); ?></th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($despesas as $despesa) : ?>
					<tr>
						<td><?php echo h($despesa['Despesa']['id']); ?></td>
						<td><?php echo h($despesa['Despesa']['despesa']); ?></td>
						<td><?php echo  date("d/m/Y", strtotime($despesa['Despesa']['data_vencimento']));  ?></td>
						<td><?php echo  'R$ '. number_format($despesa['Despesa']['valor'], 2, ',', '') ; ?></td>
						
						<td><?php echo h($despesa['Categoriasdespesa']['categoria']); ?></td>
						<td><?php echo ($despesa['Despesa']['recorrente'] == true ? 'Sim': 'Não'); ?></td>

						<td><?php echo ($despesa['Despesa']['status_pago'] == true ? 'Pago': 'Em Aberto'); ?></td>
						<td>

							<?php
							//echo $this->html->image('tb-ver.png', array('data-id' => $despesa['Despesa']['id'], 'class' => 'bt-tabela ver viewModal', 'data-id' => $despesa['Despesa']['id']));

							echo $this->html->image('tb-edit.png', array('data-id' => $despesa['Despesa']['id'], 'class' => 'bt-tabela editModal', 'data-id' => $despesa['Despesa']['id']));

							echo $this->Form->postLink(
									$this->Html->image('tb-confirmar.png', array('class'=>'bt-tabela bt-entrega','alt' => __('Enviar'))), //le image
									array('controller'=>'Despesas','action' => 'confirmarpagamento', $despesa['Despesa']['id']), //le url
									array('escape' => false), //le escape
									__('Mudar a situação da conta para %s para pago?', $despesa['Despesa']['id'])
							);

							echo $this->Form->postLink(
								$this->Html->image('tb-excluir.png', array('class' => 'bt-tabela', 'alt' => __('Excluir'))), //le image
								array('controller' => 'Despesas', 'action' => 'delete', $despesa['Despesa']['id']), //le url
								array('escape' => false), //le escape
								__('Deseja Excluir a função  %s?', $despesa['Despesa']['despesa'])
							);

							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</div>
	</div>
<div class="row-fluid">
	<p>
		<?php
		echo $this->Paginator->counter(array(
			'format' => __('Página {:page} de {:pages}, Visualisando {:current} de {:count} resultados.')
		));
		echo "<br>";
		echo $this->Paginator->counter(array(
			'format' => __('Inicio no registro {:start}, fim no {:end}.')
		));
		?> </p>
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

<div id="loadDivModal"></div>
<style type="text/css">
	

	.modal-body {
		max-height: 600px !important;
		overflow-y: scroll !important;
	}

	.label-large {
		width: 220px !important;
	}
	.nonerecorrente{
		display: none;
	}
</style>
<script>
	$(document).ready(function() {
		loja = $('#filterMinhaslojas').val();
		$('#DespesaFilialId').val(loja);
		$("#clickmodal").click(function() {
			$('#responsive').modal('show');
		});
		var urlInicio = window.location.host;

		if (urlInicio == "localhost") {
			urlInicio = "localhost/entregapp_sistema";
		}
		$('body').on('click', '.editModal', function(event) {
			event.preventDefault();

			modalid = $(this).data('id');


			//	$('#loaderGif'+idpedido).show();
			//	$('#divActions'+idpedido).hide();
			$("#loadDivModal").load('http://' + urlInicio + '/despesas/edit/' + modalid + '', function() {
				$('#modalLoaded').modal('show');
				/*$('#loaderGif'+idpedido).hide();
			$('#divActions'+idpedido).show();
			 $('#counter').countdown({
	          image: 'http://'+urlInicio+'/img/digits2.png',
	          startTime: '00:10:00',
			  digitWidth: 34,
			    digitHeight: 45,
			    format: 'hh:mm:ss',
			});*/

			});
		});
		$('body').on('click', '.viewModal', function(event) {
			event.preventDefault();

			modalid = $(this).data('id');


			//	$('#loaderGif'+idpedido).show();
			//	$('#divActions'+idpedido).hide();
			$("#loadDivModal").load('http://' + urlInicio + '/despesas/view/' + modalid + '', function() {
				$('#modalLoaded').modal('show');
				/*$('#loaderGif'+idpedido).hide();
			$('#divActions'+idpedido).show();
			 $('#counter').countdown({
	          image: 'http://'+urlInicio+'/img/digits2.png',
	          startTime: '00:10:00',
			  digitWidth: 34,
			    digitHeight: 45,
			    format: 'hh:mm:ss',
			});*/

			});
		});

		/*$("#responsive").on("show", function () {
		  $("body").addClass("modal-open");
		}).on("hidden", function () {
		  $("body").removeClass("modal-open")
		});*/

		$('body').on('click', '#DespesaRecorrente', function(event) {
			
			if ($('#DespesaRecorrente').is(':checked')) {
				$('.nonerecorrente').show();
			}else{
				$('.nonerecorrente').hide();
			}
		});
	});
</script>