
 	<?php
                        $coutmsg=0;
                        if(isset($mensagenspedidos)){
                            if(!empty($mensagenspedidos)){
                                 foreach($mensagenspedidos['Pedido'] as $msgAtiva){
                                     echo '<p id="idpedido'.$msgAtiva['pedido_id'].'"><a data-id="'.$msgAtiva['pedido_id'].'" class="editpedido">Pedido: '.$msgAtiva['codigo'].' '.$msgAtiva['username'].'</a></p>';
                                    $coutmsg++;
                                 }
                            }

                        }



	?>

	<input type="hidden" value="<?php echo $coutmsg; ?>" id="quantia-mensagens">
    <script type="text/javascript">
    $(document).ready(function() {
      setTimeout(function () {
        qtdeMsg = $('#quantia-mensagens').val();
        console.log(qtdeMsg);

        if(qtdeMsg != ''){
            $('#msg-qtd').html(qtdeMsg);
        }else{
            $('#msg-qtd').html(0);
        }
      },2000);


    });
    </script>
