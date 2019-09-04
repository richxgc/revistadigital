<div class="container" id="content">
	<div class="row">
		<div class="col-lg-3">
			<div class="row">
				<nav class="col-lg-12">
					<ul class="nav nav-pills nav-stacked box-1">
						<h2>Portadas</h2>
						<li><a href="<?php echo base_url().index_page(); ?>/admin/portadas"><i class="fa fa-picture-o"></i> Todas las portadas</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<section class="col-lg-9">
			<h2 class="margin-0">Editar Portada <?php echo $cover->por_nombre; ?></h2>
			<form class="form-admin" method="post" action="#" id="form-edit-main-cover">
				<input type="hidden" name="por_id" value="<?php echo $cover->por_id; ?>"/>
				<div class="form-group">
					<label for="m-slider">Slider Principal</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control search_article" id="m-slider" data-type-slide="slide" placeholder="Buscar un artículo"/>
					</div>
					<ul class="list-group search-articles-list"></ul>
					<div class="slide-container" id="main-slider"></div>
					<p class="help-block">Busca el nombre del artículo en la barra de busqueda superior, 
					luego has clic sobre él para poder agregarlo en los slides actuales. Solo puedes agregar artículos que esten publicados (no en borrador).
					También puedes ordenar los elementos agarrandolos y soltandolos en la posición que desees.</p>
				</div>
				<div class="form-group">
					<label for="a-highlights">Artículos Destacados</label>
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
						<input type="text" class="form-control search_article" id="a-highlights" data-type-slide="highlight" placeholder="Buscar un artículo"/>
					</div>
					<ul class="list-group search-articles-list"></ul>
					<div class="slide-container" id="highlights-articles"></div>
					<p class="help-block">Busca el nombre del artículo en la barra de busqueda superior, 
					luego has clic sobre él para poder agregarlo en los slides actuales. Solo puedes agregar artículos que esten publicados (no en borrador).
					También puedes ordenar los elementos agarrandolos y soltandolos en la posición que desees.</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-upload"></i> Guardar portada</button>
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
				</div>
			</form>
		</section>
	</div>
</div>
<script>
	var data = '<?php echo $cover->por_datos; ?>';
</script>