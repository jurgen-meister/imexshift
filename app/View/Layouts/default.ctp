<?php $cakeDescription = __d('cake_dev', 'Imexport srl '); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription; ?>-
		<?php echo $title_for_layout; ?>
	</title>
	<!--  meta info -->
	<?php
	  echo $this->Html->meta(array("name"=>"viewport",
		"content"=>"width=device-width,  initial-scale=1.0"));
	/*  echo $this->Html->meta(array("name"=>"description",
		"content"=>"this is the description"));
	  echo $this->Html->meta(array("name"=>"author",
		"content"=>"TheHappyDeveloper.com - @happyDeveloper"))*/
	?>
	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- styles -->
  	<?php 
		//echo $this->Html->meta('icon');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive.min');
		echo $this->Html->css('unicorn.main');
		echo $this->Html->css('unicorn.grey',
				null,
				array('class' => 'skin-color'));
		echo $this->Html->css('datepicker');//just for this project I gonna put the calendar here
	?>
	<!-- icons -->
	<?php
		echo  $this->Html->meta('icon',$this->webroot.'img/favicon.ico');
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/apple-touch-icon.png'));
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/apple-touch-icon.png',  'sizes'=>'72x72'));
		echo $this->Html->meta(array('rel' => 'apple-touch-icon',
		  'href'=>$this->webroot.'img/apple-touch-icon.png',  'sizes'=>'114x114'));
	?>	
</head>
<body>
	
	<div id="header">
		<h1>IMEXPORT SRL</h1>
	</div>
	
	<!--
	<div id="search">
		<input type="text" placeholder="Search here..." /><button type="submit" class="tip-right" title="Search"><i class="icon-search icon-white"></i></button>
	</div>
	-->
	<div id="user-nav" class="navbar navbar-inverse">
		<?php if($logged_in):?>
			<ul class="nav btn-group">
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-user"></i> <span class="text"><?php echo ' Usuario: '.$current_user['login'];?></span></a></li>
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-briefcase"></i> <span class="text"><?php echo ' Rol: '.$this->Session->read('Role.name');?></span></a></li>
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-time"></i> <span class="text"><?php echo ' Gestión: '.$this->Session->read('Period.year');?></span></a></li>
				<!--<li class="btn btn-inverse dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Gestion 2013</span> <span class="label label-important">5</span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a class="sAdd" title="" href="#">Gestion 2012</a></li>
						<li><a class="sInbox" title="" href="#">Gestion 2011</a></li>
						<li><a class="sOutbox" title="" href="#">Gestion 2010</a></li>
						<li><a class="sOutbox" title="" href="#">Más gestiones</a></li>
					</ul>
				</li>-->
				<li class="btn btn-inverse">
					<?php echo $this->Html->link(
						'<i class="icon icon-share-alt"></i><span class="text">&nbspSalir</span>', 
						array('controller'=>'admUsers', 'action'=>'logout'),
						array('escape' => FALSE));?>
				</li>
			</ul>
		
		<?php endif;?>
	</div>
	<!-- MENU -->
	<div id="sidebar">
		<a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
		<?php //echo $this->Session->read('Menu');?>
		
		
		
		<ul>
				<li><a href="index.html"><i class="icon icon-wrench"></i> <span>Administracion</span></a></li>
				<li class="submenu active open">
					<a href="#"><i class="icon  icon-list-alt"></i> <span>Inventario</span> <span class="label">11</span></a>
					<ul>
						<li><a href="form-validation.html">Entradas</a></li>
						<li><a href="form-validation.html">Entradas de Compras</a></li>
						<li><a href="form-validation.html">Salidas</a></li>
						<li><a href="form-validation.html">Salidas de Ventas</a></li>
						<li><a href="form-validation.html">Transferencia Almacenes</a></li>
						<li><a href="form-validation.html">Kardex</a></li>
						<li><a href="form-validation.html">Items</a></li>
						<li><a href="form-validation.html">Almacenes</a></li>
						<li><a href="form-validation.html">Proveedores</a></li>
						<li><a href="form-validation.html">Tipos Movimiento</a></li>
						<li><a href="form-validation.html">Marcas</a></li>
					</ul>
				</li>
				<li class="submenu">
					<a href="#"><i class="icon icon-shopping-cart"></i> <span>Compras</span> <span class="label">4</span></a>
					<ul>
						<li><a href="invoice.html">Invoice</a></li>
						<li><a href="chat.html">Support chat</a></li>
						<li><a href="calendar.html">Calendar</a></li>
						<li><a href="gallery.html">Gallery</a></li>
					</ul>
				</li>
				<li class="submenu">
					<a href="#"><i class="icon icon-tags"></i> <span>Ventas</span> <span class="label">4</span></a>
					<ul>
						<li><a href="invoice.html">Invoice</a></li>
						<li><a href="chat.html">Support chat</a></li>
						<li><a href="calendar.html">Calendar</a></li>
						<li><a href="gallery.html">Gallery</a></li>
					</ul>
				</li>
			</ul>
		
		
		
	</div>
	<!-- MENU ENDS HERE -->
<!--	<div id="style-switcher">
		<i class="icon-arrow-left icon-white"></i>
		<span>Style:</span>
		<a href="#grey" style="background-color: #555555;border-color: #aaaaaa;"></a>
		<a href="#blue" style="background-color: #2D2F57;"></a>
		<a href="#red" style="background-color: #673232;"></a>
	</div>-->
	<div id="content">
		<!--
		<div id="content-header">
			<h1>Inventario</h1>
			<div class="btn-group">
				<a class="btn btn-large tip-bottom" title="Manage Files"><i class="icon-file"></i></a>
				<a class="btn btn-large tip-bottom" title="Manage Users"><i class="icon-user"></i></a>
				<a class="btn btn-large tip-bottom" title="Manage Comments"><i class="icon-comment"></i><span class="label label-important">5</span></a>
				<a class="btn btn-large tip-bottom" title="Manage Orders"><i class="icon-shopping-cart"></i></a>
			</div>
		</div>
		-->
		<!--
		<div id="breadcrumb">
			<a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
			<a href="#" class="current">arghhhhh</a>
		</div>
		-->
		<!-- CONTENT STARTS HERE -->
		<div class="container-fluid">
			<div class="row-fluid">
				<?php echo $this->fetch('content'); ?>			
			</div>
			<div class="row-fluid">
				<div id="footer" class="span12">
					<!--2013 &copy IMERPORT SRL-->
				</div>
			</div>
		</div>
		<!-- CONTENT ENDS HERE -->
	</div>
	<!-- page specific scripts -->
	<?php //echo $this->Html->script('jquery'); ?>
	
<!--
	<script src="js/excanvas.min.js"></script>
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery.ui.custom.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.flot.min.js"></script>
	<script src="js/jquery.flot.resize.min.js"></script>
	<script src="js/jquery.peity.min.js"></script>
	<script src="js/fullcalendar.min.js"></script>
	<script src="js/unicorn.js"></script>
	<script src="js/unicorn.dashboard.js"></script>
-->
	
	<?php //echo $this->Html->script('excanvas.min'); //plotter, para hacer graficas x,y?>
	<?php //echo $this->Html->script('jquery.min'); ?>
	<?php //echo $this->Html->script('jquery.ui.custom'); ?>
	<?php //echo $this->Html->script('bootstrap.min'); ?>
	<?php //echo $this->Html->script('jquery.flot.min'); //charts and graphs?>
	<?php //echo $this->Html->script('jquery.flot.resize.min');  //charts and graphs ?>
	<?php //echo $this->Html->script('jquery.peity.min'); //convert to mini charts?>
	<?php //echo $this->Html->script('fullcalendar.min'); //big calendar?>

	<?php //echo $this->Html->script('unicorn'); ?>
	<?php //echo $this->Html->script('unicorn.dashboard'); ?>
	
	

	<?php 
	echo $this->Html->script('jquery.min');
	echo $this->Html->script('jquery.ui.custom');
	echo $this->Html->script('bootstrap.min');
	echo $this->Html->script('unicorn');
	echo $this->Html->script('bootstrap-datepicker'); //just for this project I gonna put the calendar here
	?>
	<?php echo $this->fetch('script'); //maybe not necessary?>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>