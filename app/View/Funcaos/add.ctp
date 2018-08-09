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

<h2><?php echo __('Cadastro de Funções'); ?></h2>
	<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Funções</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Funcao',array('class' => 'form-inline  centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('funcao',array('type'=>'text','class' => 'input-large ','label' => array('text' => 'Função')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							$opts = array(
								'n'=> 'N/A',
								'v' => 'Visualizar',
								'm' => 'Modificar',
								'g' => 'Gestor',
								'a' => 'Adm',
							);
							echo $this->Form->input('Autorizacao.0.pedidos',array('type'=>'radio', 'options'=> $opts,'default'=> 'n','class' => 'input-large','label' => array('text' => 'Pedidos', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.mensagens',array('type'=>'radio', 'default'=> 'n','options'=> $opts,'class' => 'input-large','label' => array('text' => 'Mensagens', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.mapas',array('type'=>'radio', 'default'=> 'n','options'=> $opts,'class' => 'input-large','label' => array('text' => 'Mapas', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.clientes',array('type'=>'radio','default'=> 'n', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Clientes', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.produtos',array('type'=>'radio', 'default'=> 'n','options'=> $opts,'class' => 'input-large','label' => array('text' => 'Produtos', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
						<?php
							echo $this->Form->input('Autorizacao.0.entregadores',array('type'=>'radio','default'=> 'n', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Entregadores', 'class'=>'label-large')));
						?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.formas_de_pagamento',array('type'=>'radio', 'default'=> 'n','options'=> $opts,'class' => 'input-large','label' => array('text' => 'Formas de Pagamento', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.usuarios',array('type'=>'radio', 'default'=> 'n','options'=> $opts,'class' => 'input-large','label' => array('text' => 'Usuários', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.funcoes',array('type'=>'radio','default'=> 'n', 'options'=> $opts,'class' => 'input-large','label' => array('text' => 'Funções', 'class'=>'label-large')));
					?>
					</div>
					<?php
						$boleaopts = array(
							'n'=> 'N/A',
							'm' => 'Modificar',

						);
					?>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.confirmar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Pedidos', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.preparar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Preparo', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.separar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Separação', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.enviar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Envio', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.entregar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Confirmar Entrega', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.finalizar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Finalizar Entrega', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.cancelar',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large','label' => array('text' => 'Cancelar Pedidos', 'class'=>'label-large')));
					?>
					</div>
					<br />
					<div class="form-group  form-group-lg">
					<?php
							echo $this->Form->input('Autorizacao.0.relatorios',array('type'=>'radio','default'=> 'n', 'options'=> $boleaopts,'class' => 'input-large ','label' => array('text' => 'Acessar Relatórios', 'class'=>'label-large')));
					?>
					</div>
				</div>
				<?php
						echo $this->Form->input('filial_id',array('type' => 'hidden'));
						?>
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
			echo $this->Search->create('Funcao', array('class'=> 'form-inline'));
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
		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Nova</button>
		</div>
		<div class="area-tabela">
				<table class="table-action" >
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('funcao', 'Funcao');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($funcaos as $funcao): ?>
						<tr>
							<td><?php echo h($funcao['Funcao']['id']); ?></td>
							<td><?php echo h($funcao['Funcao']['funcao']); ?></td>

							<td>

								<?php
									echo $this->html->image('tb-ver.png',array('data-id'=>$funcao['Funcao']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$funcao['Funcao']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$funcao['Funcao']['id'],'class'=>'bt-tabela editModal','data-id'=>$funcao['Funcao']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Funcaos','action' => 'delete', $funcao['Funcao']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir a função  %s?', $funcao['Funcao']['funcao'])
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
.modal-dialog {
  width: 882px !important;



}
.modal-body{
	max-height: 600px !important;
	overflow-y:scroll !important;
}
.label-large{
	width: 187px !important;
}
</style>
<script>
$(document).ready(function() {
loja = $('#filterMinhaslojas').val();
	 $('#FuncaoFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
$('body').on('click','.editModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/funcaos/edit/'+modalid+'', function(){
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
		$("#loadDivModal").load('http://'+urlInicio+'/funcaos/view/'+modalid+'', function(){
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