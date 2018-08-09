
<!-- Modal -->
	<div class="modal fade modal-grande modal-Entregador" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Entregador</h4>
	      </div>
	      <div class="modal-body">

	  			<div class="circulodivGrande">

					<?php
					$entregador = $this->request->data;
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
							echo $this->Form->input('password',array('class' => 'input-default','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('nome',array('class' => 'input-default','label' => array('text' => 'Nome:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cpf',array('class' => 'input-default cpf','label' => array('text' => 'CPF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cep',array('class' => 'input-default cep','label' => array('text' => 'Cep:')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('endereco',array('class' => 'input-default','label' => array('text' => 'Endereço:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('bairro',array('class' => 'input-default','label' => array('text' => 'Bairro:')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('cidade',array('class' => 'input-default','label' => array('text' => 'Cidade:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('uf',array('class' => 'input-default','label' => array('text' => 'UF:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('telefone',array('class' => 'input-default telefone','label' => array('text' => 'Telefone :')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('celular',array('class' => 'input-default celular','label' => array('text' => 'Celular :')));
					?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('email',array('class' => 'input-default','label' => array('text' => 'Email :')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto',array('class' => 'input-foto','type'=>'file','label' => array('text' => 'Foto :')));
						?>
					</div>
					<div class="status-entregador form-group  form-group-lg">
						<?php
						echo $this->Form->input('ativo',array('class' => '','label' => array('text' => 'Ativo :')));
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
		$('#EntregadorEditForm').submit();
	});
	setTimeout(function(){
		$('.cep').mask('99999999');
		$('.telefone').mask('(99) 9999-9999');
		$.mask.definitions['~'] = '([0-9] )?';
		$(".celular").mask("(99) 9999-9999~");
		$(".uf").mask("aa");
		$(".nasc").mask("99/99/9999");

	}, 3000);


});

</script>
