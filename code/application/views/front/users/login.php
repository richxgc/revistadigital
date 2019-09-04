<div class="col-lg-8 col-lg-offset-2" id="main-content" style="margin-top:50px;">
	<h1>Iniciar Sesión</h1>
	<form role="form" method="post" action="#" id="form-login">
		<div class="form-group">
			<label for="usr_email">Correo electrónico</label>
			<input type="email" class="form-control" name="usr_email" id="usr_email" placeholder="micorreo@itmorelia.edu.mx" required/>
		</div>
		<div class="form-group">
			<label for="usr_password">Contraseña</label>
			<input type="password" class="form-control" name="usr_password" id="usr_password" placeholder="tu contraseña" required/>
		</div>
		<div class="form-group">
			<a href="<?php echo base_url().index_page(); ?>/registrar" class="btn btn-default"><i class="fa fa-sign-in"></i> Registrarme</a>
			<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-lock"></i> Iniciar sesión</button>
			<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
		</div>
		<p class="help-block"><i class="fa fa-info-circle"></i> Si aún no tienes una cuenta da clic en el botón "Registrarme".</p>
	</form>
</div>
<div class="modal fade" id="modal-info">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="md-title">Confirmación de la cuenta</h4>
			</header>
			<section class="modal-body">
				<p id="modal-info-content">Si no has verificado tu cuenta previamente no podrás acceder al sistema. 
				Revisa la bandeja de entrada del correo con el que te registraste para activar la cuenta. Si no te ha
				llegado ningún correo puedes dar clic en el enlace de abajo para que volvamos a enviar tu código de verificación.</p>
				<p><a href="<?php echo base_url().index_page(); ?>/reenviar_verificacion">Reenviar código de verificación a mi correo.</a></p>
			</section>
			<footer class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
			</footer>
		</div>
	</div>
</div>