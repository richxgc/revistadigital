<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Categorias</h2>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/categorias"><i class="fa fa-tags"></i> Todas las categorias</a></li>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/categorias/nueva"><i class="fa fa-tag"></i> Nueva categoría</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<h2 class="margin-0">Nueva Categoría</h2>
			<form class="form" method="post" action="#" id="form-create-category">
				<div class="form-group">
					<label for="cat_nombre">Nombre</label>
					<input type="text" class="form-control" placeholder="Nombre de la categoría" name="cat_nombre" pattern="^[\w\W\s\-\_]{5,}$" required/>
					<p class="help-block">El nombre de la categoría y como aparecerá en los menús del sitio. Mínimo 5 caracteres.</p>
				</div>
				<div class="form-group">
					<label for="cat_url">Ruta</label>
					<input type="text" class="form-control" placeholder="nombre-de-categoria" name="cat_url" pattern="^[a-z0-9\-]{5,}$" required/>
					<p class="help-block">La dirección con la que se podra acceder a la categoría para los lectores. Mínimo 5 caracteres, solo caracteres alfanumericos y guiones medios.</p>
				</div>
				<div class="form-group">
					<label for="cat_color">Color</label>
					<input type="text" class="form-control" placeholder="#0099FF" name="cat_color" id="cat_color" pattern="^#[a-fA-Z0-9]{6}$" required/>
					<p class="help-block">El color de la categoría sirve para diferenciar en el diseño principal cada sección del sitio. Debe ser un color en hexadecimal.</p>
				</div>
				<div class="form-group">
					<label for="cat_super_id">Categoría Padre</label>
					<select class="form-control" name="cat_super_id">
						<option value="">Ninguna</option>
						<?php foreach($categories as $category): ?>
						<option value="<?php echo $category->cat_id; ?>"><?php echo $category->cat_nombre; ?></option>
						<?php endforeach; ?>
					</select>
					<p class="help-block">Puedes seleccionar una categoría padre para ésta categoría, de esta forma puedes crear jerarquias dentro del sitio. (Opcional).</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-upload"></i> Crear categoría</button>
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
				</div>
			</form>
		</section>
	</div>
</div>