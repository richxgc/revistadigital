<!DOCTYPE>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title><?php echo $title; ?></title>
	<link rel="shorcut icon" type="image/x-icon" href="<?php echo base_url('images'); ?>/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets/system'); ?>/admin.css"/>
	<?php if(isset($styles)): ?>
	<?php foreach($styles as $style): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>"/>
	<?php endforeach; ?>
	<?php endif; ?>
</head>
<body>
	<div class="alert" id="alert-overlay">
		<p id="alert-text"></p>
	</div>