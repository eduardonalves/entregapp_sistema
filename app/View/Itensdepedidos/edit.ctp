<div class="itensdepedidos form">
<?php echo $this->Form->create('Itensdepedido'); ?>
	<fieldset>
		<legend><?php echo __('Edit Itensdepedido'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('produto_id');
		echo $this->Form->input('pedido_id');
		echo $this->Form->input('qtd');
		echo $this->Form->input('valor');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Itensdepedido.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Itensdepedido.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Itensdepedidos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Produtos'), array('controller' => 'produtos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Produto'), array('controller' => 'produtos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Pedidos'), array('controller' => 'pedidos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Pedido'), array('controller' => 'pedidos', 'action' => 'add')); ?> </li>
	</ul>
</div>
