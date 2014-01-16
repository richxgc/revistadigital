<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Categorías</h2>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/categorias"><i class="fa fa-tags"></i> Todas las categorías</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/categorias/nueva"><i class="fa fa-tag"></i> Nueva categoría</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<?php if(sizeof($categories) > 0): ?>
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>ID</th>
						<th>Articulos</th>
						<th>Nombre</th>
						<th>Ruta</th>
						<th>Color</th>
						<th>Padre</th>
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($categories as $category): ?>
					<tr id="cat-<?php echo $category->cat_id; ?>">
						<td><?php echo $category->cat_id; ?></td>
						<td><?php echo $category->cat_articulos; ?></td>
						<td><?php echo $category->cat_nombre; ?></td>
						<td><?php echo $category->cat_url; ?></td>
						<td><div class="color" style="background-color:<?php echo $category->cat_color; ?>"></div></td>
						<td><?php echo $category->cat_super; ?></td>
						<td><a href="<?php echo base_url().index_page().'/admin/categorias/editar/'.$category->cat_id; ?>" title="Editar <?php echo $category->cat_nombre; ?>"><i class="fa fa-edit"></i></a></td>
						<td><a href="#" class="delete-category" id="dc-<?php echo $category->cat_id; ?>" title="Eliminar <?php echo $category->cat_nombre; ?>"><i class="fa fa-times"></i></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php else: ?>
			<h3 class="margin-0">No hay ninguna categoría actualmente.</h3>
			<p>Dirigete a la pestaña del menú izquierdo "Nueva categoria" para comenzar a crear una.</p>
			<?php endif; ?>
			<?php if($total_categories > 10): ?>
			<aside id="pagination-wrapper">
				<?php 
					$pages = intval($total_categories / 10); 
					if($total_categories % 10 > 0){ $pages += 1;}
					$limit = 10; $offset = 0;
				?>
				<ul class="pagination">
					<li><a href="<?php echo base_url().index_page(); ?>/admin/categorias" title="Ir al principio">&laquo;</a></li>
					<?php for($i=0; $i<$pages; $i++): ?>
					<li><a href="<?php echo base_url().index_page().'/admin/categorias/'.$limit.'/'.$offset; ?>"><?php echo ($i + 1); ?></a></li>
					<?php $offset += 10; ?>
					<?php endfor; ?>
					<li><a href="<?php echo base_url().index_page().'/admin/categorias/'.$limit.'/'.($offset-10); ?>" title="Ir al final">&raquo;</a></li>
				</ul>
			</aside>
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
					<h4 class="modal-title" id="md-title">Eliminar Categoria</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="adm_password">Contraseña</label>
						<input type="password" class="form-control" name="adm_password" placeholder="tu contraseña del sistema" required/>
					</div>
					<p class="help-block">
						Estas ha punto de eliminar una categoría del sistema para siempre, ten en cuenta que solo la podrás 
						eliminar si la categoría no tiene ningún artículo relacionado. 
						<br/>
						Por motivos de seguridad es necesario que nos proporciones tu contraseña del sistema. 
						Las categorias solo pueden ser eliminadas si se tienen los permisos necesarios.
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