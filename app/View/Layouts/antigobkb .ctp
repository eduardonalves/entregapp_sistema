<body class="hold-transition sidebar-mini layout-fixed">
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