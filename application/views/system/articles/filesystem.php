<div class="modal fade" id="modal-file-upload">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="title-dnd">Administrador de Archivos</h4>
			</header>
			<section class="modal-body">
				<div class="row">
					<div class="col-lg-3">
						<div class="panel panel-default">
							<div class="panel-heading"><i class="fa fa-folder-open"></i> <span id="selected-folder">Todas las Carpetas</span> <a href="#" class="pull-right hidden" id="back-folder" title="Volver atrás"><i class="fa fa-arrow-left"></i></a></div>
							<div class="list-group" id="list-directories-zone"></div>
							<div class="panel-footer">
								<div class="input-group">
									<input type="text" class="form-control" id="new-folder-name" placeholder="Crear una carpeta"/>
									<span class="input-group-btn">
										<button type="button" class="btn btn-default" id="add-new-folder" title="Agregar nueva carpeta"><i class="fa fa-plus-circle"></i></button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-9">
						<div id="drop-files-zone">
							<ul id="list-files-zone"></ul>
						</div>
						<p class="help-block margin-0">Arrastre los archivos en la caja que se encuentra arriba ó pulse el botón "seleccionar archivos" para subir nuevos documentos.</p>
					</div>
				</div>
			</section>
			<footer class="modal-footer">
				<input type="file" class="hidden" id="the-file-selector" multiple/>
				<button type="button" class="btn btn-default" id="file-selector-btn"><i class="fa fa-upload"></i> Seleccionar archivos</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</footer>
		</div>
	</div>
</div>

<script type="text/javascript">
var base_url = '<?php echo base_url().index_page(); ?>';
</script>