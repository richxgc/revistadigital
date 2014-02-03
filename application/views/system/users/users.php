<div class="container" id="content">
	<div class="row">
		<nav class="col-lg-3">
			<ul class="nav nav-pills nav-stacked box-1">
				<h2>Usuarios</h2>
				<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/usuarios"><i class="fa fa-users"></i> Usuarios del sistema</a></li>
				<li><a href="<?php echo base_url().index_page(); ?>/admin/usuarios/nuevo"><i class="fa fa-user"></i> Nuevo usuario</a></li>
			</ul>
		</nav>
		<section class="col-lg-9">
			<?php if(sizeof($users) > 0): ?>
			<table class="table table-hover table-responsive">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Correo electrónico</th>
						<th>Privilegios</th>
						<th>Editar</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($users as $us): ?>
					<tr>
						<td><?php echo $us->adm_id; ?></td>
						<td><?php echo $us->adm_nombre; ?></td>
						<td><?php echo $us->adm_email; ?></td>
						<td><?php echo $us->adm_modulos; ?></td>
						<td><a href="<?php echo base_url().index_page().'/admin/usuarios/editar/'.$us->adm_id; ?>" class="only-icon" title="Editar <?php echo $us->adm_nombre; ?>"><i class="fa fa-edit"></i></a></td>
						<?php if($us->adm_tipo == 'super'): ?>
						<td></td>
						<?php else: ?>
						<td><a href="#" class="delete-user only-icon" data-delete-id="<?php echo $us->adm_id; ?>" title="Eliminar <?php echo $us->adm_nombre; ?>"><i class="fa fa-times"></i></a></td>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif; ?>
			<?php if($total_users > 10): ?>
			<aside id="pagination-wrapper">
				<?php 
					$pages = intval($total_users / 10); 
					if($total_users % 10 > 0){ $pages += 1;}
					$offset = 1;
				?>
				<ul class="pagination">
					<li><a href="<?php echo base_url().index_page(); ?>/admin/usuarios" title="Ir al principio">&laquo;</a></li>
					<?php for($i=0; $i<$pages; $i++): ?>
					<li><a href="<?php echo base_url().index_page().'/admin/usuarios/'.$offset; ?>"><?php echo ($i + 1); ?></a></li>
					<?php $offset += 1; ?>
					<?php endfor; ?>
					<li><a href="<?php echo base_url().index_page().'/admin/usuarios/'.($offset-1); ?>" title="Ir al final">&raquo;</a></li>
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
					<h4 class="modal-title" id="md-title">Eliminar Usuario</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="adm_password">Contraseña</label>
						<input type="password" class="form-control" name="adm_password" placeholder="tu contraseña del sistema" required/>
					</div>
					<p class="help-block">
						Estas ha punto de eliminar un usuario del sistema para siempre. Si el usuario tiene artículos realacionados
						a él estos serán marcados como si ningún administrador los hubiera publicado.
						<br/>
						Por motivos de seguridad es necesario que nos proporciones tu contraseña del sistema. 
						Los usuarios solo se pueden eliminar si se tienen los permisos necesarios.
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