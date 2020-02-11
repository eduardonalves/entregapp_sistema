<!-- Modal -->
<div class="modal fade modal-grande modal-viewFechamento" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Visualizar Fechamento</h4>

      </div>
      <div class="modal-body">

			<?php echo $this->Form->create('Fechamento',array('class' => 'form-inline  centralizadoForm'));
				echo $this->Form->input('id',array(
												'default'=> $fechamento['Fechamento']['id'],
												'type'=>'hidden','class' => 'idView',
												'id'=>'idView','label' => false));
			?>
				<?php
						$data =$fechamento['Fechamento']['data'];
						$data = implode("-",array_reverse(explode("/",$data)));
						$fechamento['Fechamento']['data']= $data;

						$dataFechamento=explode('-', $fechamento['Fechamento']['data']) ;
						$fechamento['Fechamento']['data'] = $dataFechamento['2'].'/'.$dataFechamento['1'].'/'.$dataFechamento['0'];
					?>

				<span class="modal-subtitulo"><h3>Cód: <?php echo $fechamento['Fechamento']['id'];?></h3></span>
        <br>
				<h5 style="  text-align: center;   margin-top: 0px;   color: #5E5E5E; font-style: italic;"><?php echo 'Data: '. $fechamento['Fechamento']['data'] . '</span>' ;?></h5>


          <?php
						if( $fechamento['Fechamento']['total']!=''){
					?>
          <div class="form-group  form-group-md col-md-3">
            <label>Total</label>

            <p><span id="obsFechamento"><?php echo $fechamento['Fechamento']['total']; ?></span></p>
          </div>
          <?php
						}
					?>
          <?php
            if( $fechamento['Fechamento']['taxa']!=''){
          ?>
          <div class="form-group  form-group-md col-md-3">
            <label>Taxa de Serviço</label>
            <p><span >R$ <?php echo number_format($fechamento['Fechamento']['taxa'],2,',','.'); ?></span></p>
          </div>
          <?php
            }
          ?>
			<?php echo $this->Form->end();?>
			<section class="row-fluid">

        	<?php 
          
           $TotPag =0;
           $totTaxa=0;
          if (!empty($fechamento['Fechamentoiten'])): ?>

						<div class="area-tabela">
							<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
								<tbody>
									<thead class="cf">
										<tr>
											
											<th class="textCenter"><?php echo __('Pagamento'); ?></th>
											<th class="textCenter"><?php echo __('Total'); ?></th>
											<th class="textCenter"><?php echo __('Taxa'); ?></th>
										</tr>
									</thead>
									<?php
        

                    foreach ($fechamento['Fechamentoiten'] as $itensdefechamento):

                     

                          $TotPag += $itensdefechamento['total'];
                          $totTaxa += $itensdefechamento['total_taxa'];
                      
                    ?>
									<tr>

										<td data-title="Cód" class="textCenter"><?php echo $itensdefechamento['pagamentonome']; ?></td>
										
										<td data-title="Vl. Unit" class="textCenter"> <?php echo 'R$ ' . number_format($itensdefechamento['total'], 2, ',', '.'); ?></td>
										
										<td data-title="Vl. Total" class="textCenter"><?php echo 'R$ ' . number_format($itensdefechamento['total_taxa'], 2, ',', '.'); ?></td>
									</tr>

									<?php endforeach; ?>
                  <tr id="linhaTotalFechamento">
                    
                    <th class="textCenter" style="background: #000000;" colspan="3"><?php echo __('Resumo'); ?></th>
                  </tr>

									<tr id="linhaTotalFechamento">
										<td colspan="2" style="text-align:left;">Total Serviços</td>
										<td id="valorTotalFechamento" class="textCenter"><?php echo 'R$ ' . number_format($totTaxa, 2, ',', '.'); ?></td>
									</tr>
                  <tr id="linhaTotalFechamento">
                    <td colspan="2" style="text-align:left;">Total Vedas</td>
                    <td id="valorTotalFechamento" class="textCenter"><?php echo 'R$ ' . number_format($TotPag, 2, ',', '.'); ?></td>
                  </tr>
                  <tr id="linhaTotalFechamento">
                    <td colspan="2" style="text-align:left;">Total Geral</td>
                    <td id="valorTotalFechamento" class="textCenter"><?php echo 'R$ ' . number_format($TotPag + $totTaxa , 2, ',', '.'); ?></td>
                  </tr>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
			</section>





			<?php
				echo $this->Form->create('Fechamento', array('id' => 'statusFechamentoForm'));
				echo $this->Form->input('id',array('type' => 'hidden', 'value' => $fechamento['Fechamento']['id'],'id' => 'idfechamento'));
				echo $this->Form->end();

			?>
		</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
			</div>

		</div>
	</div>
</div>



<style media="screen">
  .textCenter{
    text-align: center;
  }
  .textValorTotal{
    font-weight: bold;
    font-size: 28px;
  }
  .textTituloTotal{
    font-size: 20px;
  }
</style>
<script>
$(document).ready(function() {

	var urlInicio = window.location.host;
	urlInicio= urlInicio;
  if(urlInicio=="localhost" ){
    urlInicio= "localhost/entregapp_sistema"; 
  } 
	var scoreFechamento = $('#avalFechamento').text();
	var idfechamento= $('#idView').text();










});

</script>
