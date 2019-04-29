<?php 
include 'header.php';
$obj_admins->id = $_SESSION['admin_id'];
$profile_data = $obj_admins->readone_profile();
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Profile</li>
      </ol>
	  <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-lock"></i> Profile <span class="pull-right"><a href="javascript:void(0);"  data-toggle="modal" data-target="#saasappoint_change_email_modal">Want to change email?</a></span></div>
        <div class="card-body">
      <div class="row">
		<div class="col-md-12">
		  <form name="saasappoint_profile_form" id="saasappoint_profile_form" method="post">
			  <input type='hidden' id="saasappoint-profile-admin-id-hidden" name="saasappoint-profile-admin-id-hidden" value="<?php echo $_SESSION['admin_id']; ?>" />
			  <div class="form-group">
				<div class="saasappoint-image-upload">
					<div class="saasappoint-image-edit-icon">
						<input type='hidden' id="saasappoint-image-upload-file-hidden" name="saasappoint-image-upload-file-hidden" />
						<input type='file' id="saasappoint-image-upload-file" accept=".png, .jpg, .jpeg" />
						<label for="saasappoint-image-upload-file"></label>
					</div>
					<div class="saasappoint-image-preview">
						<div id="saasappoint-image-upload-file-preview" style="<?php if($profile_data['image'] != '' && file_exists("../includes/images/".$profile_data['image'])){ echo "background-image: url(".SITE_URL."includes/images/".$profile_data['image'].");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-avatar.jpg);"; } ?>">
						</div>
					</div>
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-5">
					<label class="control-label">First Name</label>
					<input class="form-control" id="saasappoint_profile_firstname" name="saasappoint_profile_firstname" type="text" value="<?php echo $profile_data['firstname']; ?>" placeholder="Enter First Name" />
				</div>
				<div class="col-md-4">
					<label class="control-label">Last Name</label>
					<input class="form-control" id="saasappoint_profile_lastname" name="saasappoint_profile_lastname" type="text" value="<?php echo $profile_data['lastname']; ?>" placeholder="Enter Last Name" />
				</div>
				<div class="col-md-3">
					<label class="control-label">Phone</label>
					<input class="form-control" id="saasappoint_profile_phone" name="saasappoint_profile_phone" type="text" value="<?php echo $profile_data['phone']; ?>" placeholder="Enter Phone" />
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-12">
					<label class="control-label">Address</label>
					<textarea class="form-control" id="saasappoint_profile_address" name="saasappoint_profile_address" rows="1" placeholder="Enter Address" ><?php echo $profile_data['address']; ?></textarea>
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">City</label>
					<input class="form-control" id="saasappoint_profile_city" name="saasappoint_profile_city" type="text" value="<?php echo $profile_data['city']; ?>" placeholder="Enter City" />
				</div>
				<div class="col-md-6">
					<label class="control-label">State</label>
					<input class="form-control" id="saasappoint_profile_state" name="saasappoint_profile_state" type="text" value="<?php echo $profile_data['state']; ?>" placeholder="Enter State" />
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Zip</label>
					<input class="form-control" id="saasappoint_profile_zip" name="saasappoint_profile_zip" type="text" value="<?php echo $profile_data['zip']; ?>" placeholder="Enter Zip" />
				</div>
				<div class="col-md-6">
					<label class="control-label">Country</label>
					<input class="form-control" id="saasappoint_profile_country" name="saasappoint_profile_country" type="text" value="<?php echo $profile_data['country']; ?>" placeholder="Enter Country" />
				</div>
			  </div>
			  <a class="btn btn-success btn-block saasappoint_update_profile_btn" href="javascript:void(0);">Update Profile</a>
		 </form>
       </div>
		</div>
        <div class="card-footer small text-muted"></div>
      </div>
	 </div>
	 
	 <!-- Change email modal -->
	 <div class="modal fade" id="saasappoint_change_email_modal">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Change Email</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<form name="saasappoint_change_profile_email_form" id="saasappoint_change_profile_email_form" method="post">
						<div class="row m-2">
							<div class="col-md-9 p-1">
								<input type="text" class="form-control" placeholder="Enter new email" name="saasappoint_change_profile_email" id="saasappoint_change_profile_email" />
							</div>
							<div class="col-md-3 p-1">
								<a href="javascript:void(0)" class="btn btn-success w-100" id="saasappoint_change_profile_email_btn">Change</a>
							</div>
						</div>
					</form>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer"></div>
			</div>
		</div>
	</div>
<?php include 'footer.php'; ?>