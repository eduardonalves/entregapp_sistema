<!-- Modal -->
<div class="modal fade modal-grande modal-viewVenda" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Visualizar Venda</h4>

      </div>
      <div class="modal-body">

			<?php echo $this->Form->create('Venda',array('class' => 'form-inline  centralizadoForm'));
				echo $this->Form->input('id',array(
												'default'=> $venda['Venda']['id'],
												'type'=>'hidden','class' => 'idView',
												'id'=>'idView','label' => false));
			?>
				<?php
						$data =$venda['Venda']['data'];
						$data = implode("-",array_reverse(explode("/",$data)));
						$venda['Venda']['data']= $data;

						$dataVenda=explode('-', $venda['Venda']['data']) ;
						$venda['Venda']['data'] = $dataVenda['2'].'/'.$dataVenda['1'].'/'.$dataVenda['0'];
					?>

				<span class="modal-subtitulo"><h3>Cód: <?php echo $venda['Venda']['id'];?></h3></span>
        <br>
				<h5 style="  text-align: center;   margin-top: 0px;   color: #5E5E5E; font-style: italic;"><?php echo 'Data: '. $venda['Venda']['data'] . ' / Hora:'.$venda['Venda']['hora_atendimento'].' / Status: <span class="statusView">'.$venda['Venda']['status'].'</span>' ;?></h5>


					<?php
						if( $venda['User']['username']!=''){
					?>
							<div class="form-group  form-group-lg">
								<label>Atendente:</label>
								<p><?php echo $venda['User']['username'];?></p>
							</div>
					<?php
						}
					?>
          <?php
						if( $venda['Venda']['obs']!=''){
					?>
          <div class="form-group  form-group-lg">
            <label>Observações</label>
            <p><span id="obsVenda"><?php echo h($venda['Venda']['obs']); ?></span></p>
          </div>
          <?php
						}
					?>
          <?php
						if( $venda['Venda']['desconto']!=''){
					?>
          <div class="form-group  form-group-lg">
            <label>Observações</label>
            <p><span id="obsVenda"><?php echo h($venda['Venda']['desconto']); ?></span></p>
          </div>
          <?php
						}
					?>
			<?php echo $this->Form->end();?>
			<section class="row-fluid">

        	<?php if (!empty($venda['Vendasiten'])): ?>
						<div class="area-tabela">
							<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
								<tbody>
									<thead class="cf">
										<tr>
											<th class="textCenter"><?php echo __('Código'); ?></th>
											<th class="textCenter"><?php echo __('Produto'); ?></th>
											<th class="textCenter"><?php echo __('Vl. Unit'); ?></th>
											<th class="textCenter"><?php echo __('Qtd'); ?></th>
											<th class="textCenter"><?php echo __('Vl. Total'); ?></th>
										</tr>
									</thead>
									<?php
                    $totProd =0;

                    foreach ($venda['Vendasiten'] as $itensdevenda):

                      if($itensdevenda['status']=='Ativo')
                      {
                          $totProd += $itensdevenda['valor_total'];
                      }
                    ?>
									<tr>

										<td data-title="Cód" class="textCenter"><?php echo $itensdevenda['produto_id']; ?></td>
										<td data-title="Nome" class="textCenter"><?php echo $itensdevenda['prodNome']; ?></td>
										<td data-title="Vl. Unit" class="textCenter"> <?php echo 'R$ ' . number_format($itensdevenda['valor_unit'], 2, ',', '.'); ?></td>
										<td data-title="Qtd" class="textCenter"><?php echo $itensdevenda['qtde']; ?></td>
										<td data-title="Vl. Total" class="textCenter"><?php echo 'R$ ' . number_format($itensdevenda['valor_total'], 2, ',', '.'); ?></td>
									</tr>

									<?php endforeach; ?>
									<tr id="linhaTotalVenda">
										<td colspan="4" style="text-align:left;">Total</td>
										<td id="valorTotalVenda" class="textCenter"><?php echo 'R$ ' . number_format($totProd, 2, ',', '.'); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php endif; ?>
			</section>

      <section class="row-fluid">

          <?php if (!empty($venda['Vendaspagamento'])): ?>
            <div class="area-tabela">
              <table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
                <tbody>
                  <thead class="cf">
                    <tr>

                      <th class="textCenter"><?php echo __('Pagamento'); ?></th>
                      <th class="textCenter"><?php echo __('Obs'); ?></th>
                      <th class="textCenter"><?php echo __('Valor'); ?></th>


                    </tr>
                  </thead>
                  <?php
                    $totPag =0;
                    foreach ($venda['Vendaspagamento'] as $pagamento):
                    if($pagamento['status']=='Ativo')
                    {
                        $totPag += $pagamento['valor'];
                    }
                  ?>
                  <tr>

                    <td data-title="Pagamento" class="textCenter"><?php echo $pagamento['pagNome']; ?></td>
                    <td data-title="Obs" class="textCenter"><?php echo $pagamento['obs']; ?></td>
                    <td data-title="Valor" class="textCenter"> <?php echo 'R$ ' . number_format($pagamento['valor'], 2, ',', '.'); ?></td>



                  </tr>

                  <?php endforeach; ?>
                  <tr id="linhaTotalVenda">
                    <td colspan="2" style="text-align:left;">Total</td>
                    <td id="valorTotalVenda" class="textCenter"><?php echo 'R$ ' . number_format($totPag, 2, ',', '.'); ?></td>
                  </tr>
                </tbody>
              </table>

            </div>
          <?php endif; ?>
          <?php
            if($venda['Venda']['desconto'] != '' ):

           ?>
          <div class="area-tabela">
            <table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
              <tbody>
                <thead class="cf">
                  <tr>
                    <th class="textCenter"><?php echo __('Desconto'); ?></th>
                  </tr>
                </thead>
                <tr>
                  <td data-title="Desconto" class="textCenter"> <?php echo number_format($venda['Venda']['desconto'], 2, ',', '.');  ?></td>
                </tr>
              </tbody>
                </table>
            </div>
              <?php
               endif;
               ?>
               <div class="area-tabela">
                 <table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
                   <tbody>
                     <thead class="cf">
                       <tr>
                         <th class="textCenter textTituloTotal"><?php echo __('Total'); ?></th>
                       </tr>
                     </thead>
                     <tr>
                       <td data-title="Totais" class="textCenter textValorTotal"> <?php
                       $vlTot = (($totProd + $venda['Venda']['taxa']) - $venda['Venda']['desconto']);
                        echo "R$ ".number_format($vlTot, 2, ',', '.');  ?></td>
                     </tr>
                   </tbody>
                     </table>
                 </div>
      </section>





			<?php
				echo $this->Form->create('Venda', array('id' => 'statusVendaForm'));
				echo $this->Form->input('id',array('type' => 'hidden', 'value' => $venda['Venda']['id'],'id' => 'idvenda'));
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
	var scoreVenda = $('#avalVenda').text();
	var idvenda= $('#idView').text();










});

</script>
