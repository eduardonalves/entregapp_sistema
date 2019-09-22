<style>
	#modalBodyCadastrarMesa{height: 130px; overflow: hidden; padding-top: 73px; }
	.close{color:#fff;}
	th{
		background-color:#E87C00;
		color:#FFFFFF; 
		
	}
table {margin-top: 10px; font-size:80%;}



.table-striped tbody tr:nth-child(odd) td {
   background-color: #E8E8E8;
}

.table-striped tbody tr:nth-child(even) td {
   background-color: #F9F9F9;
}

</style>
<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel"> Cadastrar Forma de Pagamento</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Mesa',array('class' => 'form-inline'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						echo $this->Form->input('identificacao',array('class' => 'input-large','label' => array('text' => 'Identificação')));
						?>
					</div>
						
					
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default">Salvar</button>
	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>
	
	
	<div class="row-fluid">
		<button type="button" class="btn btn-inverse" id="clickmodal">Nova Mesa</button>
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
						<?php foreach ($mesas as $mesa): ?>
						<tr>
							<td><?php echo h($mesa['Mesa']['id']); ?></td>
							<td><?php echo h($mesa['Mesa']['identificacao']); ?></td>
							
							<td>Editar/ Deletar Desativar</td>			
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
	
		</div>
	</div>	
	<div class="row-fluid">		
		<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
		));
		?>	</p>
		<nav>
  			<ul class="pagination">
			<?php
				echo $this->Paginator->prev('< ' . __('Anterior'), array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->numbers(array('separator' => ''));
				echo $this->Paginator->next(__('Próxima') . ' >', array(), null, array('class' => 'next disabled'));
			?>
			</ul>
		</nav>
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
