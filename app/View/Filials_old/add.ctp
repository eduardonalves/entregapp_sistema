<style>
	#modalBodyCadastrarUsuario{height: 285px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}



</style>
<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Categoria</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Categoria',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));
					?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('nome',array('class' => 'input-large limpa','label' => array('text' => 'Nome')));
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
		<h2><?php echo __('Cadastro de Categorias'); ?></h2>
		<button type="button" class="btn btn-success" id="clickmodal">Novo</button>

		<div class="area-tabela">
				<table class="table-action" >
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>


							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($categorias as $categoria): ?>
						<tr>
							<td><?php echo h($categoria['Categoria']['id']); ?></td>
							<td><?php echo h($categoria['Categoria']['nome']); ?></td>
							<td>
								<?php
									echo $this->html->image('tb-ver.png',array('data-id'=>$categoria['Categoria']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$categoria['Categoria']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$categoria['Categoria']['id'],'class'=>'bt-tabela editModal','data-id'=>$categoria['Categoria']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Categorias','action' => 'delete', $categoria['Categoria']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir o  %s?', $categoria['Categoria']['nome'])
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
		$("#loadDivModal").load('http://'+urlInicio+'/categorias/view/'+modalid+'', function(){
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
			$("#loadDivModal").load('http://'+urlInicio+'/categorias/edit/'+modalid+'', function(){
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