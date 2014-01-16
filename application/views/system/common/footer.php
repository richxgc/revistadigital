	</div>
	<script type="text/javascript">
	var path = "<?php echo base_url().index_page().'/admin'; ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('scripts/system'); ?>/system.js"></script>
	<?php if(isset($scripts)): ?>
	<?php foreach($scripts as $script): ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php endforeach; ?>
	<?php endif; ?>
</body>
</html>