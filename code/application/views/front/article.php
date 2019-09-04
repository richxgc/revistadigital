<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" id="main-content">
	<div class="row" style="margin-top:20px;">
		<div class="col-lg-7 col-md-7">
			<h1 style="margin:0px;"><?php echo $article->art_titulo; ?></h1>
			<blockquote class="article-abstract"><?php echo $article->art_abstracto; ?></blockquote>
		</div>
		<div class="col-lg-5 col-md-5 hidden-sm hidden-xs">
			<img src="<?php echo $article->art_portada; ?>" alt="<?php echo $article->art_titulo; ?>" class="article-cover"/>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="row">
				<div class="col-lg-12">
					<div class="authors">
						<?php if(sizeof($article->art_autores) > 1): ?>
						<h4><i class="fa fa-user"></i> Autores</h4>
						<?php else: ?>
						<h4><i class="fa fa-user"></i> Autor</h4>
						<?php endif; ?>
					</div>
					<?php foreach($article->art_autores as $author): ?>
					<div class="article-author">
						<div class="article-author-image">
						<?php if($author->usr_imagen == NULL || $author->usr_imagen == ''): ?>
							<img src="<?php echo base_url('images'); ?>/profile_thumbnail.jpg" alt="<?php echo $author->usr_nombre; ?>"/>
						<?php else: ?>
							<img src="<?php echo base_url().$author->usr_imagen; ?>" alt="<?php echo $author->usr_nombre; ?>"/>
						<?php endif; ?>
						</div>
						<div class="article-author-name">
							<a href="<?php echo get_user_url($author->usr_id); ?>" rel="author"><?php echo $author->usr_nombre; ?></a>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php if($front_uid != FALSE): ?>
			<?php if($article->art_pdf != NULL): ?>
			<div class="row" style="margin-top:20px;">
				<div class="col-lg-12"><?php $this->load->view('front/components/show_document'); ?></div>
			</div>
			<?php endif; ?>
			<div class="row" style="margin-top:20px;">
				<div class="col-lg-12"><?php $this->load->view('front/components/save_library'); ?></div>
			</div>
			<?php endif; ?>
			<div class="row" style="margin-top:20px;">
				<div class="col-lg-12"><?php $this->load->view('front/components/share'); ?></div>
			</div>
			<div class="row" style="margin-top:10px;">
				<div class="col-lg-12"><?php $this->load->view('front/components/related'); ?></div>
			</div>
			<div class="row hidden-sm hidden-xs" style="margin-top:20px;">
				<div class="col-lg-12"><?php $this->load->view('front/components/popular'); ?></div>
			</div>
		</div>
		<div class="col-lg-9 col-md-9">
			<p class="help-block"><i class="fa fa-calendar"></i> <?php echo get_simplified_date($article->art_fecha); ?></p>
			<div class="article-content"><?php echo htmlspecialchars_decode($article->art_contenido); ?></div>
		</div>
	</div>
</div>