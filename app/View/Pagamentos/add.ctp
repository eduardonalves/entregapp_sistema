<style>
	#modalBodyPagamento{height: 130px; overflow: hidden; padding-top: 73px; }
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

</style>
<h2><?php echo __('Cadastro de Formas de Pagamento'); ?></h2>
	<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Pagamento</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Pagamento',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('tipo',array('class' => 'input-large','label' => array('text' => 'Forma de Pagamento', 'class' => 'labellarge')));
						?>
						<?php
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
			<button type="button" class="btn btn-success" id="clickmodal">Nova Forma de Pgto</button>
		</div>
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
		<div class="area-tabela"  id="no-more-tables">
				<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
					<thead class="cf">
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('tipo', 'Tipo');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($pagamentos as $pagamento): ?>
						<tr>
							<td data-title="Código"><?php echo h($pagamento['Pagamento']['id']); ?></td>
							<td data-title="Tipo"><?php echo h($pagamento['Pagamento']['tipo']); ?></td>

							<td data-title="Actions">

								<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$pagamento['Pagamento']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$pagamento['Pagamento']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$pagamento['Pagamento']['id'],'class'=>'bt-tabela editModal','data-id'=>$pagamento['Pagamento']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'pagamentos','action' => 'delete', $pagamento['Pagamento']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir a forma de pagamento  %s?', $pagamento['Pagamento']['tipo'])
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
	 $('#PagamentoFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
$('body').on('click','.editModal', function(event){
		event.preventDefault();
		$(this).attr('src','/img/ajax-loader.gif');
		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/pagamentos/edit/'+modalid+'', function(){
			$('.editModal').attr('src','/img/tb-edit.png');
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
	$('body').on('click','.viewModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/pagamentos/view/'+modalid+'', function(){
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