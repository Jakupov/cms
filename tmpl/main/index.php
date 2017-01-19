<div class="header">
	<div class="container">
		<div class="hidden-xs col-sm-5 col-md-5 col-lg-5 text-right">
			<a href="https://wksu.kz"><h3><?php echo TITLE; ?></h3></a>
		</div>
		<div class="hidden-xs col-sm-2 col-md-2 col-lg-2 text-center">
			<img class="logo" src="images/logo/main.png">
		</div>
		<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 text-left">
			<a href="/"><h3><?php echo SITETITLE; ?></h3></a>
		</div>
		<div style="clear:both;"></div>
		<?=load_module("lang");?>
	</div>
</div>
<div class="ztop-menu">
	<div class="container">
		<?=load_module("ztop-menu");?>
	</div>
</div>
<div class="main container">
	<?php if($main -> view == 'main'){?>
		<?=load_module("top-slider")?>
	<?php } ?>
	<div class="content">
			<?php 
	if (isset($_GET['view'])&&$_GET['view'] == 'module') {
		load_module("normativ");
	}else echo $main -> get_content(); ?>
	</div>
	<?=load_module("dtop-menu");?>
</div>
<div class="container-fluid footer">
<address>
	<h2 class="hidden-xs">М.Өтемісов атындағы Батыс Қазақстан мемлекеттік университеті</h2>
	<br />
	<h3>Қазақстан Республикасы <br />Орал қ, Достық 162</h3>
	<br />
	<h4><abbr title="Телефон/факс">51-26-32, 51-42-66</abbr> <br /> <a href="mailto:">info@wksu.kz</a></h4>
</address>
</div>