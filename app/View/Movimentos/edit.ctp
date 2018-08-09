
<!-- Modal -->
	<div class="modal fade modal-grande modal-Movimento" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Movimento</h4>
	      </div>
	      <div class="modal-body">
					<h3 style="text-align:center;"><strong>Pagamentos</strong></h3>
					<p style="font-size: 20px; text-align:center;">
						<strong class="tituloVenda" >Total Vendas R$ <?php echo  number_format($vendaValor, 2,',','.');  ?></strong>
					</p>
					<div class="area-tabela" id="no-more-tables">
						<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
								<thead class="cf">
									<tr>
										<th>Pagamento</th>
										<th>Valor</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($meusPagamentos as $pag): ?>
									<tr>
										<td data-title="Pagamento"><?php echo h($pag['nome']); ?></td>
										<td data-title="Valor">R$ <?php echo  number_format($pag['valor'], 2,',','.'); ?></td>
									</tr>
									<?php endforeach; ?>
									<tr>
										<td>
											Total
										</td>
										<td>
											R$ <?php echo number_format($totalPagamento, 2,',','.'); ?>
										</td>
									</tr>
								</tbody>
							</table>

							<div style="clear:both;">

							</div>
							<br>
							<br>

							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php if(!empty($meusAtendentes)): ?>
								<div class="panel panel-default">
							    <div class="panel-heading" role="tab" id="headingOne">
							      <h4 class="panel-title">
							        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								          Total Taxa de Atendimento R$ <?php  echo  number_format($totalTaxa, 2,',','.'); ?>
							        </a>
							      </h4>
							    </div>
							    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
							      <div class="panel-body">
											<div class="area-tabela" id="no-more-tables">
												<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
														<thead class="cf">
															<tr>
																<th>Atendente</th>
																<th>Taxa</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($meusAtendentes as $atend): ?>
															<tr>
																<td data-title="Pagamento"><?php echo h($atend['nome_atendente']); ?></td>
																<td data-title="Valor">R$ <?php echo  number_format($atend['taxa'], 2,',','.'); ?></td>
															</tr>
															<?php endforeach; ?>

														</tbody>
													</table>


												</div>
							      </div>
							    </div>
							  </div>
								<?php endif ?>
								<?php if(!empty($meusEntregadores)): ?>
							  <div class="panel panel-default">
							    <div class="panel-heading" role="tab" id="headingTwo">
							      <h4 class="panel-title">
							        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
							          Total Taxa de Entrega
							        </a>
							      </h4>
							    </div>
							    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
							      <div class="panel-body">
											<div class="area-tabela" id="no-more-tables">
												<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
														<thead class="cf">
															<tr>
																<th>Entregador</th>
																<th>Valor Entrega</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($meusEntregadores as $entr): ?>
															<tr>
																<td data-title="Pagamento"><?php echo h($entr['nome_entregador']); ?></td>
																<td data-title="Valor">R$ <?php echo  number_format($entr['valor_entrega'], 2,',','.'); ?></td>
															</tr>
															<?php endforeach; ?>

														</tbody>
													</table>


												</div>
							      </div>
							    </div>
							  </div>
							<?php endif ?>
							</div>
					</div>
					<div style="clear:both;">

					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
					<?php
					echo $this->Form->postLink(
							$this->Html->image('pago_ok.png', array('class'=>'bt-tabela','alt' => __('Fechar'))), //le image
							array('controller'=>'movimentos','action' => 'fechar', $this->request->data['Movimento']['id']), //le url
							array('escape' => false), //le escape
							__('Deseja fechar este movimento  %s?', $this->request->data['Movimento']['data'])
					);
					 ?>
	      </div>
	    </div>
	  </div>
	</div>
<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#MovimentoEditForm').submit();
	});
	setTimeout(function(){
		$('.cep').mask('99999999');
		$('.telefone').mask('(99) 9999-9999');
		$.mask.definitions['~'] = '([0-9] )?';
		$(".celular").mask("(99) 9999-9999~");
		$(".uf").mask("aa");
		$(".nasc").mask("99/99/9999");

	}, 3000);


});

</script>
<style media="screen">
table{
	text-align: center;
}
</style>
