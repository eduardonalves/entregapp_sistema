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
<?php echo $this->Html->docType('html5'); ?>
<html>

<head>
	<?php echo $this->Html->charset('UTF-8'); ?>
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC1Fq4149nqLAr86NBlDE0_01kgzOaV_Qg&amp;sensor=false"></script>

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Rudo - Aplicativo de entregas.</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<?php
	if ($_SERVER['SERVER_NAME'] == 'localhost') {
		echo $this->Html->meta('icon', $this->Html->url('http://' . $_SERVER['SERVER_NAME'] . '/entregapp_sistema' . '/img/favicon.png'));
	} else {
		echo $this->Html->meta('icon', $this->Html->url('http://' . $_SERVER['SERVER_NAME'] . '/img/favicon.png'));
	}



	//echo $this->Html->css('styles');

	echo $this->fetch('meta');
	echo $this->Html->css('plugins/fontawesome-free/css/all.min.css');
	echo $this->Html->css('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css');
	echo $this->Html->css('plugins/icheck-bootstrap/icheck-bootstrap.min.css');
	echo $this->Html->css('plugins/jqvmap/jqvmap.min.css');
	echo $this->Html->css('dist/css/adminlte.min.css');
	echo $this->Html->css('dist/css/skin-midnight.min.css');
	echo $this->Html->css('plugins/overlayScrollbars/css/OverlayScrollbars.min.css');
	echo $this->Html->css('plugins/daterangepicker/daterangepicker.css');
	echo $this->Html->css('plugins/summernote/summernote-bs4.css');
	echo $this->Html->css('progressbar');
	echo $this->Html->css('jquery-ui-1.10.1.custom');
	echo $this->Html->css('jquery.ui.combogrid');

	echo $this->Html->css('estilo_lovo');
	/*echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive');
		echo $this->Html->css('owl.carousel');
		echo $this->Html->css('owl.theme');
		echo $this->Html->css('jquery.raty');
		echo $this->Html->css('progressbar');
		echo $this->Html->css('jquery-ui-1.10.1.custom');
		echo $this->Html->css('jquery.ui.combogrid');
		echo $this->Html->css('estilo');
		
		echo $this->Html->css('tabelas');*/

	echo $this->fetch('css');

	/*echo $this->Html->script('jquery');
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
		

		echo $this->Html->script('ui-bootstrap-tpls-0.10.0.min');*/

	echo $this->Html->script('angular.min');
	echo $this->Html->script('jquery');
	echo $this->Html->script('jquery-ui-1.10.1.custom.min');
	echo $this->Html->script('jquery.ui.combogrid-1.6.3');
	echo $this->Html->script('jquery.maskedinput');
	echo $this->Html->script('jquery.price');
	echo $this->Html->script('funcao');
	echo $this->Html->script('plugins/bootstrap/js/bootstrap.bundle.min.js');
	echo $this->Html->script('plugins/chart.js/Chart.min.js');
	echo $this->Html->script('plugins/sparklines/sparkline.js');
	echo $this->Html->script('plugins/jqvmap/jquery.vmap.min.js');
	echo $this->Html->script('plugins/jqvmap/maps/jquery.vmap.usa.js');
	echo $this->Html->script('plugins/jquery-knob/jquery.knob.min.js');
	echo $this->Html->script('plugins/moment/moment.min.js');
	echo $this->Html->script('plugins/daterangepicker/daterangepicker.js');
	echo $this->Html->script('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js');
	echo $this->Html->script('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js');
	echo $this->Html->script('dist/js/adminlte.js');
	//echo $this->Html->script('dist/js/pages/dashboard.js');
	echo $this->Html->script('dist/js/demo.js');


	echo $this->Html->script('plugins/bootstrap/js/bootstrap.bundle.min.js');
	echo $this->Html->script('dist/js/adminlte.min.js');
	echo $this->fetch('script');
	$isCatalog =  $this->Session->read('catalogMode');

	?>
	<!--<link href="/../summernote/summernote.css" rel="stylesheet">
<script src="/../summernote/summernote.js"></script>-->
</head>

<body class="hold-transition layout-top-nav skin-midnight">
	<div class="wrapper skin-midnight">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand-md skin-midnight">
			<div class="container skin-midnight">
				<a href="../../index3.html" class="navbar-brand skin-midnight">

					<?php
					echo $this->Html->link(
						$this->Html->image("favicon.png", array("alt" => "Pedidos", "height" => '48px', 'width' => '48px', 'class' => 'logo-nav', 'style' => 'opacity: .8')),
						"/",
						array('escape' => false)
					);
					?>
					<span class="brand-text font-weight-light">Rudo - Sistema de Entregas</span>
				</a>

				<button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse order-3" id="navbarCollapse">
					<!-- Left navbar links -->
					<ul class="navbar-nav skin-midnight">
						<li class="nav-item">


							<?php
							echo $this->Html->link(__('Pedidos', true), array('controller' => 'Pedidos', 'action' => 'index'), array('class' => 'nav-link skin-midnight-aux-text'));

							?>
						</li>

						<!--<li class="nav-item dropdown">
							<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Ações</a>
							<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
								<li>
									<?php
									//echo $this->Html->link(__('Vendas', true), array('controller' => 'vendas', 'action' => 'index'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									//echo $this->Html->link(__('Salão', true), array('controller' => 'mesas', 'action' => 'salao'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									//echo $this->Html->link(__('Solicitações p/ Fazer', true), array('controller' => 'Setores', 'action' => 'monitorar'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									//echo $this->Html->link(__('Solicitações p/ Entrega', true), array('controller' => 'Setores', 'action' => 'prontos'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									//echo $this->Html->link(__('Movimentos', true), array('controller' => 'Fechamentos', 'action' => 'index'), array('class' => 'dropdown-item'));
									?>
								</li>

							</ul>
						</li>-->

						<li class="nav-item dropdown skin-midnight">
							<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle skin-midnight ">Cadastros</a>
							<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow skin-midnight skin-midnight-aux">
								<li>
									<?php
									echo $this->Html->link(__('Clientes', true), array('controller' => 'Clientes', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Produtos', true), array('controller' => 'Produtos', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Atendentes', true), array('controller' => 'Atendentes', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Entregadores', true), array('controller' => 'Entregadores', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<!--<li>
									<?php
									//echo $this->Html->link(__('Mesas / Cadastrar', true), array('controller' => 'Mesas', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>-->
								<li>
									<?php
									echo $this->Html->link(__('Setores', true), array('controller' => 'Setores', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Bairros', true), array('controller' => 'Bairros', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Cidades', true), array('controller' => 'Cidades', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Estados', true), array('controller' => 'Estados', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>
								<li>
									<?php
									echo $this->Html->link(__('Formas de Pagamento', true), array('controller' => 'Pagamentos', 'action' => 'add'), array('class' => 'dropdown-item'));
									?>
								</li>

							</ul>
						</li>
					</ul>

				</div>

				<!-- Right navbar links -->
				<ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
					<!-- Messages Dropdown Menu -->
					<!-- <li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="fas fa-comments"></i>
							<span class="badge badge-danger navbar-badge">3</span>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<a href="#" class="dropdown-item">
								
								<div class="media">
									<img src="../../dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
									<div class="media-body">
										<h3 class="dropdown-item-title">
											Brad Diesel
											<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
										</h3>
										<p class="text-sm">Call me whenever you can...</p>
										<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
									</div>
								</div>
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								
								<div class="media">
									<img src="../../dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
									<div class="media-body">
										<h3 class="dropdown-item-title">
											John Pierce
											<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
										</h3>
										<p class="text-sm">I got your message bro</p>
										<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
									</div>
								</div>
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								
								<div class="media">
									<img src="../../dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
									<div class="media-body">
										<h3 class="dropdown-item-title">
											Nora Silvester
											<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
										</h3>
										<p class="text-sm">The subject goes here</p>
										<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
									</div>
								</div>
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
						</div>
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#">
							<i class="far fa-bell"></i>
							<span class="badge badge-warning navbar-badge">15</span>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header">15 Notifications</span>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-envelope mr-2"></i> 4 new messages
								<span class="float-right text-muted text-sm">3 mins</span>
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-users mr-2"></i> 8 friend requests
								<span class="float-right text-muted text-sm">12 hours</span>
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-file mr-2"></i> 3 new reports
								<span class="float-right text-muted text-sm">2 days</span>
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i class="fas fa-th-large"></i></a>
					</li>-->
				</ul>
			</div>
		</nav>
		<!-- /.navbar -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"> 
							Menu -
							<?php
								if($this->request->params['action'] == 'index'){
									echo 'Listar';
								}else if($this->request->params['action'] == 'add'){
									echo 'Cadastrar';
								}else if($this->request->params['action'] == 'display'){
									echo 'Início';
								}else{
									echo $this->request->params['action'];
								}
								

								?> <small><?php
								if($this->request->params['controller'] != 'pages'){
									echo $this->request->params['controller'];
								}
								
								?></small></h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="#"><?php
								if($this->request->params['controller']== 'pages'){
									echo "Página";
								}else{
									echo $this->request->params['controller'];
								}
								
								?></a></li>
								
								<li class="breadcrumb-item active">
								<?php
								if($this->request->params['action'] == 'index'){
									echo 'Listar';
								}else if($this->request->params['action'] == 'add'){
									echo 'Cadastrar';
								}else if($this->request->params['action'] == 'display'){
									echo 'Inicial';
								}else{
									echo $this->request->params['action'];
								}
								

								?>
								</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<div class="content">
				<?php echo $this->Session->flash(); ?>
				<div class="container">
					<?php echo $this->fetch('content'); ?>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
			<div class="p-3">
				<h5>Title</h5>
				<p>Sidebar content</p>
			</div>
		</aside>
		<!-- /.control-sidebar -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<div class="float-right d-none d-sm-inline">
				Anything you want
			</div>
			<!-- Default to the left -->
			<strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
		</footer>
	</div>
	<!-- ./wrapper -->


</body>

</html>