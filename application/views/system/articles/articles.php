<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Artículos</h2>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos"><i class="fa fa-list-alt"></i> Artículos publicados</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/10/0/borrador"><i class="fa fa-archive"></i> Artículos en borrador</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/nuevo"><i class="fa fa-pencil-square-o"></i> Nuevo artículo</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<?php if(sizeof($articles) > 0): ?>
			<?php var_dump($articles); ?>
			<?php else: ?>
			<h3 class="margin-0">No hay ningún artículo publicado.</h3>
			<p>Dirigete a la pestaña del menú izquierdo "Nueva artículo" para comenzar a crear uno, o revisa la bandaja de borradores.</p>
			<?php endif; ?>
		</section>
	</div>
</div>