<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Usuarios</h2>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/usuarios"><i class="fa fa-users"></i> Usuarios del sistema</a></li>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/usuarios/nuevo"><i class="fa fa-user"></i> Nuevo usuario</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<h2 class="margin-0">Nuevo Usuario</h2>
			<form class="form" method="post" action="#" id="form-create-user" autocomplete="off">
				<div class="form-group">
					<label for="adm_nombre">Nombre de usuario</label>
					<input type="text" class="form-control" name="adm_nombre" id="adm_nombre" placeholder="Nombre de usuario" pattern="^[\w\-\_\.]{5,50}$" required/>
					<p class="help-block">Es el nombre o nick con el que se tiene acceso al sistema. Debe tener minímo 5 caracteres y solo pueden ser alfanumericos, puntos y guiones.</p>
				</div>
				<div class="form-group">
					<label for="adm_email">Correo electrónico</label>
					<input type="email" class="form-control" name="adm_email" id="adm_email" placeholder="Correo electrónico" required/>
					<p class="help-block">Se requiere una dirección de correo electrónico para poder enviar notificaciones al usuario. El usuario podrá modificar la dirección posteriormente.</p>
				</div>
				<div class="form-group">
					<label for="adm_password">Contraseña</label>
					<input type="password" class="form-control" name="adm_password" id="adm_password" placeholder="Contraseña" pattern="^[\w\-\.]{5,}$" required/>
					<p class="help-block">La contraseña para iniciar sesión, minímo 5 caracteres. El usuario podrá modificar la contraseña posteriormente.</p>
				</div>
				<div class="form-group">
					<label for="adm_rep_password">Repite la contraseña</label>
					<input type="password" class="form-control" name="adm_rep_password" id="adm_rep_password" placeholder="Repite la contraseña" required/>
					<p class="help-block">Repite la contraseña anterior. Es necesario para asegurarnos que has introducido la contraseña que deseas.</p>
				</div>
				<div class="form-group">
					<label for="modules[]" class="block">Permisos</label>
					<div class="checkbox col-lg-3">
						<label>
							<input type="checkbox" name="modules[]" value="stats"/> Ver Estadísticas
						</label>
					</div>
					<div class="checkbox col-lg-3">
						<label>
							<input type="checkbox" name="modules[]" value="publish"/> Publicar Artículos
						</label>
					</div>
					<div class="checkbox col-lg-3">
						<label>
							<input type="checkbox" name="modules[]" value="users"/> Modificar Usuarios
						</label>
					</div>						
					<div class="checkbox col-lg-3">
						<label>
							<input type="checkbox" name="modules[]" value="categories"/> Modificar Categorias
						</label>
					</div>
					<p class="help-block">Estos son los modulos a los que el usuario tendra acceso. Solo pueden ser modificados por alguien que tenga acceso al modulo de usuarios.</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-upload"></i> Crear usuario</button>
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
				</div>
			</form>
		</section>
	</div>
</div>