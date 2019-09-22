<style>
	#modalBodyCadastrarFuncao{height: 115px; overflow: hidden; padding-top: 73px;}
	.close{color:#fff;}
</style>
<div id="responsive" class="modal hide fade" tabindex="-1" data-width="760">
	<div class="modal-header" style="background-color:#003D4C;">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3>Cadastrar Funções</h3>
	</div>
  	<div class="modal-body" id="modalBodyCadastrarFuncao">
		<div class="row-fluid">
			<div >
				<?php echo $this->Form->create('Funcao',array('class' => 'form-horizontal'));
					echo $this->Form->input('funcao',array('class' => 'control','label' => array('text' => 'Função')));
				?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn btn-danger">Fechar</button>
		<button type="submit" class="btn btn-primary salvarForm">Salvar</button>
		<?php echo $this->Form->end(); ?>
	 </div>
</div>
<a href="#" id="clickmodal">linkmodal</a>
<script>
$(document).ready(function() {

$("#clickmodal").click(function(){
$('#responsive').modal('show');
});

$(".salvarForm").click(function(){
$('.UserAddForm').modal('show');
});
});


</script>