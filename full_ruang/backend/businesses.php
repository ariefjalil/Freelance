<?php 
include 's_header.php'; 
$saasappoint_date_format = $obj_settings->get_superadmin_option('saasappoint_date_format');
$time_format = $obj_settings->get_superadmin_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format;
$all_businesses = $obj_businesses->get_all_business();
 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Businesses</li>
      </ol>
	  <div class="card border-0"><div class="card-body m-0 p-0 pb-2"><a href="<?php echo SITE_URL; ?>backend/add_business.php" class="btn btn-success btn-sm pull-right"><i class="fa fa-plus"></i> Add New Business</a></div></div>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_businesses_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Admin Name</th>
				  <th>Business Name</th>
				  <th>Business Type</th>
				  <th>Business Email</th>
				  <th>Business Phone</th>
				  <th>Registered On</th>
				  <th>Status</th>
				  <th>Visit</th>
				  <th>Points</th>
				  <th>Point Status</th>
				  <th>History</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				while($business = mysqli_fetch_array($all_businesses)){ 
					$obj_settings->business_id = $business['id']; 
					?>
					<tr>
					  <td><?php echo $business['id']; ?></td>
					  <td><?php echo ucwords($business['firstname']." ".$business['lastname']); ?></td>
					  <td><?php echo ucwords($obj_settings->get_option('saasappoint_company_name')); ?></td>
					  <td><?php echo $business['business_type']; ?></td>
					  <td><?php echo ucwords($obj_settings->get_option('saasappoint_company_email')); ?></td>
					  <td><?php echo ucwords($obj_settings->get_option('saasappoint_company_phone')); ?></td>
					  <td><?php echo date($saasappoint_datetime_format, strtotime($business['registered_on'])); ?></td>
					  <td><label class="saasappoint-toggle-switch"> <input type="checkbox" data-id="<?php echo $business['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_business_status" <?php if($business['status'] == "Y"){ echo "checked"; } ?> /> <span class="saasappoint-toggle-switch-slider"></span> </label></td>
					  <td><a target="_blank" href="<?php echo SITE_URL; ?>?bid=<?php echo base64_encode($business['id']); ?>">Visit Site</a> / <a id="saasappoint_admin_autologin" data-id="<?php echo $business['id']; ?>" href="javascript:void(0);">Dashboard</a></td>					  
					  <td><input style="width:68px;" type="text" name="bussiness_points<?php echo $business['id']; ?>" class="bussiness_points<?php echo $business['id']; ?>"  value="<?php if(isset($business['points']) && $business['points']!=''){ echo $business['points']; } ?>"><a class="btn btn-success btn-sm saasappoint_save_points" href="javascript:void(0);" data-bid="<?php echo $business['id']; ?>"><i class="fa fa-check"></i> Save</a></td>
					  <td><label class="saasappoint-toggle-switch"> <input type="checkbox" data-id="<?php echo $business['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_point_status" <?php if($business['pointstatus'] == "Y"){ echo "checked"; } ?> /> <span class="saasappoint-toggle-switch-slider"></span> </label></td>
					  <td><a class="btn btn-warning btn-sm" href="<?php echo SITE_URL; ?>backend/subscription-detail.php?bid=<?php echo $business['id']; ?>"><i class="fa fa-eye"></i></a></td>
					  <td><a class="btn btn-danger btn-sm saasappoint-delete-business-btn" href="javascript:void(0)" data-id="<?php echo $business['id']; ?>"><i class="fa fa-close"></i> Delete</a></td>
					</tr>
					<?php 
				} 
				?>
			  </tbody>
		   </table>
          </div>
        </div>
      </div>
<?php include 's_footer.php'; ?>