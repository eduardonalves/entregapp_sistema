<style>
	#modalBodyEstado{height: 130px; overflow: hidden; padding-top: 73px; }
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
<h2><?php echo __('Cadastro Estados'); ?></h2>
	<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Estado</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Estado',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('estado',array('type'=> 'text','class' => 'input-large','label' => array('text' => 'Nome', 'class' => 'labellarge')));
						echo $this->Form->input('valor',array('type'=> 'text','class' => 'input-large','label' => array('text' => 'Valor da Entrega', 'class' => 'labellarge')));


						echo $this->Form->input('ativo',array('class' => 'input-large','label' => array('text' => 'Ativo', 'class' => 'labellarge')));
						echo $this->Form->input('cobertura_total',array('class' => 'input-large','label' => array('text' => 'Cobertura Total', 'class' => 'labellarge')));
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
		
		<?php
			echo $this->Search->create('Estado', array('class'=> 'form-inline'));
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
		<br/>
		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Novo Estado</button>
		</div>
		<div class="area-tabela"  id="no-more-tables">
				<table class="table-action  col-md-12 table-bordered table-striped table-condensed cf" >
					<thead class="cf">
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('estado', 'Estado');?></th>
							<th><?php echo $this->Paginator->sort('ativo', 'Status');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($estados as $estado): ?>
						<?php
	                        $disabledline = ($estado['Estado']['ativo']== 0 ? 'disabledline': '');
	                    ?>
						<tr class="<?php echo $disabledline; ?>">
							<td data-title="Código"><?php echo h($estado['Estado']['id']); ?></td>
							<td data-title="Estado"><?php echo h($estado['Estado']['estado']); ?></td>
							<td data-title="Status">
								<?php
									if($estado['Estado']['ativo']==1){
										echo 'Ativo';
									}else{
										echo 'Desabilitado';
									}
								 ?>
							</td>
							<td data-title="Actions">

								<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$estado['Estado']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$estado['Estado']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$estado['Estado']['id'],'class'=>'bt-tabela editModal','data-id'=>$estado['Estado']['id']));
									echo $this->Form->postLink(
										  $this->Html->image('tb-desabilitar.png', array('class'=>'bt-tabela','alt' => __('Desabilitar'))), //le image
										  array('controller'=>'estados','action' => 'disable', $estado['Estado']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja habilitar/desabilitar o estado  %s?', $estado['Estado']['estado'])
									);
									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'estados','action' => 'delete', $estado['Estado']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir o estado  %s?', $estado['Estado']['estado'])
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
	 $('#EstadoFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
if(urlInicio=="localhost" ){
	urlInicio= "localhost/entregapp_sistema";	
} 
$('body').on('click','.editModal', function(event){
		event.preventDefault();
		$(this).attr('src','http://'+urlInicio+'/img/ajax-loader.gif');
		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/estados/edit/'+modalid+'', function(){
			$('.editModal').attr('src','http://'+urlInicio+'/img/tb-edit.png');
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
		$("#loadDivModal").load('http://'+urlInicio+'/estados/view/'+modalid+'', function(){
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