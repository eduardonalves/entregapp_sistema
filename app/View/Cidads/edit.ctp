<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Cidade</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Cidad',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('id');
						echo $this->Form->input('cidade',array('class' => 'input-large','label' => array('text' => 'Nome', 'class' => 'labellarge')));
						echo $this->Form->input('estado_id',array('options'=> $estados,'type'=> 'select','class' => 'input-large','label' => array('text' => 'Estado', 'class' => 'labellarge')));
						echo $this->Form->input('valor',array('type'=> 'text','class' => 'input-large','label' => array('text' => 'Valor da Entrega', 'class' => 'labellarge')));

						echo $this->Form->input('cobertura_total',array('class' => 'input-large','label' => array('text' => 'Cobertura Total', 'class' => 'labellarge')));
						echo $this->Form->input('ativo',array('class' => 'input-large','label' => array('text' => 'Ativo', 'class' => 'labellarge')));

						echo $this->Form->input('filial_id',array('type' => 'hidden'));
						?>
					</div>
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
		<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#CidadEditForm').submit();
	});
});
	</script>
