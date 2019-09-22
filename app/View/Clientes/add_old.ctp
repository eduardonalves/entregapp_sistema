<style>
	

</style>
	<h2><?php echo __('Cadastro de Clientes'); ?></h2>
	<?php 
			echo $this->Search->create();
		?>
		
		<div class="form-group  form-group-lg">	
			<?php
			echo $this->Search->input('codigo', array('label' => 'Código:','class'=>'filtroCliente', 'required' =>'false'));
			?>
		</div>
		
		<div class="form-group  form-group-lg">	
			<?php
			echo $this->Search->input('nome', array('label' => 'Nome:','class'=>'filtroCliente',  'required' =>'false'));
			?>
		</div>
		
		<div class="form-group  form-group-lg">	
			<?php
			echo $this->Search->input('telefone', array('label' => 'telefone','class'=>'filtroCliente', 'required' =>'false'));
		?>
		</div>
		<div class="form-group  form-group-lg">	
			<?php
			echo $this->Search->end(__('Filtrar', true));
			?>
		</div>
		
	<!-- Modal -->
	<div class="modal fade modal-grande" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Clientes</h4>
	      </div>
	      <div class="modal-body">
	  		 
				<span class="loadingCep" id="loadingCep"></span>
				<?php echo $this->Form->create('Cliente',array('class' => 'form-inline','enctype'=>'multipart/form-data'));	?>

					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('nome',array('class' => 'input-large nome','label' => array('text' => 'Nome*: ')));?>
					</div>

					<div class="form-group  form-group-lg">	
						<?php echo $this->Form->input('telefone',array('class' => 'input-large telefone','label' => array('text' => 'Telefone*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('celular',array('class' => 'input-large celular','label' => array('text' => 'Celular: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('nasc',array('type' => 'text','class' => 'input-large nasc','label' => array('text' => 'Dt. Nasc: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('cep',array('class' => 'input-large cep','label' => array('text' => 'CEP: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('logradouro',array('class' => 'input-large logradouro','label' => array('text' => 'Logradouro*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('numero',array('class' => 'input-large numero','label' => array('text' => 'Numero*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('complemento',array('class' => 'input-large numero','label' => array('text' => 'Complemento: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('bairro',array('class' => 'input-large bairro','label' => array('text' => 'Bairro* : ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('cidade',array('class' => 'input-large cidade','label' => array('text' => 'Cidade*: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('uf',array('class' => 'input-large uf','label' => array('text' => 'UF: ')));?>
					</div>
					<div class="form-group  form-group-lg">
						<?php echo $this->Form->input('email',array('class' => 'input-large email','label' => array('text' => 'Email: ')));?>
					</div>
					
						
			
				<div class="control-group">
					
				<?php	
					echo $this->Form->input('foto',array('type' => 'file','class' => 'input-large','label' => array('text' => 'Foto: ')));
				?>
				</div>
				<?php echo $this->Form->input('lat',array('class' => 'input-large lat','type' => 'hidden'));?>
					
						<?php echo $this->Form->input('lng',array('class' => 'input-large lng','type' => 'hidden'));?>
					
					<?php echo $this->Form->input('latOrigem',array('default' => $empresa['Empresa']['lat'],'class' => 'input-large latOrigem','type' => 'hidden'));?>
					<?php echo $this->Form->input('lngOrigem',array('default' => $empresa['Empresa']['lng'],'class' => 'input-large lngOrigem','type' => 'hidden'));?>
					<?php echo $this->Form->input('distancia',array('class' => 'input-large distancia','type' => 'hidden'));?>
			
			<div id="mapa"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default">Salvar</button>
	      </div>
	    </div>
	  </div>
	</div>	
	
	<div class="row-fluid">
		
		
		<div class="form-group  form-group-lg">	
			<button type="button" class="btn btn-success" id="clickmodal">Novo Cliente</button>
		</div>
		
		<div class="area-tabela">
				<table class="table-action" >
					<thead>
						<tr>
							<th>Código</th>
							<th class="th-header-normal">Nome</th>
							<th class="th-header-normal">Telefone</th>
							<th class="th-header-pequena" colspan="4">Cliente</th>
							<th class="th-header-normal">Email</th>
							<th class="th-header-normal">Ações</th>
							
						</tr>
					</thead>
			
					<tbody>
						<?php foreach ($clientes as $cliente): ?>
						<tr>
							<td><?php echo h($cliente['Cliente']['id']); ?></td>
							<td><?php echo h($cliente['Cliente']['nome']); ?></td>
							<td><?php echo h($cliente['Cliente']['telefone']); ?></td>
							<td><?php echo h($cliente['Cliente']['email']); ?></td>
							<td>
								<?php 
									echo $this->html->image('tb-edit.png',array('data-modalid' => $cliente['Cliente']['id'],'class'=>'bt-tabela editModal','data-id'=>$cliente['Cliente']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Clientes','action' => 'delete', $cliente['Cliente']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir o  %s?', $cliente['Cliente']['nome'])
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
<script>
$(document).ready(function() {

$("#clickmodal").click(function(){
	$('#myModal').modal('show');
});
var urlInicio      = window.location.host;
if(urlInicio=="localhost" ){
	urlInicio= "localhost/entregapp_sistema";	
} 
$('body').on('click','.editModal', function(event){
		event.preventDefault();

		modalid = $(this).data('modalid');
		alert(modalid);
		
	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/clientes/edit/'+modalid+'', function(){
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

		modalid = $(this).data('modalid');

		
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
