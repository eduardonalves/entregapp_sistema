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
	text-align:center;

}
	.logo-appedido{
			position: relative;
			margin: 0px auto;
			width: 120px;
			min-height: 40px;
		}
			.logo-appedido img{
				top: -90px;
				left: 0;
				/*border-radius: 35%;*/
				height: 120px;
				width: 120px;
				position: absolute;
			}

		.input-login{
			width: 95%;
			text-align: center;
		}

		.bt-login{
			width: 100%;
			margin: 10px auto;
			background: none repeat scroll 0% 0% #D91E19;
			border-radius: 5px;
			height: 30px;
			color: #FFF;
			font-weight: bold;
			border: medium none;
			font-size: 16px;
			cursor: pointer;

		}

		.bt-login:hover{
			background: none repeat scroll 0% 0% #EF4836;
		}
		.bt-login:focus{
			background: none repeat scroll 0% 0% #EF4836;
			border:1px solid #E74C3C;
		}
#rodape{
	background: #D91E19;
	border-top: 5px solid #EF4836;
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

@media (max-width: 700px){
	body{background-size: auto;}
	.centro{width: 80%;margin-top:100px;}
}

</style>

<div class="centro">
	<div class="logo-appedido">
			<?php echo $this->Html->image('entregap-home.png'); ?>
		</div>

		<?php echo $this->Session->flash(); ?>

		<?php
		if(isset($_GET['t']))
		{
			$token =  $_GET['t']; 
		}else{
			$token =  ''; 
		}
		echo $this->Session->flash('auth');

		echo $this->Form->create('Changesenha',array('action'=> '/RestClientes/formtrocasenha','id'=>'formtrocasenha'));
		   echo $this->Form->input('password',array('class' => 'input-login','label' => 'Senha:'));
		   echo $this->Form->input('confirmpassword',array('type'=>'password','class' => 'input-login','label' => 'Confirme a Senha:'));
		 echo $this->Form->input('tk',array('type' => 'hidden','value'=> $token ));
		   echo $this->Form->submit('Enviar',array('class'=>'bt-login'));
		echo $this->Form->end(); ?>
</div>
<div id="rodape">
	<p>Appedido - @2015 Todos os direitos reservados -
		<a href="#" target="_blank" rel="nofollow">Agência Kanguru</a>
	</p>
</div>

<script type="text/javascript">
$('document').ready(function(){
	var urlInicio      = window.location.host;
	if(urlInicio=="localhost" ){
		urlAction= "/entregapp_sistema/RestClientes/formtrocasenha";	
	}else{
		urlAction= "/RestClientes/formtrocasenha";	
	} 
	$('#formtrocasenha').attr('action',urlAction);
	//$('#ChangesenhaPassword').val(null);
	//$('#ChangesenhaConfirmpassword').val(null);
	/*$('#formtrocasenha').submit(function(event){

		event.preventDefault();
		valor1 = $('#ChangesenhaPassword').val();
		valor2 = $('#ChangesenhaConfirmpassword').val();

		if(valor1 != '' && (valor1 == valor2)){
			
				$('#formtrocasenha').submit();
				
			
		}else{
			alert('Aviso: As senhas digitadas não podem ser vaizas e não podem serdiferentes!');
		}
	});*/
});
</script>







