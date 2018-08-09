<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Visualizar Bairro</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Bairro',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('id');
						echo $this->Form->input('Bairro',array('default' => $bairro['Bairro']['bairro'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Bairro:')));
						echo $this->Form->input('Bairro',array('default' => number_format($bairro['Bairro']['valor'],2,',','.'),'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Valor da Entrega:')));
						echo $this->Form->input('cobertura_total');
						echo $this->Form->input('ativo');

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
