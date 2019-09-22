<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Visualizar Estado</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Estado',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('id',array('value'=> $estado['Estado']['id'], 'readonly'=> 'readonly'));
						echo $this->Form->input('estado',array('value'=> $estado['Estado']['estado'], 'readonly'=> 'readonly'));
						echo $this->Form->input('valor',array('value'=> $estado['Estado']['valor'], 'readonly'=> 'readonly'));
						echo $this->Form->input('cobertura_total',array('label'=> array('text'=>'Cobertura Total'),'type'=>'checkbox','value'=> $estado['Estado']['cobertura_total'], 'readonly'=> 'readonly'));
						echo $this->Form->input('ativo',array('label'=> array('text'=>'Ativo'),'type'=>'checkbox','value'=> $estado['Estado']['ativo'], 'readonly'=> 'readonly'));

						?>
					</div>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>
		<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#PagamentoEditForm').submit();
	});
});
	</script>
