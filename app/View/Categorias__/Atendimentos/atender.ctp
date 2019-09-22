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
			<h4>Atendimento</h4>
			
			<dl class="dl-horizontal">
					<dt>Código :</dt>
					<dd><?php echo $atendimento['Atendimento']['codigo'];?></dd>
					<?php if(isset($atendimento['Cliente']['nome']) && !empty($atendimento['Cliente']['nome'])){?>
					<dt>Cliente :</dt>
					<dd><?php echo $atendimento['Cliente']['nome'];?></dd>
					<?php } ?>
					<?php if(isset($atendimento['User']['username']) && !empty($atendimento['User']['username'])){?>
					<dt>Atendente :</dt>
					<dd><?php echo $atendimento['User']['username'];?></dd>
					<?php } ?>
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
							<th class="span2">V.Und</th>
							<th class="span1">Qtde</th>
							<th class="span2">V. Total</th>
						</tr>
					</thead>
					<tbody>
					<?php $valorTotalPedido=0;?>
					<?php if(!empty($itensdepedidos)){?>	
						<?php foreach($itensdepedidos as $itensdepedido){?>
						<tr>
							<td><?php echo $itensdepedido['Produto']['id']; ?></td>
							<td><?php echo $itensdepedido['Produto']['nome']; ?></td>
							<td><?php echo "R$ ".number_format($itensdepedido['Produto']['preco_venda'],2,",","."); ?></td>
							<td><?php echo $itensdepedido['Itensdepedido']['qtde']; ?></td>
						
						<?php  
							$valorTotal = $itensdepedido['Produto']['preco_venda'] * $itensdepedido['Itensdepedido']['qtde']; 
							$valorTotalPedido += $valorTotal;
						?>
						<td><?php echo "R$ ".number_format($valorTotal,2,",","."); ?></td>
						</tr>
						
						<?php } ?>
					<?php } ?>
					<tr id="linhaTotal">
							<td colspan="4">Total</td>
							<td id="totalPedido"><?php echo "R$ ".number_format($valorTotalPedido,2,",","."); ?></td>
					</tr>	
					</tbody>
					
				</table>
				<?php echo $this->Html->link(__('Fazer Pedido'), array('controller'=> 'pedidos','action' => 'add',$codigo)); ?> 
				<button class="btn" type="button" id="pedir">Enviar Pedido</button>
				<div class="loaderAjax">
					 <?php echo $this->Html->image("ajaxloader.gif", array('class' => 'ajax'));?><small>Enviando Pedido</small>
				</div>
				<div class="erroqtde">
					<small>Erro: Não existem itens para enviar, adicione itens no pedido</small>
				</div>		
		</div>
	</div>
 <style type="text/css">
      br { clear: both; }
      .cntSeparator {
        font-size: 54px;
        /*margin: 10px 7px;*/
        color: #000;
      }
      .desc { margin: 7px 3px; }
      .desc div {
        float: left;
        font-family: Arial;
        width: 70px;
        margin-right: 65px;
        font-size: 13px;
        font-weight: bold;
        color: #000;
      }
    </style>	
<script>
	
$(document).ready(function() {
	var hora = "<?php echo $difHora;?>";
	 $('#counter').countdown({
          image: '../../img/digits2.png',
          startTime: hora,
		  digitWidth: 34,
		    digitHeight: 45,
		    format: 'hh:mm:ss',
        });
		
});		
</script>