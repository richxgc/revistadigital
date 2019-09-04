<div class="col-lg-12" id="main-content" style="background-color:transparent;">
	<div class="row" style="margin-top:20px; margin-bottom:20px;" id="user-content">
		<form role="form" id="form-save-account" method="post" action="<?php echo base_url().index_page(); ?>/front/users/save_account" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="account-basic account-card">
							<?php if($user->usr_imagen != NULL && $user->usr_imagen != ''): ?>
							<img src="<?php echo base_url().$user->usr_imagen; ?>" alt="<?php echo $user->usr_nombre; ?>" class="account-image img-circle"/>
							<?php else: ?>
							<img src="<?php echo base_url('images'); ?>/profile.jpg" alt="<?php echo $user->usr_nombre; ?>" class="account-image img-circle"/>					
							<?php endif; ?>
							<div class="account-content">
								<h4>Imagen de perfil</h4>
								<input type="file" class="form-control" name="usr_imagen" id="usr_imagen" accept="image/*"/>
								<p class="help-block justify">Tu imagen de perfil se mostrará en tu curriculum y en los artículos donde aparezcas como autor.
								<br/>Solo puedes subir imagenes jpg, png y bmp. Tamaño máximo del archivo 1 MB.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-12">
						<div class="account-card">
							<h4>Nombre</h4>
							<div class="form-group">
								<label for="usr_nombre">Nombre completo</label>
								<input type="text" class="form-control" name="usr_nombre" id="usr_nombre" placeholder="Tu nombre completo" value="<?php echo $user->usr_nombre; ?>" required/>
							</div>
							<p class="help-block">Tienes que escribir tu nombre completo (requerido).</p>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-12">
						<div class="account-card">
							<h4>Cambiar contraseña</h4>
							<div class="form-group">
								<label for="usr_password">Nueva contraseña</label>
								<input type="password" class="form-control" name="usr_password" id="usr_password" placeholder="Tu nueva contraseña"/>
							</div>
							<div class="form-group">
								<label for="usr_rep_password">Repite la contraseña</label>
								<input type="password" class="form-control" name="usr_rep_password" id="usr_rep_password" placeholder="Repite la contraseña"/>
							</div>
							<p class="help-block justify">Si quieres cambiar tu contraseña anterior lo puedes hacer mediante estos campos. Si no deseas modificar tu contraseña deja esto en blanco.</p>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-12">
						<a href="<?php echo base_url().index_page(); ?>/mi_cuenta" class="btn btn-default btn-lg"><i class="fa fa-arrow-left"></i> Volver</a>
						<button type="submit" class="btn btn-info btn-lg"><i class="fa fa-upload"></i> Guardar</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" method="post" action="#" id="form-password-account">
				<header class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="md-title">Editar perfil</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="usr_password">Contraseña</label>
						<input type="password" class="form-control" name="usr_password" placeholder="Tu contraseña actual" required/>
					</div>
					<p class="help-block">
						Por motivos de seguridad, para poder editar tus datos actuales necesitamos que nos proporciones tu contraseña actual para validar tu identidad.
					</p>
				</section>
				<footer class="modal-footer">
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-check"></i> Validar</button>
				</footer>
			</form>
		</div>
	</div>
</div>