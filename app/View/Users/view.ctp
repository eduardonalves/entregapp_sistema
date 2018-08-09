<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Visualizar Usuários</h4>
	      </div>
	      <div class="modal-body">

	  			<div class="circulodivGrande">

					<?php
					 $this->request->data=$user;
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



					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('username',array('readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Username:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('nome',array('readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Nome:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('filial_id',array('type'=>'text','value'=> $filiais,'readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Loja:', 'class'=>'control-label selectcake')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php


						echo $this->Form->input('funcao_id',array('type'=>'text','value'=> $funcao['Funcao']['funcao'],'readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Função:', 'class'=>'control-label selectcake')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('password',array('readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('ativo',array('readonly' => 'readonly','class' => 'input-large','label' => array('text' => 'Ativo:')));
						?>
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
