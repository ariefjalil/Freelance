<?php 
/** Include class files and create their objects to call functions **/
include(dirname(dirname(dirname(__FILE__)))."/constants.php"); 
include(dirname(dirname(dirname(__FILE__)))."/classes/class_connection.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_businesses.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

$obj_database = new saasappoint_database();
$conn = $obj_database->connect();

$obj_businesses = new saasappoint_businesses();
$obj_businesses->conn = $conn;

$obj_settings = new saasappoint_settings();
$obj_settings->conn = $conn;

$perpage = 9;
$page = 1;
$keyword = "";

if(!empty($_POST["pageid"])) {
	$page = $_POST["pageid"];
}
if(!empty($_POST["keyword"])) {
	$keyword = $_POST["keyword"];
}

$start = ($page-1)*$perpage;
if($start < 0){ $start = 0; }

$get_all_active_businesses = $obj_businesses->get_all_active_businesses_by_limit($start, $perpage, $keyword);
$i = 1; $j = 1;
$count_businesses = mysqli_num_rows($get_all_active_businesses);

$total_businesses = $obj_businesses->get_countof_all_active_businesses($keyword); 
if(empty($_POST["rowcount"])) {
	$_POST["rowcount"] = $total_businesses;
}
$sa_rowcount = $_POST["rowcount"];

if($count_businesses>0){ 
	?>
	<div class="row text-center saasappoint-business-card <?php if($j != 1){ echo "pt-5"; } ?>">
		<?php 
		while($business = mysqli_fetch_array($get_all_active_businesses)){ 
			$obj_settings->business_id = $business["id"];
			?>
			<div class="col-md-4 mt-4">
				<div class="card">
					<div class="card-block">
						<?php $saasappoint_company_logo = $obj_settings->get_option("saasappoint_company_logo"); if($saasappoint_company_logo != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/includes/images/".$saasappoint_company_logo)){ ?>
							<div class="card-img ">
								<img class="img-responsive" height="150" src="<?php echo SITE_URL; ?>includes/images/<?php echo $saasappoint_company_logo; ?>">
							</div>
						<?php } ?>
						<div class="card-title">
							<h4><?php echo ucwords($obj_settings->get_option("saasappoint_company_name")); ?></h4>
						</div>
						<div class="card-text">
							<p><i class="fa fa-user"></i> <?php echo ucwords($business["firstname"]." ".$business["lastname"]); ?></p>
							<p><i class="fa fa-envelope"></i> <?php echo ucwords($obj_settings->get_option("saasappoint_company_email")); ?></p>
							<p><i class="fa fa-phone"></i> <?php echo ucwords($obj_settings->get_option("saasappoint_company_phone")); ?></p>	
							
							<?php if($business["pointstatus"]=='Y'){ ?>
							<p>Performance Points: <b class="text-primary"><?php echo $business["points"]; ?> <i class="fa fa-trophy"></i></b></p>
							<?php } ?>
						</div>
						<hr>
						<div class="card-footer">
							<ul class="list-inline">
								<li><i class="fa fa-map-marker"></i><?php echo ucwords($obj_settings->get_option("saasappoint_company_address")).", ".ucwords($obj_settings->get_option("saasappoint_company_city")).", ".ucwords($obj_settings->get_option("saasappoint_company_state")).", ".ucwords($obj_settings->get_option("saasappoint_company_country"))." - ".ucwords($obj_settings->get_option("saasappoint_company_zip")); ?></li>
								<?php 
								$saasappoint_location_selector_status = $obj_settings->get_option("saasappoint_location_selector_status"); 
								if($saasappoint_location_selector_status == "N" || $saasappoint_location_selector_status == ""){ 
									?>
									<li class="pull-right"><a href="<?php echo SITE_URL; ?>?bid=<?php echo base64_encode($business["id"]); ?>"><i class="fa fa-calendar"></i> Book Now</a></li>
									<?php 
								}else{ 
									?>
									<li class="pull-right"><a href="<?php echo SITE_URL; ?>location-selector.php?bid=<?php echo base64_encode($business["id"]); ?>"><i class="fa fa-calendar"></i> Book Now</a></li>
									<?php 
								} 
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<?php 
			$i++; $j++;
		} 
		?>
	</div>
	<input type="hidden" name="rowcount" id="rowcount" value="<?php echo $sa_rowcount; ?>" />
	<nav class="mt-5">
	  <ul id="saasappoint-bd-pagination" class="pagination justify-content-center pagination-lg">
		<?php 
		$output = '';
		$count = $sa_rowcount;
		if($perpage != 0){
			$pages  = ceil($count/$perpage);
		}
		if($pages>1) {
			if(($page-3)>0) {
				if($page == 1){
					$output = $output . "<li class='page-item active' data-id='1'><a class='page-link' href='javascript:void(0)'>1</a></li>";
				}else{
					$output = $output . '<li class="page-item saasappoint_perpage_pagination_link" data-id="1"><a href="javascript:void(0)" class="page-link">1</a></li>';
				}
			}
			if(($page-3)>1) {
					$output = $output . "<li class='page-item disabled' data-id='0'><a class='page-link' href='javascript:void(0)'>...</a></li>";
			}
			
			for($i=($page-2); $i<=($page+2); $i++)	{
				if($i<1){ continue; }
				if($i>$pages){ break; }
				if($page == $i){
					$output = $output . "<li class='page-item active' data-id='".$i."'><a class='page-link' href='javascript:void(0)'>".$i."</a></li>";
				}else{				
					$output = $output . '<li class="page-item saasappoint_perpage_pagination_link" data-id="'.$i.'"><a href="javascript:void(0)" class="page-link">'.$i.'</a></li>';
				}
			}
			
			if(($pages-($page+2))>1) {
				$output = $output . "<li class='page-item disabled' data-id='0'><a class='page-link' href='javascript:void(0)'>...</a></li>";
			}
			if(($pages-($page+2))>0) {
				if($page == $pages){
					$output = $output . "<li class='page-item active' data-id='".$pages."'><a class='page-link' href='javascript:void(0)'>".$pages."</a></li>";
				}else{				
					$output = $output . '<li class="page-item saasappoint_perpage_pagination_link" data-id="'.$pages.'"><a href="javascript:void(0)" class="page-link">'.$pages.'</a></li>';
				}
			}			
		} 
		echo $output;
		?>
	  </ul>
	</nav>
	<?php 
}else{ 
	?>
	<div class="saasappoint-bsearch-result-heading ">
		<h3>None of business available to book an appointment.</h3>
	</div>
	<?php 
} 