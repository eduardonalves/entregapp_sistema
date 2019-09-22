<!-- Modal -->
	<div class="modal fade modal-grande modal-users" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Usuários</h4>
	      </div>
	      <div class="modal-body">

	  			<div class="circulodivGrande">

					<?php
					$user = $this->request->data;
						if(isset($user['User']['foto']) && $user['User']['foto'] != ''){
					?>
							<img src=<?php echo $user['User']['foto'];?> alt=<?php echo $user['User']['foto']; ?> title=<?php echo $user['User']['foto']; ?>width="100px" height="100px"/>
					<?php
						}else{
							echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'100px','height'=>'100px'));

						}
					?>
				</div>
				<div >

					<?php

						echo $this->Form->create('User',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>

					<?php
						echo $this->Form->input('id',array('class' => 'input-default','label' => array('text' => 'Código:')));
					?>

					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('username',array('class' => 'input-default','label' => array('text' => 'Login:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('nome',array('class' => 'input-default','label' => array('text' => 'Nome:')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('password',array('class' => 'input-default','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('funcao_id',array('class' => 'input-funcao','label' => array('text' => 'Função:', 'class'=>'control-label selectcake')));
						?>
					</div>
					<div class="  form-group ">
						<?php
							echo $this->Form->input('empresa_id', array('class' => 'input-default','label' => array('text' => 'Empresa: ')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						// output all the checkboxes at once
						        echo $this->Form->input(
							  'UsersFilial.filial_id',
							  array(
							      'type' => 'select',
							      'multiple' => true,
							      'options' => $filiais,
							      'selected' => $selecionadas
							  )
							);
						?>
					</div>
					<div class="status-user form-group  form-group-lg">
						<?php
						echo $this->Form->input('ativo',array('class' => '','label' => array('text' => 'Ativo:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto',array('class' => 'input-default','type'=>'file','label' => array('text' => 'Foto:')));
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
<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#UserEditForm').submit();
	});

});

</script>
