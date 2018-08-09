
<h2><?php echo __('Cadastro de Movimentos'); ?></h2>
	<?php
		echo $this->Search->create('Movimentos', array('class'=> 'form-inline'));
	?>
		<div class="form-group  form-group-lg">
		<?php
			echo $this->Search->input('codigo', array('label' => 'Código :','class'=>'filtroMovimento', 'required' =>'false'));
		?>
		</div>
		<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('nome', array('label' => 'Nome :','class'=>'filtroMovimento',  'required' =>'false'));
		?>
		</div>
		<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('cpf', array('label' => 'CPF :','class'=>'filtroMovimento',  'required' =>'false'));
		?>
		</div>
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

		<!-- Modal -->
	<div class="row-fluid">

		<div class="area-tabela" id="no-more-tables">
			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
					<thead class="cf">
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('data', 'Data');?></th>
							<th><?php echo $this->Paginator->sort('numero', 'Número');?></th>
							<th><?php echo $this->Paginator->sort('status', 'Status');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($movimentos as $movimento): ?>
						<tr>
							<td data-title="Código"><?php echo h($movimento['Movimento']['id']); ?></td>
							<td data-title="Data"><?php echo h($movimento['Movimento']['data']); ?></td>
							<td data-title="Número"><?php echo h($movimento['Movimento']['numero']); ?></td>
							<td data-title="Status"><?php echo h($movimento['Movimento']['status']); ?></td>

							<td data-title="Actions">
								<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$movimento['Movimento']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$movimento['Movimento']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$movimento['Movimento']['id'],'class'=>'bt-tabela editModal','data-id'=>$movimento['Movimento']['id']));

									

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
	 $('#MovimentoFilialId').val(loja);
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
		$("#loadDivModal").load('http://'+urlInicio+'/movimentos/edit/'+modalid+'', function(){
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
		$("#loadDivModal").load('http://'+urlInicio+'/movimentos/view/'+modalid+'', function(){
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
<style media="screen">
	table{
		text-align: center;
	}
</style>
