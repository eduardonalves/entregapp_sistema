<style>
	#modalBodyCadastrarMesa{height: 130px; overflow: hidden; padding-top: 73px; }
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
.btn-comprar{margin-top: 15px;}
.layerslide{
	background-color: rgba(0, 0, 0, 0.8);
	color:white;
	
	
	max-height: 30%;
	overflow:hidden;
	padding: 9px;
	margin-top:15px;
	
}

.slider{
	padding: 1px;
	
}
.accordion-group {border: 2px solid #FFFFFF; 

}
#pedir{float:left;}
.loaderAjax{color:#FFFFFF; float: left; margin-left: 8px; margin-top: 7px; display:none;}
.loaderAjax small{margin-left:5px;}
.erroqtde{color:#FFFFFF; float: left; margin-left: 8px; margin-top: 7px; display:none;}
</style>
	<div class="row-fluid">
		<div class="Atendimento">
			<h4>Pedidos</h4>
			<?php foreach($pedidos as $pedido){?>
			<dl class="dl-horizontal">
					<dt>Código :</dt>
					<dd><?php echo $pedido['Atendimento']['codigo'];?></dd>
					
					
					
					<?php if(isset($atendimento['Mesa']['identificacao']) && !empty($atendimento['Mesa']['identificacao'])){?>
					<dt>Mesa :</dt>
					<dd><?php echo $atendimento['Mesa']['identificacao'];?></dd>
					<?php } ?>
					<?php if(isset($atendimento['Atendimento']['avaliacao']) && !empty($atendimento['Atendimento']['avaliacao'])){?>
					<dt>Avaliação :</dt>
					<dd><?php echo $atendimento['Atendimento']['avaliacao'];?></dd>
					<?php } ?>
					<?php if(isset($pedido['Pedido']['posicao_fila']) && !empty($pedido['Pedido']['posicao_fila'])){?>
					<dt>Posicao Fila :</dt>
					<dd><?php echo $pedido['Pedido']['posicao_fila'];?></dd>
					<?php } ?>
					<dt>Data :</dt>
					<dd><?php echo $pedido['Pedido']['data'];?></dd>
					<dt>Hora :</dt>
					<dd><?php echo $pedido['Pedido']['hora_atendimento'];?></dd>
					<dt>Previsão :</dt>
					
					
			</dl>
			<div id="counter"></div>
			
		</div>
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" id="pedido">
					<thead>
						<tr>
							<th class="span1">Cód</th>
							<th>Produto</th>
							<th>Qtde</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
					<?php $valorTotalPedido=0;?>
					<?php if(!empty($pedido['Itensdepedido'])){?>	
						<?php foreach($pedido['Itensdepedido'] as $itensdepedido){?>
						<tr>
							<td><?php echo $itensdepedido['produto_id']; ?></td>
						
							<td><?php echo $itensdepedido['produto']; ?></td>
							<td><?php echo $itensdepedido['qtde']; ?></td>
						<td>Ações</td>
						</tr>
						
						<?php } ?>
					<?php } ?>
						
					</tbody>
					
				</table>
				<?php echo $this->Html->link(__('Fazer Pedido'), array('controller'=> 'pedidos','action' => 'add',$pedido['Atendimento']['codigo'])); ?> 
				<button class="btn" type="button" id="pedir">Enviar Pedido</button>
				<div class="loaderAjax">
					 <?php echo $this->Html->image("ajaxloader.gif", array('class' => 'ajax'));?><small>Enviando Pedido</small>
				</div>
				
		</div>
		<?php } ?>	
	</div>
	

