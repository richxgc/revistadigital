<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-top">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo base_url().index_page(); ?>/admin" class="navbar-brand">Revista ITMorelia</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar-top">
			<ul class="nav navbar-nav">
				<li <?php if($active=='home'){echo 'class="active"';} ?>>
					<a href="<?php echo base_url().index_page(); ?>/admin">Inicio</a>
				</li>
				<?php foreach($menu as $item): ?>
				<li <?php if($item->mad_nombre==$active){echo 'class="active"';} ?>>
					<a href="<?php echo base_url().index_page().'/admin/'.$item->mad_url; ?>"><?php echo $item->mad_menu; ?></a>
				</li>
				<?php endforeach; ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $user; ?> <i class="fa fa-bars"></i></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo base_url().index_page(); ?>/admin/logout"><i class="fa fa-power-off"></i> Cerrar sesión</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>