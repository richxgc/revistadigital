<div class="col-lg-3 col-md-3 hidden-sm hidden-xs affix" id="navbar">
	<div id="navbar-inner">
		<div class="row" style="margin-top:20px;">
			<div class="col-lg-12">
				<form role="search" method="get" action="<?php echo base_url().index_page(); ?>/buscar/" id="search-content">
					<div class="input-group">
						<span class="input-group-btn">
							<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
						</span>
						<?php if($this->input->get('s')): ?>
						<input type="text" class="form-control" name="s" placeholder="Buscar" value="<?php echo $this->input->get('s'); ?>"/>							
						<?php else: ?>
						<input type="text" class="form-control" name="s" placeholder="Buscar"/>
						<?php endif; ?>
					</div>
				</form>	
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<ul class="category-menu">
					<?php if($active == 'home'): ?>
					<li class="color-menu active" data-color-menu="#fed14f"><a href="<?php echo base_url(); ?>">Inicio</a></li>
					<?php else: ?>
					<li class="color-menu" data-color-menu="#fed14f"><a href="<?php echo base_url(); ?>">Inicio</a></li>
					<?php endif; ?>
					<?php foreach($categories as $category): ?>
					<?php if(isset($category->cat_hijas)): ?>
					<li class="color-menu active" data-color-menu="<?php echo $category->cat_color; ?>"><a href="<?php echo get_category_url($category->cat_url); ?>"><?php echo $category->cat_nombre; ?></a></li>
					<!--string helper!-->
					<?php echo list_children_categories($category->cat_hijas,$active); ?>
					<?php else: ?>
					<?php if($active == $category->cat_id): ?>
					<li class="color-menu active" data-color-menu="<?php echo $category->cat_color; ?>"><a href="<?php echo get_category_url($category->cat_url); ?>"><?php echo $category->cat_nombre; ?></a></li>
					<?php else: ?>
					<li class="color-menu" data-color-menu="<?php echo $category->cat_color; ?>"><a href="<?php echo get_category_url($category->cat_url); ?>"><?php echo $category->cat_nombre; ?></a></li>
					<?php endif; ?>
					<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="row" style="margin-bottom:30px;">
			<div class="col-lg-12">
				<!-- <h4 class="help-block">Enlaces</h4> -->
				<p>
					<a href="<?php echo base_url().index_page(); ?>/acerca_de">Acerca de la Revista</a> | 
					<a href="<?php echo base_url().index_page(); ?>/privacidad">Políticas de Privacidad</a><!--  | 
					<a href="http://itmorelia.edu.mx" target="_blank">Instituto Tecnológico de Morelia</a> -->
				</p>
				<!-- <h4 class="help-block">Redes Sociales</h4>
				<p>
					<a href="https://www.facebook.com/ITMoreliaOficial" target="_blank">Facebook</a> | 
					<a href="https://twitter.com/itmoficial" target="_blank">Twitter</a> | 
					<a href="http://www.youtube.com/user/ITMoreliaOficial" target="_blank">YouTube</a> |
					<a href="http://www.linkedin.com/company/instituto-tecnol-gico-de-morelia" target="_blank">LinkedIn</a> | 
					<a href="https://www.facebook.com/consejo.itm">Consejo Estudiantil</a> |
				</p> -->
			</div>
			<!-- <div class="col-lg-12">
				<h4 class="help-block">Contacto</h4>
				<p class="help-block">Email: <a href="mailto:escrita@itmorelia.edu.mx ">escrita@itmorelia.edu.mx</a></p>
				<p class="help-block">Telefono: +52 (443) 312-1570 con 10 líneas</p>
				<p class="help-block">Dirección: Avenida Tecnológico #1500, Col. Lomas de Santiaguito. Morelia, Mich.</p>
			</div> -->
			<div class="col-lg-12" id="logo-footer">
				<p class="help-block">© 2019 Future Corp. Todos los derechos reservados</p>
				<!-- <img src="<?php echo base_url('images'); ?>/logo_80x80.png" width="80px" height="80px" alt="ITM"/>
				<h4>Instituto<br/>Tecnológico<br/>de Morelia</h4> -->
			</div>
		</div>
	</div>
</div>