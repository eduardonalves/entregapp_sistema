<!-- Modal -->
	<div class="modal fade modal-grande" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Loja</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Filial',array('class' => 'form-inline', 'type' => 'file'));
						?>



				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Dados Básicos</a></li>
				    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Configurações da Loja</a></li>
				    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Dados do PagSeguro</a></li>
				    	<?php if($isCatalog==true):?>
								<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Catálogo</a></li>
							<?php endif; ?>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
				    <div role="tabpanel" class="tab-pane active" id="home">
							<div class="panel panel-default">
							<!--<div class="panel-heading">Dados Básicos</div>-->
							<div class="panel-body">
								<?php
								echo $this->Form->input('id');
								?>

								<?php  echo $this->Form->input('nome',array('class' => false,'label' => array('text' => 'Filial:')));?>

								<?php echo $this->Form->input('logradouro',array('class' => false,'label' => array('text' => 'Endereço:'))); ?>

								<?php echo $this->Form->input('numero',array('class' => false,'label' => array('text' => 'Número:'))); ?>

								<?php echo $this->Form->input('complemento',array('class' => false,'label' => array('text' => 'Complemento:'))); ?>
								<?php echo $this->Form->input('bairro',array('class' => false,'label' => array('text' => 'Bairro:'))); ?>
								<?php echo $this->Form->input('cidade',array('class' => false,'label' => array('text' => 'Cidade:'))); ?>
								<?php echo $this->Form->input('estado',array('class' => false,'label' => array('text' => 'UF:'))); ?>

								<?php echo $this->Form->input('telefone1',array('class' => false,'label' => array('text' => 'WhatsApp:'))); ?>
								<?php echo $this->Form->input('telefone2',array('class' => false,'label' => array('text' => 'Telefone:'))); ?>
								<?php echo $this->Form->input('telefone3',array('class' => false,'label' => array('text' => 'Telefone:'))); ?>
								<?php echo $this->Form->input('telefone4',array('class' => false,'label' => array('text' => 'Telefone:'))); ?>
								<?php echo $this->Form->input('email',array('class' => false,'label' => array('text' => 'e-mail:'))); ?>


							</div>
						</div>

						</div>
				    <div role="tabpanel" class="tab-pane" id="profile">
							<div class="panel panel-default">
							<!--<div class="panel-heading">Dados Básicos</div>-->
							<div class="panel-body">
								<?php if($isCatalog==false):?>

								<?php endif;?>
								<?php

								if($isCatalog==false)
								{
									echo $this->Form->input('status_abertura',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abrir Loja','class'=>'labellarge' )));
									
									echo $this->Form->input('totais_pesquisa',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Mostrar Totais Pedido','class'=>'labellarge' )));

									echo $this->Form->input('taxa_padrao',array( 'class' => 'input-large ','type' => 'checkbox', 'label' => array('text' => 'Taxa de Entrega Padrão?','class'=>'labellarge' )));
									echo $this->Form->input('valor_padrao',array( 'class' => 'input-large ','type' => 'text', 'label' => array('text' => 'Valor de Entrega Padrão','class'=>'labellarge' )));
									echo $this->Form->input('locais_especificos',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Entregar apenas  nos lugares cadastrados?','class'=>'labellarge' )));





									echo $this->Form->input('hora_abertura',array('class' => 'input-large  hora', 'type' => 'text', 'label' => array('text' => 'Hora de Abertura','class'=>'labellarge' )));


									echo $this->Form->input('hora_fechamento',array('class' => 'input-large hora ', 'type' => 'text', 'label' => array('text' => 'Hora do Fechamento','class'=>'labellarge' )));


									echo $this->Form->input('abre_segunda',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Segunda','class'=>'labellarge' )));

									echo $this->Form->input('abre_terca',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Terça','class'=>'labellarge' )));


									echo $this->Form->input('abre_quarta',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Quarta','class'=>'labellarge' )));


									


									echo $this->Form->input('abre_quinta',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Quinta','class'=>'labellarge' )));


									echo $this->Form->input('abre_sexta',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Sexta','class'=>'labellarge' )));

									echo $this->Form->input('abre_sabado',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre Sábado','class'=>'labellarge' )));


									echo $this->Form->input('abre_domingo',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Abre domingo','class'=>'labellarge' )));

									


								}
								if($isCatalog==false)
								{
									echo "<br>";
									echo $this->Form->input('tempo_atendimento',array('class' => 'input-large hora','label' => array('text' => 'Tempo de Atendimento', 'class' => 'labellarge ')));
								}

								

								if($isCatalog==false)
								{
									echo "<br>";
									echo $this->Form->input('tempo_verde',array('class' => 'input-large ','label' => array('text' => 'Tempo (em min.) Farol Verde', 'class' => 'labellarge ')));
								}


								if($isCatalog==false)
								{
									echo "<br>";
									echo $this->Form->input('tempo_amarelo',array('class' => 'input-large ','label' => array('text' => 'Tempo (em min.) Farol Amarelo', 'class' => 'labellarge ')));
								}

								if($isCatalog==false)
								{
									echo "<br>";
									echo $this->Form->input('tempo_vermelho',array('class' => 'input-large ','label' => array('text' => 'Tempo (em min.) Farol Vermelho', 'class' => 'labellarge ')));
								}


								?>
								
								
							</div>
							</div>
						</div>
				    <div role="tabpanel" class="tab-pane" id="messages">
							<div role="tabpanel" class="tab-pane active" id="home">
								<div class="panel panel-default">
								<!--<div class="panel-heading">Dados Básicos</div>-->
								<div class="panel-body">
									<?php if($isCatalog==false):?>

									<?php endif;?>
									<?php
									if($isCatalog==false)
									{
										echo $this->Form->input('email_pagseguro',array('class' => 'input-large','label' => array('text' => 'E-mail do PagSeguro:','class'=>'labellarge' )));
										echo $this->Form->input('token_pagseguro',array('class' => 'input-large','label' => array('text' => 'Token','class'=>'labellarge')));
										echo $this->Form->input('app_idpagseguro',array('class' => 'input-large','label' => array('text' => 'ID do Aplicativo','class'=>'labellarge')));
										echo $this->Form->input('app_keypagseguro',array('class' => 'input-large','label' => array('text' => 'Chave do Aplicativo','class'=>'labellarge')));
									}
									?>
								</div>
							</div>
						</div>
					
				  </div>

				</div>






			</div>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	        <button type="submit" class="btn btn-default" id="btn-salvar">Salvar</button>
	        <?php echo $this->Form->end(); ?>
	      </div>
	    </div>
	  </div>
	</div>
		<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click','#btn-salvar', function(event){
		event.preventDefault();
		$('#FilialEditForm').submit();
	});
	$('.hora').mask('99:99:99');
	$( "#maisFotosEdit" ).click(function() {
	  if (!$('.maisFotosEdit').is(':visible'))
		{
			$('.maisFotosEdit').show();
			$('#maisFotosEdit').text('Esconder Fotos');
		}else
		{
			$('.maisFotosEdit').hide();
			$('#maisFotosEdit').text('Mostrar Fotos');
		}
	});
});
	</script>
<style media="screen">
	.labellarge {
		    width: 215px !important;
	}
	input{
		margin-bottom: 10px;
	}
	.caixa-parceiro{
		display: block;
		width: 115px;
		height: 73px;
		margin: 0 auto;
		border-radius: 10px;
		border: 1px solid white;
		overflow: hidden;
		background: rgba(0, 0, 0, 0.6);
		    margin-bottom: 20px;


	}
	.caixa-parceiro img{
		display: block;
		margin: 0 auto;
	}
	.lbl-excluir-foto{
		width: 109px !important;
	}
	.h3-filial{
		    text-align: center;
	}
</style>
