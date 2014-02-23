<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Usuarios</h2>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/usuarios"><i class="fa fa-users"></i> Usuarios del sistema</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/usuarios/nuevo"><i class="fa fa-user"></i> Nuevo usuario</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<h2 class="margin-0">Editar Usuario - <?php echo $adm_user->adm_nombre; ?></h2>
			<form class="form-admin" method="post" action="#" id="form-edit-user" autocomplete="off">
				<input type="hidden" name="adm_id" value="<?php echo $adm_user->adm_id; ?>"/>
				<input type="hidden" name="adm_tipo" value="<?php echo $adm_user->adm_tipo; ?>"/>
				<div class="form-group">
					<label for="adm_nombre">Nombre del usuario</label>
					<input type="text" class="form-control" name="adm_nombre" id="adm_nombre" placeholder="Nombre de usuario" value="<?php echo $adm_user->adm_nombre; ?>" pattern="^[\w\W\-\_\.]{5,150}$" required/>
					<p class="help-block">Es el nombre con el que se podra identificar a un usuario dentro del sistema. Debe tener minímo 5 caracteres.</p>
				</div>
				<div class="form-group">
					<label for="adm_email">Correo electrónico</label>
					<input type="email" class="form-control" name="adm_email" id="adm_email" placeholder="Correo electrónico" value="<?php echo $adm_user->adm_email; ?>" required/>
					<p class="help-block">La dirección de correo para acceder al sistema y enviar notificaciones. El usuario podrá modificar la dirección posteriormente.</p>
				</div>
				<?php if($adm_user->adm_tipo == 'super'): ?>
				<input type="hidden" name="modules" value="all"/>
				<?php else: ?>
				<div class="form-group">
					<label for="modules[]" class="block">Permisos</label>
					<div class="checkbox col-lg-2">
						<label>
							<input type="checkbox" name="modules[]" value="stats" <?php foreach($adm_user->adm_modulos as $modulo){if($modulo->mad_nombre=='stats'){echo 'checked';}} ?>/> Ver Estadísticas
						</label>
					</div>
					<div class="checkbox col-lg-2">
						<label>
							<input type="checkbox" name="modules[]" value="covers" <?php foreach($adm_user->adm_modulos as $modulo){if($modulo->mad_nombre=='covers'){echo 'checked';}} ?>/> Editar Portadas
						</label>
					</div>
					<div class="checkbox col-lg-2">
						<label>
							<input type="checkbox" name="modules[]" value="articles" id="publish_articles" <?php foreach($adm_user->adm_modulos as $modulo){if($modulo->mad_nombre=='articles'){echo 'checked';}} ?>/> Publicar Artículos
						</label>
					</div>
					<div class="checkbox col-lg-2">
						<label>
							<input type="checkbox" name="modules[]" value="categories" <?php foreach($adm_user->adm_modulos as $modulo){if($modulo->mad_nombre=='categories'){echo 'checked';}} ?>/> Modificar Categorias
						</label>
					</div>
					<div class="checkbox col-lg-2">
						<label>
							<input type="checkbox" name="modules[]" value="users" <?php foreach($adm_user->adm_modulos as $modulo){if($modulo->mad_nombre=='users'){echo 'checked';}} ?>/> Modificar Usuarios
						</label>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<p class="help-block">Estos son los modulos a los que el usuario tendra acceso. Solo pueden ser modificados por alguien que tenga acceso al modulo de usuarios.</p>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<div class="form-group" id="permissions_articles" style="display:none;">
					<label for="art_permisos_publicar">Permisos de publicación</label>
					<div class="input-group">
						<select class="form-control" id="art_permisos_publicar">
							<option value="">Selecciona una categoría</option>
							<?php foreach($categories as $category): ?>
							<option value="<?php echo $category->cat_id; ?>"><?php echo $category->cat_nombre; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="input-group-btn">
							<button type="button" class="btn btn-default only-icon" id="add-cat-p" title="Agregar categoría"><i class="fa fa-plus-circle"></i></button>
						</span>
					</div>
					<ul id="cat-p-list"></ul>
					<p class="help-block">Seleccione una categoría de la lista superior y de clic en el botón "Agregar" para añadir permisos de publicar en esa categoría al usuario. Solo será efectivo si tiene permisos de publicar artículos.</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-upload"></i> Guardar usuario</button>
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
				</div>
			</form>
		</section>
	</div>
</div>
<script type="text/javascript">
	var permissions = '<?php echo $adm_user->adm_tipo; ?>';
</script>