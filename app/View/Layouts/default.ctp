<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('entrega_app', 'EntragApp Delivery');
?>
<?php echo $this->Html->docType('html5');?>
<html>
<head>
	<?php echo $this->Html->charset('UTF-8'); ?>
	<title>
		Rudo - Aplicativo de entregas.
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC1Fq4149nqLAr86NBlDE0_01kgzOaV_Qg&amp;sensor=false"></script>

	<?php
		if($_SERVER['SERVER_NAME'] == 'localhost'){
			echo $this->Html->meta('icon', $this->Html->url('http://'.$_SERVER['SERVER_NAME'] . '/entregapp_sistema'.'/img/favicon.png'));
		}else{
			echo $this->Html->meta('icon', $this->Html->url('http://'.$_SERVER['SERVER_NAME'] .'/img/favicon.png'));
		}
		

		echo $this->Html->css('styles');

		echo $this->fetch('meta');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive');
		echo $this->Html->css('owl.carousel');
		echo $this->Html->css('owl.theme');
		echo $this->Html->css('jquery.raty');
		echo $this->Html->css('progressbar');
		echo $this->Html->css('jquery-ui-1.10.1.custom');
		echo $this->Html->css('jquery.ui.combogrid');

		echo $this->Html->css('Chart.min');
		echo $this->Html->css('estilo');
		//echo $this->Html->css('sb-admin-2.min');
		echo $this->Html->css('tabelas');

		echo $this->fetch('css');
		//echo $this->Html->script('collapse');
		echo $this->Html->script('jquery');
		echo $this->Html->script('jquery-ui-1.10.1.custom.min');
		echo $this->Html->script('jquery.ui.combogrid-1.6.3');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('owl.carousel');
		echo $this->Html->script('jquery.price');
		echo $this->Html->script('jquery.nicescroll.min');
		echo $this->Html->script('jquery.maskedinput');
		echo $this->Html->script('countdown');

		echo $this->Html->script('jquery.raty');
		echo $this->Html->script('infobox');
		echo $this->Html->script('mapcluster');
		echo $this->Html->script('angular.min');

		echo $this->Html->script('ui-bootstrap-tpls-0.10.0.min');
		echo $this->Html->script('Chart.min');

		echo $this->Html->script('funcao');
		//echo $this->Html->script('sb-admin-2.min');
		echo $this->fetch('script');
		$isCatalog =  $this->Session->read('catalogMode');

	?>

<!--<link href="/../summernote/summernote.css" rel="stylesheet">
<script src="/../summernote/summernote.js"></script>-->


</head>
<body role="document">
	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption:400,700' rel='stylesheet' type='text/css'>

	<section style="position:relative;min-height:100%;">
	<!-- INICIO MENU SUPERIOR -->
	<div  class="row-fluid">
		    <nav class="navbar navbar-default navbar-fixed-top">
				  <div class="container-fluid">

				    <!-- Brand and toggle get grouped for better mobile display -->
				    <div class="navbar-header">
				      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				      </button>
				   	 <?php
									echo $this->Html->link(
										$this->Html->image("favicon.png", array("alt" => "Pedidos", "height" => '48px', 'width' => '48px','class'=>'logo-nav')),
										"/",
										array('escape' => false)
									);
								?>

				    </div>

				    <!-- Collect the nav links, forms, and other content for toggling -->
				    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					      <ul class="nav navbar-nav">
					        <!--<li class="active"><a href="#">Início <span class="sr-only">(current)</span></a></li>-->

							
									<?php //if($isCatalog==false): ?>
					          <!--<li>-->
										<?php
										//	echo $this->Html->link(
											//	$this->Html->image("mapa.png", array("alt" => "Mapas")).'&nbsp; Mapa',
											//	"/pedidos/mapa",
											//	array('escape' => false)
											//);
										?>
									<!--</li>-->
									<?php //endif;?>
							<!-- <li>
								<?php
								//$this->Html->image("icon-relatorio.png", array("alt" => "Relatorios")).
									/*echo $this->Html->link(
										'Relatórios',
										"/report_manager/reports",
										array('escape' => false,'id'=>'relat-menu')
									);*/
								?>
							</li>-->

					        <li class="dropdown">

					          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo $this->Html->image('lista-pedidos.png',array('class'=>'icon-menu')); ?>Acões<span class="caret"></span></a>
					          <ul class="dropdown-menu" role="menu">
					        
								<?php if($isCatalog==false): ?>
									<li><?php echo $this->Html->link(__('Pedidos'), '/Pedidos'); ?></li>
									<li><?php echo $this->Html->link(__('Painel Inicial'), '/'); ?></li>
									<li><?php echo $this->Html->link(__('Vendas'), '/Vendas'); ?></li>
									<li><?php echo $this->Html->link(__('Salão'), '/mesas/salao'); ?></li>
									<li><?php echo $this->Html->link(__('Solicitações p/ Fazer'), '/Setores/monitorar'); ?></li>
									<li><?php echo $this->Html->link(__('Solicitações p/ Entrega'), '/Setores/prontos'); ?></li>
									<li><?php echo $this->Html->link(__('Movimentos'), '/Fechamentos'); ?></li>
									<li><?php echo $this->Form->postLink(
										  'Abrir Caixa', //le image
										  array('controller'=>'Fechamentos','action' => 'abrirmovimento',1), //le url
										  array('escape' => false), //le escape
										  __('Deseja abrir o caixa?')
									);?></li>
									<li><?php echo $this->Form->postLink(
										  'Fechar Movimento', //le image
										  array('controller'=>'Fechamentos','action' => 'fecharmovimento',1), //le url
										  array('escape' => false), //le escape
										  __('Deseja fechar o movimento?')
									);?></li>
								<?php endif;?>
<!--
					            <li><?php //echo $this->Html->link(__('Mesas'), array('controller' => 'mesas', 'action' => 'add')); ?></li>
-->
					          </ul>
					        </li>
					        <li class="dropdown">

					          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo $this->Html->image('lista-cadastros.png',array('class'=>'icon-menu')); ?>Cadastros<span class="caret"></span></a>
					          <ul class="dropdown-menu" role="menu">
					            <li><?php echo $this->Html->link(__('Clientes'), '/Clientes/add'); ?></li>
											<li><?php echo $this->Html->link(__('Categorias'), '/Categorias/add'); ?></li>
					            <li><?php echo $this->Html->link(__('Produtos'), '/Produtos/add'); ?></li>
											<?php if($isCatalog==true): ?>
												<li><?php echo $this->Html->link(__('Cupons de Desconto'), '/Cupons/add'); ?></li>
											<?php endif;?>
											<?php if($isCatalog==false): ?>
												
												<li><?php echo $this->Html->link(__('Atendentes'), '/Atendentes/add'); ?></li>
					            <li><?php echo $this->Html->link(__('Entregadores'), '/Entregadors/add'); ?></li>
											<li><?php echo $this->Html->link(__('Mesas / Cadastrar'), '/mesas/add'); ?></li>
												
											<li><?php echo $this->Html->link(__('Setores'), '/Setores/add'); ?></li>
											
					            <li><?php echo $this->Html->link(__('Bairros para Entrega'), '/bairros/add'); ?></li>
											<li><?php echo $this->Html->link(__('Cidades para Entrega'), '/cidads/add'); ?></li>
											<li><?php echo $this->Html->link(__('UFs para Entrega'), '/estados/add'); ?></li>
					            <li><?php echo $this->Html->link(__('Formas de Pagamento'),'/Pagamentos/add'); ?></li>

					           

											<?php endif;?>
<!--
					            <li><?php //echo $this->Html->link(__('Mesas'), array('controller' => 'mesas', 'action' => 'add')); ?></li>
-->
					          </ul>
							</li>
							
							<li class="dropdown">

					          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo $this->Html->image('financeiro.png',array('class'=>'icon-menu','style="margin-top: -3px;')); ?>Financeiro<span class="caret"></span></a>
					          <ul class="dropdown-menu" role="menu">
					            <li><?php echo $this->Html->link(__('Controle de Despesas'), '/Despesas/add'); ?></li>
								<li><?php echo $this->Html->link(__('Categorias de Despesas'), '/categoriasdespesas/add'); ?></li>
	
					          </ul>
					        </li>

					      </ul>


					      <ul class="nav navbar-nav navbar-right">
					        <li class="dropdown">
					        	<?php
									$foto = $this->Session->read('Auth.User.foto');
								?>
					          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					          	



					          	Configurações <span class="caret"></span></a>
					          <ul class="dropdown-menu" role="menu">
					          <?php if($this->Session->read('Auth.User.funcao_id')==1){ ?>
					             <li><?php echo $this->Html->link(__('Usuários'), '/Users/add'); ?> </li>
			          			 <li><?php echo $this->Html->link(__('Funções'), '/Funcaos/add'); ?> </li>
			          			 <?php } ?>
			          			 <li><?php echo $this->Html->link(__('Loja'), '/Filials/add'); ?></li>
			                     <li><?php echo $this->Html->link(__('Sair'), '/Users/logout'); ?></li>
					          </ul>
					        </li>
					      </ul>
				    </div><!-- /.navbar-collapse -->
			  	</div><!-- /.container-fluid -->
			</nav>
		</div>
	<!-- FIM MENU SUPERIOR -->

	<!-- INICIO CONTEÙDO-->
	<div  class="container-fluid content">

		<!-- MENSAGEM DA SESSÃO -->
			<?php echo $this->Session->flash(); ?>


		<!-- CONTEÚDO DO SITE -->
			<div class="container-fluid">
				<?php echo $this->fetch('content'); ?>
			</div>
	</div>
	</section>

	<!--<div id="rodape" style="position: fixed; bottom: 0%;">
		<p>Entregapp - @2015 Todos os direitos reservados

		</p>
	</div>-->


	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
