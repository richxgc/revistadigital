<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title><?php echo $title; ?></title>
	<link rel="shorcut icon" type="image/x-icon" href="<?php echo base_url('images'); ?>/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/front/revista.css"/>
	<?php if(isset($meta_tags)): ?>
	<?php foreach($meta_tags as $meta): ?>
	<?php echo $meta; ?>
	<?php endforeach; ?>
	<?php endif; ?>
</head>
<body>
<!--navbar-->
<nav class="navbar navbar-inverse navbar-revista navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-top">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo base_url(); ?>" class="navbar-brand"><img src="<?php echo base_url('images'); ?>/logo_30x30.png" width="30px" height="30px" alt="ITM"/> Instituto Tecnológico de Morelia // Revista</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar-top">
			<ul class="nav navbar-nav navbar-right">
				<li><a href="http://itmorelia.edu.mx" target="_blank">ITMORELIA.EDU.MX</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">CUENTA <i class="fa fa-bars"></i></a>
					<ul class="dropdown-menu">
						<li><a href="#"><i class="fa fa-lock"></i> Iniciar sesión</a></li>
						<li><a href="#"><i class="fa fa-user"></i> Registrarme</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<div class="container full-height" id="content"><div class="row full-height">