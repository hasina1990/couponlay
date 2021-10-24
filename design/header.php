<div class="header-top">
	<a class="link first" href="index.php">Home</a>
	<a class="link" href="aboutus.php">About US</a>
	<a class="link" href="contact.php">Contact US</a>
	<a class="link" href="terms.php">Terms & Conditions</a>
	<div class="clear"></div>
</div>
<div class="header">
	<div class="logo" style="height:100px;"></div>
	<div class="menu">
		<div class="menu-start">
			<a class="link first" href="category.php"> Buy/sale</a>
			<a class="link" href="category.php">New project</a>
			<a class="link" href="category.php">Builder</a>
			<a class="link" href="category.php">Investor Logging</a>
			<a class="link" href="category.php">Architect</a>
			<a class="link" href="category.php">Interior designer</a>
			<a class="link" href="category.php">Land Measurement</a>
			<a class="link" href="category.php">Real Estate</a>
			<div class="clear"></div>
		</div>
		<div class="menu-end" id="services">
			<a class="menu-end-link" href="#">Services</a>
			<div id="service-panel" class="content-service">
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
				<a class="link" href="category.php">New project</a>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
<script>
jQuery("#services").hover(function(){
    jQuery('#service-panel').addClass('services-hover');
});
jQuery("#services").mouseleave(function(){
    jQuery('#service-panel').removeClass('services-hover');
});
</script>