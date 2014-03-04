<div class="col-lg-12">
	<div class="spectacular" style="border-color:<?php echo $category->cat_color; ?>">
		<div class="left-spectacular">
			<h1><?php echo $category->cat_nombre; ?></h1>
			<h2><?php echo $cover->art_titulo; ?></h2>
			<p><?php echo strip_text($cover->art_abstracto,235); ?></p>
			<a href="<?php echo $cover->art_url; ?>" class="btn btn-rmore btn-sm"><i class="fa fa-angle-double-right"></i> Seguir Leyendo</a>
		</div>
		<div class="right-spectacular hidden-xs">
			<a href="<?php echo $cover->art_url; ?>">
				<img src="<?php echo $cover->art_portada; ?>" alt="<?php echo $cover->art_titulo; ?>"/>
			</a>
		</div>
	</div>
</div>