
<div class="modal fade modal-tamanho-produto" id="responsiveEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Editar Produtos</h4>
	      </div>
	      <div class="modal-body">
	      	<?php
	      			$produto = $this->request->data;
      		?>
    		<div class="circulodivGrande">
						<?php
							if(isset($produto['Produto']['foto']) && $produto['Produto']['foto'] != ''){
						?>
								<img src=<?php echo $produto['Produto']['foto'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
						<?php
							}else{
								echo $this->html->image('carrinhoproduto.png', array('alt'=> 'Foto', 'width'=>'100px','height'=>'100px'));

							}
						?>
				</div>
	  		<?php echo $this->Form->create('Produto',array('class' => 'form-inline centralizadoForm', 'type' => 'file'));	?>
	  		<div class="form-group  form-group-lg">
	  			<?php
				echo $this->Form->input('id');
				?>


	     		</div>
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
						echo $this->Form->input('preco_custo',array('class' => 'input-default preco','label' => array('text' => 'Preço-Custo: ')));
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
					echo $this->Form->input('Tamanho.0.id');
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
					echo $this->Form->input('Tamanho.1.id');
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
					echo $this->Form->input('Tamanho.2.id');
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
					echo $this->Form->input('Tamanho.3.id');
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
					echo $this->Form->input('Tamanho.4.id');
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
					echo $this->Form->input('descricao',array('id'=>'ProdutoDescricaoEdit', 'type' => 'textarea','rows' => '7', 'cols' => '50', 'class' => '','label' => array('text' => 'Descrição: ')));
					?>
				</div>
			</div>
			<?php if($isCatalog==false):?>
		<!--	<div class="row">
				<div class="form-group  form-group-lg col-md-12">-->
					<?php
					//echo $this->Form->input('descricao_compre_ganhe',array('type' => 'textarea','rows' => '7', 'cols' => '50', 'class' => '','label' => array('text' => 'Descrição da Promoção Compre e Ganhe: ')));
					?>
			<!--	</div>
			</div>-->
			<?php endif;?>
			<div class="row">
				<div class="form-group  form-group-lg col-md-2">
					<?php
					echo $this->Form->input('ativo',array('class' => '','label' => array('text' => 'Ativo: ', 'class' => 'control-label')));
					?>
				</div>
				<?php
					if($isCatalog==true){
						$tam= 'col-md-3';
					}else{
						$tam= 'col-md-2';
					}
				?>
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
					echo $this->Form->input('foto',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Foto Destaque:' ,'class'=>'labellarge')));
					?>
				</div>

			</div>

			<?php if($isCatalog==true):?>
				<button type="button" id="maisFotosEdit" name="maisFotosEdit" class="btn btn-success" style="width: 200px; margin: 0 auto;display: block;">Mostrar Fotos Adicionais</button>
				<br>
			<?php endif;?>

			<?php if($isCatalog==true):?>
				<div class="maisFotosEdit none">
				<?php
					if(isset($produto['Produto']['foto_ext_1']) && $produto['Produto']['foto_ext_1'] != ''){
				?>
				<div class="circulodivGrande">
								<img src=<?php echo $produto['Produto']['foto_ext_1'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
				</div>
				<?php
					echo $this->Form->input('foto_ext_1_del',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
				?>
				<?php
					}
				?>
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
				<div class="maisFotosEdit none">
				<?php
					if(isset($produto['Produto']['foto_ext_2']) && $produto['Produto']['foto_ext_2'] != ''){
				?>
				<div class="circulodivGrande">

								<img src=<?php echo $produto['Produto']['foto_ext_2'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
				</div>
				<?php
					echo $this->Form->input('foto_ext_2_del',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
				?>
				<?php
					}
				?>
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
				<div class="maisFotosEdit none">
				<?php
					if(isset($produto['Produto']['foto_ext_3']) && $produto['Produto']['foto_ext_3'] != ''){
				?>
				<div class="circulodivGrande">

								<img src=<?php echo $produto['Produto']['foto_ext_3'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
				</div>
				<?php
					echo $this->Form->input('foto_ext_3_del',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
				?>
				<?php
					}
				?>
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
				<div class="maisFotosEdit none">
				<?php
					if(isset($produto['Produto']['foto_ext_4']) && $produto['Produto']['foto_ext_4'] != ''){
				?>
				<div class="circulodivGrande">
								<img src=<?php echo $produto['Produto']['foto_ext_4'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
				</div>
				<?php
					echo $this->Form->input('foto_ext_4_del',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
				?>
				<?php
					}
				?>
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
				<div class="maisFotosEdit none">
				<?php
					if(isset($produto['Produto']['foto_ext_5']) && $produto['Produto']['foto_ext_5'] != ''){
				?>
				<div class="circulodivGrande">
								<img src=<?php echo $produto['Produto']['foto_ext_5'];?> alt=<?php echo $produto['Produto']['nome'];?> width="100px" height="100px"title=<?php echo $produto['Produto']['nome'];?>/>
				</div>
				<?php
					echo $this->Form->input('foto_ext_5_del',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
				?>
				<?php
					}
				?>
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
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default" id="btn-salvar">Salvar</button>
	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>
<style media="screen">
form div {
		margin-bottom: 0px;
		padding: 0px;
		vertical-align: text-top;
}
.note-editable{
	min-height: 178px;
}
</style>
	<script type="text/javascript">
$(document).ready(function() {
	$('#ProdutoDescricaoEdit').summernote();
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#ProdutoEditForm').submit();
	});
	$('.hora').mask('99:99:99');

	$( "#maisFotosEdit" ).click(function() {
	  if (!$('.maisFotosEdit').is(':visible'))
		{
			$('.maisFotosEdit').show();
			$('#maisFotosEdit').text('Esconder Fotos Adicionais');
		}else
		{
			$('.maisFotosEdit').hide();
			$('#maisFotosEdit').text('Mostrar Fotos Adicionais');
		}
	});
});
	</script>
