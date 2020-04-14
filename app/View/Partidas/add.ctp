<style>
	#modalBodyPartida{height: 130px; overflow: hidden; padding-top: 73px; }
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
<h2><?php echo __('Cadastro de Partida'); ?></h2>



<div class="row-fluid">

	<?php
	echo $this->Search->create('Partida', array('class'=> 'form-inline'));
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

	<div class="area-tabela"  id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
			<thead class="cf">
				<tr>
					<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
					<th><?php echo $this->Paginator->sort('nome', 'nome');?></th>
					<th>Foto</th>
					<th><?php echo $this->Paginator->sort('ativo', 'Status');?></th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($partidas as $partida): ?>
					<?php
					$disabledline = ($partida['Partida']['ativo']== 0 ? 'disabledline': '');
					?>
					<tr class="<?php echo $disabledline; ?>">
						<td data-title="Código"><?php echo h($partida['Partida']['id']); ?></td>
						<td data-title="Nome"><?php echo h($partida['Partida']['nome']); ?></td>
						<td>
							<div class="circulodivPequeno">
								<?php
								if(isset($partida['Partida']['foto']) && $partida['Partida']['foto'] != ''){
									?>
									<img src=<?php echo $partida['Partida']['foto'];?> alt=<?php echo $partida['Partida']['nome'];?> width="50px" height="50px" title=<?php echo $partida['Partida']['nome'];?> width="50px" height="50px"/>
									<?php
								}else{
									echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'50px','height'=>'50px'));

								}
								?>
							</div>
						</td>
						<td data-title="Status">
							<?php
							if($partida['Partida']['ativo']==1){
								echo 'Ativo';
							}else{
								echo 'Desabilitado';
							}
							?>

						</td>
						<td data-title="Actions">

							<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$partida['Partida']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$partida['Partida']['id']));

							echo $this->html->image('tb-edit.png',array('data-id'=>$partida['Partida']['id'],'class'=>'bt-tabela editModal','data-id'=>$partida['Partida']['id']));
							echo $this->Form->postLink(
										  $this->Html->image('tb-desabilitar.png', array('class'=>'bt-tabela','alt' => __('Desabilitar'))), //le image
										  array('controller'=>'partidas','action' => 'disable', $partida['Partida']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja desabilitar a partida  %s?', $partida['Partida']['nome'])
										);
							echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'partidas','action' => 'delete', $partida['Partida']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja remover a partida  %s?', $partida['Partida']['nome'])
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
						$('#PartidaFilialId').val(loja);
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
	$("#loadDivModal").load('http://'+urlInicio+'/partidas/edit/'+modalid+'', function(){
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
	$("#loadDivModal").load('http://'+urlInicio+'/partidas/view/'+modalid+'', function(){
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
