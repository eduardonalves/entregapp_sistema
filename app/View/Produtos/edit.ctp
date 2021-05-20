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
                    if (isset($produto['Produto']['foto']) && $produto['Produto']['foto'] != '') {
                    ?>
                        <img src=<?php echo $produto['Produto']['foto']; ?> alt=<?php echo $produto['Produto']['nome']; ?> width="100px" height="100px" title=<?php echo $produto['Produto']['nome']; ?> />
                    <?php
                    } else {
                        echo $this->html->image('carrinhoproduto.png', array('alt' => 'Foto', 'width' => '100px', 'height' => '100px'));
                    }
                    ?>
                </div>
                <?php echo $this->Form->create('Produto', array('class' => 'form-inline centralizadoForm', 'type' => 'file'));    ?>
                <div class="form-group  form-group-lg">
                    <?php
                    echo $this->Form->input('id');
                    ?>


                </div>
                <div class="row">
                    <div class="form-group  form-group-lg col-md-4">

                        <?php
                        echo $this->Form->input('nome', array('class' => 'input-default', 'label' => array('text' => 'Nome: ')));
                        ?>
                    </div>
						<?php
							if($this->Session->read('Auth.User.empresa_id')==1){
						?>
                        <div class="form-group  form-group-lg col-md-4">

                            <?php
							
								echo $this->Form->input('setore_id', array('class' => 'input-default', 'label' => array('text' => 'Setor: ')));
							
                            ?>
                        </div>
						<?php } ?>
                    <?php if ($isCatalog == false) : ?>
                        <div class="form-group  form-group-lg col-md-4">
                            <?php
                            echo $this->Form->input('preco_custo', array('class' => 'input-default preco', 'label' => array('text' => 'Preço-Custo: ')));
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group  form-group-lg col-md-4">
                        <?php
                        echo $this->Form->input('preco_venda', array('class' => 'input-default', 'label' => array('text' => 'Preço-Venda: ')));
                        ?>
                    </div>
                    <div class="form-group  form-group-lg col-md-4">
                    <?php
                        echo $this->Form->input('categoria_id', array('type' => 'select', 'div' => false, 'class' => 'input-default ', 'label' => array('text' => 'Categoria: ', 'class' => 'control-label selectcake')));
                        ?>
                    
                    </div>

                    
                        <div class="form-group  form-group-lg col-md-12">
                            <?php
                            echo $this->Form->input('descricao', array('id' => 'ProdutoDescricaoEdit', 'type' => 'textarea', 'rows' => '7', 'cols' => '50', 'class' => '', 'label' => array('text' => 'Descrição: ')));
                            ?>
                        </div>
                </div>
                
                

                <div class="row">
                    <div class="form-group  form-group-lg col-md-2">
                        <?php
                        echo $this->Form->input('ativo', array('class' => '', 'label' => array('text' => 'Ativo: ', 'class' => 'control-label')));
                        ?>
                    </div>
                    <?php
                    if ($isCatalog == true) {
                        $tam = 'col-md-3';
                    } else {
                        $tam = 'col-md-3';
                    }
                    ?>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>">
                        <?php
                        echo $this->Form->input('disponivel', array('class' => '', 'label' => array('text' => 'Disponível: ', 'class' => 'control-label')));
                        ?>
                    </div>
					<?php
						if($this->Session->read('Auth.User.empresa_id')==1){
					?>
                    <div class="form-group  form-group-lg col-md-2" style="width: 230px !important;">
                        <?php
                        echo $this->Form->input('recompensa', array('class' => '', 'label' => array('text' => 'Recompensa: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<?php
						}
					?>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('show_app', array('class' => '', 'label' => array('text' => 'Mostrar no App: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<?php
							if($this->Session->read('Auth.User.empresa_id')==1){
						?>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('show_store', array('class' => '', 'label' => array('text' => 'Mostrar no App Garçom: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<?php
						}
					?>
					 <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('tem_adicional', array('class' => '', 'label' => array('text' => 'Tem adicional: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					 <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('adicional', array('class' => '', 'label' => array('text' => 'É adicional: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<?php
							if($this->Session->read('Auth.User.empresa_id')==1){
						?>
                    <div class="form-group  form-group-lg col-md-2" style="width: 230px !important;">
                        <?php
                        echo $this->Form->input('recompensa_tipo', array('options'=> array(
                                ''=> 'Selecione',
                                '1'=> 'Recompensa - Vitória',
                                '2'=> 'Recompensa - Derrota',
                                '3'=> 'Recompensa - Virória Rara',
                        ), 'label' => array('text' => 'Recompensa: '),'class'=> 'input-default'));
                        ?>
                    </div>
					<?php
						}
					?>
                </div>
				<br />
				<br />
				
				<div class="form-group  form-group-lg">
						<?php
						// output all the checkboxes at once
						        echo $this->Form->input(
							  'ProdutosAdicional.adicional_id',
							  array(
							      'type' => 'select',
							      'multiple' => true,
							      'options' => $adicionais,
							      'selected' => $selecionadas,
								  'Class' => 'multi-select'
							  )
							);
						?>
				</div>
				<label class="form-label select-label">Adicionais</label>
				<br />
				<br />
                <div class="row">
                    <div class="form-group  form-group-lg col-md-3">
                        <?php
                        echo $this->Form->input('foto', array('type' => 'file', 'class' => 'input-default', 'label' => array('text' => 'Foto Destaque:', 'class' => 'labellarge')));
                        ?>
                    </div>

                </div>


                <?php
                echo $this->Form->input('filial_id', array('type' => 'hidden'));
                ?>
            </div>
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

    .note-editable {
        min-height: 178px;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {
        //$('#ProdutoDescricaoEdit').summernote();
        $('body').on('click', '#btn-salvar', function(event) {
            event.preventDefault();
            $('#ProdutoEditForm').submit();
        });
        $('.hora').mask('99:99:99');

        $("#maisFotosEdit").click(function() {
            if (!$('.maisFotosEdit').is(':visible')) {
                $('.maisFotosEdit').show();
                $('#maisFotosEdit').text('Esconder Fotos Adicionais');
            } else {
                $('.maisFotosEdit').hide();
                $('#maisFotosEdit').text('Mostrar Fotos Adicionais');
            }
        });
    });
</script>
<?php
$urlInicio = ($_SERVER['HTTP_HOST'] == 'localhost' ? '/entregapp_sistema' : '');


?>
<link href="<?php echo $urlInicio; ?>/summernote/summernote.css" rel="stylesheet">
<script src="<?php echo $urlInicio; ?>/summernote/summernote.js"></script>