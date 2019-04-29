<?php 
include 's_header.php';
$business_types = $obj_business_type->readall_business_type_sadmin(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/businesses.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Business Types</li>
      </ol>
	        <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-briefcase"></i> Business Types List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-btype-modal"><i class="fa fa-plus"></i> Add Business Type</a>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_btype_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Business Type</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				while($type = mysqli_fetch_array($business_types)){ 
					?>
					<tr>
						<td><?php echo $type['id']; ?></td>
						<td><?php echo $type['business_type']; ?></td>
						<td>
							<label class="saasappoint-toggle-switch">
							  <input type="checkbox" data-id="<?php echo $type['id']; ?>" class="saasappoint-toggle-switch-input saasappoint_change_btype_status" <?php if($type['status'] == "Y"){ echo "checked"; } ?> />
							  <span class="saasappoint-toggle-switch-slider"></span>
							</label>
						</td>
						<td><a class="btn btn-primary saasappoint-white btn-sm saasappoint-update-btypemodal" data-id="<?php echo $type['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> <a data-id="<?php echo $type['id']; ?>" class="btn btn-danger saasappoint-white btn-sm saasappoint-delete-btype-btn"><i class="fa fa-fw fa-trash"></i></a></td>
					</td>
					<?php 
				} 
				?>
			  </tbody>
			</table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint-add-btype-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-btype-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-btype-modal-label">Add Subscription Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_btype_form" id="saasappoint_add_btype_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_btypename">Business Type</label>
				<input class="form-control" id="saasappoint_btypename" name="saasappoint_btypename" type="text" placeholder="e.g. Cleaning, Spa, Saloon" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_btypestatus">Business Type Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_btypestatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_btypestatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_btype_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-btype-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-btype-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-btype-modal-label">Update Subscription Plan</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-btype-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_btype_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 's_footer.php'; ?>