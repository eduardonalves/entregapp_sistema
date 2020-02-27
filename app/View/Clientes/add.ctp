
	<h2><?php echo __('Cadastro de Clientes'); ?></h2>
	<?php
			echo $this->Search->create('Clientes', array('class'=> 'form-inline'));
		?>

		<div class="form-group  form-group-lg">
			<?php
			echo $this->Search->input('codigo', array('label' => 'Código: ','class'=>'filtroCliente input-default', 'required' =>'false'));
			?>
		</div>

		<div class="form-group  form-group-lg">
			<?php
			echo $this->Search->input('nome', array('label' => 'Nome: ','class'=>'filtroCliente input-default',  'required' =>'false'));
			?>
		</div>

		<div class="form-group  form-group-lg">
			<?php
			echo $this->Search->input('telefone', array('label' => 'Telefone: ','class'=>'filtroCliente input-default', 'required' =>'false'));
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
	<div class="modal fade modal-grande modal-addCliente" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Clientes</h4>
	      </div>
	      <div class="modal-body">

				<span class="loadingCep" id="loadingCep"></span>
				<?php echo $this->Form->create('Cliente',array('class' => 'form-inline centralizadoForm','enctype'=>'multipart/form-data'));	?>

					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('nome',array('class' => 'input-default nome ','label' => array('text' => 'Nome*: ')));?>
					</div>

					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('telefone',array('class' => 'input-default telefone','label' => array('text' => 'Telefone*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('celular',array('class' => 'input-default celular','label' => array('text' => 'Celular: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('email',array('class' => 'input-default email','label' => array('text' => 'Email: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('nasc',array('type' => 'text','class' => 'input-default nasc','label' => array('text' => 'Dt. Nasc: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('cep',array('class' => 'input-default cep','label' => array('text' => 'CEP: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('logradouro',array('class' => 'input-default logradouro','label' => array('text' => 'Logradouro*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('numero',array('class' => 'input-default numero','label' => array('text' => 'Numero*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('complemento',array('class' => 'input-default numero','label' => array('text' => 'Complemento: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('bairro',array('class' => 'input-default bairro','label' => array('text' => 'Bairro* : ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('cidade',array('class' => 'input-default cidade','label' => array('text' => 'Cidade*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('uf',array('class' => 'input-default uf','label' => array('text' => 'UF: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('p_referencia',array('class' => 'input-default p_referencia','label' => array('text' => 'Ponto de Referência: ','class'=> 'label-large')));?>
					</div>
					
					
					<br/>
					<br/>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('ativo',array('label' => array('text' => 'Ativo: ')));?>
					</div>
				<div class="control-group">

				<?php
					/*echo $this->Form->input('foto',array('type' => 'file','class' => 'input-default','label' => array('text' => 'Foto: ')));*/
				?>
				</div>
				<?php echo $this->Form->input('lat',array('class' => 'input-large lat','type' => 'hidden'));?>

						<?php echo $this->Form->input('lng',array('class' => 'input-large lng','type' => 'hidden'));?>

					<?php echo $this->Form->input('latOrigem',array('default' => $empresa['Empresa']['lat'],'class' => 'input-large latOrigem','type' => 'hidden'));?>
					<?php echo $this->Form->input('lngOrigem',array('default' => $empresa['Empresa']['lng'],'class' => 'input-large lngOrigem','type' => 'hidden'));?>
					<?php echo $this->Form->input('distancia',array('class' => 'input-large distancia','type' => 'hidden'));?>
					<?php echo $this->Form->input('duracao',array('class' => 'input-large duracao','type' => 'hidden'));?>
					<?php echo $this->Form->input('filial_id',array('class' => 'input-large filial','type' => 'hidden'));?>


			
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default">Salvar</button>
	      </div>
	    </div>
	  </div>
	</div>

	<div class="row-fluid">
		<br/>

		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Novo Cliente</button>
		</div>

		<div class="area-tabela" id="no-more-tables">
				<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
					<thead  class="cf">
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th class="th-header-normal"><?php echo $this->Paginator->sort('nome', 'Nome');?></th>
							<th class="th-header-normal"><?php echo $this->Paginator->sort('username', 'Nome de usuario');?></th>
							<th class="th-header-normal"><?php echo $this->Paginator->sort('telefone', 'Telefone');?></th>

							<th class="th-header-normal"><?php echo $this->Paginator->sort('email', 'Email');?></th>
							<th><?php echo $this->Paginator->sort('ativo', 'Status');?></th>
							<th class="th-header-normal">Ações</th>

						</tr>
					</thead>

					<tbody>
						<?php foreach ($clientes as $cliente): ?>
							<?php
		                        $disabledline = ($cliente['Cliente']['ativo']== 0 ? 'disabledline': '');
		                    ?>
						<tr class="<?php echo $disabledline; ?>">
							<td data-title="Código"><?php echo h($cliente['Cliente']['id']); ?></td>
							<td data-title="Nome"><?php echo h($cliente['Cliente']['nome']); ?></td>
							<td data-title="Nome"><?php echo h($cliente['Cliente']['username']); ?></td>
							<td data-title="Telefone"><?php echo h($cliente['Cliente']['telefone']); ?></td>
							<td data-title="Email"><?php echo h($cliente['Cliente']['email']); ?></td>
							<td data-title="Status">
								<?php
									if($cliente['Cliente']['ativo']==1){
										echo 'Ativo';
									}else{
										echo 'Desabilitado';
									}
								 ?></td>
							<!--<td>
								<div class="circulodivPequeno">
									<?php
										if(isset($cliente['Cliente']['foto']) && $cliente['Cliente']['foto'] != ''){
									?>
											<img src=<?php echo $cliente['Cliente']['foto'];?> alt=<?php echo $cliente['Cliente']['nome'];?> width="50px" height="50px" title=<?php echo $cliente['Cliente']['nome'];?> width="50px" height="50px"/>
									<?php
										}else{
											echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'50px','height'=>'50px'));

										}
									?>
								</div>
							</td>-->
							<td data-title="Actions">
								<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$cliente['Cliente']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$cliente['Cliente']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$cliente['Cliente']['id'],'class'=>'bt-tabela editModal','data-id'=>$cliente['Cliente']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-desabilitar.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Clientes','action' => 'disable', $cliente['Cliente']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja desativar o registro  %s?', $cliente['Cliente']['nome'])
									);

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Clientes','action' => 'delete', $cliente['Cliente']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja remover o registro  %s?', $cliente['Cliente']['nome'])
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
<style type="text/css">
	.modal-dialog{
		width: 95%;
	}
	.label-large{
		width: 164px !important;
	}
	.p_referencia{

	}
</style>
<script>
$(document).ready(function() {

	$("#clickmodal").click(function(){
		$('#myModal').modal('show');
	});
	var urlInicio      = window.location.host;
	if(urlInicio=="localhost" ){
		urlInicio= "localhost/entregapp_sistema";	
	} 
	 loja = $('#filterMinhaslojas').val();
	 $('#ClienteFilialId').val(loja);

	$('body').on('click','.editModal', function(event){
		event.preventDefault();
		 $(this).attr('src','http://'+urlInicio+'/img/ajax-loader.gif');
		modalid = $(this).data('id');


		//	$('#loaderGif'+idpedido).show();
		//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/clientes/edit/'+modalid+'', function(){
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
		$("#loadDivModal").load('http://'+urlInicio+'/clientes/view/'+modalid+'', function(){
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
