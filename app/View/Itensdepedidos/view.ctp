<div class="itensdepedidos view">
<h2><?php echo __('Itensdepedido'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($itensdepedido['Itensdepedido']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Produto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($itensdepedido['Produto']['id'], array('controller' => 'produtos', 'action' => 'view', $itensdepedido['Produto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Pedido'); ?></dt>
		<dd>
			<?php echo $this->Html->link($itensdepedido['Pedido']['id'], array('controller' => 'pedidos', 'action' => 'view', $itensdepedido['Pedido']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Qtd'); ?></dt>
		<dd>
			<?php echo h($itensdepedido['Itensdepedido']['qtd']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($itensdepedido['Itensdepedido']['valor']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Itensdepedido'), array('action' => 'edit', $itensdepedido['Itensdepedido']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Itensdepedido'), array('action' => 'delete', $itensdepedido['Itensdepedido']['id']), null, __('Are you sure you want to delete # %s?', $itensdepedido['Itensdepedido']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Itensdepedidos'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Itensdepedido'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Produtos'), array('controller' => 'produtos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Produto'), array('controller' => 'produtos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pedidos'), array('controller' => 'pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pedido'), array('controller' => 'pedidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
