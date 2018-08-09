<style>
	.modal-Atendente .modal-dialog{
		width: 655px !important;
	}
	.status-atendenteView{
		width: 100px;
		height: 25px;
		line-height: 24px;
	}

	.status-atendenteView label{
		float: left;
		width: auto;
		height: auto;
	}
	.status-atendenteView .checkbox input{
		float: right !important;
		height: 20px !important;
		left: 15px;
	}

</style>
<!-- Modal -->
	<div class="modal fade modal-grande modal-Atendente" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Atendente</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
	  			<div class="circulodivGrande">

					<?php

						if(isset($atendente['Atendente']['foto']) && $atendente['Atendente']['foto'] != ''){
					?>
							<img src=<?php echo $atendente['Atendente']['foto'];?> alt=<?php echo $atendente['Atendente']['foto']; ?> title=<?php echo $atendente['Atendente']['foto']; ?>width="100px" height="100px"/>
					<?php
						}else{
							echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'100px','height'=>'100px'));

						}
					?>
				</div>
				<div >

					<?php

						echo $this->Form->create('Atendente',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Codigo',array('default'=> $atendente['Atendente']['id'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Código:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('username',array('default'=> $atendente['Atendente']['username'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Login:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('password',array('default'=> $atendente['Atendente']['password'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('nome',array('default'=> $atendente['Atendente']['nome'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Nome:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cpf',array('default'=> $atendente['Atendente']['cpf'],'readonly' => 'readonly','class' => 'input-default cpf','label' => array('text' => 'CPF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cep',array('default'=> $atendente['Atendente']['cep'],'readonly' => 'readonly','class' => 'input-default cep','label' => array('text' => 'Cep:')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('endereco',array('default'=> $atendente['Atendente']['endereco'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Endereço:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('bairro',array('default'=> $atendente['Atendente']['bairro'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Bairro:')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cidade',array('default'=> $atendente['Atendente']['cidade'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Cidade:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('uf',array('default'=> $atendente['Atendente']['uf'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'UF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('telefone',array('default'=> $atendente['Atendente']['telefone'],'readonly' => 'readonly','class' => 'input-default telefone','label' => array('text' => 'Telefone :')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('celular',array('default'=> $atendente['Atendente']['celular'],'readonly' => 'readonly','class' => 'input-default celular','label' => array('text' => 'Celular :')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('email',array('default'=> $atendente['Atendente']['email'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Email :')));
						?>
					</div>

					<div class="status-atendenteView form-group  form-group-lg">
						<?php
						echo $this->Form->input('ativo',array('default'=> $atendente['Atendente']['ativo'],'readonly' => 'readonly','class' => '','label' => array('text' => 'Ativo :')));
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
