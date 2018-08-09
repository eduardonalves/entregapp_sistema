<div class="categorias view">
<h2><?php echo __('Categoria'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nome'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['nome']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Produto'); ?></dt>
		<dd>
			<?php echo $this->Html->link($categoria['Produto']['id'], array('controller' => 'produtos', 'action' => 'view', $categoria['Produto']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Categorianame'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['nome']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ativo'); ?></dt>
		<dd>
			<?php echo h($categoria['Categoria']['ativo']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Categoria'), array('action' => 'edit', $categoria['Categoria']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Categoria'), array('action' => 'delete', $categoria['Categoria']['id']), null, __('Are you sure you want to delete # %s?', $categoria['Categoria']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Categorias'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Categoria'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Produtos'), array('controller' => 'produtos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Produto'), array('controller' => 'produtos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pedidos'), array('controller' => 'pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pedido'), array('controller' => 'pedidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Pedidos'); ?></h3>
	<?php if (!empty($categoria['Pedido'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Pagamento Id'); ?></th>
		<th><?php echo __('Categoria Id'); ?></th>
		<th><?php echo __('Codigo'); ?></th>
		<th><?php echo __('Data'); ?></th>
		<th><?php echo __('Valor'); ?></th>
		<th><?php echo __('Nota'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($categoria['Pedido'] as $pedido): ?>
		<tr>
			<td><?php echo $pedido['id']; ?></td>
			<td><?php echo $pedido['pagamento_id']; ?></td>
			<td><?php echo $pedido['categoria_id']; ?></td>
			<td><?php echo $pedido['codigo']; ?></td>
			<td><?php echo $pedido['data']; ?></td>
			<td><?php echo $pedido['valor']; ?></td>
			<td><?php echo $pedido['nota']; ?></td>
			<td><?php echo $pedido['status']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'pedidos', 'action' => 'view', $pedido['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'pedidos', 'action' => 'edit', $pedido['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'pedidos', 'action' => 'delete', $pedido['id']), null, __('Are you sure you want to delete # %s?', $pedido['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Pedido'), array('controller' => 'pedidos', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
