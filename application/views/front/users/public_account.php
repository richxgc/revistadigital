<div class="col-lg-12" id="main-content" style="background-color:transparent;">
	<div class="row" style="margin-top:20px; margin-bottom:20px;" id="user-content">
		<div class="col-lg-4 col-md-4 col-sm-5">
			<div class="row">
				<div class="col-lg-12">
					<div class="account-basic account-card">
						<?php if($user->usr_imagen != NULL && $user->usr_imagen != ''): ?>
						<img src="<?php echo base_url().$user->usr_imagen; ?>" alt="<?php echo $user->usr_nombre; ?>" class="account-image img-circle"/>
						<?php else: ?>
						<img src="<?php echo base_url('images'); ?>/profile.jpg" alt="<?php echo $user->usr_nombre; ?>" class="account-image img-circle"/>					
						<?php endif; ?>
						<div class="account-content">
							<h2><?php echo $user->usr_nombre; ?></h2>
							<h3><?php echo $user->usr_email; ?></h3>
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="margin-top:30px;">
				<div class="col-lg-12">
					<div class="account-articles account-card">
						<h2>Artículos</h2>
						<?php if($user->usr_articulos != NULL): ?>
						<ul>
							<?php foreach($user->usr_articulos as $article): ?>
							<li>
								<img src="<?php echo $article->art_portada; ?>" alt="<?php echo $article->art_titulo; ?>"/>
								<div>
									<a href="<?php echo get_article_url($article->art_url); ?>"><?php echo $article->art_titulo; ?></a>
									<p class="help-block"><?php echo get_simplified_date($article->art_fecha); ?></p>
								</div>
							</li>
							<?php endforeach; ?>
						</ul>
						<?php else: ?>
						<p class="help-block justify">No tienes ningún artículo publicado actualmente. Si has realizado un trabajo de investigación
						y deseas publicarlo en la revista, contacta con el jefe de departamento de tu carrera.</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-7">
			<div class="account-curriculum account-card">
				<h2 style="margin-bottom:10px;">Curriculum</h2>
				<?php if($user->cur_id != NULL && ($user->cur_url != NULL || $user->cur_email || $user->cur_abstract != NULL)): ?>
					<?php if($user->cur_url != NULL): ?>
					<p><b><i class="fa fa-globe"></i> Sitio web:</b> <a href="<?php echo $user->cur_url; ?>" target="_blank"><?php echo $user->cur_url; ?></a></p>
					<?php endif; ?>
					<?php if($user->cur_email != NULL): ?>
					<p><b><i class="fa fa-envelope"></i> Correo de contacto:</b> <a href="mailto:<?php echo $user->cur_email; ?>"><?php echo $user->cur_email; ?></a></p>
					<?php endif; ?>
					<?php if($user->cur_abstract != NULL): ?>
					<p class="justify"><b><i class="fa fa-inbox"></i> Extracto:</b> <?php echo $user->cur_abstract; ?></p>
					<?php endif; ?>
					<?php if($user->cur_pdf != NULL): ?>
					<p><b><i class="fa fa-download"></i> Archivo: </b> <a href="<?php echo base_url().$user->cur_pdf; ?>" download="<?php echo $user->usr_nombre; ?> - Curriculum">Descargar en formato PDF</a></p>
					<?php endif; ?>
				<?php else: ?>
				<p class="help-block">Este usuario aún no ha escrito nada sobre él.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>