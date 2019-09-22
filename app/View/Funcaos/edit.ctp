<style>
.modal-Funcao .modal-dialog{ 
  width: 815px !important;
}
.modal-Funcao .modal-body{ 
  max-height: 450px !important;
}
</style>
<!-- Modal -->
	<div class="modal fade modal-grande modal-Funcao" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Função</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Funcao',array('class' => 'form-inline  centralizadoForm'));

						echo $this->Form->input('id');
					?>

					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('funcao',array('type'=>'text','class' => 'input-default','label' => array('text' => 'Função')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							$opts = array(
								'n'=> 'N/A',
								'v' => 'Visualizar',
								'm' => 'Modificar',
								'g' => 'Gestor',
								'a' => 'Adm',
							);
							echo $this->Form->input('Autorizacao.0.pedidos',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Pedidos:', 'class'=>'label-large')));
						?>	
					</div>	
					<br />
					<?php	
							echo $this->Form->input('Autorizacao.0.id');
						?>
					<div class="form-group  form-group-lg">
						<?php	
							echo $this->Form->input('Autorizacao.0.mensagens',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Mensagens:', 'class'=>'label-large')));
						?>	
					</div>
					<br />	
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.mapas',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Mapas:', 'class'=>'label-large')));
						?>	
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.clientes',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Clientes:', 'class'=>'label-large')));
						?>	
					</div>	
					<br />
					<div class="form-group  form-group-lg">
						<?php	
							echo $this->Form->input('Autorizacao.0.produtos',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Produtos:', 'class'=>'label-large')));
						?>	
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.entregadores',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Entregadores:', 'class'=>'label-large')));
						?>	
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.formas_de_pagamento',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Formas de Pagamento:', 'class'=>'label-large')));
					?>	
					</div>
					<br />	
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.usuarios',array('type'=>'radio', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Usuários:', 'class'=>'label-large')));
					?>
					</div>	
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.funcoes',array('type'=>'radio','default'=> 'n', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Funções:', 'class'=>'label-large')));
					?>
					</div>	
					<?php
						$boleaopts = array(
							'n'=> 'N/A',
							'm' => 'Modificar',
							
						);
					?>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.confirmar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Pedidos:', 'class'=>'label-large')));
					?>
					</div>
					<br />	
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.preparar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Preparo:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.separar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Separação:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.enviar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Envio:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.entregar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Entrega:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.finalizar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Finalizar Entrega:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.cancelar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Cancelar Pedidos:', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php	
							echo $this->Form->input('Autorizacao.0.relatorios',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large ','label' => array('text' => 'Acessar Relatórios:', 'class'=>'label-large')));
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
<style type="text/css">
.modal-dialog {
  width: 820px !important;
  
	
  
}
.modal-body{
	max-height: 600px !important;
	overflow-y:scroll !important; 	
}
.label-large{
	width: 187px !important;
}
</style>
		<script type="text/javascript">
$(document).ready(function() {	
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#FuncaoEditForm').submit();
	});
});
	</script>
