<style>
form div {
    margin-bottom: 0px;
    padding: 0px;
    vertical-align: text-top;
}
.note-editable{
	min-height: 178px;
}
</style>
<h2><?php echo __('Cadastro de Produtos'); ?></h2>
	<?php
		echo $this->Search->create('Produtos', array('class'=> 'form-inline', 'type' => 'file'));
		?>
		<div class="form-group  form-group-lg">
			<?php
			echo $this->Search->input('codigo', array('label' => 'Código:','class'=>'filtroCliente input-default', 'required' =>'false'));
			?>
		</div>
		<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('nome', array('label' => 'Nome:','class'=>'filtroCliente input-default',  'required' =>'false'));
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
	<div class="modal fade modal-tamanho-produto" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Produtos</h4>
	      </div>
	      <div class="modal-body">
	  		<?php echo $this->Form->create('Produto',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));	?>
	  		<div class="row">
		  		<div class="form-group  form-group-lg col-md-4">

					<?php
					echo $this->Form->input('nome',array('class' => 'input-default','label' => array('text' => 'Nome: ')));
					?>
				</div>
        <div class="row">
		  		<div class="form-group  form-group-lg col-md-4">

					<?php
					echo $this->Form->input('setore_id',array('class' => 'input-default','label' => array('text' => 'Setor: ')));
					?>
				</div>
        </div>
				<?php if($isCatalog==false):?>
				<div class="form-group  form-group-lg col-md-4">
					<?php
					echo $this->Form->input('preco_custo',array('class' => 'input-default','label' => array('text' => 'Preço-Custo: ')));
					?>
				</div>
				<?php endif;?>
				<div class="form-group  form-group-lg col-md-4">
					<?php
					echo $this->Form->input('preco_venda',array('class' => 'input-default','label' => array('text' => 'Preço-Venda: ')));
				?>
				</div>
			<?php if($isCatalog==false):?>
			</div>
			<div class="row">
			<?php endif;?>
				<div class="form-group  form-group-lg col-md-4">
					<?php
					echo $this->Form->input('categoria_id',array('type' => 'select','div'=> false,'class' => 'input-default ','label' => array('text' => 'Categoria: ', 'class'=> 'control-label selectcake')));
					?>
				</div>
				<?php if($isCatalog==false):?>
				<div class="form-group  form-group-lg col-md-4">
					<?php
					echo $this->Form->input('tempo_preparo',array('type' => 'text', 'class' => 'input-default hora','label' => array('text' => 'Tempo-prep:')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-4">
					<?php
					echo $this->Form->input('qtde_preparo',array('type' => 'text', 'class' => 'input-default qtdepreparo inteiro','label' => array('text' => 'Qtd-Preparo:')));
					?>
				</div>
				<?php endif;?>
			</div>
			<?php if($isCatalog==false):?>
			<div class="row">
				<div class="form-group  form-group-lg  col-md-2">
					<?php
					echo $this->Form->input('Tamanho.0.nome',array('class' => '','label' => array('text' => 'Tamanho: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.0.preco',array('class' => '','label' => array('text' => 'Preço : ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.0.acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida?', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.0.promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.0.ativo',array('class' => '','label' => array('text' => 'Ativo : ', 'class' => 'control-label')));
					?>
				</div>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg  col-md-2">
					<?php
					echo $this->Form->input('Tamanho.1.nome',array('class' => '','label' => array('text' => 'Tamanho: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.1.preco',array('class' => '','label' => array('text' => 'Preço : ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.1.acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida?', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.1.promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.1.ativo',array('class' => '','label' => array('text' => 'Ativo : ', 'class' => 'control-label')));
					?>
				</div>
			</div>

			<div class="row">
				<div class="form-group  form-group-lg  col-md-2">
					<?php
					echo $this->Form->input('Tamanho.2.nome',array('class' => '','label' => array('text' => 'Tamanho: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.2.preco',array('class' => '','label' => array('text' => 'Preço : ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.2.acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida?', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.2.promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.2.ativo',array('class' => '','label' => array('text' => 'Ativo : ', 'class' => 'control-label')));
					?>
				</div>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg  col-md-2">
					<?php
					echo $this->Form->input('Tamanho.3.nome',array('class' => '','label' => array('text' => 'Tamanho: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.3.preco',array('class' => '','label' => array('text' => 'Preço : ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.3.acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida?', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.3.promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.3.ativo',array('class' => '','label' => array('text' => 'Ativo : ', 'class' => 'control-label')));
					?>
				</div>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg  col-md-2">
					<?php
					echo $this->Form->input('Tamanho.4.nome',array('class' => '','label' => array('text' => 'Tamanho: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.4.preco',array('class' => '','label' => array('text' => 'Preço : ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.4.acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida?', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('Tamanho.4.promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('Tamanho.4.ativo',array('class' => '','label' => array('text' => 'Ativo : ', 'class' => 'control-label')));
					?>
				</div>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg  col-md-3">
					<?php
					echo $this->Form->input('acompanha_bebida',array('class' => '','label' => array('text' => 'Acompanha Bebida? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('parte_bebida',array('class' => '','label' => array('text' => 'Bebida que acompanha um produto? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('promo_compre_ganhe',array('class' => '','label' => array('text' => 'Promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					echo $this->Form->input('parte_compre_ganhe',array('class' => '','label' => array('text' => 'Participação de promoção Compre e Ganhe ? ', 'class' => 'control-label')));
					?>
				</div>
			</div>
			<?php endif;?>
			<div class="row">
				<div class="form-group  form-group-lg col-md-12">
					<?php
					echo $this->Form->input('descricao',array('type' => 'textarea','rows' => '7', 'cols' => '50', 'class' => '','label' => array('text' => 'Descrição: ')));
					?>
				</div>
			</div>
			<?php if($isCatalog==false):?>
			<!--<div class="row">
				<div class="form-group  form-group-lg col-md-12">-->
					<?php
				//	echo $this->Form->input('descricao_compre_ganhe',array('type' => 'textarea','rows' => '7', 'cols' => '50', 'class' => '','label' => array('text' => 'Descrição da Promoção Compre e Ganhe: ')));
					?>
			<!--	</div>
			</div>-->
			<?php endif;?>
			<?php
				if($isCatalog==true){
					$tam= 'col-md-3';
				}else{
					$tam= 'col-md-2';
				}
			?>
			<div class="row">
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('ativo',array('class' => '','label' => array('text' => 'Ativo: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg <?php echo $tam; ?>">
					<?php
					echo $this->Form->input('disponivel',array('class' => '','label' => array('text' => 'Disponível: ', 'class' => 'control-label')));
					?>
				</div>
				<?php if($isCatalog==false):?>
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('composto',array('class' => '','label' => array('text' => 'Composto: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					$options = array(
						''=>'Selecione',
						'1'=> 'Salgada',
						'2' => 'Doce',
						'3' => 'Doce/Salgada'
					);
					echo $this->Form->input('parte_composto',array('type'=> 'select', 'options' => $options,'class' => '','label' => array('text' => 'Participação Composto: ', 'class' => 'control-label')));
					?>
				</div>
				<div class="form-group  form-group-lg col-md-3">
					<?php
					$options = array(
						''=>'Selecione',
						'1'=> 'Salgada',
						'2' => 'Doce',
						'3' => 'Doce/Salgada'
					);
					echo $this->Form->input('tipo_composto',array('type'=> 'select', 'options' => $options,'class' => '','label' => array('text' => 'Tipo Composição: ', 'class' => 'control-label')));
					?>
				</div>
				<?php endif;?>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg">
					<?php
					echo $this->Form->input('foto',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Destaque:','class'=>'labellarge')));
					?>
				</div>
			</div>
			<?php if($isCatalog==true):?>
				<button type="button" id="maisfotos" name="maisfotos" class="btn btn-success" style="width: 145px; margin: 0 auto;display: block;">Adiconar Mais Fotos</button>
				<br>
				<div class="maisFotos none">


			<?php endif;?>
			<?php if($isCatalog==true):?>
				<div class="row">
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto_ext_1',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Adicional:' ,'class'=>'labellarge')));
						?>
					</div>
				</div>
				</div>
			<?php endif;?>
			<?php if($isCatalog==true):?>
				<div class="maisFotos none">
				<div class="row">
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto_ext_2',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Adicional' ,'class'=>'labellarge')));
						?>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if($isCatalog==true):?>
				<div class="maisFotos none">
				<div class="row">
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto_ext_3',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Adicional' ,'class'=>'labellarge')));
						?>
					</div>
				</div>
				</div>
			<?php endif;?>
			<?php if($isCatalog==true):?>
				<div class="maisFotos none">
				<div class="row">
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto_ext_4',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Adicional' ,'class'=>'labellarge')));
						?>
					</div>
				</div>
			</div>
			<?php endif;?>
			<?php if($isCatalog==true):?>
				<div class="maisFotos none">
				<div class="row">
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('foto_ext_5',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Adicional' ,'class'=>'labellarge')));
						?>
					</div>
				</div>
				</div>
			<?php endif;?>
				<?php
				echo $this->Form->input('filial_id',array('type' => 'hidden'));
				?>
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
			<button type="button" class="btn btn-success" id="clickmodal">Novo Prduto</button>
		</div>
		<div class="area-tabela" id="no-more-tables">
			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
				<thead class="cf">
					<tr>
						<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
						<th><?php echo $this->Paginator->sort('nome', 'Nome');?></th>
						<th ><?php echo $this->Paginator->sort('preco_venda', 'Preço');?></th>

						<th>Foto</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($produtos as $produto): ?>
					<tr>
						<td data-title="Código"><?php echo h($produto['Produto']['id']); ?></td>
						<td data-title="Nome" ><?php echo h($produto['Produto']['nome']); ?></td>
						<td data-title="Preço"><?php echo 'R$ '.number_format($produto['Produto']['preco_venda'],2,',','2'); ?></td>

						<td data-title="Foto">
							<div class="circulodivPequeno">
								<?php
									if(isset($produto['Produto']['foto']) && $produto['Produto']['foto'] != ''){
								?>
										<img src=<?php echo $produto['Produto']['foto'];?> alt=<?php echo $produto['Produto']['nome'];?> width="50px" height="50px"/>
								<?php
									}else{
										echo $this->html->image('carrinhoproduto.png', array('alt'=> 'Foto', 'width'=>'50px','height'=>'50px'));

									}
								?>
							</div>
						</td>
						<td data-title="Actions">
							<?php
									//echo $this->html->image('tb-ver.png',array('data-id'=>$produto['Produto']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$produto['Produto']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$produto['Produto']['id'],'class'=>'bt-tabela editModal','data-id'=>$produto['Produto']['id']));

									echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'Produtos','action' => 'delete', $produto['Produto']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir o  %s?', $produto['Produto']['nome'])
									);

							?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>
	<div id="loadModalEdit">

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

<script>
$(document).ready(function() {
//	$('#ProdutoDescricao').summernote();
	$( "#maisfotos" ).click(function() {
	  if (!$('.maisFotos').is(':visible'))
		{
			$('.maisFotos').show();
		}else
		{
			$('.maisFotos').hide();
		}
	});
	loja = $('#filterMinhaslojas').val();
	 $('#ProdutoFilialId').val(loja);
	$("#clickmodal").click(function(){
		$('#responsive').modal('show');
	});
	var urlInicio      = window.location.host;
  urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp': urlInicio);
	$('body').on('click','.editModal', function(event){
		event.preventDefault();
		$(this).attr('src','/img/ajax-loader.gif');
		produtoId = $(this).data('id');

	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadModalEdit").load('http://'+urlInicio+'/Produtos/edit/'+produtoId+'', function(){
			$('.editModal').attr('src','/img/tb-edit.png');
			$('#responsiveEdit').modal('show');

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

		produtoId = $(this).data('id');

	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadModalEdit").load('http://'+urlInicio+'/Produtos/view/'+produtoId+'', function(){
			$('#responsiveEdit').modal('show');
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
