	</div></div>
	<script type="text/javascript">
		var path = "<?php echo base_url().index_page(); ?>";
	</script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('libraries'); ?>/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('scripts/front'); ?>/revista.js"></script>
	<?php if(isset($scripts)): ?>
	<?php foreach($scripts as $script): ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php endforeach; ?>
	<?php endif; ?>
	<!--facebook api -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1&appId=660426037349263";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!--twitter api-->
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<!--google+ api-->
	<script type="text/javascript">
	  window.___gcfg = {lang: 'es'};
	  (function() {
	    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
	    po.src = 'https://apis.google.com/js/platform.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
	<!--linkedin api-->
	<script src="//platform.linkedin.com/in.js" type="text/javascript">
	 lang: es_ES
	</script>
</body>
</html>