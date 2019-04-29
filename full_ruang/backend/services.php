<?php include 'header.php'; ?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/category.php">Category</a>
        </li>
        <li class="breadcrumb-item active">Services</li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> Service List
		  <a class="btn btn-success btn-sm saasappoint-white pull-right" data-toggle="modal" data-target="#saasappoint-add-service-modal"><i class="fa fa-plus"></i> Add Service</a>
		  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="saasappoint_services_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th>#</th>
				  <th>Title</th>
				  <th>Category</th>
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
	<div class="modal fade" id="saasappoint-add-service-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-add-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-add-service-modal-label">Add Service</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="saasappoint_add_service_form" id="saasappoint_add_service_form" method="post">
			  <div class="form-group">
				<label for="saasappoint_servicetitle">Service Title</label>
				<input class="form-control" id="saasappoint_servicetitle" name="saasappoint_servicetitle" type="text" placeholder="Enter Service Title" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_serviceduration">Service Duration [in minutes]</label>
				<input class="form-control" id="saasappoint_serviceduration" name="saasappoint_serviceduration" type="number" placeholder="Enter Service Duration" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_servicepbefore">Service Padding Before [in minutes]</label>
				<input class="form-control" id="saasappoint_servicepbefore" name="saasappoint_servicepbefore" type="number" placeholder="Enter Service Padding Before" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_servicepafter">Service Padding After [in minutes]</label>
				<input class="form-control" id="saasappoint_servicepafter" name="saasappoint_servicepafter" type="number" placeholder="Enter Service Padding After" />
			  </div>
			  <div class="form-group">
				<label for="saasappoint_servicedescription">Service Description</label>
				<textarea class="form-control" id="saasappoint_servicedescription" name="saasappoint_servicedescription" placeholder="Enter Service Description"></textarea>
			  </div>
			  <div class="form-group">
				<label for="saasappoint_serviceimage">Service Image</label>
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
				<label for="saasappoint_servicestatus">Service Status</label>
				<div>
					<label class="text-success"><input type="radio" name="saasappoint_servicestatus" value="Y" checked> Activate</label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="saasappoint_servicestatus" value="N"> Deactivate</label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_add_service_btn" class="btn btn-primary" href="javascript:void(0);">Add</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Update Modal-->
	<div class="modal fade" id="saasappoint-update-service-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-update-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-update-service-modal-label">Update Service</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-update-service-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_service_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update</a>
		  </div>
		</div>
	  </div>
	</div>
	 <!-- View Modal-->
	<div class="modal fade" id="saasappoint-view-service-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-view-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-view-service-modal-label">Service Detail</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-view-service-modal-body">
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
	 <!-- Schedule Modal-->
	<div class="modal fade" id="saasappoint-schedule-service-modal" tabindex="-1" role="dialog" aria-labelledby="saasappoint-schedule-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="saasappoint-schedule-service-modal-label">Service Schedule</h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">×</span>
			</button>
		  </div>
		  <div class="modal-body saasappoint-schedule-service-modal-body">
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
			<a id="saasappoint_update_service_schedule_btn" data-id="" class="btn btn-primary" href="javascript:void(0);">Update Service Schedule</a>
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>