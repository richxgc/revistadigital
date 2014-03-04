<div class="col-lg-8 col-lg-offset-2" id="main-content" style="margin-top:50px;">
	<h1>Activación de la cuenta</h1>
	<p><?php echo $response->message; ?></p>
	<?php if($response->succeed == FALSE): ?>	
	<p class="help-block">Si no puedes activar correctamente tu cuenta o iniciar sesión contacta al administrador del sitio.</p>
	<?php endif; ?>
	<p>
		<a href="<?php echo base_url().index_page(); ?>/login" class="btn btn-info"><i class="fa fa-lock"></i> Ir a iniciar sesión</a>
	</p>
</div>