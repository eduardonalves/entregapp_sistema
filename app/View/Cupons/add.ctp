<style>
	#modalBodyCupon{height: 130px; overflow: hidden; padding-top: 73px; }
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
.nonecli{
	display: none;
	margin-top: 15px;
  margin-bottom: 9px;
}
.custom-combobox{
	display: none;
}
.noneniver{
	display: none;
	margin-top: 15px;
	margin-bottom: 9px;
}
.acaomassa{
	margin-left: 1px !important;
}
td .control-group{
	width: 14px;
display: block;
height: 7px;
}
input#datavalidade,
input#filterDatavalidade-between{
	width: 40%;
	height: 32px;
}
form div {
    margin-bottom: 0px;
    padding: 0px;
    vertical-align: text-top;
}
.note-editable{
	min-height: 178px;
}
</style>
<h2><?php echo __('Criar Cupons de Desconto'); ?></h2>
	<!-- Modal -->
	<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">Criar Cupons de Desconto</h4>
	      </div>
	      <div class="modal-body">
	  		<div class="row-fluid">
				<div >
					<?php echo $this->Form->create('Cupon',array('class' => 'form-inline centralizadoForm'));
						?>
					<div class="form-group  form-group-lg">
						<?php
						//echo $this->Form->input('numero',array('class' => 'input-large','label' => array('text' => 'Cupon', 'class' => 'labellarge')));
						echo $this->Form->input('percentual',array('class' => 'input-large','label' => array('text' => 'Percentual', 'class' => 'labellarge')));
						echo $this->Form->input('descricao',array('type'=>'textarea','rows' => '7', 'cols' => '50','class' => 'input-large','label' => array('text' => 'Descricao', 'class' => 'labellarge')));
						echo $this->Form->input('validade',array('type'=>'date','dateFormat' => 'DMY','class' => 'input-large','label' => array('text' => 'Data de Validade', 'class' => 'labellarge')));

					//	echo $this->Form->input('status',array('type'=>'text','label' => array('text' => 'Situação')));
					?>
					<h4>Tipo de envio para clientes</h4>
					<?php
						echo $this->Form->input('tipo_envio', array(
							'type' => 'radio',
							'options' => array( 'Todos','Único', 'Aniversário'),
							'class'=> 'tipo_envio'
						));
						echo "<br/>";
						echo $this->Form->input('cliente_id',array('class' => 'input-large nonecli','label' => array('text' => 'Cliente', 'class' => 'labellarge nonecli cliente_label')));

						echo $this->Form->input('filial_id',array('type' => 'hidden'));
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
		<div class="form-group  form-group-lg">
			<button type="button" class="btn btn-success" id="clickmodal">Novo Cupom</button>
		</div>
		<?php
			echo $this->Search->create('Cupon', array('class'=> 'form-inline'));
		?>


		<div class="form-group  form-group-lg">
			<?php
		echo $this->Search->input('empresa', array('label' => false,'class'=>'none', 'required' =>'false'));
		?>
	</div>
	<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
	</div>
	<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('numero', array('label' => 'Numero','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
	</div>
	<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('cliente', array('type'=>'text','label' => 'Cliente','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
	</div>
	<div class="form-group  form-group-lg">
		<label>Validade de até:</label>
		<br>
		<?php
			echo $this->Search->input('datavalidade', array('id'=>'datavalidade','label' => false,'class'=>'filtroPedido data'));
			echo "<span class='separador-filter'>a</span>";
		?>
	</div>

	<div class="form-group  form-group-lg">
		<?php
		echo $this->Search->input('status', array('label' => 'Situação','class'=>'filtroPedido input-default ', 'required' =>'false'));
		?>
	</div>


		<div class="form-group  form-group-lg">
			<?php
			echo $this->Search->end(__('Filtrar', true));
			?>
		</div>
		<br>

		<?php
			echo $this->Form->create('Cupon',array('class' => 'massa form-inline', 'action'=> 'massa'))
		?>
		<div class="area-tabela"  id="no-more-tables">
				<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
					<thead class="cf">
						<tr>

							<th><?php echo $this->Paginator->sort('id', 'Código');?></th>
							<th><?php echo $this->Paginator->sort('numero', 'Numero');?></th>
							<th><?php echo $this->Paginator->sort('percentual', 'Percentual');?></th>
							<th><?php echo $this->Paginator->sort('validade', 'Validade');?></th>
							<th><?php echo $this->Paginator->sort('Cliente.nome', 'Cliente');?></th>
							<th><?php echo $this->Paginator->sort('cliente_id', 'Situação');?></th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=0;
							foreach ($cupons as $cupon):
						?>
						<?php
							$novaDataAux = explode('-', $cupon['Cupon']['validade']);
							$dataValidade='';
							if(isset($novaDataAux[1])){
								$dataValidade = $novaDataAux[2].'/'.$novaDataAux[1].'/'.$novaDataAux[0];
							}
						?>
						<tr>
							<td data-title="Código"><?php echo h($cupon['Cupon']['id']); ?></td>
							<td data-title="Nome"><?php echo h($cupon['Cupon']['numero']); ?></td>
							<td data-title="Nome"><?php echo h($cupon['Cupon']['percentual']).'%'; ?></td>
							<td data-title="Nome"><?php echo $dataValidade; ?></td>
							<td data-title="Nome"><?php echo h($cupon['Cliente']['nome']); ?></td>
							<td data-title="Nome"><?php echo h($cupon['Cupon']['status']); ?></td>
							<td data-title="Actions">

								<?php
									echo $this->Form->input('ativo.'.$i.'',array('class'=>'acaomassa','label' => false, 'value'=> $cupon['Cupon']['id'], 'type'=> 'checkbox'));
									//echo $this->html->image('tb-ver.png',array('data-id'=>$cupon['Cupon']['id'],'class'=>'bt-tabela ver viewModal','data-id'=>$cupon['Cupon']['id']));

									echo $this->html->image('tb-edit.png',array('data-id'=>$cupon['Cupon']['id'],'class'=>'bt-tabela editModal','data-id'=>$cupon['Cupon']['id']));

									/*echo $this->Form->postLink(
										  $this->Html->image('tb-excluir.png', array('class'=>'bt-tabela','alt' => __('Excluir'))), //le image
										  array('controller'=>'cupons','action' => 'delete', $cupon['Cupon']['id']), //le url
										  array('escape' => false), //le escape
										  __('Deseja Excluir a cupon  %s?', $cupon['Cupon']['numero'])
									);*/

								?>
							</td>
						</tr>
						<?php
						$i++;
						endforeach;
						?>
					</tbody>
				</table>

		</div>
		<div style="clear:both;"></div>
		<br>
			<?php
				echo $this->Form->input('em_massa', array(
					'type' => 'radio',
					'options' => array( 'Desativar','Excluir'),
					'class'=> 'tipo_envio',
					'label' => array('text'=> 'Ação em Massa')
				));
			?>
			<div style="clear:both;"></div>
			<?php
				echo $this->Form->input('todosacao',array('class'=>'todosacao','label' => array('text'=> 'Selecionar Todos'), 'value'=> 1, 'type'=> 'checkbox'));
			?>


			<button type="submit" class="btn btn-danger">Enviar</button>
		  <?php echo $this->Form->end(); ?>
	</div>
	<div class="row-fluid">
		<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Página {:page} de {:pages}, Visualisando {:current} de {:count} resultados.')
		));
		echo "<br>";
		echo $this->Paginator->counter(array(
		'format' => __('Inicio no registro {:start}, fim no {:end}.')
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

	<div id="loadDivModal"></div>
<script>
$(document).ready(function() {
	$('#CuponDescricao').summernote();
	$('.todosacao').change(function() {
		if ($('.todosacao').is(':checked'))
		{

			$(".acaomassa").each(function(){
				 $(this).prop('checked',true);
			});
		}else{
			$(".acaomassa").each(function(){
				 $(this).prop('checked',false);
			});
		}
	});

	/*$('#CuponMassaForm').submit(function(event){
		event.preventDefault();
		flagEnvio = false;
		if ($('#CuponEmMassa0').is(':checked'))
		{
			flagEnvio=true;
		}
		if ($('#CuponEmMassa1').is(':checked'))
		{
			flagEnvio=true;
		}
		if(flagEnvio==true){
			$('#CuponMassaForm').submit();
		}else{
			alert('Selecione uma opção de ação em massa mara enviar a operação!');
		}

	});*/
loja = $('#filterMinhaslojas').val();
	 $('#CuponFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
if(urlInicio=="localhost" ){
	urlInicio= "localhost/entregapp_sistema";	
} 
$('body').on('click','.editModal', function(event){
		event.preventDefault();
		modalid = $(this).data('id');
		$(this).attr('src','http://'+urlInicio+'/img/ajax-loader.gif');
		$("#loadDivModal").load('http://'+urlInicio+'/cupons/edit/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			 $('.editModal').attr('src','http://'+urlInicio+'/img/tb-edit.png');
		});
	});
	$('body').on('click','.viewModal', function(event){
		event.preventDefault();
		modalid = $(this).data('id');
		$("#loadDivModal").load('http://'+urlInicio+'/cupons/view/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
		});
	});
	$('body').on('click',".tipo_envio", function(){

		valTipo = $(this).val();

		if(valTipo ==0)
		{
			$('.custom-combobox').hide();
			$('.cliente_label').hide();

			$('.noneniver').hide();


		}
		if(valTipo ==1)
		{

			$('.custom-combobox').show();
			$('.cliente_label').show();
			$('.noneniver').hide();
		}
		if(valTipo ==2)
		{
			$('.custom-combobox').hide();
			$('.cliente_label').hide();
			$('.noneniver').show();
		}

	});

	(function( $ ) {
	    $.widget( "custom.combobox", {
	      _create: function() {
	        this.wrapper = $( "<span>" )
	          .addClass( "custom-combobox" )
	          .insertAfter( this.element );

	        this.element.hide();
	        this._createAutocomplete();
	        this._createShowAllButton();
	      },

	      _createAutocomplete: function() {
	        var selected = this.element.children( ":selected" ),
	          value = selected.val() ? selected.text() : "";

	        this.input = $( "<input>" )
	          .appendTo( this.wrapper )
	          .val( value )
	          .attr( "title", "" )
	          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
	          .autocomplete({
	            delay: 0,
	            minLength: 0,
	            source: $.proxy( this, "_source" )
	          })
	          .tooltip({
	            tooltipClass: "ui-state-highlight"
	          });

	        this._on( this.input, {
	          autocompleteselect: function( event, ui ) {
	            ui.item.option.selected = true;
	            this._trigger( "select", event, {
	              item: ui.item.option
	            });
	          },

	          autocompletechange: "_removeIfInvalid"
	        });
	      },

	      _createShowAllButton: function() {
	        var input = this.input,
	          wasOpen = false;

	        $( "<a>" )
	          .attr( "tabIndex", -1 )
	          .attr( "title", "Show All Items" )
	          .tooltip()
	          .appendTo( this.wrapper )
	          .button({
	            icons: {
	              primary: "ui-icon-triangle-1-s"
	            },
	            text: false
	          })
	          .removeClass( "ui-corner-all" )
	          .addClass( "custom-combobox-toggle ui-corner-right" )
	          .mousedown(function() {
	            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
	          })
	          .click(function() {
	            input.focus();

	            // Close if already visible
	            if ( wasOpen ) {
	              return;
	            }

	            // Pass empty string as value to search for, displaying all results
	            input.autocomplete( "search", "" );
	          });
	      },

	      _source: function( request, response ) {
	        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
	        response( this.element.children( "option" ).map(function() {
	          var text = $( this ).text();
	          if ( this.value && ( !request.term || matcher.test(text) ) )
	            return {
	              label: text,
	              value: text,
	              option: this
	            };
	        }) );
	      },

	      _removeIfInvalid: function( event, ui ) {

	        // Selected an item, nothing to do
	        if ( ui.item ) {
	          return;
	        }

	        // Search for a match (case-insensitive)
	        var value = this.input.val(),
	          valueLowerCase = value.toLowerCase(),
	          valid = false;
	        this.element.children( "option" ).each(function() {
	          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
	            this.selected = valid = true;
	            return false;
	          }
	        });

	        // Found a match, nothing to do
	        if ( valid ) {
	          return;
	        }

	        // Remove invalid value
	        this.input
	          .val( "" )
	          .attr( "title", value + " didn't match any item" )
	          .tooltip( "open" );
	        this.element.val( "" );
	        this._delay(function() {
	          this.input.tooltip( "close" ).attr( "title", "" );
	        }, 2500 );
	        this.input.autocomplete( "instance" ).term = "";
	      },

	      _destroy: function() {
	        this.wrapper.remove();
	        this.element.show();
	      }
	    });
	  })( jQuery );
		$(function() {
			$( "#toggle" ).click(function() {
				$( "#combobox" ).toggle();
			});
			setTimeout(function(){
				 $("#combocliente").combobox({
						select: function (event, ui) {

								$('.errotroco').hide();
						},
						 open: function(event,ui){

						 }
				});
		 },500);
			$("#CuponClienteId").combobox({
					select: function (event, ui) {

						 $('.errotroco').hide();
					},
					 open: function(event,ui){

					 }
			});
		});
});


</script>
