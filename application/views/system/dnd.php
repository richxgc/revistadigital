<div class="container" id="content">
	<div class="row">
		<div class="col-lg-12">

			<h1>DND DEVELOP</h1>

		</div>
	</div>
</div>


<div class="modal fade" id="modal-file-upload">
	<div class="modal-dialog">
		<div class="modal-content">
			<header class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="title-dnd">Subir archivos desde la computadora</h4>
			</header>
			<section class="modal-body">
				<div id="drop-files-zone">
					<div id="advertising-dnd">
						<p id="file-upload-icon"><i class="fa fa-file"></i></p>
						<h2>Arrastra aquí los archivos</h2>
					</div>
					<ul id="list-files-zone">
					</ul>
				</div>
				
			</section>
			<footer class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Subir</button>
			</footer>
		</div>
	</div>
</div>

<script type="text/javascript">
var base_url = '<?php echo base_url().index_page(); ?>';
</script>