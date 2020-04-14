<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Partida</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Partida',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('id');
						echo $this->Form->input('nome',array('class' => 'input-default','label' => array('text' => 'Partida:')));
						echo $this->Form->input('destaque',array('type'=>'checkbox','label' => array('text' => 'Destaque')));
							echo $this->Form->input('ativo',array('type'=>'checkbox','label' => array('text' => 'Ativo')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<label for"foto">Foto:</label>
						<?php
							echo $this->Form->file('foto',array('class' => 'input-foto limpa ','label' => 'Foto:'));
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
		$('#PartidaEditForm').submit();
	});
});
	</script>
