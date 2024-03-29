<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" id="main-content">
	<div class="row" style="margin-top:20px;">
		<?php if($cover != NULL): ?>
		<?php $this->load->view('front/components/spectacular'); ?>
		<?php else: ?>
		<div class="col-lg-12"><h1 style="margin:0px;"><?php echo $category->cat_nombre; ?></h1></div>
		<?php endif; ?>
	</div>
	<div class="row" style="margin-top:10px; margin-bottom:10px;">
		<div class="col-lg-12 section">
			<div class="section-inner"><i class="fa fa-clock-o"></i><h2>Los artículos más recientes</h2></div>
		</div>
	</div>
	<div class="row" id="articles-content">
		<?php $this->load->view('front/components/articles'); ?>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<a href="#" id="load-more-content">Cargar más artículos</a>
		</div>
		<div class="col-lg-12">
			<img src="<?php echo base_url('images'); ?>/loading-alt.gif" alt="Cargando..." title="Cargando..." id="loading-content"/>
		</div>
		<div class="col-lg-12">
			<p id="nomore-content">No hay más contenido en la página.</p>
		</div>
	</div>
</div>
<script type="text/javascript">
	var category = '<?php echo $category->cat_id; ?>';
</script>