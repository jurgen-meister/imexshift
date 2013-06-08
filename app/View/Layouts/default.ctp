<?php $cakeDescription = __d('cake_dev', 'IMEXPORT SRL'); ?>
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
		echo $this->Html->css('jquery.gritter');
	?>
</head>
<body>
	
	<div id="header">
		<h1>IMEXPORT SRL</h1>
	</div>

	<div id="user-nav" class="navbar navbar-inverse">
		<?php if($logged_in):?>
			<ul class="nav btn-group">
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-user"></i> <span class="text"><?php echo ' Usuario: '.$this->session->read('User.username');?></span></a></li>
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-briefcase"></i> <span class="text"><?php echo ' Rol: '.$this->Session->read('Role.name');?></span></a></li>
				<li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-time"></i> <span class="text"><?php echo ' Gestión: '.$this->Session->read('Period.name');?></span></a></li>
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
		<?php echo $this->Session->read('Menu');?>
		
	</div>
	<div id="content">
		<!-- CONTENT STARTS HERE -->
		<div class="container-fluid">
			
			<div class="row-fluid">
				<?php 
				//////////////////////// START - Message not authorized, when there is no permission///////////
				//Is used authError from AppController 'authError'=>'Auth Error', but I don't use the message only the string not empty
				if($this->Session->flash('auth') <> ''){?>
					<div class="alert alert-error">
							<button class="close" data-dismiss="alert">×</button>
							<strong>NO TIENE PERMISO!</strong> Comuniquese con su administrador
					</div>
				<?php }
				echo $this->Session->flash();  //to show setFlash messages
				///////////////////////// END - Message not authorized, when there is no permission////////////
				?>	
				
				<!-- ////////////////////////// START - VIEWS CONTENT(CORE) //////////////////-->
				<?php echo $this->fetch('content'); ?>			
				<!-- ////////////////////////// END - VIEWS CONTENT(CORE) //////////////////-->
			</div>
			<div class="row-fluid">
				<div id="footer" class="span12">
					<!--2013 &copy IMERPORT SRL-->
					<?php echo $this->element('sql_dump'); ?>
				</div>
			</div>
		</div>
		<!-- CONTENT ENDS HERE -->
	</div>
	<!-- page specific scripts -->
	
	<?php 
	echo $this->Html->script('jquery.min');
	echo $this->Html->script('jquery.ui.custom');
	echo $this->Html->script('bootstrap.min');
	echo $this->Html->script('unicorn');
	echo $this->Html->script('bootstrap-datepicker'); //just for this project I gonna put the calendar here
	echo $this->Html->script('jquery.gritter.min'); //growl-like notifications
	?>
	<?php echo $this->fetch('script'); //maybe not necessary?>
	
</body>
</html>