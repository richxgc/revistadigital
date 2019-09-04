<div class="container" id="content">
	<div class="row">
		<div class="col-lg-3">
			<div class="row">
				<nav class="col-lg-12">
					<ul class="nav nav-pills nav-stacked box-1">
						<h2>Estadísticas</h2>
						<li class="active"><a href="<?php echo base_url().index_page(); ?>/admin/estadisticas/articulos"><i class="fa fa-bar-chart-o"></i> Estadísticas de Artículos</a></li>
						<li><a href="<?php echo base_url().index_page(); ?>/admin/estadisticas/usuarios"><i class="fa fa-bar-chart-o"></i> Estadísticas de Usuarios</a></li>
					</ul>
				</nav>
			</div>
		</div>
		<section class="col-lg-9">
			<div class="row">
				<div class="col-lg-6">
					<div id="chart-category-articles"></div>
				</div>
				<div class="col-lg-6">
					<div id="chart-most-read"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div id="chart-monthly-publications"></div>
				</div>
			</div>
		</section>
	</div>
</div>