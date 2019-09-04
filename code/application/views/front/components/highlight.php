<?php if(isset($cover->highlights)): ?>
<?php foreach($cover->highlights as $spot): ?>
<?php $category = $spot->art_categorias[sizeof($spot->art_categorias)-1]; ?>
<div class="col-lg-4 col-md-4 col-sm-4 home-spotlight">
	<header>
		<img src="<?php echo $spot->art_portada; ?>" alt="<?php echo $spot->art_titulo; ?>" style="max-width:100%;"/>
		<div>
			<a href="<?php echo $category->cat_url; ?>"><span class="category-block" style="background-color:<?php echo $category->cat_color; ?>;"></span> <?php echo $category->cat_nombre; ?></a>
		</div>
		<h3><a href="<?php echo $spot->art_url; ?>"><?php echo $spot->art_titulo; ?></a></h3>
		<p class="help-block"><?php echo get_simplified_date($spot->art_fecha); ?></p>
	</header>
	<p><?php echo strip_text($spot->art_abstracto,270); ?></p>
</div>
<?php endforeach; ?>
<?php endif; ?>