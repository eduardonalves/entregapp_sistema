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

								<?php echo $this->Form->input('telefone1',array('class' => false,'label' => array('text' => 'Telefone:'))); ?>
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
									echo $this->Form->input('status_abertura',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Situação Abertura','class'=>'labellarge' )));
									echo $this->Form->input('taxa_padrao',array( 'class' => 'input-large ','type' => 'checkbox', 'label' => array('text' => 'Taxa de Entrega Padrão?','class'=>'labellarge' )));
									echo $this->Form->input('valor_padrao',array( 'class' => 'input-large ','type' => 'text', 'label' => array('text' => 'Valor de Entrega Padrão','class'=>'labellarge' )));
									echo $this->Form->input('locais_especificos',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Entregar apenas  nos lugares cadastrados?','class'=>'labellarge' )));
								}
								if($isCatalog==false)
								{
										echo $this->Form->input('tempo_atendimento',array('class' => 'input-large hora','label' => array('text' => 'Tempo de Atendimento', 'class' => 'labellarge hora')));
								}
								?>
								<?php if($isCatalog==true):?>
								<h4>Modo catalogo</h4>
								<?php endif;?>
								<?php
								if($isCatalog==true)
								{
									echo $this->Form->input('modo_catalogo_precos',array('class' => 'input-large ', 'type' => 'checkbox', 'label' => array('text' => 'Mostrar preços?','class'=>'labellarge' )));
								}
								?>
								<?php if($isCatalog==false):?>
								<h4>Dias da Promoção da Bebida</h4>
								<?php endif;?>
								<?php
								if($isCatalog==false)
								{
									echo $this->Form->input('Diasdepromocao.0.id');
									echo $this->Form->input('Diasdepromocao.0.filial_id',array( 'type' => 'hidden'));
									echo $this->Form->input('Diasdepromocao.0.promocao_id',array( 'type' => 'hidden','value'=>1));
									echo $this->Form->input('Diasdepromocao.0.segunda',array('class' => '','label' => array('text' => 'Segunda')));
									echo $this->Form->input('Diasdepromocao.0.terca',array('class' => '','label' => array('text' => 'Terça')));
									echo $this->Form->input('Diasdepromocao.0.quarta',array('class' => '','label' => array('text' => 'Quarta')));
									echo $this->Form->input('Diasdepromocao.0.quinta',array('class' => '','label' => array('text' => 'Quinta')));
									echo $this->Form->input('Diasdepromocao.0.sexta',array('class' => '','label' => array('text' => 'Sexta')));
									echo $this->Form->input('Diasdepromocao.0.sabado',array('class' => '','label' => array('text' => 'Sábado')));
									echo $this->Form->input('Diasdepromocao.0.domingo',array('class' => '','label' => array('text' => 'Domingo')));
								}
								?>
								<?php if($isCatalog==false):?>
								<h4>Dias da Promoção Pague e ganhe</h4>
								<?php endif;?>
								<?php
								if($isCatalog==false)
								{
									echo $this->Form->input('Diasdepromocao.1.id');
									echo $this->Form->input('Diasdepromocao.1.filial_id',array( 'type' => 'hidden'));
									echo $this->Form->input('Diasdepromocao.1.promocao_id',array( 'type' => 'hidden','value'=>2));
									echo $this->Form->input('Diasdepromocao.1.segunda',array('class' => '','label' => array('text' => 'Segunda')));
									echo $this->Form->input('Diasdepromocao.1.terca',array('class' => '','label' => array('text' => 'Terça')));
									echo $this->Form->input('Diasdepromocao.1.quarta',array('class' => '','label' => array('text' => 'Quarta')));
									echo $this->Form->input('Diasdepromocao.1.quinta',array('class' => '','label' => array('text' => 'Quinta')));
									echo $this->Form->input('Diasdepromocao.1.sexta',array('class' => '','label' => array('text' => 'Sexta')));
									echo $this->Form->input('Diasdepromocao.1.sabado',array('class' => '','label' => array('text' => 'Sábado')));
									echo $this->Form->input('Diasdepromocao.1.domingo',array('class' => '','label' => array('text' => 'Domingo')));
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
						<?php if($isCatalog==true):?>
				    <div role="tabpanel" class="tab-pane" id="settings">
							<div class="panel panel-default">
							<!--<div class="panel-heading">Dados Básicos</div>-->
							<div class="panel-body">
								<?php if($isCatalog==true):?>
								<h4>Formas de Pagamento Aceitas</h4>
								<?php endif;?>
								<?php
								if($isCatalog==true)
								{
									echo $this->Form->input('aceita_visa',array('class' => 'input-large','label' => array('text' => 'Visa :','class'=>'labellarge' )));
									echo $this->Form->input('aceita_master',array('class' => 'input-large','label' => array('text' => 'Master Card','class'=>'labellarge')));
									echo $this->Form->input('aceita_diners',array('class' => 'input-large','label' => array('text' => 'Diners Club','class'=>'labellarge')));
									echo $this->Form->input('aceita_amex',array('class' => 'input-large','label' => array('text' => 'American Express','class'=>'labellarge')));
									echo $this->Form->input('aceita_pagseguro',array('class' => 'input-large','label' => array('text' => 'Pag Seguro:','class'=>'labellarge' )));
									echo $this->Form->input('transf_bb',array('class' => 'input-large','label' => array('text' => 'Banco do Brasil','class'=>'labellarge')));
									echo $this->Form->input('transf_itau',array('class' => 'input-large','label' => array('text' => 'Itaú','class'=>'labellarge')));
									echo $this->Form->input('transf_bradesco',array('class' => 'input-large','label' => array('text' => 'Bradesco','class'=>'labellarge')));
									echo $this->Form->input('transf_caixa',array('class' => 'input-large','label' => array('text' => 'Caixa Econômica Federal:','class'=>'labellarge' )));
									echo $this->Form->input('transf_santander',array('class' => 'input-large','label' => array('text' => 'Santander','class'=>'labellarge')));

									echo $this->Form->input('elo',array('class' => 'input-large','label' => array('text' => 'Elo','class'=>'labellarge')));
									echo $this->Form->input('hipercard',array('class' => 'input-large','label' => array('text' => 'Hipercard','class'=>'labellarge')));
									echo $this->Form->input('aura',array('class' => 'input-large','label' => array('text' => 'Aura','class'=>'labellarge')));
								}
								?>

								<?php if($isCatalog==true):?>
								<h4>Logo dos parceiros</h4>
								<?php endif;?>
								<?php
								if($isCatalog==true)
								{
									echo $this->Form->input('mostrar_parceiros',array('type'=>'checkbox','class' => 'input-large','label' => array('text' => 'Mostrar Parceiros :','class'=>'labellarge' )));
								}
								?>
								<?php if($isCatalog==true):?>
									<button type="button" id="maisFotosEdit" name="maisFotosEdit" class="btn btn-success" style="width: 200px; margin: 0 auto;display: block;">Mostrar Fotos</button>
									<br>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										//@TODO LIMITAR NO BACK-END A RELOÇÃO MAXIMA DOS UPLOADS DAS IMAGENS  DAS LOGOS DOS PARCEIROS
										//@TODO SETAR A FILIAL PRINCIPAL OU PADRÃO PARA FAZER CONFIGURAÇÃO DO CATALOGO E TRAVAR O UPLOAD DE IMAGENS DE PARCEIROS E CONFIGURAÇÕES NAS OUTRAS FILIAIS QUANDO ESTIVER NO MODO CATALOGO
										if(isset($filial['Filial']['parceiro_1']) && $filial['Filial']['parceiro_1'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_1'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_1',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_1',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_2']) && $filial['Filial']['parceiro_2'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_2'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_2',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_2',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_3']) && $filial['Filial']['parceiro_3'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_3'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_3',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_3',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_4']) && $filial['Filial']['parceiro_4'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_4'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_4',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_4',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_5']) && $filial['Filial']['parceiro_5'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_5'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_5',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_5',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_6']) && $filial['Filial']['parceiro_6'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_6'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_6',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_6',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_7']) && $filial['Filial']['parceiro_7'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_7'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_7',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_7',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_8']) && $filial['Filial']['parceiro_8'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_8'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_8',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_8',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_9']) && $filial['Filial']['parceiro_9'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_9'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_9',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_9',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
								<?php if($isCatalog==true):?>
									<div class="maisFotosEdit none">
										<h5 class="h3-filial">Resolução maxima 100 x 55 pixels</h5>
									</div>
									<div class="maisFotosEdit none">
									<?php
										if(isset($filial['Filial']['parceiro_10']) && $filial['Filial']['parceiro_10'] != ''){
									?>
									<div class="caixa-parceiro">
													<img src=<?php echo $filial['Filial']['parceiro_10'];?> alt=<?php echo $filial['Filial']['nome'];?> title=<?php echo $filial['Filial']['nome'];?>/>
									</div>
									<?php
										echo $this->Form->input('parceiro_10',array('type'=>'checkbox','class' => '','label' => array('text' => 'Excluir Foto: ', 'class' => 'labellarge lbl-excluir-foto')));
									?>
									<?php
										}
									?>
									<div class="row">
										<div class="form-group  form-group-lg">
											<?php
											echo $this->Form->input('parceiro_10',array('type' => 'file', 'class' => 'input-default','label' => array('text' => 'Logo Parceiro:' ,'class'=>'labellarge')));
											?>
										</div>
									</div>
									</div>
								<?php endif;?>
							</div>
							</div>

						</div>
					<?php endif; ?>
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
