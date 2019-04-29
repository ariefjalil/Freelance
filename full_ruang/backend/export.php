<?php include 'header.php';
$export_current_date = date("Y-m-d", $currDateTime_withTZ);
$export_current_date_str = strtotime($export_current_date);
 ?>
	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
        <li class="breadcrumb-item">
			<a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Export</li>
	</ol>
	<!-- Export Services Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-th-list"></i> Export Services</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Categories</h5>
					<select class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-actions-box='true' id="saasappoint_export_categories" multiple>
						<?php
						$all_categories = $obj_categories->get_all_categories_name();
						while($category = mysqli_fetch_assoc($all_categories)){
							echo "<option value='".$category['id']."'>".$category['cat_name']."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Services</h5>
					<select class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-actions-box='true' id="saasappoint_export_services" multiple>
						<?php
						$all_services = $obj_services->get_all_services_title();
						while($service = mysqli_fetch_assoc($all_services)){
							echo "<option value='".$service['id']."'>".$service['title']."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Addons</h5>
					<select class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" data-actions-box='true' id="saasappoint_export_addons" multiple>
						<?php
						$all_addons = $obj_addons->get_all_addons_title();
						while($addon = mysqli_fetch_assoc($all_addons)){
							echo "<option value='".$addon['id']."'>".$addon['title']."</option>";
						}
						?>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mt-4">
					<a href="javascript:void(0)" class="btn btn-success saasappoint_export_services_btn"><i class="fa fa-cloud-upload"></i> Export</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Export Appointments Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-calendar-check-o"></i> Export Appointments</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>From</h5>
					<input class="form-control" id="saasappoint_export_appt_from" name="saasappoint_export_appt_from" value="<?php echo date("Y-m-d", strtotime("-1 months", $export_current_date_str)); ?>" type="date">
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>To</h5>
					<input class="form-control" id="saasappoint_export_appt_to" name="saasappoint_export_appt_to" value="<?php echo $export_current_date; ?>" type="date">
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Appointments</h5>
					<select class="form-control" id="saasappoint_export_appt_type">
						<option selected value='all'>All Appointments</option>
						<option value='registered'>Registered Customer's Appointments</option>
						<option value='guest'>Guest Customer's Appointments</option>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mt-4">
					<a href="javascript:void(0)" class="btn btn-success saasappoint_export_appt_btn"><i class="fa fa-cloud-upload"></i> Export</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Export Payments Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-money"></i> Export Payments</div>
		<div class="card-body">
			<div class="row">
				
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>From</h5>
					<input class="form-control" id="saasappoint_export_payment_from" name="saasappoint_export_appt_from" value="<?php echo date("Y-m-d", strtotime("-1 months", $export_current_date_str)); ?>" type="date">
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>To</h5>
					<input class="form-control" id="saasappoint_export_payment_to" name="saasappoint_export_appt_to" value="<?php echo $export_current_date; ?>" type="date">
				</div>
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Payments</h5>
					<select class="form-control" id="saasappoint_export_payment_type">
						<option selected value='all'>All Payments</option>
						<option value='registered'>Registered Customer's Payments</option>
						<option value='guest'>Guest Customer's Payments</option>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mt-4">
					<a href="javascript:void(0)" class="btn btn-success saasappoint_export_payment_btn"><i class="fa fa-cloud-upload"></i> Export</a>
				</div>
			</div>
		</div>
	</div>
	<!-- Export Customers Cards-->
	<div class="card mb-3">
        <div class="card-header"><i class="fa fa-users"></i> Export Customers</div>
		<div class="card-body">
			<div class="row">
				<div class="col-xl-3 col-sm-6 mb-3">
					<h5>Customers</h5>
					<select class="form-control" id="saasappoint_export_customers_type">
						<option selected value='all'>All Customers</option>
						<option value='registered'>Registered Customers</option>
						<option value='guest'>Guest Customers</option>
					</select>
				</div>
				<div class="col-xl-3 col-sm-6 mt-4">
					<a href="javascript:void(0)" class="btn btn-success saasappoint_export_customers_btn"><i class="fa fa-cloud-upload"></i> Export</a>
				</div>
			</div>
		</div>
	</div>
<?php include 'footer.php'; ?>