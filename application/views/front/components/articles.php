<?php if(isset($articles) && sizeof($articles) > 0): ?>
<?php foreach($articles as $article): ?>
<?php $category = $article->art_categorias[sizeof($article->art_categorias)-1]; ?>
	<div class="col-lg-12 article-ls">
		<div class="inner">
			<div class="image-article">
				<img src="<?php echo $article->art_portada; ?>" alt="<?php echo $article->art_titulo; ?>"/>
				<div class="category-article">
					<a href="<?php echo get_category_url($category->cat_url); ?>"><span class="category-block" style="background-color:<?php echo $category->cat_color; ?>;"></span> <?php echo $category->cat_nombre; ?></a>
				</div>
			</div>
			<div class="content-article">
				<a href="<?php echo get_article_url($article->art_url); ?>"><h3><?php echo $article->art_titulo; ?></h3></a>
				<p class="author-date">Por: 
				<?php foreach($article->art_autores as $author): ?>
					<a href="<?php echo get_user_url($author->usr_id); ?>"><?php echo $author->usr_nombre; ?></a>, 
				<?php endforeach; ?>
				<?php echo get_simplified_date($article->art_fecha); ?></p>
				<p class="abstract-article"><?php echo $article->art_abstracto; ?></p>
			</div>
		</div>
	</div>
<?php endforeach; ?>
<?php endif; ?>