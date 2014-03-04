<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" id="main-content">
	<div class="row">
		<div class="col-lg-12">
			<h1>Estas buscando: "<?php echo $search; ?>"</h1>
		</div>
	</div>
	<div class="row" style="margin-top:10px; margin-bottom:10px;">
		<div class="col-lg-12 section">
			<div class="section-inner"><i class="fa fa-search"></i><h2>Resultados de la busqueda</h2></div>
		</div>
	</div>
	<div class="row" id="articles-content">
		<?php $this->load->view('front/components/articles'); ?>
	</div>
</div>