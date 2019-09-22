<style>
	#modalBodyEntrega{height: 130px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}
	th{
		background-color:#E87C00;
		color:#FFFFFF;

	}
table {margin-top: 10px; font-size:80%;}



.table-striped tbody tr:nth-child(odd) td {
   background-color: #E8E8E8;
}

.table-striped tbody tr:nth-child(even) td {
   background-color: #F9F9F9;
}
table{
	text-align: center;
}
</style>
<h2><?php echo __('Cadastro de Entrega'); ?></h2>
	<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Entrega</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Entrega',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('identificacao',array('class' => 'input-large','label' => array('text' => 'Entrega', 'class' => 'labellarge')));
						?>
					</div>

					<div class="form-group  form-group-lg">
						<?php

						echo $this->Form->input('atendente_id',array('class' => '','label' => array('text' => 'Atendente', 'class' => '')));
						?>
					</div>
					<div class="form-group  form-group-lg">
					<?php
						//echo $this->Form->input('destaque',array('type'=>'checkbox','label' => array('text' => 'Destaque')));
						echo $this->Form->input('ativo',array('type'=>'checkbox','label' => array('text' => 'Ativo')));
						echo $this->Form->input('filial_id',array('type' => 'hidden'));
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
		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Nova Entrega</button>
		</div>
		<?php
			echo $this->Search->create('Entrega', array('class'=> 'form-inline'));
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
		<div class="area-tabela"  id="no-more-tables">
				<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
					<thead class="cf">
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('identificacao', 'identificacao');?></th>
							<th><?php echo $this->Paginator->sort('Atendente.nome', 'Atendente');?></th>
								<th><?php echo $this->Paginator->sort('ativo', 'ativo');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($entregas as $entrega): ?>
						<tr>
							<td data-title="Código"><?php echo h($entrega['Entrega']['id']); ?></td>
							<td data-title="Identificacao"><?php echo h($entrega['Entrega']['identificacao']); ?></td>
							<td data-title="Atendente"><?php echo h($entrega['Atendente']['nome']); ?></td>
							<td data-title="ativo"><?php echo ($entrega['Entrega']['ativo'] == true ? 'SIM': 'NÃO'); ?></td>
							<td data-title="Actions">

								<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$entrega['Entrega']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$entrega['Entrega']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$entrega['Entrega']['id'],'class'=>'bt-tabela editModal','data-id'=>$entrega['Entrega']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'entregas','action' => 'delete', $entrega['Entrega']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir a entrega  %s?', $entrega['Entrega']['identificacao'])
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
		'format' => __('Página {:page} de {:pages}, Visualisando {:current} de {:count} resultados.')
		));
		echo "<br>";
		echo $this->Paginator->counter(array(
		'format' => __('Inicio no registro {:start}, fim no {:end}.')
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
<script>
$(document).ready(function() {
loja = $('#filterMinhaslojas').val();
	 $('#EntregaFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
if(urlInicio=="localhost" ){
	urlInicio= "localhost/entregapp_sistema";	
} 
$('body').on('click','.editModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');
		$(this).attr('src','http://'+urlInicio+'/img/ajax-loader.gif');

	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/entregas/edit/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			 $('.editModal').attr('src','http://'+urlInicio+'/img/tb-edit.png');
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
	$('body').on('click','.viewModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/entregas/view/'+modalid+'', function(){
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

/*$("#responsive").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});*/
});


</script>
