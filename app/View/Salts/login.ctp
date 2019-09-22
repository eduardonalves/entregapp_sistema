<?php

		$this->response->header('Access-Control-Allow-Origin','*');
		//$this->response->header('Access-Control-Allow-Methods','POST');
		//$this->response->header('Access-Control-Allow-Headers','X-Requested-With');
		//$this->response->header('Access-Control-Max-Age','172800');
		//$this->response->type('json');
		if(isset($users)){
			$this->response->type('json');
			echo json_encode($users);
		}
		
	
	
	

?>

<div class="row-fluid offset3">
	<h3 class="offset1">Login</h3>
	<?php echo $this->Session->flash('auth'); ?>
	<?php 
		echo $this->Form->create('User', array('class' => 'form-horizontal'));
		echo $this->Form->input('username',array('class' => 'input-large','label' => array('text' => 'Login')));
		echo $this->Form->input('password',array('class' => 'input-large','label' => array('text' => 'Senha')));
		
	?>
	<div class="control-group">
		<div class="controls">
			<button type="submit" class="btn salvarForm">Entrar</button>
			<?php echo $this->Form->end(); ?>
		</div>
		
	</div>
	
</div>
		


		
	 


