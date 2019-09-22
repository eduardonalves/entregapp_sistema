<style>
	.modal-Entregador .modal-dialog{
		width: 655px !important;
	}
	.status-entregadorView{
		width: 100px;
		height: 25px;
		line-height: 24px;
	}

	.status-entregadorView label{
		float: left;
		width: auto;
		height: auto;
	}
	.status-entregadorView .checkbox input{
		float: right !important;
		height: 20px !important;
		left: 15px;
	}

</style>
<!-- Modal -->
	<div class="modal fade modal-grande modal-Entregador" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Entregador</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
	  			<div class="circulodivGrande">

					<?php

						if(isset($entregador['Entregador']['foto']) && $entregador['Entregador']['foto'] != ''){
					?>
							<img src=<?php echo $entregador['Entregador']['foto'];?> alt=<?php echo $entregador['Entregador']['foto']; ?> title=<?php echo $entregador['Entregador']['foto']; ?>width="100px" height="100px"/>
					<?php
						}else{
							echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'100px','height'=>'100px'));

						}
					?>
				</div>
				<div >

					<?php

						echo $this->Form->create('Entregador',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Codigo',array('default'=> $entregador['Entregador']['id'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Código:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('username',array('default'=> $entregador['Entregador']['username'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Login:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('password',array('default'=> $entregador['Entregador']['password'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('nome',array('default'=> $entregador['Entregador']['nome'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Nome:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cpf',array('default'=> $entregador['Entregador']['cpf'],'readonly' => 'readonly','class' => 'input-default cpf','label' => array('text' => 'CPF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cep',array('default'=> $entregador['Entregador']['cep'],'readonly' => 'readonly','class' => 'input-default cep','label' => array('text' => 'Cep:')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('endereco',array('default'=> $entregador['Entregador']['endereco'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Endereço:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('bairro',array('default'=> $entregador['Entregador']['bairro'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Bairro:')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cidade',array('default'=> $entregador['Entregador']['cidade'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Cidade:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('uf',array('default'=> $entregador['Entregador']['uf'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'UF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('telefone',array('default'=> $entregador['Entregador']['telefone'],'readonly' => 'readonly','class' => 'input-default telefone','label' => array('text' => 'Telefone :')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('celular',array('default'=> $entregador['Entregador']['celular'],'readonly' => 'readonly','class' => 'input-default celular','label' => array('text' => 'Celular :')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('email',array('default'=> $entregador['Entregador']['email'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Email :')));
						?>
					</div>

					<div class="status-entregadorView form-group  form-group-lg">
						<?php
						echo $this->Form->input('ativo',array('default'=> $entregador['Entregador']['ativo'],'readonly' => 'readonly','class' => '','label' => array('text' => 'Ativo :')));
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

