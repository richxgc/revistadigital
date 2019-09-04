<!DOCTYPE>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
	<title><?php echo $title; ?></title>
	<link rel="shorcut icon" type="image/x-icon" href="<?php echo base_url('images'); ?>/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets'); ?>/font-awesome.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('stylesheets/system'); ?>/install.css"/>
</head>
<body>
	<div class="alert" id="alert-overlay">
		<p id="alert-text"></p>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-12" id="main-box">
				<div class="row">
					<header class="col-lg-12">
						<h1>Gestor de contenido, Revista Científica del ITM</h1>
						<h2>Proceso de instalación</h2>	
					</header>
					<section class="col-lg-12">
						<p>
							<i class="fa fa-info-circle"></i>
							Bienvenido al gestor de contenido para revistas de difusión científica, si estas viendo esta pantalla 
		        			aún no has configurado tu sistema. Por favor llena los datos que se muestran a continuación para comenzar
		        			a gestionar tu contenido.
		    			</p>
					</section>
					<section class="col-lg-12">
						<form class="form-horizontal" role="form" method="post" action="#" id="form-install" autocomplete="off">
							<div class="form-group">
								<label for="adm_nombre" class="control-label col-lg-3">Nombre del usuario</label>
								<div class="col-lg-4">
									<input type="text" class="form-control input-lg" name="adm_nombre" id="adm_nombre" placeholder="ej. Administrador Revista" pattern="^[\w\W\_\-\.]{5,150}$" title="Nombre de usuario: longitud mínima de 5 caracteres" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="adm_email" class="control-label col-lg-3">Correo electrónico</label>
								<div class="col-lg-4">
									<input type="email" class="form-control input-lg" name="adm_email" id="adm_email" placeholder="ej. administrador@itmorelia.edu.mx" title="Correo electrónico: un email valido" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="adm_password" class="control-label col-lg-3">Contraseña</label>
								<div class="col-lg-4">
									<input type="password" class="form-control input-lg" name="adm_password" id="adm_password" placeholder="contraseña" pattern="^[\w\-\.]{5,}$" title="Contraseña: longitud mínima de 5 caracteres" required/>
								</div>
							</div>
							<div class="form-group">
								<label for="adm_rep_password" class="control-label col-lg-3">Repite la contraseña</label>
								<div class="col-lg-4">
									<input type="password" class="form-control input-lg" name="adm_rep_password" id="adm_rep_password" placeholder="repite la contraseña" title="Repite la contraseña anterior" required/>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-3">
									<button type="submit" class="btn btn-success" id="button-submit"><i class="fa fa-cogs"></i> Instalar sistema</button>
								</div>
								<div class="col-lg-4">
									<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" class="center-block" id="loading-img" style="display:none"/>
								</div>
							</div>
						</form>
					</section>
					<footer class="col-lg-12">
						<div class="pull-left">
							<i class="fa fa-bookmark"></i>
						</div>
						<div class="pull-left">
							<a href="http://itmorelia.edu.mx" target="_blank">Instituto Tecnológico de Morelia</a>
						</div>
						<div class="pull-left">
							<a href="http://creativecommons.org/licenses/by-nd/2.5/mx/" target="_blank">Bajo Licencia Creative Commons</a>
						</div>
						<div class="pull-left">
							<a href="http://richy.mx" target="_blank">Desarrollado por Ricardo Guerrero Corona</a>
						</div>
					</footer>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var path = "<?php echo base_url().index_page(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('scripts/system'); ?>/system.js"></script>
	<script type="text/javascript" src="<?php echo base_url('scripts/system'); ?>/install.js"></script>
</body>
</html>