	<section class="row-fluid">
		<div class="area-tabela"  id="no-more-tables">
			<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
				<thead class="cf">
					<tr>
						<th class="th-red">Total dos Pedidos</th>
						<th class="th-red">Total Entregue </th>
						<th class="th-red">Total Só Pedidos</th>
						<th class="th-red">Total Só Entregas </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td  class="center-text" data-title="Total dos Pedidos" >R$ <?php echo number_format($totalPedidos, 2,',','.');?>&nbsp;</td>
						<td   class="center-text " data-title="Total Entregue " >R$ <?php echo number_format($totalPedidosEntregue, 2,',','.');?>&nbsp;</td>
						<td   class="center-text" data-title="Total Só Pedidos " >R$ <?php $soEntregas = ($totalPedidosEntregue-$totalEntrega); echo number_format($soEntregas, 2,',','.');?>&nbsp;</td>
						<td   class="center-text" data-title="Total Só Entregas " >R$ <?php echo number_format($totalEntrega, 2,',','.');?>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br />
		<br />

	</section>
	<div class="area-tabela"  id="no-more-tables">
		<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
			<thead class="cf">
				<tr>

						<th><?php echo $this->Paginator->sort('Pedido.id','Código'); ?></th>
						<th><?php echo $this->Paginator->sort('Cliente.nome', 'Nome'); ?></th>

						<th><?php echo $this->Paginator->sort('data'); ?></th>
						<th><?php echo $this->Paginator->sort('hora_atendimento', 'Hora'); ?></th>
						
						<th><?php echo $this->Paginator->sort('valor'); ?></th>
						<th><?php echo $this->Paginator->sort('status_pagamento'); ?></th>
						<th><?php echo $this->Paginator->sort('Entregador.nome'); ?></th>
						<th><?php echo $this->Paginator->sort('status'); ?></th>
						<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<?php $cliente_idAux=''; ?>
			<?php foreach ($pedidos as $pedido): ?>
			<tr>

				<td data-title="Código"><?php echo h($pedido['Pedido']['id']); ?>&nbsp;</td>

				<td data-title="Nome"><?php echo ($pedido['Pedido']['cliente_id'] !=1 ? $pedido['Cliente']['nome'] : $pedido['Pedido']['nomecadcliente']); ?>&nbsp;</td>
				<?php
					$dataAntiga = $pedido['Pedido']['data'];
					$novaData = date("d/m/Y", strtotime($dataAntiga));

				?>
				<?php $cliente_idAux=$pedido['Cliente']['id'];
				?>

				<td   data-title="Data" ><?php echo  $novaData; ?>&nbsp;</td>
				<td data-title="Hora" ><?php echo h($pedido['Pedido']['hora_atendimento']); ?>&nbsp;</td>
				
				<td data-title="Valor" ><?php echo 'R$ ' . number_format($pedido['Pedido']['valor'], 2, ',', '.'); ?>&nbsp;</td>
				<td data-title="Status Pagamento"><?php echo h($pedido['Pedido']['status_pagamento']); ?>&nbsp;</td>
				<td data-title="Entregador" <?php echo 'id="linhaEntregadorNome'.$pedido['Pedido']['id'].'"';?>><?php echo h($pedido['Entregador']['nome']); ?>&nbsp;</td>
				<td   data-title="Status" <?php echo 'id="linhaPdStatus'.$pedido['Pedido']['id'].'"';?>><?php echo h($pedido['Pedido']['status']); ?>&nbsp; <?php if($pedido['Pedido']['status_novo']==1){ echo $this->Html->image('novo.jpg', array('id' => 'novoimg', 'class' => ' novoico', 'alt' => 'Novo', 'title' => 'Novo'));}?></td>
				<td  data-title="Actions" >



					<?php
						//echo $this->html->image('tb-ver.png',array('data-idpedido'=>$pedido['Pedido']['id'],'class'=>'bt-tabela ver viewModal viewpedido','data-id'=>$pedido['Pedido']['id']));
						if($autorizacao['Autorizacao']['pedidos'] != 'n' && $autorizacao['Autorizacao']['pedidos'] != 'v'){
							echo $this->html->image('tb-edit.png',array('data-idpedido-id'=>$pedido['Pedido']['id'],'class'=>'bt-tabela editpedido ','data-id'=>$pedido['Pedido']['id']));
						}
						if($autorizacao['Autorizacao']['pedidos'] == 'a'){
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
		</table>
	</div>
		
