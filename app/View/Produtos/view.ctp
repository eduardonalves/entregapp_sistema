<div class="modal fade modal-tamanho-produto modal-viewProduto" id="responsiveEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Visualizar Produtos</h4>
	      </div>
	      <div class="modal-body">
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
				echo $this->Form->input('Código',array('default'=> $produto['Produto']['id'],'class' => 'input-default','readonly' => 'readonly','label' => array('text' => 'Código: ')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('nome',array('default'=> $produto['Produto']['nome'],'class' => 'input-default','readonly' => 'readonly','label' => array('text' => 'Nome: ')));
				?>
			</div>
			<div class="row">
				<div class="form-group  form-group-lg col-md-4">

				<?php
				echo $this->Form->input('setore_id',array('class' => 'input-default','label' => array('text' => 'Setor: ')));
				?>
			</div>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('preco_custo',array('default'=> $produto['Produto']['preco_custo'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Preço-Custo: ')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('preco_venda',array('default'=> $produto['Produto']['preco_venda'],'readonly' => 'readonly','class' => 'input-default','label' => array('text' => 'Preço-Venda: ')));
			?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('categoria_id',array('default'=> $categoria['Categoria']['nome'],'readonly' => 'readonly','type' => 'text','class' => 'input-default','label' => array('text' => 'Categoria: ')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('tempo_preparo',array('type' => 'text','readonly' => 'readonly', 'class' => 'input-default hora','label' => array('text' => 'Tempo-Prep:')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('qtde_preparo',array('type' => 'text', 'readonly' => 'readonly','class' => 'input-default qtdepreparo','label' => array('text' => 'Qtd-Preparo:')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('ativo',array('default'=> $produto['Produto']['ativo'],'readonly' => 'readonly', 'class' => '','label' => array('text' => 'Ativo: ', 'class' => 'control-label')));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('estoque',array('default'=> $produto['Produto']['estoque'],'readonly' => 'readonly','class' => '','label' => array('text' => 'estoque: ', 'class' => 'control-label' )));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('disponivel',array('default'=> $produto['Produto']['disponivel'],'readonly' => 'readonly','class' => '','label' => array('text' => 'disponivel: ', 'class' => 'control-label' )));
				?>
			</div>
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Form->input('descricao',array('default'=> $produto['Produto']['descricao'],'readonly' => 'readonly','type' => 'textarea', 'class' => 'input-default','label' => array('text' => 'Descrição: ')));
				?>
			</div>


	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>
