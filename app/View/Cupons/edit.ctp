<!-- Modal -->
	<div class="modal fade modal-grande modalEditar" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Cupon</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Cupon',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('id');
						echo $this->Form->input('status',array(
							'type'=>'select',
							'options'=> array(
								'Disponível' => 'Disponível',
								'Utiliazado' => 'Utilizado',
								'Cancelado' => 'Cancelado'
							),
							'class' => 'input-default',
							'label' => array(
								'text' => 'Situação:'
							)
						)
					);

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
	<style media="screen">
		.modalEditar .modal-dialog{
			    width: 234px;
		}
	</style>
		<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#CuponEditForm').submit();
	});
});
	</script>
