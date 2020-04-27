<div class="col-sm-12">
	<table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
		<thead>
			<tr role="row">
				
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Total dos Pedidos: activate to sort column ascending">
					Total dos Pedidos
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Total Entregue: activate to sort column ascending">
					Total Entregue
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Total Só Pedidos: activate to sort column ascending">
					Total Só Pedidos
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Total Só Entregas: activate to sort column ascending">
					Total Só Entregas
				</th>
			</tr>
		</thead>
		<tbody>
		<tr role="row" class="odd">
			<td  data-title="Total dos Pedidos">R$ <?php echo number_format($totalPedidos, 2, ',', '.'); ?>&nbsp;</td>
			<td  data-title="Total Entregue ">R$ <?php echo number_format($totalPedidosEntregue, 2, ',', '.'); ?>&nbsp;</td>
			<td  data-title="Total Só Pedidos ">R$ <?php $soEntregas = ($totalPedidosEntregue - $totalEntrega);
																	echo number_format($soEntregas, 2, ',', '.'); ?>&nbsp;</td>
			<td  data-title="Total Só Entregas ">R$ <?php echo number_format($totalEntrega, 2, ',', '.'); ?>&nbsp;</td>
		</tr>
		</tbody>
	</table>
</div>

<div class="col-sm-12">
	<table id="example1" class="table table-bordered table-striped dataTable dtr-inline" role="grid" aria-describedby="example1_info">
		<thead>
			<tr role="row">
				<th class="sorting_asc th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Código: activate to sort column descending">
					<?php echo $this->Paginator->sort('Pedido.id', 'Código'); ?></th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Nome: activate to sort column ascending">
					<?php echo $this->Paginator->sort('Cliente.nome', 'Nome'); ?>
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Data: activate to sort column ascending">
					<?php echo $this->Paginator->sort('data'); ?>
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Hora: activate to sort column ascending">
					<?php echo $this->Paginator->sort('hora_atendimento', 'Hora'); ?>
				</th>
				
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Valor: activate to sort column ascending">
					<?php echo $this->Paginator->sort('valor'); ?>
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Status Pagamento: activate to sort column ascending">
					<?php echo $this->Paginator->sort('status_pagamento','St. Pagamento'); ?>
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Entregador: activate to sort column ascending">
					<?php echo $this->Paginator->sort('Entregador.nome'); ?>
				</th>
				<th class="sorting th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Situação: activate to sort column ascending">
					<?php echo $this->Paginator->sort('status','Situação'); ?>
				</th>
				<th class="sorting actions th_link" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
					<?php echo __('Ações'); ?>
				</th>



			</tr>
		</thead>
		<tbody>
		<?php $cliente_idAux = ''; $countLine=0;?>
		<?php foreach ($pedidos as $pedido) : 
			 $countLine++;
		?>
			<tr role="row" class="<?php echo ($countLine % 2 == 0 ? 'even':'odd'); ?>">

				<td data-title="Código" tabindex="0" class="sorting_1" ><?php echo h($pedido['Pedido']['id']); ?>&nbsp;</td>

				<td data-title="Nome"><?php echo ($pedido['Pedido']['cliente_id'] != 1 ? $pedido['Cliente']['nome'] : $pedido['Pedido']['nomecadcliente']); ?>&nbsp;</td>
				<?php
				$dataAntiga = $pedido['Pedido']['data'];
				$novaData = date("d/m/Y", strtotime($dataAntiga));

				?>
				<?php $cliente_idAux = $pedido['Cliente']['id'];
				?>

				<td data-title="Data"><?php echo  $novaData; ?>&nbsp;</td>
				<td data-title="Hora"><?php echo h($pedido['Pedido']['hora_atendimento']); ?>&nbsp;</td>
				
				<td data-title="Valor"><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?>&nbsp;</td>
				<td data-title="Status Pagamento"><?php echo h($pedido['Pedido']['status_pagamento']); ?>&nbsp;</td>
				<td data-title="Entregador" <?php echo 'id="linhaEntregadorNome' . $pedido['Pedido']['id'] . '"'; ?>><?php echo h($pedido['Entregador']['nome']); ?>&nbsp;</td>
				<td data-title="Status" <?php echo 'id="linhaPdStatus' . $pedido['Pedido']['id'] . '"'; ?>><?php echo h($pedido['Pedido']['status']); ?>&nbsp; <?php if ($pedido['Pedido']['status_novo'] == 1) {
																																								//echo $this->Html->image('novo.jpg', array('id' => 'novoimg', 'class' => ' novoico', 'alt' => 'Novo', 'title' => 'Novo'));
																																							?>
																																							<i class="fas fa-bell orange-st-text"><small>&nbsp;Novo</small></i>
																																							<?php
																																							} ?></td>
				<td data-title="Actions">



					<?php
					//echo $this->html->image('tb-ver.png',array('data-idpedido'=>$pedido['Pedido']['id'],'class'=>'bt-tabela ver viewModal viewpedido','data-id'=>$pedido['Pedido']['id']));
					if ($autorizacao['Autorizacao']['pedidos'] != 'n' && $autorizacao['Autorizacao']['pedidos'] != 'v') {
						//echo $this->html->image('tb-edit.png', array('data-idpedido-id' => $pedido['Pedido']['id'], 'class' => 'bt-tabela editpedido ', 'data-id' => $pedido['Pedido']['id']));
					
					?>
					
					<a title="Editar" href="#" class="skin-midnight-aux-text editpedido" data-idpedido-id="<?php echo $pedido['Pedido']['id'];?>"   data-id="<?php echo $pedido['Pedido']['id'];?>">
						<i class="fas fa-edit" ></i>
					</a>
					<?php
					}
					if ($autorizacao['Autorizacao']['pedidos'] == 'a') {
						/*echo $this->Form->postLink(
								  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
								  array('controller'=>'pedidos','action' => 'delete', $pedido['Pedido']['id']), //le url
								  array('escape' => false), //le escape
								  __('Deseja Excluir o  %s?', $pedido['Atendimento']['codigo'])
							);*/
					}
					?>
					
				</td>
			</tr>
		<?php endforeach; ?>
			
		</tbody>
		<tfoot>
			<tr>
				<th rowspan="1" colspan="1">Código</th>
				<th rowspan="1" colspan="1">Nome</th>
				<th rowspan="1" colspan="1">Data</th>
				<th rowspan="1" colspan="1">Hora</th>
				<th rowspan="1" colspan="1">Valor</th>
				<th rowspan="1" colspan="1">St. Pagamento</th>
				<th rowspan="1" colspan="1">Entregador Nome</th>
				<th rowspan="1" colspan="1">Situação</th>
				<th rowspan="1" colspan="1">Ações</th>
			</tr>
		</tfoot>
	</table>
</div>


