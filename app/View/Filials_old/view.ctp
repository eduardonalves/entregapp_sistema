<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Categorias</h4>
	      </div>
	      <div class="modal-body">


				<div >

					<?php

						echo $this->Form->create('Categoria',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>



					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('nome',array('value'=> $categoria['Categoria']['nome'],'readonly'=>'readonly','class' => 'input-large','label' => array('text' => 'Nome:')));
						?>
					</div>


	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>


	      </div>
	    </div>
	  </div>
	</div>
