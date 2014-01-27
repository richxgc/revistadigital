<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Artículos</h2>
				<?php if($status == 'publicado'): ?>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos"><i class="fa fa-list-alt"></i> Artículos publicados</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/10/0/borrador"><i class="fa fa-archive"></i> Artículos en borrador</a></li>
				<?php elseif($status == 'borrador'): ?>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos"><i class="fa fa-list-alt"></i> Artículos publicados</a></li>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos/10/0/borrador"><i class="fa fa-archive"></i> Artículos en borrador</a></li>
				<?php endif; ?>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/nuevo"><i class="fa fa-pencil-square-o"></i> Nuevo artículo</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<?php if(sizeof($articles) > 0): ?>
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>ID</th>
						<th>Título</th>
						<th>Fecha</th>
						<th>Autores</th>
						<th>Categorias</th>
						<th>Publicó</th>
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($articles as $article): ?>
					<tr id="art-<?php echo $article->art_id; ?>">
						<td><?php echo $article->art_id; ?></td>
						<td><?php echo $article->art_titulo; ?></td>
						<td><?php echo $article->art_fecha; ?></td>
						<td><?php echo $article->art_autores; ?></td>
						<td><?php echo $article->art_categorias; ?></td>
						<td><?php echo $article->adm_nombre; ?></td>
						<td><a href="<?php echo base_url().index_page().'/admin/articulos/editar/'.$article->art_id; ?>" class="only-icon" title="Editar <?php echo $article->art_titulo; ?>"><i class="fa fa-edit"></i></a></td>
						<td><a href="#" class="delete-article only-icon" id="da-<?php echo $article->art_id; ?>" title="Eliminar <?php echo $article->art_titulo; ?>"><i class="fa fa-times"></i></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php else: ?>
			<h3 class="margin-0">No hay ningún artículo publicado.</h3>
			<p>Dirigete a la pestaña del menú izquierdo "Nueva artículo" para comenzar a crear uno, o revisa la bandaja de borradores.</p>
			<?php endif; ?>
		</section>
	</div>
</div>