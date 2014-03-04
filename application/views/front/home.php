<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" id="main-content">
	<div class="row" style="margin-top:20px;">
		<div class="col-lg-9 col-md-9 hidden-xs">
			<?php $this->load->view('front/components/carousel'); ?>
		</div>
		<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">
			<?php $this->load->view('front/components/popular'); ?>
		</div>
	</div>
	<div class="row" style="margin-top:20px;">
		<?php $this->load->view('front/components/highlight'); ?>
	</div>
	<div class="row" style="margin-top:10px; margin-bottom:10px;">
		<div class="col-lg-12 section">
			<div class="section-inner"><i class="fa fa-clock-o"></i><h1>Los artículos más recientes</h1></div>
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
	var category = null;
</script>