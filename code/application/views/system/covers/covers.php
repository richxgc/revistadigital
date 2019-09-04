<div class="container" id="content">
	<div class="row">
		<div class="col-lg-3">
			<div class="row">
				<nav class="col-lg-12">
					<ul class="nav nav-pills nav-stacked box-1">
						<h2>Portadas</h2>
						<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/portadas"><i class="fa fa-picture-o"></i> Todas las portadas</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<section class="col-lg-9">
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Editar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($covers as $cover): ?>
					<?php if($cover->por_estado == 'creado'): ?>
					<tr>
						<td><?php echo $cover->por_id; ?></td>
						<td>Portada <?php echo $cover->por_nombre; ?></td>
						<td><a href="<?php echo base_url().index_page().'/admin/portadas/editar/'.$cover->por_id; ?>" class="only-icon"><i class="fa fa-edit"></i></a></td>
					</tr>
					<?php else: ?>
					<tr class="warning">
						<td><i class="fa fa-exclamation-circle"></i> No creada</td>
						<td>Portada <?php echo $cover->cat_nombre; ?></td>
						<td><a href="#" class="only-icon create-cover" data-cat-id="<?php echo $cover->cat_id; ?>"><i class="fa fa-edit"></i></a></td>
					</tr>
					<?php endif; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php if($total_covers > 10): ?>
			<aside id="pagination-wrapper">
				<?php 
					$pages = intval($total_covers / 10); 
					if($total_covers % 10 > 0){ $pages += 1;}
					$offset = 1;
				?>
				<ul class="pagination">
					<li><a href="<?php echo base_url().index_page(); ?>/admin/portadas" title="Ir al principio">&laquo;</a></li>
					<?php for($i=0; $i<$pages; $i++): ?>
					<li><a href="<?php echo base_url().index_page().'/admin/portadas/'.$offset; ?>"><?php echo ($i + 1); ?></a></li>
					<?php $offset += 1; ?>
					<?php endfor; ?>
					<li><a href="<?php echo base_url().index_page().'/admin/portadas/'.($offset-1); ?>" title="Ir al final">&raquo;</a></li>
				</ul>
			</aside>
			<?php endif; ?>
		</section>
	</div>
</div>