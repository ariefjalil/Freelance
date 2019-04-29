<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/category.php">Category</a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/services.php">Services</a>
        </li>
        <li class="breadcrumb-item active">Addons</li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> Addons List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-addon-modal"><i class="fa fa-plus"></i> Add Addon</a>
		  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_addons_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Name</th>
				  <th>Category</th>
				  <th>Service</th>
				  <th>Rate</th>
				  <th>Multiple Qty.</th>
				  <th>Status</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
			</tbody>
           </table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	<div class="modal fade" id="saasappoint-add-addon-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-addon-modal-label">Add Addon</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_addon_form" id="saasappoint_add_addon_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_addonname">Addon Name</label>
				<input class="form-control" id="saasappoint_addonname" name="saasappoint_addonname" type="text" placeholder="Enter Addon Name" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_addonrate">Addon Rate</label>
				<input class="form-control" id="saasappoint_addonrate" name="saasappoint_addonrate" type="text" placeholder="Enter Addon Rate" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_addondescription">Addon Description</label>
				<textarea class="form-control" id="saasappoint_addondescription" name="saasappoint_addondescription" placeholder="Enter Addon Description"></textarea>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_addonmultipleqty">Multiple Qty.</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_addonmultipleqty" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_addonmultipleqty" value="N"> Deactivate</label>
				</div>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_addonimage">Addon Image</label>
				<div class="saasappoint-image-upload">
					<div class="saasappoint-image-edit-icon">
						<input type='hidden' id="saasappoint-image-upload-file-hidden" name="saasappoint-image-upload-file-hidden" />
						<input type='file' id="saasappoint-image-upload-file" accept=".png, .jpg, .jpeg" />
						<label for="saasappoint-image-upload-file"></label>
					</div>
					<div class="saasappoint-image-preview">
						<div id="saasappoint-image-upload-file-preview" style="background-image: url(<?php echo SITE_URL; ?>includes/images/default-service.png);">
						</div>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_addonstatus">Addon Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_addonstatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_addonstatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_addon_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-addon-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-addon-modal-label">Update Addon</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-addon-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_addon_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- View Modal-->
	<div class="modal fade" id="saasappoint-view-addon-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-view-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-view-addon-modal-label">Addon Detail</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-view-addon-modal-body">
			
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>