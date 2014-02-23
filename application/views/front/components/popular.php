<div class="widget widget-popular">
	<header><h4><i class="fa fa-bar-chart-o"></i> Popular</h4></header>
	<ul>
		<?php foreach($popular as $article): ?>
		<li>
			<img src="<?php echo $article->art_portada; ?>" alt="<?php echo $article->art_titulo; ?>" title="<?php echo $article->art_titulo; ?>"/>
			<div>
				<a href="<?php echo get_article_url($article->art_url); ?>"><?php echo $article->art_titulo; ?></a>
				<p class="help-block"><?php echo get_simplified_date($article->art_fecha); ?></p>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>