<div class="col-lg-12" id="main-content" style="background-color:transparent;">
	<div class="row" style="margin-top:20px; margin-bottom:20px;" id="user-content">
		<form role="form" id="form-save-curriculum" method="post" action="<?php echo base_url().index_page(); ?>/front/users/save_curriculum" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
			<input type="hidden" name="cur_id" value="<?php echo $user->cur_id; ?>"/>
			<div class="col-lg-6 col-lg-offset-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="account-card">
							<h4>Información de contacto</h4>
							<div class="form-group">
								<label for="cur_url">Sitio web</label>
								<input type="url" class="form-control" name="cur_url" id="cur_url" placeholder="http://mipagina.com" value="<?php echo $user->cur_url; ?>"/>
								<p class="help-block">Puedes colocar tu página web aquí o cualquier perfil de red social para que los demás puedan conocer más de tu trabajo.</p>
							</div>
							<div class="form-group">
								<label for="cur_email">Email de contacto</label>
								<input type="email" class="form-control" name="cur_email" id="cur_email" placeholder="micorreo@empresarial.com" value="<?php echo $user->cur_email; ?>" />
								<p class="help-block">Alternativamente a tu correo principal del ITM puedes hacer publico tu correo de contacto.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-12">
						<div class="account-card">
							<h4>Curriculum</h4>
							<div class="form-group">
								<label for="cur_abstract">Extracto</label>
								<textarea class="form-control" name="cur_abstract" id="cur_abstract" rows="5" placeholder="Escribe un resumen de tu curriculum"><?php echo $user->cur_abstract; ?></textarea>
							</div>
							<div class="form-group">
								<label for="cur_pdf">Archivo completo (pdf)</label>
								<input type="file" class="form-control" name="cur_pdf" id="cur_pdf" accept="application/pdf"/>
								<p class="help-block">Si quieres que los usuarios vean tu curriculum completo de forma más ordenada puedes subirlo en formato PDF aquí.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="margin-top:20px;">
					<div class="col-lg-12">
						<a href="<?php echo base_url().index_page(); ?>/mi_cuenta" class="btn btn-default btn-lg"><i class="fa fa-arrow-left"></i> Volver</a>
						<button type="submit" class="btn btn-info btn-lg"><i class="fa fa-upload"></i> Guardar</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="modal-edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" method="post" action="#" id="form-password-account">
				<header class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="md-title">Editar perfil</h4>
				</header>
				<section class="modal-body">
					<div class="form-group">
						<label for="usr_password">Contraseña</label>
						<input type="password" class="form-control" name="usr_password" placeholder="Tu contraseña actual" required/>
					</div>
					<p class="help-block">
						Por motivos de seguridad, para poder editar tus datos actuales necesitamos que nos proporciones tu contraseña actual para validar tu identidad.
					</p>
				</section>
				<footer class="modal-footer">
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-check"></i> Validar</button>
				</footer>
			</form>
		</div>
	</div>
</div>