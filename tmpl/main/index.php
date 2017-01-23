<div class="header">
	<div class="container">
		<div class="hidden-xs col-sm-2 col-md-2 col-lg-2 text-center">
		</div>
		<div class="col-xs-12">
			<a href="/"><h3><?=SITETITLE?></h3></a>
		</div>
		<div style="clear:both;"></div>
		<?=load_module("lang");?>
	</div>
</div>
<div class="main container">
	<?php if($main -> view == 'main'){?>
		<?=load_module("top-slider")?>
	<?php } ?>
	<div class="content">
			<?php $main -> get_content(); ?>
	</div>
</div>
<div class="container-fluid footer">
<address>
	<h2 class="hidden-xs"><?=SITETITLE?></h2>
</address>
</div>
