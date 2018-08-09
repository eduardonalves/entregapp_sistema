<div class="itensdepedidos index">
	<h2><?php echo __('Itensdepedidos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('produto_id'); ?></th>
			<th><?php echo $this->Paginator->sort('pedido_id'); ?></th>
			<th><?php echo $this->Paginator->sort('qtd'); ?></th>
			<th><?php echo $this->Paginator->sort('valor'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($itensdepedidos as $itensdepedido): ?>
	<tr>
		<td><?php echo h($itensdepedido['Itensdepedido']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($itensdepedido['Produto']['id'], array('controller' => 'produtos', 'action' => 'view', $itensdepedido['Produto']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($itensdepedido['Pedido']['id'], array('controller' => 'pedidos', 'action' => 'view', $itensdepedido['Pedido']['id'])); ?>
		</td>
		<td><?php echo h($itensdepedido['Itensdepedido']['qtd']); ?>&nbsp;</td>
		<td><?php echo h($itensdepedido['Itensdepedido']['valor']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $itensdepedido['Itensdepedido']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $itensdepedido['Itensdepedido']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $itensdepedido['Itensdepedido']['id']), null, __('Are you sure you want to delete # %s?', $itensdepedido['Itensdepedido']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Itensdepedido'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Produtos'), array('controller' => 'produtos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Produto'), array('controller' => 'produtos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pedidos'), array('controller' => 'pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pedido'), array('controller' => 'pedidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
