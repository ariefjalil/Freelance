<?php include 'header.php'; ?>
	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
        <li class="breadcrumb-item">
			<a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Embed Frontend</li>
	</ol>
	<h4 class="pl-2 pb-2 pt-2">Get embed code to show booking widget on your website.</h4>
	<h6 class="pl-2 pb-2 pt-2">[please copy below code and paste in your website]</h6>
	<!-- Embed as IFrame Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-code"></i> Embed as iframe</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-12 col-sm-6">
					<code>&lt;div id="saasappoint-embeded-iframe-div" data-url="<?php echo SITE_URL."?bid=".base64_encode($_SESSION["business_id"]); ?>"&gt; &lt;/div&gt; &lt;iframe id="saasappoint-embeded-iframe" src="" width="100%" height="2000"&gt; &lt;/iframe&gt; &lt;script&gt; var saasappoint_url = document.getElementById("saasappoint-embeded-iframe-div").getAttribute("data-url"); document.getElementById("saasappoint-embeded-iframe").setAttribute("src", saasappoint_url); &lt;/script&gt;</code>
				</div>
			</div>
		</div>
	</div>
	<!-- Embed as Button Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-code"></i> Embed as Link</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-12 col-sm-6">
					<code>&lt;a target="_blank" href="<?php echo SITE_URL."?bid=".base64_encode($_SESSION["business_id"]); ?>"&gt;Book Now&lt;/a&gt;</code>
				</div>
			</div>
		</div>
	</div>
<?php include 'footer.php'; ?>