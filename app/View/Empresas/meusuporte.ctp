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
		
		<h2>Suporte</h2>
	
		<p>
			Contatos do nosso suporte técnico.
			<br /> Whatsapp: 21 97322-0773 
			<br /> E-mail: suporte@rudo.com.br
		</p>
		
		
		
</div>





<script >

</script>




