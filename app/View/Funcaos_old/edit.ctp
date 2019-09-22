<div class="funcaos form">
<?php echo $this->Form->create('Funcao'); ?>
	<fieldset>
		<legend><?php echo __('Edit Funcao'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('funcao');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Funcao.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Funcao.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Funcaos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
