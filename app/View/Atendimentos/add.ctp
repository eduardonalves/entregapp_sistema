<style>
	#modalBodyCadastrarAtendimento{height: 130px; overflow: hidden; padding-top: 73px; }
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
			<h3>Cadastrar Atendimento</h3>
		</div>
	  	<div class="modal-body" id="modalBodyCadastrarAtendimento">
			<div class="row-fluid">
				<?php
				 if($codigo !=0){
				 	$url= "http://" .$_SERVER['HTTP_HOST']. "/Pedidos/add?a=".$codigo;
			 
				 	echo $this->QrCode->url($url, array('size' => '150x150'));
				 }else{
				 		$url= "http://" .$_SERVER['HTTP_HOST']. "/Pedidos/add?a=".$codigo;
			 
				 	echo $this->QrCode->url($url, array('size' => '150x150'));
					
				 }
					 
				?>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn">Fechar</button>
			<button type="submit" class="btn salvarForm">Imprimir</button>
			<?php echo $this->Form->end(); ?>
		 </div>
	</div>
	
	<div class="row-fluid">
		
		<?php 
			echo $this->Form->postLink('Novo Atendimento',
	                                  array('action' => 'add', $codigo),
	                                  array('class' => 'btn btn-inverse'));
									  
		 ?>
		 <button type="button" class="btn btn-inverse" id="clickmodal">Imprimir Atendimento</button>
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" >
					<thead>
						<tr>
							<th>Código</th>
							<th>Identificação</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($atendimentos as $atendimento): ?>
						<tr>
							<td><?php echo h($atendimento['Atendimento']['id']); ?></td>
							<td><?php echo h($atendimento['Atendimento']['codigo']); ?></td>
							
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
<?php print_r($codigo);?>