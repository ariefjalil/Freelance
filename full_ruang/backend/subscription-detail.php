<?php 
include 's_header.php';
if(!isset($_GET['bid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
	</script>
	<?php 
	exit;
} else if(!is_numeric($_GET['bid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/businesses.php";
	</script>
	<?php 
	exit;
} 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
		<li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php">Businesses</a>
        </li>
        <li class="breadcrumb-item active">Subscription Detail</li>
      </ol>
	  <div class="m-5">
		<center>
			<div class="row">
				<div class="col-md-4 card m-3 p-0 saasappoint-boxshadow">
				  <p class="mt-4"><i class="fa fa-fw fa-comment-o fa-5x text-info"></i></p>
				  <div class="card-body">
					<a href="<?php echo SITE_URL; ?>backend/s-sms-credit-history.php?bid=<?php echo $_GET['bid']; ?>" class="btn btn-link p-0"><i class="fa fa-share" aria-hidden="true"></i> SMS Credit Purchase History</a>
				  </div>
				</div>
				<div class="col-md-4 card m-3 p-0 saasappoint-boxshadow">
				  <p class="mt-4"><i class="fa fa-fw fa-rss fa-5x text-info"></i></p>
				  <div class="card-body">
					<a href="<?php echo SITE_URL; ?>backend/s-subscription-history.php?bid=<?php echo $_GET['bid']; ?>" class="btn btn-link p-0"><i class="fa fa-share" aria-hidden="true"></i> Subscription History</a>
				  </div>
				</div>
			</div>
		</center>
	  </div>
<?php include 's_footer.php'; ?>