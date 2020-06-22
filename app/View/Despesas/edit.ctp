<style>
	
</style>
<!-- Modal -->
<div class="modal fade modal-grande modal-Funcao" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"> Editar Conta</h4>
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
						echo $this->Form->input('data_vencimento', array('type' => 'text', 'class' => 'input-default data','autocomplete'=>'off', 'label' => array('text' => 'Vencimento')));
						?>
					</div>
					<div class="form-group col-md-12">
						<?php
						echo $this->Form->input('obs', array('type' => 'textarea', 'rows' => '3', 'cols' => '10', 'label' => array('text' => 'Observações')));
						?>
					</div>
					<div class="form-group col-md-2">
						<?php
						echo $this->Form->input('recorrente', array('id'=>'recorrenteEdit','label' => array('text' => 'Recorrente')));
						?>
					</div>
					<div class="form-group col-md-3 nonerecorrenteedit">
						<?php
						echo $this->Form->input('tiposrecorrencia_id', array('class' => 'input-default', 'options' => $tiposrecorrencia, 'label' => array('text' => 'Periodicidade')));
						?>
					</div>
					<div class="form-group  col-md-4 nonerecorrenteedit">
						<?php
						echo $this->Form->input('data_prox_vencimento', array('type' => 'text',  'class'=>'data','autocomplete'=>'off','label' => array('text' => 'Próx. Vencimento', 'class' => 'label-large')));
						?>
					</div>
					<div class="form-group  col-md-4 nonerecorrenteedit">
						<?php
						echo $this->Form->input('data_recorrente_processar', array('type' => 'text',  'class'=>'data','autocomplete'=>'off','label' => array('text' => 'Próx. data Criação', 'class' => 'label-large')));
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
					<div class="form-group  ">


						<?php
						echo $this->Form->input('id');
						?>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				<button type="submit" class="btn btn-default" id="btn-salvar">Salvar</button>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	

	.modal-body {
		max-height: 600px !important;
		overflow-y: scroll !important;
	}

	.label-large {
		width: 187px !important;
	}
	
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('body').on('click', '#btn-salvar', function(event) {
			event.preventDefault();
			$('#DespesaEditForm').submit();
		});
		$('.data').datepicker({
					format: 'dd/mm/yyyy',                
					language: 'pt-br'
				});
	});
	setTimeout(function(){
		if ($('#recorrenteEdit').is(':checked')) {
			$('.nonerecorrenteedit').show();
		}else{
			$('.nonerecorrenteedit').hide();
		}
	},100);
	
	$('body').on('click', '#recorrenteEdit', function(event) {
			//alert();
			if ($('#recorrenteEdit').is(':checked')) {
				$('.nonerecorrenteedit').show();
				//console.log('mostra');
			}else{
				//console.log('esconde');
				$('.nonerecorrenteedit').hide();
			}
	});
</script>