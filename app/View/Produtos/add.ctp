<style>
    form div {
        margin-bottom: 0px;
        padding: 0px;
        vertical-align: text-top;
    }

    .note-editable {
        min-height: 178px;
    }
</style>
<h2><?php echo __('Cadastro de Produtos'); ?></h2>
<?php
echo $this->Search->create('Produtos', array('class' => 'form-inline', 'type' => 'file'));
?>
<div class="form-group  form-group-lg">
    <?php
    echo $this->Search->input('codigo', array('label' => 'Código:', 'class' => 'filtroCliente input-default', 'required' => 'false'));
    ?>
</div>
<div class="form-group  form-group-lg">
    <?php
    echo $this->Search->input('nome', array('label' => 'Nome:', 'class' => 'filtroCliente input-default',  'required' => 'false'));
    ?>
</div>
<div class="form-group  form-group-lg">
    <?php
    echo $this->Search->input('categoria', array('label' => 'Categoria:', 'class' => 'input-default',  'required' => 'false'));
    ?>
</div>
<div class="form-group  form-group-lg">
    <?php
    echo $this->Search->input('empresa', array('label' => false, 'class' => 'none', 'required' => 'false'));

    echo $this->Search->input('minhaslojas', array('label' => 'Loja', 'class' => 'filtroPedido input-default ', 'required' => 'false'));
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
                <?php echo $this->Form->create('Produto', array('class' => 'form-inline centralizadoForm', 'type' => 'file'));    ?>
                <div class="row">
                    <div class="form-group  form-group-lg col-md-4">

                        <?php
                        echo $this->Form->input('nome', array('class' => 'input-default', 'label' => array('text' => 'Nome: ')));
                        ?>
                    </div>
                    
                        <div class="form-group  form-group-lg col-md-4">

                            <?php
                            echo $this->Form->input('setore_id', array('class' => 'input-default', 'label' => array('text' => 'Setor: ')));
                            ?>
                        </div>
                    
                    <?php if ($isCatalog == false) : ?>
                        <div class="form-group  form-group-lg col-md-4">
                            <?php
                            echo $this->Form->input('preco_custo', array('class' => 'input-default', 'label' => array('text' => 'Preço-Custo: ')));
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
                        echo $this->Form->input('descricao', array('type' => 'textarea', 'rows' => '7', 'cols' => '50', 'class' => '', 'label' => array('text' => 'Descrição: ')));
                        ?>
                    </div>
                </div>


                
                <?php
                if ($isCatalog == true) {
                    $tam = 'col-md-3';
                } else {
                    $tam = 'col-md-3';
                }
                ?>
                <div class="row">
                    <div class="form-group  form-group-lg col-md-2">
                        <?php
                        echo $this->Form->input('ativo', array('class' => '', 'label' => array('text' => 'Ativo: ', 'class' => 'control-label')));
                        ?>
                    </div>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>">
                        <?php
                        echo $this->Form->input('disponivel', array('class' => '', 'label' => array('text' => 'Disponível: ', 'class' => 'control-label')));
                        ?>
                    </div>
                     <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('recompensa', array('class' => '', 'label' => array('text' => 'recompensa: ', 'class' => 'labellarge')));
                        ?>
                    </div>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('show_app', array('class' => '', 'label' => array('text' => 'Mostrar no App Cliente: ', 'class' => 'labellarge')));
                        ?>
                    </div>
                    <div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('show_store', array('class' => '', 'label' => array('text' => 'Mostrar no App Garçom: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('tem_adicional', array('class' => '', 'label' => array('text' => 'Tem Adicional: ', 'class' => 'labellarge')));
                        ?>
                    </div>
					<div class="form-group  form-group-lg <?php echo $tam; ?>"  style="width: 230px;">
                        <?php
                        echo $this->Form->input('adicional', array('class' => '', 'label' => array('text' => 'É Adicional: ', 'class' => 'labellarge')));
                        ?>
                    </div>
                    <div class="form-group  form-group-lg col-md-2" style="width: 230px !important;">
                        <?php
                        echo $this->Form->input('recompensa_tipo', array('options'=> array(
                                ''=> 'Selecione',
                                '1'=> 'Comum',
                                '2'=> 'Consolação',
                                '3'=> 'Rara',
                        ), 'label' => array('text' => 'Recompensa: '),'class'=> 'input-default'));
                        ?>
                    </div>

                </div>
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
							  'Class' => 'multi-select'

						  )
						);
					?>
				</div>
				<label class="form-label select-label">Adicionais</label>
				
				<br />
				
                <div class="row">
                    <div class="form-group  form-group-lg col-md-4">
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
                <button type="submit" class="btn btn-default">Salvar</button>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>


<div class="row-fluid">
    <br />
    <div class="form-group  form-group-lg">
        <button type="button" class="btn btn-success" id="clickmodal">Novo Produto</button>
    </div>
    <div class="area-tabela" id="no-more-tables">
        <table class="table-action col-md-12 table-bordered table-striped table-condensed cf">
            <thead class="cf">
                <tr>
                    <th><?php echo $this->Paginator->sort('id', 'Código'); ?></th>
                    <th><?php echo $this->Paginator->sort('nome', 'Nome'); ?></th>
                    <th><?php echo $this->Paginator->sort('Categoria.nome', 'Categoria'); ?></th>
                    <th><?php echo $this->Paginator->sort('preco_venda', 'Preço'); ?></th>

                    <th>Foto</th>
                    <th><?php echo $this->Paginator->sort('ativo', 'Status');?></th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $produto) : ?>
                    <?php
                        $disabledline = ($produto['Produto']['ativo']== 0 ? 'disabledline': '');
                    ?>
                    <tr class="<?php echo $disabledline; ?>">
                        <td data-title="Código"><?php echo h($produto['Produto']['id']); ?></td>
                        <td data-title="Nome"><?php echo h($produto['Produto']['nome']); ?></td>
                        <td data-title="Nome"><?php echo h($produto['Categoria']['nome']); ?></td>
                        <td data-title="Preço"><?php echo 'R$ ' . number_format($produto['Produto']['preco_venda'], 2, ',', '2'); ?></td>

                        <td data-title="Foto">
                            <div class="">
                                <?php
                                if (isset($produto['Produto']['foto']) && $produto['Produto']['foto'] != '') {
                                ?>
                                    <img src=<?php echo $produto['Produto']['foto']; ?> alt=<?php echo $produto['Produto']['nome']; ?> width="50px" height="50px" />
                                <?php
                                } else {
                                    echo $this->html->image('carrinhoproduto.png', array('alt' => 'Foto', 'width' => '50px', 'height' => '50px'));
                                }
                                ?>
                            </div>
                        </td>
                        <td data-title="Status">
                            <?php
                                if($produto['Produto']['ativo']==1){
                                    echo 'Ativo';
                                }else{
                                    echo 'Desabilitado';
                                }
                             ?>
                        </td>
                        <td data-title="Actions">
                            <?php
                            //echo $this->html->image('tb-ver.png',array('data-id'=>$produto['Produto']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$produto['Produto']['id']));



                            echo $this->html->image('tb-edit.png', array('data-id' => $produto['Produto']['id'], 'class' => 'bt-tabela editModal', 'data-id' => $produto['Produto']['id']));
                            
                            echo $this->Form->postLink(
                                $this->Html->image('tb-desabilitar.png', array('class' => 'bt-desativa', 'alt' => __('Desabilitar'))), //le image
                                array('controller' => 'Produtos', 'action' => 'disable', $produto['Produto']['id']), //le url
                                array('escape' => false), //le escape
                                __('Deseja ativar/desativar o produto  %s?', $produto['Produto']['nome'])
                            );

                            echo $this->Form->postLink(
                                $this->Html->image('tb-duplicar.png', array('class' => 'bt-duplica', 'alt' => __('Duplicar'))), //le image
                                array('controller' => 'Produtos', 'action' => 'duplicar', $produto['Produto']['id']), //le url
                                array('escape' => false), //le escape
                                __('Deseja duplicar o produto  %s?', $produto['Produto']['nome'])
                            );

                            
                            echo $this->Form->postLink(
                                $this->Html->image('tb-excluir.png', array('class' => 'bt-tabela', 'alt' => __('Excluir'))), //le image
                                array('controller' => 'Produtos', 'action' => 'delete', $produto['Produto']['id']), //le url
                                array('escape' => false), //le escape
                                __('Deseja remover o produto  %s?', $produto['Produto']['nome'])
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
        ?> </p>
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
        $("#maisfotos").click(function() {
            if (!$('.maisFotos').is(':visible')) {
                $('.maisFotos').show();
            } else {
                $('.maisFotos').hide();
            }
        });
        loja = $('#filterMinhaslojas').val();
        $('#ProdutoFilialId').val(loja);
        $("#clickmodal").click(function() {
            $('#responsive').modal('show');
        });
        var urlInicio = window.location.host;
        urlInicio = (urlInicio == 'localhost' ? urlInicio + '/entregapp_sistema' : urlInicio);
        $('body').on('click', '.editModal', function(event) {
            event.preventDefault();
            $(this).attr('src', 'http://' + urlInicio + '/img/ajax-loader.gif');
            produtoId = $(this).data('id');

            //	$('#loaderGif'+idpedido).show();
            //	$('#divActions'+idpedido).hide();

            $("#loadModalEdit").load('http://' + urlInicio + '/Produtos/edit/' + produtoId, function() {
                $('.editModal').attr('src', 'http://' + urlInicio + '/img/tb-edit.png');


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
		
        $('body').on('click', '.viewModal', function(event) {
            event.preventDefault();

            produtoId = $(this).data('id');

            //	$('#loaderGif'+idpedido).show();
            //	$('#divActions'+idpedido).hide();
            $("#loadModalEdit").load('http://' + urlInicio + '/Produtos/view/' + produtoId + '', function() {
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