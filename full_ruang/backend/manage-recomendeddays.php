<?php 
include 'header.php';
$saasappoint_date_format = $obj_settings->get_option('saasappoint_date_format');
$time_format = $obj_settings->get_option('saasappoint_time_format');
if($time_format == "24"){
	$saasappoint_time_format = "H:i";
}else{
	$saasappoint_time_format = "h:i A";
}
$saasappoint_datetime_format = $saasappoint_date_format." ".$saasappoint_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/schedule.php">Schedule</a>
        </li>
        <li class="breadcrumb-item active">Manage Recomended Days</li>
      </ol>
	  <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-calendar-o"></i> Recomended Days List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-blockoff-modal"><i class="fa fa-plus"></i> Add Recomended Days</a>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_blockoff_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Title</th>
				  <th>Date Period</th>
				  <th>Time Period</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$all_block_off = $obj_block_off->readall_block_off();
				$i = 1;
				if(mysqli_num_rows($all_block_off)>0){
					while($block_off = mysqli_fetch_array($all_block_off)){ 
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php if(strlen($block_off['title']) < 30){ echo ucfirst($block_off['title']); }else{ echo substr(ucfirst($block_off['title']), 0, 30)."..."; } ?></td>
							<td><?php echo "From ".date($saasappoint_date_format, strtotime($block_off['from_date']))." to ".date($saasappoint_date_format, strtotime($block_off['to_date'])); ?></td>
							<td><?php if($block_off['blockoff_type'] == "custom"){ echo "From ".date($saasappoint_time_format, strtotime($block_off['from_time']))." to ".date($saasappoint_time_format, strtotime($block_off['to_time'])); }else{ echo "FullDay"; } ?></td>
							<td>
								<label class="saasappoint-toggle-switch">
								  <input type="checkbox" data-id="<?php echo $block_off['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_blockoff_status" <?php if($block_off['status'] == "Y"){ echo "checked"; } ?> />
								  <span class="saasappoint-toggle-switch-slider"></span>
								</label>
							</td>
							<td>
								<a href="javascript:void(0);" class="btn btn-primary saasappoint-white btn-sm saasappoint-update-blockoffmodal" data-id="<?php echo $block_off['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> &nbsp; 
								<a href="javascript:void(0);" class="btn btn-danger saasappoint-white btn-sm saasappoint_delete_blockoff_btn" data-id="<?php echo $block_off['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
							</td>
						</tr>
						<?php 
						$i++;
					} 
				} 
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint-add-blockoff-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-blockoff-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-blockoff-modal-label">Add Recomended Days</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_blockoff_form" id="saasappoint_add_blockoff_form" method="post">
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="saasappoint_blockofftitle">Recomended Days Title</label>
					<input class="form-control" id="saasappoint_blockofftitle" name="saasappoint_blockofftitle" type="text" placeholder="Enter Recomended Days Title" />
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-md-6">
					<label for="saasappoint_blockoff_fromdate">From Date</label>
					<input class="form-control" id="saasappoint_blockoff_fromdate" name="saasappoint_blockoff_fromdate" type="date" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="saasappoint_blockoff_todate">To Date</label>
					<input class="form-control" id="saasappoint_blockoff_todate" name="saasappoint_blockoff_todate" type="date" />
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="saasappoint_blockoff_type">Recomended Days Type</label>
					<div style="visibility:hidden;">
						<label><input type="radio" class="saasappoint_blockoff_type" name="saasappoint_blockoff_type" value="fullday" checked> FullDay</label>
					</div>
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="saasappoint_blockoff_status">Recomended Days Status</label>
					<div>
						<label class="text-success"><input type="radio" class="saasappoint_blockoff_status" name="saasappoint_blockoff_status" value="Y" checked> Activate</label> &nbsp; <label class="text-danger"><input type="radio" class="saasappoint_blockoff_status" name="saasappoint_blockoff_status" value="N"> Deactivate</label>
					</div>
				  </div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_blockoff_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-blockoff-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-blockoff-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-blockoff-modal-label">Update Recomended Days</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-blockoff-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_blockoff_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>