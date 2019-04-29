<?php 
include 'c_header.php';
include(dirname(dirname(__FILE__))."/classes/class_customers.php");
$obj_customers = new saasappoint_customers();
$obj_customers->conn = $conn;
$obj_customers->id = $_SESSION['customer_id'];
$profile_data = $obj_customers->readone_customer();
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active">Profil</li>
      </ol>
	  <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-lock"></i> Profil <span class="pull-right"><a href="javascript:void(0);"  data-toggle="modal" data-target="#saasappoint_change_email_modal">Ingin Mengemaskini Alamat Emel?</a></span></div>
        <div class="card-body">
      <div class="row">
		<div class="col-md-12">
		  <form name="saasappoint_profile_form" id="saasappoint_profile_form" method="post">
			  <input type='hidden' id="saasappoint-profile-admin-id-hidden" name="saasappoint-profile-admin-id-hidden" value="<?php echo $_SESSION['customer_id']; ?>" />
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
				<div class="col-md-6">
					<label class="control-label">Nama Pertama</label>
					<input class="form-control" id="saasappoint_profile_firstname" name="saasappoint_profile_firstname" type="text" value="<?php echo $profile_data['firstname']; ?>" placeholder="Nama Pertama" />
				</div>
				<div class="col-md-6">
					<label class="control-label">Nama Terakhir</label>
					<input class="form-control" id="saasappoint_profile_lastname" name="saasappoint_profile_lastname" type="text" value="<?php echo $profile_data['lastname']; ?>" placeholder="Nama Terakhir" />
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Telefon</label>
					<input class="form-control" id="saasappoint_profile_phone" name="saasappoint_profile_phone" type="text" value="<?php echo $profile_data['phone']; ?>" placeholder="Telefon" />
				</div>
				<div class="col-md-6">
					<label class="control-label">Alamat</label>
					<textarea class="form-control" id="saasappoint_profile_address" name="saasappoint_profile_address" rows="1" placeholder="Enter Address" ><?php echo $profile_data['Alamat']; ?></textarea>
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Bandar</label>
					<input class="form-control" id="saasappoint_profile_city" name="saasappoint_profile_city" type="text" value="<?php echo $profile_data['city']; ?>" placeholder="Bandar" />
				</div>
				<div class="col-md-6">
					<label class="control-label">Negeri</label>
					<input class="form-control" id="saasappoint_profile_state" name="saasappoint_profile_state" type="text" value="<?php echo $profile_data['state']; ?>" placeholder="Negeri" />
				</div>
			  </div>
			  <div class="form-group row">
				<div class="col-md-6">
					<label class="control-label">Poskod</label>
					<input class="form-control" id="saasappoint_profile_zip" name="saasappoint_profile_zip" type="text" value="<?php echo $profile_data['zip']; ?>" placeholder="Poskod" />
				</div>
				<div class="col-md-6">
					<label class="control-label">Negara</label>
					<input class="form-control" id="saasappoint_profile_country" name="saasappoint_profile_country" type="text" value="<?php echo $profile_data['country']; ?>" placeholder="Negara" />
				</div>
			  </div>
			  <a class="btn btn-success btn-block saasappoint_update_profile_btn" href="javascript:void(0);">Kemaskini Profil</a>
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
<?php include 'c_footer.php'; ?>