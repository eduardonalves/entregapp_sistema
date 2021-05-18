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
p{text-align: center;}


.centro{
	max-width: 400px;
	height: auto;
	padding: 20px;
	position: relative;
	border-radius: 5px;
	background: rgba(255,255,255,0.8);
	box-shadow: 0px 5px 10px #ccc;
	margin: auto;
	margin-top: 13%;
	margin-bottom: 100px;
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

</style>

<div class="centro">
	
	<div class="logo-appedido">
			<?php echo $this->Html->image('entregap-home.png'); ?>
		</div>

		<?php echo $this->Session->flash(); ?>

		<?php
		echo $this->Session->flash('auth');
		?>

		<?php
			echo $this->Form->create('Empresa', array('class' => 'form-inline', 'type' => 'file'));
		?>
			<div class="span12">
				<h5> Registro de Estabelecimentos</h5>
			</div>
			<div class="span3">
				<?php

				
				echo $this->Form->input('nome',array('label' => 'Nome do Estabelecimento:','placeholder' =>'Digite o nome do estabelecimento'));
				
				?>
			</div>
			<div class="span3">
				<?php
				echo $this->Form->input('email',array('label' => 'Email:','placeholder' =>'seumail@email.com'));
				?>
			</div>
			<div class="span3">
				<?php
				echo $this->Form->input('slug',array('label' => CARDAPIO_URL,'placeholder' =>'minhaempresa'));
				?>
			</div>
			
			<div class="span3">
				<?php
				echo $this->Form->input('password',array('label' => 'Senha:','placeholder' =>'Digite sua senha'));
				?>
			</div>
			<div class="span3">
				<?php
				echo $this->Form->input('re_password',array('label' => 'Confirme sua Senha:', 'type' =>'password','placeholder' =>'Digite novamente sua senha'));
				?>
			</div>
		
		<?php
		   
		   
		   
		   echo $this->Form->submit('Registrar',array('class'=>'bt-login'));
		echo $this->Form->end(); ?>
	</div>	
</div>
<div id="rodape">
	<p>Rudo - @2020 Todos os direitos reservados
		
	</p>
</div>









