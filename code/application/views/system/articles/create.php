<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-1">
			<ul class="nav nav-pills nav-stacked box-1 nav-fixed">
				<h2>Artículos</h2>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos" title="Artículos publicados"><i class="fa fa-list-alt"></i></a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/borrador" title="Artículos en borrador"><i class="fa fa-archive"></i></a></li>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos/nuevo" title="Nuevo artículo"><i class="fa fa-pencil-square-o"></i></a></li>
			</ul>
		</nav>
		<section class="col-lg-11">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="margin-0">Publicar Artículo</h2>
				</div>
			</div>
			<form class="form-admin" method="post" action="#" class="" id="form-create-article">
				<div class="row">
					<div class="col-lg-8">
						<div class="form-group">
							<label for="art_titulo">Título</label>
							<input type="text" class="form-control" placeholder="Título del artículo" name="art_titulo" id="art_titulo" pattern="^[\w\W\s\-\_]{5,150}$" required/>
							<p class="help-block">El título que mostrará el artículo en la revista, tiene que ser único. Mínimo 5 caracteres y máximo 150.</p>
						</div>
						<div class="form-group">
							<label for="art_url">Ruta</label>
							<div class="input-group">
								<span class="input-group-addon"><?php echo base_url().index_page().'/articulo/'; ?></span>
								<input type="text" class="form-control" placeholder="titulo-del-articulo" name="art_url" id="art_url" pattern="^[a-zA-Z0-9\-]{5,150}$" required/>
							</div>
							<p class="help-block">La dirección con la los lectores podrán acceder al artículo. Mínimo 5 caracteres y máximo 150, solo caracteres alfanumericos y guiones medios.</p>
						</div>
						<div class="form-group">
							<label for="art_portada">Portada</label>
							<div class="input-group">
								<span class="input-group-btn">
									<button type="button" class="btn btn-default btn-filesystem" data-upload-method="input" data-upload-point="#art_portada" title="Insertar imagen">Insertar imagen</button>
								</span>
								<input type="url" class="form-control" name="art_portada" id="art_portada" required/>
							</div>
							<p class="help-block">La imagen que aparecerá en la portada de la revista. Requerido.</p>
						</div>
						<div class="form-group">
							<label for="art_abstracto">Extracto</label>
							<textarea class="form-control" placeholder="Breve resumen del artículo" name="art_abstracto" id="art_abstracto" rows="5" required></textarea>
							<p class="help-block">Un extracto del artículo sin formato y no más de 500 caractes. Sirve para mostrar un breve resumen en las vistas de la revista.</p>
						</div>
						<div class="form-group">
							<label for="art_contenido">Contenido</label>
							<textarea class="form-control" placeholder="Escribe aquí..." name="art_contenido" id="art_contenido" rows="26" required></textarea>
							<p class="help-block">El contenido principal del artículo, este puede ser un resumen o síntes del original. Si desea adjuntar el archivo original completo puede hacerlo desde el panel derecho "Documento".</p>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="row">
							<div class="col-lg-12" style="margin-top:25px;">
								<div class="panel panel-default">
									<header class="panel-heading"><i class="fa fa-rss"></i> <label for="art_fecha">Publicar</label> <img src="<?php echo base_url('images'); ?>/loading.gif" atl="Cargando..." id="loading-img" class="pull-right" width="25" height="25" style="display:none;"/></header>
									<div class="panel-body">
										<div class="input-group">
											<input type="date" class="form-control" placeholder="21/01/2014" name="art_fecha" id="art_fecha" title="Fecha de publicación" required/>
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
										<div class="input-group" style="margin:10px 0;">
											<select class="form-control" name="art_estado" id="art_estado" title="Estado de la publicación" required>
												<option value="borrador">Borrador</option>
												<option value="publicado">Publicar</option>
											</select>
											<span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
										</div>
										<button type="submit" class="btn btn-success btn-block" id="btn-submit"><i class="fa fa-upload"></i> Guardar</button>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<header class="panel-heading"><i class="fa fa-users"></i> <label for="art_buscar_autores">Autores</label> <img src="<?php echo base_url('images'); ?>/loading-alt.gif" atl="Cargando..." id="loading-authors" class="pull-right" width="25" height="25" style="display:none;"/></header>
									<div class="panel-body" id="panel-author">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Buscar un autor" id="art_buscar_autores"/>
											<span class="input-group-addon"><i class="fa fa-search"></i></span>
										</div>
										<ul class="list-group" id="search-author-list"></ul>
										<ul id="authors-list"></ul>
									</div>
									<footer class="panel-footer">
										<p class="help-block">Los autores del artículo tienen que estar registrados en la revista.</p>
									</footer>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<header class="panel-heading"><i class="fa fa-folder-open"></i> <label for="art_agregar_categorias">Categorías</label></header>
									<div class="panel-body" id="panel-categories">
										<div class="input-group">
											<select class="form-control" id="art_agregar_categorias">
												<option value="">Seleccionar categoría</option>
												<?php foreach($categories as $category): ?>
												<option value="<?php echo $category->cat_id; ?>"><?php echo $category->cat_nombre; ?></option>
												<?php endforeach; ?>
											</select>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default only-icon" id="add-category" title="Agregar categoría"><i class="fa fa-plus-circle"></i></button>
											</span>
										</div>
										<ul id="categories-list"></ul>
									</div>
									<footer class="panel-footer">
										<p class="help-block">Las categorías agrupan un conjunto de artículos en un mismo sitio.</p>
									</footer>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<header class="panel-heading"><i class="fa fa-tags"></i> <label form="art_etiquetas">Etiquetas</label></header>
									<div class="panel-body">
										<input type="text" class="form-control" placeholder="Etiquetas del artículo" name="art_etiquetas" id="art_etiquetas"/>
									</div>
									<footer class="panel-footer">
										<p class="help-block">Escriba las etiquetas separadas por comas y sin espacios.</p>
									</footer>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="panel panel-default">
									<header class="panel-heading"><i class="fa fa-file-text"></i> <label form="art_pdf">Documento</label></header>
									<div class="panel-body">
										<div class="input-group">
											<input type="url" class="form-control" name="art_pdf" id="art_pdf"/>
											<span class="input-group-btn">
												<button type="button" class="btn btn-default btn-filesystem only-icon" data-upload-method="input" data-upload-point="#art_pdf" title="Insertar imagen"><i class="fa fa-plus-circle"></i></button>
											</span>
										</div>
									</div>
									<footer class="panel-footer">
										<p class="help-block">Un documento adjunto al artículo. Solo archivos PDF.</p>
									</footer>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
	</div>
</div>
<script type="text/javascript">
	var base_domain = '<?php echo base_url(); ?>';
</script>