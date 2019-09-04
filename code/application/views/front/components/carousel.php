<?php if(isset($cover->sliders)): ?>
<div class="carousel slide" id="home-carousel" data-ride="carousel">
	<ol class="carousel-indicators">
		<?php $i=0; foreach($cover->sliders as $slide): ?>
		<?php if($i==0): ?>
		<li data-target="#home-carousel" data-slide-to="<?php echo $i; ?>" class="active"></li>
		<?php else: ?>
		<li data-target="#home-carousel" data-slide-to="<?php echo $i; ?>"></li>
		<?php endif; ?>
		<?php $i++; endforeach; ?>
	</ol>
	<div class="carousel-inner">
		<?php $i=0; foreach($cover->sliders as $cover): ?>
		<?php if($i==0): ?>
		<div class="item active">
		<?php else: ?>
		<div class="item">
		<?php endif; ?>
			<a href="<?php echo $cover->art_url; ?>"><img src="<?php echo $cover->art_portada; ?>" alt="<?php echo $cover->art_titulo; ?>" style="height=400px !important;"/></a>
			<div class="carousel-caption">
				<a href="<?php echo $cover->art_url; ?>"><h3><?php echo $cover->art_titulo; ?></h3></a>
				<p><?php echo $cover->art_abstracto; ?></p>
			</div>
		</div>
		<?php $i++; endforeach; ?>
	</div>
	<a class="carousel-control left" href="#home-carousel" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
	<a class="carousel-control right" href="#home-carousel" data-slide="next"><i class="fa fa-chevron-right"></i></a>
</div>
<?php endif; ?>