<div class="col-lg-12" id="main-content" style="background-color:transparent;">
	<div class="row" style="margin-top:20px; margin-bottom:20px;">
		<div class="col-lg-12">
			<div class="account-card">
				<h3>Marcadores</h3>
				<?php if($bookmarks != NULL): ?>
				<div class="row">
					<?php foreach($bookmarks as $bookmark): ?>
					<div class="col-lg-3 col-md-3 home-spotlight">
						<header>
							<a href="#" class="remove-bookmark" data-bookmark-id="<?php echo $bookmark->bus_id; ?>" title="Eliminar marcador"><i class="fa fa-bookmark"></i></a>
							<img src="<?php echo $bookmark->art_portada; ?>" alt="<?php echo $bookmark->art_titulo; ?>" class="image-responsive" style="max-width:100%;"/>
							<h3 style="margin-top:0px;"><a href="<?php echo get_article_url($bookmark->art_url); ?>"><?php echo $bookmark->art_titulo; ?></a></h3>
							<p class="help-block"><?php echo get_simplified_date($bookmark->art_fecha); ?></p>
							<p class="help-block">Guardaro el: <?php echo get_simplified_date($bookmark->bus_fecha); ?></p>
						</header>
						<p><?php echo strip_text($bookmark->art_abstracto,250); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
				<?php if($total_bookmarks > 10): ?>
				<aside id="pagination-wrapper">
					<?php 
						$pages = intval($total_bookmarks / 10); 
						if($total_bookmarks % 10 > 0){ $pages += 1;}
						$offset = 1;
					?>
					<ul class="pagination">
						<li><a href="<?php echo base_url().index_page(); ?>/marcadores" title="Ir al principio">&laquo;</a></li>
						<?php for($i=0; $i<$pages; $i++): ?>
						<?php if(($this->uri->segment(2) == $offset) || ($this->uri->segment(2) == NULL && $offset == 1)): ?>
						<li class="active"><a href="<?php echo base_url().index_page().'/marcadores/'.$offset; ?>"><?php echo ($i + 1); ?></a></li>
						<?php else: ?>
						<li><a href="<?php echo base_url().index_page().'/marcadores/'.$offset; ?>"><?php echo ($i + 1); ?></a></li>
						<?php endif; ?>
						<?php $offset += 1; ?>
						<?php endfor; ?>
						<li><a href="<?php echo base_url().index_page().'/marcadores/'.($offset-1); ?>" title="Ir al final">&raquo;</a></li>
					</ul>
				</aside>
				<?php endif; ?>
				<?php else: ?>
				<p class="help-block">No hay contenido disponible.</p>
				<?php endif; ?>
			</div>
		</div>	
	</div>
</div>