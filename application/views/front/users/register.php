<div class="col-lg-12" id="main-content" style="background-color:transparent;">
	<h1>Registro de usuario</h1>
	<div class="row">
		<div class="col-lg-4 col-md-5">
			<p class="help-block" style="text-align:justify;">
				<i class="fa fa-comment"></i> Bienvenido a la revista de difusión científica del Instituto Tecnológico de Morelia. 
				En este sitio encontrarás artículos sobre las investigaciones que se realizan en el instituto. Para registrarse es necesario 
				que seas o hayas sido alumno o docente del Tecnológico de Morelia, de ser así tendrás las siguientes características exclusivas:
			</p>
			<p class="help-block" style="text-align:justify;">
				<i class="fa fa-unlock"></i> <strong>Acceso a documentos extra.</strong> Accede a todos los documentos de los artículos de investigación, 
				con estos podrás conocer más a fondo los resultados de las investigaciones hechas en el instituto.
			</p>
			<p class="help-block" style="text-align:justify;">
				<i class="fa fa-bookmark"></i> <strong>Guardar marcadores.</strong> Si quieres guardar varios artículos de tu interés sin perderlos, 
				puedes enviarlos a tus marcadores para saber siempre dónde quedaron esos artículos tan importantes para tí.
			</p>
			<p class="help-block" style="text-align:justify;">
				<i class="fa fa-globe"></i> <strong>Curriculum Vitae.</strong> Como usuario de la revista tendrás un perfil donde podrás colocar todo lo que desees sobre tí, 
				así todas las personas en el mundo podrán ver tu trayectoria y saber más acerca de los autores de las investigaciones.
			</p>
			<p>
				<a href="<?php echo base_url().index_page(); ?>/login">¡Ya tengo una cuenta!</a>
			</p>
		</div>
		<div class="col-lg-7 col-lg-offset-1 col-md-7">
			<form role="form" action="#" method="post" id="form-register-user" autocomplete="off">
				<div class="form-group">
					<label for="usr_nombre">Nombre</label>
					<input type="text" class="form-control" name="usr_nombre" id="usr_nombre" placeholder="Tu nombre completo" pattern="^[\w\W\-\_\.]{5,150}$" required/>
					<p class="help-block">Debes escribir tu nombre completo con apellidos en éste campo. El mínimo son 5 y el máximo 150 caracteres.</p>
				</div>
				<div class="form-group">	
					<label for="usr_email">Correo electrónico</label>
					<input type="text" class="form-control" name="usr_email" id="usr_email" placeholder="Tu correo electrónico del ITM" pattern="[\w\-\.]{1,}@((itmorelia.edu.mx)|(tecmor.mx))$" required/>
					<p class="help-block">Necesitas contar con un correo electrónico del ITM, por ejemplo: "micorreo@itmorelia.edu.mx" o bien "micorreo@tecmor.mx", si aún no cuentas con él solicitalo en el centro de cómputo.</p>
				</div>
				<div class="form-group">
					<label for="usr_password">Contraseña</label>
					<input type="password" class="form-control" name="usr_password" id="usr_password" placeholder="Tu contraseña" pattern="^[\W\w]{5,100}$" required/>
					<p class="help-block">Tu contraseña para iniciar sesión en la revista. La contraseña debe de tener por lo menos 5 caracteres.</p>
				</div>
				<div class="form-group">
					<label for="usr_rep_password">Repite la contraseña</label>
					<input type="password" class="form-control" name="usr_rep_password" id="usr_rep_password" placeholder="Repite la contraseña anterior" required/>
					<p class="help-block">Para asegurarnos de que has introducido la contraseña que deseas necesitas escribirla de nuevo aquí.</p>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-success" id="btn-submit"><i class="fa fa-sign-in"></i> Registrar</button>
					<img src="<?php echo base_url('images'); ?>/loading.gif" alt="loading" id="loading-img"/>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-info">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="md-title">Confirmación de la cuenta</h4>
			</header>
			<section class="modal-body">
				<p id="modal-info-content"></p>
			</section>
			<footer class="modal-footer">
				<a href="<?php echo base_url(); ?>" class="btn btn-default">Regresar al inicio</a>
			</footer>
		</div>
	</div>
</div>