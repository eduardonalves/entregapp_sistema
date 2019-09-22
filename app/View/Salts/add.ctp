<style>
	#modalBodyCadastrarUsuario{height: 285px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}
	th{
		background-color:#E87C00;
		color:#FFFFFF; 
		
	}
table {margin-top: 10px; font-size:80%;}

.modal {
   position: absolute;
}

.table-striped tbody tr:nth-child(odd) td {
   background-color: #E8E8E8;
}

.table-striped tbody tr:nth-child(even) td {
   background-color: #F9F9F9;
}

</style>
	<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3>Cadastrar Usuários</h3>
		</div>
	  	<div class="modal-body" id="modalBodyCadastrarUsuario">
			<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('User',array('class' => 'form-horizontal'));
						
						echo $this->Form->input('username',array('class' => 'input-large','label' => array('text' => 'Nome de Usuário')));
						echo $this->Form->input('password',array('class' => 'input-large','label' => array('text' => 'Senha')));
						echo $this->Form->input('funcao_id',array('class' => 'input-large','label' => array('text' => 'Função')));
						echo $this->Form->input('ativo',array('class' => 'input-large','label' => array('text' => 'Usuário Ativo')));
						
					?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn">Fechar</button>
			<button type="submit" class="btn salvarForm">Salvar</button>
			<?php echo $this->Form->end(); ?>
		 </div>
	</div>
	
	<div class="row-fluid">
		<button type="button" class="btn btn-inverse" id="clickmodal">Novo Usuário</button>
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" >
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome de Usuário</th>
							<th>Função</th>
							<th>Ativo</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
						<tr>
							<td><?php echo h($user['User']['id']); ?></td>
							<td><?php echo h($user['User']['username']); ?></td>
							<td><?php echo h($user['Funcao']['funcao']); ?></td>
							<td><?php echo h($user['User']['ativo']); ?></td>
							<td>Editar/ Deletar Desativar</td>			
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
	
		</div>
	</div>	
<script>
$(document).ready(function() {

$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
/*$("#responsive").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});*/
});


</script>