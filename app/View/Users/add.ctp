<style>
	#modalBodyCadastrarUsuario{height: 285px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}

	.modal-users .modal-dialog{
		width: 340px !important;
	}

	.input-funcao{
		display: block !important;
		width: 100% !important;
	}

	.status-user {
		width: 100px;
		height: 25px;
		line-height: 24px;
	}
	.status-user label {
		float: left;
		width: auto;
		height: auto;
	}
	.status-user .checkbox input {
		float: right !important;
		height: 20px !important;
		left: 15px;
	}

</style>
<!-- Modal -->
	<div class="modal fade modal-grande modal-users" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Usuários</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('User',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('username',array('class' => 'input-default limpa','label' => array('text' => 'Login:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('nome',array('class' => 'input-default limpa','label' => array('text' => 'Nome:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('password',array('class' => 'input-default limpa','label' => array('text' => 'Senha:')));
						?>
					</div>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('funcao_id',array('class' => 'input-funcao limpa','label' => array('text' => 'Função:')));
						?>
					</div>


					<div class="  form-group ">
						<?php
							echo $this->Form->input('empresa_id', array('class' => 'input-default','label' => array('text' => 'Empresa: ')));
						?>
					</div>

					<div class="form-group  form-group-lg ">
						<?php
						// output all the checkboxes at once
						        echo $this->Form->input(
							  'UsersFilial.filial_id',
							  array(
							      'type' => 'select',
							      'multiple' => true,
							      'options' => $filiais,
							      'Class' => ''

							  )
							);
						?>
					</div>

					<div class="status-user  form-group  form-group-lg">
						<?php
							echo $this->Form->input('ativo', array('class' => 'input-default','label' => array('text' => 'Ativo: ')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<label for="foto">Foto:</label>
						<?php
							echo $this->Form->file('foto',array('class' => 'input-foto limpa ','label' => 'Foto:'));
						?>
					</div>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default">Salvar</button>
	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>


	<div class="row-fluid">
		<h2><?php echo __('Cadastro de Usuários'); ?></h2>
		<?php
			echo $this->Search->create('Pagamento', array('class'=> 'form-inline'));
		?>


		<div class="form-group  form-group-lg">
			<?php
		echo $this->Search->input('empresa', array('label' => false,'class'=>'none', 'required' =>'false'));

		echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
		</div>
		<div class="form-group  form-group-lg">
			<?php
				echo $this->Search->end(__('Filtrar', true));
			?>

		</div>
		<br />
		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Novo</button>
		</div>
		<div class="area-tabela"  id="no-more-tables">
				<table class="table-action col-md-12 table-bordered table-striped table-condensed cf">
					<thead class="cf">
						<tr>
							<th>Código</th>
							<th>Nome de Usuário</th>
							<th>Função</th>
							<th>Ativo</th>
							<th>Foto</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
						<tr>
							<td><?php echo h($user['User']['id']); ?></td>
							<td><?php echo h($user['User']['username']); ?></td>
							<td><?php echo h($user['User']['funcao']); ?></td>

							<td>
								<?php
									if($user['User']['ativo']==1){
										echo 'Ativo';
									}else{
										echo 'Desabilidado';
									}
								 ?>
							</td>
							<td>
								<div class="circulodivPequeno">
									<?php
										if(isset($user['User']['foto']) && $user['User']['foto'] != ''){
									?>
											<img src=<?php echo $user['User']['foto'];?> alt=<?php echo $user['User']['username'];?> width="50px" height="50px" title=<?php echo $user['User']['nome'];?> width="50px" height="50px"/>
									<?php
										}else{
											echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'50px','height'=>'50px'));

										}
									?>
								</div>
							</td>
							<td>
								<?php
									echo $this->html->image('tb-ver.png',array('data-id'=>$user['User']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$user['User']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$user['User']['id'],'class'=>'bt-tabela editModal','data-id'=>$user['User']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Users','action' => 'delete', $user['User']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir o  %s?', $user['User']['username'])
									);

								?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

		</div>
	</div>
	<div class="row-fluid">
		<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>	</p>
		<nav>
  			<ul class="pagination">
			<?php
				echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->numbers(array('separator' => ''));
				echo $this->Paginator->next(__('Próxima') . ' >', array(), null, array('class' => 'next disabled'));
			?>
			</ul>
		</nav>
	</div>
<div id="loadDivModal"></div>
<style type="text/css">
.modal-dialog {
  width: 36% !important;
}
</style>
<script>
$(document).ready(function() {
	var urlInicio      = window.location.host;
	if(urlInicio=="localhost" ){
		urlInicio= "localhost/entregapp_sistema";	
	} 
	setTimeout(function(){
		$('.limpa').val('');
	}, 1000);


$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
/*$("#responsive").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});*/

	$('body').on('click','.viewModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/users/view/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			/*$('#loaderGif'+idpedido).hide();
			$('#divActions'+idpedido).show();
			 $('#counter').countdown({
	          image: 'http://'+urlInicio+'/img/digits2.png',
	          startTime: '00:10:00',
			  digitWidth: 34,
			    digitHeight: 45,
			    format: 'hh:mm:ss',
			});*/

		});
	});

	$('body').on('click','.editModal', function(event){
			event.preventDefault();

			modalid = $(this).data('id');


		//	$('#loaderGif'+idpedido).show();
		//	$('#divActions'+idpedido).hide();
			$("#loadDivModal").load('http://'+urlInicio+'/users/edit/'+modalid+'', function(){
				$('#modalLoaded').modal('show');
				/*$('#loaderGif'+idpedido).hide();
				$('#divActions'+idpedido).show();
				 $('#counter').countdown({
		          image: 'http://'+urlInicio+'/img/digits2.png',
		          startTime: '00:10:00',
				  digitWidth: 34,
				    digitHeight: 45,
				    format: 'hh:mm:ss',
				});*/

			});
		});

});


</script>
