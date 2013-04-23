<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>
		<?php echo __('Imexport Internacional S.A.'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<?php echo $this->Html->css('bootstrap.min'); ?>
	<style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
	</style>
	<?php echo $this->Html->css('bootstrap-responsive.min'); ?>

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Le fav and touch icons -->
	<!--
	<link rel="shortcut icon" href="/ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
	-->
	<?php
	echo $this->fetch('meta');
	echo $this->fetch('css');
	?>
</head>

<body>

	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="#"><?php echo __('Imexport S.A.'); ?></a>
				<div class="nav-collapse">
					<ul class="nav">
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container-fluid">
		
			<?php if($logged_in):?>
			<div style="text-align: right;">
			<span style="font-weight:bold">Usuario: </span><?php echo $current_user['login'].' | ';?>
			<span style="font-weight:bold">Rol: </span><?php echo $this->Session->read('Role.name').' | ';?>
			<span style="font-weight:bold">Gestion: </span><?php echo $this->Session->read('Period.year');?>
			<?php echo ' | '.$this->Html->link('Salir', array('controller'=>'admUsers', 'action'=>'logout'));?>
			</div>
			<?php endif;?>
		
		<!--<h1>Bootstrap starter template</h1>-->
		<?php if($logged_in):?>
		<div class="row-fluid">
		
		<div class="span3">
			<div class="well sidebar-nav" style="padding: 8px 0; margin-top:40px;">
			<ul class="nav nav-list">

				<?php echo $this->Session->read('Menu');?>

			</ul>
			</div>
		</div>
		<?php endif;?>
		<?php echo $this->Session->flash(); ?>
		<span style="font-weight:bold; color: red; font-size: 18px">
			<?php echo $this->Session->flash('auth'); //messages auth component?>		
		</span>
		
		<?php echo $this->fetch('content'); ?>
		</div>
	</div> <!-- /container -->

	<!-- Le javascript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster --> 
	<!--<script src="/admin/js/jquery.js"></script>--> <!-- Last version 1.9. not very compatible with many scripts-->
	<script src="/imexport/js/jquery-1.8.3.js"></script>
	<?php echo $this->Html->script('bootstrap.min'); ?>
	<?php echo $this->fetch('script'); ?>

        
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
