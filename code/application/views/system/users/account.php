<div class="container" id="content">
	<div class="row">
		<div class="col-lg-6">
			<h2 class="margin-0">Datos de la cuenta</h2>
			<form role="form" method="post" action="#" class="form-admin" id="form-edit-account" autocomplete="off">
				<div class="form-group">
					<label for="adm_nombre">Nombre completo</label>
					<input type="text" class="form-control" name="adm_nombre" id="adm_nombre" placeholder="Tu nombre completo" value="<?php echo $account->adm_nombre; ?>" pattern="^[\w\W\-\_\.]{5,150}$" required/>
					<p class="help-block">Aquí tienes que colocar tu nombre completo para que puedas ser identificado más facilmente en el sistema. Debe tener minímo 5 caracteres.</p>
				</div>
				<div class="form-group">
					<label for="adm_email">Correo electrónico</label>
					<input type="email" class="form-control" name="adm_email" id="adm_email" placeholder="Tu dirección de correo electrónico" value="<?php echo $account->adm_email; ?>" required/>
					<p class="help-block">La dirección de correo para acceder al sistema y enviarte notificaciones.</p>
				</div>
				<div class="form-group">
					<label for="adm_password">Nueva contraseña</label>
					<input type="password" class="form-control" name="adm_password" id="adm_password" placeholder="Nueva contraseña" pattern="^[\w\-\.]{5,}$"/>
					<p class="help-block">La contraseña para iniciar sesión, minímo 5 caracteres. Si no deseas modificar la contraseña actual deja éste y el campo siguiente en blanco.</p>
				</div>
				<div class="form-group">
					<label for="adm_rep_password">Repite la nueva contraseña</label>
					<input type="password" class="form-control" name="adm_rep_password" id="adm_rep_password" placeholder="Repite la contraseña"/>
					<p class="help-block">Repite la contraseña anterior. Es necesario para asegurarnos que has introducido la contraseña que deseas.</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success"><i class="fa fa-upload"></i> Guardar mis datos</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" method="post" action="#" id="form-account-pass">
				<header class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="md-title">Guardar datos</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="adm_password_origin">Contraseña</label>
						<input type="password" class="form-control" name="adm_password_origin" placeholder="tu contraseña del sistema" required/>
					</div>
					<p class="help-block">
						Por motivos de seguridad para guardar tus datos es necesatio que nos proporciones tu contraseña del sistema.
					</p>
				</section>
				<footer class="modal-footer">
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-success" id="btn-update"><i class="fa fa-upload"></i> Guardar</button>
				</footer>
			</form>
		</div>
	</div>
</div>