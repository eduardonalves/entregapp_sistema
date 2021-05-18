<?php
	$this->response->header('Access-Control-Allow-Origin','*');
	//$this->response->header('Access-Control-Allow-Methods','POST');
	//$this->response->header('Access-Control-Allow-Headers','X-Requested-With');
	//$this->response->header('Access-Control-Max-Age','172800');
	//$this->response->type('json');
	if(isset($users)){
		$this->response->type('json');
		echo json_encode($users);
	}
?>

<style type="text/css" media="all">
body{
	background-image: url("../img/background.jpg");

	background-attachment: fixed;
	background-size: cover;
	padding: 0 !important;
}
*{font-family: Verdana, Geneva, sans-serif; margin: 0px; padding: 0px;}



.centro{
	max-width: 400px;
	height: auto;
	padding: 20px;
	position: relative;
	border-radius: 5px;
	background: rgba(255,255,255,0.8);
	box-shadow: 0px 5px 10px #ccc;
	margin: auto;
	margin-top: 7%;
	margin-bottom: 100px;
	overflow: hidden;
	/*text-align:center;*/

}
	.logo-appedido{
			position: relative;
			margin: 0px auto;
			width: 120px;
			min-height: 40px;
		}
			.logo-appedido img{
				top: -189px;
	    		left: -38px;
	    		/* border-radius: 35%; */
	    		height: 220px;
	    		width: 220px;
	    		position: absolute;
			}

		.input-login{
			width: 95%;
			text-align: center;
		}

		.bt-login{
			width: 100%;
			margin: 10px auto;
			background: none repeat scroll 0% 0% #000000;
			border-radius: 5px;
			height: 30px;
			color: #FFF;
			font-weight: bold;
			border: medium none;
			font-size: 16px;
			cursor: pointer;

		}

		.bt-login:hover{
			background: none repeat scroll 0% 0% #191919;
		}
		.bt-login:focus{
			background: none repeat scroll 0% 0% #191919;
			border:1px solid #E74C3C;
		}
#rodape{
	background: #000000;
	border-top: 5px solid #191919;
	width: 100%;
	position: fixed;
	bottom: 0px;
}
	#rodape p,a{
		margin: 0px;
		padding: 0px;
		color: #fff;
		font-size: 12px;
		padding: 15px;
	}

/*@media (max-width: 700px){
	body{background-size: auto;}
	.centro{width: 80%;margin-top:100px;}
}*/
h2{
	text-align: center;
	
}
p{
	text-align: center;
	font-size: 12pt;
	padding: 6pt;
}
input, textarea {
	width:auto !important;
}
.divpagseguro{
	margin: 0 auto;
	width: 209px;
}
</style>

<div class="centro">
	
<?php echo $this->Session->flash(); ?>

<?php
echo $this->Session->flash('auth');
?>
		<?php 
			//echo "<pre>";
			//print_r($hasPagseguro);
			//die;
		?>
		<?php
		
			if($hasPagseguro['Pagseguro']['status'] != 'ACTIVE'){
		?>
		<h2>Assine já!</h2>
	
		<p>
			Assine o plano padrão da plataforma Rudo por apenas R$ 29,90 por mês e aproveite a toda a plataforma sem preocupação.
		</p>
		
		<div class="divpagseguro">
			
			<?php
				echo $this->Html->link(
					$this->Html->image('/img/pagsegurobtn.jpg',array('height' => '100', 'width' => '200')),
					$hasPagseguro['Pagseguro']['checkouturl'],
					 array('escape' => false,'target' => '_blank')
				);
			?>
			<!-- INICIO FORMULARIO BOTAO PAGSEGURO: NAO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
			<!--<form action="https://pagseguro.uol.com.br/pre-approvals/request.html" method="post">
				<input type="hidden" name="code" value="D812A8038484878224C7BFB19469B1EB" />
				<input type="hidden" name="iot" value="button" />
				<input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/assinaturas/209x48-assinar-assina.gif" name="submit" alt="Pague com PagSeguro - É rápido, grátis e seguro!" width="209" height="48" />
			</form>-->
			<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
		</div>
		<?php
			}else{

			
		?>
			<h2>Deseja cancelar seu plano?</h2>
	
				<p>
					Para cancelar seu plano clique imagem abaixo!
 				</p>
				<div class="divpagseguro">
					
					<?php
						echo $this->Html->link(
							$this->Html->image('/img/pag-segurologo.png',array('height' => '100', 'width' => '200')),
							$hasPagseguro['Pagseguro']['cancelurl'],
							array('escape' => false,'target' => '_blank')
						);
					?>
					<!-- INICIO FORMULARIO BOTAO PAGSEGURO: NAO EDITE OS COMANDOS DAS LINHAS ABAIXO -->
					<!--<form action="https://pagseguro.uol.com.br/pre-approvals/request.html" method="post">
						<input type="hidden" name="code" value="D812A8038484878224C7BFB19469B1EB" />
						<input type="hidden" name="iot" value="button" />
						<input type="image" src="https://stc.pagseguro.uol.com.br/public/img/botoes/assinaturas/209x48-assinar-assina.gif" name="submit" alt="Pague com PagSeguro - É rápido, grátis e seguro!" width="209" height="48" />
					</form>-->
					<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->
				</div>
		<?php
			}
		
		//echo $this->Form->input('nome',array('label' => 'Nome do Estabelecimento:'));
		
		?>	
</div>





<script >

</script>




