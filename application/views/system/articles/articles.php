<div class="container" id="content">
	<div class="row">
		<div class="col-lg-3">
			<div class="row">
				<nav class="col-lg-12">
					<ul class="nav nav-pills nav-stacked box-1">
						<h2>Artículos</h2>
						<?php if($status == 'publicado'): ?>
						<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos"><i class="fa fa-list-alt"></i> Artículos publicados</a></li>
						<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/borrador"><i class="fa fa-archive"></i> Artículos en borrador</a></li>
						<?php elseif($status == 'borrador'): ?>
						<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos"><i class="fa fa-list-alt"></i> Artículos publicados</a></li>
						<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/articulos/borrador"><i class="fa fa-archive"></i> Artículos en borrador</a></li>
						<?php endif; ?>
						<li><a href="<?php echo base_url().index_page(); ?>/admin/articulos/nuevo"><i class="fa fa-pencil-square-o"></i> Nuevo artículo</a></li>
					</ul>
				</nav>
			</div>
			<div class="row" style="margin-top:20px;">
				<div class="col-lg-12">
					<form role="search" id="form-search-article" method="get" action="#">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-search"></i></span>
							<input type="text" class="form-control" name="art_buscar" id="art_buscar" placeholder="Buscar artículo" value="<?php echo $search; ?>"/>
						</div>
						<div class="input-group" style="margin-top:10px;">
							<span class="input-group-addon"><i class="fa fa-sort-amount-asc"></i></span>
							<select class="form-control" name="art_ordenar" id="art_ordenar">
								<option value="fecha" <?php if($order=='fecha'){ echo 'selected';} ?>>Ordenar por fecha</option>
								<option value="titulo" <?php if($order=='titulo'){ echo 'selected';} ?>>Ordenar por título</option>
							</select>
						</div>
						<div class="form-group" style="margin-top:10px;">
							<button type="submit" class="btn btn-default btn-block">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<section class="col-lg-9">
			<?php if(sizeof($articles) > 0): ?>
			<table class="table table-hover table-responsive" style="font-size:14px">
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
					<tr>
						<td><?php echo $article->art_id; ?></td>
						<td><?php echo $article->art_titulo; ?></td>
						<td><?php echo $article->art_fecha; ?></td>
						<td><?php echo $article->art_autores; ?></td>
						<td><?php echo $article->art_categorias; ?></td>
						<td><?php echo $article->adm_nombre; ?></td>
						<td><a href="<?php echo base_url().index_page().'/admin/articulos/editar/'.$article->art_id; ?>" class="only-icon" title="Editar <?php echo $article->art_titulo; ?>"><i class="fa fa-edit"></i></a></td>
						<td><a href="#" class="delete-article only-icon" data-delete-id="<?php echo $article->art_id; ?>" title="Eliminar <?php echo $article->art_titulo; ?>"><i class="fa fa-times"></i></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php if($total_articles > 10): ?>
			<aside id="pagination-wrapper">
				<?php 
					$pages = intval($total_articles / 10); 
					if($total_articles % 10 > 0){ $pages += 1;}
					$offset = 1;
				?>
				<ul class="pagination">
					<li><a href="<?php echo base_url().index_page().'/admin/articulos/'.$status.'/1/'.$order.'/'.$search; ?>" title="Ir al principio">&laquo;</a></li>
					<?php for($i=0; $i<$pages; $i++): ?>
					<li><a href="<?php echo base_url().index_page().'/admin/articulos/'.$status.'/'.$offset.'/'.$order.'/'.$search; ?>"><?php echo ($i + 1); ?></a></li>
					<?php $offset += 1; ?>
					<?php endfor; ?>
					<li><a href="<?php echo base_url().index_page().'/admin/articulos/'.$status.'/'.($offset-1).'/'.$order.'/'.$search; ?>" title="Ir al final">&raquo;</a></li>
				</ul>
			</aside>
			<?php endif; ?>
			<?php else: ?>
			<h3 class="margin-0">No se ha encontrado ningún artículo.</h3>
			<p>El contenido que estás buscando no existe o no hay ningún artículo publicado aún.</p>
			<?php endif; ?>
		</section>
	</div>
</div>
<div class="modal fade" id="modal-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" method="post" action="#" id="form-delete-post">
				<header class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="md-title">Eliminar Artículo</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="adm_password">Contraseña</label>
						<input type="password" class="form-control" name="adm_password" placeholder="tu contraseña del sistema" required/>
					</div>
					<p class="help-block">
						Estas ha punto de eliminar un artículo del sistema para siempre. Ten en cuenta que puede llevar un tiempo en
						desaparecer de los buscadores en Internet.
						<br/>
						Por motivos de seguridad es necesario que nos proporciones tu contraseña del sistema.
					</p>
				</section>
				<footer class="modal-footer">
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-danger" id="btn-delete"><i class="fa fa-download"></i> Eliminar</button>
				</footer>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	var status = "<?php echo $status; ?>";
</script>